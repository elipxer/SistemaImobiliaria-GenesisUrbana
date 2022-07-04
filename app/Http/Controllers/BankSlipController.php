<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Clients;
use App\Models\Bank;
use App\Models\Parcels;
use App\Models\Companies;
use App\Models\Interprises;
use App\Models\BankSlip;
use App\Models\BankSlipReturnInfo;
use App\Models\BankSlipSend;
use App\Models\User;
use App\Models\ReturnBankSlip;
use Carbon\Carbon;
use Exception;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class BankSlipController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type!=1 && $this->user->type!=3){
                return redirect()->route('accessDenied');
            }

            return $next($request);
        });   
    }

    public function index(){
        $data=[];
        $data['bankSlipPendingSicredi']=BankSlip::where('status',2)->where('id_financial_accounts',1)->get();
        $data['financial_account_sicredi']=Bank::where('id',1)->first();
        
        $data['bankSlipPendingCaixa']=BankSlip::where('status',2)->where('id_financial_accounts',2)->get();
        $data['financial_account_caixa']=Bank::where('id',2)->first();    
        
        $data['bankSlipSend']=BankSlipSend::orderBy('add_now','desc')->get();
        return view('sendBankSlip',$data);
    }

    public function pending_bankSlip($id_financial){
        $data['financial_account']=Bank::where('id',$id_financial)->first();
        $allBankSlip=BankSlip::where('status',2)->where('id_financial_accounts',$id_financial)->get();
        $data['bankSlipPending']=[];
        foreach ($allBankSlip as $key => $bankSlip) {
            $sale=Sales::where('id',$bankSlip->id_sale)->first();
            $parcel=Parcels::where('id',$bankSlip->id_parcel)->first();
            $client=Clients::where('id',$sale->client_payment_id)->first();

            $bankSlipInfo[]=[
                'sale'=>$sale,
                'parcel'=>$parcel,
                'bankSlipInfo'=>$bankSlip,
                'client'=>$client
            ];

            $data['bankSlipPending']=$bankSlipInfo;
        }
        
        return view('pending_bankSlip',$data);
    }

    public function generateSendBankSlip(Request $request){
        $this->resetParcels();
        $idsBankSlip=$request->only(['bankSlipCheck']);
        $bankSlipInfo=explode(',',$idsBankSlip['bankSlipCheck'][0]);

        $dataBankSlip=[];
        $id_financialAccount="";
        foreach ($bankSlipInfo as $key => $bankSlipId) {
            $bankSlip=BankSlip::where('id',$bankSlipId)->first();
            $dataBankSlip['fine']=$bankSlip->fine;
            $dataBankSlip['bank_interest_rate']=$bankSlip->bank_interest_rate;
            $dataBankSlip['delay_limit']=$bankSlip->delay_limit;
            $dataBankSlip['descont']=$bankSlip->descont;

            $sale=Sales::where('id',$bankSlip->id_sale)->first();
            $company=Companies::where('id',1)->first();
            $client=Clients::where('id',$sale->client_payment_id)->first();
            $financial_account=Bank::where('id',$bankSlip->id_financial_accounts)->first();
            $id_financialAccount=$financial_account->id;
            $beneficiario=$this->beneficiario($company);
            $pagador=$this->pagador($client);

            $parcel=Parcels::where('id',$bankSlip->id_parcel)->first();
            $boleto=[];
            if($financial_account->id_bank==748){
                $boleto=$this->sicrediBankSlip($beneficiario, $pagador,$parcel,$financial_account,$sale,$dataBankSlip);
            }else if($financial_account->id_bank==104){
                $boleto=$this->caixaBankSlip($beneficiario, $pagador, $parcel,$financial_account,$sale,$dataBankSlip);
            }
            
            $boletos[]=$boleto;

            $sendArray = [
                'beneficiario' => $beneficiario,
                'idremessa' => rand(1000000,9999999),
                'carteira' => $financial_account->wallet,
                'agencia' => $financial_account->bank_agency,
                'conta' => $financial_account->account,
                'codigoCliente' => $financial_account->id_recipient,
            ];

            if($financial_account->id_bank==748){
                $send = new \Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Sicredi($sendArray);
            }else if($financial_account->id_bank==104){
                $send=new \Eduardokum\LaravelBoleto\Cnab\Remessa\Cnab400\Banco\Caixa($sendArray);
            }
            
            if(count($boletos)==1){
                $send->addBoleto($boletos[0]);
            }else{
                $send->addBoletos($boletos);
            }

            $bankSlip->status=1;
            $bankSlip->save();
            $parcel->idBankPayment=$bankSlip->id_financial_accounts;
            $parcel->save();
        }

        $send->gerar();
        $month=date('m');
        if($month <10){
            $month=str_replace('0','',$month);
        }
        
        if($month==10){
            $month="O";
        }

        if($month==11){
            $month="N";
        }

        if($month==12){
            $month="D";
        }

        $date=$month.date('d');
        $fileName='54725'.$date;
        $ext=$this->returnExtBankSlip();
        $path=$path=storage_path()."/app/public/bankSlip/sendFile/".$fileName.$ext;
        $send->save($path);

        $allBankSlipSend=BankSlipSend::all();
        foreach ($allBankSlipSend as $key => $bankSlip) {
            $bankSlipSend=BankSlipSend::where('id',$bankSlip->id)->first();
            $bankSlipSend->add_now=0;
            $bankSlipSend->save();
        }

        $bankSlipSend=new BankSlipSend();
        $bankSlipSend->ids_bankSlip=$idsBankSlip['bankSlipCheck'][0];
        $bankSlipSend->date=date('Y-m-d');
        $bankSlipSend->id_financial_accounts=$id_financialAccount;
        $bankSlipSend->add_now=1;
        $bankSlipSend->send_file_name=$fileName.$ext;
        $bankSlipSend->save();

        return redirect()->route('sendBankSlip');
    }

    private function returnExtBankSlip(){
        $bankSend=BankSlipSend::where('date',date('Y-m-d'))->count();
        $ext="";
        if($bankSend==0){
            $ext=".CRM";
        }else{
            $ext=".CR".$bankSend;
        }

        return $ext;
    }

    public function returnBankSlip(Request $request){
        $data['returnBankSlip']=[];
        $allBankSlipReturn=DB::table('bank_slip_return')->get();

        $data['startDate']="";
        $data['finalDate']="";
        $data['order']="1";

        if($request->has(['startDate','finalDate','order'])){
            $startDate=$request->input('startDate');
            $finalDate=$request->input('finalDate');

            $order=$request->input('order');
            $orderDescription=$order=="1"?'DESC':'ASC';

            if($request->filled(['startDate','finalDate'])){
                $allBankSlipReturn=DB::table('bank_slip_return')
                    ->whereBetween('date',[$startDate,$finalDate])
                    ->orderBy('date',$orderDescription)->get();
            }

            $data['order']=$order;
            $data['startDate']=$startDate;
            $data['finalDate']=$finalDate;
        }

        foreach ($allBankSlipReturn as $key => $bankSlip) {
            $financial_account=Bank::where('id',$bankSlip->id_financial_account)->first();
            $bankSlipInfo[]=[
                'bankSlip'=>$bankSlip,
                'financialAccount'=>$financial_account,
            ];

            $data['returnBankSlip']=$bankSlipInfo;
        }
        return view('return_bankSlip',$data);
    }

    public function returnBankSlipInfo($id_bankSlipReturn,$view=true){
        $data['returnBankSlipInfo']=BankSlipReturnInfo::where('id_bankSlipReturn',$id_bankSlipReturn)->get();
        
        foreach ($data['returnBankSlipInfo'] as $key => $returnBankSlip) {
            $bankReturn=BankSlipReturnInfo::where('id',$returnBankSlip->id)->first();
            if($bankReturn->view==0){
                $our_number=substr($returnBankSlip->our_number, 3, -1);
                $deadline=$returnBankSlip->deadLine;
                $parcel=Parcels::where('our_number',$our_number)->where('date',$deadline)->first();
               
                if($returnBankSlip->ocorrency==6 && $parcel != null){
                    $this->changeStatusParcel($parcel->id,$returnBankSlip->amount_received,$returnBankSlip->dateOccorency);
                }
                $bankReturn->view=1;
                $bankReturn->save();
            }
        }
        
        if($view){
            return view('allReturnBankSlipInfo',$data);
        }
    }

    private function changeStatusParcel($idParcel,$amount_received,$occorency_date){
        $parcel=Parcels::where('id',$idParcel)->first();
        $parcel->status=1;
        $parcel->pad_value=$amount_received;
        $parcel->pad_date=$occorency_date;
        $parcel->pad_description="Pago por boleto";
        $parcel->save();

    }

   public function addReturnBankSlipFile(Request $request){
        if($request->has('returnFile')){
            $data=$request->only(['returnFile','id_bank']);
            $file=$data['returnFile'];
            if($file->isValid()){
                $returnFile=md5(rand(0,99999).rand(0,99999)).".".$file->getClientOriginalExtension();
                $pathFile="bankSlip/returnFile/";
                $file->storeAs($pathFile,$returnFile);
                $pathFileReturn=$pathFile.$returnFile;
                $content=Storage::get($pathFileReturn);
             
                try {
                    if($data['id_bank']==1){
                        $return = new \Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab400\Banco\Sicredi($content);
                    }else{
                        $return = new \Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab400\Banco\Caixa($content);
                    }
                    
                } catch (Exception $e) {
                    return redirect()->route('returnBankSlip')->withErrors($e->getMessage());
                }
                
                $return->processar();
                
                DB::table('bank_slip_return')->insert(['id_financial_account'=>1,
                    'date'=>date('Y-m-d'),'updated_parcels'=>0,'add_now'=>1, 'path_file_return'=>$pathFileReturn]);
                
                $idBankSlipReturn=DB::table('bank_slip_return')->max('id');
                
                foreach($return->getDetalhes() as $returnFile) {
                    $our_number=$returnFile->nossoNumero;
                    $occorency=$returnFile->ocorrencia;
                    $descriptionOccorency=$returnFile->ocorrenciaDescricao;
                    $dateOccorency=$returnFile->dataOcorrencia!=""?$this->dateMysql($returnFile->dataOcorrencia):null;
                    $deadLine=$returnFile->dataVencimento!=""?$this->dateMysql($returnFile->dataVencimento):null;
                    $creditDate=$returnFile->dataCredito!=""?$this->dateMysql($returnFile->dataCredito):null;
                    $value=$returnFile->valor;
                    $value_rate=$returnFile->valorTarifa;
                    $value_mora=$returnFile->valorMora;
                    $value_fine=$returnFile->valorMulta;
                    $value_descont=$returnFile->valorDesconto;
                    $amount_received=$returnFile->valorRecebido;
                    $error=$returnFile->error;
                    
                    $bankSlipReturn=new BankSlipReturnInfo(); 
                    $bankSlipReturn->id_bankSlipReturn=$idBankSlipReturn;
                    $bankSlipReturn->our_number=$our_number;
                    $bankSlipReturn->ocorrency=$occorency;
                    $bankSlipReturn->ocorrencyDescription=$descriptionOccorency;
                    $bankSlipReturn->dateOccorency=$dateOccorency;
                    $bankSlipReturn->deadLine=$deadLine;
                    $bankSlipReturn->creditDate=$creditDate=="--"?null:$creditDate;
                    
                    $bankSlipReturn->value=$value;
                    $bankSlipReturn->value_rate=$value_rate;
                    $bankSlipReturn->value_mora=$value_mora;
                    $bankSlipReturn->value_fine=$value_fine;
                    $bankSlipReturn->amount_received=$amount_received;
                    $bankSlipReturn->value_descont=$value_descont;
                    $bankSlipReturn->view=0;
                    $bankSlipReturn->error=$error;
                    $bankSlipReturn->save();
                }

                $this->returnBankSlipInfo($idBankSlipReturn,false);
            }
        }
        return redirect()->route('returnBankSlip');
    }

    private function dateMysql($date){
        $ano= substr($date, 6);
        $mes= substr($date, 3,-5);
        $dia= substr($date, 0,-8);

        return $ano."-".$mes."-".$dia;
    }

    private function resetParcels(){
        $BankSlip=BankSlip::all();
        foreach ($BankSlip as $key => $bankItem) {
            $bankSend=BankSlip::where('id',$bankItem->id)->first();
            $bankSend->add_now=0;
            $bankSend->save();
        }
    }

    public function generateBankSlip(Request $request){
        $data=$request->only(['register','id_sale','id_parcels','descont','bank_interest_rate','fine',
            'id_financial_accounts','delay_limit','observation','typeBankSlip','id_user_permission']);
        $sale=Sales::where('id',$data['id_sale'])->first();
        
        $request->validate([
            'id_sale'=>['required'],
            'id_parcels'=>['required'],
            'descont'=>['required'],
            'bank_interest_rate'=>['required'],
            'fine'=>['required'],
            'id_financial_accounts'=>['required'],
            'delay_limit'=>['required'],
        ]);

        $company=Companies::where('id',1)->first();
        $client=Clients::where('id',$sale->client_payment_id)->first();
        $financial_account=Bank::where('id',$data['id_financial_accounts'])->first();
        $parcels_id=explode(',',$data['id_parcels'][0]);
        $beneficiario=$this->beneficiario($company);
        $pagador=$this->pagador($client);
        $boletos=[];

        $parcels=[];
        foreach ($parcels_id as $key => $id) {
            $parcel=Parcels::where('id',$id)->first();
            $parcels[]=$parcel;
            $boleto=[];
            if($financial_account->id_bank==748){
                $boleto=$this->sicrediBankSlip($beneficiario, $pagador,$parcel,$financial_account,$sale,$data);
            }else if($financial_account->id_bank==104){
                $boleto=$this->caixaBankSlip($beneficiario, $pagador, $parcel,$financial_account,$sale,$data);
            }
            
            $boletos[]=$boleto;
        }

        if($data['register']=="false"){
            if($data['typeBankSlip']==1){
                $this->gerarPdf($boletos);
            }elseif($data['typeBankSlip']==2){
                return $this->gerarCarne($boletos);
            }
        }else{
            
            foreach ($boletos as $key => $boleto) {
                $fileName=md5(strtotime('now').md5(rand(0,99999*101254))).".pdf";
                $path=storage_path()."/app/public/bankSlip/".$fileName;
              
                $this->gerarPdf($boletos[$key],$path);
                
                $bankSlip=new BankSlip();
                $bankSlip->id_sale=$sale->id;
                $bankSlip->id_parcel=$parcels_id[$key];
                $bankSlip->descont=$data['descont'];
                $bankSlip->bank_interest_rate=$data['bank_interest_rate'];
                $bankSlip->fine=$data['fine'];
                $bankSlip->id_financial_accounts=$data['id_financial_accounts'];
                $bankSlip->delay_limit=$data['delay_limit'];
                $bankSlip->observation=$data['observation'];
                $bankSlip->our_number=$parcels[$key]->our_number;
                $bankSlip->date=new Carbon();
                $bankSlip->time=new Carbon();
                $bankSlip->path=$fileName;
                $bankSlip->status=2;
                $bankSlip->save();

                $parcelUpdate=Parcels::where('id',$parcels[$key]->id)->first();
                $parcelUpdate->send_bankSlip=2;
                $parcelUpdate->link=$fileName;
                $parcelUpdate->save();
            }

            return redirect()->route('sendBankSlip'); 
        }
    }

    private function gerarPdf($boletos,$path=null){
        $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();     
        if($path == null){
            if(count($boletos)==1){
                $pdf->addBoleto($boletos[0]);
            }else{
                $pdf->addBoletos($boletos);
            }
        }else{
            $pdf->addBoleto($boletos);
        }
        
                
        $action=$pdf::OUTPUT_STANDARD;
        if($path != null){
            $action=$pdf::OUTPUT_SAVE;
        }else{
            $pdf->showPrint();
            $pdf->hideInstrucoes();
        }
        
        $pdf->gerarBoleto($action, $path);
        
    }

    private function gerarCarne($boletos){
        $html = new \Eduardokum\LaravelBoleto\Boleto\Render\Html();

        if(count($boletos)==1){
            $html->addBoleto($boletos[0]);
        }else{
            $html->addBoletos($boletos);
        }

        $html->showPrint();
        $html->hideInstrucoes();

        return $html->gerarCarne();
    }

    private function sicrediBankSlip($beneficiario, $pagador, $parcel,$financial_account,$sale,$data){
        $descont=$this->getValueDescont($parcel->updated_value,$data['descont']);
        $fine=$this->getValueFine($parcel->updated_value,$data['fine']);
        $bankInterestRate=$this->getValueBankInterestRate($parcel->updated_value,$data['bank_interest_rate']);

        $sicredi = new \Eduardokum\LaravelBoleto\Boleto\Banco\Sicredi([
            'logo' => "storage/general_icons/LogoGU.png",
            'dataVencimento' => Carbon::createFromFormat('Y-m-d',$parcel->date),
            'valor' => str_replace('.','',$parcel->updated_value),
            'numero' => $parcel->our_number,
            'numeroDocumento' => $sale->contract_number.' - '.$parcel->num.'/'.$sale->parcels,
            'pagador' => $pagador,
            'beneficiario' => $beneficiario,
            'carteira' => $financial_account->wallet,
            'posto' => $financial_account->post,
            'byte' => $financial_account->byte,
            'agencia' => $financial_account->bank_agency,
            'conta' => $financial_account->account,
            'codigoCliente' => $financial_account->id_recipient,
            'multa' => $data['fine'], // 1% do valor do boleto após o vencimento
            'juros' => $data['bank_interest_rate'], // 1% ao mês do valor do boleto
            'jurosApos' => $data['delay_limit'], // quant. de dias para começar a cobrança de juros,
            'desconto'=>$descont,
            'dataDesconto:'=>Carbon::createFromFormat('Y-m-d',$parcel->date),
            'aceite'=>$financial_account->accept,
            'instrucoes' => 
                [
                    'Desconto de R$'.$descont. ' até '.date('d/m/Y',strtotime($parcel->date)).' .',
                    'Multa de R$'.$fine.' após ' .date('d/m/Y',strtotime($parcel->date)).' .',
                    'Juros de R$'.$bankInterestRate.' ao dia',
                    'Não receber após '.$data['delay_limit'].' dias de atraso'
                ],
        ]);

        return $sicredi;
    }

    private function caixaBankSlip($beneficiario, $pagador, $parcel,$financial_account,$sale,$data){
        $descont=$this->getValueDescont($parcel->updated_value,$data['descont']);
        $fine=$this->getValueFine($parcel->updated_value,$data['fine']);
        $bankInterestRate=$this->getValueBankInterestRate($parcel->updated_value,$data['bank_interest_rate']);
        
        $caixa = new \Eduardokum\LaravelBoleto\Boleto\Banco\Caixa([
            'logo' => "storage/general_icons/LogoGU.png",
            'dataVencimento' => new Carbon($parcel->date),
            'valor' => str_replace('.','',$parcel->updated_value),
            'numero' => $parcel->our_number,
            'numeroDocumento' => $sale->contract_number.' - '.$parcel->num.'/'.$sale->parcels,
            'pagador' => $pagador,
            'beneficiario' => $beneficiario,
            'carteira' => $financial_account->wallet,
            'agencia' => $financial_account->bank_agency,
            'conta' => $financial_account->account,
            'codigoCliente' => $financial_account->id_recipient,
            'multa' => $data['fine'], // 1% do valor do boleto após o vencimento
            'juros' => $data['bank_interest_rate'], // 1% ao mês do valor do boleto
            'jurosApos' => $data['delay_limit'], // quant. de dias para começar a cobrança de juros,
            'desconto'=>$descont,
            'dataDesconto:'=>new Carbon($parcel->date),
            'aceite'=>$financial_account->accept,
            'instrucoes' => 
                [
                    'Desconto de R$'.$descont. ' até '.date('d/m/Y',strtotime($parcel->date)).' .',
                    'Multa de R$'.$fine.' após ' .date('d/m/Y',strtotime($parcel->date)).' .',
                    'Juros de R$'.$bankInterestRate.' ao dia',
                    'Não receber após '.$data['delay_limit'].'dias de atraso'
                ],
        ]);

        return $caixa;
    }

    private function getValueDescont($value,$descont){
        $value=floatVal(str_replace(['.',','],['','.'],$value));
        $descont=floatVal($descont); 
        $descontValue=$value*$descont/100;
        
        return number_format($descontValue,2,',','.');
    }

    private function getValueFine($value,$fine){
        $value=floatVal(str_replace(['.',','],['','.'],$value));
        $fine=floatVal($fine); 
        $fineValue=$value*$fine/100;
        
        return number_format($fineValue,2,',','.');
    }

    private function getValueBankInterestRate($value,$bankInterestRate){
        $value=floatVal(str_replace(['.',','],['','.'],$value));
        $bankInterestRate=floatVal($bankInterestRate); 
        $bankInterestRateValue=$value*$bankInterestRate/100;
        
        return number_format($bankInterestRateValue,2,',','.');
    }

    private function beneficiario($company){
        $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa([
            'documento' => $company->cnpj,
            'nome'      => $company->company_name,
            'cep'       => $company->cep,
            'endereco'  => $company->street.", ".$company->number,
            'bairro' => $company->neighborhood,
            'uf'        => $company->state,
            'cidade'    => $company->city,
        ]);

        return $beneficiario;
    }

    private function pagador($client){
        $pagador = new \Eduardokum\LaravelBoleto\Pessoa([
            'documento' => $client->cpf!=""?$client->cpf:$client->cnpj,
            'nome'      => $client->cpf!=""?$client->name:$client->company_name,
            'cep'       => $client->cep,
            'endereco'  => $client->street.", ".$client->number,
            'bairro' => $client->neighborhood,
            'uf'        => $client->state,
            'cidade'    => $client->city,
        ]);

        return $pagador;
    }


    public function addBankSlip(Request $request){
        $data=[];
        
        $data['interprises']=Interprises::all();
        $data['radioSelectedIdSale']=$request->input('radioSelectedIdSale');
        $data['firstParcel']="";
        $data['endParcel']="";
        $data['sale_info']="";
        $data['parcels_pad']="";
        $data['parcels']=[];
        $data['reajust']=false;
        
        if($data['radioSelectedIdSale'] != ""){
            $data['sale_info']=Sales::join('interprises','sales.id_interprise','=','interprises.id')
            ->join('lots','sales.id_lot','lots.id')
            ->where('type',2)
            ->where('sales.id',$data['radioSelectedIdSale'])
            ->get(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
               ,'lots.block as lot_block'])->first();
            
            $this->verifyReajustParcels($data['radioSelectedIdSale']);
            $data['parcels_pad']=$this->getPaidValue($data['radioSelectedIdSale']);
            
            $data['parcels']=Parcels::where('id_sale',$data['radioSelectedIdSale'])
                ->where('send_bankSlip',0)->where('status',"!=",1)->get();
            $parcelsReadjust=Parcels::where('id_sale',$data['radioSelectedIdSale'])->where('send_bankSlip',0)->where('status',4)->get();
            if(count($parcelsReadjust)>0){
                $data['reajust']=true;
            }

            $dataFilterParcel=$request->only(['firstParcel','endParcel']);
            if($request->hasAny(['firstParcel','endParcel'])){
                $query=Parcels::query();
                foreach ($dataFilterParcel as $name => $value) {
                    if($value && $name=="firstParcel"){
                        $query->where('num', '>=', $value);
                    }else if($value && $name=="endParcel"){
                        $query->where('num', '<=', $value);
                    }
                }
                $data['parcels']=$query->where('id_sale',$data['radioSelectedIdSale'])
                    ->where('status',"!=",1)->where('send_bankSlip',0)->orderBy('date','ASC')->get();
                $data['firstParcel']=$dataFilterParcel['firstParcel'];
                $data['endParcel']=$dataFilterParcel['endParcel'];
            }
        }
        $data['sales']=[];
    
        $data['contract_number']="";
        $data['interprise_name']=""; 
        $data['lot_number']="";
        $data['block']="";
        $data['client_name']="";

        $dataSalesLike=$request->only(['contract_number','interprise_name','lot_number','block']);

        if($request->hasAny(['contract_number','interprise_name','lot_number','block'])){
            $query=Sales::join('interprises','sales.id_interprise','=','interprises.id')
                        ->join('lots','sales.id_lot','lots.id');
            
            foreach ($dataSalesLike as $name => $value) {
                if($value){
                   if($name=="interprise_name"){
                        $name="interprises.name";
                   }

                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            $data['sales']=$query->where('type',2)
                ->get(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
                ,'lots.block as lot_block']);

            $data['contract_number']=$dataSalesLike['contract_number'];
            $data['interprise_name']=$dataSalesLike['interprise_name']; 
            $data['lot_number']=$dataSalesLike['lot_number']; 
            $data['block']=$dataSalesLike['block'];
        }

        $data['client_name']="";
        if($request->has('client_name')){
            $client_name=$request->input('client_name');
            $data['sales']=$this->filterClients($data['sales'],$client_name);
            $data['client_name']=$client_name;
        }
         
        $clientsSaleNames=[];
        $data['clients']=[];
        
        foreach ($data['sales'] as $key => $sale) {
            $clientsSale=explode(',',$sale->clients);
            foreach ($clientsSale as $key => $clientSaleId) {
                $idClient=intval($clientSaleId);
                $client=Clients::where('id',$idClient)->first();
                $clientName=$client->name;
                if($client->name ==""){
                    $clientName=$client->company_name;
                }
                $clientsSaleNames[]=$clientName;
            }
                              
            $data['clients'][]=$clientsSaleNames;
            $clientsSaleNames=[];
        }
        
        $data['financialAccounts']=Bank::where('id',1)->orwhere('id',2)->where('status',1)->get();

        $data['users']=User::where('status',1)
        ->where('type',"=",3)
        ->get();

        return view('addBankSlip',$data);
    }

    private function verifyReajustParcels($idSale){
        $numberParcelTotal=count(Parcels::where('id_sale',$idSale)->get());
        
        $parcel=Parcels::where('id_sale',$idSale)->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
            ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')->first();
        
        $parcel=Parcels::where('id_sale',$idSale)->where('num',12)->first();
        
        if($parcel != null){
            $numParcel=$parcel->num;
            $numberTimeReadjust=$numberParcelTotal/12; 
            $allNumberParcelReadjust=[];
            for ($i=0,$numberParcelReadjust=0; $i < $numberTimeReadjust; $i++) { 
                $numberParcelReadjust=$numberParcelReadjust+12;    
                $allNumberParcelReadjust[]=$numberParcelReadjust;
            }

            if(in_array($numParcel,$allNumberParcelReadjust)){
                echo "AAAAAA";
                $this->getDatesParcel_Readjust($parcel);
            }
        }
    }

    private function getDatesParcel_Readjust($parcelObject){
        $anniversaryDate=date('Y-m-d',strtotime('-12 month',strtotime($parcelObject->date)));
        $dateIndexValueAnniversary=date('Y',strtotime($anniversaryDate)).'-'
            .date('m',strtotime($anniversaryDate)).'-01';
        $sale=Sales::where('id',$parcelObject->id_sale)->first();    
        $index=$sale->index;
        
        $index_value_object=DB::table('index_value')->where('month',$dateIndexValueAnniversary)
            ->where('idIndex',$index)->first();

        if($index_value_object!=null){
            $index_value_date_anniversary=$index_value_object->month;
            $index_value_date_final=date('Y-m-d',strtotime('+11 month',strtotime($index_value_object->month)));
           
            $totalReadjustRate=$this->getSumReadjustRate($index_value_date_anniversary,$index_value_date_final,$index);
            if($totalReadjustRate>0){
                $totalReadjustRate=$totalReadjustRate+$sale->annual_rate;
                if($totalReadjustRate < $sale->minimum_variation){
                    $totalReadjustRate=$sale->minimum_variation;
                }
            }
            
            if($totalReadjustRate>0){
                $this->readjustParcels($parcelObject,$totalReadjustRate);
            }else{
                $parcelReadjust=Parcels::where('id_sale',$parcelObject->id_sale)
                    ->where('num',$parcelObject->num+1)->first();
                if($parcelReadjust->status != 4){
                    $this->warningIndexValue($index_value_date_anniversary,$index_value_date_final
                    ,$parcelObject->id_sale,$parcelObject->num,$index);
                }
                $this->changeStatusParcelToReadjust($parcelObject);
            }
        }else{
            $index_value_date_anniversary=$dateIndexValueAnniversary;
            $index_value_date_final=date('Y-m-d',strtotime('+12 month',strtotime($dateIndexValueAnniversary)));
            $parcelReadjust=Parcels::where('id_sale',$parcelObject->id_sale)
                    ->where('num',$parcelObject->num+1)->first();
            
            if($parcelReadjust->status != 4){
                $this->warningIndexValue($index_value_date_anniversary,$index_value_date_final
                ,$parcelObject->id_sale,$parcelObject->num,$index);
            }
            $this->changeStatusParcelToReadjust($parcelObject);
        }
    }


    private function changeStatusParcelToReadjust($parcelObject){
        $parcelsReadjust=Parcels::where('num','>=',$parcelObject->num+1)
        ->where('num','<=',$parcelObject->num+11)
        ->where('id_sale',$parcelObject->id_sale)->get();
        foreach ($parcelsReadjust as $key => $parcelVal) {
            $parcelVal=Parcels::where('id',$parcelVal->id)->first();
            $parcelVal->status=4;
            $parcelVal->save();
        }
    }

    private function warningIndexValue($index_value_date_anniversary,$index_value_date_final,$idSale,$numberParcel,$index){
        $allIndex_value_readjust=DB::table('index_value')->where('idIndex',$index)->where('month','>=',$index_value_date_anniversary)
            ->where('month','<=',$index_value_date_final)->orderBy('month','asc')->get();
        $dateIndexMonth=[];
        $dateIndexMonth[]=$index_value_date_anniversary;
        for ($i=1; $i <=11; $i++) { 
            $date=date('Y-m-d',strtotime('+'.$i.'month',strtotime($index_value_date_anniversary)));
            $dateIndexMonth[]=$date;
        }
        $keys=[];
        if(count($allIndex_value_readjust)>0){
            foreach ($allIndex_value_readjust as $key => $index_val) {
                $key=array_search($index_val->month,$dateIndexMonth);
                unset($dateIndexMonth[$key]);
            }   
        }
        
        $dateIndexMonth=implode(',',$dateIndexMonth);
        $parcel_readjust="Parcelas ".($numberParcel)." até ".($numberParcel+11);
        DB::table('notification_index_value')->insert(['id_sale'=>$idSale,
            'month_index_empty'=>$dateIndexMonth,'parcels_readjust'=>$parcel_readjust,
            'id_sale'=>$idSale,'index'=>$index,'type'=>1]);
    }

    private function getSumReadjustRate($index_date_anniversary,$index_date_final,$idIndex){
        $allIndex_value_readjust=DB::table('index_value')->where('idIndex',$idIndex)->where('month','>=',$index_date_anniversary)
            ->where('month','<=',$index_date_final)->orderBy('month','asc')->get();
        $rate_readjust_sum=0;
        if(count($allIndex_value_readjust)==12){
            $index_values=[];    
            foreach ($allIndex_value_readjust as $key => $index_value) {
                $index_values[]=$index_value->value;
            }
            
            $rate_readjust_sum=array_sum($index_values);
        }
        return $rate_readjust_sum;
    }

    private function readjustParcels($parcelObject,$sumReadjust){
        $numParcel=$parcelObject->num;
        $parcelValue=Parcels::where('num',$numParcel)->where('id_sale',$parcelObject->id_sale)->first()->value;
        $parcelValue=floatVal(str_replace(['.',','],['','.'],$parcelValue)); 

        $valueReadjust=$parcelValue*$sumReadjust/100;
        $totalValueReadjust=$parcelValue+$valueReadjust;
        $parcelsReadjust=Parcels::where('num','>=',$numParcel+1)->where('num','<=',$numParcel+12)
        ->where('id_sale',$parcelObject->id_sale)->get();
        
        foreach ($parcelsReadjust as $key => $parcelItem) {
            $parcel=Parcels::where('id',$parcelItem->id)->first();
            $parcel->value=number_format($totalValueReadjust,2,",",".");
            $parcel->reajust=number_format($valueReadjust,2)." (".$sumReadjust."%)";
            $parcel->updated_value=number_format($totalValueReadjust,2,",",".");
            $parcel->status=2;
            $parcel->save();
        }
    }

    private function filterClients($sales,$name_client){
        $salesFilter=[];
        
        foreach ($sales as $key => $sale) {
            $clientsRegister=Clients::where('name','LIKE','%'.$name_client.'%')
                ->orwhere('company_name','LIKE','%'.$name_client.'%')
                ->orwhere('cpf','LIKE','%'.$name_client.'%')
                ->orwhere('cnpj','LIKE','%'.$name_client.'%')
                ->get();    
            $idsClientsSale=explode(',',$sale->clients);
            $saleAdd=0;
            foreach ($clientsRegister as $key => $clientRegisterItem) {
                if(in_array($clientRegisterItem->id,$idsClientsSale) && $saleAdd==0){
                    $salesFilter[]=$sale;
                    $saleAdd++;
                } 
            }
        }
      
        return $salesFilter;
    }


    private function getPaidValue($idSale){
        $data['parcels']=Parcels::where('status',1)->where('id_sale',$idSale)->get();
        
        $valuesParcels=[];
        foreach ($data['parcels'] as $key => $parcel) {
                $initial_value=str_replace('.','',$parcel->initial_value);
                $initial_value=str_replace(',','.',$initial_value); 
                $valuesParcels[]=floatVal($initial_value);
        }
        
        return number_format(array_sum($valuesParcels),2);
    }
}

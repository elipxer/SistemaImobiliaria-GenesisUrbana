<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parcels;
use App\Models\ProgramedPayment;
use Mpdf\Mpdf;
use App\Models\TransfersBank;
use App\Models\Bank;
use App\Models\Sales;
use Illuminate\Support\Facades\View;
use App\Models\Clients;
use Illuminate\Support\Facades\Auth;
use App\Models\InternalAccount;

class ReportsController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

    }

    public function cashFlow(Request $request){
        $data=[];
        $dataFilterDate=$request->only(['startDate','finalDate']);
        if(!$request->hasAny(['startDate','finalDate'])){
            $dataFilterDate['startDate']=date('Y-m-d');
            $dataFilterDate['finalDate']=date('Y-m-d');
        }
        $data['startDate']=date('Y-m-d');
        $data['finalDate']=date('Y-m-d');;

        $data['parcelsPad']=[];
        $data['valuePadParcels']=[];
        $data['programedPayment']=[];
        $data['padProgramedPaymentsValue']=[];
        $data['filterDates']=false;

        $sales=Sales::all();
        $data['sales']=[];
       
        foreach ($sales as $key => $sale) {
            $parcels=Parcels::where('status',1)->where('id_sale',$sale->id)
            ->whereBetween('pad_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();
            
            if(count($parcels)>0){
                $saleArray['sale']=$sale;
                
                $saleArray['client']=Clients::where('id',$sale->client_payment_id)->first();
                
                $saleArray['allParcels']=[];
            
                
                foreach ($parcels as $key => $parcel) {
                    $saleArray['allParcels'][]=$parcel;
                }
                
                $saleArray['total']=number_format($this->getValuePadParcels($parcels),2);
                $data['sales'][]=$saleArray;
            }
        }

      
        $parcels=Parcels::where('status',1)->
            whereBetween('pad_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();
        
        $data['valuePadParcels']=$this->getValuePadParcels($parcels);

        $internalAccounts=InternalAccount::all();
        $allInternalAccounts=[];
        foreach ($internalAccounts as $key => $internal) {
            $programedPayments=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
            ->join('clients','programed_payments.id_provider','clients.id')
            ->where('id_internal_account',$internal->id)
            ->where('status',1)
            ->whereBetween('payment_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get([
                'programed_payments.*',
                'clients.name as nameProvider',
                'clients.company_name as companyProvider',
                'internal_accounts.name as internalAccount',
            ]);

            if(count($programedPayments)>0){
                $internalArray['internal']=$internal;
                $internalArray['programedPayments']=[];
               
              
                foreach ($programedPayments as $key => $programed) {
                    $internalArray['programedPayments'][]=$programed;
                }
                
                $internalArray['total']=number_format($this->getValuePadProgramedPayments($programedPayments),2);
               
                $allInternalAccounts[]=$internalArray;
            }
        }

        $data['internalAccounts']=$allInternalAccounts;

        $programedPayments=ProgramedPayment::where('status',1)
        ->whereBetween('payment_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
        ->get();
        
        if(Auth::user()->type!=1){
            $allInternalAccountPermissionUser=[];
            foreach ($data['internalAccounts'] as $key => $internalAccount) {
                $id_user_permission=explode(',',$internalAccount['internal']->id_user_permission);

                if(in_array(Auth::user()->id,$id_user_permission)){
                    $allInternalAccountPermissionUser[]=$internalAccount;
                }
            }
            
            $data['internalAccounts']=$allInternalAccountPermissionUser;    
        }

        $data['padProgramedPaymentsValue']=$this->getValuePadProgramedPayments($programedPayments);    
        $data['balance']=$data['valuePadParcels']-$data['padProgramedPaymentsValue'];
        
        $data['startDate']=$dataFilterDate['startDate'];
        $data['finalDate']=$dataFilterDate['finalDate'];
        $data['filterDates']=true;
        

        return view('cashFlow',$data);
        
    }

    public function cashFlowReport(Request $request){
        $data=[];
        
        $dataFilterDate=$request->only(['startDate','finalDate']);
        $data['parcelsPad']=[];
        $data['valuePadParcels']=[];
        $data['programedPayment']=[];
        $data['padProgramedPaymentsValue']=[];
        $data['filterDates']=false;

        $sales=Sales::all();
        $data['sales']=[];
       
        foreach ($sales as $key => $sale) {
            $parcels=Parcels::where('status',1)->where('id_sale',$sale->id)
            ->whereBetween('pad_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();
            
            if(count($parcels)>0){
                $saleArray['sale']=$sale;
                
                $saleArray['client']=Clients::where('id',$sale->client_payment_id)->first();
                
                $saleArray['allParcels']=[];
            
                
                foreach ($parcels as $key => $parcel) {
                    $saleArray['allParcels'][]=$parcel;
                }
                
                $saleArray['total']=number_format($this->getValuePadParcels($parcels),2);
                $data['sales'][]=$saleArray;
            }
        }

      
        $parcels=Parcels::where('status',1)->
            whereBetween('pad_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();
        
        $data['valuePadParcels']=$this->getValuePadParcels($parcels);

        $internalAccounts=InternalAccount::all();
        $allInternalAccounts=[];
        foreach ($internalAccounts as $key => $internal) {
            $programedPayments=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
            ->join('clients','programed_payments.id_provider','clients.id')
            ->where('id_internal_account',$internal->id)
            ->where('status',1)
            ->whereBetween('payment_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get([
                'programed_payments.*',
                'clients.name as nameProvider',
                'clients.company_name as companyProvider',
                'internal_accounts.name as internalAccount',
            ]);

            if(count($programedPayments)>0){
                $internalArray['internal']=$internal;
                $internalArray['programedPayments']=[];
               
              
                foreach ($programedPayments as $key => $programed) {
                    $internalArray['programedPayments'][]=$programed;
                }
                
                $internalArray['total']=number_format($this->getValuePadProgramedPayments($programedPayments),2);
                $allInternalAccounts[]=$internalArray;
            }
        }

        $data['internalAccounts']=$allInternalAccounts;

        $programedPayments=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
        ->join('clients','programed_payments.id_provider','clients.id')
        ->where('status',1)
        ->whereBetween('payment_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
        ->get([
            'programed_payments.*',
            'clients.name as nameProvider',
            'clients.company_name as companyProvider',
            'internal_accounts.name as internalAccount',
        ]);

        if(Auth::user()->type!=1){
            $allInternalAccountPermissionUser=[];
            foreach ($data['internalAccounts'] as $key => $internalAccount) {
                $id_user_permission=explode(',',$internalAccount['internal']->id_user_permission);

                if(in_array(Auth::user()->id,$id_user_permission)){
                    $allInternalAccountPermissionUser[]=$internalAccount;
                }
            }
            
            $data['internalAccounts']=$allInternalAccountPermissionUser;    
        }

        $data['padProgramedPaymentsValue']=$this->getValuePadProgramedPayments($programedPayments);    
        
        $data['balance']=$data['valuePadParcels']-$data['padProgramedPaymentsValue'];

        $data['startDate']=$dataFilterDate['startDate'];
        $data['finalDate']=$dataFilterDate['finalDate'];
    
        $mpdf= new Mpdf();
        $mpdf->WriteHTML(View::make('reports.reportCashFlow',$data)->render());
        $mpdf->SetDisplayMode('fullpage');
        
        return $mpdf->Output('fluxoCaixa.pdf', 'I');
    }

    public function bankBalance(Request $request){
        $data=[];
        $dataFilterDate=$request->only(['startDate','finalDate']);
        if(!$request->hasAny(['startDate','finalDate'])){
            $dataFilterDate['startDate']=date('Y-m-d');
            $dataFilterDate['finalDate']=date('Y-m-d');
        }

        $data['valuePadParcels']=[];
        $data['filterDates']=false;
        $data['bankRegisters']=[];

        $banks=Bank::all();
        $banksRegisters=[];
        
        
        foreach ($banks as $key=>$bank) {
            $registers=[];

            $transferBankInputs=TransfersBank::where('id_origin_bank',$bank->id)
            ->whereBetween('date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();

            foreach ($transferBankInputs as $key => $transferInput) {
                $bankDestiny=Bank::where('id',$transferInput->id_destiny_bank)->first();

                $description="Transferência enviado para o ".$bankDestiny->name;
                $registers[]=['date'=>$transferInput->date,'type'=>1,'description'=>$description,'value'=>$transferInput->value];
            }

            $transferBankOutput=TransfersBank::where('id_destiny_bank',$bank->id)
            ->whereBetween('date',  [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();

            foreach ($transferBankOutput as $key => $transferOutput) {
                $bankOrigin=Bank::where('id',$transferOutput->id_origin_bank)->first();

                $description="Transferência recebido do ".$bankOrigin->name;
                $registers[]=['date'=>$transferOutput->date,'description'=>$description,'type'=>2,'value'=>$transferOutput->value];
            }

            $programed_payments=ProgramedPayment::where('idBank',$bank->id)
            ->where('status',1)
            ->whereBetween('date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();

            foreach ($programed_payments as $key => $programedPayment) {
                $registers[]=['date'=>$programedPayment->date,'type'=>3,'description'=>"Pagamentos",'value'=>$programedPayment->value_payment];
            }

            $parcels=Parcels::where('idBankPayment',$bank->id)->where('status',1)
                ->whereBetween('pad_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
                ->get();
            
            foreach ($parcels as $key => $parcel) {
                $registers[]=['date'=>$parcel->pad_date,'type'=>4,'description'=>"Recebimentos Boleto (Nosso numero: ".$parcel->our_number.")",'value'=>$parcel->pad_value];
            }    

            if($dataFilterDate['startDate']!=$dataFilterDate['finalDate']){
                $registers=$this->filterRegisterByDate($dataFilterDate['startDate'],$dataFilterDate['finalDate'],$registers);
            }
            $balance=$this->getBalanceBank($registers);
            $banksRegisters[]=['bank'=>$bank,'registers'=>$registers,'balance'=>$balance];
            
            $data['startDate']=$dataFilterDate['startDate'];
            $data['finalDate']=$dataFilterDate['finalDate'];
            $data['filterDates']=true;
            $data['bankRegisters']=$banksRegisters;
        } 

        if(Auth::user()->type!=1){
            $banksPermissionUser=[];
            foreach ($data['bankRegisters'] as $key => $register) {
                $bank=Bank::where('id',$register['bank']->id)->first();
                $id_user_permission=explode(',',$bank->id_user_permission);
                if(in_array(Auth::user()->id,$id_user_permission)){
                    $banksPermissionUser[]=$register;
                }
            }

            $data['bankRegisters']=$banksPermissionUser;
        }

        $data['balanceTotal']=$this->getSumAllBankBalance($data['bankRegisters']);
        return view('bankBalance',$data);
    }

    public function bankBalanceReport(Request $request){
        $data=[];
        $dataFilterDate=$request->only(['startDate','finalDate']);
        $data['startDate']="";
        $data['finalDate']="";

        if($request->hasAny(['startDate','finalDate'])){
            $banks=Bank::all();
            $banksRegisters=[];
            foreach ($banks as $key=>$bank) {
                $registers=[];
    
                $transferBankInputs=TransfersBank::where('id_origin_bank',$bank->id)
                ->whereBetween('date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
                ->get();
    
                foreach ($transferBankInputs as $key => $transferInput) {
                    $bankDestiny=Bank::where('id',$transferInput->id_destiny_bank)->first();
    
                    $description="Transferência enviado para o ".$bankDestiny->name;
                    $registers[]=['date'=>$transferInput->date,'type'=>1,'description'=>$description,'value'=>$transferInput->value];
                }
    
                $transferBankOutput=TransfersBank::where('id_destiny_bank',$bank->id)
                ->whereBetween('date',  [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
                ->get();
    
                foreach ($transferBankOutput as $key => $transferOutput) {
                    $bankOrigin=Bank::where('id',$transferOutput->id_origin_bank)->first();
    
                    $description="Transferência recebido do ".$bankOrigin->name;
                    $registers[]=['date'=>$transferOutput->date,'description'=>$description,'type'=>2,'value'=>$transferOutput->value];
                }
    
                $programed_payments=ProgramedPayment::where('idBank',$bank->id)
                ->where('status',1)
                ->whereBetween('date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
                ->get();
    
                foreach ($programed_payments as $key => $programedPayment) {
                    $registers[]=['date'=>$programedPayment->date,'type'=>3,'description'=>"Pagamentos",'value'=>$programedPayment->value_payment];
                }
    
                $parcels=Parcels::where('status',1)->where('idBankPayment',$bank->id)
                    ->whereBetween('pad_date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
                    ->get();
    
                foreach ($parcels as $key => $parcel) {
                    $registers[]=['date'=>$parcel->pad_date,'type'=>4,'description'=>"Recebimentos Boleto (Nosso numero: ".$parcel->our_number.")",'value'=>$parcel->pad_value];
                }    
                
                if($dataFilterDate['startDate']!=$dataFilterDate['finalDate']){
                    $registers=$this->filterRegisterByDate($dataFilterDate['startDate'],$dataFilterDate['finalDate'],$registers);
                }
                $balance=$this->getBalanceBank($registers);
                $banksRegisters[]=['bank'=>$bank,'registers'=>$registers,'balance'=>$balance];
                $data['startDate']=$dataFilterDate['startDate'];
                $data['finalDate']=$dataFilterDate['finalDate'];
                $data['filterDates']=true;
                $data['bankRegisters']=$banksRegisters;
            } 
        } 
        
        $data['balanceTotal']=$this->getSumAllBankBalance($data['bankRegisters']);

        if(Auth::user()->type!=1){
            $banksPermissionUser=[];
            foreach ($data['bankRegisters'] as $key => $register) {
                $bank=Bank::where('id',$register['bank']->id)->first();
                $id_user_permission=explode(',',$bank->id_user_permission);
                if(in_array(Auth::user()->id,$id_user_permission)){
                    $banksPermissionUser[]=$register;
                }
            }

            $data['bankRegisters']=$banksPermissionUser;
        }

        $mpdf= new Mpdf();
        $mpdf->WriteHTML(View::make('reports.reportBankBalance',$data)->render());
        $mpdf->SetDisplayMode('fullpage');
        
        return $mpdf->Output('saldoBancario.pdf', 'I');
    }

    private function getBalanceBank($registers){
        $balance=0;
        foreach ($registers as $key => $register) {
            $value=floatVal(str_replace(['.',','],['','.'],$register['value']));
            if($register['type']==1 || $register['type']==3){
                $balance=$balance-$value;
            }else{
                $balance=$balance+$value;
            }
        }

        return $balance;
    }

    private function getSumAllBankBalance($registers){
        $balance=0;
        foreach ($registers as $key => $register) {
            $value=$register['balance'];
            $balance=$balance+$value;
        }

        return $balance;
    }

    private function filterRegisterByDate($startDate,$finalDate,$registers){
        $date=$startDate;
        $registerFiltersDate=[];
        while ($date != $finalDate) {
            $registerFilters=$this->getRegisterByDate($registers,$date);    
            foreach ($registerFilters as $key => $register) {
                $registerFiltersDate[]=$register;
            }
            $date=date('Y-m-d',strtotime('+1 day',strtotime($date)));
        }

        return $registerFiltersDate;
    }

    private function getRegisterByDate($registers,$date){
        $registerOrderDate=[];
        foreach ($registers as $key => $register) {
            if($register['date']==$date){
                $registerOrderDate[]=$register;
            }
        }

        return $registerOrderDate;
    }


    private function getValuePadParcels($parcels){
        $valuesParcels=[];
        foreach ($parcels as $key => $parcel) {
            $value=floatVal(str_replace(['.',','],['','.'],$parcel->pad_value));
            $valuesParcels[]=$value;
        }
        return array_sum($valuesParcels);
    }

    private function getValuePadProgramedPayments($programed_payments){
        $valuesPadProgramedPayments=[];
        foreach ($programed_payments as $key => $programedPayments) {
            $value=floatVal(str_replace(['.',','],['','.'],$programedPayments->value_payment));
            $valuesPadProgramedPayments[]=$value;
        }

        return array_sum($valuesPadProgramedPayments);
    }

    public function parcelsReport(Request $request){
        $data['parcels']=Parcels::where('status',"!=",1)
        ->join('sales','parcels.id_sale','=','sales.id')
        ->orderBy('date','ASC')
        ->get(['parcels.*','sales.parcels as totalParcels',
            'sales.contract_number as contract_number','sales.id as idSale']);        
    
        $dataFilterDate=$request->only(['startDate','finalDate']);
        $data['startDate']="";
        $data['finalDate']="";
        

        if($request->input('startDate') || $request->input('finalDate')){
            
            $data['parcels']=Parcels::where('status',"!=",1)
            ->join('sales','parcels.id_sale','=','sales.id')
            ->whereBetween('date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->orderBy('date','ASC')
            ->get(['parcels.*','sales.parcels as totalParcels',
                'sales.contract_number as contract_number','sales.id as idSale']);  
            
            $data['startDate']=$dataFilterDate['startDate'];
            $data['finalDate']=$dataFilterDate['finalDate'];
        }

        $mpdf= new Mpdf();
        $mpdf->WriteHTML(View::make('reports.parcelsReport',$data)->render());
        $mpdf->SetDisplayMode('fullpage');
        return $mpdf->Output('parcelas.pdf', 'I');

    }
}

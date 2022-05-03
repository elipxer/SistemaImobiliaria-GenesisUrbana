<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransfersBank; 
use App\Models\Bank; 
use App\Models\InternalAccount;
use App\Models\UnscheduledPayment;
use App\Models\ProgramedPayment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class ActiveController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');
        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type!=1 && $this->user->type!=3 && $this->user->type!=2){
                return redirect()->route('accessDenied');
            }

            return $next($request);
        });   
    }

    public function allTransfersBank(Request $request){
        $data=[];
        $transfers_banks=TransfersBank::all();
        $data['transfers_banks']=[];

        $dataFilterDate=$request->only(['startDate','finalDate']);
        $data['startDate']="";
        $data['finalDate']="";

        if($request->hasAny(['startDate','finalDate'])){
            $transfers_banks=TransfersBank::whereBetween('date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get();
            
            $data['startDate']=$dataFilterDate['startDate'];
            $data['finalDate']=$dataFilterDate['finalDate'];
        }    


        $data['nameOrigin']='';
        $data['nameDestiny']='';
        $data['description']='';
        $data['date']='';
        

        $dataLike=$request->only(['description']);
        $dataEquals=$request->only(['date']);

        if($request->hasAny(['description','date'])){
            $query=TransfersBank::query();
            
            foreach ($dataLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }

            foreach ($dataEquals as $name => $value) {
                if($value){
                    $query->where($name, '=', $value);
                }
            }
            
            $data['description']=$dataLike['description'];
            $data['date']=$dataEquals['date'];
            $transfers_banks=$query->get();
        }
        
        foreach ($transfers_banks as $key => $transferBank) {
            $transferBankInfo[]=[
                'transferBank'=>$transferBank,
                'originBank'=>Bank::where('id',$transferBank->id_origin_bank)->first(),
                'destinyBank'=>Bank::where('id',$transferBank->id_destiny_bank)->first()
            ];

            $data['transfers_banks']=$transferBankInfo;
        }
        
        $nameOrigin=$request->input('nameOrigin');
        $nameDestiny=$request->input('nameDestiny');
        $transfersBankInfo=[];
        if($nameOrigin || $nameDestiny){
            foreach ($data['transfers_banks'] as $key => $transfers) {
                if($nameOrigin  != ""){
                    $bankOrigin=Bank::where('name','LIKE','%'.$nameOrigin.'%')->first();
                    

                    if($bankOrigin){
                        $bankOriginId=$bankOrigin->id;
                        if($transfers['transferBank']->id_origin_bank==$bankOriginId){
                            $transfersBankInfo[]=$transfers;
                        }

                        $data['nameOrigin']=$nameOrigin;
                    }
                }
                if($nameDestiny != ""){
                    $bankDestiny=Bank::where('name','LIKE','%'.$nameDestiny."%")->first();
                    if($bankDestiny != null){
                        $bankDestinyId=$bankDestiny->id;
                        if($transfers['transferBank']->id_destiny_bank==$bankDestinyId){
                            $transfersBankInfo[]=$transfers;
                        }
                        $data['nameDestiny']=$nameDestiny;
                    }
                }
            }

            $data['transfers_banks']=$transfersBankInfo;
        }

        if(Auth::user()->type!=1){
            $transfers_banksPermissionUser=[];
            foreach ($data['transfers_banks'] as $key => $transfers_bank) {
                $idBankOrigin=$transfers_bank['transferBank']->id_origin_bank;
                $idBankDestiny=$transfers_bank['transferBank']->id_destiny_bank;
                
                $bankOrigin=Bank::where('id',$idBankOrigin)->first(); 
                $id_user_permission=explode(',',$bankOrigin->id_user_permission);
                if(in_array(Auth::user()->id,$id_user_permission)){
                    $transfers_banksPermissionUser[]=$transfers_bank;
                }

                $bankDestiny=Bank::where('id',$idBankDestiny)->first(); 
                $id_user_permission=explode(',',$bankDestiny->id_user_permission);
                if(in_array(Auth::user()->id,$id_user_permission)){
                    $transfers_banksPermissionUser[]=$transfers_bank;
                }
            }

            $data['transfers_banks']=$transfers_banksPermissionUser;
        }

        return view('allTransfersBank',$data);
    }

    
    public function addTransfersBankView(Request $request){
        $data['banks_origin']=Bank::where('status',1)->get();
        $data['banks_destiny']=Bank::where('status',1)->get();
        
        $data['nameOrigin']="";
        $data['descriptionOrigin']="";
        $data['idSelectedBankOrigin']="";
        $data['nameDestiny']="";
        $data['descriptionDestiny']="";
        $data['idSelectedBankDestiny']="";


        $dataBankLikeOrigin=$request->only(['nameOrigin','descriptionOrigin']);
        $idSelectedBankOrigin=$request->input('idSelectedBankOrigin');
        if($request->hasAny(['nameOrigin','descriptionOrigin'])){
            $data['banks_origin']=$this->searchBankTransferBank($dataBankLikeOrigin);
            $data['nameOrigin']= $dataBankLikeOrigin['nameOrigin'];
            $data['descriptionOrigin']=$dataBankLikeOrigin['descriptionOrigin'];
            $data['idSelectedBankOrigin']=$idSelectedBankOrigin;
        }

        $dataBankLikeDestiny=$request->only(['nameDestiny','descriptionDestiny']);
        $idSelectedBankDestiny=$request->input(['idSelectedBankDestiny']);
        if($request->hasAny(['nameDestiny','descriptionDestiny'])){
            $data['banks_destiny']=$this->searchBankTransferBank($dataBankLikeDestiny);
            $data['nameDestiny']= $dataBankLikeDestiny['nameDestiny'];
            $data['descriptionDestiny']=$dataBankLikeDestiny['descriptionDestiny'];
            $data['idSelectedBankDestiny']=$idSelectedBankDestiny;
        }

        return view('addTransferBank',$data);
    }

    private function searchBankTransferBank($dataBankLike){
        $query=Bank::query();
        $banks=[];    
        foreach ($dataBankLike as $name => $value) {
            if($name=="nameOrigin" || $name=="nameDestiny"){
                $name="name";
            }

            if($name=="descriptionOrigin" || $name=="descriptionDestiny"){
                $name="description";
            }

            if($value){
                $query->where($name, 'LIKE', '%' . $value . '%');
            }
        
            $banks=$query->get();
            
        }

        return $banks;
    }


    public function updateTransfersBankView($idTransferBank,Request $request){
        $data['banks_origin']=Bank::where('status',1)->get();
        $data['banks_destiny']=Bank::where('status',1)->get();
        $data['transfersBank']=TransfersBank::where('id',$idTransferBank)->first();
        
        $data['nameOrigin']="";
        $data['descriptionOrigin']="";
        $data['nameDestiny']="";
        $data['descriptionDestiny']="";

        $typeBankSearch=$request->input('typeBankSearch');
        $dataBankLike=$request->only(['name','description']);
        
        if($request->hasAny(['name','date'])){
            if($typeBankSearch==1){
                $data['banks_origin']=$this->searchBank($dataBankLike);
                $data['nameOrigin']= $dataBankLike['name'];
                $data['descriptionOrigin']=$dataBankLike['description'];
            }else{
                $data['banks_destiny']=$this->searchBank($dataBankLike);
                $data['nameDestiny']=$dataBankLike['name'];
                $data['descriptionDestiny']=$dataBankLike['description'];
            }
        }

        return view('editTransferBank',$data);
    }

    public function addTransfersBank(Request $request){
        $data=$request->only(['idBankOrigin','idBankDestiny','description','value']);

        $request->validate([
            'idBankOrigin'=>['required','int'],
            'idBankDestiny'=>['required','int'],
            'description'=>['required','string'],
            'value'=>['required','string']
        ]);

        if($request->has(['idBankOrigin','idBankDestiny','description','value'])){
            $transferBank=new TransfersBank();
            $transferBank->id_origin_bank=$data['idBankOrigin'];
            $transferBank->id_destiny_bank=$data['idBankDestiny'];
            $transferBank->description=$data['description'];
            $transferBank->value=$data['value'];
            $transferBank->date=date('Y-m-d');
            $transferBank->time=date('H:i:s');
            $transferBank->save();
        }

        return redirect()->route('allTransfersBank');
    }

    public function updateTransfersBank(Request $request){
        $data=$request->only(['idTransfersBank','idBankOrigin','idBankDestiny','description','value']);

        $request->validate([
            'idBankOrigin'=>['required','int'],
            'idBankDestiny'=>['required','int'],
            'description'=>['required','string'],
            'value'=>['required','string'],
            'idTransfersBank'=>['required','int']
        ]);

        if($request->has(['idBankOrigin','idBankDestiny','description','value'])){
            $transferBank=TransfersBank::where('id',$data['idTransfersBank'])->first();
            $transferBank->id_origin_bank=$data['idBankOrigin'];
            $transferBank->id_destiny_bank=$data['idBankDestiny'];
            $transferBank->description=$data['description'];
            $transferBank->value=$data['value'];
           
            $transferBank->save();
        }

        return redirect()->route('allTransfersBank');
    }

    public function deleteTransferBank($idTransferBank){
        if(!empty($idTransferBank)){
            $transfer=TransfersBank::where('id',$idTransferBank)->first();
            $transfer->delete();
        }

        return redirect()->route('allTransfersBank');
    }

  
    public function allInternalAccounts(Request $request){
        $data=[];
        $data['internalAccounts']=InternalAccount::all();
        $data['name']="";
        $data['description']="";

        $dataInternalAccountLike=$request->only(['name','description']);
        
        if($request->hasAny(['name','date','time'])){
            $query=InternalAccount::query();
            
            foreach ($dataInternalAccountLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            $data['internalAccounts']=$query->get();
            $data['name']= $dataInternalAccountLike['name'];
            $data['description']=$dataInternalAccountLike['description'];
        }

        $data['users']=User::where('status',1)
            ->where('type',"!=",1)
            ->where('type',"!=",5)
            ->where('type',"!=",6)
            ->where('type',"!=",4)
            ->get();

        if(Auth::user()->type!=1){
            $internalAccountsPermissionUser=[];
            foreach ($data['internalAccounts'] as $key => $internalAccount) {
                $id_user_permission=explode(',',$internalAccount->id_user_permission);

                if(in_array(Auth::user()->id,$id_user_permission)){
                    $internalAccountsPermissionUser[]=$internalAccount;
                }
            }

            $data['internalAccounts']=$internalAccountsPermissionUser;
        }
        
        return view('allInternalAccounts',$data);
    }

    public function addInternalAccount(Request $request){
        $data=$request->only(['name','description','id_user_permission']);
        $request->validate([
            'name'=>['required','string','max:8'],
            'description'=>['required','string','max:450']
        ]);

        if($request->has(['name','description'])){
            $internalAccount=new InternalAccount();
            $internalAccount->name=$data['name'];
            $internalAccount->description=$data['description'];
            $idUsersPermission=$request->has('id_user_permission')!=""?implode(',',$data['id_user_permission']):"";
            $internalAccount->id_user_permission=$idUsersPermission;
            $internalAccount->save();
        }

        return redirect()->route('allInternalAccounts');
    }

    public function updateInternalAccount(Request $request){
        $data=$request->only(['idInternalAccount','name','description','id_user_permission']);
        $request->validate([
            'idInternalAccount'=>['required','int'],
            'name'=>['required','string','max:150'],
            'description'=>['required','string','max:450']
        ]);

        if($request->has(['idInternalAccount','name','description'])){
            $internalAccount=InternalAccount::where('id',$data['idInternalAccount'])->first();
            $internalAccount->name=$data['name'];
            $internalAccount->description=$data['description'];
            $idUsersPermission=$request->has('id_user_permission')!=""?implode(',',$data['id_user_permission']):"";
            $internalAccount->id_user_permission=$idUsersPermission;
            $internalAccount->save();
        }

        return redirect()->route('allInternalAccounts');
    }

    public function deleteInternalAccount($idInternalAccount){
        if(!empty($idInternalAccount)){
            $internalAccount=InternalAccount::where('id',$idInternalAccount)->first();
            $internalAccount->delete();
        }
        return redirect()->route('allInternalAccounts');
    }

    public function allProgramedPayment(Request $request){
        $data=[];
        $data['allProgramedPayment']=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
            ->join('clients','programed_payments.id_provider','clients.id')
            ->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
            ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')
            ->get([
                'programed_payments.*',
                'clients.name as nameProvider',
                'clients.company_name as companyProvider',
                'internal_accounts.name as internalAccount',
            ]);
        
        $dataFilterDate=$request->only(['startDate','finalDate']);
        $data['startDate']="";
        $data['finalDate']="";
        
        if($request->hasAny(['startDate','finalDate'])){
            $data['allProgramedPayment']=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
            ->join('clients','programed_payments.id_provider','clients.id')
            ->whereBetween('date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->get([
                'programed_payments.*',
                'clients.name as nameProvider',
                'clients.company_name as companyProvider',
                'internal_accounts.name as internalAccount',
            ]);
            
            $data['startDate']=$dataFilterDate['startDate'];
            $data['finalDate']=$dataFilterDate['finalDate'];
        }    

        $data['internalAccountName']="";
        $data['provider']="";
        $data['date']="";
        $data['parcelConfirmation']="";
        $data['numParcel']="";
        $data['payment_date']="";

        $dataProgramedPaymentLike=$request->only(['internalAccountName','description']);
        $dataProgramedPaymentEquals=$request->only(['date','num']);  
        
        if($request->hasAny(['internalAccountName','description','provider','bankName','deadline'])){
            $query=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
            ->join('clients','programed_payments.id_provider','clients.id');
            
            foreach ($dataProgramedPaymentLike as $name => $value) {
                if($name=='internalAccountName'){
                    $name="internal_accounts.name";
                }

                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }

            foreach ($dataProgramedPaymentEquals as $name => $value) {
                if($value){
                    $query->where($name, '=' ,$value);
                }
            }

            $provider=$request->input('provider');
            if($provider){
                $query->where('clients.name','LIKE', '%' . $provider . '%')
                    ->orwhere('clients.company_name','LIKE', '%' . $provider . '%');
            }
            
            $data['allProgramedPayment']=$query->get([
                'programed_payments.*',
                'clients.name as nameProvider',
                'clients.company_name as companyProvider',
                'internal_accounts.name as internalAccount',
            ]);
            
            $data['internalAccountName']=$dataProgramedPaymentLike['internalAccountName'];
            $data['provider']=$provider;
            $data['date']=$dataProgramedPaymentEquals['date'];
            $data['numParcel']=$dataProgramedPaymentEquals['num'];
        }
        $this->verifyLaterAlert($data['allProgramedPayment']);
        if(Auth::user()->type!=1){
            $allProgramedPaymentPermissionUser=[];
            foreach ($data['allProgramedPayment'] as $key => $programedPayment) {
                $internalAccount=InternalAccount::where('id',$programedPayment->id_internal_account)->first();
                $id_user_permission=explode(',',$internalAccount->id_user_permission);

                if(in_array(Auth::user()->id,$id_user_permission)){
                    $allProgramedPaymentPermissionUser[]=$programedPayment;
                }
            }
            
            $data['allProgramedPayment']= $allProgramedPaymentPermissionUser;
        }
        
        return view('allProgramedPayment',$data);
    }

    private function verifyLaterAlert($allAlertsPayment){
    
        foreach ($allAlertsPayment as $key => $alert) {
            $now = strtotime(date('Y-m-d')); 
            $dateAlert=strtotime($alert->date);
            $days = ($dateAlert - $now) /86400;
            if($days < 0 && $alert->status != 1){
                $alert=ProgramedPayment::where('id',$alert->id)->first();
                $alert->status=3;
                $alert->save();
            }
        }
    }

    public function addProgramedPaymentView(Request $request){
        $data=[];
        $data['internal_accounts']=InternalAccount::all();
        $data['internalAccountName']="";
        $data['internalAccountDescription']="";
        
        $dataInternalAccount=$request->only(['internalAccountName','internalAccountDescription']);
        if($request->hasAny(['internalAccountName','internalAccountDescription'])){
            $data['internal_accounts']=$this->searchInternalAccount($dataInternalAccount);
            $data['internalAccountName']=$dataInternalAccount['internalAccountName'];
            $data['internalAccountDescription']=$dataInternalAccount['internalAccountDescription'];
        }

        
        $data['banks']=Bank::all();
        $data['bankName']="";
        $data['description']="";
        
        $dataBankLike=$request->only(['bankName','description']);
        if($request->hasAny(['bankName','description'])){
            $data['banks']=$this->searchBank($dataBankLike);
            $data['bankName']=$dataBankLike['bankName'];
            $data['description']=$dataBankLike['description'];
        }

        return view('addProgramedPayment',$data);
    }

    public function addProgramedPayment(Request $request){
        $data=$request->only(['idInternalAccount','idProvider','parcelConfirmation','days','month',
            'firstParcel','value','description','numberParcel','date_payment','pay_now']);
        $request->validate([
            'idInternalAccount'=>['required','int'],
            'idProvider'=>['required','int'],
            'days'=>['int','nullable'],
            'month'=>['int','nullable'],
            'value'=>['required','string'],
            'firstParcel'=>['date','nullable'],
            'numberParcel'=>['int','nullable'],
            'description'=>['required','string','max:450'],
            'date_payment'=>['date','nullable']
        ]);

        if($request->has(['idInternalAccount','idProvider','parcelConfirmation',
            'firstParcel','value','description','numberParcel'])){
            
            if($data['parcelConfirmation']==1){
                $this->addAllProgramedPaymentParcel($data);
            
            }else if($data['parcelConfirmation']==2){
                $idProgramedPayment=$this->addProgramedPaymentParcel($data['idInternalAccount'],$data['idProvider'],$data['description']
                ,$data['value'],1,1,$data['date_payment'],$data['value']);
                $payNow=$request->input('payNow');
                
                if($payNow && $payNow==1){
                    return redirect()->route('seeProgramedPayment',['idProgramedPayment'=>$idProgramedPayment]);
                }
            }
        }

        return redirect()->route('allProgramedPayment');
    }

    private function addAllProgramedPaymentParcel($data){
        $days=$data['days'];
        $monthChoise=$data['month'];
        $firstParcel=$data['firstParcel'];
        $firstParcelDateDivide=explode('-',$firstParcel);
        $yearFirstParcel=$firstParcelDateDivide[0];
        $monthFirstParcel=$firstParcelDateDivide[1];
        $numberParcels=$data['numberParcel'];
        $value=floatVal(str_replace(['.',','],['','.'],$data['value']));
        
        $data['totalValue']=$value;
        $data['valueParcel']=$value/$numberParcels;
        $data['valueParcel']=number_format($data['valueParcel'],2,",",".");
        $numberYearsParcel=1;
        if($numberParcels>=12){
            $numberYearsParcel=ceil($numberParcels/12);
        }

        if($monthChoise != ""){
            $this->addProgramedPaymentParcelMonth($yearFirstParcel,$numberYearsParcel,$monthFirstParcel,
                $monthChoise,$numberParcels,$data);
        }else if($days != ""){
            $this->addProgramedPaymentParcelDays($firstParcel,$numberParcels,$data);
        }
        
    }

    private function addProgramedPaymentParcelDays($firstParcel,$numberParcels,$data){
        $this->addProgramedPaymentParcel($data['idInternalAccount'],$data['idProvider'],$data['description']
            ,$data['valueParcel'],$data['numberParcel'],1,$data['firstParcel'],$data['totalValue']);
        
        $date=date('Y-m-d',strtotime('+'.$data['days']." days",strtotime($firstParcel)));
        
        for ($parcel=2; $parcel<=$numberParcels;$parcel++) { 
            $this->addProgramedPaymentParcel($data['idInternalAccount'],$data['idProvider'],$data['description']
                ,$data['valueParcel'],$data['numberParcel'],$parcel,$date,$data['totalValue']);

            $date=date('Y-m-d',strtotime('+'.$data['days']." days",strtotime($date)));
        }
    }

    private function addProgramedPaymentParcelMonth($yearFirstParcel,$numberYearsParcel,
        $monthFirstParcel,$monthChoise,$numberParcels,$data){
        $this->addProgramedPaymentParcel($data['idInternalAccount'],$data['idProvider'],$data['description']
            ,$data['valueParcel'],$data['numberParcel'],1,$data['firstParcel'],$data['totalValue']);
        $parcel=1;

        for ($year=$yearFirstParcel; $year <= $yearFirstParcel+$numberYearsParcel; $year++) { 
            for($month=1;$month<=12;$month++){
                if($year==$yearFirstParcel){
                    if($month>=$monthFirstParcel+1){
                        $finalDate=$year."-".$month."-".$monthChoise;
                        $parcel++;
                        $this->addProgramedPaymentParcel($data['idInternalAccount'],$data['idProvider'],$data['description']
                            ,$data['valueParcel'],$data['numberParcel'],$parcel,$finalDate,$data['totalValue']);
                    }

                    if($parcel==$numberParcels){
                        break;
                    }
                }else{
                    if($parcel==$numberParcels){
                        break;
                    }
                    $finalDate=$year."-".$month."-".$monthChoise;
                    $parcel++;
                    $this->addProgramedPaymentParcel($data['idInternalAccount'],$data['idProvider'],$data['description']
                            ,$data['valueParcel'],$data['numberParcel'],$parcel,$finalDate,$data['totalValue']);
                }
            }
        }
    }

    private function addProgramedPaymentParcel($idInternalAccount,$idProvider,$description,$value,
        $totalNumberParcels,$number,$datePayment,$totalValue){
        $programedPayment=new ProgramedPayment();
        $programedPayment->id_internal_account=$idInternalAccount;
        $programedPayment->id_provider=$idProvider;
        $programedPayment->description=$description;
        $programedPayment->value=$value;
        $programedPayment->num=$number;
        $programedPayment->totalNumberParcels=$totalNumberParcels;
        $programedPayment->date=$datePayment;
        $programedPayment->status=2;
        $programedPayment->totalValue=$totalValue;
        $programedPayment->save();

        return $programedPayment->id;
    }

    public function deleteProgramedPayment($idProgramedPayment){
        if(!empty($idProgramedPayment)){
            $programedPayment=ProgramedPayment::where('id',$idProgramedPayment)->first();
            $programedPayment->delete();
        }

        return redirect()->route('allProgramedPayment');
    }

    public function seeProgramedPayment($idProgramedPayment,Request $request){
        $data=[];
        $data['programedPayment']=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
        ->join('clients','programed_payments.id_provider','clients.id')
        ->where('programed_payments.id',$idProgramedPayment)
        ->first([
            'programed_payments.*',
            'clients.name as nameProvider',
            'clients.company_name as companyProvider',
            'internal_accounts.name as internalAccount',
        ]);

        $data['banks']=Bank::where('status',1)->get();
        $data['bankName']="";
        $data['description']="";
        
        $dataBankLike=$request->only(['bankName','description']);
        if($request->hasAny(['bankName','description'])){
            $data['banks']=$this->searchBank($dataBankLike);
            $data['bankName']=$dataBankLike['bankName'];
            $data['description']=$dataBankLike['description'];
        }

        
        return view('seeProgramedPayment',$data);    
    }

    private function searchBank($dataBankLike){
        $query=Bank::query();
        $banks=[];    
        foreach ($dataBankLike as $name => $value) {
            if($name=="bankName"){
                $name="name";
            }
            if($value){
                $query->where($name, 'LIKE', '%' . $value . '%');
            }
        
            $banks=$query->get();
            
        }

        return $banks;
    }

    private function searchInternalAccount($dataInternalAccount){
        $query=InternalAccount::query();
        $internalAccounts=[];    
        foreach ($dataInternalAccount as $name => $value) {
            if($name=="internalAccountName"){
                $name="name";
            }

            if($name=="internalAccountDescription"){
                $name="description";
            }
            
            if($value){
                $query->where($name, 'LIKE', '%' . $value . '%');
            }
        
            $internalAccounts=$query->get();
            
        }

        return $internalAccounts;
    }

    

    public function payProgramedPayment(Request $request){
        $data=$request->only(['idProgramedPayment','idBank','value_payment','payment_date',
            'payment_method','proof_payment']);
        $request->validate([
            'idProgramedPayment'=>['required','int'],
            'idBank'=>['required','int'],
            'payment_date'=>['required','date'],
            'payment_method'=>['required'],
            'value_payment'=>['required','string'],
            'proof_payment'=>['mimes:txt,DOC,pdf']
        ]);

        if($request->has(['idBank','payment_date','payment_method','proof_payment'])){
            $pathFile="";
            if(!empty($data['proof_payment'])){
                $file=$data['proof_payment'];
                if($file->isValid()){
                    $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    $pathContact="proof_payment/";
                    $file->storeAs($pathContact,$contactFile);
                    $pathFile=$pathContact.$contactFile;
                }
            }

            $programedPayment=ProgramedPayment::where('id',$data['idProgramedPayment'])->first();
            $programedPayment->payment_method=$data['payment_method'];
            $programedPayment->payment_date=$data['payment_date'];
            $programedPayment->idBank=$data['idBank'];
            $programedPayment->value_payment=$data['value_payment'];
            $programedPayment->proof_payment=$pathFile;
            $programedPayment->status=1;
            $programedPayment->save();
        }

        return redirect()->route('allProgramedPayment');
    }


}

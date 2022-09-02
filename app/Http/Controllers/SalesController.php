<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lot;
use App\Models\Clients;
use App\Models\Sales;
use App\Models\Parcels;
use App\Models\ContactSale;
use App\Models\Interprises;
use App\Models\Bank;
use App\Models\BankSlip;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type==5){
                return redirect()->route('accessDenied');
            }
            return $next($request);
        },['except'=>['seeSale']]); 
    }

    public function index(Request $request){
        $data=[];
        $data['sales']=Sales::join('interprises','sales.id_interprise','=','interprises.id')
                     ->join('lots','sales.id_lot','lots.id')
                     ->where('type','!=',0)
                     ->get(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
                        ,'lots.block as lot_block']);
        
        $saleTypeUser=[];
        if(Auth::user()->type==6){
            foreach ($data['sales'] as $key => $sale) {
                $saleIds=explode(',',$sale->clients);
                
                if(in_array(Auth::user()->idClient,$saleIds)){
                    $saleTypeUser[]=$sale;
                }
            }

            $data['sales']=$saleTypeUser;
        }

        $data['interprises']=Interprises::all();
        $data['contract_number']="";
        $data['lot_number']=""; 
        $data['interprise_name']=""; 
        $data['lot']=""; 
        $data['block']="";
        $data['type']=""; 
        $data['date']=""; 

        $dataSalesLike=$request->only(['contract_number','interprise_name','lot_number','block']);
        $dataSaleEquals = $request->only('date','type');
        
        $data['orderContract']=0;
        $data['orderLot']=0;
        $data['orderBlock']=0;
        $data['orderInterprise']=0;
        
        if($request->hasAny(['contract_number','interprise_name','lot_number','block','type',
            'sales.date','orderContract','orderLot','orderBlock','orderInterprise'])){
            
            $query=Sales::join('interprises','sales.id_interprise','=','interprises.id',)
                        ->join('lots','sales.id_lot','lots.id');
            
            foreach ($dataSalesLike as $name => $value) {
                if($value){
                   if($name=="interprise_name"){
                        $name="interprises.name";
                   }
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataSaleEquals as $name => $value) {
                if($name=="date"){
                    $name='sales.begin_contract_date';
                }
                if ($value) { 
                    $query->where($name, '=', $value);
                }
            }

            $query->where('type','!=',0);
            
            if($request->input('orderContract') != 0 || $request->input('orderLot') != 0 
                || $request->input('orderBlock') != 0 || $request->input('orderInterprise')){

                $orderContract= $request->input('orderContract');
                $orderLot= $request->input('orderLot');
                $orderBlock= $request->input('orderBlock');
                $orderInterprise= $request->input('orderInterprise');

                $type=0;

                if($orderContract != 0){
                    $type=1;
                    $query=$this->filterOrderBy($query,$type,$orderContract);
                }

                if($orderLot != 0){
                    $type=2;
                    $query=$this->filterOrderBy($query,$type,$orderLot);
                }

                if($orderBlock != 0){
                    $type=3;
                    $query=$this->filterOrderBy($query,$type,$orderBlock);
                }

                if($orderInterprise != 0){
                    $type=4;
                    $query=$this->filterOrderBy($query,$type,$orderInterprise);
                }
                
                $data['orderContract']=$orderContract;
                $data['orderLot']=$orderLot;
                $data['orderBlock']=$orderBlock;
                $data['orderInterprise']=$orderInterprise;
            }    
            
            
            $data['sales']=$query->get(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
                ,'lots.block as lot_block']);

            $data['contract_number']=$dataSalesLike['contract_number'];
            $data['interprise_name']=$dataSalesLike['interprise_name']; 
            $data['lot_number']=$dataSalesLike['lot_number']; 
            $data['date']=$dataSaleEquals['date'];
            $data['block']=$dataSalesLike['block'];
            $data['type']=$dataSaleEquals['type'];
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

        if(Auth::user()->type!=1){
            $salesPermissionUser=[];
            foreach ($data['sales'] as $key => $sale) {
                $interprise=Interprises::where('id',$sale->id_interprise)->first();
                $id_user_permission=explode(',',$interprise->id_user_permission);

                if(in_array(Auth::user()->id,$id_user_permission)){
                    $salesPermissionUser[]=$sale;
                }
            }

            $data['sales']=$salesPermissionUser;
        }
        
        return view('allSales',$data);
    }

    private function filterOrderBy($query,$type,$order){
        $orderValue=[0=>'',1=>'ASC',2=>'DESC'];
        $typeOrder=[0=>'',1=>'contract_number',2=>'lots.lot_number',3=>'lots.block',4=>'interprise_name'];
       
        $orderValue=strval($orderValue[$order]);
        $typeOrder=strval($typeOrder[$type]);
        
        return $query->orderBy($typeOrder,$orderValue);
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


    public function add(Request $request){
        $data=[];
        $data['clients']=Clients::all();
        
        $data['lots']=Lot::join('interprises','lots.id_interprise','=','interprises.id')
            ->where('lots.available',1)
            ->get(['lots.*','interprises.id as idInterprise','interprises.name as interprise_name',
                    'interprises.observation as interprise_observation']);
        
        $data['interprise_name']="";
        $data['lot_number']="";
        $data['block']="";
        $data['area']="";
        $data['observation']="";
        $data['index']=DB::table('index')->get();

        $dataLotLike=$request->only(['interprise_name','block','lot_number','area','observation']);
        
        if($request->hasAny(['interprise_name','block','lot_number','area','observation'])){
            $query=Lot::join('interprises','lots.id_interprise','=','interprises.id');
            
            foreach ($dataLotLike as $name => $value) {
                if($name=='interprise_name'){
                    $name='interprises.name';
                }
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
           
            $data['lots']=$query->where('lots.available',1)->get(['lots.*','interprises.id as idInterprise','interprises.name as interprise_name',
            'interprises.observation as interprise_observation']);;
            
            $data['interprise_name']=$dataLotLike['interprise_name'];
            $data['block']=$dataLotLike['block'];
            $data['lot_number']=$dataLotLike['lot_number'];
            $data['area']=$dataLotLike['area'];
            $data['observation']=$dataLotLike['observation'];
        }


        $dataSale=$request->only(['id_interprise','id_lot','id_clients','client_payment_id','id_clients_porc','contract_number','value','expiration_day',
            'index','input','descont','first_parcel','parcels','type','value_parcel','contractFile','annual_rate','minimum_variation']);
        
        if($request->hasAny(['id_interprise','id_lot','id_clients','client_payment_id','id_clients_porc','contract_number','value','expiration_day',
            'index','input','descont','first_parcel','parcels','type','value_parcel','contractFile','annual_rate','minimum_variation'])){
            
            $this->validator($dataSale);
            $sale=new Sales();   
            $sale->id_interprise=$dataSale['id_interprise'];
            $sale->id_lot=$dataSale['id_lot'];
            $sale->client_payment_id=$dataSale['client_payment_id'];
            $sale->contract_number=$dataSale['contract_number'];
            $clients=implode(',',$dataSale['id_clients']);
            $sale->clients=$clients;
            $clients_porc=implode(',',$dataSale['id_clients_porc']);
            $sale->id_clients_porc=$clients_porc;
            $sale->value=$dataSale['value'];
            $sale->value_parcel=$dataSale['value_parcel'];
            $sale->expiration_day=$dataSale['expiration_day'];
            $sale->index=$dataSale['index'];
            $sale->input=$dataSale['input'];
            $sale->descont=$dataSale['descont'];
            $sale->first_parcel=$dataSale['first_parcel'];
            $sale->parcels=$dataSale['parcels'];
            $sale->annual_rate=$dataSale['annual_rate'];
            $sale->minimum_variation=$dataSale['minimum_variation'];
            $sale->propose_date=date('Y-m-d');
            $sale->type=1;
            $sale->save();
            
            return redirect()->route('allSales');
        }    
        
        return view('addSale',$data);
    }

    public function suspendSale($idSale){
        if(!empty($idSale)){
            $parcels=Parcels::where('id_sale',$idSale)->get();
            foreach ($parcels as $key => $parcel) {
                $parcel->delete();
            }

            $bankSlips=BankSlip::where('id_sale',$idSale)->get();
            foreach ($bankSlips as $key => $bankSlip) {
                $bankSlip->delete();
            }

            DB::table('cancel_contact_info')->where('id_sale',$idSale)->delete();
           
            DB::table('change_lot_info')->where('id_sale',$idSale)->delete();
            DB::table('juridical_contacts')->where('id_sale',$idSale)->delete();
            DB::table('notification_index_value')->where('id_sale',$idSale)->delete();
          
            DB::table('refinancing_info')->where('id_sale',$idSale)->get();
            DB::table('reissue_contact_info')->where('id_sale',$idSale)->delete();
            DB::table('contact_sale')->where('id_sale',$idSale)->delete();
          
            $sale=Sales::where('id',$idSale)->first();
            $sale->delete();

            $lot=Lot::where('id',$sale->id_lot)->first(); 
            $lot->available=1;
            $lot->save();
            
        }

        return redirect()->route('allSales');
    }

    public function finishContractSale(Request $request){
        $idSale="";
        $dataSale=$request->only(['idSale','contractFile']);
        
        if($request->has('idSale')){
            $pathContractArray=[];
            if(count($dataSale['contractFile']) > 0){
                foreach ($dataSale['contractFile'] as $key => $file) {
                    $contractFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    if($file->getClientOriginalExtension() == "php" || $file->getClientOriginalExtension() == "js" ){
                        return redirect()->route('seeSale',['idSale'=>$dataSale['idSale']])
                            ->withErrors("Extensão inválida!!");
                    }
                    $pathContract="contractSale/";
                    $file->storeAs($pathContract,$contractFile);
                    $pathContractArray[]=$pathContract.$contractFile;
                }
            }

            $idSale=$request->input('idSale');
            $sale=Sales::where('id',$idSale)->first();
            $parcels=Parcels::where('id_sale',$idSale)->get();
            $this->verifyLateParcel($parcels);
            $this->registerParcels($sale->id,$sale->value_parcel,$sale->parcels,
            $sale->first_parcel,$sale->expiration_day);
            $sale->type=2;
            $sale->contract=implode('|',$pathContractArray);
            $sale->begin_contract_date=date('Y-m-d');
            $sale->save();    

            $interprise=Lot::where('id',$sale->id_lot)->first();
            $interprise->available=2;
            $interprise->save();

        }

       return redirect()->route('seeSale',['idSale'=>$idSale]);
    }

    public function finishSale(Request $request){
        $idSale="";
        $dataSale=$request->only(['idSale','finishSaleFile']);
        if($request->has('idSale')){
            $pathFilesArray=[];
            if(count($dataSale['finishSaleFile']) > 0){
                foreach ($dataSale['finishSaleFile'] as $key => $file) {
                    $almostFinishFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    $pathFile="finishFileSale/";
                    $file->storeAs($pathFile,$almostFinishFile);
                    $pathFilesArray[]=$pathFile.$almostFinishFile;
                }
            }

            $idSale=$request->input('idSale');
            $sale=Sales::where('id',$idSale)->first();
            $sale->type=4;
            $sale->finishFileSale=implode('|',$pathFilesArray);
            $sale->save();
        }

       return redirect()->route('seeSale',['idSale'=>$idSale]);
    }

    public function almostFinishSale(Request $request){
        $idSale="";
        $dataSale=$request->only(['idSale','almostFinishSale']);
        if($request->has('idSale')){
            $pathFilesArray=[];
            if(count($dataSale['almostFinishSale']) > 0){
                foreach ($dataSale['almostFinishSale'] as $key => $file) {
                    $almostFinishFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    $pathFile="almostFinishFile/";
                    $file->storeAs($pathFile,$almostFinishFile);
                    $pathFilesArray[]=$pathFile.$almostFinishFile;
                }
            }

            $idSale=$request->input('idSale');
            $sale=Sales::where('id',$idSale)->first();
            $sale->type=6;
            $sale->almostFinishFile=implode('|',$pathFilesArray);
            $sale->save();
        }

       return redirect()->route('seeSale',['idSale'=>$idSale]);
    }

    public function registerParcels($idSale,$valueParcel,$numberParcels,$firstParcelDate,$expiredDay){
        $this->addParcel($idSale,1,$firstParcelDate,$valueParcel,1);
        
        $firstParcelDateDivide=explode('-',$firstParcelDate);
        $yearFirstParcel=$firstParcelDateDivide[0];
        $monthFirstParcel=$firstParcelDateDivide[1];
        $numberYearsParcel=1;
        
        if($numberParcels>=12){
            $numberYearsParcel=ceil($numberParcels/12);
        }
        $parcel=1;
        

        for ($year=$yearFirstParcel; $year <= $yearFirstParcel+$numberYearsParcel; $year++) { 
           for($month=1;$month<=12;$month++){
                if($year==$yearFirstParcel){
                    if($month>=$monthFirstParcel+1){
                        $finalDate=$year."-".$month."-".$expiredDay;
                        $parcel++;
                        $this->addParcel($idSale,$parcel,$finalDate,$valueParcel,1);
                    }
                    if($parcel==$numberParcels){
                        break;
                    }
                }else{
                    if($parcel==$numberParcels){
                        break;
                    }
                    $finalDate=$year."-".$month."-".$expiredDay;
                    
                    $parcel++;
                    $this->addParcel($idSale,$parcel,$finalDate,$valueParcel,1);
                }
            }
        }
    }

    private function addParcel($idSale,$numberActualParcel,$dateParcel,$valueParcel,$type){
        $parcel=new Parcels();
        $parcel->id_sale=$idSale;
        $parcel->num=$numberActualParcel;
        $correctDateParcel=$this->maxDayInTheMonth($dateParcel);
        $parcel->date=$correctDateParcel;
        $parcel->value=$valueParcel;
        $parcel->updated_value=$valueParcel;
        $parcel->status=2;
        $parcel->type=1;
        $parcel->send_bankSlip=0;
        $parcel->our_number=$this->createOurNumber($correctDateParcel);
        $parcel->save();
    }

    private function createOurNumber($dateParcel){
        $isOk=false;
        $our_number="";
        
        while($isOk==false){
            $our_number=rand(10000,99999);
            $parcel=Parcels::where('our_number',$our_number)->where('date',$dateParcel)->where('send_bankSlip',0)->first();
            if($parcel==null){
                $isOk=true;
            }
        }

        return $our_number;
    }

    private function maxDayInTheMonth($date){
        //Servirão de alertas para o while na hora de mecher nas datas
        $NUMBER_DAYS_OK=false;
        
        $divideDate=explode('-',$date);
        $day=$divideDate[2];
        $month=$divideDate[1];
        $year=$divideDate[0];
        
        $numberDaysInTheMonth=cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        /*Caso o num de dias seja maiorcom o numero do mes selecionado, irá rodar o laço, diminuindo os dias, 
            até ficar no dia maximo do mes*/
        while($NUMBER_DAYS_OK==false){
            if($day > $numberDaysInTheMonth){
                $day--;
            }else{
                $NUMBER_DAYS_OK=true;
            }
        }

        $data=$year.'-'.$month.'-'.$day;

        return $data;
   }

    
    public function seeSale($idSale,$idJuridical=null,Request $request){
        $data=[];
        $data['sale']=Sales::join('interprises','sales.id_interprise','=','interprises.id')
        ->join('lots','sales.id_lot','lots.id')
        ->where('sales.id',$idSale)
        ->first(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
           ,'lots.block as lot_block']);;
        
        $data['idJuridical']=$idJuridical;

        $clientsIds=explode(',',$data['sale']->clients);
        foreach ($clientsIds as $key => $clientID) {
            $data['clients'][]=Clients::where('id',$clientID)->first();
        }

        if($data['sale']->type==2){
            $this->verifyReajustParcels($idSale);
        }

        if($data['sale']->type==2){
            $parcels=Parcels::where('id_sale',$idSale)->where('status',"!=",1)
            ->orderBy('date','ASC')->get();
            $this->verifyLateParcel($parcels);
        }
        $data['parcels']=Parcels::where('id_sale',$idSale)->where('status',"!=",1)
            ->orderBy('date','ASC')->get();
        
        $data['parcels_unpaid']=count($data['parcels']);    
        $data['parcels_paid']=count(Parcels::where('id_sale',$idSale)->where('status',1)->orderBy('date')->get());    
        $data['later_parcels']=count(Parcels::where('id_sale',$idSale)->where('status',3)->get());;
        
        $data['firstParcel']="";
        $data['endParcel']="";
        $data['rest_value']=$this->getRestValue($idSale);
        $data['paid_value']=$this->getPaidValue($idSale);
        $data['later_value']=$this->getLaterValue($idSale);
        
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
            $data['parcels']=$query->where('id_sale',$idSale)->where('status',"!=",1)->orderBy('date','ASC')->get();
            $data['firstParcel']=$dataFilterParcel['firstParcel'];
            $data['endParcel']=$dataFilterParcel['endParcel'];
        }
        
        $data['num']="";
        $data['date']="";
        $data['value']="";
        $data['status']="";
        $data['reajust']="";

        $dataParcelsEquals=$request->only(['num','date','status']);
        $dataParcelsLike=$request->only(['value','reajust']);
        
        if($request->hasAny(['num','date','value','status'])){
            $query=Parcels::query();
            foreach ($dataParcelsLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }

            foreach ($dataParcelsEquals as $name => $value) {
                if($value){
                    $query->where($name, '=', $value);
                }
            }

            if(empty($dataParcelsEquals['status'])){
                $data['parcels']=$query->where('id_sale',$idSale)->where('status',"!=",1)->orderBy('date','ASC')->get();
            }else{
                $data['parcels']=$query->where('id_sale',$idSale)->orderBy('date','ASC')->get();
            }
            
            $data['num']=$dataParcelsEquals['num'];
            $data['date']=$dataParcelsEquals['date'];
            $data['status']=$dataParcelsEquals['status'];
        }
        
        if(Auth::user()->type == 6){
            
            $data['contact']=ContactSale::join('users','contact_sale.id_user','users.id')
                        ->where('id_sale',$idSale)
                        ->where('id_user',Auth::user()->id)
                        ->orderBy('contact_sale.register_date','DESC')
                        ->orderBy('contact_sale.status','DESC')
                        ->get(['contact_sale.*','users.name as user_name']);
            
            if($request->filled('justResolved')){
                $data['contact']=ContactSale::join('users','contact_sale.id_user','users.id')
                ->where('id_sale',$idSale)->where('id_user',Auth::user()->id)
                ->where('contact.sales.status','!=',1)
                ->orderBy('contact_sale.register_date','DESC')
                ->orderBy('contact.sales.status','DESC')
                ->get(['contact_sale.*','users.name as user_name']);

                $data['justResolved']=true;

            }

            if($this->verifySaleUserClient($idSale)==false){
                return redirect()->route('allSales');
            }            
         }else if(Auth::user()->type!=6){
            $data['contact']=ContactSale::join('users','contact_sale.id_user','users.id')
                    ->where('id_sale',$idSale)
                    ->orderBy('contact_sale.register_date','DESC')
                    ->orderBy('status','DESC')
                    ->get(['contact_sale.*','users.name as user_name']);
            
            if($request->filled('justResolved')){
                $data['contact']=ContactSale::join('users','contact_sale.id_user','users.id')
                ->where('id_sale',$idSale)->where('id_user',Auth::user()->id)
                ->where('contact_sale.status','!=',1)
                ->orderBy('contact_sale.register_date','DESC')
                ->orderBy('contact_sale.status','DESC')
                ->get(['contact_sale.*','users.name as user_name']);

                $data['justResolved']=true;
            }    
    
         }
        
        if($data['sale']->type==3){
            $data['parcels']=Parcels::where('id_sale',$idSale)->where('type',5)->get();
        }       
        
        $salePorcClient=explode(',',$data['sale']->id_clients_porc);
        $data['clients_porc']=[];
        foreach ($salePorcClient as $key => $porc) {
            $dividePorc=explode('-',$porc);
            $data['clients_porc'][]=$dividePorc[1];
        }
        $data['contracts']=explode("|",$data['sale']->contract);
        $data['change_owner_info']=DB::table('change_owner_info')->where('id_sale',$idSale)
            ->where('path_document_done',"!=","")->get();

        $data['cancel_contact_info']=DB::table('cancel_contact_info')->where('id_sale',$idSale)
            ->where('path_document_done',"!=","")->get();
        
        $data['number_now']="";
        if($data['sale']->type==2){
            $parcelNum=Parcels::where('id_sale',$idSale)->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
            ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')->first();
            if($parcelNum != null){
                $data['number_now']=Parcels::where('id_sale',$idSale)->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
                ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')->first()->num;
            }
        }

        $data['almostFinishSale']=false;
        if($this->verifyFinishSale($idSale) && $data['sale']->type != 4){
            $data['almostFinishSale']=true;
        }

        $data['almostFinishFiles']=[];
        if($data['sale']->type==4 || $data['sale']->type==6){
            $data['almostFinishFiles']=explode("|",$data['sale']->almostFinishFile);
        }

        $data['finishFiles']=[];
        if($data['sale']->type==4){
            $data['finishFiles']=explode("|",$data['sale']->finishFileSale);
        }

        $data['banks']=Bank::where('status',1)->get();
        
        $parcelArray=[];
        foreach ($data['parcels'] as $key => $parcel) {
            $parcelArray[]=$parcel;
            $bankSlip=BankSlip::where('id_parcel',$parcel->id)->first();
            if($bankSlip!=null){
                $parcelArray[$key]['bankSlip']=$bankSlip;
            }    
        }

        $data['index']=DB::table('index')->get();

        return view('seeSale',$data);
    }

    public function updateSale(Request $request){
        $dataSale=$request->only(['idSale','propose_date','minimum_variation','annual_rate','index']);
        
        $idSale=$dataSale['idSale'];
        $sale=Sales::where('id',$idSale)->first();

        $proposeDate=$request->filled('propose_date')?$dataSale['propose_date']:$sale->propose_date;
        $minimum_variation=$request->filled('minimum_variation')?$dataSale['minimum_variation']:0;
        $annual_rate=$request->filled('annual_rate')?$dataSale['annual_rate']:$sale->annual_rate;
        $index=$request->filled('index')?$dataSale['index']:$sale->index;

        $sale->propose_date=$proposeDate;
        $sale->minimum_variation=$minimum_variation;
        $sale->annual_rate=$annual_rate;
        $sale->index=$index;
        $sale->save();

        return redirect()->route('seeSale',['idSale'=>$idSale]);
    }

    private function verifyFinishSale($idSale){
        $sale=Sales::where('id',$idSale)->first();
        $parcelsSale=count(Parcels::where('id_sale',$idSale)->where('status',1)->get());
        $totalParcels=$sale->parcels;
        
        if($parcelsSale==$totalParcels){
            return true;
        }else{
            return false;
        }
    }

    private function verifySaleUserClient($idSale){
        $sale=Sales::where('id',$idSale)->first();
        $idClients=explode(',',$sale->clients);
        
        if(in_array(Auth::user()->idClient,$idClients)){
            return true;
        }else{
            return false;
        }
    }   

    private function getRestValue($idSale){
        $data['parcels']=Parcels::where('id_sale',$idSale)->get()->except('status',1);
       
        $valuesParcels=[];
        foreach ($data['parcels'] as $key => $parcel) {
            $updated_value=floatVal(str_replace(['.',','],['','.'],$parcel->updated_value));
            $valuesParcels[]=$updated_value;
        }
        
        return number_format(array_sum($valuesParcels),2,',','.');
    }

    private function getPaidValue($idSale){
        $data['parcels']=Parcels::where('status',1)->where('id_sale',$idSale)->get();
        
        $valuesParcels=[];
        foreach ($data['parcels'] as $key => $parcel) {
            $updated_value=floatVal(str_replace(['.',','],['','.'],$parcel->updated_value));
            $valuesParcels[]=floatVal($updated_value);
        }
        
        return number_format(array_sum($valuesParcels),2,',','.');
    }

    private function getLaterValue($idSale){
        $data['parcels']=Parcels::where('status',3)->where('id_sale',$idSale)->get();
        
        $valuesParcels=[];
        foreach ($data['parcels'] as $key => $parcel) {
            $updated_value=floatVal(str_replace(['.',','],['','.'],$parcel->updated_value));
            $valuesParcels[]=floatVal($updated_value);
        }
        
        return number_format(array_sum($valuesParcels),2,',','.');
    }

    private function verifyLateParcel($parcels){
        $now=date('Y-m-d',strtotime('NOW'));
        
        foreach ($parcels as $key => $parcel) {
            $dateParcel=date('Y-m-d',strtotime($parcel->date));
            if($now>$dateParcel && $parcel->status != 1){
                $parcelObject=Parcels::where('id',$parcel->id)->first();
                $parcelObject->status=3;
                $parcelObject->late_days=$this->lateDays($parcel);
                $parcelObject->save();
                $this->calcFineValueLaterParcel($parcel); 
            }
        }
    }

    private function calcFineValueLaterParcel($parcel){
        $lateRateDays=0.000333;
        $lateFine=2;
        $daysDiff=$this->lateDays($parcel);
        $value=floatVal(str_replace(['.',','],['','.'],$parcel->value));
        $lateFineCalc=number_format($value*$lateFine/100,2);
        $lateRateDaysCalc=number_format(($lateRateDays*$value)*$daysDiff,2);
        
        $updated_value=$value+$lateRateDaysCalc+$lateFineCalc;
        $added_value=$lateRateDaysCalc+$lateFineCalc;
        
        $lateFineCalc=number_format($lateFineCalc,2);
        
        $updated_value=number_format($updated_value,2);
        $updated_value=str_replace([',','.'],['',','],$updated_value);
        $added_value=number_format($added_value,2);

        $lateRateDaysCalc=number_format($lateRateDaysCalc,2);
        
        $parcel->updated_value=$updated_value;
        $parcel->late_fine=$lateFineCalc." (0,2%)";
        $parcel->late_rate=$lateRateDaysCalc.""." (".number_format($lateRateDays*$daysDiff,3)."%)";
        $parcel->added_value=$added_value;
        $parcel->save();
    }

    private function lateDays($parcel){
        $date=new Carbon($parcel->date);
        $now=Carbon::now();
        $daysDiff=$date->diff($now)->days;
        return $daysDiff;
    }

    private function verifyReajustParcels($idSale){
        $numberParcelTotal=count(Parcels::where('id_sale',$idSale)->get());
        $parcel=Parcels::where('id_sale',$idSale)->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
            ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')->first();
        
        if($parcel != null){
            $numParcel=$parcel->num;
            $numberTimeReadjust=$numberParcelTotal/12; 
            $allNumberParcelReadjust=[];
            
            for ($i=0,$numberParcelReadjust=0; $i < $numberTimeReadjust; $i++) { 
                $numberParcelReadjust=$numberParcelReadjust+12;    
                $allNumberParcelReadjust[]=$numberParcelReadjust;
            }

            if(in_array($numParcel,$allNumberParcelReadjust)){
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
        $parcelsReadjust=Parcels::where('num','>=',$parcelObject->num)
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

    public function allParcelsView(Request $request){
        $this->user = auth()->user();
        if($this->user->type!=1 && $this->user->type!=3){
            return redirect()->route('accessDenied');
        }

        $dataFilterDate=$request->only(['startDate','finalDate','startDatePayment','finalDatePayment']);
        $data['startDate']=date('Y-m-d',strtotime('-2 month'));
        $data['finalDate']=date('Y-m-d',strtotime('+2 month'));

        $data['parcels']=Parcels::join('sales','parcels.id_sale','=','sales.id')
            ->join('interprises','sales.id_interprise','interprises.id')
            ->whereBetween('parcels.date', [$data['startDate'], $data['finalDate']])
            ->orderBy('date','ASC')
            ->get(['parcels.*','sales.parcels as totalParcels','interprises.name as interprise_name',
                'sales.contract_number as contract_number','sales.id as idSale']);        
        
        $orderFilter=$request->only(['contractCheck','deadlineCheck','paymentDateCheck']);
        $data['contractCheck']="";
        $data['deadlineCheck']="";
        $data['paymentDateCheck']="";

        $data['startDatePayment']='';
        $data['finalDatePayment']='';
        
        if($request->hasAny(['startDate','finalDate','startDatePayment','finalDatePayment'])){
            $query=Parcels::query();
            
            $data['parcels']=$query->join('sales','parcels.id_sale','=','sales.id')
            ->join('interprises','sales.id_interprise','interprises.id')
            ->whereBetween('parcels.date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']]);
            
            if($request->hasAny(['startDatePayment','finalDatePayment'])){
                $dataFilterDate=$request->only(['startDate','finalDate','startDatePayment','finalDatePayment']);
                $query->whereBetween('pad_date', [$dataFilterDate['startDatePayment'], $dataFilterDate['finalDatePayment']]);
            }


            if($request->hasAny(['contractCheck','deadlineCheck','paymentDateCheck'])){
                foreach ($orderFilter as $name => $value) {
                    if($name=="contractCheck"){
                        $name="sales.contract_number";
                    }
        
                    if($name=="deadlineCheck"){
                        $name="date";
                    }
        
                    if($name=="paymentDateCheck"){
                        $name="pad_date";
                    }
                    
                    if($value){
                        $query->orderBy($name,'ASC');
                    }
                }
            }

            $data['parcels']=$query->orderBy('date','ASC')
            ->get(['parcels.*','sales.parcels as totalParcels','interprises.name as interprise_name',
                'sales.contract_number as contract_number','sales.id as idSale']);  
        }

        $data['startDate']=$request->has('startDate')?$dataFilterDate['startDate']:$data['startDate'];
        $data['finalDate']=$request->has('finalDate')?$dataFilterDate['finalDate']:$data['finalDate'];
        $data['startDatePayment']=$request->has('startDatePayment')?$dataFilterDate['startDatePayment']:'';
        $data['finalDatePayment']=$request->has('finalDatePayment')?$dataFilterDate['finalDatePayment']:'';
        $data['contractCheck']=$request->has('contractCheck')?$orderFilter['contractCheck']:'';
        $data['deadlineCheck']=$request->has('deadlineCheck')?$orderFilter['deadlineCheck']:'';
        $data['paymentDateCheck']=$request->has('paymentDateCheck')?$orderFilter['paymentDateCheck']:'';

       if($request->hasAny(['contractCheck','deadlineCheck','paymentDateCheck','laterParcel'])){
            $query=Parcels::query();
            foreach ($orderFilter as $name => $value) {
                if($name=="contractCheck"){
                    $name="sales.contract_number";
                }

                if($name=="deadlineCheck"){
                    $name="date";
                }

                if($name=="paymentDateCheck"){
                    $name="pad_date";
                }
                
                if($value){
                    $query->orderBy($name,'ASC');
                }
            }

            $data['parcels']=$query->join('sales','parcels.id_sale','=','sales.id')
            ->join('interprises','sales.id_interprise','interprises.id')
            ->whereBetween('parcels.date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
            ->orderBy('parcels.date','ASC')
            ->get(['parcels.*','sales.parcels as totalParcels','interprises.name as interprise_name',
            'sales.contract_number as contract_number','sales.id as idSale']);

            $data['contractCheck']=$request->has('contractCheck')?$orderFilter['contractCheck']:'';
            $data['deadlineCheck']=$request->has('deadlineCheck')?$orderFilter['deadlineCheck']:'';
            $data['paymentDateCheck']=$request->has('paymentDateCheck')?$orderFilter['paymentDateCheck']:'';
        }

        $data['interpriseName']="";
        $data['num']="";
        $data['date']="";
        $data['status']="";
        $data['contract_number']="";
        $data['pad_date']="";
        $data['our_number']="";
        $data['type']="";

        $dataParcelsEquals=$request->only(['num','date','status','pad_date','type']);
        $dataParcelsLike=$request->only(['contract_number','our_number','interpriseName']);
        
        if($request->hasAny(['num','interpriseName','date','status','type','contract_number','pad_date','our_number'])){
            $query=Parcels::query();
            foreach ($dataParcelsLike as $name => $value) {
                if($name=="contract_number"){
                    $name="sales.contract_number";
                }

                if($name=="interpriseName"){
                    $name="interprises.name";
                }

                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }

            foreach ($dataParcelsEquals as $name => $value) {
                if($name=="contract_number"){
                    $name="sales.contract_number";
                }

                if($name=="status"){
                    $name="parcels.status";
                }

                if($name=="type"){
                    $name="parcels.type";
                }

                if($value){
                    $query->where($name, '=', $value);
                }
            }

            if(empty($dataParcelsEquals['status'])){
                if($request->input('startDate') || $request->input('finalDate')){
                    $query->whereBetween('parcels.date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']]);
                }

                if($request->hasAny(['contractCheck','deadlineCheck','paymentDateCheck'])){
                    foreach ($orderFilter as $name => $value) {
                        if($name=="contractCheck"){
                            $name="sales.contract_number";
                        }
            
                        if($name=="deadlineCheck"){
                            $name="date";
                        }
            
                        if($name=="paymentDateCheck"){
                            $name="pad_date";
                        }
                        
                        if($value){
                            $query->orderBy($name,'ASC');
                        }
                    }
                }
                
                
                $data['parcels']=$query->where('status',"!=",1)
                    ->join('sales','parcels.id_sale','=','sales.id')
                    ->join('interprises','sales.id_interprise','interprises.id')
                    ->whereBetween('parcels.date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']])
                    ->orderBy('parcels.date','ASC')
                    ->get(['parcels.*','sales.parcels as totalParcels',
                    'interprises.name as interprise_name',
                    'sales.contract_number as contract_number','sales.id as idSale']);
            }else{
                if($request->input('startDate') || $request->input('finalDate')){
                    $query->whereBetween('parcels.date', [$dataFilterDate['startDate'], $dataFilterDate['finalDate']]);
                }

                if($request->hasAny(['contractCheck','deadlineCheck','paymentDateCheck'])){
                    foreach ($orderFilter as $name => $value) {
                        if($name=="contractCheck"){
                            $name="sales.contract_number";
                        }
            
                        if($name=="deadlineCheck"){
                            $name="parcels.date";
                        }
            
                        if($name=="paymentDateCheck"){
                            $name="parcels.pad_date";
                        }
                        
                        if($value){
                            $query->orderBy($name,'ASC');
                        }
                    }
                }
                
                $data['parcels']=$query->join('sales','parcels.id_sale','=','sales.id')
                    ->join('interprises','sales.id_interprise','interprises.id')
                    ->orderBy('parcels.date','ASC')
                    ->get(['parcels.*','sales.parcels as totalParcels','interprises.name as interprise_name',
                    'sales.contract_number as contract_number','sales.id as idSale']);
                }
                $data['num']=$dataParcelsEquals['num'];
                $data['interpriseName']=$dataParcelsLike['interpriseName'];
                $data['date']=$dataParcelsEquals['date'];
                $data['status']=$dataParcelsEquals['status'];
                $data['contract_number']=$dataParcelsLike['contract_number'];
                $data['pad_date']=$dataParcelsEquals['pad_date'];
                $data['type']=$dataParcelsEquals['type'];
            }

        

        $this->verifyLateParcel($data['parcels']);
        $data['banks']=Bank::where('status',1)->get();

        $parcelArray=[];
        foreach ($data['parcels'] as $key => $parcel) {
            $parcelArray[]=$parcel;
            $bankSlip=BankSlip::where('id_parcel',$parcel->id)->first();
            if($bankSlip!=null){
                $parcelArray[$key]['bankSlip']=$bankSlip;
            }    
        }

       
        return view('allParcels',$data);
    }
    
    public function payParcel(Request $request){
        $data=$request->only(['id','pad_value','pad_date','pad_description','allParcels','idBank']);
        $request->validate([
            'id'=>['required'],
            'pad_value'=>['required'],
            'pad_date'=>['required','date'],
            'pad_description'=>['required','string'],
            'idBank'=>['required']
        ]);

        if($request->has(['id','pad_value','pad_date','pad_description'])){
            $parcel=Parcels::where('id',$data['id'])->first();
            $parcel->status=1;
            $parcel->pad_value=$data['pad_value'];
            $parcel->pad_date=$data['pad_date'];
            $parcel->pad_description=$data['pad_description'];
            $parcel->idBankPayment=$data['idBank'];
            $parcel->save();
        }

        if($request->has('idSale')){
            return redirect()->route('seeSale',['idSale'=>$request->input('idSale')]);
        }else{
            return redirect()->route('allParcels');
        }
        
    }
    
    public function validatorContract($data){
        return Validator::make($data,
            ['contractFile'=>['required','mimes:txt,DOC,pdf']]
        ,$this->msgContract($data))->validate();
    }

    private function msgContract($data){
        return [
            'contractFile.required'=>'o arquivo não foi enviado!',
            'contractFile.mimes'=>"O contrato tem que ser do tipo txt,DOC ou pdf."
        ];
    }

    public function validator($data){
        return Validator::make($data,[
            'contract_number'=>['required','unique:sales'],
            'id_interprise'=>['required','int','nullable'],
            'id_lot'=>['required','int','nullable'],
            'value'=>['required','max:20','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'expiration_day'=>['required'],
            'index'=>['required','string','nullable'],
            'input'=>['max:20','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'descont'=>['max:20','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'first_parcel'=>['date','nullable'],
            'parcels'=>['required','int','nullable'],
            'annual_rate'=>['required','nullable'],
            'minimum_variation'=>['required','nullable'],
            'value_parcel'=>['required','max:20','regex:/^([0-9\.\,]{1,})$/','nullable'],
        ],$this->msgSale())->validate();
    }

    public function msgSale(){
        return[
            'contract_number.required'=>' O numero de contrato é obrigatório!',
            'contract_number.unique'=>' esse numero de contrato ja está sendo utilizado!',
            'id_interprise.required'=>'o id do empreendimento não foi enviado! Algo deu errado!',
            'id_interprise.int'=>'o id empreendimento tem que ser inteiro',
            'id_lot.required'=>'o id do lot não foi enviado! Algo deu errado!',
            'id_lot.int'=>'o id lot tem que ser inteiro',
            'value.required'=>'o campo saldo é obrigatório',
            'value.max'=>'o valor tem que ter no máximo 20 caracteres',
            'value.regex'=>'o valor está inválido',
            'expiration_day.required'=>'o dia de vencimento é obrigatório!',
            'index.string'=>'o indice tem que ser string',
            'input.max'=>'o valor tem que ter no máximo 20 caracteres',
            'input.regex'=>'o valor está inválido',
            'descont.regex'=>'o valor do desconto está inválido',
            'descont.max'=>'o desconto tem que ter no máximo 20 caracteres',
            'first_parcel.date'=>'a primeira parcela tem que ser uma data',
            'parcels.required'=>'o numero de parcelas é obrigatório!',
            'parcels.int'=>'o numero de parcelas tem que ser inteiro',
            'value_parcel.max'=>'o valor da parcela tem que ter no maximo 20 caracteres',
            'value_parcel.regex'=>'o valor da parcela está inválido',
            'annual_rate.required'=>'A taxa anual é obrigatória',
            'minimum_variation.required'=>'A variação minima é obrigatória',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Sales;
use App\Models\Clients;
use App\Models\Parcels;
use App\Models\Interprises;
use Illuminate\Support\Facades\DB;
use App\Models\Lot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');
        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type==6){
                return redirect()->route('accessDenied');
            }
            return $next($request);
        });   
    }

    public function index(Request $request){
        $data=[];
        $data['contacts']=ContactSale::join('sales','contact_sale.id_sale','=','sales.id')
            ->where('id_user',Auth::user()->id)
            ->where('status',2)->get(['contact_sale.*','sales.contract_number as contractNumber']);
        $data['where']="";
        $data['subject_matter']="";
        $data['type']="";
        $data['register_date']="";
        $data['deadline']="";
        $data['status']=2;

        $dataContactLike=$request->only(['where','subject_matter']);
        $dataContactEquals=$request->only(['type','register_date','deadline','status']);
        
        if($request->hasAny(['where','subject_matter','type','register_date','deadline','status'])){
            $query=ContactSale::query();
            foreach ($dataContactLike as $name => $value) {
                if($value){
                    $query->where($name,'LIKE','%'.$value.'%');
                }
            }

            foreach ($dataContactEquals as $name => $value) {
                if($value){
                    $query->where($name,"=",$value);
                }
            }
            
            $data['contacts']=$query->where('id_user',Auth::user()->id)->get();
            $data['where']=$dataContactLike['where'];
            $data['subject_matter']=$dataContactLike['subject_matter'];
            $data['type']=$dataContactEquals['type'];
            $data['register_date']=$dataContactEquals['register_date'];
            $data['deadline']=$dataContactEquals['deadline'];;
            $data['status']=$dataContactEquals['status'];;
        }

        return view('allContact',$data);
    }

    public function addView($idSale,$typeContact,$allContact=1,Request $request){
        $data=[];
        $data['idSale']=$idSale;
        $data['users']=User::where('status',1)->where('type',"!=",5)->get();
        
        $sale=Sales::join('interprises','sales.id_interprise','=','interprises.id')
        ->join('lots','sales.id_lot','lots.id')
        ->where('sales.id',$idSale)
        ->first(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
           ,'lots.block as lot_block']);

        $index=DB::table('index')->where('id',$sale->index)->first();   
        $data['indexName']=$index->name;
        $data['sale']=$sale;
        $data['idIndex']=$sale->index;
        $data['parcels_pad']=str_replace('.',',',$this->getPaidValue($sale->id));
        $data['number_parcels_pad']=count(Parcels::where('id_sale',$idSale)->where('status',1)->get());
        $data['allContact']=$allContact;

        if($typeContact==1){
            return view('contactsViews.severalContact',$data);
        
        }else if($typeContact==2){
            $data['clients']=[];
            $data['client_payment']=Clients::where('id',$sale->client_payment_id)->first();
            $data['clientsIds']=$sale->clients;
            $idsClients=explode(',',$sale->clients);
            foreach ($idsClients as $key => $idClient) {
                $client=Clients::where('id',$idClient)->first();
                $data['clients'][]=$client;
            }
            $salePorcClient=explode(',',$data['sale']->id_clients_porc);
            $data['clients_porc']=[];
            foreach ($salePorcClient as $key => $porc) {
                $dividePorc=explode('-',$porc);
                $data['clients_porc'][]=$dividePorc[1];
            }

            $data['clients_porc_sale']=$data['sale']->id_clients_porc;

            return view('contactsViews.changeOwner',$data);
        
        }else if($typeContact==3){
           return view('contactsViews.changeExpiredDate',$data);
        
        }else if($typeContact==4){
            // Calculos para o refinanciamento;
            $data['index']=DB::table('index')->get();
            //Verifica qual foi a ultima parcela a ser reajustada
            $lastParcelReajust=$this->getLastReajustValue($idSale);
           
            $data['refinancing_failed']=true;
            $firstParcelNotPad=Parcels::where('id_sale',$idSale)->where('status',"!=",1)->first();
            if($lastParcelReajust==null){
                //Se não houve nenhuma, estã sendo feito uma tentativa de refinanciamento antes da parcela numero 12, 
                //então ele pega a ultima parcela paga como base
                $sumIndexRate=$this->getSumIndexRate($firstParcelNotPad->date,$data['idIndex']);
            }else{
                //Se houve alguma, ele faz as somas dos indices de acordo com a ultima parcela reajustada   
                $sumIndexRate=$this->getSumIndexRate($lastParcelReajust->date,$data['idIndex']);
            }
            if($sumIndexRate>0){
                $rate_year=$sale->annual_rate;
                if($lastParcelReajust!=null){
                    $totalMonth=$this->getNumberMonthDiff($lastParcelReajust->date);
                }else{
                    $totalMonth=$this->getNumberMonthDiff($firstParcelNotPad->date);
                }
                $total_rate=($rate_year * $totalMonth / 12)+$sumIndexRate;
                
                $parcelNow=Parcels::where('id_sale',$idSale)->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
                ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')->first();
                $parcelNow=floatVal(str_replace(['.',','],['','.'],$parcelNow->value));
                
                $valueReadjust=$parcelNow*$total_rate/100;
                $newValueParcel=$parcelNow+$valueReadjust;
                $numberParcelsNotPaidAndLate=$this->getNumberParcelLateAndNotPaid($idSale);
                
                $totalRefinanciament=$newValueParcel*$numberParcelsNotPaidAndLate;
                $totalRefinanciament=number_format(strval($totalRefinanciament),2);
                $data['totalValueRefinanciament']=str_replace([',','.'],['',','],$totalRefinanciament);
                
                $data['totalLaterValue']=$this->getFineLateValue($idSale);
                $data['totalParcels']=$numberParcelsNotPaidAndLate;
                $data['refinancing_failed']=false;
            }else{
                $data['refinancing_failed']=true;
                $parcelNow=Parcels::where('id_sale',$idSale)->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
                ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')->first();
                
                $firstParcelNotPad=Parcels::where('id_sale',$idSale)->where('status',"!=",1)->first();
                $lastParcelReajust=$this->getLastReajustValue($idSale);
                $notifications=DB::table('notification_index_value')
                    ->join('sales','notification_index_value.id_sale','=','sales.id')
                    ->where('notification_index_value.done',0)
                    ->where('notification_index_value.type',2)
                    ->where('id_sale',$idSale)
                    ->get(['notification_index_value.*','sales.id as idSale','sales.contract_number as contractNumber']);
                
                if($lastParcelReajust==null){
                    if(count($notifications)==0){
                        $dateNow=$parcelNow->date;
                        $this->warningIndexValue($firstParcelNotPad->date,$dateNow,$sale,$data['idIndex']);
                    }
                }else{
                    if(count($notifications)==0){
                        $this->warningIndexValue($lastParcelReajust,$parcelNow->date,$sale,$data['idIndex']);
                    }
                }

                $data['notifications_index']=DB::table('notification_index_value')
                ->join('sales','notification_index_value.id_sale','=','sales.id')
                ->where('notification_index_value.done',0)->where('notification_index_value.type',2)
                ->where('id_sale',$idSale)
                ->get(['notification_index_value.*','sales.id as idSale','sales.contract_number as contractNumber']);;
            } 
            return view('contactsViews.refinancing',$data);
            
        
        }else if($typeContact==5){
            $lot=Lot::where('id',$sale->id_lot)->first();
            $future_value=floatVal(str_replace(['.',','],['','.'],$lot->future_value));
            $data['future_value']=$future_value;
            $sale_commission_value=strval($future_value*6/100);
            $data['sale_commission_value']=str_replace('.',',',number_format($sale_commission_value,2));
            $data['future_value']=$future_value;
            $sumIndexRate=$this->getSumIndexRate($sale->begin_contract_date,$data['idIndex']);
            $data['sumIndexRate']=$sumIndexRate;
            $valueReadjust=$sale_commission_value*$sumIndexRate/100;
            $total_sale_commission_value_adjusted=strval($sale_commission_value+$valueReadjust);
            $data['sale_commission_value_adjusted']=str_replace('.',',',number_format($total_sale_commission_value_adjusted,2));
            $total_parcels_pad=$this->getSumValueParcels($idSale);
            $input_value=floatVal(str_replace(['.',','],['','.'],$sale->input));
            $data['total_parcels_pad']=str_replace('.',',',$total_parcels_pad);
            $data['administrative_expenses']=str_replace('.',',',number_format($total_parcels_pad*10/100,2));
            
            $deadline=date('Y-m')."-26";
            $data['first_parcel']=date('Y-m-d',strtotime('+1 month',strtotime($deadline)));
            
            return view('contactsViews.cancel',$data);
        
        }else if($typeContact==6){
            $data['reissue']=1;
            $data['parcels_late']=Parcels::where('id_sale',$sale->id)->where('status',3)->get();
            $numberParcels=count($data['parcels_late']);
            if($numberParcels==0){
                $data['reissue']=0;
            }
            return view('contactsViews.reissue',$data);
        
        }else if($typeContact==7){
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

            $total_parcels_pad=$this->getSumValueParcels($idSale);
            $input_value=floatVal(str_replace(['.',','],['','.'],$sale->input));
            $data['total_parcels_pad']=$total_parcels_pad+$input_value;
            $data['number_parcels_pad']=count(Parcels::where('id_sale',$sale->id)->where('type',1)->where('status',1)->get());
            $data['number_parcels_to_pay']=count(Parcels::where('id_sale',$sale->id)->where('type',1)->where('status',"!=",1)->get());

            $data['actual_lot']=Lot::where('id',$sale->id_lot)->first();
            $data['actual_interprise']=Interprises::where('id',$sale->id_interprise)->first();

            $data['firstParcelDate']=date('Y-m-d',strtotime('+1 month',strtotime(date('Y-m-d'))));
            return view('contactsViews.changeLot',$data);
        
        }else if($typeContact==8){
            $data['usersJuridical']=User::where('status',1)->where('type',5)->get();
            return view('contactsViews.add_juridical',$data);
        }
    }


    private function getPaidValue($idSale){
        $data['parcels']=Parcels::where('status',1)->where('id_sale',$idSale)->get();
        
        $valuesParcels=[];
        foreach ($data['parcels'] as $key => $parcel) {
            $updated_value=floatVal(str_replace(['.',','],['','.'],$parcel->updated_value));
            $valuesParcels[]=floatVal($updated_value);
        }
        
        return number_format(array_sum($valuesParcels),2);
    }

    private function getSumValueParcels($idSale){
        $parcels=Parcels::where('id_sale',$idSale)->where('status',1)->where('type',1)->get();
        
        $valuesParcels=[];
        foreach ($parcels as $key => $parcel) {
            $value=floatVal(str_replace(['.',','],['','.'],$parcel->updated_value));
            $valuesParcels[]=$value;
        }
        return array_sum($valuesParcels);
    }

   
    private function verify_index_empty($index_value_date_first,$index_value_date_final,$idIndex){
        
        $date_first=date('Y-m',strtotime($index_value_date_first))."-01";
        $date_final=date('Y-m',strtotime('-1 month',strtotime($index_value_date_final)))."-01";
        $allIndex_value_readjust=DB::table('index_value')->where('idIndex',$idIndex)->where('month','>=',$date_first)
            ->where('month','<=',$date_final)->get();
        $dates_between=[];
        
        for ($i=0; $i <= count($allIndex_value_readjust) ; $i++) { 
            $date=date('Y-m-d',strtotime('+'.$i.'month',strtotime($index_value_date_first)));
            $dates_between[]=$date;
            if($date==$date_final){
                break;
            }
        }    

        $index_month=[];    
        foreach ($allIndex_value_readjust as $key => $value_index) {
            $index_month[]=$value_index->month;
        }
        $index_date_empty=[];
        foreach ($dates_between as $key => $date) {
            if(!in_array($date,$index_month)){
                $index_date_empty[]=$date;
            }
        }
        if(count($index_date_empty)>0){
            return true;
        }else{
            return false;
        }
    }

    private function warningIndexValue($index_value_date_first,$index_value_date_final,$sale,$idIndex){
        $date_first=date('Y-m',strtotime($index_value_date_first))."-01";
        $date_final=date('Y-m',strtotime('-1 month',strtotime($index_value_date_final)))."-01";
        $index_date_empty=[];
        
        if($date_first==$date_final){
            $index_date_empty[]=date('Y-m-01',strtotime('-1 month',strtotime($index_value_date_final)));
        }else{    
            $allIndex_value_readjust=DB::table('index_value')->where('idIndex',$idIndex)->where('month','>=',$date_first)
                ->where('month','<',$date_final)->orderBy('month','asc')->get();
      
            $dates_between=[];
            for ($i=0; $i <= count($allIndex_value_readjust) ; $i++) { 
                $date=date('Y-m-01',strtotime('+'.$i.'month',strtotime($index_value_date_first)));
                $dates_between[]=$date;
                if($date==$date_final){
                    break;
                }
            }    
            $index_month=[];    
            foreach ($allIndex_value_readjust as $key => $value_index) {
                $index_month[]=$value_index->month;
            }
            
            
            foreach ($dates_between as $key => $date) {
                if(!in_array($date,$index_month)){
                    $index_date_empty[]=$date;
                }
            }
        }
        $dateIndexMonth=implode(',',$index_date_empty);
        $parcel_readjust=" Indices das datas: ".$dateIndexMonth;
        DB::table('notification_index_value')->insert(['id_sale'=>$sale->id,
            'month_index_empty'=>$dateIndexMonth,'parcels_readjust'=>$parcel_readjust,
            'index'=>$sale->index,'type'=>2,'done'=>0]);
    }

    private function getFineLateValue($idSale){
        $parcelNow=Parcels::where('id_sale',$idSale)->whereraw('MONTH(date) = MONTH(CURRENT_DATE())')
        ->whereraw('YEAR(date) = YEAR(CURRENT_DATE())')->first();
        $parcelNow=floatVal(str_replace(['.',','],['','.'],$parcelNow->value));
        $total_later_value=$this->calcTotalLaterParcel($idSale);
        
        return $total_later_value;
    }

    private function getNumberParcelLateAndNotPaid($idSale){
        $numberParcels=count(Parcels::where('id_sale',$idSale)->where('status',"!=",1)->where('type',1)->get());
        return $numberParcels;
    }

    private function getNumberMonthDiff($final_date){
        $data1 = new Carbon();
        $data2 = new Carbon($final_date);

        $intervalo = $data1->diff( $data2 );
        return $intervalo->m;
    }
    
    private function getSumIndexRate($index_date_first,$index){
        $index_now=date('Y-m')."-01";
        $index_date_first=date('Y-m',strtotime($index_date_first))."-01";
        if($index_now==$index_date_first){
            $index_date_first=date('Y-m-d',strtotime('-1 month',strtotime($index_date_first)));
            $allIndex_value_readjust=DB::table('index_value')->where('idIndex',$index)->where('month','=',$index_date_first)->get();
        }else{
            $allIndex_value_readjust=DB::table('index_value')->where('idIndex',$index)->where('month','>=',$index_date_first)
            ->where('month','<',$index_now)->get();
        }
        $rate_readjust_sum=0;    
        if($this->verify_index_empty($index_date_first,date('Y-m').'-01',$index)==false){
            $rate_readjust_sum=0;
            $index_values=[];    
            foreach ($allIndex_value_readjust as $key => $index_value) {
                $index_values[]=$index_value->value;
            }
            
            $rate_readjust_sum=array_sum($index_values);
        }    
       
        return $rate_readjust_sum;
    }

    private function getLastReajustValue($idSale){
        $lastParcelReajus="";
        $parcels=Parcels::where('id_sale',$idSale)->where('type',1)->get();
        $first_parcel_num=Parcels::where('id_sale',$idSale)->where('status','!=',1)
            ->where('type',1)->first();
        for ($i=$first_parcel_num->num; $i <count($parcels) ; $i=$i+12) { 
            $parcelCompare=Parcels::where('id_sale',$idSale)->where('num','=',$i)->first();
            if($parcelCompare->reajust != ""){
                $lastParcelReajus=$parcelCompare;  
            }
        }

        return $lastParcelReajus;
    }

    public function addSeveralContact(Request $request){
        $dataContact=$request->only(['id_sale','id_user','contact_client_name','where','deadline','subject_matter'
        ,'contactFile','fine_contact','expiration_fine_contact','prefix_parcel_contact',
        'number_parcel_contact','status']);
        
       
        $this->validator($dataContact,1);
        
        if($request->hasAny(['contact_client_name','where','deadline','subject_matter','id_sale','id_user'])){
            
            $contact=new ContactSale();
            $contact->id_sale=$dataContact['id_sale'];
            $contact->id_user=$dataContact['id_user'];
            $contact->contact_client_name=$dataContact['contact_client_name'];
            $contact->where=$dataContact['where'];
            $contact->type=1;
            $contact->register_date=date('Y-m-d');
            $contact->deadline=$dataContact['deadline'];
            $contact->subject_matter=$dataContact['subject_matter'];
            $contact->status=2;
            
            if(!empty($dataContact['contactFile'])){
                $this->insertFileContact($dataContact['contactFile'],$contact);
            }
            $contact->save();
            
            if(!empty($dataContact['fine_contact'])){
                $fine_contact=str_replace(['.',','],['','.'],$dataContact['fine_contact']);
                echo $fine_contact;
                $this->insertParcelsFineContact($dataContact['id_sale'],$fine_contact,
                    $dataContact['expiration_fine_contact'],$dataContact['prefix_parcel_contact'],
                    $dataContact['number_parcel_contact'],2,$contact->id);
            }
        }    

        return redirect()->route('seeSale',['idSale'=>$dataContact['id_sale']]);
    }

    public function addChangeOwnerContact(Request $request){
        $dataContact=$request->only(['id_sale','clients','client_payment','id_clients_porc','id_sale'
            ,'id_user','contact_client_name','where','deadline','subject_matter','contactFile']);
        
        $status=2;
        if(isset($dataContact['status'])){
            $status=1;
        }
        $this->validator($dataContact,2);
        
        if($request->hasAny(['contact_client_name','where','deadline','subject_matter','id_sale','id_user'])){
            
            $contact=new ContactSale();
            $contact->id_sale=$dataContact['id_sale'];
            $contact->id_user=$dataContact['id_user'];
            $contact->contact_client_name=$dataContact['contact_client_name'];
            $contact->type=2;
            $contact->register_date=date('Y-m-d');
            $contact->where=$dataContact['where'];
            $contact->deadline=$dataContact['deadline'];
            $contact->subject_matter=$dataContact['subject_matter'];
            $contact->status=$status;
            if(!empty($dataContact['contactFile'])){
                $this->insertFileContact($dataContact['contactFile'],$contact);
            }
            $contact->save();

            $clients=implode(',',$dataContact['clients']);
            $idClientPayment=$dataContact['client_payment'];
            $id_clients_porc=implode(',',$dataContact['id_clients_porc']);

            $sale=Sales::where('id',$dataContact['id_sale'])->first();

            DB::table('change_owner_info')->insert([
                'id_contact'=>$contact->id,
                'id_sale'=>$dataContact['id_sale'],
                'old_clients'=>$sale->clients,
                'old_client_payment'=>$sale->client_payment_id,
                'old_clients_porc'=>$sale->id_clients_porc,
                'clients'=>$clients,
                'client_payment'=>$idClientPayment,
                'clients_porc'=>$id_clients_porc
            ]);
        }    

        return redirect()->route('seeSale',['idSale'=>$dataContact['id_sale']]);

    }

    public function changeOwner(Request $request){
        $dataContact=$request->only(['idSale','idContact','contactFile']);
        $sale=Sales::where('id',$dataContact['idSale'])->first();
        
        $pathContactArray=[];
        if(count($dataContact['contactFile'])){
            foreach ($dataContact['contactFile'] as $key => $file) {
                $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                $pathContact="contactFiles/changeOwner_done/";
                $file->storeAs($pathContact,$contactFile);
                $pathContactArray[]=$pathContact.$contactFile;
            }
        }
        
        $change_owner_info=DB::table('change_owner_info')->where('id_contact',$dataContact['idContact'])->first();

        $sale->clients=$change_owner_info->clients;
        $sale->client_payment_id=$change_owner_info->client_payment;
        $sale->id_clients_porc=$change_owner_info->clients_porc;
        $sale->save();

        DB::table('change_owner_info')->where('id_contact',$dataContact['idContact'])
            ->update(['path_document_done'=>implode('|',$pathContactArray)]);

        $contact=ContactSale::where('id',$change_owner_info->id_contact)->first();
        $contact->status=1;
        $contact->save();

        return redirect()->route('seeSale',['idSale'=>$dataContact['idSale']]);
    }

    public function addChangeExpiredDate(Request $request){
        $dataContact=$request->only(['id_sale','id_sale','id_user','expired_day','cancel_payment_slip','contact_client_name',
            'where','deadline','subject_matter','contactFile','fine_contact','expiration_fine_contact',
            'prefix_parcel_contact','number_parcel_contact']);
        
        
        $this->validator($dataContact,3);
        
        if($request->hasAny(['contact_client_name','where','deadline','subject_matter','id_sale','id_user'])){
            
            $contact=new ContactSale();
            $contact->id_sale=$dataContact['id_sale'];
            $contact->id_user=$dataContact['id_user'];
            $contact->contact_client_name=$dataContact['contact_client_name'];
            $contact->type=3;
            $contact->register_date=date('Y-m-d');
            $contact->where=$dataContact['where'];
            $contact->deadline=$dataContact['deadline'];
            $contact->subject_matter=$dataContact['subject_matter'];
            $contact->expired_day=$dataContact['expired_day'];
            $contact->status=2;
            
            if(!empty($dataContact['contactFile'])){
                $this->insertFileContact($dataContact['contactFile'],$contact);
            }
            $contact->save();

            $this->changeExpiredDaySale($contact->id);
            $contact=ContactSale::where('id',$contact->id)->first();
            $contact->status=1;
            $contact->save();
        }    

        return redirect()->route('seeSale',['idSale'=>$dataContact['id_sale']]);

    }

    public function changeExpiredDaySale($idContact){
        $contact=ContactSale::where('id',$idContact)->first();
        $idSale=$contact->id_sale;
        $expiredDay=$contact->expired_day;

        $parcelActualMonth=DB::select("SELECT * FROM parcels WHERE MONTH(date) = MONTH(CURRENT_DATE()) 
            AND YEAR(date)=YEAR(CURRENT_DATE()) AND id_sale=:idSale",['idSale'=>$idSale]);
      
        if(!empty($parcelActualMonth)){
            $parcels=Parcels::where('date','>=',$parcelActualMonth[0]->date)->where('type',1)
            ->where('id_sale',$idSale)->get();
        }else{
            $parcels=Parcels::where('id_sale',$idSale)->get();
        }
        
        foreach ($parcels as $key => $parcel) {
            if($parcel->status != 3){
                $month=date('m',strtotime($parcel->date));
                $year=date('Y',strtotime($parcel->date));
                $newDate=$year."-".$month."-".$expiredDay;
                $parcel->date=$newDate;
                $parcel->save();
            }
         }   
        
        $parcels_rate=Parcels::where('id_contact',$idContact)->get();
        if(count($parcels_rate)>0){
            foreach ($parcels_rate as $key => $parcel) {
                $parcel=Parcels::where('id',$parcel->id)->first();
                $parcel->status=1;
                $parcel->save();
            }

            $contact->status=1;
            $contact->save();

            return redirect()->route('doneContact',['idContact'=>$idContact]);
        }
    }

    public function addRefinancingContact(Request $request){
        $dataContact=$request->only(['id_sale','id_contact','id_sale','id_user','contact_client_name','where','deadline',
        'subject_matter','contactFile','fine_contact','fine_total','prefix_parcel_contact','number_parcel_contact'
        ,'expiration_day','index','value','parcels','sufix','value_parcel']);
        
        $this->validator($dataContact,4);
        $request->validate([
            'fine_total'=>'required',
            'number_parcel_contact'=>'required',
            'expiration_day'=>'required',
            'parcels'=>'required',
            'fine_contact'=>'required'
        ],[
            'fine_contact.required'=>"O valor da taxa é obrigatório!",
            'number_parcel_contact.required'=>'O numero de parcelas do administrativo é obrigatório!',
        ]);

        if($request->hasAny(['contact_client_name','where','deadline','subject_matter','id_sale','id_user'])){
            $contact=new ContactSale();
            $contact->id_sale=$dataContact['id_sale'];
            $contact->id_user=$dataContact['id_user'];
            $contact->contact_client_name=$dataContact['contact_client_name'];
            $contact->type=4;
            $contact->register_date=date('Y-m-d');
            $contact->where=$dataContact['where'];
            $contact->deadline=$dataContact['deadline'];
            $contact->subject_matter=$dataContact['subject_matter'];
            $contact->status=2;
            
            if(!empty($dataContact['contactFile'])){
                $this->insertFileContact($dataContact['contactFile'],$contact);
            }
            $contact->save();

            $month = date('m');         
            $year = date("Y");
            $last_day = date("t", mktime(0,0,0,$month,'01',$year));

            $date_expiration_fine=$year.'-'.$month.'-'.$last_day;
            $this->insertParcelsFineContact($dataContact['id_sale'],$dataContact['fine_total'],$date_expiration_fine,
            $dataContact['prefix_parcel_contact'],1,3,$contact->id);
            
            DB::table('refinancing_info')->insert(['id_contact'=>$contact->id,'id_sale'=>$dataContact['id_sale'],
            'total_value'=>$dataContact['value'],'number_parcels'=>$dataContact['parcels'],
            'value_parcel'=>$dataContact['value_parcel'],'value_fine_percentage'=>$dataContact['fine_contact'],
            'value_fine'=>$dataContact['fine_total'],'number_parcels_fine'=>$dataContact['number_parcel_contact'],
            'date'=>new Carbon(),'time'=>new Carbon(),'sufix'=>$dataContact['sufix']]);
        }    
        
        return redirect()->route('seeSale',['idSale'=>$dataContact['id_sale']]);
    }

    public function addCancelContact(Request $request){
        $dataContact=$request->only(['id_sale','id_sale','id_user','expired_day','cancel_payment_slip','contact_client_name',
        'where','deadline','subject_matter','contactFile','return_value','number_parcels_return','value_parcel_return',
        'first_parcel_return','number_parcel_contact','future_value','sale_commission_rate','administrative_expenses','iptu_debits','others_debits',
        'administrative_debits','specification_debits','total_parcels_pad','sale_commission','sale_commission_adjusted']);
        
        $this->validator($dataContact,5);
        $request->validate([
            'return_value'=>['required'],
            'number_parcels_return'=>['required'],
            'value_parcel_return'=>['required'],
            'administrative_expenses'=>['required'],
            'sale_commission_rate'=>['required']
        ],[
            'return_value.required'=>"valor a restituir é obrigatório!",
            'number_parcels_return.required'=>"numero de parcelas é obrigatório!",
            'administrative_expenses.required'=>"Taxa contratual das despesas administrativas é obrigatório!",
            'sale_commission_rate.required'=>"Taxa comissão de venda é obrigatório!"
        ]);
        

        if($request->hasAny(['contact_client_name','where','deadline','subject_matter','id_sale','id_user'])){
            $contact=new ContactSale();
            $contact->id_sale=$dataContact['id_sale'];
            $contact->id_user=$dataContact['id_user'];
            $contact->contact_client_name=$dataContact['contact_client_name'];
            $contact->type=5;
            $contact->register_date=date('Y-m-d');
            $contact->where=$dataContact['where'];
            $contact->deadline=$dataContact['deadline'];
            $contact->subject_matter=$dataContact['subject_matter'];
            $contact->status=2;
            
            if(!empty($dataContact['contactFile'])){
                $this->insertFileContact($dataContact['contactFile'],$contact);
            }
            $contact->save();

            DB::table('cancel_contact_info')
                ->insert(['id_contact'=>$contact->id,
                'id_sale'=>$dataContact['id_sale'],
                'future_value'=>$dataContact['future_value'],
                'sale_commission_rate'=>$dataContact['sale_commission_rate'],
                'administrative_expenses'=>$dataContact['administrative_expenses'],
                'iptu_debits'=>$dataContact['iptu_debits'],
                'others_debits'=>$dataContact['others_debits'],
                'administrative_debits'=>$dataContact['administrative_debits'],
                'specification_debits'=>$dataContact['specification_debits'],
                'total_parcels_pad'=>$dataContact['total_parcels_pad'],
                'sale_commission'=>$dataContact['sale_commission'],
                'sale_commission_adjusted'=>$dataContact['sale_commission_adjusted'],
                'return_value'=>$dataContact['return_value'],
                'value_parcel_return'=>$dataContact['value_parcel_return'],
                'number_parcels_return'=>$dataContact['number_parcels_return'],
                'first_parcel_return'=>$dataContact['first_parcel_return'],
                'specification_debits'=>$dataContact['specification_debits']]);
            
        }
        return redirect()->route('seeSale',['idSale'=>$dataContact['id_sale']]);
    }

    public function cancelSale(Request $request){
        $dataContact=$request->only(['idContact','idSale','contactFile','return_value','number_parcels_return','value_parcel_return']);
        $contact=ContactSale::where('id',$dataContact['idContact'])->first();
        $cancel_contact_info=DB::table('cancel_contact_info')->where('id_contact',$dataContact['idContact'])->first();
        if($cancel_contact_info != null){
            $request->validate([
                'contactFile'=>['required'],
                'return_value'=>['required'],
                'number_parcels_return'=>['required'],
                'value_parcel_return'=>['required'],
                'administrative_expenses'=>['required'],
                'sale_commission_rate'=>['required']
            ],[
                'return_value.required'=>"valor a restituir é obrigatório!",
                'number_parcels_return.required'=>"numero de parcelas é obrigatório!",
                'administrative_expenses.required'=>"Taxa contratual das despesas administrativas é obrigatório!",
                'sale_commission_rate.required'=>"Taxa comissão de venda é obrigatório!"
            ]);
            
            $pathContactArray=[];
            if(count($dataContact['contactFile'])){
                foreach ($dataContact['contactFile'] as $key => $file) {
                    $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    $pathContact="contactFiles/cancel_done/";
                    $file->storeAs($pathContact,$contactFile);
                    $pathContactArray[]=$pathContact.$contactFile;
                }
            }

            DB::table('cancel_contact_info')->where('id_contact',$dataContact['idContact'])
                ->update([
                'value_parcel_return'=>$dataContact['value_parcel_return'],
                'number_parcels_return'=>$dataContact['number_parcels_return'],
                'return_value'=>$dataContact['return_value'],
                'path_document_done'=>implode('|',$pathContactArray),
                ]);
            
            $this->registerParcels($cancel_contact_info->id_sale,$dataContact['value_parcel_return'],
            $dataContact['number_parcels_return'],$cancel_contact_info->first_parcel_return,
            26,"Restituir",5,$cancel_contact_info->id_contact);
        }

        $sale=Sales::where('id',$contact->id_sale)->first();
        $sale->type=3;
        $sale->save();

        $lot=Lot::where('id',$sale->id_lot)->first();
        $lot->available=1;
        $lot->save();

        $contact->status=1;
        $contact->save();

        return redirect()->route('seeSale',['idSale'=>$dataContact['idSale']]);
    }

   

    public function addReissueContact(Request $request){
        $dataContact=$request->only(['id_sale','id_user','expired_day','cancel_payment_slip','contact_client_name',
            'where','deadline','subject_matter','contactFile','parcel_late_sum','rate_reissue','deadline_reissue'
            ,'total_reissue','parcels_selected']);

        $this->validator($dataContact,6);
        $request->validate([
            'parcel_late_sum'=>['required'],
            'rate_reissue'=>['required'],
            'deadline_reissue'=>['required'],
            'parcels_selected'=>['required']
        ]);

        if($request->hasAny(['contact_client_name','where','deadline','subject_matter','id_sale','id_user'])){
            $contact=new ContactSale();
            $contact->id_sale=$dataContact['id_sale'];
            $contact->id_user=$dataContact['id_user'];
            $contact->contact_client_name=$dataContact['contact_client_name'];
            $contact->type=6;
            $contact->register_date=date('Y-m-d');
            $contact->where=$dataContact['where'];
            $contact->deadline=$dataContact['deadline'];
            $contact->subject_matter=$dataContact['subject_matter'];
            $contact->status=2;
            
            if(!empty($dataContact['contactFile'])){
                $this->insertFileContact($dataContact['contactFile'],$contact);
            }
            $contact->save();

            DB::table('reissue_contact_info')->insert(['id_sale'=>$dataContact['id_sale'],'id_contact'=>$contact->id,
            'parcel_late_sum'=>$dataContact['parcel_late_sum'],'rate_reissue'=>$dataContact['rate_reissue'],
            'deadline_reissue'=>$dataContact['deadline_reissue'],'total_reissue'=>$dataContact['total_reissue'],
            'parcels_selected'=>implode(',',$dataContact['parcels_selected'])]);
        }

        return redirect()->route('seeSale',['idSale'=>$dataContact['id_sale']]);
    }

    public function reissueContact(Request $request){
        $dataContact=$request->only(['id_contact']);
        $request->validate([
            'id_contact'=>['required'],
        ]);
         
        $reissue_contact_info=DB::table('reissue_contact_info')
            ->where('id_contact',$dataContact['id_contact'])->first();
        $this->deleteOldParcelsAndAddNewParcel($reissue_contact_info); 
        
        $contact=ContactSale::where('id',$reissue_contact_info->id_contact)->first();    
        $contact->status=1;
        $contact->save();

        return redirect()->route('seeSale',['idSale'=>$reissue_contact_info->id_sale]);
    }

    public function addChangeLotContact(Request $request){
        $dataContact=$request->only(['id_sale','id_user','expired_day','contact_client_name',
            'where','deadline','subject_matter','contactFile','id_lot','old_lot','old_lot_value',
            'lot_selected','value_lot_selected','total_parcels_pad','new_value_pay','first_parcel',
            'number_parcels_pad','number_parcels_to_pay','value_after_change',
            'number_parcel_change_lot','rate_financing','value_parcel_change_lot']);

        $this->validator($dataContact,7);
        $request->validate([
            'old_lot'=>['required','string'],
            'old_lot_value'=>['required','string'],
            'lot_selected'=>['required','string'],
            'value_lot_selected'=>['required'],
            'total_parcels_pad'=>['required'],
            'new_value_pay'=>['required'],
            'number_parcels_pad'=>['required'],
            'number_parcels_to_pay'=>['required'],
            'value_after_change'=>['required'],
            'number_parcel_change_lot'=>['required'],
            'rate_financing'=>['required'],
            'value_parcel_change_lot'=>['required'],
            'first_parcel'=>['required'],
        ]);

        if($request->hasAny(['contact_client_name','where','deadline','subject_matter','id_sale','id_user'])){
            $contact=new ContactSale();
            $contact->id_sale=$dataContact['id_sale'];
            $contact->id_user=$dataContact['id_user'];
            $contact->contact_client_name=$dataContact['contact_client_name'];
            $contact->type=7;
            $contact->register_date=date('Y-m-d');
            $contact->where=$dataContact['where'];
            $contact->deadline=$dataContact['deadline'];
            $contact->subject_matter=$dataContact['subject_matter'];
            $contact->status=2;
            
            if(!empty($dataContact['contactFile'])){
                $this->insertFileContact($dataContact['contactFile'],$contact);
            }
            $contact->save();

            DB::table('change_lot_info')->insert([
                'id_sale'=>$dataContact['id_sale'],
                'id_contact'=>$contact->id,
                'old_lot'=>$dataContact['old_lot'],
                'old_lot_value'=>$dataContact['old_lot_value'],
                'lot_selected'=>$dataContact['lot_selected'],
                'id_lot_selected'=>$dataContact['id_lot'],
                'value_lot_selected'=>$dataContact['value_lot_selected'],
                'total_parcels_pad'=>$dataContact['total_parcels_pad'],
                'new_value_pay'=>$dataContact['new_value_pay'],
                'number_parcels_pad'=>$dataContact['number_parcels_pad'],
                'number_parcels_to_pay'=>$dataContact['number_parcels_to_pay'],
                'number_parcel_change_lot'=>$dataContact['number_parcel_change_lot'],
                'rate_financing'=>$dataContact['rate_financing'],
                'value_after_change'=>$dataContact['value_after_change'],
                'value_parcel_change_lot'=>$dataContact['value_parcel_change_lot'],
                'first_parcel'=>$dataContact['first_parcel']
            ]);
        }

        return redirect()->route('seeSale',['idSale'=>$dataContact['id_sale']]);
    }

    public function changeLotSuccess(Request $request){
        $dataContact=$request->only(['idContact','contactFile']);
        
        $pathContactArray=[];
        if(count($dataContact['contactFile'])){
            foreach ($dataContact['contactFile'] as $key => $file) {
                $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                $pathContact="contactFiles/changeLot_done/";
                $file->storeAs($pathContact,$contactFile);
                $pathContactArray[]=$pathContact.$contactFile;
            }
        }

        DB::table('change_lot_info')->where('id_contact',$dataContact['idContact'])
        ->update(['path_document_done'=>implode(',',$pathContactArray)]);

        $change_lot_info=DB::table('change_lot_info')->where('id_contact',$dataContact['idContact'])->first();


        $parcels=Parcels::where('id_sale',$change_lot_info->id_sale)->where('status',"!=",1)->get();
       
        foreach ($parcels as $parcel) {
            $parcel=Parcels::where('id',$parcel->id)->first();
            $parcel->delete();
        }

        $lot=Lot::where('id',$change_lot_info->id_lot_selected)->first();
        $lot->available=2;
        $lot->save();

       

        $sale=Sales::where('id',$change_lot_info->id_sale)->first();

        $lot=Lot::where('id',$sale->id_lot)->first();
        $lot->available=1;
        $lot->save();

        $sale->input=$change_lot_info->total_parcels_pad;
        $sale->id_lot=$change_lot_info->id_lot_selected;
        $sale->id_interprise=$lot->id_interprise;
        $sale->save();

        $contact=ContactSale::where('id',$dataContact['idContact'])->first();
        $contact->status=1;
        $contact->save();

        $expiredDay=date('d',strtotime($change_lot_info->first_parcel));
        $this->registerParcels($change_lot_info->id_sale,$change_lot_info->value_parcel_change_lot,
            $change_lot_info->number_parcels_to_pay,$change_lot_info->first_parcel,$expiredDay,"",1,0);

        return redirect()->route('seeSale',['idSale'=>$contact->id_sale]);

    }

    public function deleteOldParcelsAndAddNewParcel($reissue_contact_info){
        $parcelsSelected=explode(',',$reissue_contact_info->parcels_selected);
        $total_parcels=count(Parcels::where('id_sale',$reissue_contact_info->id_sale)->get());
        $parcelsNumber=[];
        foreach ($parcelsSelected as $key => $parcelId) {
            $parcel=Parcels::where('id',$parcelId)->first();
            $parcelsNumber[]=$parcel->num."/".$total_parcels;
            $parcel->delete();
        }

        $newParcel=new Parcels();
        $newParcel->id_sale=$reissue_contact_info->id_sale;
        $newParcel->num_reissue=implode(',',$parcelsNumber);
        $newParcel->date=$reissue_contact_info->deadline_reissue;
        $newParcel->value=$reissue_contact_info->parcel_late_sum;
        $newParcel->updated_value=$reissue_contact_info->parcel_late_sum;
        $newParcel->added_value='0,00';
        $newParcel->prefix="Reemitida";
        $newParcel->send_bankSlip=0;
        $newParcel->status=2;
        $newParcel->type=6;
        $newParcel->our_number=rand(10000,99999);
        $newParcel->save();
    }

    private function calcTotalLaterParcel($idSale){
        $parcels=Parcels::where('id_sale',$idSale)->where('status',3)->get();
        $sumParcelsValue=0;
        foreach ($parcels as $key => $parcel) {
            $updated_value=floatVal(str_replace(['.',','],['','.'],$parcel->updated_value));
            $sumParcelsValue=$sumParcelsValue+$updated_value;
        }
        
        return $sumParcelsValue;
    }

    public function insertFileContact($file,$contactObject){
       if(!empty($file)){
            if($file->isValid()){
                $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                $pathContact="contactFiles/";
                $file->storeAs($pathContact,$contactFile);
                $pathContact=$pathContact.$contactFile;
                $contactObject->contactFile=$pathContact;
                $contactObject->save();
            }
        }
    }

    private function insertParcelsFineContact($idSale,$fine,$expiration_fine_contact,
        $prefix_parcel_contact,$number_parcel_contact,$type,$idContact){
            $value=$fine/$number_parcel_contact;
            $fine=number_format($value,2,",",".");;
            $expirationDay=date('d',strtotime($expiration_fine_contact));
            $this->registerParcels($idSale,$fine,$number_parcel_contact,$expiration_fine_contact,
                $expirationDay,$prefix_parcel_contact,$type,$idContact);
    }

    public function registerParcels($idSale,$valueParcel,$numberParcels,$firstParcelDate,$expiredDay
        ,$prefix,$type,$idContact){
        $this->addParcel($idSale,1,$firstParcelDate,$valueParcel,$prefix,$type,$idContact);
        
        $firstParcelDateDivide=explode('-',$firstParcelDate);
        $yearFirstParcel=$firstParcelDateDivide[0];
        $monthFirstParcel=$firstParcelDateDivide[1];
        $numberYearsParcel=0;
        if($numberParcels>=12){
            $numberYearsParcel=ceil($numberParcels/12);
            $parcel=1;
        }else{
            $numberYearsParcel=ceil($numberParcels/12);
            $parcel=1;
        }
       

        for ($year=$yearFirstParcel; $year <= $yearFirstParcel+$numberYearsParcel; $year++) { 
           for($month=1;$month<=12;$month++){
                if($year==$yearFirstParcel){
                    if($month>=$monthFirstParcel+1){
                        $finalDate=$year."-".$month."-".$expiredDay;
                        $parcel++;
                        
                        $this->addParcel($idSale,$parcel,$finalDate,$valueParcel,$prefix,$type,$idContact);
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
                    
                    $this->addParcel($idSale,$parcel,$finalDate,$valueParcel,$prefix,$type,$idContact);
                }
            }
        }
    }

    private function addParcel($idSale,$numberActualParcel,$dateParcel,$valueParcel,$prefix,$type,$idContact){
        $parcel=new Parcels();
        $parcel->id_sale=$idSale;
        $parcel->num=$numberActualParcel;
        $correctDateParcel=$this->maxDayInTheMonth($dateParcel);
        $parcel->date=$correctDateParcel;
        $parcel->value=$valueParcel;
        $parcel->updated_value=$valueParcel;
        $parcel->prefix=$prefix;
        $parcel->status=2;
        $parcel->type=$type;
        $parcel->send_bankSlip=0;
        $parcel->our_number=$this->createOurNumber($correctDateParcel);
        $parcel->id_contact=$idContact;
        $parcel->save();
    }

    private function createOurNumber($dateParcel){
        $isOk=false;
        $our_number="";
        
        while($isOk==false){
            $our_number=rand(10000,99999);
            $parcel=Parcels::where('our_number',$our_number)->where('date',$dateParcel)
            ->where('send_bankSlip',0)->first();
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
        
        /*Caso o num de dias seja maior que  o numero do mes selecionado, irá rodar o laço, diminuindo os dias, 
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


    public function seeContact($idContact){
        $data=[];
        $data['contact']=ContactSale::join('users','contact_sale.id_user','users.id')
                        ->where('contact_sale.id',$idContact)   
                        ->first(['contact_sale.*','users.name as user_name']);
        
        $data['refinancing_info']=DB::table('refinancing_info')->where('id_contact',$idContact)->first(); 

        if($this->verifyParcelsRefinancingPad($idContact,$data['contact']->id_sale) 
            && $data['contact']->type==4 && $data['contact']->status==2){
            $this->refinancingSuccess($data['contact']->id_sale,$idContact);
            return redirect()->route('doneContact',['idContact'=>$idContact]);
        }

        if($data['contact']->type==5){
            $cancel_contact_info=DB::table('cancel_contact_info')->where('id_contact',$idContact)->first();
            $data['cancel_contact_info']=$cancel_contact_info;
        }

        $data['padParcels']='';
        if($data['contact']->type==1){
            if($data['contact']->solution != "" && $data['contact']->status==1){
                return redirect()->route('doneContact',['idContact'=>$idContact]);
            }
        }

        if($data['contact']->type==6){
            $cancel_contact_info=DB::table('reissue_contact_info')->where('id_contact',$idContact)->first();
            $data['reissue_contact_info']=$cancel_contact_info;
        }

        if($data['contact']->type==7){
            $change_lot_info=DB::table('change_lot_info')->where('id_contact',$idContact)->first();
            $data['change_lot_info']=$change_lot_info;
        }

        if($data['contact']->type==2){
            $change_owner_info=DB::table('change_owner_info')->where('id_contact',$idContact)->first();
            $data['old_clients']=$this->getOldClients($change_owner_info->old_clients);
            $data['old_perc']=$this->getAllPercClient($change_owner_info->old_clients_porc);
            $data['new_clients']=$this->getNewClients($change_owner_info->clients);
            $data['new_perc']=$this->getAllPercNewClient($change_owner_info->clients_porc);
            $data['old_client_payment']=Clients::where('id',$change_owner_info->old_client_payment)->first();
            $data['new_client_payment']=Clients::where('id',$change_owner_info->client_payment)->first();
        }

        $data['parcels_rate']=Parcels::where('id_contact',$idContact)->get();
        $data['sale']=Sales::where('id',$data['contact']->id_sale)->first();
        return view('seeContact',$data);
    }

    private function getOldClients($clients){
        $id_clients=explode(',',$clients);
        $old_clients=[];
        foreach ($id_clients as $key => $id) {
            $client=Clients::where('id',$id)->first();
            $old_clients[]=$client;
        }

        return $old_clients;
    }

    private function getNewClients($clients){
        $id_clients=explode(',',$clients);
        $new_clients=[];
        foreach ($id_clients as $key => $id) {
            $client=Clients::where('id',$id)->first();
            $new_clients[]=$client;
        }

        return $new_clients;
    }

    private function getAllPercClient($id_clients_porc){
        $old_perc=[];
        $dividePorc=explode(',',$id_clients_porc);

        foreach ($dividePorc as $key => $client_porc) {
            $porc=explode('-',$client_porc);
            $old_perc[]=number_format($porc[1],2);
        }

        return $old_perc;
    }

    private function getAllPercNewClient($id_clients_porc){
        $old_perc=[];
        $dividePorc=explode(',',$id_clients_porc);

        foreach ($dividePorc as $key => $client_porc) {
            $porc=explode('-',$client_porc);
            $old_perc[]=number_format($porc[1],2);
        }

        return $old_perc;
    }

    public function severalSolution(Request $request){
        $data=$request->only(['idContact','solution','resolved']);
        if($request->has(['idContact','solution'])){
            $contact=ContactSale::where('id',$data['idContact'])->first();
            $contact->solution=$data['solution'];
            if($request->filled('resolved')){
                $contact->status=1;
            }
            $contact->save();
        }

        return redirect()->route('seeContact',['idContact'=>$data['idContact']]);
    }

    public function verifyParcelsRatePad($idContact){
        $parcelsPad=count(Parcels::where('id_contact',$idContact)->where('status',1)->get());
        $totalParcelsRate=count(Parcels::where('id_contact',$idContact)->get());
        
        if($totalParcelsRate==$parcelsPad){
            $contact=ContactSale::where('id',$idContact)->first();
            $contact->status=1;
            $contact->save();
            
            return true;
        }else{
            return false;
        }
    }

    public function forcePayParcelsRate(Request $request){
        $idContact=$request->input('idContact');
        $totalParcelsRate=Parcels::where('id_contact',$idContact)->get();
        
        foreach ($totalParcelsRate as $key => $parcel) {
            $parcelObject=Parcels::where('id',$parcel->id)->first();
            $parcelObject->status=1;
            $parcelObject->save();
        }
        
        $contact=ContactSale::where('id',$idContact)->first();
        $contact->status=1;
        $contact->save();

        return redirect()->route('doneContact',['idContact'=>$idContact]);
    }

    public function refinancingSuccess($idSale,$idContact,$forceConfirm=false){
        $parcel_rate=Parcels::where('id_contact',$idContact)->first();
        $parcel_rate->status=1;
        $parcel_rate->save();

        $parcels=Parcels::where('id_sale',$idSale)
        ->where('status',"!=",1)->get();
        
        $idLastParcelRefinanciament=Parcels::where('id_contact',$idContact)->max('id');
        $lastParcelRefinanciament=Parcels::where('id',$idLastParcelRefinanciament)->first();
        
        $firstParcelDate=date('Y-m',strtotime('+1 month',strtotime($lastParcelRefinanciament->date)));
        $expired_day=date('d',strtotime($parcels[0]->date));
        $firstParcelDate=$firstParcelDate."-".$expired_day;
        
        $refinancing_info=DB::table('refinancing_info')->where('id_contact',$idContact)->first();
        $value_parcel=number_format($refinancing_info->value_parcel,2,",",".");
        $number_parcels=$refinancing_info->number_parcels;
        
        foreach ($parcels as $key => $parcel) {
           if($parcel->id_contact==""){
                $parcel->delete();   
           }
             
           
        }
        
        $sale=Sales::where('id',$idSale)->first();
        $sale->parcels=$number_parcels;
        $sale->save();

        $this->registerParcels($idSale,$value_parcel,$number_parcels,
            $firstParcelDate,$expired_day,"",1,null);

        $contact=ContactSale::where('id',$idContact)->first();    
        $contact->status=1;
        $contact->save();

        if($forceConfirm){
            return redirect()->route('seeSale',['idSale'=>$idSale]);
        }
    }

    private function verifyParcelsRefinancingPad($idContact){
        $parcels=Parcels::where('id_contact',$idContact)->get();
        $numParcels=count($parcels);
        $numParcelsPad=0;

        foreach ($parcels as $key => $parcel) {
            if($parcel->status==1){
                $numParcelsPad++;
            }
        }

        if($numParcels==$numParcelsPad){
            return true;
        }else{
            return false;
        }
    }


    public function updateContactFile(Request $request){
        $dataFile=$request->only('idContact','contactFile','edit');
        $request->validate([
            'idContact'=>['required','int'],
            'contactFile' => ['required','mimes:txt,DOC,pdf'],
        ],$this->msgContact());

        if(!empty($dataFile['contactFile'])){
            $contactObject=ContactSale::where('id',$dataFile['idContact'])->first();
            if($dataFile['edit']==true){
                Storage::delete($contactObject->contactFile);
            }
            
            $this->insertFileContact($dataFile['contactFile'],$contactObject);
        }

        return redirect()->route('seeContact',['idContact'=>$dataFile['idContact']]);
    }

    public function updateStatus($idContact,$idSale,$status){
        if(!empty($idContact) && !empty($status)){
            $contact=ContactSale::where('id',$idContact)->first();
            if($contact->type==2){
                if($contact->status==2){
                    $sale=Sales::where('id',$contact->id_sale)->first();
                    $sale->clients=$contact->clients;
                    $sale->client_payment_id=$contact->client_payment;
                    $sale->save();

                    $contact->status=1;
                    $contact->save();
                }else{
                    $contact->status=2;
                    $contact->save();
                }
            }

            if($contact->type==3){
                if($contact->status==2){
                    $this->changeExpiredDaySale($contact->id_sale,$contact->expired_day);
                    $contact->status=1;
                    $contact->save();
                }else{
                    $contact->status=2;
                    $contact->save();
                }
            }
            
            return redirect()->route('seeSale',['idSale'=>$idSale]);
        }
    }

    public function doneContact($idContact){
        $contact=ContactSale::where('id',$idContact)->first();
        $data['basic_contact_info']=$contact;
        $data['sale']=Sales::where('id',$contact->id_sale)->first();
        
        if($contact->type==5){
            $data['cancel_contact_info']=DB::table('cancel_contact_info')->where('id_contact',$idContact)->first();
        }

        if($contact->type==4){
            $data['refinancing_info']=DB::table('refinancing_info')->where('id_contact',$idContact)->first();
        }

        if($contact->type==2){
            $data['change_owner_info']=DB::table('change_owner_info')->where('id_contact',$idContact)->first();
            $change_owner_info=DB::table('change_owner_info')->where('id_contact',$idContact)->first();
            $data['old_clients']=$this->getOldClients($change_owner_info->old_clients);
            $data['old_perc']=$this->getAllPercClient($change_owner_info->old_clients_porc);
            $data['new_clients']=$this->getNewClients($change_owner_info->clients);
            $data['new_perc']=$this->getAllPercNewClient($change_owner_info->clients_porc);
            $data['old_client_payment']=Clients::where('id',$change_owner_info->old_client_payment)->first();
            $data['new_client_payment']=Clients::where('id',$change_owner_info->client_payment)->first();
            
        }

        
        $data['change_owner_info_documents']=DB::table('change_owner_info')->where('id_contact',$contact->id)
            ->where('path_document_done',"!=","")->get();

        $data['cancel_contact_info_documents']=DB::table('cancel_contact_info')->where('id_contact',$contact->id)
        ->where('path_document_done',"!=","")->get();

        $data['change_lot_contact_info_documents']=DB::table('change_lot_info')->where('id_contact',$contact->id)
        ->where('path_document_done',"!=","")->get();

        $data['parcels_rate']=Parcels::where('id_contact',$idContact)->get();
            
        return view('contactsViews.doneContact',$data);
    }

    private function validator($data,$type){
        $ruleContactClients=[];
        $ruleContactClientsPayment=[];
        
        if($type==2){
            $ruleContactClients=['required'];
            $ruleContactClientsPayment=['required'];
        }
        
        return Validator::make($data,[
            'clients'=>$ruleContactClients,
            'client_payment'=>$ruleContactClientsPayment,
            'contact_client_name'=>['required','string','max:450'],
            'where'=>['required','string','max:450'],
            'deadline'=>['required','date'],
            'subject_matter'=>['required','string','max:450'],
            'contactFile'=>['mimes:txt,DOC,pdf'],
            'fine_contact'=>['max:20','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'expiration_fine_contact'=>['date','nullable'],
            'prefix_parcel_contact'=>['string','nullable'],
            'number_parcel_contact'=>['int','nullable'],
            'id_user'=>['required','int'],
            'id_sale'=>['required','int'],
        ],$this->msgContact())->validate();
    }

    private function msgContact(){
        return [
            'clients.required'=>'Escolha algum cliente',
            'client_payment.required'=>'Escolha algum cliente para emitir o pagamento',
            'id_user.required'=>'escolha o responsavel pelo contato',
            'id_user.int'=>'o id do usuario tem que ser inteiro',
            'id_sale.required'=>'o id da venda não foi enviado!',
            'id_sale.int'=>'o id da venda tem que ser inteiro!',
            'id_user.int'=>'o id do usuario tem que ser inteiro',
            'contact_client_name.required'=>'o campo pessoa é obrigatório!',
            'contact_client_name.string'=>'o campo pessoa tem que ser string!',
            'contact_client_name.max'=>'o campo pessoa tem que ter no maximo 450 caracteres',
            'where.required'=>'o campo via é obrigatório!',
            'where.string'=>'o campo via tem que ser string!',
            'where.max'=>'o campo via tem que ter no maximo 450 caracteres',
            'deadline.required'=>'o campo prazo é obrigatório!',
            'deadline.date'=>'o campo prazo tem que ser uma data valida!',
            'subject_matter.required'=>'o campo assunto é obrigatório!',
            'subject_matter.string'=>'o campo assunto tem que ser string!',
            'subject_matter.max'=>'o campo assunto tem que ter no maximo 450 caracteres',
            'contactFile.mimes'=>'o arquivo do contato tem ser do tipo txt,DOC ou pdf',
            'fine_contact.max'=>'o campo multa tem que ter no maximo 20 caracteres',
            'fine_contact.regex'=>'o campo multa tem que ser um valor valido!',
            'expiration_fine_contact.date'=>'o campo vencimento tem que ser uma data valida',
            'prefix_parcel_contact.string'=>'o prefixo da parcela tem que ser string',
            'number_parcel_contact.int'=>'o numero de parcelas tem que ser inteiro'
        ];
    }
}

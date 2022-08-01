<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ContactSale;
use App\Models\Parcels;
use App\Models\Sales;
use App\Models\ProgramedPayment;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type==5){
                return redirect()->route('allJuridicalContacts');
            }

            return $next($request);
        }); 
        
        $this->reajustAndLaterParcelsSales();
    }

    private function reajustAndLaterParcelsSales(){
        $sales=Sales::where('type',2)->get(['id']);
        foreach ($sales as $key => $saleItem) {
            $this->verifyReajustParcels($saleItem->id);
            $this->verifyLateParcel($saleItem->id);
        }
    }

    private function verifyLateParcel($idSale){
        $parcels=Parcels::where('id_sale',$idSale)->where('status',"!=",1)
        ->orderBy('date','ASC')->get();

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        
        $data['contact']=ContactSale::join('sales','sales.id','=','contact_sale.id_sale')
            ->where('id_user',Auth::user()->id)
            ->where('status',2)
            ->where('solution',null)
            ->get(['contact_sale.*','sales.contract_number as contract_number']);;
        
        $data['typeContact']=[
            1=>"Diversos",
            2=>"Alterar Proprietário",
            3=>"Alterar Vencimento",
            4=>"Refinanciamento",
            5=>"Cancelamento",
            6=>"Reemitir Parcelas",
            7=>"Alterar Lote"
        ];

        $data['notifications_index']=DB::table('notification_index_value')
            ->join('sales','notification_index_value.id_sale','=','sales.id')
            ->where('notification_index_value.done',0)->where('notification_index_value.type',1)
            ->get(['notification_index_value.*','sales.id as idSale','sales.contract_number as contractNumber']);
        
        $data['notifications_almostFinish']=$this->notificationSalesAlmostFinish();
        
        $data['allAlertsPayments']=ProgramedPayment::join('internal_accounts','programed_payments.id_internal_account','internal_accounts.id')
        ->join('clients','programed_payments.id_provider','clients.id')
        ->where('date',date('Y-m-d'))
        ->where('status',"!=",1)
        ->get([
            'programed_payments.*',
            'clients.name as nameProvider',
            'clients.company_name as companyProvider',
            'internal_accounts.name as internalAccount',
        ]);

        $this->verifyLaterAlert($data['allAlertsPayments']);
       
        return view('home',$data);
    }

    private function verifyLaterAlert($allAlertsPayment){
        foreach ($allAlertsPayment as $key => $alert) {
            $dateAlert=strtotime($alert->date);
            $now=strtotime('now');

            if($dateAlert>$now && $alert->status != 1){
                $alert=ProgramedPayment::where('id',$alert->id)->first();
                $alert->status=3;
                $alert->save();
            }
        }
    }

    private function notificationSalesAlmostFinish(){
        $sales=Sales::all();
        $notifications=[];
        foreach ($sales as $key => $sale) {
            if($this->verifyFinishSale($sale->id) && $sale->type != 6 && $sale->type != 4){
                $notifications[]=$sale;
            }            
        }

        return $notifications;
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
}

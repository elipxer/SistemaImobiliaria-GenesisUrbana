<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ContactSale;
use App\Models\Parcels;
use App\Models\Sales;
use App\Models\ProgramedPayment;

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

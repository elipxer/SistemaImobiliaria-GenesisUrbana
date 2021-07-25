<?php

namespace App\Http\Controllers;

use App\Models\BankSlip;
use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Bank;
use App\Models\Lot;
use App\Models\Parcels;
use App\Models\Sales;

class AjaxController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

    }
    
    public function getAddressByCep(Request $request){
        $data=['error'=>''];
        if($request->has('cep')){
            $data=$request->only('cep');
            $cep=$data['cep'];
            $url='https://viacep.com.br/ws/'.$cep.'/json/';
            $data['address'] =json_decode(file_get_contents($url));
        }
        
        echo json_encode($data);
    }

    public function getAllClients(Request $request){
        $data=['error'=>'','clients'=>''];
        $clientVal=$request->only('clientVal');
        
        if($request->has('clientVal')){
            $data['clients']=Clients::where('name','LIKE','%'.$clientVal['clientVal'].'%')
                ->orWhere('company_name','LIKE','%'.$clientVal['clientVal'].'%')
                ->orWhere('cpf','LIKE','%'.$clientVal['clientVal'].'%')
                ->orWhere('cnpj','LIKE','%'.$clientVal['clientVal'].'%')->get();
        }

        echo json_encode($data);
    }

    public function getLot(Request $request){
        $data=['error'=>'','lot'=>''];
        $idLot=$request->input('idLot');

        if($request->has('idLot')){
            $data['lot']=Lot::where('id',$idLot)->first();
        }

        echo json_encode($data);
    }

    public function getAllInfoByCnpj(Request $request){
        $data=['error'=>''];
        if($request->has('cnpj')){
            $data=$request->only('cnpj');
            $cnpj=str_replace(['.','/','-'], "", $data['cnpj']);
            $url='https://www.receitaws.com.br/v1/cnpj/'.$cnpj;
            $data['cnpj_info'] =json_decode(file_get_contents($url));
        }

        echo json_encode($data);
    }

    public function verifyClientExist(Request $request){
        
        if($request->has('cpf')){
            $cpf=$request->only('cpf');
            $clients=Clients::where('cpf',$cpf)->first();
        }
        
        $clientID=0;
        if($clients != null){
            $clientID= $clients->id;
        }

        return $clientID;
    }

    public function verifyContractNumber(Request $request){
        $idSale=-1;
        if($request->has('contract_number')){
            $contract_number=$request->input('contract_number');
            $sale=Sales::where('contract_number',$contract_number)->first();
            if($sale!=""){
                $idSale=$sale->id;
            }
        }

        return $idSale;
    }

    public function getBankSlip(Request $request){
        $idBankSlips=explode(',',$request->input('idBankSlips'));
        $bankSlipArray=[];
        foreach ($idBankSlips as $key => $id) {
            $bankSlip=BankSlip::where('id',$id)->first();
            $sale=Sales::where('id',$bankSlip->id_sale)->first();
            $parcel=Parcels::where('id',$bankSlip->id_parcel)->first();
            $financialAccount=Bank::where('id',$bankSlip->id_financial_accounts)->first();

            $bankSlipInfo=[
                'financialAccount'=>$financialAccount,
                'bankSlip'=>$bankSlip,
                'sale'=>$sale,
                'parcel'=>$parcel
            ];

            $bankSlipArray[]=$bankSlipInfo;
        }
       
        echo json_encode($bankSlipArray);
    }
}

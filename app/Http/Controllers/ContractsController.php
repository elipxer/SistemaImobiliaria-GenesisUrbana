<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use App\Models\ContactSale;
use App\Models\Interprises;
use App\Models\Sales;
use App\Models\Lot;
use App\Models\Companies;
use Illuminate\Support\Facades\DB;

class ContractsController extends Controller
{   

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

    }
    
    public function contractSale($id_sale){
        $sale=Sales::where('id',$id_sale)->first();
        $data['sale']=$sale;
        $interprise=Interprises::where('id',$sale->id_interprise)->first();
        $companies_interprise_ids=explode(',',$interprise->company_ids);
       
        $data['companies']=[];
        foreach ($companies_interprise_ids as $key => $id) {
            $company=Companies::where('id',$id)->first();
            $data['companies'][]=$company;
        }

        $clients_ids=explode(',',$sale->clients);
        $data['clients']=[];
        foreach ($clients_ids as $key => $id) {
            $client=Clients::where('id',$id)->first();
            $client_representative=Clients::where('id',$client->id_representative)->first();
            if($client->id_representative == null){
                $client_representative==null;
            }
            $clientArray[]=[
                'client'=>$client,
                'client_representative'=>$client_representative
            ];
            
            $data['clients']=$clientArray;
        }

        $data['lot']=Lot::where('id',$sale->id_lot)->first();
        $data['interprise']=Interprises::where('id',$sale->id_interprise)->first();

        $month = [
            1=>'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];


        $data['now']=date('d')." de ".$month[intVal(date('m'))]." de ".date('Y');

        $mpdf= new Mpdf();
        $mpdf->WriteHTML(View::make('contracts.contract_sale',$data)->render());
        $mpdf->SetDisplayMode('fullpage');
        return $mpdf->Output('contrato_venda.pdf', 'I');
    }

    public function contractCancel($id_contact){
        $month = [
            1=>'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];

        $contact=ContactSale::where('id',$id_contact)->first();
        $data['contact']=$contact;
        $sale=Sales::where('id',$contact->id_sale)->first();
        $data['sale']=$sale;
        $interprise=Interprises::where('id',$sale->id_interprise)->first();
        $companies_interprise_ids=explode(',',$interprise->company_ids);
        $data['lot']=Lot::where('id',$sale->id_lot)->first();
        $data['interprise']=Interprises::where('id',$data['lot']->id_interprise)->first();
        
        $data['companies']=[];
        foreach ($companies_interprise_ids as $key => $id) {
            $company=Companies::where('id',$id)->first();
            $data['companies'][]=$company;
        }

        $clients_ids=explode(',',$sale->client_payment_id);
        $data['clients']=[];
        foreach ($clients_ids as $key => $id) {
            $client=Clients::where('id',$id)->first();
            $client_representative=Clients::where('id',$client->id_representative)->first();
            if($client->id_representative == null){
                $client_representative==null;
            }
            $clientArray[]=[
                'client'=>$client,
                'client_representative'=>$client_representative
            ];
            
            $data['clients']=$clientArray;
        }

        $data['maritalState']=[
            1=>'Solteiro',
            2=>"Casado",
            3=>"Divorciado"
        ];

        $data['nationality']=[
            1=>'Brasileiro',
            2=>"Estrangeiro",
        ];

        
        $data['cancel_contact_info']=DB::table('cancel_contact_info')->where('id_contact',$id_contact)->first();
        
        $data['now']=date('d')." de ".$month[intVal(date('m'))]." de ".date('Y');
        $mpdf= new Mpdf();
        $mpdf->WriteHTML(View::make('contracts.contract_cancel',$data)->render());
        $mpdf->SetDisplayMode('fullpage');
        return $mpdf->Output('contrato_cancelamento.pdf', 'I');
    }

    public function contractChangeOwner($id_contact){
        $contact=ContactSale::where('id',$id_contact)->first();
        $data['contact']=$contact;
        $sale=Sales::where('id',$contact->id_sale)->first();
        $data['sale']=$sale;
        
        $change_owner_info=DB::table('change_owner_info')->where('id_contact',$contact->id)->first();
        $oldClients=explode(',',$change_owner_info->old_clients);
        $data['oldClients']=[];
        
        foreach ($oldClients as $key => $id) {
            $client=Clients::where('id',$id)->first();
            $client_representative=Clients::where('id',$client->id_representative)->first();
            if($client->id_representative == null){
                $client_representative==null;
            }
            $clientArray[]=[
                'client'=>$client,
                'client_representative'=>$client_representative
            ];
            
            $data['oldClients']=$clientArray;
        }

        $newClients=explode(',',$change_owner_info->clients);
        $data['newClients']=[];
        
        foreach ($newClients as $key => $id) {
            $client=Clients::where('id',$id)->first();
            $client_representative=Clients::where('id',$client->id_representative)->first();
            if($client->id_representative == null){
                $client_representative==null;
            }
            $newClientArray[]=[
                'client'=>$client,
                'client_representative'=>$client_representative
            ];
            
            $data['newClients']=$newClientArray;
        }
        
        $interprise=Interprises::where('id',$sale->id_interprise)->first();
        $companies_interprise_ids=explode(',',$interprise->company_ids);
        $data['lot']=Lot::where('id',$sale->id_lot)->first();
        $data['interprise']=Interprises::where('id',$data['lot']->id_interprise)->first();
        
        $data['companies']=[];
        foreach ($companies_interprise_ids as $key => $id) {
            $company=Companies::where('id',$id)->first();
            $data['companies'][]=$company;
        }

        $month = [
            1=>'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];


        $data['now']=date('d')." de ".$month[intVal(date('m'))]." de ".date('Y');

        $mpdf= new Mpdf();
        $mpdf->WriteHTML(View::make('contracts.contract_change_owner',$data)->render());
        $mpdf->SetDisplayMode('fullpage');
        return $mpdf->Output('cessão.pdf', 'I');
    }

    public function contractChangeLot($id_contact){
        $month=[
            1=>'Janeiro',
            'Fevereiro',
            'Março',
            'Abril',
            'Maio',
            'Junho',
            'Julho',
            'Agosto',
            'Setembro',
            'Outubro',
            'Novembro',
            'Dezembro'
        ];

        $contact=ContactSale::where('id',$id_contact)->first();
        $data['contact']=$contact;
        $sale=Sales::where('id',$contact->id_sale)->first();
        $data['sale']=$sale;
        

        $contact=ContactSale::where('id',$id_contact)->first();
        $data['contact']=$contact;
        $sale=Sales::where('id',$contact->id_sale)->first();
        $data['sale']=$sale;
        $interprise=Interprises::where('id',$sale->id_interprise)->first();
        $companies_interprise_ids=explode(',',$interprise->company_ids);
        $data['lot']=Lot::where('id',$sale->id_lot)->first();
        $data['interprise']=Interprises::where('id',$data['lot']->id_interprise)->first();
        
        $data['companies']=[];
        foreach ($companies_interprise_ids as $key => $id) {
            $company=Companies::where('id',$id)->first();
            $data['companies'][]=$company;
        }

        $clients_ids=explode(',',$sale->client_payment_id);
        $data['clients']=[];
        foreach ($clients_ids as $key => $id) {
            $client=Clients::where('id',$id)->first();
            $client_representative=Clients::where('id',$client->id_representative)->first();
            if($client->id_representative == null){
                $client_representative==null;
            }
            $clientArray[]=[
                'client'=>$client,
                'client_representative'=>$client_representative
            ];
            
            $data['clients']=$clientArray;
        }

        $data['maritalState']=[
            1=>'Solteiro',
            2=>"Casado",
            3=>"Divorciado"
        ];

        $data['nationality']=[
            1=>'Brasileiro',
            2=>"Estrangeiro",
        ];
        
        $data['now']=date('d')." de ".$month[intVal(date('m'))]." de ".date('Y');

        $change_lot_info=DB::table('change_lot_info')->where('id_contact',$contact->id)->first();
        $data['change_lot_info']=$change_lot_info;
        $mpdf= new Mpdf();
        $mpdf->WriteHTML(View::make('contracts.contract_change_lot',$data)->render());
        $mpdf->SetDisplayMode('fullpage');
        return $mpdf->Output('troca_lote.pdf', 'I');
    }
}

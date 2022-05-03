<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Sales;
use App\Models\Parcels;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
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
        }); 
    }

    public function index(Request $request){
        $data=[];
        $data['kindPerson']=1;
        
        if($request->has('client_kind_person')){
            $kindPerson=$request->only('client_kind_person');
            $data['kindPerson']=$kindPerson['client_kind_person'];
        }

        $client=Clients::query();
        if(Auth::user()->type==6){
            $data['clients']=$client->where('id',Auth::user()->idClient)->get();
            if($data['clients'][0]->kind_person==1){
                $data['kindPerson']=1;
            }else{
                $data['kindPerson']=2;
            }
        }else{
            $data['clients']=$client->where('kind_person',$data['kindPerson'])->get();
        }
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];

        $data['company_name']="";
        $data['fantasy_name']="";
        $data['name']="";
        $data['spouse_name']="";
        $data['cpf']="";
        $data['cnpj']="";
        $data['email']="";
        $data['phones']="";
        $data['cep']="";
        $data['street']="";
        $data['number']="";
        $data['neighborhood']="";
        $data['city']="";
        $data['stateChoise']="";
        $data['date']="";

        $dataClientsLike=[];
        
        if($data['kindPerson']==1){
            $dataClientsLike=$request->only(['name','cpf','spouse_name',
                'phones','street','number','neighborhood','city','cep']);
        }else if($data['kindPerson']==2){
            $dataClientsLike=$request->only(['company_name','fantasy_name','cnpj','email','phones'
            ,'street','number','neighborhood','city','cep']);
        }
       
        $dataClientsEquals = $request->only('state');

        if($request->hasAny(['name','spouse_name','cpf','cnpj','email','phones'
            ,'street','number','neighborhood','city','state','cep'])){
            $query=Clients::query();
            
            foreach ($dataClientsLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataClientsEquals as $name => $value) {
                if ($value) { 
                    $query->where($name, '=', $value);
                }
            }
            
            $data['clients']=$query->where('kind_person',$data['kindPerson'])->get();
            
            if($data['kindPerson']==1){
                $data['name']=$dataClientsLike['name'];
                $data['cpf']=$dataClientsLike['cpf'];
            }else if($data['kindPerson']==2){
                $data['company_name']=$dataClientsLike['company_name'];
                $data['fantasy_name']=$dataClientsLike['fantasy_name'];
                $data['cnpj']=$dataClientsLike['cnpj']; 
            }
            
            $data['spouse_name']=$dataClientsLike['spouse_name'];   
            $data['phones']=$dataClientsLike['phones'];
            $data['street']=$dataClientsLike['street'];      
            $data['number']=$dataClientsLike['number'];
            $data['neighborhood']=$dataClientsLike['neighborhood'];      
            $data['city']=$dataClientsLike['city'];
            $data['stateChoise']=$dataClientsEquals['state'];
            $data['cep']=$dataClientsLike['cep'];;
        }

        $data['cpf_representative_input']="";
        if($request->has('cpf_representative')){
            $cpf_representative=$request->input('cpf_representative');
            $data['clients']=$this->filterClientsRepresentative($data['clients'],$cpf_representative);
            $data['cpf_representative_input']=$cpf_representative;
        }
        $data['clients_representative']=[];
        if($data['kindPerson']==2){
            foreach ($data['clients'] as $key => $client) {
                $clientsRepresentative=Clients::where('id',$client->id_representative)->first();
                if($clientsRepresentative!=null){
                    $data['clients_representative'][]=$clientsRepresentative->cpf;
                }else{
                    $data['clients_representative'][]="";
                }
            }
        }
        
        return view('allClients',$data);
    }


    public function allClientsSeveral(Request $request){
        $data=[];
        $data['kindPerson']=1;
        
        if($request->has('client_kind_person')){
            $kindPerson=$request->only('client_kind_person');
            $data['kindPerson']=$kindPerson['client_kind_person'];
        }

        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        $data['stateChoise']="";


        $client=Clients::query();
        if(Auth::user()->type==6){
            $data['clients']=$client->where('id',Auth::user()->idClient)->get();
            if($data['clients'][0]->kind_person==1){
                $data['kindPerson']=1;
            }else{
                $data['kindPerson']=2;
            }
        }else{
            $data['clients']=$client->where('kind_person',$data['kindPerson'])->get();
        }

        $allClientsColumsKindPerson1=['name','rg','emitting_organ','cpf','nationality','sex','email','occupation',
        'street','number','complement','cep','neighborhood','city','state','spouse_name','spouse_birth_date',
        'spouse_rg','spouse_cpf','spouse_emitting_organ','spouse_email','spouse_occupation','street_payment_collection'
        ,'number_payment_collection','number_payment_collection','neighborhood_payment_collection','city_payment_collection',
        'complement_payment_collection','state_payment_collection','cep_payment_collection','phones','whatsAppNumber'];
        
        $extraFiltersKindPerson1=$request->only(['birth_date','marital_status','nationality','sex',
            'spouse_birth_date','spouse_nationality','spouse_sex']);
        $data['birth_date']="";
        $data['marital_status']="";
        $data['nationality']="";
        $data['spouse_birth_date']="";      
        $data['spouse_nationality']="";  
        $data['spouse_sex']="";
        $data['sex']="";

        $allClientsColumsKindPerson2=['company_name','fantasy_name','cnpj','email','street','number','complement','cep','neighborhood',
        'city','state','number_payment_collection','number_payment_collection','neighborhood_payment_collection','city_payment_collection',
        'complement_payment_collection','state_payment_collection','cep_payment_collection','phones','whatsAppNumber'];

        $data['clientSeveral']="";
        $clientSeveral=$request->input('clientSeveral');
        if($clientSeveral || $request->hasAny(['birth_date','marital_status','nationality','sex',
            'spouse_birth_date','spouse_nationality','spouse_sex'])){
            $query=Clients::query();

            if($request->hasAny(['birth_date','marital_status','nationality','sex',
                'spouse_birth_date','spouse_nationality','spouse_sex'])){

                foreach ($extraFiltersKindPerson1 as $name => $value) {
                        if ($value) { 
                            $query->where($name, '=', $value);
                        }
                    }
                }

            if($clientSeveral){
                $allClientsColums=[];
                if($data['kindPerson']==1){
                    $allClientsColums=$allClientsColumsKindPerson1;
                }else{
                    $allClientsColums=$allClientsColumsKindPerson2;

                }
                foreach ($allClientsColums as $key=>$name) {
                    if($key==0){
                        $query->where($name, 'LIKE', '%' . $clientSeveral . '%');
                    }else{
                        $query->orwhere($name, 'LIKE', '%' . $clientSeveral . '%');
                    }
                }
            }

            

            $data['clients']=$query->get();
            $data['clientSeveral']=$clientSeveral;
            if($data['kindPerson']==1){
                $data['birth_date']=$extraFiltersKindPerson1['birth_date'];
                $data['marital_status']=$extraFiltersKindPerson1['marital_status'];
                $data['nationality']=$extraFiltersKindPerson1['nationality'];
                $data['sex']=$extraFiltersKindPerson1['sex'];
                $data['spouse_birth_date']=$extraFiltersKindPerson1['spouse_birth_date'];      
                $data['spouse_nationality']=$extraFiltersKindPerson1['spouse_nationality'];  
                $data['spouse_sex']=$extraFiltersKindPerson1['spouse_sex'];
            }
        }


        $data['cpf_representative_input']="";
        if($request->has('cpf_representative')){
            $cpf_representative=$request->input('cpf_representative');
            $data['clients']=$this->filterClientsRepresentative($data['clients'],$cpf_representative);
            $data['cpf_representative_input']=$cpf_representative;
        }
        $data['clients_representative']=[];
        if($data['kindPerson']==2){
            foreach ($data['clients'] as $key => $client) {
                $clientsRepresentative=Clients::where('id',$client->id_representative)->first();
                if($clientsRepresentative!=null){
                    $data['clients_representative'][]=$clientsRepresentative->cpf;
                }else{
                    $data['clients_representative'][]="";
                }
            }
        }
        
        return view('allClientsSeveral',$data);
    }

    private function filterClientsRepresentative($clients,$cpf_representative){
        $clientsRepresentative=[];
        $clientsIdsRepresentative=[];
        
        foreach ($clients as $key => $client) {
            $clientsRegister=Clients::where('cpf','LIKE','%'.$cpf_representative.'%')->get();    
            
            foreach ($clientsRegister as $key => $clientRegisterItem) {
                if($clientRegisterItem->id==$client->id_representative){
                    $clientsIdsRepresentative[]=$clientRegisterItem->id;
                } 
            }
        }
       ;
        $clientsIdsRepresentative="'".implode(',',array_map('intval', $clientsIdsRepresentative))."'";
        $clientsRepresentative=DB::select("SELECT * FROM clients WHERE FIND_IN_SET(id_representative,".$clientsIdsRepresentative.")");
      
        return $clientsRepresentative;
    }

    public function add(Request $request,$cpf_representative=null,$edit_idClient=null){
        if(Auth::user()->type!=1 && Auth::user()->type!=4){
            Auth::logout();
            return redirect()->route('login');
        }

        $data=[];
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        $data['cpf_representative_register']="";
        $data['id_representative']="";
        $data['cpf_representative']=$cpf_representative;
        $data['kind_person']=1;

        $dataClients=$request->only(['name','company_name','birth_date','kind_person','rg','emitting_organ','cpf',
            'cnpj','marital_status','nationality','sex','email','occupation','cep','street','number','neighborhood',
            'city','state','complement','spouse_name','spouse_birth_date','spouse_rg','spouse_emitting_organ',
            'spouse_cpf','spouse_cnpj','spouse_nationality','spouse_sex','spouse_email','spouse_occupation',
            'cep_payment_collection','street_payment_collection','number_payment_collection',
            'neighborhood_payment_collection','city_payment_collection','complement_payment_collection',
            'state_payment_collection','phones','id_representative','whatsAppNumber']);
        
        if($request->hasAny(['name','company_name','birth_date','kind_person','rg','emitting_organ','cpf','cnpj',
            'marital_status','nationality','sex','email','occupation','cep','street','number','neighborhood',
            'city','state','complement','spouse_name','spouse_birth_date','spouse_rg','spouse_emitting_organ',
            'spouse_cpf','spouse_cnpj','spouse_nationality','spouse_sex','spouse_email','spouse_occupation',
            'cep_payment_collection','street_payment_collection','number_payment_collection',
            'neighborhood_payment_collection','city_payment_collection','complement_payment_collection',
            'state_payment_collection','whatsAppNumber'])){
            
            $this->validator($dataClients);
            
            $clients=new Clients();
            $clients->name=$dataClients['name'];
            $clients->company_name=$dataClients['company_name'];
            $clients->birth_date=$dataClients['birth_date'];
            $clients->rg=$dataClients['rg'];
            $clients->emitting_organ=$dataClients['emitting_organ'];
            $clients->cpf=$dataClients['cpf'];
            $clients->cnpj=$dataClients['cnpj'];
            $clients->marital_status=$dataClients['marital_status'];
            $clients->nationality=$dataClients['nationality'];
            $clients->sex=$dataClients['sex'];
            $clients->email=$dataClients['email'];
            $clients->occupation=$dataClients['occupation'];
            $clients->cep=$dataClients['cep'];
            $clients->street=$dataClients['street'];
            $clients->number=$dataClients['number'];
            $clients->neighborhood=$dataClients['neighborhood'];
            $clients->city=$dataClients['city'];
            $clients->state=$dataClients['state'];
            $clients->complement=$dataClients['complement'];
            if($dataClients['marital_status']==2){
                $clients->spouse_name=$dataClients['spouse_name'];
                $clients->spouse_birth_date=$dataClients['spouse_birth_date'];
                $clients->spouse_rg=$dataClients['spouse_rg'];
                $clients->spouse_emitting_organ=$dataClients['spouse_emitting_organ'];
                $clients->spouse_cpf=$dataClients['spouse_cpf'];
                $clients->spouse_nationality=$dataClients['spouse_nationality'];
                $clients->spouse_sex=$dataClients['spouse_sex'];
                $clients->spouse_email=$dataClients['spouse_email'];
                $clients->spouse_email=$dataClients['spouse_occupation'];
            }
            $clients->cep_payment_collection=$dataClients['cep_payment_collection'];
            $clients->city_payment_collection=$dataClients['city_payment_collection'];
            $clients->street_payment_collection=$dataClients['street_payment_collection'];
            $clients->number_payment_collection=$dataClients['number_payment_collection'];
            $clients->neighborhood_payment_collection=$dataClients['neighborhood_payment_collection'];
            $clients->city_payment_collection=$dataClients['city_payment_collection'];
            $clients->complement_payment_collection=$dataClients['complement_payment_collection'];
            $clients->state_payment_collection=$dataClients['state_payment_collection'];
            $clients->id_representative=$dataClients['id_representative'];
            $clients->whatsAppNumber=$dataClients['whatsAppNumber'];   
            
            if($cpf_representative==null){
                $clients->kind_person=$dataClients['kind_person'];
            }else{
                $clients->kind_person=1;
            }
            
            if($request->has('phones') && count($request->only('phones'))>0){
                $phones=implode(',',$dataClients['phones']);
                $clients->phones=$phones;
            }
        
            $clients->save();

            if($cpf_representative==null){
                return redirect()->route('allClients');
            }else{
                $data['cpf_representative_register']=$dataClients['cpf'];
                $data['id_representative']=$clients->id;
                $data['cpf_representative']=null;
                
                if($edit_idClient != null){
                    $clientEdit=Clients::where('id',$edit_idClient)->first();
                    $clientEdit->id_representative=$clients->id;
                    $clientEdit->save();
                    
                    return redirect()->route('editClient',['idClient'=>$edit_idClient]);
                }else{
                    return redirect()->route('addClient');
                }
            }
        }    

        return view('addClient',$data);
    }

    public function edit(Request $request,$idClient){
        if(Auth::user()->type!=1 && Auth::user()->type!=3){
            Auth::logout();
            return redirect()->route('login');
        }
        
        $data=[];
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        $data['client']=Clients::where('id',$idClient)->first();
        
        $data['cpf_representative_register']=Clients::where('id',$data['client']->id_representative)->first('cpf');
        $data['id_representative']=$data['client']->id_representative;

        $dataClients=$request->only(['id_representative','id','name','birth_date','rg','emitting_organ','cpf','cnpj',
            'marital_status','nationality','sex','email','occupation','cep','street','number','neighborhood',
            'city','state','complement','spouse_name','spouse_birth_date','spouse_rg','spouse_emitting_organ',
            'spouse_cpf','spouse_nationality','spouse_sex','spouse_email','spouse_occupation',
            'cep_payment_collection','street_payment_collection','number_payment_collection',
            'neighborhood_payment_collection','city_payment_collection','complement_payment_collection',
            'state_payment_collection','phones']);

        if($request->hasAny(['name','birth_date','rg','emitting_organ','cpf','cnpj',
            'marital_status','nationality','sex','email','occupation','cep','street','number','neighborhood',
            'city','state','complement','spouse_name','spouse_birth_date','spouse_rg','spouse_emitting_organ',
            'spouse_cpf','spouse_nationality','spouse_sex','spouse_email','spouse_occupation',
            'cep_payment_collection','street_payment_collection','number_payment_collection',
            'neighborhood_payment_collection','city_payment_collection','complement_payment_collection',
            'state_payment_collection'])){
            
            $clients=Clients::where('id',$dataClients['id'])->first();
            $this->validator($dataClients,$clients);
             
            $clients->name=$dataClients['name'];
             $clients->birth_date=$dataClients['birth_date'];
             $clients->rg=$dataClients['rg'];
             $clients->emitting_organ=$dataClients['emitting_organ'];
             $clients->cpf=$dataClients['cpf'];
             $clients->cnpj=(!empty($dataClients['cnpj']))?$dataClients['cnpj']:'';
             $clients->marital_status=$dataClients['marital_status'];
             $clients->nationality=$dataClients['nationality'];
             $clients->sex=$dataClients['sex'];
             $clients->email=$dataClients['email'];
             $clients->occupation=$dataClients['occupation'];
             $clients->cep=$dataClients['cep'];
             $clients->street=$dataClients['street'];
             $clients->number=$dataClients['number'];
             $clients->neighborhood=$dataClients['neighborhood'];
             $clients->city=$dataClients['city'];
             $clients->state=$dataClients['state'];
             $clients->complement=$dataClients['complement'];
             if($dataClients['marital_status']==2){
                $clients->spouse_name=$dataClients['spouse_name'];
                $clients->spouse_birth_date=$dataClients['spouse_birth_date'];
                $clients->spouse_rg=$dataClients['spouse_rg'];
                $clients->spouse_emitting_organ=$dataClients['spouse_emitting_organ'];
                $clients->spouse_cpf=$dataClients['spouse_cpf'];
                $clients->spouse_nationality=$dataClients['spouse_nationality'];
                $clients->spouse_sex=$dataClients['spouse_sex'];
                $clients->spouse_email=$dataClients['spouse_email'];
                $clients->spouse_occupation=$dataClients['spouse_occupation'];
             }else{
                $clients->spouse_name=null;
                $clients->spouse_birth_date=null;
                $clients->spouse_rg=null;
                $clients->spouse_emitting_organ=null;
                $clients->spouse_cpf=null;
                $clients->spouse_nationality=null;
                $clients->spouse_sex=null;
                $clients->spouse_email=null;
                $clients->spouse_occupation=null;
             }
             $clients->cep_payment_collection=$dataClients['cep_payment_collection'];
             $clients->city_payment_collection=$dataClients['city_payment_collection'];
             $clients->street_payment_collection=$dataClients['street_payment_collection'];
             $clients->number_payment_collection=$dataClients['number_payment_collection'];
             $clients->neighborhood_payment_collection=$dataClients['neighborhood_payment_collection'];
             $clients->city_payment_collection=$dataClients['city_payment_collection'];
             $clients->complement_payment_collection=$dataClients['complement_payment_collection'];
             $clients->state_payment_collection=$dataClients['state_payment_collection'];
             $clients->id_representative=$dataClients['id_representative'];
             
             if($request->has('phones') && count($request->only('phones'))>0){
                $phones=implode(',',$dataClients['phones']);
                $clients->phones=$phones;
             }
           
             $clients->save();

            return redirect()->route('allClients');
            }    

        return view('editClient',$data);
    }

    public function delete($idClient){
        if(Auth::user()->type!=1){
            return redirect()->route('allClients');
        }
        if(!empty($idClient)){
            $client=Clients::where('id',$idClient)->first();
            $client->delete();
        }

        return redirect()->route('allClients');;
    }

    public function seeClient($idClient){
        if(!empty($idClient)){
            $data=[];
            $data['client']=Clients::where('id',$idClient)->first();
            $data['client_representative']=[];
            if($data['client']->id_representative != null){
                $data['client_representative']=Clients::where('id',$data['client']->id_representative)->first();
            }

            $sales=Sales::join('interprises','sales.id_interprise','=','interprises.id')
            ->join('lots','sales.id_lot','lots.id')
            ->get(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
               ,'lots.block as lot_block']);;
            
            $data['sales']=[];
            foreach ($sales as $key => $sale) {
                $clients=explode(',',$sale->clients);
                if(in_array($idClient,$clients)){
                    $parcels_unpaid=count(Parcels::where('id_sale',$sale->id)->where('status',"!=",1)
                    ->orderBy('date','ASC')->get());    
                    $parcels_paid=count(Parcels::where('id_sale',$sale->id)->where('status',1)->orderBy('date')->get());    
                    $later_parcels=count(Parcels::where('id_sale',$sale->id)->where('status',3)->get());;
                    $rest_value=$this->getRestValue($sale->id);
                    $paid_value=$this->getPaidValue($sale->id);
                    $later_value=$this->getLaterValue($sale->id);
                    $saleInfo=[
                        'sale'=>$sale,
                        'parcels_unpaid'=>$parcels_unpaid,
                        'parcels_paid'=>$parcels_paid,
                        'later_parcels'=>$later_parcels,
                        'rest_value'=>$rest_value,
                        'paid_value'=>$paid_value,
                        'later_value'=>$later_value
                    ];
                    $data['sales'][]=$saleInfo;
                }
            }

            return view('seeClient',$data);
        }
    }

    private function getRestValue($idSale){
        $data['parcels']=Parcels::where('id_sale',$idSale)->get()->except('status',1);;
       
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



    public function validator($data,$client=null){
        $ruleUnique=[];

        if($client!=null){
            $ruleUnique=Rule::unique('clients')->ignore($client->id);
        }else{
            $ruleUnique='unique:clients';
        }

        $ruleRequiredRepresentative="";
        $ruleRequiredNameClient="";
        $ruleRequiredCpfClient="";
        $ruleRequiredCnpjClient="";
        
        if($client == null){
            if($data['kind_person']==2){
                $ruleRequiredRepresentative="required";
                $ruleRequiredCnpjClient="required";
            
            }else if($data['kind_person']==1){
                $ruleRequiredNameClient='required';
                $ruleRequiredCpfClient="required";
            }
        }
        
        $ruleRequiredSpouse="";
        if($data['marital_status']==2){
            $ruleRequiredSpouse='required';
        }

        return Validator::make($data,[
            'name'=>[$ruleRequiredNameClient,'string','nullable','max:450'],
            'kind_person'=>['int','nullable'],
            'birth_date'=>['date','nullable'],
            'rg'=>['string','nullable','max:30'],
            'emitting_organ'=>['string','nullable','max:30'],
            'cpf'=>[$ruleRequiredCpfClient,'cpf',$ruleUnique,'nullable'],
            'cnpj'=>[$ruleRequiredCnpjClient,'cnpj',$ruleUnique,'nullable'],
            'marital_status'=>['int','nullable'],
            'nationality'=>['int','nullable'],
            'sex'=>['int','nullable'],
            'email'=>['email',$ruleUnique,'max:450','nullable'],
            'occupation'=>['string','nullable'],
            'cep'=>['required','string','regex:/([0-9]{5})\-([0-9]{3})/'],
            'street'=>['required','string','max:450'],
            'number'=>['required','int','nullable'],
            'complement'=>['string','nullable','max:450'],
            'neighborhood'=>['required','string','max:450'],
            'city'=>['required','string','nullable','max:450'],
            'state'=>['required','string','nullable','max:2'],
            'id_representative'=>$ruleRequiredRepresentative,
            
            'spouse_name'=>[$ruleRequiredSpouse,'string','nullable','max:450'],
            'spouse_birth_date'=>['date','nullable'],
            'spouse_rg'=>['string','nullable','max:30'],
            'spouse_emitting_organ'=>['string','nullable','max:30'],
            'spouse_cpf'=>[$ruleRequiredSpouse,'cpf',$ruleUnique,'nullable'],
            'spouse_nationality'=>['int','nullable'],
            'spouse_sex'=>['int','nullable'],
            'spouse_email'=>['email', $ruleUnique,'max:30','nullable'],
            'spouse_occupation'=>['string','nullable'],
            'cep_payment_collection'=>['string','nullable','regex:/([0-9]{5})\-([0-9]{3})/'],
            'street_payment_collection'=>['string','nullable','max:450'],
            'number_payment_collection'=>['int','nullable'],
            'complement_payment_collection'=>['string','nullable','max:450'],
            'neighborhood_payment_collection'=>['string','nullable','max:450'],
            'city_payment_collection'=>['string','nullable','max:450'],
            'state_payment_collection'=>['string','nullable','max:2'],

        ],$this->msgClients())->validate();
    }

    private function msgClients(){
        return [
            'id_representative.required'=>'Clientes Juridicos são obrigatório ter um representante!',
            'name.string'=>'o campo nome tem que ser string',
            'name.max'=> 'o campo nome deve ter no maximo 450 caracteres',
            'name.required'=> 'o campo nome é obrigatório',
            'kind_person.int'=>'o tipo pessoa tem que ser inteiro',
            'birth_date.date'=>'o campo data nascimento tem que ser uma data valida',
            'rg.string'=>'o campo rg tem que ser string',   
            'rg.max'=>'o campo rg ter no maximo 30 caracteres',
            'emitting_organ.string'=>'o campo orgão emissor tem que ser string',   
            'emitting_organ.max'=>'o campo orgão emissor ter no maximo 30 caracteres', 
            'cpf.cpf'=>'cpf inválido',
            'cpf.required'=>'cpf é obrigatório',
            'cpf.unique'=>'esse cpf ja está sendo utilizado',
            'cnpj.cnpj'=>'cnpj inválido',
            'cnpj.required'=>'cnpj é obrigatório',
            'cnpj.unique'=>'esse cnpj ja está sendo utilizado',
            'marital_status.int'=>'o campo estado civil tem que ser inteiro',
            'nationality.int'=>'o campo nacionalidade tem que ser inteiro',
            'sex.int'=>'o campo sexo tem que ser inteiro',
            'email.email'=>'email inválido',
            'email.unique'=>'o email ja está sendo utilizado',
            'email.max'=>'o campo email deve ter no maximo 450 caracteres',
            'occupation.string'=>'o campo profissão tem que ser string',
            'cep.string'=>'o campo cep tem que ser string',
            'cep:regex'=>'Cep inválido',
            'cep.required'=>'o campo cep é obrigatório',
            'street.string'=>'o campo rua deve ser string',
            'street.max'=>'o campo rua deve ter no maximo 450 caracteres',
            'street.required'=>'o campo rua é obrigatório',
            'number.string'=>'o campo numero deve ser inteiro',
            'number.required'=>'o campo numero é obrigatório',
            'complement.string'=>'o campo complemento deve ser string',
            'complement.max'=>'o campo complemento deve ter no maximo 450 caracteres',
            'neighborhood.string'=>'o campo bairro deve ser string',
            'neighborhood.max'=>'o campo bairro deve ter no maximo 450 caracteres',
            'neighborhood.required'=>'o campo bairro é obrigatório',
            'city.string'=>'o campo cidade deve ser string',
            'city.max'=>'o campo cidade deve ter no maximo 450 caracteres',   
            'city.required'=>'o campo cidade é obrigatório',
            'state.string'=>'o campo estado cidade deve ser string',
            'state.max'=>'o campo estado deve ter no maximo 2 caracteres',   
            'state.required'=>'o campo estado é obrigatório',
            
            'spouse_name.string'=>'o campo nome cônjuge tem que ser string',
            'spouse_name.required'=>'o campo nome cônjuge é obrigatório',
            'spouse_name.max'=> 'o campo nome cônjuge deve ter no maximo 450 caracteres',
            'spouse_birth_date.date'=>'o campo data nascimento cônjuge tem que ser uma data valida',
            'spouse_rg.string'=>'o campo rg cônjuge tem que ser string',   
            'spouse_rg.max'=>'o campo rg cônjuge ter no maximo 30 caracteres',
            'spouse_emitting_organ.string'=>'o campo orgão emissor cônjuge tem que ser string',   
            'spouse_emitting_organ.max'=>'o campo orgão emissor cônjuge ter no maximo 30 caracteres', 
            'spouse_cpf.cpf'=>'cpf inválido',
            'spouse_cpf.required'=>'cpf do cônjuge é obrigatório',
            'spouse_cpf.unique'=>'esse cpf do cônjuge ja está sendo utilizado',
            'spouse_nationality.int'=>'o campo nacionalidade cônjuge tem que ser inteiro',
            'spouse_sex.int'=>'o campo sexo cônjuge tem que ser inteiro',
            'spouse_email.email'=>'email cônjuge inválido',
            'spouse_email.unique'=>'o email do cônjuge ja está sendo utilizado',
            'spouse_email.max'=>'o campo email cônjuge deve ter no maximo 450 caracteres',
            'spouse_occupation.string'=>'o campo profissão cônjuge tem que ser string',
            'cep_payment_collection.string'=>'o campo cep cobrança tem que ser string',
            'cep_payment_collection:regex'=>'Cep inválido',
            'street_payment_collection.string'=>'o campo rua cobrança deve ser string',
            'street_payment_collection.max'=>'o campo street_payment_collection deve ter no maximo 450 caracteres',
            'number_payment_collection.string'=>'o campo numero cobrança deve ser inteiro',
            'complement_payment_collection.string'=>'o campo complemento cobrança deve ser string',
            'complement_payment_collection.max'=>'o campo complemento cobrança deve ter no maximo 450 caracteres',
            'neighborhood_payment_collection.string'=>'o campo bairro cobrança deve ser string',
            'neighborhood_payment_collection.max'=>'o campo bairro cobrança deve ter no maximo 450 caracteres',
            'city_payment_collection.string'=>'o campo cidade cobrança deve ser string',
            'city_payment_collection.max'=>'o campo cidade cobrança deve ter no maximo 450 caracteres',   
            'state_payment_collection.string'=>'o campo estado cobrança cidade deve ser string',
            'state_payment_collection.max'=>'o campo estado cobrança deve ter no maximo 2 caracteres', 
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Interprises;
use App\Models\Lot;
use App\Models\Companies;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class InterpriseController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type==6 || $this->user->type==5){
                return redirect()->route('accessDenied');
            }
            return $next($request);
        });   
    }

    public function index(Request $request){
        $data=[];
        $data['interprises']=Interprises::all();
        $data['name']="";
        $data['city']="";
        $data['stateChoise']="";
        $data['observation']="";
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
                             "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        $data['date']="";
        
        $dataInterpriseLike=$request->only(['name','city','observation']);
        $dataInterpriseEquals = $request->only('date','state');
     
        if($request->hasAny(['name','city','observation','state','date'])){
            $query=Interprises::query();
            foreach ($dataInterpriseLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataInterpriseEquals as $name => $value) {
                if ($value) { 
                    $query->where($name, '=', $value);
                }
            }
            $data['interprises']=$query->get();
            
            $data['name']=$dataInterpriseLike['name'];
            $data['city']=$dataInterpriseLike['city'];
            $data['observation']=$dataInterpriseLike['observation'];
            $data['stateChoise']=$dataInterpriseEquals['state'];
            $data['date']=$dataInterpriseEquals['date'];
        }

        if(Auth::user()->type!=1){
            $interprisesPermissionUser=[];
            foreach ($data['interprises'] as $key => $interprise) {
                $id_user_permission=explode(',',$interprise->id_user_permission);

                if(in_array(Auth::user()->id,$id_user_permission)){
                    $interprisesPermissionUser[]=$interprise;
                }
            }

            $data['interprises']=$interprisesPermissionUser;
        }
        
        return view('allInterprise',$data);
    }

    public function add(Request $request){
        if(Auth::user()->type!=1){
            Auth::logout();
        }
        
        $data=[];
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
                             "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        
        $dataInterprise=$request->only(['name','city','state','observation','id_companies',
            'id_companies_porc','id_user_permission']);
        if($request->has(['name','city','state','observation'])){
            $this->validator($dataInterprise);

            $interprise=new Interprises();
            $interprise->name=$dataInterprise['name'];
            $interprise->city=$dataInterprise['city'];
            $interprise->state=$dataInterprise['state'];
            $interprise->date=date('Y-m-d');
            $interprise->observation=$dataInterprise['observation'];
            $interprise->company_ids=$dataInterprise['id_companies'];
            $interprise->company_perc=$dataInterprise['id_companies_porc'];
            $id_user_permission=$request->has('id_user_permission')!=""?implode(',',$dataInterprise['id_user_permission']):"";
            $interprise->id_user_permission=$id_user_permission;
            $interprise->save();

            return redirect()->route('allInterprises');
        }

        $data['companies']=Companies::where('status',1)->get();
        $data['company_name']="";
        $data['cnpj']="";
        $data['representative_cpf']="";

        $dataCompanyLike=$request->only(['company_name','cnpj','representative_cpf']);
        if($request->hasAny(['company_name','cnpj','representative_cpf'])){
            $query=Companies::query();
            foreach ($dataCompanyLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            $data['companies']=$query->where('status',1)->get();
            
            $data['company_name']=$dataCompanyLike['company_name'];
            $data['cnpj']=$dataCompanyLike['cnpj'];
            $data['representative_cpf']=$dataCompanyLike['representative_cpf'];
        }

        $data['users']=User::where('status',1)
            ->where('type',"!=",1)
            ->where('type',"!=",5)
            ->where('type',"!=",6)
            ->get();


        return view("addInterprise",$data);
    }

    private function validator(array $data){
        return Validator::make($data,[
            'name'=>['required','string','max:450'],
            'city'=>['required','string','max:450'],
            'state'=>['required','string','max:2'],
            'observation'=>['string','max:16777215','nullable'],
        ],$this->msgInterprise())->validate();
    }

    public function edit(Request $request,$idInterprise){
        if(Auth::user()->type!=1){
            Auth::logout();
        }
        
        $data=[];
        $data['interprise']=Interprises::where('id',$idInterprise)->first();;
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];

        $dataInterprise=$request->only(['id','name','city','state','observation','id_companies',
            'id_companies_porc','id_user_permission']);
        if($request->has(['name','city','state','observation'])){
            $this->validatorEdit($dataInterprise);
            $interprise=Interprises::where('id',$dataInterprise['id'])->first();
            $interprise->name=$dataInterprise['name'];
            $interprise->city=$dataInterprise['city'];
            $interprise->state=$dataInterprise['state'];
            $interprise->observation=$dataInterprise['observation'];
            $interprise->company_ids=$dataInterprise['id_companies'];
            $interprise->company_perc=$dataInterprise['id_companies_porc'];
            $id_user_permission=$request->has('id_user_permission')!=""?implode(',',$dataInterprise['id_user_permission']):"";
            $interprise->id_user_permission=$id_user_permission;
            $interprise->save();

            return redirect()->route('allInterprises');
        }
        
        $data['companies']=Companies::where('status',1)->get();

        $data['company_name']="";
        $data['cnpj']="";
        $data['representative_cpf']="";

        $dataCompanyLike=$request->only(['company_name','cnpj','representative_cpf']);
        if($request->hasAny(['company_name','cnpj','representative_cpf'])){
            $query=Companies::query();
            foreach ($dataCompanyLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            $data['companies']=$query->where('status',1)->get();
            
            $data['company_name']=$dataCompanyLike['company_name'];
            $data['cnpj']=$dataCompanyLike['cnpj'];
            $data['representative_cpf']=$dataCompanyLike['representative_cpf'];
        }
        

        $data['companies_interprise_ids']=explode(',',$data['interprise']->company_ids);;
        $data['companies_interprise']=[];
        $companies_interprise_perc=explode(',',$data['interprise']->company_perc);

        foreach ($data['companies_interprise_ids'] as $key => $id) {
            $company=Companies::where('id',$id)->first();
            $company_perc_divide=explode('-',$companies_interprise_perc[$key]);
            $companie_info=[
                'company'=>$company,
                'perc'=>$company_perc_divide[1]
            ];

            $data['companies_interprise'][]=$companie_info;
        }

        $data['users']=User::where('status',1)
        ->where('type',"!=",1)
        ->where('type',"!=",5)
        ->where('type',"!=",6)
        ->get();

        $data['id_user_permission']=explode(',',$data['interprise']->id_user_permission);
        
        return view("editInterprise",$data);
    }

    private function validatorEdit(array $data){
        return Validator::make($data,[
            'id'=>['required','int'],
            'name'=>['required','string','max:450'],
            'city'=>['required','string','max:450'],
            'state'=>['required','string','max:2'],
            'observation'=>['string','max:16777215','nullable'],
        ],$this->msgInterprise())->validate();
    }

    private function msgInterprise(){
        return[
            'id.required'=>'O id do empreendimento não foi enviado',
            'id.int'=>'O id empreendimento está inválido',
            'name.required'=>'O campo nome é obrigatório',
            'name.string'=>'O campo nome está inválido',
            'name.max'=>'O campo nome tem que ter no maximo 450 caracteres',
            'state.required'=>'O campo estado é obrigatório',
            'state.string'=>'O campo estado está inválido',
            'state.max'=>'O campo nome tem que ter no maximo 2 caracteres',
            'observation.string'=>'O campo observação está inválido',
            'observation.max'=>'O campo observação tem que ter no maximo 16777215 caracteres',
            'city.required'=>'O campo cidade é obrigatório',
            'date:required'=>'O campo data é obrigatório',
            'date:date'=>'O campo data tem que ser uma data valida'
        ];
    }
    

    public function delete($idInterprise){
        if(Auth::user()->type!=1){
            Auth::logout();
        }
        if(!empty($idInterprise)){
            $interprise=Interprises::where('id',$idInterprise)->first();
            $interprise->delete();
        }

        return redirect()->route('allInterprises');;
    }

    public function allLot($idInterprise,Request $request){
        $data=[];
        $data['interprise']=Interprises::where('id',$idInterprise)->first();
        $data['allLot']=Lot::where('id_interprise',$idInterprise)->get();

        $companies_interprise_ids=explode(',',$data['interprise']->company_ids);
        
        $data['companies']=[];
        foreach ($companies_interprise_ids as $key => $id) {
            $company=Companies::where('id',$id)->first();
            $data['companies'][]=$company;
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

        
        $data['lot_number']="";
        $data['block']="";
        $data['area']="";
        $data['confrontations']="";
        $data['available']="";

        $dataLotLike=$request->only(['block','area','confrontations']);
        $dataLotEquals = $request->only(['lot_number','available']);
        
        if($request->hasAny(['lot_number','block','area','confrontations'])){
            $query=Lot::query();
            foreach ($dataLotLike as $name => $value) {
                if($value){
                   $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataLotEquals as $name => $value) {
                if ($value) { 
                    $query->where($name, '=', $value);
                }
            }
            $data['allLot']=$query->get();
            $data['block']=$dataLotLike['block'];
            $data['area']=$dataLotLike['area'];
            $data['confrontations']=$dataLotLike['confrontations'];
            $data['lot_number']=$dataLotEquals['lot_number'];
            $data['available']=$dataLotEquals['available'];
        }
        

        return view('allLot',$data);
    }

    public function seeLot($idLot){
        if(!empty($idLot)){
            $data['lot']=Lot::where('id',$idLot)->first();
            return view('seeLot',$data);
        }

        return redirect()->route('allInterprises');
    }

    public function addLot($idInterprise,Request $request){
        $data=[];
        $data['idInterprise']=$idInterprise;
        
        $dataLot=$request->only(['idInterprise','block','area','lot_number','confrontations',
            'registration_number','municipal_registration','present_value','future_value','input',
            'financing_term','financing_rate']);
        
        if($request->hasAny(['block','area','lot_number','confrontations','visible','registration_number',
            'municipal_registration','present_value','future_value','input','financing_term','financing_rate'])){
            $this->validatorLot($dataLot);

            $lot=new Lot();    
            $lot->id_interprise=$dataLot['idInterprise'];
            $lot->name='Quadra '.$dataLot['block']."; Lot ".$dataLot['lot_number'];
            $lot->block=$dataLot['block'];
            $lot->area=$dataLot['area'];
            $lot->lot_number=$dataLot['lot_number'];
            $lot->confrontations=$dataLot['confrontations'];
            $lot->registration_number=$dataLot['registration_number'];
            $lot->municipal_registration=$dataLot['municipal_registration'];
            $lot->present_value=$dataLot['present_value'];
            $lot->future_value=$dataLot['future_value'];
            $lot->input=$dataLot['input'];
             
            $lot->available=1;
            $lot->save();

            return redirect()->route('allLot',['idInterprise'=>$dataLot['idInterprise']]);
        }    

        return view('addLot',$data);
    }

    public function editLot($idLot,Request $request){
        $data=[];
        $data['lot']=Lot::where('id',$idLot)->first();
        
        $dataLot=$request->only(['idLot','block','area','lot_number','confrontations',
            'registration_number','municipal_registration','present_value','future_value','input',
            'descont','financing_term','financing_rate']);
        
        if($request->hasAny(['block','area','lot_number','confrontations','visible','registration_number',
            'municipal_registration','present_value','future_value','input','descont','financing_term','financing_rate'])){
            
            $lot=Lot::where('id',$dataLot['idLot'])->first();      
            $dataLot['idInterprise']=$lot->id_interprise;
            
            $this->validatorLot($dataLot);   
            $lot->name='Quadra '.$dataLot['block']." Lot ".$dataLot['lot_number'];
            $lot->block=$dataLot['block'];
            $lot->area=$dataLot['area'];
            $lot->lot_number=$dataLot['lot_number'];
            $lot->confrontations=$dataLot['confrontations'];
            $lot->registration_number=$dataLot['registration_number'];
            $lot->municipal_registration=$dataLot['municipal_registration'];
            $lot->descont=$dataLot['descont'];
            $lot->present_value=$dataLot['present_value'];
            $lot->future_value=$dataLot['future_value'];
            $lot->input=$dataLot['input'];
            $lot->available=1;
            $lot->visible=1;
            $lot->save();

            return redirect()->route('allLot',['idInterprise'=>$lot->id_interprise]);
        }    

        return view('editLot',$data);

    }

    public function deleteLot($idLot){
        if(!empty($idLot)){
            $lot=Lot::where('id',$idLot)->first();
            $idInterprise=$lot->id_interprise;
            $lot->delete();
        }

        return redirect()->route('allLot',['idInterprise'=>$idInterprise]);;
    }

    private function validatorLot(array $data){
        return Validator::make($data,[
            'idInterprise'=>['required','int'],
            'block'=>['required','string','max:450','nullable'],
            'area'=>['required','max:450','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'lot_number'=>['required','int','nullable'],
            'confrontations'=>['string','nullable'],
            'registration_number'=>['string','max:450','nullable'],
            'municipal_registration'=>['string','max:450','nullable'],
            'present_value'=>['required','max:30','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'future_value'=>['required','max:30','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'input'=>['max:30','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'descont'=>['max:30','regex:/^([0-9\.\,]{1,})$/','nullable'],
            'financing_term'=>['string','max:30','nullable'],
            'financing_rate'=>['string','max:30','nullable']
        ],$this->msgLot())->validate();
    }

    private function msgLot(){
        return [
            'idInterprise.required'=>'id empreendimento não foi enviado',
            'idInterprise.int'=>'id empreendimento está inválido',
            'block.string'=>'o campo quadra deve ser string',
            'block.max'=>'o campo quadra deve ter no maximo 450 caracteres',
            'block.required'=>'O campo quadra é obrigatório',
            'area.max'=>'o campo quadra deve ter no maximo 450 caracteres',
            'area.regex'=>'dado da area está inválido',
            'area.required'=>'o campo area é obrigatório',
            'lot_number.int'=>'dado do numero do lot está inválido',
            'lot_number.required'=>'o campo numero lot é obrigatório',
            'confrontations.string'=>'dado da confrotação está inválido',
            'registration_number.string'=>'dado do numero de registro está inválido',
            'registration_number.max'=>'o campo numero do registro deve ter no maximo 450 caracteres',
            'municipal_registration.string'=>'dado do registro municipal está inválido',
            'municipal_registration.max'=>'o campo numero do registro municipal deve ter no maximo 450 caracteres',
            'present_value.max'=>'o campo valor atual deve ter no maximo 30 caracteres',
            'present_value.regex'=>'dado do valor atual está inválido',
            'present_value.required'=>' o campo valor atual é obrigatório',
            'future_value.max'=>'o campo valor futuro deve ter no maximo 30 caracteres',
            'future_value.regex'=>'dado do valor futuro está inválido',
            'future_value.required'=>'o valor futuro é obrigatório',
            'input.max'=>'o campo entrada deve ter no maximo 30 caracteres',
            'input.regex'=>'dado da entrada está inválido',
            'descont.max'=>'o campo desconto deve ter no maximo 30 caracteres',
            'descont.regex'=>'dado do desconto está inválido',
            'financing_term.string'=>'dado do termo de financiamento está inválido',
            'financing_term.max'=> 'o campo termo de financiamento deve ter no maximo 30 caracteres',
            'financing_rate.string'=>'dado do termo de financiamento está inválido',
            'financing_term.rate'=> 'o campo termo de avaliação deve ter no maximo 30 caracteres',

            
        ];
    }
}

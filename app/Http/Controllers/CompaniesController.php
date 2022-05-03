<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Companies;
use Illuminate\Support\Facades\Validator;

class CompaniesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');
        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type!=1){
                return redirect()->route('accessDenied');
            }
            return $next($request);
        });   
    }

    public function index(Request $request){
        $data['companies']=Companies::where('status',1)->get();
        $data['stateChoise']="";
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
                             "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        
        $data['company_name']="";
        $data['cnpj']="";
        $data['street']="";
        $data['number']="";
        $data['neighborhood']="";
        $data['city']="";
        $data['cep']="";
        $data['representative_cpf']="";

        $dataCompanyLike=$request->only(['company_name','cnpj','street','number',
            'neighborhood','city','cep','representative_cpf']);
        if($request->hasAny(['company_name','cnpj','street','number',
            'neighborhood','city','cep','representative_cpf'])){
            $query=Companies::query();
            foreach ($dataCompanyLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            $data['companies']=$query->where('status',1)->get();
            
            $data['company_name']=$dataCompanyLike['company_name'];
            $data['cnpj']=$dataCompanyLike['cnpj'];
            $data['street']=$dataCompanyLike['street'];
            $data['number']=$dataCompanyLike['number'];
            $data['neighborhood']=$dataCompanyLike['neighborhood'];
            $data['city']=$dataCompanyLike['city'];
            $data['cep']=$dataCompanyLike['cep'];
            $data['representative_cpf']=$dataCompanyLike['representative_cpf'];

        }
                             
        
        return view('allCompanies',$data);
    }

    public function add(Request $request){
        $data=[];
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        
        $dataCompanies=$request->only(['company_name','cnpj','street','number','cep','neighborhood','complement',
        'city','state','representative_name','representative_marital_status','representative_occupation',
        'representative_rg','representative_cpf','representative_street','representative_complement','representative_number',
        'representative_cep','representative_neighborhood','representative_city','representative_state','representative_nationality']);
         
        

        if($request->hasAny(['company_name','cnpj','street','number','cep','neighborhood',
            'city','state','representative_name','representative_marital_status','representative_occupation',
            'representative_rg','representative_cpf','representative_street','representative_number',
            'representative_cep','representative_neighborhood','representative_city','representative_state'])){
            
            $this->validator($dataCompanies);
            
            $companies=new Companies();
            $companies->company_name=$dataCompanies['company_name'];
            $companies->cnpj=$dataCompanies['cnpj'];
            $companies->street=$dataCompanies['street'];
            $companies->number=$dataCompanies['number'];
            $companies->complement=$dataCompanies['complement']!=""?$dataCompanies['complement']:"";
            $companies->cep=$dataCompanies['cep'];
            $companies->neighborhood=$dataCompanies['neighborhood'];
            $companies->city=$dataCompanies['city'];
            $companies->state=$dataCompanies['state'];
            $companies->representative_name=$dataCompanies['representative_name'];
            $companies->representative_marital_status=$dataCompanies['representative_marital_status'];
            $companies->representative_occupation=$dataCompanies['representative_occupation'];
            $companies->representative_rg=$dataCompanies['representative_rg'];
            $companies->representative_cpf=$dataCompanies['representative_cpf'];
            $companies->representative_street=$dataCompanies['representative_street'];
            $companies->representative_number=$dataCompanies['representative_number'];
            $companies->representative_complement=$dataCompanies['representative_complement']!=""?$dataCompanies['representative_complement']:"";
            $companies->representative_cep=$dataCompanies['representative_cep'];
            $companies->representative_neighborhood=$dataCompanies['representative_neighborhood'];
            $companies->representative_city=$dataCompanies['representative_city'];
            $companies->representative_state=$dataCompanies['representative_state'];
            $companies->representative_nationality=$dataCompanies['representative_nationality'];
            $companies->status=1;
            $companies->save();

            return redirect()->route('allCompanies');
        }
        
        return view('addCompany',$data);
    }

    public function edit($idCompany,Request $request){
        $data=[];
        $data['company']=Companies::where('id',$idCompany)->first();
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        
        $dataCompanies=$request->only(['idCompany','company_name','cnpj','street','number','cep','neighborhood','complement',
        'city','state','representative_name','representative_marital_status','representative_occupation',
        'representative_rg','representative_cpf','representative_street','representative_complement','representative_number',
        'representative_cep','representative_neighborhood','representative_city','representative_state','representative_nationality']);
         

        if($request->hasAny(['company_name','cnpj','street','number','cep','neighborhood',
            'city','state','representative_name','representative_marital_status','representative_occupation',
            'representative_rg','representative_cpf','representative_street','representative_number',
            'representative_cep','representative_neighborhood','representative_city','representative_state'])){
            
            $this->validator($dataCompanies);
            
            $companies=Companies::where('id',$dataCompanies['idCompany'])->first();
            $companies->company_name=$dataCompanies['company_name'];
            $companies->cnpj=$dataCompanies['cnpj'];
            $companies->street=$dataCompanies['street'];
            $companies->number=$dataCompanies['number'];
            $companies->complement=$dataCompanies['complement']!=""?$dataCompanies['complement']:"";
            $companies->cep=$dataCompanies['cep'];
            $companies->neighborhood=$dataCompanies['neighborhood'];
            $companies->city=$dataCompanies['city'];
            $companies->state=$dataCompanies['state'];
            $companies->representative_name=$dataCompanies['representative_name'];
            $companies->representative_marital_status=$dataCompanies['representative_marital_status'];
            $companies->representative_occupation=$dataCompanies['representative_occupation'];
            $companies->representative_rg=$dataCompanies['representative_rg'];
            $companies->representative_cpf=$dataCompanies['representative_cpf'];
            $companies->representative_street=$dataCompanies['representative_street'];
            $companies->representative_number=$dataCompanies['representative_number'];
            $companies->representative_complement=$dataCompanies['representative_complement']!=""?$dataCompanies['representative_complement']:"";
            $companies->representative_cep=$dataCompanies['representative_cep'];
            $companies->representative_neighborhood=$dataCompanies['representative_neighborhood'];
            $companies->representative_city=$dataCompanies['representative_city'];
            $companies->representative_state=$dataCompanies['representative_state'];
            $companies->representative_nationality=$dataCompanies['representative_nationality'];
            $companies->save();

            return redirect()->route('allCompanies');
        }
        
        return view('editCompany',$data);
    }


    public function suspend($id){
        $companies=Companies::where('id',$id)->first();
        $companies->status=2;
        $companies->save();

        return redirect()->route('allCompanies');
    }

    public function validator($data){
        return Validator::make($data,[
            'company_name'=>['required','string','max:450'],
            'cnpj'=>['required','cnpj'],
            'street'=>['required','string','max:450'],
            'number'=>['required','string','max:50'],
            'cep'=>['required','string','regex:/([0-9]{5})\-([0-9]{3})/'],
            'neighborhood'=>['required','string','max:450'],
            'city'=>['required','string','max:450'],
            'state'=>['required'],
            'representative_name'=>['string','max:450','nullable'],
            'representative_occupation'=>['string','max:450','nullable'],
            'representative_rg'=>['string','max:450','nullable'],
            'representative_cpf'=>['cpf','max:14','nullable'],
            'representative_street'=>['string','max:450','nullable'],
            'representative_number'=>['string','max:50','nullable'],
            'representative_complement'=>['string','max:450','nullable'],
            'representative_cep'=>['string','regex:/([0-9]{5})\-([0-9]{3})/','nullable'],
            'representative_neighborhood'=>['string','max:450','nullable'],
            'representative_city'=>['string','max:450','nullable'],
        ],$this->msgCompanies())->validate();
    }

    private function msgCompanies(){
        return [
            'company_name.required'=>'o campo nome da empresa é obrigatório',
            'company_name.string'=>'o campo nome da empresa tem que ser string',
            'company_name.max'=> 'o campo nome da empresa deve ter no maximo 450 caracteres',
            'cnpj.required'=>'o campo cnpj é obrigatório',
            'cnpj.cnpj'=>'o cnpj está inválido',
            'street.required'=>'o campo rua da empresa é obrigatório',
            'street.string'=>'o campo rua da empresa tem que ser string',
            'street.max'=> 'o campo rua da empresa deve ter no maximo 450 caracteres',
            'number.required'=>'o campo numero da empresa é obrigatório',
            'number.string'=>'o campo numero da empresa tem que ser string',
            'number.max'=> 'o campo numero da empresa deve ter no maximo 50 caracteres',
            'complement.string'=>'o campo complemento da empresa tem que ser string',
            'complement.max'=> 'o campo complemento da empresa deve ter no maximo 450 caracteres',
            'cep.required'=>'o campo cep da empresa é obrigatório',
            'cep.string'=>'o campo cep da empresa tem que ser string',
            'cep.regex'=> 'o campo cep da empresa é obrigatório',
            'neighborhood.required'=>'o campo bairro da empresa é obrigatório',
            'neighborhood.string'=>'o campo bairro da empresa tem que ser string',
            'neighborhood.max'=> 'o campo bairro da empresa deve ter no maximo 450 caracteres',
            'city.required'=>'o campo cidade da empresa é obrigatório',
            'city.string'=>'o campo cidade da empresa tem que ser string',
            'city.max'=> 'o campo cidade da empresa deve ter no maximo 450 caracteres',
            'state.required'=>'o estado da empresa é obrigatório',
            
            'representative_name.string'=>'o campo nome do representante tem que ser string',
            'representative_name.max'=> 'o campo nome do representante deve ter no maximo 450 caracteres',
            'representative_occupation.required'=>'o campo profissão do representante é obrigatório',
            'representative_occupation.string'=>'o campo profissão do representante tem que ser string',
            'representative_occupation.max'=> 'o campo profissão do representante deve ter no maximo 450 caracteres',
            'representative_rg.required'=>'o campo rg do representante é obrigatório',
            'representative_rg.string'=>'o campo rg do representante tem que ser string',
            'representative_rg.max'=> 'o campo rg do representante deve ter no maximo 450 caracteres',
            'representative_cpf.cpf'=>'o cpf do representante está inválido',
            'representative_street.string'=>'o campo rua do representante tem que ser string',
            'representative_street.max'=> 'o campo rua do representante deve ter no maximo 450 caracteres',
            'representative_number.string'=>'o campo numero do representante tem que ser string',
            'representative_number.max'=> 'o campo numero do representante deve ter no maximo 50 caracteres',
            'representative_complement.string'=>'o campo complemento do representante tem que ser string',
            'representative_complement.max'=> 'o campo complemento do representante deve ter no maximo 450 caracteres',
            'representative_cep.string'=>'o campo cep do representante tem que ser string',
            'representative_cep.regex'=> 'o campo cep do representante é obrigatório',
            'representative_neighborhood.required'=>'o campo bairro do representante é obrigatório',
            'representative_neighborhood.string'=>'o campo bairro do representante tem que ser string',
            'representative_neighborhood.max'=> 'o campo bairro do representante deve ter no maximo 450 caracteres',
            'representative_city.required'=>'o campo cidade do representante é obrigatório',
            'representative_city.string'=>'o campo cidade do representante tem que ser string',
            'representative_city.max'=> 'o campo cidade do representante deve ter no maximo 450 caracteres',
            'representative_state.required'=>'o estado do representante é obrigatório',
           
        ];
    }  
}

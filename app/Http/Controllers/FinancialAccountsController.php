<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class FinancialAccountsController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');
        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type==6 || $this->user->type==4 || $this->user->type==5){
                return redirect()->route('accessDenied');
            }
            return $next($request);
        });   
    }

    public function index(Request $request){
        $data=[];
        $data['allFinancialAccounts']=Bank::where('status',1)->get();
        
        $data['name']="";
        $data['id_recipient']="";
        $data['bank_agency']="";
        $data['verifying_digit']="";
        $data['account']="";
        $data['description']="";

        $dataAccuntLike=$request->only(['name','id_recipient','bank_agency','verifying_digit',
            'account','description']);
        
        if($request->hasAny(['name','id_recipient','bank_agency','verifying_digit',
            'account','description'])){
            
            $query=Bank::query();
            foreach ($dataAccuntLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            $data['allFinancialAccounts']=$query->where('status',1)->get();
            $data['name']=$dataAccuntLike['name'];
            $data['id_recipient']=$dataAccuntLike['id_recipient'];
            $data['bank_agency']=$dataAccuntLike['bank_agency'];
            $data['verifying_digit']=$dataAccuntLike['verifying_digit'];
            $data['account']=$dataAccuntLike['account'];;
            $data['description']=$dataAccuntLike['description'];;
        }

        if(Auth::user()->type!=1){
            $banksPermissionUser=[];
            foreach ($data['allFinancialAccounts'] as $key => $bank) {
                $id_user_permission=explode(',',$bank->id_user_permission);
                if(in_array(Auth::user()->id,$id_user_permission)){
                    $banksPermissionUser[]=$bank;
                }
            }

            $data['allFinancialAccounts']=$banksPermissionUser;
        }

        return view('allFinancialAccounts',$data);
    }

    public function addFinancialAccounts(Request $request){
        $data=[];
        $data=$request->only(['id_recipient','recipient','cnpj','bank_name','id_bank','bank_agency',
            'verifying_digit','account','wallet','byte','post','accept','kind_doc','street','number',
            'neighborhood','city','uf','cep','observation','id_user_permission']);        
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        
            
        if($request->hasAny(['id_recipient','recipient','cnpj','bank_name','id_bank','bank_agency',
            'verifying_digit','account','wallet','byte','post','accept','kind_doc','street','number',
            'neighborhood','city','uf','cep','observation'])){
            $this->validator($data);
            
            $financialAccounts = new Bank();  
            $financialAccounts->id_recipient=$data['id_recipient'];
            $financialAccounts->recipient=$data['recipient'];
            $financialAccounts->cnpj=$data['cnpj'];
            $financialAccounts->name=$data['bank_name'];
            $financialAccounts->id_bank=$data['id_bank'];
            $financialAccounts->bank_agency=$data['bank_agency'];
            $financialAccounts->verifying_digit=$data['verifying_digit'];
            $financialAccounts->account=$data['account'];
            $financialAccounts->wallet=$data['wallet'];
            $financialAccounts->byte=$data['byte'];
            $financialAccounts->post=$data['post'];
            $financialAccounts->accept=$data['accept'];
            $financialAccounts->kind_doc=$data['kind_doc'];
            $financialAccounts->street=$data['street'];
            $financialAccounts->number=$data['number'];
            $financialAccounts->neighborhood=$data['neighborhood'];
            $financialAccounts->city=$data['city'];
            $financialAccounts->uf=$data['uf'];
            $financialAccounts->cep=$data['cep'];
            $financialAccounts->description=$data['observation'];
            $financialAccounts->status=1;
            $idUsersPermission=$request->has('id_user_permission')!=""?implode(',',$data['id_user_permission']):"";
            $financialAccounts->id_user_permission=$idUsersPermission;
            $financialAccounts->save();

            return redirect()->route('allFinancialAccounts');
        }

        $data['users']=User::where('status',1)
        ->where('type',"!=",1)
        ->where('type',"!=",5)
        ->where('type',"!=",6)
        ->where('type',"!=",4)
        ->get();

        return view('addFinancialAccounts',$data);
    }

    public function editFinancialAccounts($idAccount,Request $request){
        $data=[];
        $data=$request->only(['idAccount','id_recipient','recipient','cnpj','bank_name','id_bank','bank_agency',
            'verifying_digit','account','wallet','byte','post','accept','kind_doc','street','number',
            'neighborhood','city','uf','cep','observation','id_user_permission']);        
        
        $data['states'] = ["AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB",
        "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" ];
        
        $data['financialAccount']=Bank::where('id',$idAccount)->first();
            
        if($request->hasAny(['idAccount','id_recipient','recipient','cnpj','bank_name','id_bank','bank_agency',
            'verifying_digit','account','wallet','byte','post','accept','kind_doc','street','number',
            'neighborhood','city','uf','cep','observation'])){
            $this->validator($data,$data['financialAccount']);
            
            $financialAccounts = Bank::where('id',$data['idAccount'])->first();  
            $financialAccounts->id_recipient=$data['id_recipient'];
            $financialAccounts->recipient=$data['recipient'];
            $financialAccounts->cnpj=$data['cnpj'];
            $financialAccounts->name=$data['bank_name'];
            $financialAccounts->id_bank=$data['id_bank'];
            $financialAccounts->bank_agency=$data['bank_agency'];
            $financialAccounts->verifying_digit=$data['verifying_digit'];
            $financialAccounts->account=$data['account'];
            $financialAccounts->wallet=$data['wallet'];
            $financialAccounts->byte=$data['byte'];
            $financialAccounts->post=$data['post'];
            $financialAccounts->accept=$data['accept'];
            $financialAccounts->kind_doc=$data['kind_doc'];
            $financialAccounts->street=$data['street'];
            $financialAccounts->number=$data['number'];
            $financialAccounts->neighborhood=$data['neighborhood'];
            $financialAccounts->city=$data['city'];
            $financialAccounts->uf=$data['uf'];
            $financialAccounts->cep=$data['cep'];
            $financialAccounts->description=$data['observation'];
            $idUsersPermission=$request->has('id_user_permission')!=""?implode(',',$data['id_user_permission']):"";
            $financialAccounts->id_user_permission=$idUsersPermission;
            $financialAccounts->save();

            return redirect()->route('allFinancialAccounts');
        }

        $data['users']=User::where('status',1)
        ->where('type',"!=",1)
        ->where('type',"!=",5)
        ->where('type',"!=",6)
        ->where('type',"!=",4)
        ->get();
        
        return view('editFinancialAccounts',$data);
    }

    public function seeFinancialAccounts($idAccount){
        $data=[];
        $data['financialAccounts']=Bank::where('id',$idAccount)->first();

        return view('seeFinancialAccounts',$data);
    }

    public function deleteAccounts($idAccount){
        if(Auth::user()->type != 1){
            Auth::logout();
            return redirect()->route('login');
        }
        
        if(!empty($idAccount)){
            $financialAccounts=Bank::where('id',$idAccount)->first();
            $financialAccounts->status=2;
            $financialAccounts->save();
        }

        return redirect()->route('allFinancialAccounts');
        
    }

    private function validator($data,$account=null){
        $ruleUnique=[];
        if($account!=null){
            $ruleUnique=Rule::unique('financial_accounts')->ignore($account->id);
        }else{
            $ruleUnique='unique:financial_accounts';
        }

        return Validator::make($data,[
            'id_recipient'=>['required','int'],
            'recipient'=>['required','string','max:450'],
            'cnpj'=>['required','cnpj'],
            'bank_name'=>['required','string','max:450'],
            'id_bank'=>['required','int'],
            'bank_agency'=>['required','int'],
            'verifying_digit'=>['required','int'],
            'account'=>['required','int'],
            'wallet'=>['required','string','max:4'],
            'byte'=>['required','string','max:4'],
            'post'=>['required','string','max:10'],
            'accept'=>['required','string','max:1'],
            'kind_doc'=>['required','string','max:10'],
            'cep'=>['required','string','regex:/([0-9]{5})\-([0-9]{3})/'],
            'street'=>['required','string','max:450'],
            'number'=>['int','nullable'],
            'neighborhood'=>['required','string','max:450'],
            'city'=>['required','string','max:450'],
            'uf'=>['required','string','max:2'],
            'observation'=>['string','nullable']
        ],$this->msgAccount())->validate();
    }

    private function msgAccount(){
        return [
            'id_recipient.required'=>'o campo codigo do beneficiario é obrigatório!',
            'id_recipient.int'=>'o campo codigo do beneficiario tem que ser inteiro!',
            'recipient.required'=>'o campo beneficiario é obrigatório!',
            'recipient.string'=>'o campo beneficiario tem que ser string!',
            'recipient.max'=>'o campo beneficiario tem que ter no maximo 450 caracteres!',
            'cnpj.cnpj'=>'cnpj inválido!',
            'cnpj.required'=>'cnpj é obrigatório!',
            'cnpj.unique'=>'esse cnpj ja está sendo utilizado!',
            'bank_name.required'=>'nome do banco é obrigatório!',
            'bank_name.string'=>'o campo nome do banco tem que ser string!',
            'bank_name.max'=>'o campo nome do banco tem que ter no maximo 450 caracteres!',
            'id_bank.required'=>'o campo codigo do banco é obrigatório!',
            'id_bank.int'=>'o campo codigo do banco tem que ser inteiro!',
            'bank_agency.required'=>'o campo agencia é obrigatório!',
            'bank_agency.int'=>'o campo agencia tem que ser inteiro!',
            'verifying_digit.required'=>'o campo Dv é obrigatório!',
            'verifying_digit.int'=>'o campo Dv tem que ser inteiro!',
            'account.required'=>'o campo conta é obrigatório!',
            'account.int'=>'o campo conta tem que ser inteiro!',
            'wallet.required'=>'o campo carteira é obrigatório!',
            'wallet.string'=>'o campo carteira tem que ser string!',
            'wallet.max'=>'o campo carteira tem que ter no maximo 4 caracteres!',
            'byte.required'=>'o campo byte é obrigatório!',
            'byte.string'=>'o campo byte tem que ser string!',
            'byte.max'=>'o campo byte tem que ter no maximo 4 caracteres!',
            'post.required'=>'o campo posto é obrigatório!',
            'post.string'=>'o campo posto tem que ser string!',
            'post.max'=>'o campo posto tem que ter no maximo 4 caracteres!',
            'accept.required'=>'o campo aceite é obrigatório!',
            'accept.string'=>'o campo aceite tem que ser string!',
            'accept.max'=>'o campo aceite tem que ter no maximo 4 caracteres!',
            'kind_doc.required'=>'o campo especie doc é obrigatório!',
            'kind_doc.string'=>'o campo especie doc tem que ser string!',
            'kind_doc.max'=>'o campo especie doc tem que ter no maximo 4 caracteres!',
            'cep.string'=>'o campo cep tem que ser string',
            'cep:regex'=>'Cep inválido',
            'street.string'=>'o campo rua deve ser string',
            'street.max'=>'o campo rua deve ter no maximo 450 caracteres',
            'number.string'=>'o campo numero deve ser inteiro',
            'neighborhood.string'=>'o campo bairro deve ser string',
            'neighborhood.max'=>'o campo bairro deve ter no maximo 450 caracteres',
            'city.string'=>'o campo cidade deve ser string',
            'city.max'=>'o campo cidade deve ter no maximo 450 caracteres',   
            'uf.string'=>'o campo estado cidade deve ser string',
            'uf.max'=>'o campo estado deve ter no maximo 2 caracteres',   
        ];
    }
}

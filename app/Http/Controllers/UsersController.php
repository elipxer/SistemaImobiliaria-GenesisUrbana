<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');
        }
     
    public function index(Request $request){
        if(Auth::user()->type != 1){
            return redirect()->route('accessDenied');
        }

        $data=[];
        $data['users']=User::where('status',1)->get();
        $data['name']="";
        $data['email']="";
        $data['type']="";
     

        $dataUserLike=$request->only(['name','email']);
        $dataUserEquals = $request->only('type');
        
       
        if($request->hasAny(['name','email','type'])){
            $query=User::query();
           
            foreach ($dataUserLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataUserEquals as $name => $value) {
                if ($value) { 
                    $query->where($name, '=', $value);
                }
            }
            $data['users']=$query->get();
            
             $data['name']=$dataUserLike['name'];
             $data['email']=$dataUserLike['email'];
             $data['type']=$dataUserEquals['type'];
        }

        $data['type_name']=[
            1=>'Administrador',
            2=>'Gestão',
            3=>'Operação',
            4=>'Comercialização',
            5=>'Júridico',
            6=>'Cliente'
        ];

        return view('allUsers',$data);
    }

    public function myProfile(){
        $data['type_name']=[
            1=>'Administrador',
            2=>'Gestão',
            3=>'Operação',
            4=>'Comercialização',
            5=>'Júridico',
            6=>'Cliente'
        ];
        
        $data['clientInfo']="";
        if(Auth::user()->type==6){
            $data['clientInfo']=Clients::where('id',Auth::user()->idClient)->first();
        }
        
        return view('myProfile',$data);
    }

    public function add(Request $request){
        if(Auth::user()->type != 1){
            Auth::logout();
            return redirect()->route('login');
        }
        $data=$request->only(['name','email','password','password_confirmation','type','photo','idClient']);
        if($request->has(['name','email','password','type'])){
            $this->validator($data);
            $image='user-default.png';
            if($request->has('photo')){
                if($request->file('photo')->isValid()){
                    $image=md5(rand(0,99999).rand(0,99999)).'.'.$request->file('photo')->getClientOriginalExtension();
                    $path="users/";
                    $request->file('photo')->storeAs($path,$image);
                }
            }
            $user=new User();
            $user->name=$data['name'];
            $user->email=$data['email'];
            $user->password=Hash::make($data['password']);
            $user->type=$data['type'];
            $user->date=date('Y-m-d',strtotime('NOW'));
            $user->status=1;
            $user->photo=$image;
            $user->idClient=$data['idClient']!=""?$data['idClient']:0;
            $user->save();
            
           return redirect()->route('allUsers');
        }

        return view('addUser');
        
    }

    protected function validator(array $data)
    {
       return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4','max:255','confirmed'],
            'photo'=>['mimes:jpeg,jpg,giff,svg,png'],
            'type' => ['required','int']
       ],$this->msgUsers())->validate();
    }


    public function edit($idUser){
        $data=[];
        if(Auth::user()->type != 1){
            Auth::logout();
            return redirect()->route('login');
        }
        $user=User::where('id',$idUser)->first();
        $data['clientInfo']="";
        if($user->type==6){
            $data['clientInfo']=Clients::where('id',$user->idClient)->first();
        }
        $data['user']=$user;
        return view('editUser',$data);
    }

    public function editAction(Request $request){
        $data=$request->only(['idUser','name','email','password','type','photo']); 
        $user=User::where('id',$data['idUser'])->first();
       
        //Alteração vinda do meu perfil
         if($request->has(['idUser','name','email','password'])){
            $this->validatorEdit($data);
            $image=$user->photo;
            $pass=$user->password;

            if($request->has('photo')){
                if($request->file('photo')->isValid()){
                    $image=md5(rand(0,99999).rand(0,99999)).'.'.$request->file('photo')->getClientOriginalExtension();
                    $path="users/";
                    $request->file('photo')->storeAs($path,$image);
                }
            }
              
             
            if($data['password'] != $pass){
                $pass=Hash::make($data['password']);
            }
            
            
            $user->name=$data['name'];
            $user->email=$data['email'];
            $user->password=$pass;
            $user->photo=$image;
            $user->save();
            
           return redirect()->route('myProfile');
        }

        //Alteração do administrador
        if($request->has(['type','idUser'])){
            $request->validate([
                'type'=>['required']
            ]);
            $user->type=$data['type'];
            $user->save();
            return redirect()->route('allUsers');
        } 
    }

    protected function validatorEdit(array $data){
       return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => [ 'string', 'min:4','max:255'],
            'photo'=>['mimes:jpeg,jpg,giff,svg,png'],
            'type' => ['required','int']
       ],$this->msgUsers())->validate();
    }

    
    private function msgUsers(){
        return[
            'name.required'=>'O campo nome é obrigatório',
            'name.string'=>'O campo nome está inválido',
            'name.max'=>'O campo nome tem que ter no maximo 255 caracteres',
            'email.required'=>'O campo email é obrigatório',
            'email.string'=>'O campo email está inválido',
            'email.max'=>'O campo nome tem que ter no maximo 255 caracteres',
            'email.email'=>'O campo email tem que ser um email valido',
            'photo:mimes'=>'a foto tem que ser do tipo jpeg/jpg/giff/svf,png',
            'type:required'=>'o campo tipo usuario é obrigatório',
            'type:int'=>'o campo tipo usuario está inválido',
        ];
    }

    public function delete($idUser){
        if(Auth::user()->type != 1){
            Auth::logout();
            return redirect()->route('login');
        }
        
        if(!empty($idUser)){
            $user=User::where('id',$idUser)->first();
            $user->delete();
        }

        return redirect()->route('allUsers');
    }

    public function firstAccess($idUser){
        return view('firstAccess');
    }
  
}

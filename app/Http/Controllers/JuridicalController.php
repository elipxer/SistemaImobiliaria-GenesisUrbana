<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JuridicalContact;
use App\Models\JuridicalUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Sales;
use App\Models\User;
use App\Models\Parcels;

class JuridicalController extends Controller
{   
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

        $this->middleware(function ($request, $next) {  
            $this->user = auth()->user();
            if($this->user->type!=1 && $this->user->type!=5 && $this->user->type!=3 ){
                return redirect()->route('accessDenied');
            }
            return $next($request);
        });   
    }
    
    public function index(){
        $data=[];
        $data['juridical_contacts']=[];
        $data['juridical_contacts_process']=[];
        if(Auth::user()->type==5){
            $data['juridical_contacts']=JuridicalContact::join('sales','sales.id','=','juridical_contacts.id_sale')
                ->where('status','!=',3)
                ->where('status','!=',4)
                ->where('id_juridicalUser',Auth::user()->id)
                ->get(['juridical_contacts.*','sales.id as idSale','sales.contract_number as contractNumber']);

            $data['juridical_contacts_process']=JuridicalContact::join('sales','sales.id','=','juridical_contacts.id_sale')
            ->where('status',3)
            ->orwhere('status',4)
            ->where('id_juridicalUser',Auth::user()->id)
            ->orderBy('id','DESC')
            ->get(['juridical_contacts.*','sales.id as idSale','sales.contract_number as contractNumber']);  
        
        }else if(Auth::user()->type==1 || Auth::user()->type==3){
            $data['juridical_contacts']=JuridicalContact::join('sales','sales.id','=','juridical_contacts.id_sale')
                ->where('status','!=',3)
                ->where('status','!=',4)
                ->where('id_user',Auth::user()->id)
                ->get(['juridical_contacts.*','sales.id as idSale','sales.contract_number as contractNumber']);
            
            $data['juridical_contacts_process']=JuridicalContact::join('sales','sales.id','=','juridical_contacts.id_sale')
                ->where('status',3)
                ->orwhere('status',4)
                ->where('id_user',Auth::user()->id)
                ->orderBy('id','DESC')
                ->get(['juridical_contacts.*','sales.id as idSale','sales.contract_number as contractNumber']);
        }

        return view('juridical',$data);
    }


    public function addJuridicalContact(Request $request){
        $data=$request->only(['situation','contactFile','id_user','id_sale']);
        Validator::make($data,[
            'situation'=>['required','string'],
            'id_user'=>['required','int'],
            'id_sale'=>['required','int'],
        ],[],[
            'situation'=>"situação",
            'id_user'=>"usuário Juridico"])
        ->validate();
        
        
        if($request->has(['situation','id_user','id_sale'])){
            $pathFile="";
            if(!empty($data['contactFile'])){
                $file=$data['contactFile'];
                if($file->isValid()){
                    $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    $pathContact="contactFiles/juridical/";
                    $file->storeAs($pathContact,$contactFile);
                    $pathFile=$pathContact.$contactFile;
                }
            }

            $juridicalContact=new JuridicalContact();
            $juridicalContact->situation=$data['situation'];
            $juridicalContact->file_path=$pathFile;
            $juridicalContact->id_juridicalUser=$data['id_user'];
            $juridicalContact->id_user=Auth::user()->id;
            $juridicalContact->id_sale=$data['id_sale'];
            $juridicalContact->register_date=date('Y-m-d');
            $juridicalContact->register_time=date('H:i:s');
            $juridicalContact->save();

            $sale=Sales::where('id',$data['id_sale'])->first();
            $sale->type=5;
            $sale->save();
        }

        return redirect()->route('seeSale',['idSale'=>$data['id_sale']]);
    }

    public function updateResolutionJuridical(Request $request){
        $data=$request->only(['id_juridical','resolution']);
        
        Validator::make($data,[
            'resolution'=>['required','string'],
            'id_juridical'=>['required','int'],
        ],[],['resolution'=>"solução do caso"])->validate();
        
        if($request->has(['id_juridical','resolution'])){
            $juridical=JuridicalContact::where('id',$data['id_juridical'])->first();
            $juridical->status=1;
            $juridical->resolution=$data['resolution'];
            $juridical->save();
        }

        return redirect()->route('allJuridicalContacts',['idJuridical'=>$data['id_juridical']]);
    }

    public function finalResolutionJuridical(Request $request){
        $data=$request->only(['id_juridical','decision']);
        $request->validate([
            'id_juridical'=>['required','int'],
            'decision'=>['required']
        ]);

        if($request->has(['id_juridical','decision'])){
            $juridical=JuridicalContact::where('id',$data['id_juridical'])->first();
            if($data['decision']==1){
                $juridical->status=3;
                $juridical->date_authorization_juridical=date('Y-m-d');
                $juridical->deadline=date('Y-m-d',strtotime('+2 month'));
                $juridical->save();
            }else{
                $sale=Sales::where('id',$juridical->id_sale)->first();
                $sale->type=2;
                $sale->save();

                $juridical->status=2;
                $juridical->save();
            }
        }

        return redirect()->route('allJuridicalContacts');
    }

    public function startJudicialProcess(Request $request){
        $data=$request->only(['id_juridical','process_number','document_juridical']);
        
        Validator::make($data,[
            'id_juridical'=>['required','int'],
            'process_number'=>['required','string','unique:juridical_contacts'],
            'document_juridical'=>['required','mimes:txt,DOC,pdf']
        ],[],
        [
            'process_number'=>"Numero do processo",
            'document_juridical'=>"Documento do processo"
        ])->validate();
        
        if($request->has(['id_juridical','process_number','document_juridical'])){
            $pathFile="";
            if(!empty($data['document_juridical'])){
                $file=$data['document_juridical'];
                if($file->isValid()){
                    $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    $pathContact="contactFiles/juridical/";
                    $file->storeAs($pathContact,$contactFile);
                    $pathFile=$pathContact.$contactFile;
                }
            }
            
            $juridical=JuridicalContact::where('id',$data['id_juridical'])->first();
            $juridical->document_juridical=$pathFile;
            $juridical->process_number=$data['process_number'];
            $juridical->status=4;
            $juridical->save();
        }

        return redirect()->route('allJuridicalContacts');
    }

    public function seeJuridical($idJuridical){
        $data=[];
        $juridical=JuridicalContact::where('id',$idJuridical)->first();
        $sale=Sales::join('interprises','sales.id_interprise','=','interprises.id')
        ->join('lots','sales.id_lot','lots.id')
        ->where('sales.id',$juridical->id_sale)
        ->first(['sales.*','interprises.name as interprise_name','lots.lot_number as lot_number'
           ,'lots.block as lot_block']);
        
        $user=User::where('id',$juridical->id_user)->first();   

        $data['user']=$user;
        $data['sale']=$sale;
        $data['juridical']=$juridical;
        
        $total_parcels_pad=$this->getSumValueParcels($juridical->id_sale);
        $input_value=floatVal(str_replace(['.',','],['','.'],$sale->input));
        $data['parcels_pad']=str_replace('.',',',$this->getPaidValue($sale->id));
        $data['total_parcels_pad']=$total_parcels_pad+$input_value;
        $data['number_parcels_pad']=count(Parcels::where('id_sale',$sale->id)->where('type',1)->where('status',1)->get());
        $data['number_parcels_to_pay']=count(Parcels::where('id_sale',$sale->id)->where('type',1)->where('status',"!=",1)->get());

        $data['typesUser']=[
            1=>'Administrador',
            3=>'Operação',
            5=>'Juridico'
        ];
        
        $juridicalUpdates=JuridicalUpdate::where('id_juridical',$idJuridical)->orderBy('id','DESC')->get();

        $data['juridicalUpdates']=[];
        foreach ($juridicalUpdates as $key => $juridical) {
            $user=User::where('id',$juridical->id_user)->first();
            if($juridical->id_user == ""){
                $user=User::where('id',$juridical->id_juridicalUser)->first();
            }
            $juridicalInfo[]=[
                'updateJuridicalInfo'=>$juridical,
                'user'=>$user,
                
            ];

            $data['juridicalUpdates']=$juridicalInfo;
        }
        return view('seeJuridical',$data);
    }

    public function addJuridicalUpdate(Request $request){
        $data=$request->only(['id_juridical','subject',
            'update_decription','document','decision']);
       
        $request->validate([
            'id_juridical'=>['required','int'],
            'update_decription'=>['required','string'],
            'subject'=>['required','max:50','string']
        ]);
        
        if($request->has('update_decription')){
            $pathFile="";
            if(!empty($data['document'])){
                $file=$data['document'];
                if($file->isValid()){
                    $contactFile=md5(rand(0,99999).rand(0,99999)).'.'.$file->getClientOriginalExtension();
                    $pathContact="contactFiles/juridical/";
                    $file->storeAs($pathContact,$contactFile);
                    $pathFile=$pathContact.$contactFile;
                }
            }

            $juridicalUpdate=new JuridicalUpdate();
            $juridicalUpdate->id_juridical=$data['id_juridical'];
            $juridicalUpdate->id_juridicalUser=Auth::user()->type==5?Auth::user()->id:null;
            $juridicalUpdate->id_user=Auth::user()->type==1 || Auth::user()->type==3?Auth::user()->id:null;
            $juridicalUpdate->subject=$data['subject'];
            $juridicalUpdate->update_decription=$data['update_decription'];
            $juridicalUpdate->date=date('Y-m-d');
            $juridicalUpdate->time=date('H:i:s');
            $juridicalUpdate->document=$pathFile;
            $juridicalUpdate->update=0;
            $juridicalUpdate->save();

            if(isset($data['decision']) && $data['decision']==1){
                $juridicalContact=JuridicalContact::where('id',$data['id_juridical'])->first();
                $juridicalContact->status=2;
                $juridicalContact->save();    

                $id_sale=$juridicalContact->id_sale;

                $sales=Sales::where('id',$id_sale)->first();
                $sales->type=2;
                $sales->save();

                
            }
        }

        return redirect()->route('seeJuridicalContact',['idJuridical'=>$data['id_juridical']]);
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

}

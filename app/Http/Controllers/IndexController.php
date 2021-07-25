<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Parcels;



class IndexController extends Controller
{   

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('auth.unique.user');

    }
    
    public function index(Request $request){
        $data['index']=DB::table('index')->get();
        
        $data['name']="";
        $data['date']="";
        $data['time']="";

        $dataIndexLike=$request->only(['name','time']);
        $dataIndexEquals = $request->only('date',);
        
        if($request->hasAny(['name','date','time'])){
            $query=DB::table('index');
            
            foreach ($dataIndexLike as $name => $value) {
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataIndexEquals as $name => $value) {
                if($value){ 
                    $query->where($name, '=', $value);
                }
            }
            
            $data['index']=$query->get();
            $data['name']=$dataIndexLike['name'];
            $data['date']=$dataIndexEquals['date'];
            $data['time']=$dataIndexLike['time'];
        }

        return view('index',$data);
    }

    public function add(Request $request){
        $request->validate([
            'indexName'=>['required','string']
        ]);
        $indexName=$request->input('indexName');
        if($request->has('indexName')){
            DB::table('index')->insert(['name'=>$indexName,'date'=>date('y-m-d'),'time'=>date('H:i:s')]);
        }

        return redirect()->route('index');
    }

    public function edit(Request $request){
        $request->validate([
            'indexName'=>['required','string']
        ]);
        $dataIndex=$request->only('indexName','idIndex');
        if($request->has('indexName')){
            DB::table('index')->where('id',$dataIndex['idIndex'])->update(['name'=>$dataIndex['indexName'],'date'=>date('y-m-d'),'time'=>date('H:i:s')]);
        }

        return redirect()->route('index');
    }

    public function delete($id){
        if(!empty($id)){
            DB::table('index_value')->where('idIndex',$id)->delete();
            DB::table('index')->where('id',$id)->delete();
        }

        return redirect()->route('index');
    }

    public function seeIndexValue($indexId,Request $request){
        $data['index']=DB::table('index')->where('id',$indexId)->first();
        $data['indexValues']=DB::table('index_value')->join('index','index.id','=','index_value.idIndex')
            ->where('idIndex',$indexId)->orderByDesc('month')->get(['index_value.*','index.id as idIndex','index.name as indexName']);
        $data['index_value']="";
        $data['month']="";
        
        $dataIndexLike=$request->only(['index_value']);
        $dataIndexEquals = $request->only('month');
        
        if($request->hasAny(['index_value','month'])){
            $query=DB::table('index_value')->join('index','index.id','=','index_value.idIndex');
            
            foreach ($dataIndexLike as $name => $value) {
                if($name=='index_value'){
                    $name='value';
                }
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataIndexEquals as $name => $value) {
                if($value){ 
                    if($name=='month'){
                        $value=$dataIndexEquals['month'].'-01';
                    }
                    $query->where($name, '=', $value);
                }
            }
            
            $data['indexValues']=$query->where('idIndex',$indexId)->orderByDesc('date')
                ->get(['index_value.*','index.id as idIndex','index.name as indexName']);
            $data['index_value']=$dataIndexLike['index_value'];
            $data['month']=$dataIndexEquals['month'];
        }

        return view('seeIndex',$data);
    }


    public function seeAllIndexValue(Request $request){
        $data['indexValues']=DB::table('index_value')->join('index','index.id','=','index_value.idIndex')
                ->orderBy('date','desc')->get(['index_value.*','index.id as idIndex','index.name as indexName']);
        $data['idIndex']="";
        $data['index_value']="";
        $data['month']="";
        $data['allIndex']=DB::table('index')->get();
        
        $dataIndexLike=$request->only(['index_value']);
        $dataIndexEquals = $request->only('month','idIndex');
        
        if($request->hasAny(['index_value','month'])){
            $query=DB::table('index_value')->join('index','index.id','=','index_value.idIndex');
            
            foreach ($dataIndexLike as $name => $value) {
                if($name=='index_value'){
                    $name='value';
                }
                if($value){
                    $query->where($name, 'LIKE', '%' . $value . '%');
                }
            }
            
            foreach ($dataIndexEquals as $name => $value) {
                if($value){ 
                    if($name=='month'){
                        $value=$dataIndexEquals['month'].'-01';
                    }
                    $query->where($name, '=', $value);
                }
            }
            
            $data['indexValues']=$query->get(['index_value.*','index.id as idIndex','index.name as indexName']);
            $data['index_value']=$dataIndexLike['index_value'];
            $data['month']=$dataIndexEquals['month'];
            $data['idIndex']=$dataIndexEquals['idIndex'];;
        }

        return view('seeAllIndex',$data);
    }

    public function addIndexValue(Request $request){
        $date=$request->input('month').'-01';
        
        $request->merge([
            'month' => $date,
        ]);

        $request->validate([
            'idIndex'=>['required','int'],
            'index_value'=>['required'],
            'month'=>['required','date'],
        ]);

        $dataIndex=$request->only(['idIndex','index_value','month','notification']);
        
        if($this->verifyValuesExist($date,$dataIndex['idIndex'])){
            return redirect()->route('seeIndexValue',$dataIndex['idIndex'])
                ->withErrors("O valor para o mes digitado ja foi inserido");
        }
        
        if($request->has(['idIndex','index_value','month'])){
            DB::table('index_value')->insert(['idIndex'=>$dataIndex['idIndex'],'value'=>$dataIndex['index_value'],
            'month'=>$dataIndex['month']]);

            $this->verifyNotificationsIndex($dataIndex['month']);
        }
          
        $allIndexVal=$request->input('allIndexVal');
        if($allIndexVal){
            return redirect()->route('seeAllIndexValue');
        }else{
            return redirect()->route('seeIndexValue',$dataIndex['idIndex']);
        }  
    }

    public function addIndexValueNotification(Request $request){
        
        $request->validate([
            'idSale'=>['required','int'],
            'idIndex'=>['required','int'],
            'index_value.*'=>['nullable'],
            'month.*'=>['required','date'],
        ]);

        $dataIndex=$request->only(['idSale','idIndex','index_value','month','notification']);
        $dataIndexValue=[];

        foreach ($dataIndex['month'] as $key => $month) {
            $indexValueData['month']=$month;
            $indexValueData['index_value']=$dataIndex['index_value'][$key];
            $dataIndexValue[]=$indexValueData;
        }
       
        foreach ($dataIndexValue as $key => $indexValue) {
            if($indexValue['month'] !='' && $indexValue['index_value'] !=''){
                DB::table('index_value')->insert(['idIndex'=>$dataIndex['idIndex'],'value'=>$indexValue['index_value'],
            'month'=>$indexValue['month']]);
            $this->verifyNotificationsIndex($indexValue['month']);
            }
        }

        if(!empty($request->input('refinancing'))){
            return redirect()->route('addView',['idSale'=>$request->input('refinancing'), 'type'=>4]);
        }

        return redirect()->route('home');
        
    }

    private function verifyNotificationsIndex($monthRegister){
        $notificationsIndex=DB::table('notification_index_value')->get();
        foreach ($notificationsIndex as $key => $notification) {
            $month_index_empty=explode(',',$notification->month_index_empty);
            $index=array_search($monthRegister,$month_index_empty,true);
            $new_month_index_empty=$month_index_empty;
            
            if($index>=0 || $index!=""){
                unset($new_month_index_empty[$index]);
                $new_month_index_empty_implode=implode(',',$new_month_index_empty);
                DB::table('notification_index_value')->where('id',$notification->id)
                    ->update(['month_index_empty'=>$new_month_index_empty_implode]);
                
                if(count($new_month_index_empty)==0){
                    DB::table('notification_index_value')->where('id',$notification->id)
                    ->update(['done'=>1]);
                    $this->changeStatusParcel($notification->id_sale);
                }
            }
        }
    }

    private function changeStatusParcel($idSale){
        $parcels=Parcels::where('status',4)->where('id_sale',$idSale)->get();
        
        foreach ($parcels as $key => $parcelVal) {
            $parcelVal=Parcels::where('id_sale',$idSale)->where('status',4)->first();
            $parcelVal->status=2;
            $parcelVal->save();
        }
    }
   
    public function editIndexValue(Request $request){
        $date=$request->input('index_month').'-01';
        
        $request->merge([
            'index_month' => $date,
        ]);

        $request->validate([
            'idIndexValue'=>['required','int'],
            'idIndex'=>['required','int'],
            'index_value'=>['required'],
            'index_month'=>['required','date'],
        ]);

        $allIndexVal=$request->input('allIndexVal');
        
        $dataIndex=$request->only(['idIndexValue','idIndex','index_value','index_month']);
        $indexValue=DB::table('index_value')->where('id',$dataIndex['idIndexValue'])->first();
      
        if($date != $indexValue->month){
            if($this->verifyValuesExist($date,$dataIndex['idIndex'])){
                if($allIndexVal){
                    return redirect()->route('seeAllIndexValue',$dataIndex['idIndex'])
                        ->withErrors("O valor para o mes digitado ja foi inserido");
                }else{
                    return redirect()->route('seeIndexValue',$dataIndex['idIndex'])
                        ->withErrors("O valor para o mes digitado ja foi inserido");;
                }  
            }
        }
        
        
        if($request->has(['idIndexValue','idIndex','index_value','index_month'])){
            DB::table('index_value')->where('id',$dataIndex['idIndexValue'])->update(['idIndex'=>$dataIndex['idIndex'],'value'=>$dataIndex['index_value'],
            'month'=>$dataIndex['index_month'].'-01']);
        }
        
        if($allIndexVal){
            return redirect()->route('seeAllIndexValue',$dataIndex['idIndex']);
        }else{
            return redirect()->route('seeIndexValue',$dataIndex['idIndex']);
        }  
    }

    public function deleteIndexValue($idIndexValue,$idIndex,$allIndexValue=false){
        if(!empty($idIndexValue)){
            DB::table('index_value')->where('id',$idIndexValue)->delete();
        }

        if($allIndexValue==false){
            return redirect()->route('seeIndexValue',['idIndexValue'=>$idIndexValue,'idIndex'=>$idIndex]);
        }else{
            return redirect()->route('seeAllIndexValue');
        }
    }

    private function verifyValuesExist($month,$idIndex){
        $index_value_register=DB::table('index_value')->where('month',$month)->where('idIndex',$idIndex)->get();
        if(count($index_value_register)>0){
            return true;
        }else{
            return false;   
        }
    }
}

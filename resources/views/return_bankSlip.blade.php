@extends('adminlte::page')
@extends('layouts/modal')
@extends('layouts/searchSale')

<form action="{{route('addReturnBankSlipFile')}}" method="post" 
    id="formReturnBankSlip" enctype="multipart/form-data" style="display: none;">
    @csrf
    <div class="card" >
       <div class="card-body">
           <div class="card-body">
                <div class="uploadArea">
                    <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                    <div class="uploadAreaDrop">
                        <div class="uploadAreaDrop__img">
                            <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                        </div>
                        <div class="uploadAreaDrop__descriptionFile"></div>
                    </div>
                    <input name="returnFile" multiple="multiple" type="file" class="uploadInput"/>
                </div>
            </div>
            <input type="hidden" name="id_bank" id="id_bank">
            <div class="card-footer">
                <center><div class="btn-group w-75" role="group">
                    <button type="button" class="btn btn-success btnTypeBankSlip" value="1" id="btnSicredi">Sicredi</button>
                    <button type="button" class="btn btn-primary btnTypeBankSlip" value="2" id="btnCaixa">Caixa</button>
                </div></center><br><br>
                <center><input type="submit" class="btn btn-success btn-lg w-25" 
                    id="btnReturnBankSlip" disabled value="Salvar"></center>
            </div>
        </div> 
    </div>
</form>

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            <div title="adicionar arquivo retorno" class="btnActions btnActions--middle" data-toggle="modal" 
            data-target="#modalAcoes" data-toggle="tooltip" id="btnAddReturnBankSlip">+</div>            
            <div class="info__title info__title--without-margin-top">Retornos</div>
        </div>

        <div class="card-header">
            <form class="row" method="get">
                <input type="hidden" name="order">
                <div class="col-6">
                    <div class="form-group">
                        <h5>Filtrar por:</h5>
                        <div class="form-group d-flex mt-4">
                            <div class="d-flex mr-3">
                                <input class="mr-1" style="margin-top: 6px" type="radio" 
                                    {{$order=="1"?'checked':''}} name="order" value="1">
                                <label class='font-weight-bold'>Novo ao antigo</label> 
                            </div>

                            <div class="d-flex">
                                <input class="mr-1" style="margin-top: 6px" type="radio" 
                                {{$order=="2"?'checked':''}} name="order" value="2">
                                <label class='font-weight-bold'>Antigo ao novo</label> 
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Periodo</label>
                        <div class="form-group d-flex">
                            <input class="form-control" id="startDate" type="date" 
                                name="startDate" value="{{$startDate!=""?$startDate:''}}">
                            <input class="form-control" id="finalDate" type="date" 
                                name="finalDate" value="{{$finalDate!=""?$finalDate:''}}">
                            <input class="btn btn-success" type="submit" value="Filtrar">
                        </div>
                    </div>
                </div>
                <div class="col-6"></div>
            </form>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Conta</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Gerado</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Arquivo de retorno</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ver mais</th>

                    </tr>    
                </thead>
                <tbody>
                   @foreach ($returnBankSlip as $bankSlip)
                        <tr>
                            <td>{{$bankSlip['financialAccount']->id==1?"Sicredi":"Caixa"}}</td>
                            <td>{{date('d/m/Y',strtotime($bankSlip['bankSlip']->date))}}</td>
                            <td>
                                <a href="{{asset('storage/'.$bankSlip['bankSlip']->path_file_return)}}" 
                                    download="returnfile.crm">Download</a>
                            </td>

                            <td>
                                <center><a href="{{route('returnBankSlipInfo',['id_bankSlipReturn'=>$bankSlip['bankSlip']->id])}}" class="btnActions" title="ver mais">...</a></center>
                            </td>
                        </tr> 
                   @endforeach
                  
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('js/returnBankSlip.min.js')}}"></script>
    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                text: '{{$errors->first()}}',
                customClass: 'mySweetalert',
            })
        </script>
    @endif 

@endsection
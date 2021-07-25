@extends('adminlte::page')
@extends('layouts/modal')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/home.min.css')}}">
@endsection

<form action="{{route('addIndexValueNotification')}}" method="post" class="formIndexEmpty" style="display: none;">
    @csrf
    <input type="hidden" name="idIndex" value="">
    <input type="hidden" name="idSale" value="">

    <table class="table table-bordered table-hover dataTable dtr-inline" id="tableIndexEmpty" role="grid">
        <thead class="table">
            <tr>
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor do indice</th>
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Mes do indice</th>
            </tr>
        </thead>
        <tbody>
            <tr class="tableIndexEmpty_line" style="display: none;">
                <td>
                    <input type="number" class="form-control" step="any" name="index_value[]">
                </td>
                <td>
                    <input type="text" class="form-control" step="any" name="month[]" readonly>
                </td>
            </tr>
        </tbody>   
    </table>   
</form>

@section('content')
    @if (count($allAlertsPayments)>0)
    <div class="row">
        <div class="col-7" style="z-index:100000;">
            <div class="card card-warning collapsed-card">
                <div class="card-header">
                <h3 class="card-title">Alertas de vendas programadas (Hoje)</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: none;">
                    <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                        <thead class="table table-dark">
                        <tr role="row">
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Pra Pagar</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ações</th>
                        </tr>    
                        </thead>
                    @foreach ($allAlertsPayments as $alertsPayments)
                        <tr class="{{$alertsPayments->status==2?"table-warning":"table-success"}}
                            {{$alertsPayments->status==3?"table-danger":""}}">
                            <td>{{date('d/m/Y',strtotime($alertsPayments->date))}}</td>
                            <td>{{$alertsPayments->value}}</td>
                            <td>{{$alertsPayments->description}}</td>
                            <td class="buttons_area">
                                <a href="{{route('seeProgramedPayment',['idProgramedPayment'=>$alertsPayments->id])}}" 
                                    class="btnActions" title="ver mais">
                                    ...
                                </a>
                                
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-6">
            
        </div>
    </div><br>    
    @endif
    

    @if(Auth::user()->type==1 || Auth::user()->type==3)
        @if (count($notifications_almostFinish)>0)
        <div class="row">
            <div class="col-7" style="z-index:100000;">
                <div class="card card-success collapsed-card">
                    <div class="card-header">
                    <h3 class="card-title">Vendas com parcelas todas pagas</h3>
    
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: none;">
                        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                            <thead class="table">
                            <tr role="row">
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Contrato</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Inicio Contrato</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                            </tr>    
                            </thead>
                        @foreach ($notifications_almostFinish as $sale)
                            <tr>
                                <td>
                                    <a style="color: blue;" href="{{route('seeSale',['idSale'=>$sale->id])}}">
                                        {{$sale->contract_number}}
                                    </a>
                                </td>
                                <td>{{date('d/m/Y',strtotime($sale->begin_contract_date))}}</td>
                            <td>Todas as {{$sale->parcels}} parcelas desse contrato foram pagas.</td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-6">
                
            </div>
        </div><br>    
        @endif
    @endif
    
    
    @if(count($contact)>0)
    <div class="row">
        <div class="col-7" style="z-index:100000;">
            <div class="card card-warning collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Contatos não resolvidos</h3>
  
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                            <thead class="table">
                            <tr role="row">
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Contrato</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Pessoa</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Assunto</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Prazo</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Categoria</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Ações</th>
                            </tr>    
                            </thead>
                        @foreach ($contact as $contactItem)
                            <tr>
                                <td>
                                    <a style="color: blue;" href="{{route('seeSale',['idSale'=>$contactItem->id_sale])}}">
                                        {{$contactItem->contract_number}}
                                    </a>
                                </td>
                                <td>{{$contactItem->contact_client_name}}</td>
                                <td style="max-width: 150px;">{{$contactItem->subject_matter}}</td>
                                <td>{{date('d/m/Y',strtotime($contactItem->deadline))}}</td>
                                <td>{{$typeContact[$contactItem->type]}}</td>
                                <td class="buttons_area">
                                    @if($contactItem->status==2)
                                        <a href="{{route('seeContact',['idContact'=>$contactItem->id])}}"  class="btnActions" title="ver mais">...</a>
                                    @else
                                        <a href="{{route('doneContact',['idContact'=>$contactItem->id])}}"  class="btnActions" title="ver mais">...</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                </div>
              </div>
        </div>
        <div class="col-6">
            
        </div>
    </div><br>
   @endif

   @if(Auth::user()->type==1 || Auth::user()->type==3)
    @if(count($notifications_index)>0)
        <div class="row">
            <div class="col-7" style="z-index:100000;">
                <div class="card card-danger collapsed-card">
                    <div class="card-header">
                    <h3 class="card-title">Há parcelas para serem reajustadas</h3>
    
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: none;">
                        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                            <thead class="table">
                            <tr role="row">
                                <th>Contrato</th>
                                <th>Parcelas a serem reajustadas</th>
                                <th>Ações</th>
                            </tr>    
                            </thead>
                        @foreach ($notifications_index as $notificationItem)
                            <tr>
                                <td>
                                    <a style="color: blue;" href="{{route('seeSale',['idSale'=>$notificationItem->id_sale])}}">
                                        {{$notificationItem->contractNumber}}
                                    </a>
                                </td>
                                <td>{{$notificationItem->parcels_readjust}}</td>
                                <td>
                                    <center>
                                        <a idSale="{{$notificationItem->id_sale}}" idIndex="{{$notificationItem->index}}" month_index_empty="{{$notificationItem->month_index_empty}}"class="btnActions btnIndexEmpty"  
                                            data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip" 
                                            title="ver mais">...</a>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-6">
                
            </div>
        </div><br>
        @endif
    @endif
  

    <div class="logo"></div>
@endsection

@section('js')
    @if($errors->any())
          <script>
              Swal.fire({
                  icon: 'error',
                  text: '{{$errors->first()}}',
                  customClass: 'mySweetalert',
              })
          </script>
      @endif
    <script src="{{asset('js/home.min.js')}}"></script>
@endsection
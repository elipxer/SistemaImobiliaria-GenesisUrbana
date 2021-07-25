@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4 buttons_area--left">
                    @if (Auth::user()->type==1)
                        <a href="{{route('addProgramedPaymentView')}}" title="adicionar programação de pagamento" class="btnActions btnActions--middle">+</a>
                    @endif
                    <div class="info__title info__title--without-margin-top">Programação de pagamentos</div>
                </div>
                <div class="col-8">
                    <form method="GET" class="w-100 d-flex justify-content-end">
                        <div class="form-group" >
                            <label>Filtrar por datas</label><br>
                            <div class="form-group" style="display:flex;">
                                <input class="form-control" style="width: 40%" required value="{{$startDate}}" type="date" name="startDate" placeholder="Data Inicial">
                                <input class="form-control" style="width: 40%" required value="{{$finalDate}}" type="date" name="finalDate" placeholder="Data Fim">
                                <input class="btn btn-success" type="submit" value="Filtrar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Conta Interna</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Fornecedor</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor</th>
                    <th style="max-width: 50px;" class="sorting" tabindex="0"  rowspan="1" colspan="1">Num</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Pago</th>
                    <th style="max-width: 80px;" class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Pagar</th>
                    <th style="max-width: 100px;" class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor Pago</th>
                   
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ações</th>
                
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <input type="hidden" name="startDate" required value="{{$startDate}}">
                                <input type="hidden" name="finalDate" required value="{{$finalDate}}">
                                <td> 
                                    <input class="form-control" type="text" name="internalAccountName"
                                    value="{{$internalAccountName}}" placeholder="Nome Conta Interna">
                                </td>

                                <td> 
                                    <input class="form-control" type="text" name="provider"
                                    value="{{$provider}}" placeholder="Fornecedor">
                                </td>

                                <td></td>
                                <td style="max-width: 120px;">
                                    <input class="form-control" type="number" name="num"
                                    value="{{$numParcel}}">
                                </td>
                                <td style="max-width: 190px;"> 
                                    <input class="form-control" type="date" name="date" value="{{$date}}">
                                </td>
                                <td style="max-width: 190px;"> 
                                    <input class="form-control" type="date" name="payment_date" value="{{$payment_date}}">
                                </td>
                               
                                <td></td>
                                <td></td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                        @foreach ($allProgramedPayment as $programedPayment)
                            <tr class="{{$programedPayment->status==1?"table-success":""}}
                                {{$programedPayment->status==3?"table-danger":""}}">
                                <td>{{$programedPayment->internalAccount}}</td>
                                <td>{{$programedPayment->nameProvider!=""?$programedPayment->nameProvider:$programedPayment->companyProvider}}</td>
                                <td>{{$programedPayment->value}}</td>
                                <td style="max-width: 50px;">{{$programedPayment->num."/".$programedPayment->totalNumberParcels}}</td>
                                <td>{{date('d/m/Y',strtotime($programedPayment->date))}}</td>
                                <td style="max-width: 190px;">{{$programedPayment->payment_date!=""?date('d/m/Y',strtotime($programedPayment->date)):"Não Pago"}}</td>
                                <td>{{$programedPayment->value_payment!=""?$programedPayment->value_payment:"Não pago"}}</td>

                                <td class="buttons_area">
                                    <a href="{{route('seeProgramedPayment',['idProgramedPayment'=>$programedPayment->id])}}"  class="btnActions" title="ver mais">...</a>

                                    @if(Auth::user()->type==1)
                                        <a href="{{route('deleteProgramedPayment',['idProgramedPayment'=>$programedPayment->id])}}" 
                                            msg="Tem certeza que deseja excluir essa programação de pagamento?"  
                                            class="btnActions btnDelete" title="suspender venda">
                                            x
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
            </table>
        </div>
    </div>
@endsection
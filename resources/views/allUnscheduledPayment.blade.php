@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4 buttons_area--left">
                    @if (Auth::user()->type==1)
                        <a href="{{route('addUnscheduledPaymentView')}}" title="adicionar pagamento não programado" class="btnActions btnActions--middle">+</a>
                    @endif
                    <div class="info__title info__title--without-margin-top">Pagamentos não programado</div>
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
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Fornecedor</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Vencimento</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Banco</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Comprovante</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ações</th>
                </tr>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <td> 
                                    <input class="form-control" type="text" name="internalAccountName"
                                    value="{{$internalAccountName}}" placeholder="Nome Conta Interna">
                                </td>

                                <td> 
                                    <input class="form-control" type="text" name="description"
                                    value="{{$description}}" placeholder="Descrição">
                                </td>

                                <td> 
                                    <input class="form-control" type="text" name="provider"
                                    value="{{$provider}}" placeholder="Fornecedor">
                                </td>

                                <td> 
                                    
                                </td>

                                <td> 
                                    <input class="form-control" type="date" name="deadline"
                                    value="{{$deadline}}" placeholder="Vencimento">
                                </td>

                                <td> 
                                    <input class="form-control" type="text" name="bankName"
                                    value="{{$bankName}}" placeholder="Nome Banco">
                                </td>
                                <td></td>
                                <td></td>
                                
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($allunscheduled_payments as $unscheduledPayment)
                            <tr>
                               <td>{{$unscheduledPayment->internalAccount}}</td>
                               <td>{{$unscheduledPayment->description}}</td>
                               <td>{{$unscheduledPayment->nameProvider!=""?$unscheduledPayment->nameProvider:$unscheduledPayment->companyProvider}}</td>
                               <td>{{$unscheduledPayment->value}}</td>
                               <td>{{date('d/m/Y',strtotime($unscheduledPayment->deadline))}}</td>
                               <td>{{$unscheduledPayment->bankName}}</td>
                               <td>
                                   @if ($unscheduledPayment->proof_payment != "")
                                        <a href="{{asset('storage/'.$unscheduledPayment->proof_payment)}}" download>Download</a>
                                    @endif
                                </td>
                                <td class="buttons_area">
                                    <a href="{{route('seeUnscheduledPayment',['idUnscheduledPayment'=>$unscheduledPayment->id])}}"  class="btnActions" title="ver mais">...</a>
                                    
                                    @if (Auth::user()->type==1)
                                        <a href="{{route('deleteUnscheduledPayment',['idUnscheduledPayment'=>$unscheduledPayment->id])}}" 
                                            class="btnActions btnActions--transparent btnDelete btnDelete"
                                            msg="Tem certeza que deseja excluir esse pagamento não programado?">
                                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
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
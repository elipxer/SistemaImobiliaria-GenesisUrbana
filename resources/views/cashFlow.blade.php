@extends('adminlte::page')
@extends('layouts/searchSale')


@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4 buttons_area--left">
                   <div class="info__title">Fluxo de Caixa</div>
                </div>
                <div class="col-8">
                    <form method="GET" class="w-100 d-flex justify-content-end">
                        <div class="form-group" >
                            <label>Periodo</label><br>
                            <div class="form-group" style="display:flex;">
                                <input class="form-control" style="width: 40%" required value="{{$startDate}}" type="date" name="startDate">
                                <input class="form-control" style="width: 40%" required value="{{$finalDate}}" type="date" name="finalDate">
                                <input class="btn btn-success" type="submit" value="Filtrar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @foreach ($sales as $sale)
            <div class="card">
                <div class="card-header" style="background-color: rgb(169,208,142);">
                    <div class="info__title" style="color: black;">Recebimentos</div>
                </div>

                <div class="card">
                    <div class="card-header" style="background-color: rgb(169,208,142);">
                        <div class="row">
                            <div class="col-6">
                                <div class="info__title" style="color: black;">{{$sale['client']->name!=null?$sale['client']->name:$sale['client']->company_name}}</div>
                            </div>

                            <div class="col-6 text-right">
                                <div class="info__title" style="color: black;"> {{"Saldo: R$".$sale['total']}}</div>
                            </div>   
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                                <thead class="table" style="background-color: rgb(191, 191, 191);">
                                    <tr role="row">
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Numero Contrato</th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Num Parcela</th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Valor</th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Valor Pago</th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Data Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale['allParcels'] as $parcel)
                                        <tr>
                                            <td><a href="{{route('seeSale',['idSale'=>$sale['sale']->id])}}">{{$sale['sale']->contract_number}}</a></td>
                                            <td>{{$parcel->num."/".$sale['sale']->parcels}}</td>
                                            <td>{{$parcel->value}}</td>
                                            <td>{{$parcel->pad_value}}</td>
                                            <td>{{date('d/m/Y',strtotime($parcel->pad_date))}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><br>
            </div>
        @endforeach

        <div class="card">
            <div class="card-header" style="background-color: rgb(169,208,142);">
                <div class="info__title" style="color: black;">Pagamentos</div>
            </div>
            
            @foreach ($internalAccounts as $internal)
            <div class="card">
                <div class="card-header" style="background-color: rgb(169,208,142);">
                    <div class="row">
                        <div class="col-6">
                            <div class="info__title" style="color: black;">{{$internal['internal']->name}}</div>
                        </div>

                        <div class="col-6 text-right">
                            <div class="info__title" style="color: black;"> {{"R$".$internal['total']}}</div>
                        </div>   
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                            <thead class="table" style="background-color: rgb(191, 191, 191);">
                                <tr role="row">
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Conta Interna</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Fornecedor</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Vencimento</th>

                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor Total</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor Parcela</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Num Parcela</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Valor Pago</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Data Pago</th>
                                
                            </thead>
                            <tbody>
                                @foreach ($internal['programedPayments'] as $programedPayment)
                                    <tr>
                                        <td>{{$programedPayment->internalAccount}}</td>
                                        <td>{{$programedPayment->nameProvider!=""?$programedPayment->nameProvider:$programedPayment->companyProvider}}</td>
                                        <td>{{date('d/m/Y',strtotime($programedPayment->date))}}</td>

                                        <td>{{$programedPayment->totalValue}}</td>
                                        <td>{{$programedPayment->value}}</td>
                                        <td>{{$programedPayment->num."/".$programedPayment->totalNumberParcels}}</td>
                                        <td>{{$programedPayment->value_payment}}</td>
                                        <td>{{date('d/m/Y',strtotime($programedPayment->payment_date))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><br>
            @endforeach
        </div>

        @if($filterDates)
        <div class="card">
            <div class="card-header">
                <div class="info__title">Informações Gerais</div>

            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-4">
                            <div class="input-info__group">
                                <div class="input-info__title">Total Parcelas Pagas</div>
                                <div class="input__info">
                                    {{$valuePadParcels!="0"?'$'.number_format($valuePadParcels,2):'0,00'}}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-4">
                            <div class="input-info__group">
                                <div class="input-info__title">Total Pagamento Programado</div>
                                <div class="input__info">
                                    {{$padProgramedPaymentsValue!="0"?'$'.number_format($padProgramedPaymentsValue,2):"0,00"}}
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-info__group">
                                <div class="input-info__title">Saldo</div>
                                <div class="input__info">
                                    {{$balance!="0"?'$'.number_format($balance,2):'0,00'}}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <form action="{{route('cashFlowReport')}}" method="POST" target="_blank" >
                    @csrf
                    <input class="form-control" value="{{$startDate}}" type="hidden" name="startDate">
                    <input class="form-control" value="{{$finalDate}}" type="hidden" name="finalDate">
                    
                    <center><input type="submit" class="btn btn-success btn-lg w-25" value="Gerar Relatorio"></center>
                </form>
            </div>
        </div>
        @endif
    </div>
@endsection            
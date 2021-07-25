@extends('adminlte::page')
@extends('layouts/searchSale')


@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4 buttons_area--left flex-column align-items-start">
                   <div class="info__title">Saldos Bancários</div>
                   <div class="info__title">Total Soma dos Saldos: {{number_format($balanceTotal,2)}}</div>
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

        @foreach ($bankRegisters as $key=>$register)
            <div class="card">
                <div class="card-header" style="background-color: rgb(169,208,142);">
                    <div class="row">
                        <div class="col-6">
                            <div class="info__title" style="color:black !important;">{{$register['bank']->name}}</div>
                        </div>

                        <div class="col-6" style="text-align: right;">
                            <div class="info__title" style="color:black !important;">{{"Saldo: ".number_format($register['balance'],2)}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                            <thead class="table" style="background-color: rgb(191, 191, 191);">
                                <tr role="row">
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" style="colort:black !important;">Data</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" style="colort:black !important;">Descrição</th>
                                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" style="colort:black !important;">Valor</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($register['registers'] as $registerItem)
                                    <tr>
                                        <td>{{date('d/m/Y',strtotime($registerItem['date']))}}</td>
                                        <td>{{$registerItem['description']}}</td>
                                        <td>{{$registerItem['type']==3 || $registerItem['type']==1?'-'.$registerItem['value']:$registerItem['value']}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach

        @if($filterDates)
            <div class="card-footer">
                <form action="{{route('bankBalanceReport')}}" method="POST" target="_blank" >
                    @csrf
                    <input class="form-control" value="{{$startDate}}" type="hidden" name="startDate">
                    <input class="form-control" value="{{$finalDate}}" type="hidden" name="finalDate">
                    
                    <center><input type="submit" class="btn btn-success btn-lg w-25" value="Gerar Relatorio"></center>
                </form>
            </div>
        @endif
    </div>
@endsection            
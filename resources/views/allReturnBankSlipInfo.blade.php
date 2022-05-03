@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            <div class="info__title info__title--without-margin-top">Retornos</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nosso Numero</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Cod Ocorrência</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição Ocorrência</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Erro Ocorrência</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Ocorrência</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Vencimento</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Crédito</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Tarifa</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Desconto</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Recebido</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Mora</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Multa</th>
                    </tr>    
                </thead>
                <tbody>
                   @foreach ($returnBankSlipInfo as $bankSlip)
                        <tr>
                            <td>{{$bankSlip->our_number}}</td>
                            <td>{{$bankSlip->ocorrency}}</td>
                            <td>{{$bankSlip->ocorrencyDescription}}</td>
                            <td>{{$bankSlip->error!=""?$bankSlip->error:'Nenhum Erro'}}</td>
                            <td>{{$bankSlip->dateOccorency!=""?date('d/m/Y',strtotime($bankSlip->dateOccorency)):""}}</td>
                            <td>{{$bankSlip->deadLine!=""?date('d/m/Y',strtotime($bankSlip->deadLine)):""}}</td>
                            <td>{{$bankSlip->creditDate!=""?date('d/m/Y',strtotime($bankSlip->creditDate)):""}}</td>
                            <td>{{!empty($bankSlip->value)?$bankSlip->value:'0,00'}}</td>
                            <td>{{!empty($bankSlip->value_rate)?$bankSlip->value_rate:'0,00'}}</td>
                            <td>{{!empty($bankSlip->value_descont)?$bankSlip->value_descont:'0,00'}}</td>
                            <td>{{!empty($bankSlip->amount_received)?$bankSlip->amount_received:'0,00'}}</td>
                            <td>{{!empty($bankSlip->value_mora)?$bankSlip->value_mora:'0,00'}}</td>
                            <td>{{!empty($bankSlip->value_fine)?$bankSlip->value_fine:'0,00'}}</td>
                        </tr> 
                   @endforeach
                  
                </tbody>
            </table>
            </div>
        </div>
    </div>
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

@endsection
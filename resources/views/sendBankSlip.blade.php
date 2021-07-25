@extends('adminlte::page')
@extends('layouts/searchSale')
@extends('layouts/modal')


@section('css')
    <link rel="stylesheet" href="{{asset('css/bankSlip.min.css')}}">
@endsection

<table id="tableBankSlip" style="display: none;" class="table table-bordered table-hover dataTable dtr-inline" role="grid">
    <thead class="table table-dark">
        <td>Conta</td>
        <td>Contrato</td>
        <td>Parcela</td>
        <td>Boleto</td>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            <a href="{{route('addBankSlip')}}" title="adicionar boleto" class="btnActions btnActions--middle">+</a> 
             
            <div class="info__title info__title--without-margin-top">Remessas</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="info__title">Gerar Remessas</div>
            </div>
            <div class="card-body">
                <div class="container_pending_bankSlip">
                    <a href="{{route('pending_bankSlip',['id_financial'=>$financial_account_sicredi->id])}}" class="pending_bankSlip">
                        <div class="pending_bankSlip__item justify-content-center align-items-center">
                            <div class="pending_bankSlip__icon d-flex justify-content-center align-items-center">{{$financial_account_sicredi->id_bank}}</div>
                        </div>
                        <div class="pending_bankSlip__item  justify-content-center align-items-center">
                            <div class="pending_bankSlip__info">
                                <span class="pending_bankSlip__title">{{$financial_account_sicredi->name}}</span>
                                <span class="pending_bankSlip__title font-weight-bold flex-wrap">{{count($bankSlipPendingSicredi)}} boletos pendentes</span>
                            </div>
                        </div>
                    </a>

                    <a href="{{route('pending_bankSlip',['id_financial'=>$financial_account_caixa->id])}}" class="pending_bankSlip">
                        <div class="pending_bankSlip__item justify-content-center align-items-center">
                            <div class="pending_bankSlip__icon d-flex justify-content-center align-items-center">{{$financial_account_caixa->id_bank}}</div>
                        </div>
                        <div class="pending_bankSlip__item  justify-content-center align-items-center">
                            <div class="pending_bankSlip__info">
                                <span class="pending_bankSlip__title">{{$financial_account_caixa->name}}</span>
                                <span class="pending_bankSlip__title font-weight-bold flex-wrap">{{count($bankSlipPendingCaixa)}} boletos pendentes</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="info__title">Remessas Geradas</div>
            </div>
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Conta</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Último Numero</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Arquivo Remessa</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ver Boletos</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Geração</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ações</th>
                    </tr>    
                </thead>
                <tbody>
                   @foreach ($bankSlipSend as $bankSlipItem)
                       <tr class="{{$bankSlipItem->add_now==1?'table-success':''}}">
                           <td>
                               {{$bankSlipItem->id_financial_accounts==1?"Sicredi":"Caixa"}}
                           </td>
                           <td></td>
                           <td>
                                <a href="{{asset('storage/bankSlip/sendFile/'.$bankSlipItem->send_file_name)}}" 
                                    download="{{$bankSlipItem->send_file_name}}">Download</a>
                            </td>
                            <td>
                               <a style="color: blue; cursor: pointer;" class="btnSeeBankSlip"
                               data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip"
                               idBankSlips="{{$bankSlipItem->ids_bankSlip}}">Ver Boletos</a>
                            </td>
                            <td>{{date('d/m/Y',strtotime($bankSlipItem->date))}}</td>
                            <td></td>
                       </tr>
                   @endforeach
                  
                </tbody>
            </table>
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
    <script>
        const urlBankSlip='{{route('getBankSlip')}}';
        const urlDownloadBankSlip="{{asset('storage/bankSlip/')}}";
        const urlSale='{{asset('')."seeSale/"}}';
    </script>
    <script src="{{asset('js/sendBankSlip.min.js')}}"></script>
@endsection

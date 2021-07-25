@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6">
                <div class="info__title">Informações pagamento não programado</div> 
            </div>
            
            <div class="col-6" style="text-align: right">
                <a href="{{route('allUnscheduledPayment')}}" class="btn btn-success backButton">Voltar</a>  
            </div>
        </div>
    </div> 
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="input-info__group">
                    <div class="input-info__title">Nome da conta interna</div>
                    <div class="input__info">
                        {{$unscheduledPayment->internalAccount}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Descrição</div>
                    <div class="input__info">
                        {{$unscheduledPayment->description}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Fornecedor</div>
                    <div class="input__info">
                        {{$unscheduledPayment->nameProvider!=""?$unscheduledPayment->nameProvider:$unscheduledPayment->companyProvider}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Valor Pagamento</div>
                    <div class="input__info">
                        {{$unscheduledPayment->payment_value}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Função</div>
                    <div class="input__info">
                        {{$unscheduledPayment->payment_method}}
                    </div>
                </div><br>
            </div>

            <div class="col-6">
                <div class="input-info__group">
                    <div class="input-info__title">Data Pagamento</div>
                    <div class="input__info">
                        {{date('d/m/Y',strtotime($unscheduledPayment->payment_date))}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Valor</div>
                    <div class="input__info">
                        {{$unscheduledPayment->value}}                    
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Vencimento</div>
                    <div class="input__info">
                        {{date('d/m/Y',strtotime($unscheduledPayment->deadline))}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Nome Banco</div>
                    <div class="input__info">
                        {{date('d/m/Y',strtotime($unscheduledPayment->bankName))}}
                    </div>
                </div><br>
            </div>
        </div>
    </div>       
    
    @if ($unscheduledPayment->proof_payment != "")
        <div class="card">
            <div class="card-header">
                <div class="info__title info__title--white">
                    <center>Download do comprovante</center>
                </div>
            </div>
            <div class="card-body">
                <div class="downloadArea">
                    <div class="download__title">Clique pra fazer download do comprovante</div>
                    <a href="{{asset('storage/'.$unscheduledPayment->proof_payment)}}" class="downloadLink" 
                        download></a>   
                </div><br>
            </div>  
        </div>
    @endif

@endsection
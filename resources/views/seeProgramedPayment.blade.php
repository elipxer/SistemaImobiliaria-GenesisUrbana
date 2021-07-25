@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <div class="info__title">Informações programação de pagamento</div> 
                </div>
                
                <div class="col-6" style="text-align: right">
                    <a href="{{route('allProgramedPayment')}}" class="btn btn-success backButton">Voltar</a>  
                </div>
            </div>
        </div> 
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Nome da conta interna</div>
                        <div class="input__info">
                            {{$programedPayment->internalAccount}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Descrição</div>
                        <div class="input__info">
                            {{$programedPayment->description}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Fornecedor</div>
                        <div class="input__info">
                            {{$programedPayment->nameProvider!=""?$programedPayment->nameProvider:$programedPayment->companyProvider}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Valor Pagamento</div>
                        <div class="input__info">
                            {{str_replace('.',',', $programedPayment->value)}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Valor Total</div>
                        <div class="input__info">
                            {{$programedPayment->totalValue}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Data Para Pagamento</div>
                        <div class="input__info">
                            {{date('d/m/Y',strtotime($programedPayment->date))}}
                        </div>
                    </div><br>
                </div>

                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Numero Parcela</div>
                        <div class="input__info">
                            {{$programedPayment->num}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Total Parcelas</div>
                        <div class="input__info">
                            {{$programedPayment->totalNumberParcels}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Valor Pago</div>
                        <div class="input__info">
                            {{$programedPayment->value_payment!=""?$programedPayment->value_payment:'Não Pago'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Data Pago</div>
                        <div class="input__info">
                            {{$programedPayment->payment_date!=""?date('d/m/Y',strtotime($programedPayment->payment_date)):'Não Pago'}}
                        </div>
                    </div><br>

                    <div class="input-info__group input-info__group--big">
                        <div class="input-info__title">Forma</div>
                        <div class="input__info">
                            {{$programedPayment->payment_method!=""?$programedPayment->payment_method:'Não Pago'}}
                        </div>
                    </div><br>
                </div>
            </div>
        </div>       
    </div>
    
    @if ($programedPayment->status == 1)
        <div class="card">
            <div class="card-header">
                <div class="info__title info__title--white">
                    <center>Download do comprovante</center>
                </div>
            </div>
            <div class="card-body">
                <div class="downloadArea">
                    <div class="download__title">Clique pra fazer download do comprovante</div>
                    <a href="{{asset('storage/'.$programedPayment->proof_payment)}}" class="downloadLink" 
                        download></a>   
                </div><br>
            </div>  
    </div>
    @endif

    @if ($programedPayment->status != 1)
    <div class="card">
        <div class="card-header buttons_area--left">
            <div class="info__title info__title--without-margin-top">Selecione o banco</div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                           
                                <td></td>
                                <td>
                                    <input class="form-control" type="text" name="bankName"
                                        value="{{$bankName}}" placeholder="Nome Banco">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="description"
                                        value="{{$description}}" placeholder="Descrição">
                                </td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($banks as $bank)
                            <tr>
                                <td><input type="radio" name="idBank" class="idBank" 
                                    value="{{$bank->id}}"></td>
                                <td>{{$bank->name}}</td>
                                <td>{{$bank->description}}</td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div> 

    <div class="card">
        <div class="card-header">
            <div class="info__title">Pagamento</div>
        </div>
        <div class="card-body">
            <form action="{{route('payProgramedPayment')}}" id="formAlertPayment" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="idBank" id="idBank">
                <input type="hidden" name="idProgramedPayment" value="{{$programedPayment->id}}">
                <input type="hidden" id="valueToPad" value="{{str_replace('.',',', $programedPayment->value)}}">

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="payment_method">Forma</label>
                            <textarea class="form-control" name="payment_method" cols="15" rows="10"></textarea>
                        </div>

                        <div class="form-group w-50">
                            <label for="value_payment">Valor Pago</label>
                            <input class="form-control money" type="text" name="value_payment" 
                            id="value_payment" value="{{$programedPayment->value}}">
                        </div>
                        <div class="form-group w-50">
                            <label for="payment_date">Data Pagamento</label>
                            <input class="form-control" type="date" name="payment_date" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}">
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card" >
                            <div class="card-header">
                                <div class="info__title">
                                    <center>Inserir comprovante</center>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="uploadArea">
                                    <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                                    <div class="uploadAreaDrop">
                                        <div class="uploadAreaDrop__img">
                                            <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                        </div>
                                        <div class="uploadAreaDrop__descriptionFile"></div>
                                    </div>
                                    <input name="proof_payment" id="proof_payment" multiple="multiple" type="file" class="uploadInput"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <center><input type="submit" value="Salvar" class="btn btn-success btn-lg w-25" id="btnAddAlertPayment"></center>
                </div>
            </form>
        </div>
    </div>
    @endif
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
    
    <script src="{{asset('js/paymentAlert.min.js')}}"></script>
@endsection
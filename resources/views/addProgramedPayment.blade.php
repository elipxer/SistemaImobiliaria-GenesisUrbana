@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <div class="info__title">Adicionar Pagamento não programado</div> 
                </div>
                
                <div class="col-6" style="text-align: right">
                    <a href="{{route('allProgramedPayment')}}" class="btn btn-success backButton">Voltar</a>  
                </div>
            </div>
        </div> 
        <div class="card-body">
            <div class="card">
                <div class="card-header buttons_area--left">
                    <div class="info__title info__title--without-margin-top">Selecione a Conta Interna</div>
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
                                            <input class="form-control" type="text" name="internalAccountName"
                                                value="{{$internalAccountName}}" placeholder="Nome Conta Interna">
                                        </td>
        
                                        <td>
                                            <input class="form-control" type="text" name="internalAccountDescription"
                                                value="{{$internalAccountDescription}}" placeholder="Descrição">
                                        </td>
                                        <input type="submit" style="display: none;">
                                    </tr>
                                </form>
                            
                                @foreach ($internal_accounts as $internal_account)
                                    <tr>
                                        <td><input type="radio" name="idInternalAccount" class="idInternalAccount" value="{{$internal_account->id}}"></td>
                                        <td>{{$internal_account->name}}</td>
                                        <td style="max-width: 150px;">{{$internal_account->description}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div> 
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="info__title">Escolha o fornecedor:</div>
                </div>
    
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Digite o nome ou cpf/cnpj do fornecedor" id="clientName">
                        </div>
                    </div><br>
    
                    <div class="card" id="clientArea" style="display: none">
                        <table class="table table-bordered table-hover dataTable dtr-inline" id="clientAreaTable" role="grid" aria-describedby="example2_info">
                            <thead class="table table-dark">
                            <tr role="row">
                                <th></th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cpf</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cnpj</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Rg</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
                            </tr>   
                            </thead>
                            <tbody>
                                <tr class="clientLineTable" style="display: none">
                                    <td>
                                        <input type="radio" name="clientRadio" value="">
                                    </td>
                                    <td></td>
                                    <td></td>            
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table> 
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="info__title">Informações da programação de pagamento</div>
            </div>
    
            <div class="card-body">
                <form action="{{route('addProgramedPayment')}}" method="post" id="formProgramedPayment">
                    @csrf
                    <input type="hidden" name="idInternalAccount" id="idInternalAccount">
                    <input type="hidden" name="idProvider" id="idProvider">

                    
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group w-75">
                                        <label for="value">Valor</label>
                                        <input class="form-control money" type="text" name="value">
                                    </div>
                                </div>
                                <div class="col-6 d-flex">
                                    <div class="form-group w-50">
                                        <label for="parcelConfirmation">Parcelado</label><br>
                                        <input style="margin-left: 5px;" type="radio" name="parcelConfirmation" value="1" checked> Sim 
                                        <input style="margin-left: 5px;" type="radio" name="parcelConfirmation" value="2"> Não
                                    </div>

                                    <div class="form-group w-50" id="payNowArea" style="display: none;">
                                        <label for="payNow">Pagar agora</label><br>
                                        <input style="margin-left: 5px;" type="radio" name="payNow" class="payNow" value="1"> Sim 
                                        <input style="margin-left: 5px;" type="radio" name="payNow" class="payNow" value="2" checked> Não
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group" id="noParcelArea" style="display: none;">
                                <div class="form-group w-50">
                                    <label for="value">Data Vencimento</label>
                                    <input class="form-control money" type="date" name="date_payment" id="date_payment">
                                </div>
                            </div>
                           
                            <div class="form-group" id="parcelArea">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="days">Dias</label>
                                            <input type="number" class="form-control" name="days" 
                                                placeholder="Digite o intervalo de dias">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="days">Meses</label>
                                            <input type="number" class="form-control" name="month" 
                                                placeholder="Digite o dia de vencimento">
                                         </div>
                                    </div>
                                     
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="days">Primeira Parcela</label>
                                            <input type="date" class="form-control" name="firstParcel" min="{{date('Y-m-d')}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="days">Numero Parcelas</label>
                                            <input type="number" class="form-control" name="numberParcel">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea class="form-control" name="description" cols="30" rows="8"></textarea>
                            </div>
                        </div>    
                    </div>    
                </div>
            </div>

            

            <div class="form-group">
                <center><input type="submit" class="btn btn-success btn-lg w-25" value="Salvar" id="btnAddProgramedPayment"></center>
            </div>
        </form>
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
        const CLIENT_URL="{{route('getAllClients')}}";
    </script>
    <script src="{{asset('js/programedPayment.min.js')}}"></script>
@endsection
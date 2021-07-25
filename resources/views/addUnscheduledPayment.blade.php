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
                    <a href="{{route('allUnscheduledPayment')}}" class="btn btn-success backButton">Voltar</a>  
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
                                        <td>{{$internal_account->description}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div> 
            </div>

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
                                        <td><input type="radio" name="idBank" class="idBank" value="{{$bank->id}}"></td>
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
                <div class="info__title">Informações do pagamento não programado</div>
            </div>
    
            <div class="card-body">
                <form action="{{route('addUnscheduledPayment')}}" method="post" 
                    id="formUnscheduledPayment" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_payment_bank" id="idBank">
                    <input type="hidden" name="idInternalAccount" id="idInternalAccount">
                    <input type="hidden" name="idProvider" id="idProvider">

                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group w-75">
                                <label for="description">Descrição*</label>
                                <textarea class="form-control" name="description" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="value">Valor*</label>
                                <input class="form-control w-50 money" name="value" type="text">
                            </div>
                            <div class="form-group">
                                <label for="value">Vencimento*</label>
                                <input class="form-control w-50" name="deadline" type="date" min="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group w-50">
                                <label for="value">Valor Pagamento*</label>
                                <input class="form-control money" name="payment_value" type="text">
                            </div>

                            <div class="form-group w-50">
                                <label for="value">Data Pagamento*</label>
                                <input class="form-control" name="payment_date" type="date" min="{{date('Y-m-d')}}">
                            </div>
                            <div class="form-group">
                                <label for="value">Forma*</label>
                                <textarea class="form-control" name="payment_method" cols="30" rows="5"></textarea>
                            </div>
                        </div>    
                    </div>    
                </div>
            </div>

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

        <div class="form-group">
            <center><input type="submit" class="btn btn-success btn-lg w-25" value="Salvar" id="btnAddUnscheduledPayment"></center>
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
    <script src="{{asset('js/unscheduledPayment.min.js')}}"></script>
@endsection
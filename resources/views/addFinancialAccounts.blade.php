@extends('adminlte::page')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <h4>Adicionar Conta</h4>
                </div>
                <div class="col-6" style="text-align: right">
                    <a href="{{route('allFinancialAccounts')}}" class="btn btn-success backButton">Voltar</a>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="recipient_id">Cod Beneficiario*</label>
                                <input type="number" class="form-control w-75" value="{{old('recipient_id')}}" name="id_recipient" autofocus>
                            </div>

                            <div class="form-group">
                                <label for="recipient" >Beneficiario*</label>
                                <input type="text" name="recipient" value="{{old('recipient')}}" class="form-control w-75">
                            </div>

                            <div class="form-group">
                                <label for="cnpj" >Cnpj*</label>
                                <input type="text" name="cnpj" value="{{old('cnpj')}}" class="form-control w-50 cnpj">
                            </div>

                            <div class="form-group">
                                <label for="bank_name" >Nome do banco*</label>
                                <input type="text" name="bank_name" value="{{old('bank_name')}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="id_bank" >Cod Banco*</label>
                                <input type="number" name="id_bank" value="{{old('id_bank')}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="bank_agency">Agência*</label>
                                <input type="number" name="bank_agency" value="{{old('bank_agency')}}" class="form-control w-50">
                            </div>

                            <div class="row w-50">
                                <div class="col-8">
                                    <label for="account">Conta*</label>
                                    <input type="number" name="account" value="{{old('account')}}" class="form-control">
                                </div>
                                
                                <div class="col-4">
                                    <label for="verifying_digit">Dv*</label>
                                    <input type="number" name="verifying_digit" value="{{old('verifying_digit')}}" class="form-control w-75">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="wallet">Carteira*</label>
                                <input type="text" name="wallet" maxlength="4" value="{{old('wallet')}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="byte">Byte*</label>
                                <input type="text" name="byte" maxlength="4" value="{{old('byte')}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="post">Posto*</label>
                                <input type="text" name="post" maxlength="4" value="{{old('post')}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="accept">Aceite</label>
                                <input type="text" name="accept" maxlength="1" value="{{old('accept')}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="kind_doc">Especie Doc*</label>
                                <input type="text" name="kind_doc" maxlength="4" value="{{old('kind_doc')}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="observation">Observação</label>
                                <textarea class="form-control" name="observation" cols="30" rows="5">{{old('post')}}</textarea>
                            </div>
                        </div>
                    </div>       
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card"  id="address_account">
                    <div class="card-header">
                    <div class="row">
                        <div class="col-6">  
                        <h4>Endereço</h4>
                        </div>
                    </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cep">Cep</label>
                            <input type="text" class="form-control w-25 cep" name="cep" value="{{old('cep')}}">
                        </div>

                        <div class="form-group">
                            <label for="street">Rua</label>
                            <input type="text" class="form-control w-50" name="street" value="{{old('street')}}">
                        </div>

                        <div class="form-group">
                            <label for="number">Numero</label>
                            <input type="number" class="form-control w-25" name="number" value="{{old('number')}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="neighborhood">Bairro</label>
                            <input type="text" class="form-control w-50" name="neighborhood" value="{{old('neighborhood')}}">
                        </div>

                        <div class="form-group">
                            <label for="city">Cidade</label>
                            <input type="text" class="form-control w-50" name="city" value="{{old('city')}}">
                        </div>

                        <div class="form-group">
                            <label for="uf">UF</label>
                            <select name="uf" class="form-control w-25">
                                @foreach ($states as $state)
                                    <option {{old('uf')==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="info__title">
                            Usuarios permitidos:
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tableUsers" class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                            <thead class="table table-dark">
                            <tr role="row">
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Tipo</th>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr role="row" class="odd">
                                        <td>
                                            <input type="checkbox" class="idUserCheck" name="id_user_permission[]" value="{{$user->id}}">
                                        </td>
                                        <td>
                                            <div class="mini-photo_user">
                                                <img src="{{asset('storage/users/'.$user->photo)}}" alt="" 
                                                    width="100%" height="100%">
                                            </div>
                                        </td>
                                        <td tabindex="0" class="sorting_1">{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @if ($user->type==1)
                                                Administrador
                                            @elseif($user->type==2)
                                                Gestão
                                            @elseif($user->type==3)
                                                Operação
                                            @elseif($user->type==4)
                                                Comercialização
                                            @elseif($user->type==5)
                                                Júridico
                                            @elseif($user->type==6)
                                                Cliente
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <center><input type="submit" class="btn btn-block btn-outline-success w-25" id="btnInput" value="Salvar"></center>
        </div>
    </form>

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
            const ADDRESS_URL='{{route('getAddressByCep')}}';
        </script>
        <script src="{{asset('js/financialAccounts.min.js')}}"></script>

    @endsection

@endsection
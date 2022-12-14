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
                    <h4>Editar Conta</h4>
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
                            <input type="hidden" name="idAccount" value="{{$financialAccount->id}}">
                            <div class="form-group">
                                <label for="recipient_id">Cod Beneficiario*</label>
                                <input type="number" class="form-control w-75"  name="id_recipient" value="{{$financialAccount->id_recipient}}" autofocus>
                            </div>

                            <div class="form-group">
                                <label for="recipient" >Beneficiario*</label>
                                <input type="text" name="recipient" value="{{$financialAccount->recipient}}" class="form-control w-75">
                            </div>

                            <div class="form-group">
                                <label for="cnpj" >Cnpj*</label>
                                <input type="text" name="cnpj" value="{{$financialAccount->cnpj}}" class="form-control w-50 cnpj">
                            </div>

                            <div class="form-group">
                                <label for="bank_name" >Nome do banco*</label>
                                <input type="text" name="bank_name" value="{{$financialAccount->name}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="id_bank" >Cod Banco*</label>
                                <input type="number" name="id_bank" value="{{$financialAccount->id_bank}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="bank_agency">Ag??ncia*</label>
                                <input type="number" name="bank_agency" value="{{$financialAccount->bank_agency}}" class="form-control w-50">
                            </div>

                            <div class="row w-50">
                                <div class="col-8">
                                    <label for="account">Conta*</label>
                                    <input type="number" name="account" value="{{$financialAccount->account}}" class="form-control">
                                </div>
                                
                                <div class="col-4">
                                    <label for="verifying_digit">Dv*</label>
                                    <input type="number" name="verifying_digit" value="{{$financialAccount->verifying_digit}}" class="form-control w-50">
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
                                <input type="text" name="wallet" maxlength="4" value="{{$financialAccount->wallet}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="byte">Byte*</label>
                                <input type="text" name="byte" maxlength="4" value="{{$financialAccount->byte}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="post">Posto*</label>
                                <input type="text" name="post" maxlength="4" value="{{$financialAccount->post}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="accept">Aceite</label>
                                <input type="text" name="accept" maxlength="1" value="{{$financialAccount->accept}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="kind_doc">Especie Doc*</label>
                                <input type="text" name="kind_doc" maxlength="4" value="{{$financialAccount->kind_doc}}" class="form-control w-50">
                            </div>

                            <div class="form-group">
                                <label for="observation">Observa????o</label>
                                <textarea class="form-control" name="observation" cols="30" rows="5">{{$financialAccount->description}}</textarea>
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
                        <h4>Endere??o</h4>
                        </div>
                    </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cep">Cep</label>
                            <input type="text" class="form-control w-25 cep" name="cep" value="{{$financialAccount->cep}}">
                        </div>

                        <div class="form-group">
                            <label for="street">Rua</label>
                            <input type="text" class="form-control w-50" name="street" value="{{$financialAccount->street}}">
                        </div>

                        <div class="form-group">
                            <label for="number">Numero</label>
                            <input type="number" class="form-control w-25" name="number" value="{{$financialAccount->number}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="neighborhood">Bairro</label>
                            <input type="text" class="form-control w-50" name="neighborhood" value="{{$financialAccount->neighborhood}}">
                        </div>

                        <div class="form-group">
                            <label for="city">Cidade</label>
                            <input type="text" class="form-control w-50" name="city" value="{{$financialAccount->city}}">
                        </div>

                        <div class="form-group">
                            <label for="uf">UF</label>
                            <select name="uf" class="form-control w-25">
                                @foreach ($states as $state)
                                    <option {{$financialAccount->uf==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div>
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
                                            <input type="checkbox" class="idUserCheck" 
                                                {{in_array($user->id,explode(',',$financialAccount->id_user_permission))?'checked':''}} 
                                                name="id_user_permission[]" value="{{$user->id}}">
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
                                                Gest??o
                                            @elseif($user->type==3)
                                                Opera????o
                                            @elseif($user->type==4)
                                                Comercializa????o
                                            @elseif($user->type==5)
                                                J??ridico
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
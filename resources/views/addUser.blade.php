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
                  <h4>Adicionar Usuario</h4>
                </div>
                <div class="col-6" style="text-align: right">
                    <a href="{{route('allUsers')}}" class="btn btn-success backButton">Voltar</a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-body">
                                <div class="photo-user">
                                    <img src="{{asset('storage/users/user-default.png')}}" width="100%" height="100%" alt="user-default">
                                </div>
                                
                                <form method="POST" enctype="multipart/form-data" id="formUser">
                                @csrf
                                <input type="file" name="photo" id="photo-file" style="display: none;">
                                <input type="hidden" name="idClient" id="idClient" val="">    

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>
        
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
        
                                    <div class="col-md-6">
                                        <input id="email" type="email" name="email" id="email" class="form-control"  value="{{ old('email') }}"  autocomplete="email">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Senha</label>
        
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" autocomplete="new-password">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirme a senha</label>
        
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Tipo</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="type" id="type_user">
                                            <option value="1">Administrador</option>
                                            <option value="2">Gestão</option>
                                            <option value="3">Operação</option>
                                            <option value="4">Comercialização</option>
                                            <option value="5">Júridico</option>
                                            <option value="6">Cliente</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="info__title">Escolha o cliente para esse usuario:</div>
                        </div>
            
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <input class="form-control" id="search_client_input" disabled type="text" placeholder="Digite o nome ou cpf/cnpj do cliente" id="clientName">
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
                                                <input type="radio" name="clientCheck" value="">
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
                </div>
            </div>
        </div>
    </div>
    
    <center><input type="submit" class="btn btn-block btn-outline-success w-25" id="btnInput" value="Cadastrar"></center>
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
        const CLIENT_URL="{{route('getAllClients')}}";
    </script>
    <script src="{{asset('js/user.min.js')}}"></script>
@endsection



@endsection



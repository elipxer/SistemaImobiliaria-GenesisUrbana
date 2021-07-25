@extends('adminlte::page')
@extends('layouts/searchSale')
@extends('layouts/modal')
@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <form action="{{route('addInternalAccount')}}" method="post" id="internalAccountForm" style="display: none;">
        @csrf
        
        <input type="hidden" name="idInternalAccount" id="idInternalAccount">
        <div class="form-group">
            <label for="name">Nome</label>
            <input class="form-control" type="text" name="name" maxlength="8" required>
        </div>

        <div class="form-group">
            <label for="name">Descrição</label>
            <textarea class="form-control" name="description" cols="30" rows="5" required></textarea>
        </div>
        
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

        <div class="form-group">
            <center><input type="submit" class="btn btn-success btn-lg w-25" value="Salvar"></center>
        </div>
    </form>
    
    <div class="card">
        <div class="card-header buttons_area--left">
            @if(Auth::user()->type==1)
                <div title="adicionar conta interna" class="btnActions btnActions--middle" data-toggle="modal" 
                data-target="#modalAcoes" data-toggle="tooltip" id="btnInternalAccount">+</div>
            @endif
            <div class="info__title info__title--without-margin-top">Contas Internas</div>
            
        </div> 
    
        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ações</th>
                    </tr>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <td>
                                    <input class="form-control w-75" type="text" name="name"
                                        value="{{$name}}" placeholder="Nome Banco">
                                </td>

                                <td>
                                    <input class="form-control w-75" type="text" name="description"
                                        value="{{$description}}" placeholder="Descrição">
                                </td>
                                <td></td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($internalAccounts as $internalAccount)
                            <tr>
                                <td>{{$internalAccount->name}}</td>
                                <td style="max-width: 150px;">{{$internalAccount->description}}</td>
                                <td class="buttons_area">
                                    @if(Auth::user()->type==1)
                                        <div class="btnActions btnActions--transparent btnEditInternalAccount" data-toggle="modal" 
                                        data-target="#modalAcoes" data-toggle="tooltip" id="{{$internalAccount->id}}" 
                                        title="editar" id_permission="{{$internalAccount->id_user_permission}}">
                                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                        </div>
                                        <a href="{{route('deleteInternalAccount',['idInternalAccount'=>$internalAccount->id])}}" 
                                            class="btnActions btnActions--transparent btnDelete btnDelete"
                                            msg="Tem certeza que deseja excluir essa conta interna?">
                                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{asset('js/internalAccount.min.js')}}"></script>
    <script>
        const ADDINTERNALACCOUNT="{{route('addInternalAccount')}}";
        const UPDATEINTERNALACCOUNT="{{route('updateInternalAccount')}}";
    </script>
@endsection
    
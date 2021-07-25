@extends('adminlte::page')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            <a href="{{route('addUser')}}" title="adicionar usuario" class="btnActions btnActions--middle">+</a>
            <div class="info__title info__title--without-margin-top">Todos Usuarios</div>
        </div> 

        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Tipo</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Acoes</th>
                </thead>
                <tbody>
                    
                        <form method="get" class="formFilter">
                            <tr>
                                <td></td>
                                <td>
                                    <input class="form-control" type="text" name="name" 
                                        value="{{$name}}" placeholder="Nome Usuario">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="email" 
                                        value="{{$email}}" placeholder="Email Usuario">
                                </td>
                            <td>
                                    <select class="form-control selectFilter" name="type">
                                        <option></option>
                                        <option {{$type==1?'selected':''}} value="1">Administrador</option>
                                        <option {{$type==2?'selected':''}} value="2">Gestão</option>
                                        <option {{$type==3?'selected':''}} value="3">Operação</option>
                                        <option {{$type==4?'selected':''}} value="4">Comercialização</option>
                                        <option {{$type==5?'selected':''}} value="5">Júridico</option>
                                        <option {{$type==6?'selected':''}} value="6">Cliente</option>
                                        
                                    </select>
                                </td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                    @foreach ($users as $user)
                        <tr role="row" class="odd">
                            <td>
                                <div class="mini-photo_user">
                                    <img src="{{asset('storage/users/'.$user->photo)}}" alt="" 
                                        width="100%" height="100%">
                                </div>
                            </td>
                            <td tabindex="0" class="sorting_1">{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                {{$type_name[$user->type]}}
                            </td>
                            <td class="buttons_area">
                                <a href="{{route('editUser',['idUser'=>$user->id])}}" class="btnActions" title="ver mais">
                                        ...
                                </a>
                                <a href="{{route('deleteUser',['idUser'=>$user->id])}}" 
                                    msg="Tem certeza que deseja desativar esse usuario?"  
                                    class="btnActions btnActions--transparent btnDelete" title="excluir">
                                    <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div>Icons made by <a href="https://www.flaticon.com/authors/chanut" title="Chanut">Chanut</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
@endsection
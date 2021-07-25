@extends('adminlte::page')
@extends('layouts/searchSale')


@section('content')
    <h4>Todos Clientes</h4>
    <div class="card ">
        <div class="card-header">
            <div class="row">
                <div class="col-6 buttons_area--left">
                    @if(Auth::user()->type==1 || Auth::user()->type==4 || Auth::user()->type==3)
                        <a href="{{route('addClient')}}" title="adicionar cliente" class="btnActions btnActions--middle">+</a>
                    @endif
                    <div class="info__title info__title--without-margin-top">Clientes</div>
                </div>
                <div class="col-6" style="display:flex;justify-content:flex-end;">
                    @if(Auth::user()->type!=6)
                    <button class="btn btn-success dropdown-toggle h-50" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Filtragens
                    </button>
                    <div class="form-group" style="display:flex;">
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{route('allClientsSeveral')}}">Filtragem Geral</a>
                        </div>
                    </div>
                    <form method="GET" style="margin-left: 15px;">
                        <div class="form-group" style="display:flex;">
                            <select class="form-control" name="client_kind_person">
                                <option {{$kindPerson==1?'selected':''}} value="1">Pessoa Física</option>
                                <option {{$kindPerson==2?'selected':''}} value="2">Pessoa Júridica</option>
                            </select>
                            <input class="btn btn-success" type="submit" value="Filtrar">
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div> 

        @if($kindPerson==1)
        <div class="card-body">
            <div class="card-header">
                <h4>Clientes Físicos</h4>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cpf</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Nome Cônjuge</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Telefones</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Rua</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Numero</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Bairro</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cidade</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Estado</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cep</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Ações</th>
                </thead>
                <tbody>
                    <form method="GET" class="formFilter">
                        @if(Auth::user()->type!=6)
                        <tr>
                            <input type="hidden" name="client_kind_person" value="{{$kindPerson}}">
                        <td>
                            <input class="form-control" type="text" name="name" autofocus
                                value="{{$name}}" placeholder="Nome">
                        </td>
                        
                        <td>
                            <input class="form-control cpf-not-autoClear" type="text" name="cpf" autofocus
                                value="{{$cpf}}" placeholder="CPF">
                        </td>
                        
                        <td>
                            <input class="form-control" type="text" name="spouse_name" autofocus
                                value="{{$spouse_name}}" placeholder="Nome Cônjuge">
                        </td>
                        <td>
                            <input class="form-control phone-not-autoClear" type="text" name="phones" autofocus
                                value="{{$phones}}" placeholder="Telefone">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="street" autofocus
                                value="{{$street}}" placeholder="Rua">
                        </td>
                        <td>
                            <input class="form-control" type="number" name="number" autofocus
                                value="{{$number}}" placeholder="Num">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="neighborhood" autofocus
                                value="{{$neighborhood}}" placeholder="Bairro">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="city" autofocus
                                value="{{$city}}" placeholder="Cidade">
                        </td>
                        <td>
                            <select name="state" class="form-control selectFilter">
                                <option value=""></option>
                                @foreach ($states as $state)
                                    <option {{$state==$stateChoise?'selected':''}} value="{{$state}}">{{$state}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="form-control cep-not-autoClear" type="text" name="cep" autofocus
                                value="{{$cep}}" placeholder="Cep">
                        </td>
                       
                            <input type="submit" style="display: none;">
                        </tr>
                        @endif
                    </form>
                    @foreach ($clients as $client)
                        <tr role="row" class="odd">
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{$client->name}}</td>
                            <td style="min-width: 150px;">{{$client->cpf}}</td>
                            <td style="min-width: 280px;">{{$client->spouse_name}}</td>
                            <td style="min-width: 155px;">
                                <?php $phones=explode(',',$client->phones);?>
                                @foreach ($phones as $phone)
                                    {{$phone}}<br>
                                @endforeach
                            </td>
                            <td style="min-width: 130px;">{{$client->street}}</td>
                            <td style="min-width: 100px;">{{$client->number}}</td>
                            <td style="min-width: 150px;">{{$client->neighborhood}}</td>
                            <td style="min-width: 100px;">{{$client->city}}</td>
                            <td style="min-width: 100px;">{{$client->state}}</td>
                            <td style="min-width: 150px;">{{$client->cep}}</td>
                            
                            <td class="buttons_area">
                                <a href="{{route('seeClient',['idClient'=>$client->id])}}"  class="btnActions" title="ver mais">...</a>
                                @if(Auth::user()->type==1 || Auth::user()->type==3)
                                    <a href="{{route('editClient',['idClient'=>$client->id])}}"  class="btnActions btnActions--transparent" title="editar">
                                        <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                    </a>
                                @endif

                                @if(Auth::user()->type==1)
                                    <a href="{{route('deleteClient',['idClient'=>$client->id])}}" 
                                        class="btnActions btnActions--transparent btnDelete"
                                        msg="Tem certeza que deseja excluir esse cliente?">
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
        
        @elseif($kindPerson==2)
        
        <div class="card-body">
            <div class="card-header">
                <h4>Clientes Júridico</h4>
            </div>
            <div class="table-responsive">
            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome Empresa</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >CNPJ</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >CPF Representante</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Telefones</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Rua</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Numero</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Bairro</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cidade</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Estado</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cep</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Ações</th>
                </thead>
                <tbody>
                    <form method="GET" class="formFilter">
                        @if(Auth::user()->type!=6)
                        <tr>
                            <input type="hidden" name="client_kind_person" value="{{$kindPerson}}">
                        <td>
                            <input class="form-control" type="text" name="fantasy_name" autofocus
                                value="{{$fantasy_name}}" placeholder="Nome Fantasia">
                        </td>
                        <td>
                            <input class="form-control cnpj-not-autoClear" type="text" name="cnpj" autofocus
                                value="{{$cnpj}}" placeholder="CNPJ">
                        </td>
                        <td>
                            <input class="form-control cpf-not-autoClear" type="text" name="cpf_representative" autofocus
                                value="{{$cpf_representative_input}}" placeholder="Cpf Representante">
                        </td>
                        
                        <td>
                            <input class="form-control" type="email" name="email" autofocus
                                value="{{$email}}" placeholder="Email">
                        </td>
                        <td>
                            <input class="form-control phone-not-autoClear" type="text" name="phones" autofocus
                                value="{{$phones}}" placeholder="Telefone">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="street" autofocus
                                value="{{$street}}" placeholder="Rua">
                        </td>
                        <td>
                            <input class="form-control" type="number" name="number" autofocus
                                value="{{$number}}" placeholder="Num">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="neighborhood" autofocus
                                value="{{$neighborhood}}" placeholder="Bairro">
                        </td>
                        <td>
                            <input class="form-control" type="text" name="city" autofocus
                                value="{{$city}}" placeholder="Cidade">
                        </td>
                        <td>
                            <select name="state" class="form-control selectFilter">
                                <option value=""></option>
                                @foreach ($states as $state)
                                    <option {{$state==$stateChoise?'selected':''}} value="{{$state}}">{{$state}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input class="form-control cep-not-autoClear" type="text" name="cep" autofocus
                                value="{{$cep}}" placeholder="Cep">
                        </td>
                       
                            <input type="submit" style="display: none;">
                        </tr>
                        @endif
                    </form>
                    @foreach ($clients as $key=>$client)
                        <tr role="row" class="odd">
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{$client->company_name}}</td>
                            <td style="min-width: 200px;">{{$client->cnpj}}</td>
                            <td style="min-width: 200px;">{{$clients_representative[$key]}}</td>
                            <td style="min-width: 280px;">{{$client->email}}</td>
                            <td style="min-width: 155px;">
                                <?php $phones=explode(',',$client->phones);?>
                                @foreach ($phones as $phone)
                                    {{$phone}}<br>
                                @endforeach
                            </td>
                            <td style="min-width: 130px;">{{$client->street}}</td>
                            <td style="min-width: 100px;">{{$client->number}}</td>
                            <td style="min-width: 150px;">{{$client->neighborhood}}</td>
                            <td style="min-width: 150px;">{{$client->city}}</td>
                            <td style="min-width: 100px;">{{$client->state}}</td>
                            <td style="min-width: 150px;">{{$client->cep}}</td>
                            
                            <td class="buttons_area">
                                <a href="{{route('seeClient',['idClient'=>$client->id])}}"  class="btnActions" title="ver mais">...</a>
                                @if(Auth::user()->type==1 || Auth::user()->type==3)
                                    <a href="{{route('editClient',['idClient'=>$client->id])}}"  class="btnActions btnActions--transparent" title="editar">
                                        <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                    </a>
                                @endif

                                @if(Auth::user()->type==1)
                                    <a href="{{route('deleteClient',['idClient'=>$client->id])}}" 
                                        class="btnActions btnActions--transparent btnDelete btnDelete"
                                        msg="Tem certeza que deseja excluir esse cliente?">
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
        @endif
    </div>
@endsection

@section('footer')
    <div>Icons made by <a href="https://www.flaticon.com/authors/chanut" title="Chanut">Chanut</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
@endsection
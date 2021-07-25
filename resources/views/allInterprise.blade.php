@extends('adminlte::page')
@extends('layouts/searchSale')


@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            @if(Auth::user()->type==1)
            <a href="{{route('addInterprise')}}" title="adicionar empreendimento" class="btnActions btnActions--middle">+</a>
            @endif
            <div class="info__title info__title--without-margin-top">Empreendimentos</div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" >
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cidade</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Estado</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Data</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Observação</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Acoes</th>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                            <td>
                                <input class="form-control" type="text" name="name" autofocus
                                    value="{{$name}}" placeholder="Nome Empreendimento">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="city" 
                                    value="{{$city}}" placeholder="Cidade Empreendimento">
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
                                <input class="form-control dateFilter" type="date" name="date"  value="{{$date}}"
                                     placeholder="Cidade Empreendimento">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="observation" 
                                    value="{{$observation}}" placeholder="Obervação Empreendimento">
                            </td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($interprises as $interprise)
                            <tr role="row" class="odd">
                                <td tabindex="0" class="sorting_1">{{$interprise->name}}</td>
                                <td>{{$interprise->city}}</td>
                                <td>{{$interprise->state}}</td>
                                <td>{{date('d/m/Y',strtotime($interprise->date))}}</td>
                                <td>{{$interprise->observation}}</td>
                                <td class="{{Auth::user()->type==1?'buttons_area':''}}">
                                    <a href="{{route('allLot',['idInterprise'=>$interprise->id])}}"  class="btnActions" title="ver mais">...</a>
                                    @if(Auth::user()->type==1)
                                        <a href="{{route('editInterprise',['idInterprise'=>$interprise->id])}}"  class="btnActions btnActions--transparent" title="editar">
                                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                        </a>
                                        <a href="{{route('deleteInterprise',['idInterprise'=>$interprise->id])}}" 
                                            class="btnActions btnActions--transparent btnDelete btnDelete"
                                            msg="Tem certeza que deseja excluir esse empreendimento?">
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

@section('footer')
    <div>Icons made by <a href="https://www.flaticon.com/authors/chanut" title="Chanut">Chanut</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
@endsection
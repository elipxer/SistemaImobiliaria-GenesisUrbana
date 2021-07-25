@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            <div class="info__title info__title--without-margin-top">Contatos</div>
        </div> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                    <thead class="table table-dark">
                        <tr role="row">
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Contrato</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Usuario</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Via</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Assunto</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Categoria</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Data Registro</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Prazo</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Resolvido</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <td></td>
                                <td>
                                    <input class="form-control" type="text" name="where" autofocus
                                        value="{{$where}}" placeholder="Via">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="subject_matter" autofocus
                                        value="{{$subject_matter}}" placeholder="Assunto">
                                </td>
                                <td>
                                    <select class="form-control selectFilter" name="type">
                                        <option value=""></option>
                                        <option {{$type==1?'selected':''}} value="1">Diversos</option>
                                        <option {{$type==2?'selected':''}} value="2">Mudança Proprietário </option>
                                        <option {{$type==3?'selected':''}} value="3">Mudança Dia Vencimento  </option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control" type="date" name="register_date" autofocus
                                        value="{{$register_date}}" placeholder="Data Registrado">
                                </td>
                                <td>
                                    <input class="form-control" type="date" name="deadline" autofocus
                                        value="{{$deadline}}" placeholder="Prazo">
                                </td>
                                <td>
                                    <select class="form-control selectFilter" name="status">
                                        <option {{$status==1?'selected':''}} value="1">Sim</option>
                                        <option {{$status==2?'selected':''}} value="2">Não </option>
                                    </select>
                                </td>
                                
                                <td></td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>    
                        @foreach ($contacts as $contactItem)
                            <tr>
                                <td><a href="{{route('seeSale',['idSale'=>$contactItem->id_sale])}}">{{$contactItem->contractNumber}}</a></td>
                                <td>{{Auth::user()->name}}</td>
                                <td>{{$contactItem->where}}</td>
                                <td style="max-width: 200px;">{{$contactItem->subject_matter}}</td>
                                <td>
                                    @if ($contactItem->type==1)
                                        Diversos
                                    @elseif($contactItem->type==2)
                                        Mudança Proprietário    
                                    @elseif($contactItem->type==3)
                                        Mudança Dia Vencimento 
                                    @elseif($contactItem->type==4)
                                        Refinanciamento 
                                    @elseif($contactItem->type==5)
                                        Cancelamento
                                    @elseif($contactItem->type==6)
                                        Reemissão de Parcelas 
                                    @elseif($contactItem->type==7)
                                        Troca de Lote                    
                                    @endif
                                </td>
                                <td>{{date('d/m/Y',strtotime($contactItem->register_date))}}</td>
                                <td>{{date('d/m/Y',strtotime($contactItem->deadline))}}</td>
                                <td>{{$contactItem->status==1?'Resolvido':'Não Resolvido'}}</td>
                                <td class="buttons_area">
                                    @if($contactItem->status==2)
                                        <a href="{{route('seeContact',['idContact'=>$contactItem->id])}}"  class="btnActions" title="ver mais">...</a>
                                    @else
                                        <a href="{{route('doneContact',['idContact'=>$contactItem->id])}}"  class="btnActions" title="ver mais">...</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
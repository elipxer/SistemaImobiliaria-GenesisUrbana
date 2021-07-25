@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4 buttons_area--left">
                    @if(Auth::user()->type==1)
                        <a href="{{route('addTransfersBankView')}}" title="adicionar trasnfêrencia" class="btnActions btnActions--middle">+</a>
                    @endif
                    <div class="info__title info__title--without-margin-top">Transferencias Bancárias</div>
                </div>
                <div class="col-8">
                    <form method="GET" class="w-100 d-flex justify-content-end">
                        <div class="form-group" >
                            <label>Filtrar por datas</label><br>
                            <div class="form-group" style="display:flex;">
                                <input class="form-control" style="width: 40%" required value="{{$startDate}}" type="date" name="startDate" placeholder="Data Inicial">
                                <input class="form-control" style="width: 40%" required value="{{$finalDate}}" type="date" name="finalDate" placeholder="Data Fim">
                                <input class="btn btn-success" type="submit" value="Filtrar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Banco de Origem</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Banco Destino</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Ações</th>
                
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <td>
                                    <input class="form-control" type="text" name="nameOrigin"
                                        value="{{$nameOrigin}}" placeholder="Nome Banco Origem">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="nameDestiny"
                                        value="{{$nameDestiny}}" placeholder="Nome Banco Destino">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="description"
                                        value="{{$description}}" placeholder="Descrição">
                                </td>

                                <td> </td>
                                <td>
                                    <input class="form-control" type="date" name="date"
                                        value="{{$date}}">
                                </td>
                                <td></td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($transfers_banks as $transfer)
                            <tr>
                                <td>{{$transfer['originBank']->name}}</td>
                                <td>{{$transfer['destinyBank']->name}}</td>
                                <td>{{$transfer['transferBank']->description}}</td>
                                <td>{{$transfer['transferBank']->value}}</td>
                                <td>{{date('d/m/Y',strtotime($transfer['transferBank']->date))}}</td>
                                <td class="buttons_area">
                                    @if(Auth::user()->type==1)
                                        <a href="{{route('updateTransfersBankView',['idTransferBank'=>$transfer['transferBank']->id])}}"  class="btnActions btnActions--transparent" title="editar">
                                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                        </a>

                                        <a href="{{route('deleteTransferBank',['idTransferBank'=>$transfer['transferBank']->id])}}" 
                                            msg="Tem certeza que deseja excluir essa transferência?"  
                                            class="btnActions btnDelete" title="suspender venda">
                                            x
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
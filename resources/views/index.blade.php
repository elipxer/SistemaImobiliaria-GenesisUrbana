@extends('adminlte::page')
@extends('layouts/modal')
@extends('layouts/searchSale')


<form action="{{route('addIndex')}}" method="post" id="formIndex" style="display: none;">
    @csrf
    <div class="form-group">
        <input class="form-control" type="text" name="indexName" placeholder="Nome do indice">
    </div>
</form>

<form action="{{route('editIndex')}}" method="post" id="formIndexEdit" style="display: none;">
    @csrf
    <input type="hidden" name="idIndex">
    <div class="form-group">
        <input class="form-control" type="text" name="indexName" placeholder="Nome do indice">
    </div>
</form>

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            @if(Auth::user()->type==1 || Auth::user()->type==3)
            <div title="adicionar indice" class="btnActions btnActions--middle" data-toggle="modal" 
            data-target="#modalAcoes" data-toggle="tooltip" id="btnAddIndex">+</div>
            @endif
            <a href="{{route('seeAllIndexValue')}}" class="btnActions btnActions--middle" title="Ver todos os valores dos indices">...</a>
            <div class="info__title info__title--without-margin-top">Indices Financeiros</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                    <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data Cadastrado</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Hora Cadastrado</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Ações</th>
                    </thead>
                    <tbody>
                    
                    <form method="GET" class="formFilter">
                        <tr>
                            <td>
                                <input class="form-control" type="text" name="name" autofocus
                                    value="{{$name}}" placeholder="Nome Indice">
                            </td>
                            <td>
                                <input class="form-control" type="date" name="date" autofocus
                                    value="{{$date}}">
                            </td>

                            <td>
                                <input class="form-control" type="time" name="time" autofocus
                                    value="{{$time}}">
                            </td>
                            <input type="submit" style="display: none;">
                        </tr>
                    </form>
                        
                    @foreach ($index as $indexItem)
                        <tr role="row" class="odd">
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{$indexItem->name}}</td>
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{date('d/m/Y',strtotime($indexItem->date))}}</td>
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{$indexItem->time}}</td>
                            
                            <td class="buttons_area">
                                
                                <a href="{{route('seeIndexValue',['idIndex'=>$indexItem->id])}}" class="btnActions" title="ver mais">...</a>
                                @if(Auth::user()->type==1) 
                                    <a id="{{$indexItem->id}}" class="btnActions btnActions--transparent btnEditIndex" title="editar"  data-toggle="modal" 
                                    data-target="#modalAcoes" data-toggle="tooltip">
                                        <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                    </a>
                                    <a href="{{route('deleteIndex',['idIndex'=>$indexItem->id])}}" 
                                        class="btnActions btnActions--transparent btnDelete btnDelete"
                                        msg="Tem certeza que deseja excluir esse indice?">
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

    <script src="{{asset('js/index.min.js')}}"></script>
    
@endsection
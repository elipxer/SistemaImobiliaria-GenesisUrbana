@extends('adminlte::page')
@extends('layouts/searchSale')
@extends('layouts/modal')

<form action="{{route('addIndexValue')}}" method="post" id="formIndex" style="display: none;">
    @csrf
    <input type="hidden" name="idIndex" value="{{$index->id}}">
    <div class="form-group">
        <label for="index_value">Indice</label>
        <div class="form-control">
            {{$index->name}}
        </div> 
    </div>

    <div class="form-group">
        <label for="index_value">Valor do indice*</label>
        <input class="form-control" type="number" name="index_value" step="any">
    </div>

    <div class="form-group">
        <label for="index_value">Mes*</label>
        <input class="form-control" type="month" name="month">
    </div>
</form>

<form action="{{route('editIndexValue')}}" method="post" id="formIndexEdit" style="display: none;">
    @csrf
    <input type="hidden" name="idIndex" value="{{$index->id}}">
    <input type="hidden" name="idIndexValue" value="">
    <div class="form-group">
        <label for="index_value">Indice</label>
        <div class="form-control indexNameInput">
            {{$index->name}}
        </div> 
    </div>

    <div class="form-group">
        <label for="index_value">Valor do indice*</label>
        <input class="form-control" type="number" name="index_value" step="any">
    </div>

    <div class="form-group">
        <label for="index_value">Mes*</label>
        <input class="form-control" type="month" name="index_month">
    </div>
</form>

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 buttons_area--left">
                    @if(Auth::user()->type==1 || Auth::user()->type==3)
                    <div title="adicionar valor ao indice" class="btnActions btnActions--middle" data-toggle="modal" 
                    data-target="#modalAcoes" data-toggle="tooltip" id="btnAddValueIndex">+</div>
                    @endif
                    <div class="info__title info__title--without-margin-top">Valores do Indice: {{$index->name}}</div>
                </div>
                <div class="col-6" style="text-align: right;">
                    <a href="{{route('index')}}" class="btn btn-success backButton">Voltar</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                    <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Indice</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Data</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Ações</th>
                    </thead>
                    <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <td>
                                    
                                </td>
                                <td>
                                    <input class="form-control" type="number" step="any" name="index_value" autofocus
                                        value="{{$index_value}}">
                                </td>
    
                                <td>
                                    <input class="form-control" type="month" name="month" autofocus
                                        value="{{$month}}">
                                </td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>    
                    @foreach ($indexValues as $indexVal)
                        <tr role="row" class="odd">
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{$indexVal->indexName}}</td>
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{$indexVal->value}}</td>
                            <td style="min-width: 200px;" tabindex="0" class="sorting_1">{{date('d/m/Y',strtotime($indexVal->month))}}</td>
                            
                            <td class="buttons_area">
                                @if(Auth::user()->type==1) 
                                <a id="{{$indexVal->id}}" class="btnActions btnActions--transparent btnEditIndexValue" title="editar"  data-toggle="modal" 
                                data-target="#modalAcoes" data-toggle="tooltip">
                                    <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                </a>
                                <a href="{{route('deleteIndexValue',['idIndexValue'=>$indexVal->id,'idIndex'=>$indexVal->idIndex])}}" 
                                    class="btnActions btnActions--transparent btnDelete btnDelete"
                                    msg="Tem certeza que deseja excluir esse valor do indice?">
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
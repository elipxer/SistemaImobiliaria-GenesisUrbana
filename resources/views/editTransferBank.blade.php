@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6">
                <div class="info__title">Atualizar Transfêrencia</div> 
            </div>
            
            <div class="col-6" style="text-align: right">
              <a href="{{route('allTransfersBank')}}" class="btn btn-success backButton">Voltar</a>  
            </div>
        </div>
      </div>  
    <div class="card">
        <div class="card-header buttons_area--left">
            <div class="info__title info__title--without-margin-top">Selecione o banco de origem</div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <input type="hidden" name="typeBankSearch" value="1">
                                <td></td>
                                <td>
                                    <input class="form-control" type="text" name="name"
                                        value="{{$nameOrigin}}" placeholder="Nome Banco">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="description"
                                        value="{{$descriptionOrigin}}" placeholder="Descrição">
                                </td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($banks_origin as $bank)
                            <tr>
                                <td><input type="radio" {{$transfersBank->id_origin_bank==$bank->id?'checked':''}} name="idBankOrigin" class="idBankOrigin" value="{{$bank->id}}"></td>
                                <td>{{$bank->name}}</td>
                                <td>{{$bank->description}}</td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>    

    <div class="card">
        <div class="card-header buttons_area--left">
            <div class="info__title info__title--without-margin-top">Selecione o banco de destino</div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                    </tr>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <input type="hidden" name="typeBankSearch" value="2">
                                <td></td>
                                <td>
                                    <input class="form-control" type="text" name="name"
                                        value="{{$nameDestiny}}" placeholder="Nome Banco">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="description"
                                        value="{{$descriptionDestiny}}" placeholder="Descrição">
                                </td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($banks_destiny as $bank)
                            <tr>
                                <td><input type="radio"  {{$transfersBank->id_destiny_bank==$bank->id?'checked':''}} class="idBankDestiny" name="idBankDestiny" value="{{$bank->id}}"></td>
                                <td>{{$bank->name}}</td>
                                <td>{{$bank->description}}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div> 
    
    <div class="card">
        <div class="card-header">
            <div class="info__title">Informações da transferência</div>
        </div>

        <div class="card-body">
            <form action="{{route('updateTransfersBank')}}" method="post" id="formTransfers">
                @csrf
                <input type="hidden" name="idBankOrigin" id="idBankOrigin" value="{{$transfersBank->id_origin_bank}}">
                <input type="hidden" name="idBankDestiny" id="idBankDestiny" value="{{$transfersBank->id_destiny_bank}}">
                <input type="hidden" name="idTransfersBank" value="{{$transfersBank->id}}">

                <div class="form-group w-50">
                    <label for="description">Descrição*</label>
                    <textarea class="form-control" name="description" cols="30" rows="5">{{$transfersBank->description}}</textarea>
                </div>
                <div class="form-group">
                    <label for="value">Valor*</label>
                    <input class="form-control w-25 money" name="value" type="text" value="{{$transfersBank->value}}">
                </div>
        </div>
        <div class="form-group">
            <center><input type="submit" class="btn btn-success btn-lg w-25" value="Salvar" id="btnAddTransfers"></center>
        </div>
        </form>
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
<script src="{{asset('js/transfersBank.min.js')}}"></script>

@endsection
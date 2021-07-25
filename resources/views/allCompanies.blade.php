@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            <a href="{{route('addCompany')}}" title="adicionar empresa" class="btnActions btnActions--middle">+</a>
            <div class="info__title info__title--without-margin-top">Empresas</div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cnpj</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Rua</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Num</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Bairro</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cidade</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cep</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cpf Representante</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Acoes</th>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                            <td>
                                <input class="form-control" type="text" name="company_name" autofocus
                                    value="{{$company_name}}" placeholder="Nome Empresa">
                            </td>
                            
                            <td>
                                <input class="form-control" type="text" name="cnpj" 
                                    value="{{$cnpj}}" placeholder="Cnpj">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="street" 
                                value="{{$street}}">
                            </td>
                            <td>
                                <input class="form-control" type="number" name="number" 
                                value="{{$number}}">
                            </td>
                            
                            <td>
                                <input class="form-control" type="text" name="neighborhood" 
                                    value="{{$neighborhood}}">
                            </td>

                            <td>
                                <input class="form-control" type="text" name="city" value="{{$city}}">
                            </td>

                            <td>
                                <input class="form-control cep-not-autoClear" type="text" name="cep" value="{{$cep}}">
                            </td>

                            <td>
                                <input class="form-control" type="text" name="representative_cpf" 
                                    value="{{$representative_cpf}}">
                            </td>
                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($companies as $company)
                            <tr>
                                <td>{{$company->company_name}}</td>
                                <td>{{$company->cnpj}}</td>
                                <td>{{$company->street}}</td>
                                <td>{{$company->number}}</td>
                                <td>{{$company->neighborhood}}</td>
                                <td>{{$company->city}}</td>
                                <td>{{$company->cep}}</td>
                                <td>{{$company->representative_cpf}}</td>
                                <td class="buttons_area">
                                    <a href="{{route('editCompany',['idCompany'=>$company->id])}}"  class="btnActions btnActions--transparent" title="editar">
                                        <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                    </a>
                                    @if($company->id != 1)
                                    <a href="{{route('suspend',['idCompany'=>$company->id])}}" 
                                        class="btnActions btnActions--transparent btnDelete btnDelete"
                                        msg="Tem certeza que deseja excluir essa empresa?">
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
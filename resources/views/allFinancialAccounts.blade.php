@extends('adminlte::page')
@extends('layouts/searchSale')


@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            @if(Auth::user()->type==1 || Auth::user()->type==3)
            <a href="{{route('addFinancialAccounts')}}" title="adicionar contas" class="btnActions btnActions--middle">+</a>
            @endif
            <div class="info__title info__title--without-margin-top">Contas</div>
        </div> 

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" >
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome Banco</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cod Banco</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Agencia</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >DV</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Conta</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Boleto</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Observação</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Acoes</th>
                </thead>
                <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <td>
                                    <input class="form-control" type="text" name="bank_name" 
                                    value="{{$name}}" placeholder="Nome Banco">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="id_recipient" 
                                    value="{{$id_recipient}}" placeholder="Codigo Beneficiario">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="bank_agency" 
                                    value="{{$bank_agency}}" placeholder="Agência">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="verifying_digit" 
                                    value="{{$verifying_digit}}" placeholder="DV">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="account" 
                                    value="{{$account}}" placeholder="Conta">
                                </td>
                                
                                <td>
                                    <input class="form-control" type="text" name="observation" 
                                    value="{{$description}}" placeholder="Observação">
                                </td>

                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($allFinancialAccounts as $financialAccounts)
                            <tr>
                                <td>{{$financialAccounts->name}}</td>
                                <td>{{$financialAccounts->id_recipient}}</td>
                                <td>{{$financialAccounts->bank_agency}}</td>
                                <td>{{$financialAccounts->verifying_digit}}</td>
                                <td>{{$financialAccounts->account}}</td>
                                <td></td>
                                <td>{{$financialAccounts->description}}</td>
                                <td class="buttons_area">
                                    <a href="{{route('seefinancialAccounts',['idAccount'=>$financialAccounts->id])}}" class="btnActions" title="ver mais">
                                            ...
                                    </a>
                                    @if (Auth::user()->type==1)
                                        <a href="{{route('editFinancialAccounts',['idAccount'=>$financialAccounts->id])}}"  class="btnActions btnActions--transparent" title="editar">
                                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                        </a>
                                        <a href="{{route('deleteAccounts',['idAccount'=>$financialAccounts->id])}}" 
                                            msg="Tem certeza que deseja suspender essa conta??"  
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

@section('footer')
    <div>Icons made by <a href="https://www.flaticon.com/authors/chanut" title="Chanut">Chanut</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
@endsection
@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6 buttons_area--left">
                @if(Auth::user()->type==1)
                <a href="{{route('editFinancialAccounts',['idAccount'=>$financialAccounts->id])}}"  
                    class="btnActions btnActions--middle btnActions--transparent" title="editar">
                    <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                </a>
                <a href="{{route('deleteAccounts',['idAccount'=>$financialAccounts->id])}}" 
                    msg="Tem certeza que deseja suspender essa conta??"  
                    class="btnActions btnActions--middle btnDelete" title="suspender venda">
                    x
                </a>
                @endif
                <div class="info__title">Informações Conta</div>
            </div>
            
            <div class="col-6" style="text-align: right;">
                <a href="{{route('allClients')}}" class="btn btn-success backButton">Voltar</a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="input-info__group">
                    <div class="input-info__title">Cod Beneficiario</div>
                    <div class="input__info">
                        {{$financialAccounts->id_recipient}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Beneficiario</div>
                    <div class="input__info">
                        {{$financialAccounts->recipient}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Cnpj</div>
                    <div class="input__info">
                        {{$financialAccounts->cnpj}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Nome do Banco</div>
                    <div class="input__info">
                        {{$financialAccounts->bank_name}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Cod Banco</div>
                    <div class="input__info">
                        {{$financialAccounts->id_bank}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Agencia</div>
                    <div class="input__info">
                        {{$financialAccounts->bank_agency}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Conta</div>
                    <div class="input__info">
                        {{$financialAccounts->account}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Dv</div>
                    <div class="input__info">
                        {{$financialAccounts->verifying_digit}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Carteira</div>
                    <div class="input__info">
                        {{$financialAccounts->wallet}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Byte</div>
                    <div class="input__info">
                        {{$financialAccounts->byte}}
                    </div>
                </div><br>
            </div>

            <div class="col-6">
                <div class="input-info__group">
                    <div class="input-info__title">Poste</div>
                    <div class="input__info">
                        {{$financialAccounts->post}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Aceite</div>
                    <div class="input__info">
                        {{$financialAccounts->accept}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Especie Doc</div>
                    <div class="input__info">
                        {{$financialAccounts->kind_doc}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Rua</div>
                    <div class="input__info">
                        {{$financialAccounts->street}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Numero</div>
                    <div class="input__info">
                        {{$financialAccounts->number}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Bairro</div>
                    <div class="input__info">
                        {{$financialAccounts->neighborhood}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Cidade</div>
                    <div class="input__info">
                        {{$financialAccounts->city}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Uf</div>
                    <div class="input__info">
                        {{$financialAccounts->uf}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Cep</div>
                    <div class="input__info">
                        {{$financialAccounts->cep}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Observação</div>
                    <div class="input__info">
                        {{$financialAccounts->observação}}
                    </div>
                </div><br>

            </div>
        </div>
        
               
    </div>
</div>
@endsection
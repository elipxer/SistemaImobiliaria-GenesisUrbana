@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6 buttons_area--left">
                @if(Auth::user()->type==3 || Auth::user()->type==1)
                <a href="{{route('editClient',['idClient'=>$client->id])}}"  class="btnActions btnActions--middle btnActions--transparent" title="editar">
                    <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                </a>
                <a href="{{route('deleteClient',['idClient'=>$client->id])}}" 
                    class="btnActions btnActions--middle btnActions--transparent btnDelete btnDelete"
                    msg="Tem certeza que deseja excluir esse cliente?">
                    <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                </a>
                @endif
                <div class="info__title">Informações cliente</div>
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
                    <div class="input-info__title">Nome</div>
                    <div class="input__info">
                        {{$client->name}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Data Nascimento</div>
                    <div class="input__info">
                        {{date('d/m/Y',strtotime($client->birth_date))}}
                    </div>
                </div><br>

                @if($client->kind_person==2)
                <div class="input-info__group">
                    <div class="input-info__title">Razão Social</div>
                    <div class="input__info">
                        {{$client->company_name}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Nome Fantasia</div>
                    <div class="input__info">
                        {{$client->fantasy_name}}
                    </div>
                </div><br>
                @endif

                <div class="input-info__group">
                    <div class="input-info__title">Tipo Pessoa</div>
                    <div class="input__info">
                        {{$client->kind_person==1?'Pessoa Fisica':'Pessoa Juridica'}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Rg</div>
                    <div class="input__info">
                        {{$client->rg}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Orgão Emissor</div>
                    <div class="input__info">
                        {{$client->emitting_organ}}
                    </div>
                </div><br>

                @if($client->kind_person==1)
                    <div class="input-info__group">
                        <div class="input-info__title">Cpf</div>
                        <div class="input__info">
                            {{$client->cpf}}
                        </div>
                    </div><br>
                @endif

                @if ($client->kind_person==2)
                    <div class="input-info__group">
                        <div class="input-info__title">Cnpj</div>
                        <div class="input__info">
                            {{$client->cnpj}}
                        </div>
                    </div><br>
                @endif
                
                <div class="input-info__group">
                    <div class="input-info__title">Estado Cívil</div>
                    <div class="input__info">
                        @if ($client->marital_status==1)
                            Solteiro
                        @elseif($client->marital_status==2)
                            Casado
                        @elseif($client->marital_status==3)
                            Divorciado    
                        @endif
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Nacionalidade</div>
                    <div class="input__info">
                        {{$client->nationality==1?'Brasileiro':'Estrangeiro'}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Sexo</div>
                    <div class="input__info">
                        {{$client->sex==1?'Masculino':'Feminino'}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Profissão</div>
                    <div class="input__info">
                        {{$client->occupation}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Rua</div>
                    <div class="input__info">
                        {{$client->street}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Numero</div>
                    <div class="input__info">
                        {{$client->number}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Complemento</div>
                    <div class="input__info">
                        {{$client->complement}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Bairro</div>
                    <div class="input__info">
                        {{$client->neighborhood}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Cidade</div>
                    <div class="input__info">
                        {{$client->city}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Estado</div>
                    <div class="input__info">
                        {{$client->state}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Cep</div>
                    <div class="input__info">
                        {{$client->cep}}
                    </div>
                </div><br>

            </div>

            <div class="col-6">
                @if ($client->marital_status==2)
                    <div class="input-info__group">
                        <div class="input-info__title">Nome Cônjuge</div>
                        <div class="input__info">
                            {{$client->spouse_name}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Data Nascimento Cônjuge</div>
                        <div class="input__info">
                            {{date('d/m/Y',strtotime($client->spouse_birth_date))}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Rg Cônjuge</div>
                        <div class="input__info">
                            {{$client->spouse_rg}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Orgão Emissor Cônjuge</div>
                        <div class="input__info">
                            {{$client->spouse_emitting_organ}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cpf Cônjuge</div>
                        <div class="input__info">
                            {{$client->spouse_cpf}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Nacionalidade Cônjuge</div>
                        <div class="input__info">
                            {{$client->spouse_nationality==1?'Brasileiro':'Estrangeiro'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Sexo Cônjuge</div>
                        <div class="input__info">
                            {{$client->spouse_sex==1?'Masculino':'Feminino'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Profissão Cônjuge</div>
                        <div class="input__info">
                            {{$client->spouse_occupation}}
                        </div>
                    </div><br>
                @endif

                <div class="input-info__group">
                    <div class="input-info__title">Rua Cobrança</div>
                    <div class="input__info">
                        {{$client->street_payment_collection}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Numero Cobrança</div>
                    <div class="input__info">
                        {{$client->number_payment_collection}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Complemento Cobrança</div>
                    <div class="input__info">
                        {{$client->complement_payment_collection}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Bairro Cobrança</div>
                    <div class="input__info">
                        {{$client->neighborhood_payment_collection}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Cidade Cobrança</div>
                    <div class="input__info">
                        {{$client->city_payment_collection}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Estado Cobrança</div>
                    <div class="input__info">
                        {{$client->state_payment_collection}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Cep Cobrança</div>
                    <div class="input__info">
                        {{$client->cep_payment_collection}}
                    </div>
                </div><br>

                <div class="input-info__group  input-info__group--big">
                    <div class="input-info__title">Telefones</div>
                    <div class="input__info--left">
                        <?php $phones=explode(',',$client->phones);?>
                            @foreach ($phones as $phone)
                                {{$phone}}<br>
                        @endforeach
                    </div>
                </div><br>
            </div>
        </div>
    </div>

    @if ($client_representative != null)
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 buttons_area--left">
                    @if(Auth::user()->type==3 || Auth::user()->type==1)
                        <a href="{{route('editClient',['idClient'=>$client_representative->id])}}"  class="btnActions btnActions--middle btnActions--transparent" title="editar">
                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                        </a>
                        <a href="{{route('deleteClient',['idClient'=>$client_representative->id])}}" 
                            class="btnActions btnActions--middle btnActions--transparent btnDelete btnDelete"
                            msg="Tem certeza que deseja excluir esse cliente?">
                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                        </a>
                    @endif
                    <div class="info__title">Representante Legal</div>
                </div>
                
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Nome</div>
                        <div class="input__info">
                            {{$client_representative->name}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Data Nascimento</div>
                        <div class="input__info">
                            {{date('d/m/Y',strtotime($client_representative->birth_date))}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Tipo Pessoa</div>
                        <div class="input__info">
                            {{$client_representative->kind_person==1?'Pessoa Fisica':'Pessoa Juridica'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Rg</div>
                        <div class="input__info">
                            {{$client_representative->rg}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Orgão Emissor</div>
                        <div class="input__info">
                            {{$client_representative->emitting_organ}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cpf</div>
                        <div class="input__info">
                            {{$client->cpf}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Estado Cívil</div>
                        <div class="input__info">
                            @if ($client_representative->marital_status==1)
                                Solteiro
                            @elseif($client_representative->marital_status==2)
                                Casado
                            @elseif($client_representative->marital_status==3)
                                Divorciado    
                            @endif
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Nacionalidade</div>
                        <div class="input__info">
                            {{$client_representative->nationality==1?'Brasileiro':'Estrangeiro'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Sexo</div>
                        <div class="input__info">
                            {{$client_representative->sex==1?'Masculino':'Feminino'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Profissão</div>
                        <div class="input__info">
                            {{$client_representative->occupation}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Rua</div>
                        <div class="input__info">
                            {{$client_representative->street}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Numero</div>
                        <div class="input__info">
                            {{$client_representative->number}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Complemento</div>
                        <div class="input__info">
                            {{$client_representative->complement}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Bairro</div>
                        <div class="input__info">
                            {{$client_representative->neighborhood}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cidade</div>
                        <div class="input__info">
                            {{$client_representative->city}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Estado</div>
                        <div class="input__info">
                            {{$client_representative->state}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cep</div>
                        <div class="input__info">
                            {{$client_representative->cep}}
                        </div>
                    </div><br>

                </div>

                <div class="col-6">
                    @if ($client_representative->marital_status==2)
                    <div class="input-info__group">
                        <div class="input-info__title">Nome Cônjuge</div>
                        <div class="input__info">
                            {{$client_representative->spouse_name}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Data Nascimento Cônjuge</div>
                        <div class="input__info">
                            {{date('d/m/Y',strtotime($client_representative->spouse_birth_date))}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Rg Cônjuge</div>
                        <div class="input__info">
                            {{$client_representative->spouse_rg}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Orgão Emissor Cônjuge</div>
                        <div class="input__info">
                            {{$client_representative->spouse_emitting_organ}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cpf Cônjuge</div>
                        <div class="input__info">
                            {{$client_representative->spouse_cpf}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cnpj Cônjuge</div>
                        <div class="input__info">
                            {{$client_representative->spouse_cnpj}}
                        </div>
                    </div><br>


                    <div class="input-info__group">
                        <div class="input-info__title">Nacionalidade Cônjuge</div>
                        <div class="input__info">
                            {{$client_representative->spouse_nationality==1?'Brasileiro':'Estrangeiro'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Sexo Cônjuge</div>
                        <div class="input__info">
                            {{$client_representative->spouse_sex==1?'Masculino':'Feminino'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Profissão</div>
                        <div class="input__info">
                            {{$client_representative->spouse_occupation}}
                        </div>
                    </div><br>
                    @endif
                    <div class="input-info__group">
                        <div class="input-info__title">Rua Cobrança</div>
                        <div class="input__info">
                            {{$client_representative->street_payment_collection}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Numero Cobrança</div>
                        <div class="input__info">
                            {{$client_representative->number_payment_collection}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Complemento Cobrança</div>
                        <div class="input__info">
                            {{$client_representative->complement_payment_collection}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Bairro Cobrança</div>
                        <div class="input__info">
                            {{$client_representative->neighborhood_payment_collection}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cidade Cobrança</div>
                        <div class="input__info">
                            {{$client_representative->city_payment_collection}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Estado Cobrança</div>
                        <div class="input__info">
                            {{$client_representative->state_payment_collection}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cep Cobrança</div>
                        <div class="input__info">
                            {{$client_representative->cep_payment_collection}}
                        </div>
                    </div><br>

                    <div class="input-info__group  input-info__group--big">
                        <div class="input-info__title">Telefones</div>
                        <div class="input__info--left">
                            <?php $phones=explode(',',$client_representative->phones);?>
                                @foreach ($phones as $phone)
                                    {{$phone}}<br>
                            @endforeach
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


<div class="info__title">Vendas Relacionada ao Cliente</div>
@foreach ($sales as $sale)
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6 buttons_area--left">
                @if(Auth::user()->type==1)
                    <a href="{{route('suspendSale',['idSale'=>$sale['sale']->id])}}" 
                        msg="Tem certeza que deseja suspender essa venda??"  
                        class="btnActions btnActions--middle  btnDelete" title="suspender venda">
                        x
                    </a>
                @endif
                <div class="info__title">Informações da Venda</div>
                
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="input-info__group">
                    <div class="input-info__title">Empreendimento</div>
                    <div class="input__info">
                        {{$sale['sale']->interprise_name}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Lot</div>
                    <div class="input__info">
                        {{$sale['sale']->lot_number}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Quadra</div>
                    <div class="input__info">
                        {{$sale['sale']->lot_block}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Valor</div>
                    <div class="input__info">
                        {{'R$ '.$sale['sale']->value}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Entrada</div>
                    <div class="input__info">
                        {{'R$ '.$sale['sale']->input}}
                    </div>
                </div><br>

                
                <div class="input-info__group">
                    <div class="input-info__title">Desconto</div>
                    <div class="input__info">
                        {{'R$ '.$sale['sale']->descont}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Indice</div>
                    <div class="input__info">
                        {{$sale['sale']->index}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Parcelas Paga</div>
                    <div class="input__info">
                        {{$sale['parcels_paid']}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Parcelas Não Paga</div>
                    <div class="input__info">
                        {{$sale['parcels_unpaid']}}
                    </div>
                </div><br>

                
                <div class="input-info__group">
                    <div class="input-info__title">Parcelas Atrasadas</div>
                    <div class="input__info">
                        {{$sale['later_parcels']}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Dia de vencimento</div>
                    <div class="input__info">
                        Dia {{$sale['sale']->expiration_day}}
                    </div>
                </div><br>
              
            </div>
            <div class="col-6">
                <div class="input-info__group">
                    <div class="input-info__title">Saldo Devedor</div>
                    <div class="input__info">
                        {{'R$ '.$sale['rest_value']}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Valor Atrasado</div>
                    <div class="input__info">
                        {{'R$ '.$sale['later_value']}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Valor Pago</div>
                    <div class="input__info">
                        {{'R$ '.$sale['paid_value']}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Numero Parcelas</div>
                    <div class="input__info">
                        {{$sale['sale']->parcels}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Primeira parcela</div>
                    <div class="input__info">
                        {{date('d/m/Y',strtotime($sale['sale']->first_parcel))}}
                    </div>
                </div><br>
                
                <div class="input-info__group">
                    <div class="input-info__title">Status</div>
                    <div class="input__info">
                        @if ($sale['sale']->type==1)
                            Proposta
                        @elseif($sale['sale']->type==2)
                            Ativo   
                        @elseif($sale['sale']->type==3)
                            Cancelado
                        @endif
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Tipo Contrato</div>
                    <div class="input__info">
                        @if ($sale['sale']->type==1)
                            Proposta
                        @elseif($sale['sale']->type==2)
                            Ativo  
                        @elseif($sale['sale']->type==3)
                            Cancelado  
                        @endif
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Data Proposta</div>
                    <div class="input__info">
                        {{date('d/m/Y',strtotime($sale['sale']->propose_date))}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Data Inicio Contrato</div>
                    <div class="input__info">
                        {{$sale['sale']->begin_contract_date!=""?date('d/m/Y',strtotime($sale['sale']->begin_contract_date)):'Contrato não iniciado'}}
                    </div>
                </div><br>

                <div class="input-info__group">
                    <div class="input-info__title">Juros por ano</div>
                    <div class="input__info">
                        {{$sale['sale']->annual_rate}}%
                    </div>
                </div><br>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection


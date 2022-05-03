@extends('adminlte::page')
@extends('layouts/searchSale')
@extends('layouts/modal')


<form action="{{route('payParcel')}}" id="payParcelForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="idSale" value="{{$sale->id}}">
    <input type="hidden" name="id" id="idParcel">

    <div class="form-group">
        <label for="pad_date">Valor Para Pagamento</label>
        <div class="form-control" id="valueParcel"></div>
    </div>

    <div class="form-group">
        <label for="pad_date">Data Pagamento*</label>
        <input class="form-control" type="date" name="pad_date" value="{{date('Y-m-d')}}">
    </div>

    <div class="form-group">
        <label for="pad_description">Forma Pagamento*</label>
        <textarea class="form-control" maxlength="450" name="pad_description"></textarea>
    </div>

    <div class="form-group">
        <label for="pad_value">Valor Pagamento*</label>
        <input class="form-control money" id="pad_value" type="text" name="pad_value">
    </div>

    <div class="form-group">
        <label for="banks">Escolha o Banco:</label>
        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
            <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banks as $bank)
                    <tr>
                        <td><input type="radio" name="idBank" class="idBank" 
                            value="{{$bank->id}}"></td>
                        <td>{{$bank->name}}</td>
                        <td>{{$bank->description}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <center><input type="submit" class="btn btn-success btn-lg w-25" id="btnPayParcel" value="Salvar"></center>
</form>


@section('content')
    @if($sale->type==6)
        <div class="alert alert-info"><center><h2>Contrato Quitado!!!</h2></center></div>
    @endif

    @if($sale->type==3)
        <div class="alert alert-info"><center><h2>Contrato Cancelado!!!</h2></center></div>
    @endif

    @if($sale->type==4)
        <div class="alert alert-info"><center><h2>Contrato Finalizado!!!</h2></center></div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 buttons_area--left">
                    @if(Auth::user()->type==1)
                        <a href="{{route('suspendSale',['idSale'=>$sale->id])}}" 
                            msg="Tem certeza que deseja suspender essa venda??"  
                            class="btnActions btnActions--middle  btnDelete" title="suspender venda">
                            x
                        </a>
                    @endif
                    <div class="info__title">Informações da Venda - Contrato: {{$sale->contract_number}}</div>
                     
                </div>
                
                @if(Auth::user()->type !=5)
                <div class="col-6" style="text-align: right;">
                    <a href="{{$idJuridical==null?route('allSales'):route('seeJuridicalContact',['idJuridical'=>$idJuridical])}}" class="btn btn-success backButton">Voltar</a>
                </div>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Empreendimento</div>
                        <div class="input__info">
                            {{$sale->interprise_name}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Lot</div>
                        <div class="input__info">
                            {{$sale->lot_number}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Quadra</div>
                        <div class="input__info">
                            {{$sale->lot_block}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Valor</div>
                        <div class="input__info">
                            {{"R$ ".$sale->value}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Entrada</div>
                        <div class="input__info">
                            {{"R$ ".$sale->input}}
                        </div>
                    </div><br>

                    
                    <div class="input-info__group">
                        <div class="input-info__title">Desconto</div>
                        <div class="input__info">
                            {{"R$ ".$sale->descont}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Indice</div>
                        <div class="input__info">
                            {{$sale->index}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Parcelas Paga</div>
                        <div class="input__info">
                            {{$parcels_paid}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Parcelas Não Paga</div>
                        <div class="input__info">
                            {{$parcels_unpaid}}
                        </div>
                    </div><br>

                    
                    <div class="input-info__group">
                        <div class="input-info__title">Parcelas Atrasadas</div>
                        <div class="input__info">
                            {{$later_parcels}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Dia de vencimento</div>
                        <div class="input__info">
                            Dia {{$sale->expiration_day}}
                        </div>
                    </div><br>
                  
                </div>
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Saldo Devedor</div>
                        <div class="input__info">
                            {{"R$ ".$rest_value}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Valor Atrasado</div>
                        <div class="input__info">
                            {{"R$ ".$later_value}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Valor Pago</div>
                        <div class="input__info">
                            {{"R$ ".$paid_value}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Numero Parcelas</div>
                        <div class="input__info">
                            {{$sale->parcels}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Primeira parcela</div>
                        <div class="input__info">
                            {{date('d/m/Y',strtotime($sale->first_parcel))}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Status</div>
                        <div class="input__info">
                            @if($sale->type==1)
                                Proposta
                            @elseif($sale->type==2)    
                                Ativo
                            @elseif($sale->type==3)    
                                Cancelado
                            @elseif($sale->type==4)    
                                Finalizado  
                            @elseif($sale->type==5)    
                                Juridico                                  
                            @elseif($sale->type==6)    
                                Quitado
                            @endif
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Tipo Contrato</div>
                        <div class="input__info">
                            @if($sale->type==1)
                                Proposta
                            @elseif($sale->type==2)    
                                Ativo
                            @elseif($sale->type==3)    
                                Cancelado
                            @elseif($sale->type==4)    
                                Finalizado  
                            @elseif($sale->type==5)    
                                Juridico                                  
                            @elseif($sale->type==6)    
                                Quitado
                            @endif
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Data Proposta</div>
                        @if(Auth::user()->type!=1)
                        <div class="input__info">
                            {{date('d/m/Y',strtotime($sale->propose_date))}}
                        </div>
                        @endif
                        @if(Auth::user()->type==1)
                            <form method="POST" action="{{route('updateSale')}}" class="input-info__group" action="{}">
                                @csrf
                                <div class="input__info">
                                    <input type="hidden" name="idSale" value="{{$sale->id}}">
                                    <input type="date" name="propose_date" class="form-control" value="{{$sale->propose_date}}">  
                                </div>
                                <input type="submit" class="btn btn-info" value="Salvar"/>
                            </form>
                        @endif
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Data Inicio Contrato</div>
                        <div class="input__info">
                            {{$sale->begin_contract_date!=""?date('d/m/Y',strtotime($sale->begin_contract_date)):'Contrato não iniciado'}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Juros por ano</div>
                        <div class="input__info">
                            {{$sale->annual_rate}}%
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="info__title">Contatos</div>
        </div>
        @if($sale->type != 3 && $sale->type != 1)
        <div class="card-header buttons_area--left">
            @if(Auth::user()->type != 2 && Auth::user()->type != 6 && Auth::user()->type != 5)
            <div class="row w-100">
                <div class="col-6">
                    <div class="dropdown dropright">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Adicionar Contato
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>1,'allContact'=>2])}}">Diversos</a>
                            @if($sale->type !=4)
                                <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>6,'allContact'=>2])}}">Reemitir Parcelas</a>
                                <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>2,'allContact'=>2])}}">Alterar Proprietário</a>
                                <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>3,'allContact'=>2])}}">Alterar Dia Vencimento</a>
                                <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>7,'allContact'=>2])}}">Alterar Lote</a>
                                @if($number_now > 1)
                                    <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>4,'allContact'=>2])}}">Refinanciamento</a>
                                @endif
                                @if ($sale->type!=5)
                                    <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>8,'allContact'=>2])}}">Processo Judicial</a>
                                @endif
                                <a class="dropdown-item" href="{{route('addView',['idSale'=>$sale->id, 'type'=>5,'allContact'=>2])}}">Cancelar Venda</a>
                            @endif
                        </div>
                    </div>
                </div>
                <form class="col-6" style="display:flex; justify-content:flex-end;" method="get">
                    <div class="form-group mr-3">
                        <input type="checkbox" name="justResolved" {{isset($justResolved)?'checked':''}}> Ocultar Resolvidos
                    </div>
                    <div class="form-group">
                        <input class="btn btn-success" type="submit" value="Filtrar">
                    </div>
                </form>
            </div>
        
            @endif
        </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
                    <thead class="table table-dark">
                        <tr role="row">
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Responsável</th>
                            <th class="sorting" tabindex="0" rowspan="1" colspan="1">Cliente</th>
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
                        @foreach ($contact as $contactItem)
                            <tr>
                                <td>{{$contactItem->user_name}}</td>
                                <td>{{$contactItem->contact_client_name}}</td>
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
                                        @if(Auth::user()->type != 6)
                                            <a href="{{route('seeContact',['idContact'=>$contactItem->id])}}"  class="btnActions" title="ver mais">...</a>
                                        @endif
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
    
    
    <div class="card">
        <div class="card-header">
            <div class="info__title">Clientes da Venda</div>
        </div>
    @foreach ($clients as $key=>$client)
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-10 buttons_area--left">
                        @if(Auth::user()->type==1 || Auth::user()->type==3)
                        <a href="{{route('editClient',['idClient'=>$client->id])}}"  class="btnActions btnActions--middle btnActions--transparent" title="editar">
                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                        </a>
                        @endif
                        <div class="info__title w-75">
                            <div class="row">
                                <div class="col-6">Nome: {{$client->name==""?$client->company_name:$client->name}}</div>
                                <div class="col-6" style="text-align: right;">Cpf/Cpnj: {{$client->cpf!=""?$client->cpf:$client->cnpj}}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-2" style="display:flex; justify-content:flex-end;">
                        <div class="btnActions btnActions--middle btnActions--transparent btnSeeMoreClient">
                            <img src="{{asset('storage/general_icons/triangle.png')}}" width="100%" height="100%">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="display: none">
                <div class="row">
                    <div class="col-6">
                        <div class="input-info__group">
                            <div class="input-info__title">{{$client->kind_person==1?'Cpf':'Cnpj'}}</div>
                            <div class="input__info">
                                {{$client->kind_person==1?$client->cpf:$client->cnpj}}
                            </div>
                        </div><br>

                        <div class="input-info__group">
                            <div class="input-info__title">Data Nascimento</div>
                            <div class="input__info">
                                {{date('d/m/Y',strtotime($client->birth_date))}}
                            </div>
                        </div><br>

                        <div class="input-info__group">
                            <div class="input-info__title">Rg</div>
                            <div class="input__info">
                                {{$client->rg}}
                            </div>
                        </div><br>

                        <div class="input-info__group">
                            <div class="input-info__title">Estado Cívil</div>
                            <div class="input__info">
                                @if($client->marital_status==1)
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
                            <div class="input-info__title">Email</div>
                            <div class="input__info">
                                {{$client->email}}
                            </div>
                        </div><br>
                    </div>
                    
                    <div class="col-6">
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
                            <div class="input-info__title">Porcentagem do lote</div>
                            <div class="input__info">
                                {{$clients_porc[$key]}}%
                            </div>
                        </div><br>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>
    
    @if($sale->type != 1)
        <div class="card card-success">
            <div class="card-header">
                <div class="info__title info__title--white">
                    <center>Download do contrato</center>
                </div>
            </div>
            @foreach ($contracts as $part=>$contract)
                <div class="card-body">
                    <div class="downloadArea">
                        <div class="download__title">Clique pra fazer download do Contrato Parte {{$part+1}}</div>
                        <a href="{{asset('storage/'.$contract)}}" class="downloadLink" 
                            download="Contrato_Parte {{$part+1}}.pdf"></a>   
                    </div><br>
                </div>  
            @endforeach
        </div>
    @endif

    @if($sale->type == 1)
    @if(Auth::user()->type==3 || Auth::user()->type==1)
    <div class="card">
        <div class="card-body">
            <div class="downloadArea contract_area">
                <div class="download__title">Clique pra gerar o contrato</div>
                <a id="contract" href="{{route('contractSale',['id_sale'=>$sale->id])}}" target="_blank" class="downloadLink" ></a>   
              
            </div><br>
        </div>  
    </div>

    <div class="card" >
        <div class="card-header">
            <div class="info__title">
                <center>Inserir contrato com assinaturas</center>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('finishContractSale')}}" method="POST" 
                enctype="multipart/form-data" id="contractFinishForm">
                @csrf
                <input type="hidden" name="idSale" value="{{$sale->id}}">
                
                <div class="card-body">
                    <div class="uploadArea">
                        <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                        <div class="uploadAreaDrop">
                            <div class="uploadAreaDrop__img">
                                <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                            </div>
                            <div class="uploadAreaDrop__descriptionFile"></div>
                        </div>
                        <input name="contractFile[]" multiple="multiple" type="file" class="uploadInput"/>
                    </div>
                </div>

                <div class="card-footer">
                    <center><input type="submit" class="btn btn-success btn-lg w-25" 
                        id="btnFileContract" disabled value="Salvar"></center>
                </div>
            </form>
        </div> 
    </div>
    @endif
    @endif

    <div class="card">
        <div class="card-header">
            <form method="get" class="w-50 formFilter" style="display: flex;;">
                <h5 style=" line-height: 35px; margin-right:5px;">Filtrar Parcelas:</h5>
                <div class="form-group  w-25">
                    <input class="form-control" type="number" name="firstParcel" 
                        placeholder="1° Parcela" value="{{$firstParcel!=""?$firstParcel:'1'}}">
                </div>
                   
                <div class="form-group  w-25">
                    <input class="form-control" type="number" name="endParcel" 
                        placeholder="Ultima Parcela" value="{{$endParcel!=""?$endParcel:count($parcels)}}">
                </div>
                <input type="submit" style="display: none;">
            </form>
        </div>
        <div class="card-body">
            <h4>Parcelas</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline">
                    <thead class="table table-dark">
                        <td>Ações</td>
                        <th>Numero</th>
                        <th>Tipo</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Atraso</th>
                        <th>Multa</th>
                        <th>Juros</th>
                        <th>Reajuste</th>
                        <th>Acrescimo</th>
                        <th>Atualizado</th>
                        <th>Valor Pago</th>
                        <th>Data Pago</th>
                        <th>Boleto</th>
                        <th>Status</th>
                        
                    </thead>
                    <tbody>
                        @if($sale->type!=3)
                        <form method="GET" class="formFilter">
                            <tr>
                                <input type="hidden" name="first_parcel" value="{{$firstParcel!=""?$firstParcel:'1'}}">
                                <input type="hidden" name="end_parcel" value="{{$endParcel!=""?$endParcel:count($parcels)}}">
                                <td></td>
                                <td>
                                    <input class="form-control" type="number" name="num" value="{{$num}}">
                                </td>
                                <td>
                                    <select name="type" class="form-control selectFilter">
                                        <option value="0"></option>
                                        <option value="1">Financiamento</option>
                                        <option value="2">Taxa</option>
                                        <option value="6">Restituir</option>
                                    </select>
                                </td>
                                <td >
                                    <input class="form-control" type="date" name="date" value="{{$date}}">
                                </td>
                                <td></td>
                                <td>
                                    
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <select name="status" class="form-control selectFilter">
                                        <option value="0"></option>
                                        <option {{$status==1?'selected':''}} value="1">Paga</option>
                                        <option {{$status==2?'selected':''}} value="2">Não Paga</option>
                                        <option {{$status==3?'selected':''}} value="3">Atrasada</option>
                                    </select>
                                </td>
                                <input type="submit" style="display: none;">
                            <tr>
                        </form>       
                        @endif
                        @foreach ($parcels as $parcel)
                            <tr class={{$parcel->status==3?"table-danger":''}}
                                {{$parcel->type==2 || $parcel->type==3 ?"table-primary":''}}
                                {{$parcel->status==1?"table-success":''}}
                                {{$parcel->status==4?"table-warning":''}}
                                {{$parcel->type==5?"table-info":''}}>

                                <td class="buttons_area">
                                    @if(Auth::user()->type==1)
                                        <div class="btnActions fas fa-receipt btnPayParcel" title="Pagar" data-toggle="modal" 
                                        data-target="#modalAcoes" data-toggle="tooltip" 
                                        idParcel={{$parcel->id}} value={{$parcel->value}}></div>
                                    @endif
                                </td>
                                <td style="width: 50px">
                                    {{$parcel->num!=""?$parcel->num.'/'.$sale->parcels:$parcel->num_reissue}}
                                </td>
                                <td>
                                    @if ($parcel->type==1)
                                        Financiamento - {{$parcel->prefix}}
                                    @elseif($parcel->type==2)
                                        Taxas - {{$parcel->prefix}}
                                    @elseif($parcel->type==3)
                                        Refinanciamento - {{$parcel->prefix}}
                                    @elseif($parcel->type==5) 
                                        {{$parcel->prefix}}    
                                    @endif
                                </td>
                                <td>{{date('d/m/Y',strtotime($parcel->date))}}</td>
                                <td style="min-width: 90px">{{$parcel->value}}</td>
                                <td style="min-width: 80px;">{{!empty($parcel->late_days)?$parcel->late_days." dias":"0 dias"}}</td>
                                <td style="min-width: 100px">{{$parcel->late_fine!=""?$parcel->late_fine:'0,00 (0,0%)'}}</td>
                                <td style="min-width: 115px">{{!empty($parcel->late_rate)?$parcel->late_rate:'0,00 (0,0%)'}}</td>
                                <td style="min-width: 100px">{{!empty($parcel->reajust)?$parcel->reajust:'0,00 (0,0%)'}}</td>
                                
                                <td>{{!empty($parcel->added_value)?$parcel->added_value:'0,00'}}</td>
                                <td>{{!empty($parcel->updated_value)?$parcel->updated_value:'0,00'}}</td>
                                <td>
                                    @if($parcel->send_bankSlip==0)
                                        {{!empty($parcel->pad_value)?str_replace(['.','.'],['',','],$parcel->pad_value):'0,00'}}
                                    @else
                                        {{!empty($parcel->pad_value)?str_replace(['.','.'],[',',','],$parcel->pad_value):'0,00'}}
                                    @endif
                                </td>
                                <td>{{!empty($parcel->pad_date)?date('d/m/Y',strtotime($parcel->pad_date)):'Não Pago'}}</td>
                                <td>
                                    @if($parcel['bankSlip'])
                                        <a href="{{asset('storage/bankSlip/'.$parcel['bankSlip']->path)}}" download>Download Boleto</a>
                                    @endif
                                </td>
                                <td style="min-width: 150px;">
                                    @if ($parcel->status==1)
                                        Paga  
                                    @elseif($parcel->status==2)
                                        Não Paga
                                    @elseif($parcel->status==3) 
                                        Atrasada
                                    @elseif($parcel->status==4) 
                                        Reajustando...    
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @if($almostFinishSale && $sale->type !=6)
        <div class="card" >
            <div class="card-header">
                <div class="info__title">
                    <center>Inserir Termo de Quitação</center>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('almostFinishSale')}}" method="POST" 
                    enctype="multipart/form-data" id="almostFinishSaleForm">
                    @csrf
                    <input type="hidden" name="idSale" value="{{$sale->id}}">
                    
                    <div class="card-body">
                        <div class="uploadArea">
                            <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                            <div class="uploadAreaDrop">
                                <div class="uploadAreaDrop__img">
                                    <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                </div>
                                <div class="uploadAreaDrop__descriptionFile"></div>
                            </div>
                            <input name="almostFinishSale[]" multiple="multiple" type="file" class="uploadInput"/>
                        </div>
                    </div>
    
                    <div class="card-footer">
                        <center><input type="submit" class="btn btn-success btn-lg w-25" 
                            id="btnAlmostFinishSale" disabled value="Salvar"></center>
                    </div>
                </form>
            </div> 
        </div>
    @endif
    
    @if($sale->type == 6)
    <div class="card" >
        <div class="card-header">
            <div class="info__title">
                <center>Inserir Escritura</center>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('finishSale')}}" method="POST" 
                enctype="multipart/form-data" id="almostFinishSaleForm">
                @csrf
                <input type="hidden" name="idSale" value="{{$sale->id}}">
                
                <div class="card-body">
                    <div class="uploadArea">
                        <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                        <div class="uploadAreaDrop">
                            <div class="uploadAreaDrop__img">
                                <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                            </div>
                            <div class="uploadAreaDrop__descriptionFile"></div>
                        </div>
                        <input name="finishSaleFile[]" multiple="multiple" type="file" class="uploadInput"/>
                    </div>
                </div>

                <div class="card-footer">
                    <center><input type="submit" class="btn btn-success btn-lg w-25" 
                        id="btnFinishSaleFile" disabled value="Salvar"></center>
                </div>
            </form>
        </div> 
    </div>
    @endif

    @if($sale->type == 4)
        <div class="card card-success">
            <div class="card-header">
                <div class="info__title info__title--white">
                    <center>Download da escritura</center>
                </div>
            </div>
            @foreach ($finishFiles as $part=>$files)
                <div class="card-body">
                    <div class="downloadArea">
                        <div class="download__title">Clique pra fazer download da Escritura Parte {{$part+1}}</div>
                        <a href="{{asset('storage/'.$files)}}" class="downloadLink" 
                            download></a>   
                    </div><br>
                </div>  
            @endforeach
        </div>
    @endif

    @if($sale->type == 4 || $sale->type == 6)
        <div class="card card-success">
            <div class="card-header">
                <div class="info__title info__title--white">
                    <center>Download do Termo de Quitação</center>
                </div>
            </div>
            @foreach ($almostFinishFiles as $part=>$files)
                <div class="card-body">
                    <div class="downloadArea">
                        <div class="download__title">Clique pra fazer download do Termo Parte {{$part+1}}</div>
                        <a href="{{asset('storage/'.$files)}}" class="downloadLink" 
                            download></a>   
                    </div><br>
                </div>  
            @endforeach
        </div>
    @endif

  

    @section('js')
        <script>
            const URL_CONTRACT_EDIT="{{route('contractEditSale',['id_sale'=>$sale->id])}}";
        </script>
        @if($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    text: '{{$errors->first()}}',
                    customClass: 'mySweetalert',
                })
            </script>
        @endif 
        <script src="{{asset('js/seeSale.min.js')}}"></script>
    @endsection

@endsection
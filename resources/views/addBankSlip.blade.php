@extends('adminlte::page')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/bankSlip.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <div class="info__title">Criar Boleto</div>
                </div>
                <div class="col-6" style="text-align: right">
                    <a href="{{route('sendBankSlip')}}" class="btn btn-success backButton">Voltar</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="info__title">Escolha o contrato</div>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover dataTable dtr-inline">
                    <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Contrato</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Empreendimento</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Lote</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Quadra</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Clientes</th>
                    </thead>
                    <tbody>
                        <form method="GET" class="formFilter">
                            <tr>
                                <td></td>
                                <td>
                                    <input class="form-control" type="text" name="contract_number" autofocus
                                        value="{{$contract_number}}" placeholder="N° Contrato">
                                </td>
                                
                                <td>
                                    <select class="form-control selectFilter" name="interprise_name">
                                        <option value=""></option>
                                        @foreach ($interprises as $interprise)
                                            <option {{$interprise_name==$interprise->name?'selected':''}} 
                                                value="{{$interprise->name}}">{{$interprise->name}}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="lot_number" 
                                        value="{{$lot_number}}" placeholder="Lote">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="block" 
                                        value="{{$block}}" placeholder="Quadra">
                                </td>
                                
                                <td>
                                    <input class="form-control" type="text" name="client_name" 
                                        value="{{$client_name}}" placeholder="Nome ou CPF/CNPJ">
                                </td>

                                <input type="submit" style="display: none;">
                            </tr>
                        </form>
                    
                        @foreach ($sales as $key=>$sale)
                            <tr>
                                <td>
                                    <form class="formFilter">
                                        <input type="hidden" name="contract_number" value="{{$contract_number}}">
                                        <input type="hidden" name="interprise_name" value="{{$interprise_name}}">
                                        <input type="hidden" name="lot_number" value="{{$lot_number}}">
                                        <input type="hidden" name="block" value="{{$block}}">
                                        <input type="hidden" name="client_name" value="{{$client_name}}">
                                        
                                        <input type="radio" {{$radioSelectedIdSale==$sale->id?'checked':''}} 
                                            class="radioFilter radioSelectedIdSale" name="radioSelectedIdSale" value="{{$sale->id}}">
                                    </form>
                                </td>
                                <td><a style="color: blue;" href="{{route('seeSale',['idSale'=>$sale->id])}}">
                                    {{$sale->contract_number}}
                                </a></td>
                                <td style="min-width: 180px">{{$sale->interprise_name}}</td>
                                <td>{{$sale->lot_number}}</td>
                                <td>{{$sale->lot_block}}</td>
                                <td style="min-width: 200px;">
                                    @foreach ($clients[$key] as $client)
                                        {{$client}}<br>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>    
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="info__title">Escolha as parcelas para as remessas</div>
            </div>

            @if($radioSelectedIdSale != "")
                <div class="card-header">
                    <form method="get" class="w-50 formFilter" style="display: flex;;">
                        <input type="hidden" name="contract_number" value="{{$contract_number}}">
                        <input type="hidden" name="interprise_name" value="{{$interprise_name}}">
                        <input type="hidden" name="lot_number" value="{{$lot_number}}">
                        <input type="hidden" name="block" value="{{$block}}">
                        <input type="hidden" name="client_name" value="{{$client_name}}">
                        
                        <h5 style=" line-height: 35px; margin-right:5px;">Filtrar Parcelas:</h5>
                        <input type="hidden" name="radioSelectedIdSale" value="{{$radioSelectedIdSale}} ">
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
            @endif

            <div class="card-body">
                <table class="table table-bordered table-hover dataTable dtr-inline">
                    <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Num</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Tipo</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Vencimento</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Valor Atualizado</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Status</th>
                        
                    </thead>
                    <tbody>
                        @foreach ($parcels as $parcel)
                        <tr class={{$parcel->status==3?"table-danger":''}}
                            {{$parcel->type==2 || $parcel->type==3 ?"table-primary":''}}
                            {{$parcel->status==1?"table-success":''}}
                            {{$parcel->status==4?"table-warning":''}}
                            {{$parcel->type==5?"table-info":''}}>
                            <td>
                                <input type="checkbox" name="checkParcelId" class="checkParcelId" value="{{$parcel->id}}">
                            </td>
                            <td style="width: 50px">{{$parcel->num!=""?$parcel->num.'/'.$sale_info->parcels:$parcel->num_reissue}}</td>
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
                            <td>{{!empty($parcel->updated_value)?$parcel->updated_value:'0,00'}}</td>
                            <td>
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

        <div class="card">
            <div class="card-header">
                <div class="info__title">Informação Boleto</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <form action="{{route('generateBankSlip')}}" method="POST" id="formInfoBankSlip">
                            @csrf
                            <input type="hidden" name="id_sale" class="id_sale">
                            <input type="hidden" name="id_parcels[]" class="id_parcels">
                            <input type="hidden" name="typeBankSlip" class="typeBankSlip" value="1">
                            <input type="hidden" name="register" value="true">
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="descont">Desconto*</label>
                                        <input class="form-control w-50" type="number" step="any" 
                                            name="descont" value="10">
                                    </div>

                                    <div class="form-group">
                                        <label for="bank_interest_rate">Juros*</label>
                                        <input class="form-control w-50" type="number" step="any" 
                                            name="bank_interest_rate" value="1">
                                    </div>

                                    <div class="form-group">
                                        <label for="fine">Multa*</label>
                                        <input class="form-control w-50" type="number" step="any" 
                                            name="fine" value="2">
                                    </div>
                                    
                                </div>
                                
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="id_financial_accounts">Conta*</label>
                                        <select class="form-control" name="id_financial_accounts">
                                            <option value=""></option>
                                            @foreach ($financialAccounts as $accounts)
                                                <option value="{{$accounts->id}}">{{$accounts->name}}
                                                    {{" - ".$accounts->id_bank}}{{" - ".$accounts->bank_agency}}
                                                    {{" - ".$accounts->account}}{{$accounts->verifying_digit}}</option>     
                                            @endforeach
                                        </select>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="fine">Limite de atraso*</label>
                                        <input class="form-control w-50" type="number" step="any" 
                                            name="delay_limit" value="90">
                                    </div>

                                    <div class="form-group">
                                        <label for="fine">Observação*</label>
                                        <textarea class="form-control" rows="4" name="observation"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                        @if($reajust==false)
                        <div class="card">
                            <div class="card-header">
                                <div class="info__title">Ações Boleto</div>
                            </div> 
                           
                            <div class="card-body">
                                <div class="row">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-secondary btnTypeBankSlip" value="1">Boleto</button>
                                        <button type="button" class="btn btn-secondary btnTypeBankSlip" value="2">Carnê</button>
                                        <button type="button" id="viewBankSlipBtn" disabled class="btn btn-success">Visualizar</button>
                                        

                                        <form action="{{route('generateBankSlip')}}" id="formViewBankSlip" 
                                            method="POST" target="__blank">
                                            @csrf
                                            <input type="hidden" name="register" value="false">
                                            <input type="hidden" name="id_sale" class="id_sale">
                                            <input type="hidden" name="id_parcels[]" class="id_parcels">
                                            <input type="hidden" name="descont" class="descont" value="">
                                            <input type="hidden" name="bank_interest_rate" class="bank_interest_rate">
                                            <input type="hidden" name="fine" class="fine">
                                            <input type="hidden" name="id_financial_accounts" class="id_financial_accounts">
                                            <input type="hidden" name="delay_limit" class="delay_limit">
                                            <input type="hidden" name="observation" class="observation">
                                            <input type="hidden" name="typeBankSlip" class="typeBankSlip" value="1">
                                            <input type="hidden" name="id_user_permission" class="id_user_permission" value="1">

                                            
                                        </form>
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="card-footer">
                            <center><button  type="button" id="generateBankSlipBtn" class="btn btn-success w-50">Gerar Boletos</button></center>
                        </div>
                        @else 
                            <div class="alert alert-danger">Há parcelas a serem reajustadas mas tem índices faltando.
                                 <a href="{{route('home')}}">Clique para adicionar os indices faltando.</a></div>
                        @endif

                    </div>
                    <div class="col-6">
                        @if($sale_info != "")
                        <div class="card">
                            <div class="card-header">
                                <div class="info__title">Informações Venda</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="input-info__group">
                                            <div class="input-info__title">Numero Contrato</div>
                                            <div class="input__info">
                                                {{$sale_info->contract_number}}
                                            </div>
                                        </div><br>
        
                                        <div class="input-info__group">
                                            <div class="input-info__title">Empreendimento</div>
                                            <div class="input__info">
                                                {{$sale_info->interprise_name}}
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Lot</div>
                                            <div class="input__info">
                                                {{$sale_info->lot_number}}
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Valor</div>
                                            <div class="input__info">
                                                {{$sale_info->value}}
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Entrada</div>
                                            <div class="input__info">
                                                {{$sale_info->input}}
                                            </div>
                                        </div><br>
                    
                                        
                                        <div class="input-info__group">
                                            <div class="input-info__title">Desconto</div>
                                            <div class="input__info">
                                                {{$sale_info->descont}}
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Indice</div>
                                            <div class="input__info">
                                                {{$sale_info->index}}
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Status</div>
                                            <div class="input__info">
                                                @if ($sale_info->type==1)
                                                    Proposta
                                                @elseif($sale_info->type==2)
                                                    Ativo    
                                                @endif
                                            </div>
                                        </div><br>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-info__group">
                                            <div class="input-info__title">Juros por ano</div>
                                            <div class="input__info">
                                                {{$sale_info->annual_rate}}%
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Numero Parcelas</div>
                                            <div class="input__info">
                                                {{$sale_info->parcels}}
                                            </div>
                                        </div><br>
                                        
                                        <div class="input-info__group">
                                            <div class="input-info__title">Primeira parcela</div>
                                            <div class="input__info">
                                                {{date('d/m/Y',strtotime($sale_info->first_parcel))}}
                                            </div>
                                        </div><br>
                                        
                                        <div class="input-info__group">
                                            <div class="input-info__title">Parcelas Paga</div>
                                            <div class="input__info">
                                                {{$parcels_pad}}
                                            </div>
                                        </div><br>
                                        
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Dia de vencimento</div>
                                            <div class="input__info">
                                                Dia {{$sale_info->expiration_day}}
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Tipo Contrato</div>
                                            <div class="input__info">
                                                @if ($sale_info->type==1)
                                                    Proposta
                                                @elseif($sale_info->type==2)
                                                    Ativo  
                                                @elseif($sale_info->type==3)
                                                    Cancelado  
                                                @endif
                                            </div>
                                        </div><br>
                    
                                        <div class="input-info__group">
                                            <div class="input-info__title">Data Proposta</div>
                                            <div class="input__info">
                                                {{date('d/m/Y',strtotime($sale_info->propose_date))}}
                                            </div>
                                        </div><br>
        
                                        <div class="input-info__group">
                                            <div class="input-info__title">Data Começo Contrato</div>
                                            <div class="input__info">
                                                {{date('d/m/Y',strtotime($sale_info->begin_contract_date))}}
                                            </div>
                                        </div><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@section('js')
    <script src="{{asset('js/addBankSlip.min.js')}}"></script>
    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                text: '{{$errors->first()}}',
                customClass: 'mySweetalert',
            })
        </script>
    @endif 

@endsection


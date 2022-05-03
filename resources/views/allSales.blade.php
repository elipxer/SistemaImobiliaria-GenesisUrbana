@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header buttons_area--left">
            @if(Auth::user()->type==1 || Auth::user()->type==4)
                <a href="{{route('addSale')}}" title="adicionar venda" class="btnActions btnActions--middle">+</a>    
            @endif
            <div class="info__title info__title--without-margin-top">Vendas</div>
        </div>
        <div class="card-header">
            <form method="get" class="formOrder">
                <input type="hidden" name="contract_number" value="{{$contract_number}}">
                <input type="hidden" name="orderContract" value="{{$orderContract}}">
                <input type="hidden" name="orderLot" value="{{$orderLot}}">
                <input type="hidden" name="orderBlock" value="{{$orderBlock}}">
                <input type="hidden" name="orderInterprise" value="{{$orderInterprise}}">
                <input type="hidden" name="interprise_name" value="{{$interprise_name}}">
                <input type="hidden" name="lot_number" value="{{$lot_number}}">
                <input type="hidden" name="block"  value="{{$block}}">
                <input type="hidden" name="date" value="{{$date}}">
                <input type="hidden" name="client_name" value="{{$client_name}}">
                <input type="hidden" name="type" value="{{$type}}">
                
                <div class="info__title">Ordenar por:</div><br>
                <div class="form-group d-flex ml-2">
                    <button class="btn m-1 btnOrderBy {{$orderContract == 0 ? 'btn-success':'btn-primary'}}" order="1">Contrato</button>
                    <button class="btn m-1 btnOrderBy {{$orderLot == 0 ? 'btn-success':'btn-primary'}}" order="2">Lotes</button>
                    <button class="btn m-1 btnOrderBy {{$orderBlock == 0 ? 'btn-success':'btn-primary'}}" order="3">Quadras</button>
                    <button class="btn m-1 btnOrderBy {{$orderInterprise == 0 ? 'btn-success':'btn-primary'}}" order="4">Empreendimento</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable dtr-inline">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Acoes</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Contrato</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Empreendimento</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Lote</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Quadra</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Data (Contrato)</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Clientes</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Status</th>
                    
                </thead>
                <tbody>
                    <form method="get" class="formFilter">
                        @if(Auth::user()->type!=6)
                        <tr>
                            <td></td>
                            <td>
                                <input class="form-control" type="text" name="contract_number" 
                                value="{{$contract_number}}" placeholder="Contrato">
                            </td>
                            <td>
                                <select class="form-control selectFilter" name="interprise_name">
                                    <option value=""></option>
                                    @foreach ($interprises as $interprise)
                                        <option {{$interprise_name==$interprise->name?'selected':''}} value="{{$interprise->name}}">{{$interprise->name}}</option>
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
                                <input class="form-control" type="date" name="date" 
                                    value="{{$date}}">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="client_name" 
                                    value="{{$client_name}}" placeholder="Nome ou CPF/CNPJ">
                            </td>

                            <td>
                                <select class="form-control selectFilter" name="type">
                                    <option value=""></option>
                                    <option {{$type==1?'selected':''}} value="1">Proposta</option>
                                    <option {{$type==2?'selected':''}} value="2">Ativo</option>
                                    <option {{$type==3?'selected':''}} value="3">Cancelado</option>
                                    <option {{$type==4?'selected':''}} value="4">Finalizado</option>
                                    <option {{$type==5?'selected':''}} value="5">Juridico</option>
                                    <option {{$type==6?'selected':''}} value="6">Quitado</option>
                                   
                                </select>
                            </td>
                            <input type="submit" style="display: none;">
                        </tr>
                        @endif
                    </form>
                
                    @foreach ($sales as $key=>$sale)
                        <tr>
                            <td class="buttons_area">
                                <a href="{{route('seeSale',['idSale'=>$sale->id])}}" class="btnActions" title="ver mais">
                                        ...
                                </a>
                                 
                                @if(Auth::user()->type==1)

                                    <a href="{{route('suspendSale',['idSale'=>$sale->id])}}" 
                                        msg="Tem certeza que deseja deletar essa venda?
                                            Tudo relacionado a essa venda será deletado"  
                                        class="btnActions btnDelete" title="suspender venda">
                                        x
                                    </a>
                                @endif
                            </td>
                            <td>{{$sale->contract_number}}</td>
                            <td style="min-width: 180px">{{$sale->interprise_name}}</td>
                            <td>{{$sale->lot_number}}</td>
                            <td>{{$sale->lot_block}}</td>
                            <td>
                                {{$sale->propose_date!=""?date('d/m/Y',strtotime($sale->propose_date)).' (Proposta)':''}}<br>
                                {{$sale->begin_contract_date!=""?date('d/m/Y',strtotime($sale->begin_contract_date)).' (Contrato)':'Contrato Não iniciado'}}<br>
                            </td>   
                            <td style="min-width: 200px;">
                                @foreach ($clients[$key] as $client)
                                    {{$client}}<br>
                                @endforeach
                            </td>
                            <td style="min-width: 150px;">
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
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>    
            </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <div>Icons made by <a href="https://www.flaticon.com/authors/chanut" title="Chanut">Chanut</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
@endsection

@section('js')
    <script src="{{asset('js/allSales.min.js')}}"></script>
@endsection
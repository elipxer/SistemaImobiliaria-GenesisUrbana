@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <div class="info__title">Escolha um lote:</div>
                    </div>
                    <div class="col-6" style="text-align: right;">
                        <a href="{{route('allSales')}}" class="btn btn-success backButton">Voltar</a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                    <thead class="table table-dark">
                    <tr role="row">
                        <th></th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Empreendimento</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Lote</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Quadra</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Area</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Observação</th>
                    </thead>
                    <tbody>
                        <tr>
                            <form class="formFiltro">
                                <td></td>
                                
                                <td>
                                    <input class="form-control" type="text" name="interprise_name" 
                                        placeholder="Nome Empreendimento" value="{{$interprise_name}}">
                                </td>
                                
                                <td>
                                    <input class="form-control" type="number" name="lot_number" 
                                        placeholder="Lote" value="{{$lot_number}}">
                                </td>
                                
                                <td>
                                    <input class="form-control" type="text" name="block" 
                                        placeholder="Quadra" value="{{$block}}">
                                </td>

                                <td>
                                    <input class="form-control" type="number" step="any" name="area" 
                                        placeholder="Area" value="{{$area}}">
                                </td>

                                <td>
                                    <input class="form-control" type="text" name="observation" 
                                        placeholder="Observação" value="{{$observation}}">
                                </td>
                                <input type="submit" style="display: none;">
                            </form>
                        </tr>
                        @foreach ($lots as $lot)
                            <tr>
                                <td>
                                    <input type="radio" name="interpriseCheck" value="{{$lot->idInterprise}}">
                                    <input type="hidden" name="lotCheck" value="{{$lot->id}}">
                                </td>
                                <td>{{$lot->interprise_name}}</td>
                                <td>{{$lot->lot_number}}</td>
                                <td>{{$lot->block}}</td>
                                <td>{{$lot->area}}</td>
                                <td>{{$lot->interprise_observation}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="info__title">Informações da venda:</div>
            </div>
            <div class="card-body">
                <form method="post" id="formSale" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="client_payment_id"  id="client_payment_id">
                    <input type="hidden" name="id_interprise"  id="id_interprise">
                    <input type="hidden" name="id_lot"  id="id_lot" >
                    <input type="hidden" name="id_clients[]"  id="id_clients">
                    <input type="hidden" name="id_clients_porc[]"  id="id_clients_porc">
                    <input type="file" name="contractFile"  id="contractFile" style="display: none;">
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">   
                            <label for="contract">Contrato*</label>
                                <input class="form-control w-50" type="text" name="contract_number"  style="width: 200px;" value="{{old('contract')}}">
                            </div>
                            
                            <div class="form-group">
                                <label for="contract">Lote*</label>
                                <div class="form-control w-50" id="lot_number_input">{{old('lot_number_input')}}</div>
                            </div>

                            <div class="form-group">
                                <label for="value">Valor*</label>
                                <input class="form-control money w-50" type="text" value="{{old('value')}}" id="value" readOnly >
                            </div>
                            
                            <div class="form-group">
                                <label for="value">Valor Futuro</label>
                                <input class="form-control money w-50" type="text" name="future_value" readOnly value="{{old('future_value')}}">
                            </div>

                            <div class="form-group">
                                <label for="index">Indice*</label>
                                <select class="form-control w-50" name="index">
                                    @foreach ($index as $indexItem)
                                    <option {{old('index')==$indexItem->id?'selected':''}} value="{{$indexItem->id}}">{{$indexItem->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="form-group">
                                <label for="contract">Entrada</label>
                                <input class="form-control money w-50" type="text" name="input" 
                                    id="input" value="{{old('input')==null?'0,00':old('input')}}" readOnly >
                            </div>
                            
                            <div class="form-group">
                                <label for="contract">Dia Vencimento*</label>
                                <input class="form-control w-50" type="number" 
                                    name="expiration_day" value="{{old('expiration_day')}}">
                            </div>
                        </div>
                    
                        <div class="col-6">
                            <div class="form-group">
                                <label for="contract">Desconto</label>
                                <input class="form-control money w-50" type="text" name="descont" 
                                    id="descont" value="{{old('descont')==null?'0,00':old('descont')}}" readOnly>
                            </div>

                            <div class="form-group">
                                <label for="contract">Primeira Parcela*</label>
                                <input class="form-control w-50" type="date" name="first_parcel" value="{{old('first_parcel')}}">
                            </div>
                        
                            <div class="form-group">
                                <label for="contract">Saldo*</label>
                                <input class="form-control money w-50" type="text" name="value" readOnly {{old('value')}}>
                            </div>
                            
                            <div class="form-group">
                                <label for="contract">Parcelas*</label>
                                <input class="form-control w-50" type="text" name="parcels" readOnly value="{{old('parcels')==null?'180':old('parcels')}}" id="parcels">
                            </div>
                            
                            <div class="form-group">
                                <label for="contract">Valor das parcelas*</label>
                                <input class="form-control money w-50" type="text" name="value_parcel" id="value_parcel" 
                                    readOnly value="{{old('value')}}">
                            </div>
                            
                            <div class="form-group">
                                <label for="contract">Taxa anual*</label>
                                <input class="form-control w-25" type="number" name="annual_rate" 
                                        value="6">
                            </div>

                            <div class="form-group">
                                <label for="contract">Variação Minima*</label>
                                <input class="form-control w-25" type="number" name="minimum_variation" 
                                        value="10">
                            </div>
                        </div>
                    </div>

                    <input type="submit" id="submitSale" style="display: none;">
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="info__title">Escolha os clientes:</div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <input class="form-control" type="text" placeholder="Digite o nome ou cpf/cnpj do cliente" id="clientName">
                    </div>
                </div><br>

                <div class="card" id="clientArea" style="display: none">
                    <table class="table table-bordered table-hover dataTable dtr-inline" id="clientAreaTable" role="grid" aria-describedby="example2_info">
                        <thead class="table table-dark">
                        <tr role="row">
                            <th></th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cpf</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cnpj</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Rg</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
                        </tr>   
                        </thead>
                        <tbody>
                            <tr class="clientLineTable" style="display: none">
                                <td>
                                    <input type="checkbox" name="clientCheck" value="">
                                </td>
                                <td></td>
                                <td></td>            
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="info__title">Clientes Escolhidos</div>
                </div>

                <div class="card-body" id="card_chooseClients">
                    <div class="row chooseClientRow form-control" style="display: none; margin-left:10px">
                        <input type="hidden">
                        <div class="col-6"> </div>
                        <div class="col-4"> </div>
                        <div class="col-2">
                            <div class="btnActions btnActions--transparent 
                                btnDelete btnDelete-chooseClientRow">
                                <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="info__title">Escolha o cliente para emitir o pagamento</div>
                </div>
                <div class="card-body">
                    <div class="col-4">
                        <select class="form-control" name="client_payment" id="client_payment">
                            
                        </select>
                    </div>
                </div>
            </div>

            <div class="card" >
                <div class="card-header">
                    <div class="info__title">Porcentagens dos clientes</div>
                </div>

                <div class="card-body" id="card_porc">
                    <div class="row clientLinePorc" style="display: none;">
                        <div class="form-control nameClient" style="width:60%; overflow:hidden"></div>
                        <input type="hidden" class="idClient" value="">
                        <input type="number" step="any" class="form-control porcValue" value="" style="width: 90px">
                        <label style="margin-left:5px;font-size: 25px; font-family:'Source Sans Pro';">%</label>
                    </div>
                </div>
            </div>
        </div>

        <center><button class="btn btn-outline-success btn-md" id="addSale">Adicionar Venda</button></center><br> 

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
        
        <script>
            const CLIENT_URL="{{route('getAllClients')}}";
            const LOT_URL="{{route('getLot')}}";
            const VERIFY_CONTRACT_URL="{{route('verifyContractNumber')}}";
        </script>
        <script src="{{asset('js/sale.min.js')}}"></script>
    @endsection
@endsection
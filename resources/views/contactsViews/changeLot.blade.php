@extends('adminlte::page')
@extends('layouts/searchSale')


@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <h4>Mudança de Lote</h4>
        </div>
        <div class="col-6" style="text-align: right">
            <a href="{{$allContact==2?route('seeSale',['idSale'=>$sale->id]):route('allContact')}}" class="btn btn-success backButton">Voltar</a>   
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Escolha o lote disponivel</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline" id="table_interprise">
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
                        <form class="formFiltro" method="GET">
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
                                <input type="hidden" name="lot_value" value="{{$lot->present_value}}">
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
    
    <form action="{{route('addChangeLotContact')}}" id="addChangeLotContact"  method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_sale" value={{$idSale}}>
        <input type="hidden" name="id_lot" id="id_lot" value="">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="info__title">Informações da troca de lote</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="old_lot">Lote atual</label>
                                    <input class="form-control" type="text" readonly
                                        name="old_lot" value="{{$actual_interprise->name}} - {{$actual_lot->name}}">
                                </div>

                                <div class="form-group w-50">
                                    <label for="old_lot_value">Valor do lote atual</label>
                                    <input class="form-control money" type="text" readonly 
                                        name="old_lot_value" id="old_lot_value" value="{{$actual_lot->present_value}}">
                                </div>

                                <div class="form-group">
                                    <label for="lot_selected">Lote selecionado</label>
                                    <input class="form-control" type="text" readonly 
                                        name="lot_selected" id="lot_selected" value="">
                                </div>

                                <div class="form-group">
                                    <label for="value_lot_selected">Valor do lote selecionado*</label>
                                    <input class="form-control money w-50" type="text" readonly 
                                        name="value_lot_selected" id="value_lot_selected" value="">
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="total_parcels_pad">Soma dos valores pagos</label>
                                    <input class="form-control money w-50" type="text" readonly 
                                        name="total_parcels_pad" id="total_parcels_pad" value="{{$total_parcels_pad}}">
                                </div>

                                <div class="form-group">
                                    <label for="new_value_pay">Novo Saldo para pagar</label>
                                    <input class="form-control money w-75" type="text" readonly 
                                        name="new_value_pay" id="new_value_pay" value="">
                                </div>

                                <div class="form-group">
                                    <label for="number_parcels_pad">Quantidade de parcelas pagas</label>
                                    <input class="form-control w-25" type="number" 
                                        name="number_parcels_pad" value="{{$number_parcels_pad}}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="number_parcels_to_pay">Quantidade de parcelas a pagar</label>
                                    <input class="form-control  w-25" type="number" 
                                        name="number_parcels_to_pay" value="{{$number_parcels_to_pay}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="info__title">Informações Contato</div>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="contact_client_name">Pessoa*</label>
                                    <input class="form-control" type="text" name="contact_client_name" 
                                        value="{{old('contact_client_name')}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="where">Via*</label>
                                    <input class="form-control" type="text" name="where" value="{{old('where')}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="deadline">Prazo*</label>
                                    <input class="form-control" type="date" name="deadline" min="{{date('Y-m-d')}}" value="{{old('deadline')}}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject_matter">Assunto*</label>
                            <textarea class="form-control" type="date" name="subject_matter" rows="5">{{old('subject_matter')}}</textarea>
                        </div>
                    </div>
                   
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="info__title">Adicione algum arquivo:</div>
                    </div>
                    <div class="card-body">
                        <div class="uploadArea w-100">
                            <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                            <div class="uploadAreaDrop">
                                <div class="uploadAreaDrop__img">
                                    <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                </div>
                                <div class="uploadAreaDrop__descriptionFile"></div>
                            </div>
                            <input name="contactFile" type="file" class="uploadInput"/>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="info__title">
                            Usuario responsavel pelo contato:
                        </div>
                    </div>
                    <div class="card-body overflow-hidden">
                        <table class="table table-responsive table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                            <thead class="table table-dark">
                            <tr role="row">
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Tipo</th>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr role="row" class="odd">
                                        <td>
                                            <input type="radio" name="id_user" value="{{$user->id}}">
                                        </td>
                                        <td>
                                            <div class="mini-photo_user">
                                                <img src="{{asset('storage/users/'.$user->photo)}}" alt="" 
                                                    width="100%" height="100%">
                                            </div>
                                        </td>
                                        <td tabindex="0" class="sorting_1">{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>
                                            @if ($user->type==1)
                                                Administrador
                                            @elseif($user->type==2)
                                                Gestão
                                            @elseif($user->type==3)
                                                Operação
                                            @elseif($user->type==4)
                                                Comercialização
                                            @elseif($user->type==5)
                                                Júridico
                                            @elseif($user->type==6)
                                                Cliente
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>
                </div>

                <div class="card" >
                    <div class="card-header">
                        <div class="info__title">Administrativo:</div>
                    </div>
                     <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group w-75">
                                    <label for="value_after_change">Saldo após a mudança</label>
                                    <input class="form-control money" type="text" name="value_after_change" 
                                       id="value_after_change" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="contact_client_name">Confirmação da taxa de financiamento</label>
                                    <input class="form-control w-50" type="number" step="any" disabled
                                        name="rate_financing" id="rate_financing" value="0.5">
                                </div>

                                <div class="form-group">
                                    <label for="contact_client_name">Primeira Parcela</label>
                                    <input class="form-control w-50" type="date" value="{{$firstParcelDate}}"
                                        name="first_parcel" id="first_parcel">
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="number_parcel_change_lot">Confirmação de parcelas</label>
                                    <input class="form-control w-25" type="number" step="any" disabled
                                        name="number_parcel_change_lot" id="number_parcel_change_lot" 
                                        value="{{$number_parcels_to_pay}}">
                                </div>

                                <div class="form-group">
                                    <label for="value_parcel_change_lot">Valor parcela após mundança</label>
                                    <input class="form-control w-75 money" type="text" step="any" 
                                        name="value_parcel_change_lot" id="value_parcel_change_lot" readonly>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="card">
                    <div class="card-footer">
                        <center><input type="submit" class="btn btn-success w-25" value="Salvar" id="btnInput"></center>
                    </div>
                </div>
            </div>
            <div class="col-6">
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
                                        {{$sale->contract_number}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-75">
                                    <div class="input-info__title">Empreendimento</div>
                                    <div class="input__info" style="height: auto;">
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
                                    <div class="input-info__title">Valor</div>
                                    <div class="input__info">
                                        {{$sale->value}}
                                    </div>
                                </div><br>
            
                                <div class="input-info__group">
                                    <div class="input-info__title">Entrada</div>
                                    <div class="input__info">
                                        {{$sale->input}}
                                    </div>
                                </div><br>
            
                                
                                <div class="input-info__group">
                                    <div class="input-info__title">Desconto</div>
                                    <div class="input__info">
                                        {{$sale->descont}}
                                    </div>
                                </div><br>
            
                                <div class="input-info__group">
                                    <div class="input-info__title">Indice</div>
                                    <div class="input__info">
                                        {{$indexName}}
                                    </div>
                                </div><br>
            
                                <div class="input-info__group">
                                    <div class="input-info__title">Status</div>
                                    <div class="input__info">
                                        @if ($sale->type==1)
                                            Proposta
                                        @elseif($sale->type==2)
                                            Ativo  
                                        @elseif($sale->type==3)
                                            Cancelado  
                                        @endif
                                    </div>
                                </div><br>
                            </div>
                            <div class="col-6">
                                <div class="input-info__group">
                                    <div class="input-info__title">Juros por ano</div>
                                    <div class="input__info">
                                        {{$sale->annual_rate}}%
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
                                    <div class="input-info__title">Parcelas Paga</div>
                                    <div class="input__info">
                                        {{$parcels_pad}}
                                    </div>
                                </div><br>
                                
                                <div class="input-info__group">
                                    <div class="input-info__title">Num Parcelas Paga</div>
                                    <div class="input__info">
                                        {{$number_parcels_pad}}
                                    </div>
                                </div><br>
                                
                                
                                <div class="input-info__group">
                                    <div class="input-info__title">Dia de vencimento</div>
                                    <div class="input__info">
                                        Dia {{$sale->expiration_day}}
                                    </div>
                                </div><br>
            
                                <div class="input-info__group">
                                    <div class="input-info__title">Tipo Contrato</div>
                                    <div class="input__info">
                                        @if ($sale->type==1)
                                            Proposta
                                        @elseif($sale->type==2)
                                            Ativo  
                                        @elseif($sale->type==3)
                                            Cancelado  
                                        @endif
                                    </div>
                                </div><br>
            
                                <div class="input-info__group">
                                    <div class="input-info__title">Data Proposta</div>
                                    <div class="input__info">
                                        {{date('d/m/Y',strtotime($sale->propose_date))}}
                                    </div>
                                </div><br>

                                <div class="input-info__group">
                                    <div class="input-info__title">Data Começo Contrato</div>
                                    <div class="input__info">
                                        {{date('d/m/Y',strtotime($sale->begin_contract_date))}}
                                    </div>
                                </div><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
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
        <script src="{{asset('js/contactChangeLot.min.js')}}"></script>
    @endsection
    
@endsection
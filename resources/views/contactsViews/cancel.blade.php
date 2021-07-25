@extends('adminlte::page')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <h4>Cancelar Contato</h4>
        </div>
        <div class="col-6" style="text-align: right">
            <a href="{{$allContact==2?route('seeSale',['idSale'=>$sale->id]):route('allContact')}}" class="btn btn-success backButton">Voltar</a>   
        </div>
    </div>
    
    <form action="{{route('addCancelContact')}}"  method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_sale" value={{$idSale}}>
        <input type="hidden" name="sumIndexRate" id="sumIndexRate" value="{{$sumIndexRate}}">
        <div class="card">
            <div class="card-header">
                <h4>Informações Cancelamento</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="sale_commission">Valor Futuro*</label>
                            <input class="form-control w-25 money" type="text" 
                            name="future_value" id="future_value" value="{{$future_value}}" readonly>
                        </div>

                        <div class="form-group">   
                            <label for="sale_commission_rate">Taxa comissão de venda*</label>
                            <input class="form-control w-25" type="number" step="any" 
                            name="sale_commission_rate" id="sale_commission_rate" value="6">
                        </div>
                        
                        <div class="form-group">
                            <label for="administrative_expenses">Taxa contratual das despesas administrativas*</label>
                            <input  class="form-control w-25" type="text" 
                                name="administrative_expenses" value="10" id="administrative_expenses">
                        </div>

                        <div class="form-group">
                            <label for="contract">Débitos de IPTU em atraso*</label>
                            <input class="form-control w-50 money" type="text" 
                                name="iptu_debits" id="iptu_debits" value="0,00">
                        </div>

                        <div class="form-group">
                            <label for="contract">Outros débitos em atraso*</label>
                            <input class="form-control w-50 money" type="text" 
                                name="others_debits" id="others_debits" value="0,00">
                        </div>
                        
                    </div>
                
                    <div class="col-6">
                        <div class="form-group">
                            <label for="contract">Despesas administrativas*</label>
                            <input class="form-control w-50 money" type="text" 
                                name="administrative_debits" id="administrative_debits" value="{{$administrative_expenses}}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="contract">Especificação do debito*</label>
                            <input class="form-control w-50" type="text" 
                                name="specification_debits">
                        </div>

                        <div class="form-group">
                            <label for="total_parcels_pad">Soma dos valores do financiamento (pago) *</label>
                            <input class="form-control w-25 money" type="text" 
                            name="total_parcels_pad" value="{{$total_parcels_pad}}" id="total_parcels_pad" readonly>
                        </div>

                        <div class="form-group">
                            <label for="contract">Comissão da venda*</label>
                            <input class="form-control w-25" type="text" 
                                name="sale_commission" value="{{$sale_commission_value}}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="contract">Comissão da venda corrigida*</label>
                            <input class="form-control w-25" type="text" 
                                name="sale_commission_adjusted" value="{{$sale_commission_value_adjusted}}" 
                                readonly id="sale_commission_adjusted">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
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
                        <div class="info__title">
                            Usuario responsavel pelo contato:
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
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
                        <div class="alert alert-warning" style="display: none;" id="alert">
                            O cliente não tem nada a receber
                        </div>
                        <div class="row" id="card_administrative">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="fine_contact">Valor a restituir</label>
                                    <input class="form-control money" type="text" name="return_value" id="return_value"
                                        value="return_value">
                                </div>
                            </div>
                            
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="number_parcels_return">Primeira parcela</label>
                                    <input class="form-control" type="date" id="deadline" name="first_parcel_return" 
                                        value="{{$first_parcel}}" readonly>
                                </div>
                            </div>
                            
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="value_parcel_return">Mensalidade</label>
                                    <input class="form-control money" type="text" name="value_parcel_return" 
                                        value="" id="value_parcel_return" readonly>
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="number_parcel_contact">Num Parcelas</label>
                                    <input class="form-control" type="int" name="number_parcels_return" 
                                        value="{{$number_parcels_pad}}" id="number_parcels_return">
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
                                        {{$sale->index}}
                                    </div>
                                </div><br>
            
                                <div class="input-info__group">
                                    <div class="input-info__title">Status</div>
                                    <div class="input__info">
                                        @if ($sale->type==1)
                                            Proposta
                                        @elseif($sale->type==2)
                                            Ativo    
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
    <a href="" target="blank"></a>
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
            var urlSale='{{route('seeSale',['idSale'=>$idSale])}}';
        </script>    
        <script src="{{asset('js/contactCancel.min.js')}}"></script>
    @endsection
    
@endsection
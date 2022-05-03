@extends('adminlte::page')
@extends('layouts/modal')
@extends('layouts/searchSale')


@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <h4>Adicionar Contato (Mudar Proprietário)</h4>
        </div>
        <div class="col-6" style="text-align: right">
            <a href="{{$allContact==2?route('seeSale',['idSale'=>$sale->id]):route('allContact')}}"
                class="btn btn-success backButton">
                Voltar
           </a>   
        </div>
    </div>

    <div id="clientSearchArea" style="display: none;">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <input class="form-control" type="text" id="clientName" placeholder="Digite o nome ou o cpf/cpnj">
                </div>
            </div>

            <div class="col-6">

            </div>
        </div>

        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" id="clientAreaTable">
            <thead class="table table-dark">
            <tr role="row">
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Cpf/Cnpj</th>
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
            </tr>
            </thead>
            <tbody>
                <tr class="clientLineTable" style="display: none;">
                    <td>
                        <input type="checkbox" name="clientCheck">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        
    </div>
    
    <form action="{{route('addChangeOwnerContact')}}" id="changeOwnerForm" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_sale" value={{$idSale}}>
        <input type="hidden" name="clients[]" id="clients" value={{$clientsIds}}>
        <input type="hidden" name="client_payment_id" value="{{$client_payment->id}}">
        <input type="hidden" name="id_clients_porc[]" value="{{$clients_porc_sale}}"  id="id_clients_porc">

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
                    <div class="card-header buttons_area--left">
                        <a title="adicionar cliente" class="btnActions"  data-toggle="modal" 
                            data-target="#modalAcoes" data-toggle="tooltip" id="btnAddClient">+</a>
                        <div class="info__title">Clientes</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" id="tableOwner">
                            <thead class="table table-dark">
                            <tr role="row">
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Cpf/Cnpj</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Porcentagem</th>
                                <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $key=>$client)
                                    <tr role="row" class="tableOwneLine" id="{{$client->id}}">
                                        <td>
                                            @if(!empty($client->name))
                                                {{$client->name}}
                                            @else
                                                {{$client->company_name}}
                                            @endif
                                            
                                       </td>
                                       <td>
                                            @if(!empty($client->cpf))
                                                {{$client->cpf}}
                                            @else
                                                {{$client->cnpj}}
                                            @endif    
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control porcValue" value="{{$clients_porc[$key]}}" style="width: 70px;">
                                        </td>
                                        <td class="buttons_area">
                                            <a class="btnActions btnActions--transparent btnDeleteClient" id="{{$client->id}}">
                                                <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="info__title">Cliente para emitir o pagamento</div>
                        </div>
                        <div class="card-body">
                            <select class="form-control" name="client_payment" id="client_payment">
                                @foreach ($clients as $client)
                                    <option {{$client->id==$client_payment->id?'selected':''}} value="{{$client->id}}">Nome: {{$client->name}}; Cpf/Cnpj 
                                        @if(!empty($client->cpf))
                                            {{$client->cpf}}
                                        @else
                                            {{$client->cnpj}}
                                        @endif 
                                    </option>
                                @endforeach
                            </select>
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
                                            <input type="checkbox" name="id_user" value="{{$user->id}}">
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
   
                <div class="card">
                    <div class="card-footer">
                        <center><input type="submit" class="btn btn-success w-25" class="inputOptionFineBtn" value="Salvar" id="btnInput"></center>
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
        </script>
        <script src="{{asset('js/contactChangeOwner.min.js')}}"></script>
        <script src="{{asset('js/optionContactFine.min.js')}}"></script>
    @endsection
    
@endsection
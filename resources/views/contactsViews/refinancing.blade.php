@extends('adminlte::page')
@extends('layouts/modal')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

<form action="{{route('addIndexValueNotification')}}" method="post" class="formIndexEmpty" style="display: none;">
    @csrf
    <input type="hidden" name="idIndex" value="">
    <input type="hidden" name="idSale" value="">
    <input type="hidden" name="refinancing" value="{{$idSale}}">

    <table class="table table-bordered table-hover dataTable dtr-inline" id="tableIndexEmpty" role="grid">
        <thead class="table">
            <tr>
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Valor do indice</th>
                <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Mes do indice</th>
            </tr>
        </thead>
        <tbody>
            <tr class="tableIndexEmpty_line" style="display: none;">
                <td>
                    <input type="number" class="form-control" step="any" name="index_value[]">
                </td>
                <td>
                    <input type="text" class="form-control" step="any" name="month[]" readonly>
                </td>
            </tr>
        </tbody>   
    </table>   
</form>

@section('content')
    @if ($refinancing_failed==true)
        <div class="row">
            <div class="col-6">
                <div class="card card-warning collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Há indices que não foram cadastrados para fazer esse refinanciamento</h3>
        
                        <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                          </button>
                        </div>
                    </div>
                <div class="card-body" style="display: none;">
                    <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                        <thead class="table">
                            <tr role="row">
                                <th>Contrato</th>
                                <th>Parcelas a serem reajustadas</th>
                                <th>Ações</th>
                            </tr>    
                        </thead>
                        @foreach ($notifications_index as $notificationItem)
                            <tr>
                                <td>
                                    <a style="color: blue;" href="{{route('seeSale',['idSale'=>$notificationItem->id_sale])}}">
                                        {{$notificationItem->contractNumber}}
                                    </a>
                                </td>
                                <td>{{$notificationItem->parcels_readjust}}</td>
                                <td>
                                    <center>
                                        <a idSale="{{$notificationItem->id_sale}}" idIndex="{{$notificationItem->index}}" month_index_empty="{{$notificationItem->month_index_empty}}"class="btnActions btnIndexEmpty"  
                                            data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip" 
                                            title="ver mais">...</a>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @else
    <div class="row">
       <div class="col-6">
            <h4>Adicionar Contato (Refinanciamento)</h4>
        </div>
        <div class="col-6" style="text-align: right">
            <a href="{{$allContact==2?route('seeSale',['idSale'=>$sale->id]):route('allContact')}}"
                class="btn btn-success backButton">
                Voltar
           </a>    
        </div>
    </div>
    
    <form action="{{route('addRefinancingContact')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_sale" value={{$idSale}}>
        
        <div class="card">
            <div class="card-header">
                <h4>Informações Refinanciamento</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">   
                        <label for="contract">Contrato*</label>
                            <input class="form-control w-50" type="text" name="contract_number"  
                                value="{{$sale->contract_number}}" readonly>
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
                            <label for="contract">Dia Vencimento*</label>
                            <input class="form-control w-25" type="number" 
                                name="expiration_day" id="expiration_day" value="{{$sale->expiration_day}}">
                        </div>

                    </div>
                
                    <div class="col-6">
                        <div class="form-group">
                            <label for="contract">Total*</label>
                            <input class="form-control money w-50" type="text" name="value" 
                                id="value" value="{{$totalValueRefinanciament}}">
                        </div>
                        
                        <div class="form-group">
                            <label for="contract">Parcelas*</label>
                            <input class="form-control w-50" type="number" name="parcels" 
                                value="{{$totalParcels}}" id="parcels">
                        </div>

                        <div class="form-group">   
                            <label for="contract">Sufixo usado nas parcelas</label>
                            <input class="form-control w-25" type="text" name="sufix"  
                                value="{{old('contract')}}">
                        </div>
                        
                        <div class="col-6">
                            <div class="form-group">
                                <label for="contract">Valor das parcelas*</label>
                                <input class="form-control money w-50" type="text" name="value_parcel" id="value_parcel" 
                                    readOnly value="">
                            </div>
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
                                    <input class="form-control" type="date" name="deadline" 
                                        min="{{date('Y-m-d')}}" value="{{old('deadline')}}">
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
                        <div class="uploadArea">
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

                <div class="card">
                    <div class="card-header">
                        <div class="info__title">Administrativo:</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="fine_contact">Taxa</label>
                                    <input class="form-control" type="number" step="any" name="fine_contact" value="10">
                                </div>
                            </div>
                            
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="fine_total">Valor da multa</label>
                                    <input fine_value="{{$totalLaterValue}}" class="form-control" readonly type="text" name="fine_total" 
                                        value="">
                                </div>
                            </div>
                            
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="prefix_parcel_contact">Prefixo</label>
                                    <input class="form-control" type="text" name="prefix_parcel_contact" value="{{old('prefix_parcel_contact')}}">
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="number_parcel_contact">Num Parcelas</label>
                                    <input class="form-control" type="number" name="number_parcel_contact" value="1">
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="card">
                    <div class="card-footer">
                        <center><input type="submit" class="btn btn-success w-25" id="btnInput" value="Salvar"></center>
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
    @endif
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
                var refinancing_failed="{{$refinancing_failed}}";
            </script>
        <script src="{{asset('js/contactRefinancing.min.js')}}"></script>
    @endsection
@endsection
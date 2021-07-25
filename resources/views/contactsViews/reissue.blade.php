@extends('adminlte::page')
@extends('layouts/searchSale')


@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    
    <div class="row">
        <div class="col-6">
            <h4>Adicionar Contato (Reemitir Parcelas)</h4>
        </div>
        <div class="col-6" style="text-align: right">
            <a href="{{$allContact==2?route('seeSale',['idSale'=>$sale->id]):route('allContact')}}"
                 class="btn btn-success backButton">
                 Voltar
            </a>     
        </div>
    </div>
    @if($reissue==1)
    <div class="card">
        <div class="card-header">
            <div class="info__title">
                Parcelas em atraso
            </div>
        </div>
        <table class="table table-bordered table-hover dataTable dtr-inline" id="table_parcels">
            <thead class="table table-dark">
                <th></th>
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
                <th>Status</th>
                
            </thead>
            <tbody>
                @foreach ($parcels_late as $parcel)
                    <tr class={{$parcel->status==3?"table-danger":''}}{{$parcel->type==2 || $parcel->type==3 ?"table-primary":''}}
                        {{$parcel->status==1?"table-success":''}}
                        {{$parcel->status==4?"table-warning":''}}
                        {{$parcel->type==5?"table-info":''}}>
                        <td>
                            <center><input id="{{$parcel->id}}" type="checkbox" class="checkParcel"></center>
                        </td>
                        <td class="numberParcel" style="width: 50px">{{$parcel->num.'/'.$sale->parcels}}</td>
                        <td>
                            @if ($parcel->type==1)
                                Financiamento - {{$parcel->prefix}}
                            @elseif($parcel->type==2)
                                Taxas - {{$parcel->prefix}}
                            @elseif($parcel->type==5) 
                                {{$parcel->prefix}}    
                            @endif</td>
                        <td>{{date('d/m/Y',strtotime($parcel->date))}}</td>
                        <td style="min-width: 90px">{{$parcel->value}}</td>
                        <td style="min-width: 80px;">{{!empty($parcel->late_days)?$parcel->late_days." dias":"0 dias"}}</td>
                        <td style="min-width: 100px">{{$parcel->late_fine!=""?$parcel->late_fine:'0,00 (0,0%)'}}</td>
                        <td style="min-width: 115px">{{!empty($parcel->late_rate)?$parcel->late_rate:'0,00 (0,0%)'}}</td>
                        <td style="min-width: 100px">{{!empty($parcel->reajust)?$parcel->reajust:'0,00 (0,0%)'}}</td>
                        
                        <td>{{!empty($parcel->added_value)?$parcel->added_value:'0,00'}}</td>
                        <td class="updated_value">{{!empty($parcel->updated_value)?$parcel->updated_value:'0,00'}}</td>
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
    
    <form action="{{route('addReissueContact')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_sale" value={{$idSale}}>
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
                        <table class="table table-bordered table-hover dataTable dtr-inline"  role="grid">
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
                    <div class="card-header">
                        <div class="info__title">Informações de reemissão:</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="parcel_late_sum">Soma Parcelas Atraso</label>
                                    <input class="form-control money" type="text" name="parcel_late_sum" 
                                        value="" id="parcel_late_sum" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="rate_reissue">Definição da taxa para reemissão</label>
                                    <input class="form-control money" type="text" name="rate_reissue" 
                                        value="" id="rate_reissue" readonly>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="deadline">Dia para pagamento</label>
                                    <input class="form-control" type="date" name="deadline_reissue" 
                                            min="{{date('Y-m-d',strtotime('+1 day'))}}" 
                                            value="{{date('Y-m-d',strtotime('+1 day'))}}">
                                </div>

                                <div class="form-group">
                                    <label for="total_reissue">Total a pagar</label>
                                    <input class="form-control" type="text" name="total_reissue" 
                                        value="" id="total_reissue" readonly>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>

                <input type="hidden" name="parcels_selected[]" id="parcels_selected" value="">
                <div class="card">
                    <div class="card-footer">
                        <center><input type="submit" id="btnInput" class="btn btn-success w-25" disabled value="Salvar"></center>
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
    @else
        <div class="alert alert-warning w-50">
            Não há parcelas em atraso para pode reemitir.
        </div>
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

        <script src="{{asset('js/contactReissue.min.js')}}"></script>

    @endsection
    
@endsection
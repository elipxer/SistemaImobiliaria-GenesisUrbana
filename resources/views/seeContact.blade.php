@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 buttons_area--left">
                            <div class="info__title">Informações Contato</div>
                        </div>
                        
                        <div class="col-6" style="text-align: right;">
                            <a href="{{route('seeSale',['idSale'=>$contact->id_sale])}}" class="btn btn-success backButton">Voltar</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="input-info__group ">
                                <div class="input-info__title">Usuario</div>
                                <div class="input__info">
                                    {{$contact->user_name}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Via</div>
                                <div class="input__info">
                                    {{$contact->where}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group input-info__group--big">
                                <div class="input-info__title">Assunto</div>
                                <div class="input__info input__info--left">
                                    {{$contact->subject_matter}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Categoria</div>
                                <div class="input__info">
                                    @if ($contact->type==1)
                                        Diversos
                                    @elseif($contact->type==2)
                                        Mudança Proprietário    
                                    @elseif($contact->type==3)
                                        Mudança Dia Vencimento    
                                    @elseif($contact->type==5)
                                        Cancelamento 
                                    @elseif($contact->type==6)
                                        Reemissão de Parcelas
                                    @elseif($contact->type==7)
                                        Troca de Lote       
                                    @endif
                                </div>
                            </div><br>
                        </div>
                        <div class="col-6">
                            <div class="input-info__group">
                                <div class="input-info__title">Data</div>
                                <div class="input__info">
                                    {{date('d/m/Y',strtotime($contact->register_date))}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Prazo</div>
                                <div class="input__info">
                                    {{date('d/m/Y',strtotime($contact->deadline))}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Status</div>
                                <div class="input__info">
                                    {{$contact->status==1?'Resolvido':'Não Resolvido'}}
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
            </div>

            @if ($contact->type==2)
            <div class="card">
                <div class="card-header">
                    <div class="info__title">
                       Informações mudança de Proprietario
                    </div>

                    <div class="card-body">
                            <div class="input-info__group w-100">
                                <div class="input-info__title">Clientes Antigos</div>
                                <div class="input__info input__info--left" style="height:auto !important;">
                                    @foreach ($old_clients as $key=>$client)
                                        Nome: {{$client->name}} - Cpf/Cnpj:
                                        {{$client->cpf!=""?$client->cpf:$client->cnpj}} -
                                        {{$old_perc[$key]}}%<br>
                                    @endforeach
                                </div>
                            </div><br><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title ">Novos Clientes</div>
                                <div class="input__info input__info--left" style="height:auto !important;">
                                    @foreach ($new_clients as $key=>$client)
                                        Nome: {{$client->name}} - Cpf/Cnpj:
                                        {{$client->cpf!=""?$client->cpf:$client->cnpj}} -
                                        {{$new_perc[$key]}}%<br>
                                    @endforeach
                                </div>
                            </div><br><br>

                            <div class="input-info__group input-info__group  w-100">
                                <div class="input-info__title ">Cliente antigo para emitir o pagamento</div>
                                <div class="input__info input__info--left">
                                    Nome: {{$old_client_payment->name}} - Cpf/Cnpj:
                                    {{$old_client_payment->cpf!=""?$old_client_payment->cpf:$old_client_payment->cnpj}}
                                </div>
                            </div><br><br>
                            
                            <div class="input-info__group  w-100">
                                <div class="input-info__title ">Cliente novo para emitir o pagamento</div>
                                <div class="input__info input__info--left">
                                    Nome: {{$new_client_payment->name}} - Cpf/Cnpj:
                                    {{$new_client_payment->cpf!=""?$new_client_payment->cpf:$new_client_payment->cnpj}}
                                </div>
                            </div><br><br>
                        </div>
                    </div>
                </div>
            @endif
            
            @if(count($parcels_rate)>0)
                <div class="card">
                    <div class="card-header">
                        <div class="info__title">
                            Taxas do Contato
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-hover dataTable dtr-inline">
                            <thead class="table table-dark">
                                <th>Numero</th>
                                <th>Tipo</th>
                                <th>Vencimento</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach ($parcels_rate as $parcel)
                                    <tr class={{$parcel->status==3?"table-danger":''}}
                                        {{$parcel->type==2 || $parcel->type==3 ?"table-primary":''}}>
                                        
                                        <td style="width: 50px">{{$parcel->num!=""?$parcel->num.'/'.$sale->parcels:$parcel->num_reissue}}</td>
                                        <td>Taxas - {{$parcel->prefix}}</td>
                                        <td>{{date('d/m/Y',strtotime($parcel->date))}}</td>
                                        <td style="min-width: 90px">{{$parcel->value}}</td>
                                        <td>
                                            @if ($parcel->status==1)
                                                Paga  
                                            @elseif($parcel->status==2)
                                                Não Paga
                                            @elseif($parcel->status==3) 
                                                Atrasada
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>  
                </div>
            @endif
             
            @if ($contact->type==5 && $cancel_contact_info != null)
            <div class="card" id="card-cancel">
                <div class="card-header">
                    <div class="info__title">
                        Informações do cancelamento
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="input-info__group w-100">
                                <div class="input-info__title">Taxa de comissão da venda</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->sale_commission_rate."%"}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Comissão da venda</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->sale_commission}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Comissão da venda ajustada</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->sale_commission_adjusted}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Despesas administrativas</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->administrative_expenses."%"}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Débitos administrativas</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->administrative_debits}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Débitos de IPTU</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->iptu_debits}}
                                </div>
                            </div><br>

                            <div class="input-info__group">
                                <div class="input-info__title">Outros Débitos</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->others_debits}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Especificação do Débito</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->specification_debits==""?'Não especificado':$cancel_contact_info->specification_debits}}
                                </div>
                            </div><br>
                        </div>
                        <div class="col-6">
                            <div class="input-info__group w-100">
                                <div class="input-info__title">Total de parcelas pagas</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->total_parcels_pad}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title ">Total de parcelas pagas</div>
                                <div class="input__info">
                                    {{$cancel_contact_info->total_parcels_pad}}
                                </div>
                            </div><br>

                            <div class="input-info__group input-info__group-edit">
                                <div class="input-info__title">Valor a restituir</div>
                                <input type="text" class="form-control input__info input__info-edit money" 
                                    id="return_value" value="{{$cancel_contact_info->return_value}}">
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Valor da parcela a restituir</div>
                                <div class="input__info" id="value_parcel_return">
                                    {{$cancel_contact_info->value_parcel_return}}
                                </div>   
                            </div><br>

                            <div class="input-info__group input-info__group-edit w-100">
                                <div class="input-info__title ">Numero de parcelas a restituir</div>
                                <input type="number" class="form-control input__info-edit input__info" 
                                    id="numberParcel" value="{{$cancel_contact_info->number_parcels_return}}">
                            </div><br>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($contact->type==5 && $cancel_contact_info == null)
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-warning">O cliente não tem nada a receber</div>
                    </div>
                </div>
            @endif

            @if ($contact->type!=5 && $contact->type != 2 && $contact->type != 7)
                <div class="card">
                    <div class="card-header">
                        <div class="info__title">
                            Informações do arquivo do contato
                        </div>
                    </div>
                    @if(empty($contact->contactFile))
                    <form action="{{route('updateContactFile')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="idContact" value="{{$contact->id}}">
                        <input type="hidden" name="edit" value="false">
                        <div class="card-body">
                            <div class="uploadArea w-100">
                                <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                                <div class="uploadAreaDrop w-100">
                                    <div class="uploadAreaDrop__img">
                                        <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                    </div>
                                    <div class="uploadAreaDrop__descriptionFile"></div>
                                </div>
                                <input name="contactFile" type="file" class="uploadInput"/>
                            </div>
                        </div>
        
                        <div class="card-footer">
                            <center><input type="submit" class="btn btn-success btn-lg w-25" 
                                id="btnFileContact" disabled value="Salvar"></center>
                        </div>
                    </form>
                    @else 
                        <div class="card-body">
                            <div class="downloadArea">
                                <div class="download__title">Clique pra fazer download do Arquivo</div>
                                <a href="{{asset('storage/'.$contact->contactFile)}}" class="downloadLink" 
                                    download="Contatos_Arquivo"></a>   
                            </div><br>
                            <form action="{{route('updateContactFile')}}" method="POST" 
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="idContact" value="{{$contact->id}}">
                                <input type="hidden" name="edit" value="true">
                                <div class="row">
                                    <div class="form-group">
                                        <input class="form-control" name="contactFile" type="file"/>
                                    </div>
                                    <center><input type="submit" class="btn btn-success" value="Salvar"></center>
                                </div>
                            </form>
                        </div>   
                    @endif
                </div>
            @endif

            @if($contact->type==7)
            <div class="card">
                <div class="card-header">
                    <div class="info__title">
                        Informações do Troca de Lote
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="input-info__group w-100">
                                <div class="input-info__title">Lote antigo</div>
                                <div class="input__info input__info--left" style="height: auto;">
                                    {{$change_lot_info->old_lot}}
                                </div>
                            </div><br>
                            
                            <div class="input-info__group w-100">
                                <div class="input-info__title">Valor lote antigo</div>
                                <div class="input__info">
                                    {{$change_lot_info->old_lot_value}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Novo lote selecionado</div>
                                <div class="input__info input__info--left" style="height: auto;">
                                    {{$change_lot_info->lot_selected}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Valor novo lote selecionado</div>
                                <div class="input__info">
                                    {{$change_lot_info->value_lot_selected}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Total de parcelas pagas</div>
                                <div class="input__info">
                                    {{$change_lot_info->total_parcels_pad}}
                                </div>
                            </div><br>
                        </div>
                        
                        <div class="col-6">
                            <div class="input-info__group w-100">
                                <div class="input-info__title">Novo saldo para pagar</div>
                                <div class="input__info">
                                    {{$change_lot_info->new_value_pay}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Total de parcelas para pagar</div>
                                <div class="input__info">
                                    {{$change_lot_info->number_parcels_to_pay}}
                                </div>
                            </div><br>

                           <div class="input-info__group w-100">
                                <div class="input-info__title">Saldo para paga após a mudança</div>
                                <div class="input__info">
                                    {{$change_lot_info->value_after_change}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Numero de parcelas após a mudança</div>
                                <div class="input__info">
                                    {{$change_lot_info->number_parcel_change_lot}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Valor de parcelas após a mudança</div>
                                <div class="input__info">
                                    {{$change_lot_info->value_parcel_change_lot}}
                                </div>
                            </div><br>

                            <div class="input-info__group w-100">
                                <div class="input-info__title">Taxa</div>
                                <div class="input__info">
                                    {{$change_lot_info->rate_financing}}
                                </div>
                            </div><br>
                            
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-6">
            @if ($contact->type==1)
            <div class="card" id="card_several-solution">
                <div class="card-header ">
                    <div class="info__title">
                        Solução
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('severalSolution')}}" method="POST" id="severalContactSolution">
                        @csrf
                        <input type="hidden" name="idContact" value="{{$contact->id}}">
                        <div class="form-group">
                            <textarea class="form-control" name="solution" rows="10">{{$contact->solution}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Definir como resolvido</label>
                            <input class="ml-2" type="checkbox" name="resolved"/>
                        </div>
                        <div class="card-footer">
                            <center><input type="submit" class="btn btn-success btn-lg w-25" 
                                id="btnSeveralSolution" value="Confirmar"></center>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            @if ($contact->type==4)
                <div class="card">
                    <div class="card-header">
                        <div class="info__title">
                            Informações do Refinanciamento
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Valor Total</div>
                                    <div class="input__info">
                                        {{$refinancing_info->total_value}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Numero de parcelas</div>
                                    <div class="input__info">
                                        {{$refinancing_info->number_parcels}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Valor parcelas</div>
                                    <div class="input__info">
                                        {{$refinancing_info->value_parcel}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Data Registrado</div>
                                    <div class="input__info">
                                        {{date('d/m/Y',strtotime($refinancing_info->date))}}
                                    </div>
                                </div><br>
                            </div>
                            <div class="col-6">
                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Porcentagem da taxa</div>
                                    <div class="input__info">
                                        {{$refinancing_info->value_fine_percentage}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Numero parcelas taxa</div>
                                    <div class="input__info">
                                        {{$refinancing_info->number_parcels_fine}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Valor da taxa</div>
                                    <div class="input__info">
                                        {{$refinancing_info->value_fine}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Hora Registrado</div>
                                    <div class="input__info">
                                        {{$refinancing_info->time}}
                                    </div>
                                </div><br>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" id="card_refinancing">
                    <div class="card-header">
                        <div class="info__title alert alert-danger">
                            O sistema identificou que as parcelas do refinanciamento ainda não foram pagas. 
                            Caso o cliente pagou, você pode confirmar o refinanciamento logo abaixo.
                        </div>

                        <div class="card-body">
                            <form action="{{route('refinancingSuccess',['idContact'=>$contact->id
                                ,'idSale'=>$contact->id_sale,'forceConfirm'=>true])}}" 
                                method="GET" enctype="multipart/form-data" id="refinancingContact">
                            @csrf
                            <div class="card-footer">
                                <center><input type="submit" class="btn btn-danger btn-lg w-25" 
                                    id="btnRefinancing" value="Confirmar"></center>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            @if ($contact->type==5)
            <div class="card">
                <div class="info__title">
                    Gerar documento de cancelamento
                </div>
                
                <div class="card-body">
                    <div class="downloadArea contract_area">
                        <div class="download__title">Clique pra gerar o documento</div>
                        @if($contact->status==2)
                            <a id="contract" href="{{route('contractCancel',['id_contact'=>$contact->id])}}" target="_blank" class="downloadLink" ></a>   
                        @else
                            <a href="{{route('contractCancel',['id_contact'=>$contact->id])}}" target="_blank" class="downloadLink" ></a>   
                        @endif;
                    </div><br>
                </div>  
            </div>
            @endif

            @if ($contact->type==7)
            <div class="card">
                <div class="card-header">
                    <div class="info__title">
                        Gerar documento de mudança de lote
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="downloadArea contract_area">
                        <div class="download__title">Clique pra gerar o documento</div>
                        <a href="{{route('contractChangeLot',['id_contact'=>$contact->id])}}" target="_blank" class="downloadLink" ></a>   
                        
                    </div><br>
                </div>  
            </div>
            @endif

            @if ($contact->type==7)
            <div class="card" id="card-finish-change-lot">
                <div class="card-header">
                    <div class="info__title">
                        Inserir documento de mudança de lote com assinaturas
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('changeLotSuccess')}}" method="POST" 
                        enctype="multipart/form-data" id="changeOwnerForm">
                        @csrf
                        <input type="hidden" name="idContact" value="{{$contact->id}}">
                        <input type="hidden" name="idSale" value="{{$contact->id_sale}}">
                        
                        <div class="card-body">
                            <div class="uploadArea">
                                <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                                <div class="uploadAreaDrop">
                                    <div class="uploadAreaDrop__img">
                                        <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                    </div>
                                    <div class="uploadAreaDrop__descriptionFile"></div>
                                </div>
                                <input name="contactFile[]" multiple="multiple" type="file" class="uploadInput"/>
                            </div>
                        </div>
        
                        <div class="card-footer">
                            <center><input type="submit" class="btn btn-success btn-lg w-25" 
                                id="btnFileContact" disabled value="Salvar"></center>
                        </div>
                    </form>
                </div> 
            </div>
            @endif

            @if ($contact->type==2)
            <div class="card">
                <div class="card-header">
                    <div class="info__title">
                        Gerar documento cessão
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="downloadArea contract_area">
                        <div class="download__title">Clique pra gerar o documento</div>
                            <a href="{{route('contractChangeOwner',['id_contact'=>$contact->id])}}" target="_blank" class="downloadLink" ></a>   
                    </div><br>
                </div>  
            </div>
            @endif

            @if ($contact->type==2)
            <div class="card" id="card-finish-changeOwner">
                <div class="card-header">
                    <div class="info__title">
                        Inserir documento cessão com assinaturas
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('changeOwner')}}" method="POST" 
                        enctype="multipart/form-data" id="changeOwnerForm">
                        @csrf
                        <input type="hidden" name="idContact" value="{{$contact->id}}">
                        <input type="hidden" name="idSale" value="{{$contact->id_sale}}">
                        
                        <div class="card-body">
                            <div class="uploadArea">
                                <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                                <div class="uploadAreaDrop">
                                    <div class="uploadAreaDrop__img">
                                        <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                    </div>
                                    <div class="uploadAreaDrop__descriptionFile"></div>
                                </div>
                                <input name="contactFile[]" multiple="multiple" type="file" class="uploadInput"/>
                            </div>
                        </div>
        
                        <div class="card-footer">
                            <center><input type="submit" class="btn btn-success btn-lg w-25" 
                                id="btnFileContact" disabled value="Salvar"></center>
                        </div>
                    </form>
                </div> 
            </div>
            @endif


            @if ($contact->type==5)
            <div class="card" id="card-finish-cancel">
                <div class="card-header">
                    <div class="info__title">
                        Inserir documento com assinaturas
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('cancelSale')}}" method="POST" 
                        enctype="multipart/form-data" id="cancelSale">
                        @csrf
                        <input type="hidden" name="idContact" value="{{$contact->id}}">
                        <input type="hidden" name="idSale" value="{{$contact->id_sale}}">
                        @if($cancel_contact_info != null)
                            <input type="hidden" name="return_value" value="{{$cancel_contact_info->return_value}}">
                            <input type="hidden" name="number_parcels_return" value="{{$cancel_contact_info->number_parcels_return}}">
                            <input type="hidden" name="value_parcel_return" value="{{$cancel_contact_info->value_parcel_return}}">
                        @endif
                        <div class="card-body">
                            <div class="uploadArea">
                                <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                                <div class="uploadAreaDrop">
                                    <div class="uploadAreaDrop__img">
                                        <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                    </div>
                                    <div class="uploadAreaDrop__descriptionFile"></div>
                                </div>
                                <input name="contactFile[]" multiple="multiple" type="file" class="uploadInput"/>
                            </div>
                        </div>
        
                        <div class="card-footer">
                            <center><input type="submit" class="btn btn-success btn-lg w-25" 
                                id="btnFileContact" disabled value="Salvar"></center>
                        </div>
                    </form>
                </div> 
            </div>
            @endif

            @if($contact->type==6)
                <div class="card">
                    <div class="card-header">
                        <div class="info__title">
                            Informações da reemissão de parcelas
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Soma Parcelas Atraso</div>
                                    <div class="input__info">
                                        {{$reissue_contact_info->parcel_late_sum}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Definição da taxa para reemissão</div>
                                    <div class="input__info">
                                        {{$reissue_contact_info->rate_reissue}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Parcelas selecionadas para reemitir</div>
                                    <div class="input__info">
                                        {{$reissue_contact_info->parcels_selected}}
                                    </div>
                                </div><br>
                            </div>
                            <div class="col-6">
                               
                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Dia para pagamento</div>
                                    <div class="input__info">
                                        {{date('d/m/Y',strtotime($reissue_contact_info->deadline_reissue))}}
                                    </div>
                                </div><br>

                                <div class="input-info__group w-100">
                                    <div class="input-info__title">Total a pagar</div>
                                    <div class="input__info">
                                        {{$reissue_contact_info->total_reissue}}
                                    </div>
                                </div><br>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($contact->type==6)
                <div class="card" id="card_reissue">
                    <div class="card-header">
                        <div class="info__title">
                            Caso o cliente tenha aceitado. Confirme para reemitir a parcela
                        </div>

                        <div class="card-body">
                            <form action="{{route('reissueContact')}}" id="reissueContact" method="POST" enctype="multipart/form-data" >
                            @csrf
                            <input type="hidden" name="id_contact" value="{{$contact->id}}">
                            <div class="card-footer">
                                <center><input type="submit" class="btn btn-success btn-lg w-50" 
                                    id="btnReissue" value="Confirmar"></center>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
 
    
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
        <script src="{{asset('js/seeContact.min.js')}}"></script>
    @endsection
@endsection
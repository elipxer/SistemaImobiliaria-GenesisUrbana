@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="info__title">
                Informações contato concluido
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="info__title">
                                Informações contato
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Pessoa</div>
                                        <div class="input__info">
                                            {{$basic_contact_info->contact_client_name}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Via</div>
                                        <div class="input__info">
                                            {{$basic_contact_info->where}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Assunto</div>
                                        <div class="input__info">
                                            {{$basic_contact_info->subject_matter}}
                                        </div>
                                    </div><br>
                                </div>

                                <div class="col-6">
                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Prazo</div>
                                        <div class="input__info">
                                            {{date('d/m/Y',strtotime($basic_contact_info->deadline))}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Tipo</div>
                                        <div class="input__info">
                                            @if ($basic_contact_info->type==1)
                                                Diversos
                                            @elseif($basic_contact_info->type==2)
                                                Mudança Proprietário    
                                            @elseif($basic_contact_info->type==3)
                                                Mudança Dia Vencimento   
                                            @elseif($basic_contact_info->type==4)
                                                Refinanciamento
                                            @elseif($basic_contact_info->type==5)
                                                Cancelamento 
                                            @elseif($basic_contact_info->type==6)
                                                Reemissão de parcelas   
                                            @endif
                                        </div>
                                    </div><br>

                                    @if($basic_contact_info->type==1)
                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Solução</div>
                                        <div class="input__info">
                                            {{$basic_contact_info->solution}}
                                        </div>
                                    </div><br>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(count($parcels_rate)>0 && $basic_contact_info->type!=1)
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
                    
                    @if(count($cancel_contact_info_documents)>0 && $basic_contact_info->type==5)
                    @foreach ($cancel_contact_info_documents as $cancel_contact)
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="info__title info__title--white">
                                    <center>Documentos do contrato cancelado</center>
                                </div>
                            </div>
                            @foreach (explode("|",$cancel_contact->path_document_done) as $part=>$document)
                                <div class="card-body">
                                    <div class="downloadArea">
                                        <div class="download__title">Clique pra fazer download do Documento Parte {{$part+1}}</div>
                                        <a href="{{asset('storage/'.$document)}}" class="downloadLink" 
                                            download="Troca_Proprietario"></a>   
                                    </div><br>
                                </div>  
                            @endforeach
                        </div>
                    @endforeach
                    @endif

                    @if(count($change_owner_info_documents)>0 && $basic_contact_info->type==2)
                    @foreach ($change_owner_info_documents as $change_owner)
                        <div class="card card-success">
                            <div class="card-header">
                                <div class="info__title info__title--white">
                                    <center>Documentos da troca de proprietário</center>
                                </div>
                            </div>
                            @foreach (explode("|",$change_owner->path_document_done) as $part=>$document)
                                <div class="card-body">
                                    <div class="downloadArea">
                                        <div class="download__title">Clique pra fazer download do Documento Parte {{$part+1}}</div>
                                        <a href="{{asset('storage/'.$document)}}" class="downloadLink" 
                                            download="Troca_Proprietario"></a>   
                                    </div><br>
                                </div>  
                            @endforeach
                        </div>
                    @endforeach
                    @endif

                   
                    
                    @if($basic_contact_info->contactFile != "" && $basic_contact_info->type!=1)
                    <div class="card card-success">
                        <div class="card-header">
                            <div class="info__title info__title--white">
                                Documento Inserido no contato</center>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="downloadArea">
                                <div class="download__title">Clique pra fazer download do Arquivo</div>
                                <a href="{{asset('storage/'.$basic_contact_info->contactFile)}}" class="downloadLink" 
                                    download="Contatos_Arquivo"></a>   
                            </div><br>
                        </div> 
                    </div>
                    @endif
                </div>
                
                <div class="col-6">
                    @if(count($change_lot_contact_info_documents)>0 && $basic_contact_info->type==7)
                    @foreach ($change_lot_contact_info_documents as $change_lot)
                        <div class="card card-success">
                            <div class="card-header">
                                <div class="info__title info__title--white">
                                    <center>Documentos da troca de lote</center>
                                </div>
                            </div>
                            @foreach (explode("|",$change_lot->path_document_done) as $part=>$document)
                                <div class="card-body">
                                    <div class="downloadArea">
                                        <div class="download__title">Clique pra fazer download do Documento Parte {{$part+1}}</div>
                                        <a href="{{asset('storage/'.$document)}}" class="downloadLink" 
                                            download="Troca_Proprietario"></a>   
                                    </div><br>
                                </div>  
                            @endforeach
                        </div>
                    @endforeach
                    @endif

                    @if(count($parcels_rate)>0 && $basic_contact_info->type==1)
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

                    @if($basic_contact_info->type==5)
                    <div class="card" id="card-cancel">
                        <div class="card-header">
                            <div class="info__title">
                                Informações do cancelamento
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
        
                                        <div class="input-info__group">
                                            <div class="input-info__title">Valor a restituir</div>
                                            <div class="input__info">
                                                {{$cancel_contact_info->return_value}}
                                            </div>
                                        </div><br>
        
                                        <div class="input-info__group w-100">
                                            <div class="input-info__title">Valor da parcela a restituir</div>
                                            <div class="input__info" id="value_parcel_return">
                                                {{$cancel_contact_info->value_parcel_return}}
                                            </div>   
                                        </div><br>
        
                                        <div class="input-info__group w-100">
                                            <div class="input-info__title">Numero de parcelas a restituir</div>
                                            <input type="number" class="form-control input__info-edit input__info" 
                                                id="numberParcel" value="{{$cancel_contact_info->number_parcels_return}}">
                                        </div><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($basic_contact_info->type==2)
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
                    
                    @if ($basic_contact_info->type==4)
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
                    @endif

                    @if ($basic_contact_info->type==3)
                        <div class="card">
                            <div class="card-header">
                                <div class="info__title">
                                    Troca de dia de vencimento
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success">
                                    Dia de vencimento trocado para {{$basic_contact_info->expired_day}} 
                                </div>
                                
                            </div>
                        </div> 
                    @endif

                    @if($basic_contact_info->contactFile != "" && $basic_contact_info->type==1)
                        <div class="card card-success">
                            <div class="card-header">
                                <div class="info__title info__title--white">
                                    Documento Inserido no contato</center>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="downloadArea">
                                    <div class="download__title">Clique pra fazer download do Arquivo</div>
                                    <a href="{{asset('storage/'.$basic_contact_info->contactFile)}}" class="downloadLink" 
                                        download="Contatos_Arquivo"></a>   
                                </div><br>
                            </div> 
                        </div>
                    @endif

                   
                </div>
            </div>
        </div>
    </div>    
@endsection

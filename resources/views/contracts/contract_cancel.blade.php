<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contato Cancelamento</title>
    <meta charset="Windows-1252" />
    <style>

        *{
            text-align: justify; 
        }
        .title{
            text-align: center;
            text-decoration: underline;
        }

        .subtitle{
            text-align: center;
            font-weight: bold;
        }

        .subtitle--left{
            text-align: left;
        }

        p{
            font-size: 14px;
            text-align: justify;

        }

        .container_signature__row{
            width: 100%;
        }

        .container_signature__item{
            width: 40%;
            float: left;
        }

        .signature_line{
            width: 60%;
        }
      
        .container_signature_line{
            margin-top: 30px;
            border-top: 1px solid black;
            text-align: center;
        }
        
    
    </style>
</head>
<body>
    <h5>Promitente Vendedor</h5>
    @foreach ($companies as $company)
    <p style="text=align:rigth;;width: 70%;">
        {{$company->company_name}}., pessoa(s) jurídica(s) de direito privado, com sede {{$company->street}}, {{$company->number}}
        {{$company->neighborhood}} {{$company->city}} - {{$company->state}}, 
        inscrita no CNPJ/MF sob o n.º {{$company->cnpj}}; neste ato representada por CARLOS SHIGUERU IMADA, 
        brasileiro, engenheiro civil, portador da Cédula de Identidade 536.905-SSP/MS e CPF/MF 595.291.481-00;
    </p><br>
    @endforeach
    
    <h5> Compromissário(a)(s) Comprador(a)(es)</h5>
    @foreach ($clients as $client)
        @if($client['client']->kind_person==1)
            @if($client['client']->marital_status==2)
                <p>
                    {{$client['client']->name}} e {{$client['client']->spouse_name}}, {{$client['client']->nationality}}, CASADOS, residentes e domiciliados à 
                    {{$client['client']->street}}, {{$client['client']->number}} {{$client['client']->neighborhood}} {{$client['client']->city}} - {{$client['client']->state}},
                    portador(a)(es) das Cédulas de Identidade {{$client['client']->rg}} e {{$client['client']->spouse_rg}} 
                    e dos CPF/MF {{$client['client']->cpf}} e {{$client['client']->spouse_cpf}} respectivamente;
                </p>
           @else 
                <p>
                    {{$client['client']->name}}  residente e domiciliado à 
                    {{$client['client']->street != ""?$client['client']->street:'RUA NÃO INFORMADA'}}, 
                    {{$client['client']->number != ""?$client['client']->number:'NUMERO NÃO INFORMADO'}} 
                    {{$client['client']->neighborhood!= ""?$client['client']->neighborhood:'BAIRRO NÃO INFORMADO'}}, 
                    {{$client['client']->city!= ""?$client['client']->city:'CIDADE NÃO INFORMADA'}} - 
                    {{$client['client']->state!= ""?$client['client']->state:'ESTADO NÃO INFORMADO'}},
                    portador(a)(es) das Cédulas de Identidade {{$client['client']->rg!= ""?$client['client']->rg:'RG NÃO INFORMADO'}} 
                    e do CPF/MF {{$client['client']->cpf!= ""?$client['client']->cpf:'CPF NÃO INFORMADO'}} ;
                </p>
           @endif
        @else
        <p>
            {{$client['client']->company_name}}, pessoa jurídica de direito privado, com sede 
            {{$client['client']->street}}, {{$client['client']->number}} {{$client['client']->neighborhood}} 
            {{$client['client']->city}} - {{$client['client']->state}}, inscrita no CNPJ/MF sob o n.º {{$client['client']->cnpj}}, 
            
            @if($client['client']['client_representative'] != null)
                neste ato representada por 
                @if($client['client']->marital_status==1)
                    {{$client['client']['client_representative']->name}}  residente e domiciliado à 
                    {{$client['client']['client_representative']->street != ""?$client['client']['client_representative']->street:'RUA NÃO INFORMADA'}}, 
                    {{$client['client']['client_representative']->number != ""?$client['client']['client_representative']->number:'NUMERO NÃO INFORMADO'}} 
                    {{$client['client']['client_representative']->neighborhood!= ""?$client['client']['client_representative']->neighborhood:'BAIRRO NÃO INFORMADO'}}, 
                    {{$client['client']['client_representative']->city!= ""?$client['client']['client_representative']->city:'CIDADE NÃO INFORMADA'}} - 
                    {{$client['client']['client_representative']->state!= ""?$client['client']['client_representative']->state:'ESTADO NÃO INFORMADO'}},
                    portador(a)(es) das Cédulas de Identidade {{$client['client']['client_representative']->rg!= ""?$client['client']['client_representative']->rg:'RG NÃO INFORMADO'}} 
                    e do CPF/MF {{$client['client']['client_representative']->cpf!= ""?$client['client']['client_representative']->cpf:'CPF NÃO INFORMADO'}} ;
                @else
                    {{$client['client']['client_representative']->name}} e {{$client['client']->spouse_name}}, {{$client['client']['client_representative']->nationality}}, 
                    CASADOS, residentes e domiciliados à {{$client['client']['client_representative']->street}}, {{$client['client']['client_representative']->number}} 
                    {{$client['client']['client_representative']->neighborhood}} {{$client['client']['client_representative']->city}} - {{$client['client']['client_representative']->state}},
                    portador(a)(es) das Cédulas de Identidade {{$client['client']->rg}} e {{$client['client']->spouse_rg}} 
                    e dos CPF/MF {{$client['client']['client_representative']->cpf}} e {{$client['client']['client_representative']->spouse_cpf}} respectivamente;
                @endif
            @endif

        </p>
        @endif
    @endforeach
    <p>
        As partes acima identificadas resolvem através deste termo, rescindir 
        o CONTRATO PARTICULAR DE VENDA E COMPRA N.º {{$sale->contract_number}}, 
        referente o lote {{$lot->lot_number}} conforme condições a seguir:
    </p>

    <p>
        <b>Cláusula Primeira:</b> Pelo presente instrumento particular o(a)(s) <b>Compromissário(a)(s) Comprador(a)(es)</b> 
        solicitam e a Promitente Vendedora rescindem a venda do Lote {{$lot->lot_number}} da Quadra {{$lot->block}} do {{$interprise->name}}, 
        na cidade de {{$interprise->city}}/{{$interprise->state}}, objeto do contrato nº. {{$sale->contract_number}}, 
        conforme condições a seguir.
    </p>

    <p>
        <b>Cláusula Segunda:</b> O(A)(s) <b>Compromissário(a)(s) Comprador(a)(es)</b> devolve(m) todo direito 
        e posse do imóvel acima identificado à Compromitente Vendedora, e esta recebe, como assim 
        os tem devolvido e recebido. O imóvel deste instrumento é devolvido à <b>Promitente Vendedora</b> livre 
        e desembaraçado de quaisquer ônus judiciais e/ou extrajudiciais, em dia com todas as taxas, impostos, 
        custas, emolumentos, condomínio, inclusive taxas de água, luz, gás e IPTU até a data da assinatura 
        deste instrumento.
    </p>

    <p>
       <div style="margin-left:25px;">
        Parágrafo Único: Caso existam débitos de IPTU, ou taxas de concessionárias de água e energia elétrica,
         dos exercícios anteriores da data de assinatura deste instrumento, o(a)(s) <b>Compromissário(a)(s) Comprador(a)(s)</b> 
        se responsabilizará(ao) pelo pagamento integral destes débitos.
        </div> 
    </p>

    <p>
        Cláusula Terceira: Mediante este instrumento, a <b>Promitente Vendedora</b> restituirá ao(à)(s) 
        <b>Compromissário(a)(s) Comprador(a)(s)</b> a quantia total de R$ {{$cancel_contact_info->return_value}} 
        (extenso) que será pago em {{$cancel_contact_info->number_parcels_return}} (extenso) parcelas de R$ {{$cancel_contact_info->value_parcel_return}} 
        (extenso), vincendas todo dia 26 de cada mês, 
        sendo a primeira s ser paga no próximo dia {{date('d/m/Y',strtotime($cancel_contact_info->first_parcel_return))}}.
    </p>

    <p>{{$interprise->city}} - {{$interprise->state}}, {{$now}}</p><br>

    @foreach ($companies as $company)
    <div class="container_signature__row">
        <div class="container_signature__item">
            <b>ORTORGANTE CEDENTE</b>
        </div>

        <div class="container_signature__item signature_line">
            <div class="container_signature_line">
                {{$company->representative_name}}
            </div>
        </div>
    </div><br><br>
    @endforeach

    
    @foreach ($clients as $client)
    @if($client['client']->kind_person==1)
        <div class="container_signature__row">
            <div class="container_signature__item">
                <b>ORTORGANTE CESSIONÁRIO</b>
            </div>

            <div class="container_signature__item signature_line">
                <div class="container_signature_line">
                    {{$client['client']->name}}
                </div>
            </div>
        </div><br><br>

        @if($client['client']->marital_status==2)
            <div class="container_signature__row">
                <div class="container_signature__item">
                    <b>CÔNJUGE</b>
                </div>

                <div class="container_signature__item signature_line">
                    <div class="container_signature_line">
                        {{$client['client']->spouse_name}}
                    </div>
                </div>
            </div><br><br>
        @endif
    @else 
        <div class="container_signature__row">
            <div class="container_signature__item">
                <b>ORTORGANTE CESSIONÁRIO</b>
            </div>

            <div class="container_signature__item signature_line">
                <div class="container_signature_line">
                    {{$client['client']->name}}
                </div>
            </div>
        </div><br><br>
        @if($client['client']->marital_status==2)
            <div class="container_signature__row">
                <div class="container_signature__item">
                    <b>CÔNJUGE</b>
                </div>

                <div class="container_signature__item signature_line">
                    <div class="container_signature_line">
                        {{$client['client']->spouse_name}}
                    </div>
                </div>
            </div><br><br>
        @endif
    @endif
    @endforeach

    @foreach ($companies as $company)
    <div class="container_signature__row">
        <div class="container_signature__item">
            <b>INTERVENIENTE ANUENTE</b>
        </div>

        <div class="container_signature__item signature_line">
            <div class="container_signature_line">
                {{$company->company_name}}
            </div>
        </div>
    </div>
    @endforeach
     

    <p>
        <b>Testemunhas:</b><br>
        1)
    </p><br><br>
    <p>
        2)
    </p><br>

</body>
</html>
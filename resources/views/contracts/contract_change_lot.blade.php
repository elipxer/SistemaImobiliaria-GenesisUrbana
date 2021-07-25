<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contato Alterar Lote</title>
    <style>
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
    <h2 class="title">TERMO ADITIVO DE MUDANÇA DE LOTE - N° {{$sale->contract_number}}</h2><br>
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
        As partes acima identificadas têm entre si, justo e acertado o presente instrumento 
        particular para refinanciar o saldo devedor, que se segue:
    </p><br>

    
    <p>
        <b><u>Cláusula Primeira</u></b> - Pelo presente instrumento particular o(a)(s) <b>Compromissário(a)(s) 
        Comprador(a)(es)</b> solicitam e a <b>Promitente Vendedora</b> autoriza a alteração do objeto do 
        contrato nº. {{$sale->contract_number}} {{$lot->lot_number}} da <b>Quadra</b> {{$lot->block}} 
        do {{$interprise->name}}, na cidade de {{$interprise->city}}/{{$interprise->state}}, 
        conforme condições a seguir.
    </p>

    <p>
        <b><u>Cláusula Segunda</u></b> -Saldo devedor atual: R$ {{$change_lot_info->new_value_pay}} (extenso) que será pago 
        em {{$change_lot_info->number_parcels_to_pay}} (extenso) parcelas mensais e consecutivas 
        de R$ {{$change_lot_info->value_parcel_change_lot}} (extenso), com a primeira parcela vencendo 
        em {{date('d/m/Y',strtotime($change_lot_info->first_parcel))}}.
    </p>

    <p>
        Parágrafo Primeiro – As parcelas terão reajuste ANUAL PELA VARIAÇÃO DO ÍNDICE MAIS JUROS%, 
        a partir da data deste instrumento.
    </p>

    <p>
        Parágrafo Terceiro – Os pagamentos das parcelas mensais efetuados até o dia do vencimento terão um 
        abatimento de 10% (dez por cento) no valor nominal, a título de bonificação por pontualidade.
    </p>

    <p>
        Parágrafo Quarto – Ocorrendo a impontualidade no pagamento das prestações, além de perder o abono, 
        incidirão juros de 1% (um por cento) ao mês e multa pecuniária de 2% (dois por cento) sobre o valor 
        da prestação na data do efetivo pagamento.
    </p>

    <p>
        <b><u>Cláusula Terceira</u></b> -Quaisquer controvérsias, divergências ou conflitos resultantes desse 
        contrato ou incidentes nas cláusulas do mesmo, serão resolvidos pelo procedimento arbitral, 
        conforme a lei de arbitragem n° 9.307/96, adotando a regra do direito, por intermédio do TACOM - PR – 
        Câmara de Arbitragem, Conciliação e Mediação do Paraná, localizado na Avenida Paissandu, n° 1062, Zona 03, 
        na cidade de Maringá, Estado do Paraná, CNPJ/MF sob o n° 05.475.080/0001-55, de acordo com seu regulamento, 
        regimento e demais normas de procedimentos, por um árbitro integrante de seu quadro, no idioma português..
    </p>

    <p>
        <b><u>Cláusula Quarta</u></b> - Para celebração do presente instrumento, é cobrada uma tarifa no importe de 
        R$ {{$change_lot_info->value_after_change}} (extenso) a ser paga em {{$change_lot_info->number_parcel_change_lot}} parcela(s) vencendo no(s) dia(a) PARCELAS.
    </p>

    <p>
        As demais cláusulas previstas no instrumento particular de compra e venda originário, que não colidam 
        diretamente com as novas cláusulas previstas neste instrumento, permanecem inalteradas, possuindo ainda, plena vigência. 
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
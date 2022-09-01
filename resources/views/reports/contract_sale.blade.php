<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato</title>
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
    <h2 class="title">CONTRATO PARTICULAR DE VENDA E COMPRA - N.º {{$sale->contract_number}}</h2><br>
    <h5>Promitente Vendedor</h5>
    @foreach ($companies as $company)
    <p style="text-align:justify;;width: 70%;">
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
    <p>As partes acima identificadas têm entre si contratado o presente instrumento sob as seguintes condições:</p>

    <h4 class="title">CLÁUSULA PRIMEIRA</h4>
    <p>
        A(s) Promitente Vendedora é(são) legítima(s) senhora(s) e possuidora(s) 
        do Lote {{$lot->lot_number}} da Quadra {{$lot->block}} do {{$interprise->name}}, na cidade de {{$interprise->state}}/UF, 
        com área de {{$lot->area}} m², objeto da matrícula n.º {{$lot->registration_number}} do Cartório de Registro de Imóveis 
        da respectiva comarca. O referido lote tem as seguintes divisas e confrontações: {{$lot->confrontations}}.
    </p>

    <h4 class="title">CLÁUSULA SEGUNDA</h4>
    <p>
        A Promitente Vendedora promete vender e o(a)(s) Compromissário(a)(s) Comprador(a)(es) se compromete(m) 
        a comprar o imóvel descrito na Cláusula Primeira pelo preço certo e ajustado de R$ {{$sale->value}}, 
        a ser pago da seguinte forma:<br>
        a) Arras/Sinal do Negócio: no valor de R$ {{$sale->input}}, representada por 01 (uma) parcela paga no ato da assinatura da Proposta de Compra;<br>
        b) Saldo: no valor de R$ {{$sale->value}} a ser pago em {{$sale->parcels}} parcelas mensais e consecutivas no valor de R$ {{$sale->value_parcel}} cada, 
        com vencimento todo dia {{$sale->expiration_day}} do mês, sendo a primeira a vencer em {{date('d/m/Y',strtotime($sale->first_parcel))}}.<br>
        § Primeiro – As parcelas serão reajustadas anualmente, a partir da data da respectiva Proposta de Compra, observando-se sempre o mínimo de 10 (dez) por cento que se dá por conta da inflação 
        e reestabelecimento econômico e financeiro das prestações vincendas.<br>
        § Segundo – As parcelas serão pagas exclusivamente através de cobrança bancária.<br>
        § Terceiro – Os pagamentos das parcelas mensais efetuados até o dia do vencimento, terão um abatimento de 
            10% (dez por cento) no valor nominal, a título de bonificação por pontualidade.<br>
        § Quarto – Ocorrendo a impontualidade no pagamento das prestações, além de perder o abono, incidirão juros 
        de 1% (um por cento) ao mês e multa pecuniária de 2% (dois por cento) sobre o valor da prestação na data 
        do efetivo pagamento.<br>
        § Quinto – No caso de atraso superior a 03 (três) parcelas, consecutivas ou não, deverão ser pagos, 
        além de juros e da multa, honorários advocatícios fixados em 10% (dez por cento) no caso de transação amigável 
        e de 20% (vinte por cento) no caso de transação judicial, independentemente do pagamento das custas judiciais 
        e extrajudiciais porventura existentes, ônus estes atribuídos à parte faltante.
    </p>

    <h4 class="title">CLÁUSULA TERCEIRA</h4>
    <p>
        A presente promessa de venda e compra é celebrada em caráter irrevogável e irretratável podendo, porém ser 
        rescindida por inadimplemento de qualquer de suas cláusulas e ou condições.<br>
        a) No caso da rescisão vier a ocorrer por culpa da Promitente Vendedora, deverá esta devolver toda e qualquer 
        importância que houver recebido, com as mesmas correções do 
        § Segundo da Cláusula Segunda, e parceladamente nas mesmas condições em que recebeu do(a)(s) 
        Compromissário(a)(s) Comprador(a)(es).<br>
        b) No caso da rescisão vier a ser causada por desistência ou inadimplência por culpa do(a)(s) Compromissário(a)(s) 
        Comprador(a)(es), e após ter(em) sido devidamente notificado(s), conforme preceitua o § Primeiro, do art. 32, 
        da Lei 6.766, de 19.12.79, ficará a Promitente Vendedora investida no direito de ajuizar ação de reintegração 
        de posse, com pedido de liminar e restituir ao(à)(s) Compromissário(a)(s) Comprador(a)(es) o valor de que deste(s) 
        tiver recebido como pagamento de preço, sem correção, deduzidas, entretanto, em seu favor, a verbas equivalentes a:<br>
        I. 6,0% (seis por cento) do preço total do lote, com os devidos reajustes, atualizados sobre o montante da venda da cláusula segunda, 
        a título de comissão de corretagem deste negócio;;<br>
        II. 20,0% (vinte por cento) dos valores principais pagos pelo(a)(s) Compromissário(a)(s) Comprador(a)(es), 
        com os devidos reajustes, atualizados sobre o montante da venda da cláusula segunda, a título de despesas 
        com análise e controle cadastral, elaboração de contrato, consultas diversas, e de despesas administrativas de cobranças.<br>
        III. Os valores relativos a juros e multas decorrentes de atrasos nos pagamentos das parcelas.
        IV. Os valores, devidamente corrigidos, que a Promitente Vendedora houver antecipado com despesas havidas para 
        a rescisão deste contrato, tais como custas, emolumentos, notificações, intimações, honorários advocatícios, etc.<br>
        § Primeiro – Caso os valores pagos pelo(a)(s) Compromissário(a)(s) Comprador(a)(es) sejam inferiores aos valores 
        dos itens “I”, “II”, “III” e “IV”, este(s) deverá(ao) completar o pagamento para se fazer frente a estas despesas.<br>
        § Segundo – Os valores a serem restituídos ao(à)(s) Compromissário(a)(s) Comprador(a)(es) serão pagos 
        a este em igual número de parcelas que o(s) mesmo(s) efetuou(aram) à Promitente Vendedora, de forma sucessiva, 
        sem correção, a partir de 30 (trinta) dias da rescisão contratual.
    </p>
    
    <h4 class="title">CLÁUSULA QUARTA</h4>
    <p>
        A Promitente Vendedora, no ato da assinatura deste instrumento, mediante as cláusulas pactuadas, transmite ao(à)(s) Compromissário(a)(s) Comprador(a)(es) 
        toda a posse direta, domínio, direito e ação que até então exercia sobre o referido imóvel, comprometendo-se 
        por si, herdeiros e legais sucessores,  a fazê-la sempre boa, firme e valiosa e a responderem pela evicção 
        de direito na forma da Lei.<br>
        A Promitente Vendedora se compromete a entregar o imóvel com toda a infra-estrutura básica, de acordo com 
        o compromisso firmado com a Prefeitura Municipal.
        O imóvel será entregue livre e desembaraçado de todos e quaisquer ônus judiciais e extrajudiciais, foro, 
        pensão e hipoteca de qualquer natureza, bem como quite de todos os impostos e taxas.
    </p>

    <h4 class="title">CLÁUSULA QUINTA</h4>
    <p>
        Correrão por conta do(a)(s) Compromissário(a)(s) Comprador(a)(es) todas as despesas decorrentes da aquisição do 
        lote, tais como Escritura Pública de Compra e Venda (com Pacto Comissório para o caso de ainda existir créditos 
        em favor da Promitente Vendedora), ITBI, FUNREJUS, registros em cartórios, bem como quaisquer outros impostos 
        e taxa que venham a incidir sobre o mesmo a partir da data da assinatura da Proposta de Compra, documento gerador
         deste compromisso, ainda que encargos sejam lançados em nome da Promitente Vendedora.<br>
        É de responsabilidade do(a)(s) Compromissário(a)(s) Comprador(a)(es) manter o referido lote em perfeito estado 
        de limpeza e higiene; a retirar expensas muros e cercas em desacordo com a demarcação correta.<br>
        É de responsabilidade do(a)(s) Compromissário(a)(s) Comprador(a)(es) informar para a Promitente Vendedora, 
        num prazo de 10 (dez) dias, quando da(s) sua(s) alteração(ões) de residência ou de endereço para recebimento 
        de correspondência, avisos e carnês, sob pena de não o fazendo, ser considerado como estando em lugar 
        incerto e não sabido, sofrendo as conseqüências judiciais dessa caracterização.
    </p>

    <h4 class="title">CLÁUSULA SEXTA</h4>
    <p>
        No caso do(a)(s) Compromissário(a)(s) Comprador(a)(es) desejar(em) ceder ou transferir à terceiros os direitos 
        e deveres ajustados no presente contrato, somente poderá(ao) fazê-lo, desde que sem débito pecuniário vencido 
        frente à Promitente Vendedora. A cessão e transferência de direitos e obrigações realizada pelo(a)(s) 
        Compromissário(a)(s) Comprador(a)(es), mesmo em situação regular de pagamentos, somente terá eficácia em relação 
        à Promitente Vendedora caso esta venha a anuir expressamente ao cessionário no instrumento que materializar o ato, 
        ou, se notificada nos termos da Lei para tanto, não se opor no prazo legal ao negócio.<br>
        § Único – O(A)(s) Compromissário(a)(s) Comprador(a)(es), neste caso, arcará(ao) com todas as despesas, 
        inclusive de ordem meramente administrativas à Promitente Vendedora, decorrentes da cessão transferência 
        que realizar no valor de 3,0% (três por cento) sobre o valor do lote, devidamente corrigido na referida data 
        da anuência.
    </p>

    <h4 class="title">CLÁUSULA SETIMA</h4>
    <p>
        Quaisquer controvérsias, divergências ou conflitos resultantes desse contrato ou incidentes nas cláusulas do mesmo,
        serão resolvidos pelo procedimento arbitral, conforme a lei de arbitragem n° 9.307/96, adotando a regra do direito,
        por intermédio do TACOM - PR – Câmara de Arbitragem, Conciliação e Mediação do Paraná, localizado na Avenida 
        Paissandu, n° 1062, Zona 03, na cidade de Maringá, Estado do Paraná, CNPJ/MF sob o n° 05.475.080/0001-55, 
        de acordo com seu regulamento, regimento e demais normas de procedimentos, 
        por um árbitro integrante de seu quadro, no idioma português.
    </p>

    <p>
        E, por estarem de acordo, justos e contratados, assinam o presente em VIAS vias, de igual teor e forma, 
        na presença das testemunhas abaixo.
    </p>

    <p>{{$interprise->city}} - {{$interprise->state}}, {{$now}}</p><br><br><br>


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

    <!--@foreach ($companies as $company)
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
    @endforeach-->
    

</body>
</html>
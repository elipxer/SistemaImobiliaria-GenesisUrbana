<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contato Troca de Proprietario</title>
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
    <h2 class="title">CONTRATO PARTICULAR DE CESSÃO DE DIREITOS DE IMÓVEL -  {{$sale->contract_number}}</h2><br>
    <p>
        Pelo presente Instrumento Particular de Cessão de Direitos, Vantagens Obrigações e Responsabilidades, 
        nesta cidade de Londrina, pelas partes adiante nomeadas e qualificadas a saber: como:
    </p><br>
    
    <h4> Outorgante(s) Cedente(s):</h4>
    @foreach ($oldClients as $client)
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
            neste ato representada por 
            @if($client['client']['client_representative'] != null)
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

    <h4> Outorgado(a)(s) Cessionário(a)(s):</h4>
    @foreach ($newClients as $client)
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
            {{$client['client']->street != ""?$client['client']->street:'RUA NÃO INFORMADA'}}, 
            {{$client['client']->number != ""?$client['client']->number:'NUMERO NÃO INFORMADO'}} 
            {{$client['client']->neighborhood!= ""?$client['client']->neighborhood:'BAIRRO NÃO INFORMADO'}}, 
            {{$client['client']->city!= ""?$client['client']->city:'CIDADE NÃO INFORMADA'}} - 
            {{$client['client']->state!= ""?$client['client']->state:'ESTADO NÃO INFORMADO'}}, - {{$client['client']->state}}, inscrita no CNPJ/MF sob o n.º 
            {{$client['client']->cnpj}}
           
            @if($client['client']['client_representative'] != null)
                ,neste ato representada por 
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

    <h4> Interveniente Anuente Original: </h4>
    @foreach ($companies as $company)
    <p style="text=align:rigth;;width: 70%;">
        {{$company->company_name}}, pessoa(s) jurídica(s) de direito privado, com sede {{$company->street}}, {{$company->number}}
        {{$company->neighborhood}} {{$company->city}} - {{$company->state}}, 
        inscrita no CNPJ/MF sob o n.º {{$company->cnpj}}; neste ato representada por {{$company->representative_name}}, 
        {{$company->representative_nationality==1?'Brasileiro':'Estrageniro'}}, {{$company->representative_occupation}}, 
        portador da Cédula de Identidade {{$company->representative_rg}} e CPF/MF {{$company->representative_cpf}};
    </p>
    @endforeach

    <h4>Interveniente Anuente:</h4>
    @foreach ($companies as $company)
    <p style="text=align:rigth;;width: 70%;">
        {{$company->company_name}}, pessoa(s) jurídica(s) de direito privado, com sede {{$company->street}}, {{$company->number}}
        {{$company->neighborhood}} {{$company->city}} - {{$company->state}}, 
        inscrita no CNPJ/MF sob o n.º {{$company->cnpj}}; neste ato representada por {{$company->representative_name}}, 
        {{$company->representative_nationality==1?'Brasileiro':'Estrageniro'}}, {{$company->representative_occupation}}, 
        portador da Cédula de Identidade {{$company->representative_rg}} e CPF/MF {{$company->representative_cpf}};
    </p>
    @endforeach
    
    <p style="text=align:rigth;;width: 70%;">
        Que tem entre si justo e contratado, o que mutuamente outorgam, 
        aceitam e assinam, convencionados pelas Cláusulas e Condições seguintes.
    </p>

    <p>
        <b><u>Cláusula Primeira</u></b> - Que o(a)(s) <b>Outorgante(s) Cedente(s)</b> declara(m)-se senhor(a)(es) 
        e legítimo(a)(s) possuidor(a)(es) dos direitos, vantagens, obrigações e responsabilidades 
        de PERCENTUAL% (extenso) do imóvel constituído do <b>lote</b> {{$lot->lot_number}} da <b>Quadra</b> {{$lot->block}} 
        do {{$interprise->name}}, <b>objeto da Matrícula</b> {{$lot->number_register}} <b> da Proposta e Contrato</b> 
        {{$sale->contract_number}} <b>com data de</b> {{date('d/m/Y',strtotime($sale->begin_contract_date))}}, que é entregue ao(à)(s) 
        <b>Outorgado(a)(s) Cessionário(a)(s)</b> de imediato, COM(SEM) saldo devedor junto à <b>Interveniente Anuente Original</b>.
    </p>

    <p>
        <b><u>Cláusula Segunda</u></b> - Que assim sendo vem o(a)(s)<b> Outorgante(s) Cedente(s)</b>, 
        via deste instrumento e na melhor forma de direito, CEDER E TRANSFERIR, como de fato tem CEDIDO E TRANSFERIDO, 
        o referido objeto para a pessoa do(a)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b>, com todos os seus referidos direitos, 
        vantagens, obrigações e responsabilidades decorrentes do aludido objeto do presente instrumento.
    </p>

    <p>
        <b>Parágrafo único</b> – Fica(m) o(a)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> e o(a)(s) <b>Interveniente(s) 
        Anuente(s) Solidário(a)(s)</b> declarados(as) cada um senhores(as) e legítimos(as) possuidores(as) 
        de partes iguais dos direitos, vantagens, obrigações e responsabilidades do referido imóvel. 
    </p>

    <p>
        <b><u>Cláusula Terceira</u></b> - Que fica(m) o(a)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> imitido(a)(s) 
        na posse direta, direito, ação, uso, gozo e servidão sobre o objeto deste instrumento, correndo por conta única 
        e exclusiva deste, a partir do recebimento do aludido objeto deste instrumento, ficando o mesmo sub-rogado em 
        todas as taxas, ônus, impostos, custas, prestações, emolumentos, ou a quem de direito e demais despesas com 
        escrituração, registros, averbações, transferências, certidões negativas e outras que incidam ou venham a incidir 
        sobre o referido objeto, mesmo que lançadas e/ou cobradas em nome do(a)(s) <b>Outorgante(s) Cedente(s)</b>.
    </p>

    <p>
        <b><u>Cláusula Quarta</u></b> - Que o objeto deste instrumento é entregue ao(à)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b>
        em conformidade com o disposto na Cláusula Terceira deste instrumento, livre e desembaraçado de quaisquer ônus 
        judiciais e/ou extrajudiciais, em dia com todas as taxas, impostos, custas, emolumentos, condomínio, 
        inclusive taxas de água, luz, gás e IPTU até a data da assinatura deste instrumento..
    </p>

    <p>
        <b>Parágrafo Único</b> - Caso existam débitos de IPTU, ou taxas de concessionárias de água e energia elétrica, 
        dos exercícios anteriores da data de assinatura deste instrumento, o(a)(s) <b>Outorgante(s) Cedente(s)</b> se 
        responsabilizará(ao) pelo pagamento integral destes débitos.
    </p>

    <p>
        <b><u>Cláusula Quinta</u></b> - Que o(a)(s) <b>Outorgante(s) Cedente(s)</b> se obriga(m) e se compromete(m) a prestar 
        toda e qualquer assistência, bem como sua(s) presença(s) se solicitada(a) for(em) para regularização e/ou 
        transferência definitiva do objeto deste instrumento a favor do(a)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> 
        ou a quem este(a)(s) indicar(em) independente da outorga de procuração pública, sem reclamação por parte 
        do(a)(s) <b>Outorgante(s) Cedente(s)</b>, futuramente, por si, seus herdeiros e sucessores, de importâncias devidas, 
        além das aqui ajustadas.
    </p>

    <p>
        <b><u>Cláusula Sexta</u></b> - Que na hipótese de SINISTRO E/OU PARTILHA DE BENS, fica(m) o(a)(s) <b>Outorgado(a)(s) 
        Cessionário(a)(s)</b> ou seus beneficiários e sucessores, autorizado(s) a se habilitar(em) no respectivo processo e 
        requerer(em) junto ao cartório e/ou juízo competente a carta de Adjudicação expedida a seu(s) favor(es), 
        relativamente ao objeto deste Instrumento, podendo para tanto: constituir(em) Advogado(s) com os poderes da 
        cláusula “Ad-Judicia” e os mais necessários perante qualquer instância, foro ou tribunal, em juízo ou fora dele,
         podendo para tanto: acordar, discordar, transigir, recorrer, desistir, propor, e variar de ações e recursos, 
         receber citações, prestar declarações informações, apresentar provas, abrir, acompanhar e dar andamento a 
         processos, pedir vistas, cumprir exigências, tomar ciência de despachos, requerer alegar e assinar o que 
         preciso for, juntar, apresentar e retirar documentos, requerer certidões, alvarás diversos e demais autorizações, 
         assinar termos, requerimentos e praticar os demais atos aos fins deste instrumento, 
        o que será dado sempre por bom, firme e valioso, por si, seus herdeiros e sucessores.
    </p>

    <p>
        <b><u>Cláusula Sétima</u></b> - Que o presente instrumento é feito entre as partes contratantes por si, 
        seus herdeiros e sucessores, em CARÁTER IRREVOGAVEL E IRRETRÁTAVEL, não dando margem a arrependimento, 
        ressalvando-se o que preceituam os artigos 417 a 420 do Código Civil Brasileiro, obrigando-se estas mesmas 
        partes a manterem o presente sempre bom, firme e valioso, respondendo as partes na forma da lei, pelos riscos 
        da Evicção de Direitos, se chamados forem à Autoria a qualquer tempo e época; podendo, 
        porém ser rescindido por inadimplemento de qualquer de suas cláusulas e ou condições.
    </p>

    <p>
        <b><u>a)</u></b> No caso da rescisão vier a ocorrer por culpa da <b>Interveniente Anuente Original</b>, 
        deverá esta devolver toda e qualquer importância que houver recebido, com as mesmas correções do Parágrafo 
        Primeiro da Cláusula Quinta, e parceladamente nas mesmas condições em que recebeu do(a)(s) <b>Outorgante(s) Cedente(s)</b> 
        e doravante do(a)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> e <b>Interveniente(s) Anuente(s)</b>.
    </p>

    <p>
        <b><u>b)</u></b> No caso da rescisão vier a ser causada por desistência ou inadimplência por culpa do(a)(s) 
        <b>Outorgado(a)(s) Cessionário(a)(s)</b> e <b>Interveniente(s) Anuente(s)</b>, e após ter(em) sido 
        devidamente notificado(a)(s), conforme preceitua o Parágrafo Primeiro, do art. 32, da Lei 6.766, de 19.12.79, 
        ficará a <b>Interveniente Anuente Original</b> investida no direito de ajuizar ação de reintegração de posse, 
        com pedido de liminar e restituir ao(à)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> e <b>Interveniente(s) Anuente(s)</b> o valor de que 
        deste(a)(s) tiver recebido como pagamento de preço, sem correção, deduzidas, entretanto, em seu favor, a verbas equivalentes a:.
    </p>

    <p>
        I. 6,0% (seis por cento) do valor do contrato inicial, atualizados sobre o valor do contrato original, 
        a título de comissão de corretagem deste negócio; 
    </p>

    <p>
        II .20,0% (vinte por cento) dos valores principais pagos pelo(a)(s) <b>Outorgante(s) Cedente(s)</b> e 
        doravante do(a)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> e <b>Interveniente(s) Anuente(s)</b>, 
        com os devidos reajustes, atualizados monetariamente, a título de despesas com análise e controle cadastral, 
        elaboração de contrato, consultas diversas, e de despesas administrativas de cobranças. 
    </p>

    <p>
        III. Os valores relativos a juros e multas decorrentes de atrasos nos pagamentos das parcelas. 
    </p>

    <p>
        IV. Os valores, devidamente corrigidos, que a Interveniente Anuente Original houver antecipado com 
        despesas havidas para a rescisão deste contrato, tais como custas, emolumentos, notificações, intimações, 
        honorários advocatícios, etc.
    </p>

    <p>
        Parágrafo Primeiro – Caso os valores pagos pelo(a)(s) <b>Outorgante(s) Cedente(s)</b> e doravante do(a)(s) <b>
        Outorgado(a)(s) Cessionário(a)(s)</b> e <b>Interveniente(s)
        Anuente(s)</b> sejam inferiores aos valores dos itens “I”, “II”, “III” e “IV”, este(a)(s) deverá(ão) 
        completar o pagamento para se fazer frente a estas despesas.
    </p>

     
    <p>
        Parágrafo Segundo – Os valores a serem restituídos ao(à)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> e 
        <b>Interveniente(s) Anuente(s)</b> serão pagos a este em igual número de parcelas que o mesmo efetuou à 
        <b>Interveniente Anuente Original</b>, de forma sucessiva, sem correção, a partir de 30 (trinta) dias 
        da rescisão contratual.
    </p>

    <p>
        <b><u>Cláusula Oitava</u></b> - Quaisquer controvérsias, divergências ou conflitos resultantes desse contrato 
        ou incidentes nas cláusulas do mesmo, serão resolvidos pelo procedimento arbitral, conforme a lei de arbitragem 
        n° 9.307/96, adotando a regra do direito, por intermédio do TACOM - PR – Câmara de Arbitragem, Conciliação e 
        Mediação do Paraná, localizado na Avenida Paissandu, n° 1062, Zona 03, na cidade de Maringá, Estado do Paraná, 
        CNPJ/MF sob o n° 05.475.080/0001-55, de acordo com seu regulamento, regimento e demais normas de procedimentos, 
        por um árbitro integrante de seu quadro, no idioma português.
    </p>

    <p>
        <b><u>Cláusula Nona</u></b> -  O(A)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b> aceita(m) este Contrato Particular 
        de Cessão de Direitos como aqui se contém; e pelo(a)(s) <b>Outorgante(s) Cedente(s)</b> que aceita(m) o 
        referido contrato, o qual leu(ram) e compreendeu(ram), na qualidade de proprietário(a)(s) do referido objeto do presente, 
        como declara estar de pleno acordo com a presente Cessão de Direitos feita ao(à)(s) <b>Outorgado(a)(s) Cessionário(a)(s)</b>.
    </p>

    <p>E, por estarem acordes, assinam o presente Instrumento em VIAS (extenso) vias de igual teor e forma, 
        para que produza seus efeitos legais e em direito permitido.</p>

        <p>{{$interprise->city}} - {{$interprise->state}}, {{$now}}</p><br><br><br>

        @foreach ($companies as $company)
        <div class="container_signature__row">
            <div class="container_signature__item">
                <b>EMPRESAS PROPRIETÁRIAS</b>
            </div>
    
            <div class="container_signature__item signature_line">
                <div class="container_signature_line">
                    {{$company->company_name}}
                </div>
            </div>
        </div><br><br>
        @endforeach
        
        @foreach ($oldClients as $client)
        @if($client['client']->kind_person==1)
            <div class="container_signature__row">
                <div class="container_signature__item">
                    <b>CLIENTE QUE SAI</b>
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
                    <b>CLIENTE QUE SAI</b>
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

        @foreach ($newClients as $client)
        @if($client['client']->kind_person==1)
            <div class="container_signature__row">
                <div class="container_signature__item">
                    <b>CLIENTE QUE ENTRA</b>
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
                    <b>CLIENTE QUE ENTRA</b>
                </div>
    
                <div class="container_signature__item signature_line">
                    <div class="container_signature_line">
                        {{$client['client']->representative_name}}
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
    
        <p>
            <b>Testemunhas:</b><br>
            1)
        </p><br><br>
        <p>
            2)
        </p><br>    

</body>
</html>
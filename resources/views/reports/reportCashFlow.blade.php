<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Relatorio</title>
    <style>
        *{
            box-sizing:border-box;
            font-family: "Arial Narow";
        }

        .title{
            font-family: "Arial Narow";
            font-size: 20px;
            text-align: center;
        }

        .cabecalho{
            max-height: 125px;
            max-width: 95%;
        }

        .cabecalho__logo{
            width: 125px;
            height: 125px;
            float: left;
            margin: 5px;
            border: 1px solid black;;
        }

        .cabecalho__titulo{
            text-align: center;
            float: left;
            height: 125px;
            line-height: 125px;
        }

        .info{
            width: 100%;
            max-width: 95%;
        }

        .info--80{
            width: 85%;
            max-width: 85%;
            margin-left: 100px
        }

        .info_item{
            width: 100%;
            float: left;
        }

        .info_item--w-33{
            width: 33.33%;
        }

        .info_conteudo{
            width: 100%;
            height: 25px;
        }

        .info_conteudo--right{
            width: 100%;
            height: 25px;
            float: right;
        }

        .info_titulo{
            display: block;
            background-color:rgb(169,208,142);
            color: black;
            font-size: 18px;
            width: 77%;
            height: 52px;
            text-align:right;
            float: left;
            line-height: 50px;
            padding-right: 15px; 

        }

        .info_titulo--73{
            width: 73.5%;
        }

        .info_titulo--left{
            text-align: left;
            padding-right: 0px;
            padding-left: 15px;  
        }

      
        .info_texto{
            width: 20%;
            height: 50px;
            text-align: center;
            color: black;
            float: left;
            line-height: 50px;
            font-size: 20px;
            border: 1px solid gray;
        }

        .info_texto--23{
            width: 23.5%;
            height: 50px;
            text-align: center;
            color: black;
            float: left;
            line-height: 50px;
            font-size: 20px;
            border: 1px solid gray;

        }

        table{
            border-right: 1px solid gray;
            border-top: 1px solid gray;

        }
        .tabelaCabecalho th{
            padding: 5px;
            background-color: rgb(191, 191, 191);
            border-collapse: collapse;
            font-family: "Arial Narow" !important;
        }

        .tabelaLinha td{
            border-bottom: 1px solid gray;
            border-left: 1px solid gray;
            padding: 10px;
            font-family: "Arial Narow" !important;
        }
    </style>
</head>
<body>
    <div class="title">FLUXO DE CAIXA (entre: {{date('d/m/Y',strtotime($startDate))}} e {{date('d/m/Y',strtotime($finalDate))}})</div><br>
    <hr>
    <div class="info">
        <div class="info_item">
            <div class="info_conteudo">
                <div class="info_titulo">Recebimentos</div>
                <div class="info_texto">
                    {{$valuePadParcels!="0"?'R$'.number_format($valuePadParcels,2):'0,00'}}
                </div>
            </div>

            <div class="info_conteudo">
                <div class="info_titulo">Pagamentos</div>
                <div class="info_texto">
                    {{$padProgramedPaymentsValue!="0"?'R$'.number_format($padProgramedPaymentsValue,2):'0,00'}}
                </div>
            </div>

            <div class="info_conteudo">
                <div class="info_titulo">Saldo do Periodo</div>
                <div class="info_texto">
                    {{$balance!="0"?'R$'.number_format($balance,2):"0,00"}}
                </div>
            </div>
        </div>
     </div><br><br><br><br>

    <div class="info">
        <div class="info_conteudo">
            <div class="info_titulo info_titulo--left">Recebimentos</div>
            <div class="info_texto">
                {{$valuePadParcels!="0"?'R$'.number_format($valuePadParcels,2):'0,00'}}
            </div>
        </div>
    </div><br>

    @foreach ($sales as $sale)
    <div class="info info--80">
        <div class="info_conteudo">
            <div class="info_titulo info_titulo--left info_titulo--73">{{$sale['client']->name!=null?$sale['client']->name:$sale['client']->company_name}}</div>
            <div class="info_texto info_texto--23">
                {{"R$".$sale['total']}}
            </div>
            <table width="100%" cellspacing="0">
                <tr class="tabelaCabecalho">
                    <th>Contrato</th>
                    <th>Num</th>
                    <th>Valor</th>
                    <th>Valor Pago</th>
                    <th>Data Pago</th>
                </tr>
                
                    @foreach ($sale['allParcels'] as $parcel)
                        <tr class="tabelaLinha">
                            <td>{{$sale['sale']->contract_number}}</a></td>
                            <td>{{$parcel->num."/".$sale['sale']->parcels}}</td>
                            <td>{{$parcel->value}}</td>
                            <td>{{$parcel->pad_value}}</td>
                            <td>{{date('d/m/Y',strtotime($parcel->pad_date))}}</td>
                        </tr>
                    @endforeach    
            </table><br>
        </div>
    </div>
    @endforeach

    <br><br><br><br>

    <div class="info">
        <div class="info_conteudo">
            <div class="info_titulo info_titulo--left">Pagamentos</div>
            <div class="info_texto">
                {{$padProgramedPaymentsValue!="0"?'R$'.number_format($padProgramedPaymentsValue,2):'0,00'}}
            </div>
        </div>
    </div><br>

    @foreach ($internalAccounts as $internal)
    <div class="info info--80">
        <div class="info_conteudo">
            <div class="info_titulo info_titulo--left info_titulo--73">{{$internal['internal']->name}}</div>
            <div class="info_texto info_texto--23">
                {{"R$".$internal['total']}}
            </div>
            <table width="100%" cellspacing="0">
                <tr class="tabelaCabecalho">
                    <th>Conta Interna</th>
                    <th>Fornecedor</th>
                    <th>Valor Total</th>
                    <th>Valor Parcela</th>
                    <th>Num Parcela</th>
                    <th>Valor Pago</th>
                    <th>Data Pago</th>
                </tr>
                    @foreach ($internal['programedPayments'] as $programedPayment)
                        <tr class="tabelaLinha">
                            <td>{{$programedPayment->internalAccount}}</td>
                            <td>{{$programedPayment->nameProvider!=""?$programedPayment->nameProvider:$programedPayment->companyProvider}}</td>
                            <td>{{$programedPayment->totalValue}}</td>
                            <td>{{$programedPayment->value}}</td>
                            <td>{{$programedPayment->num."/".$programedPayment->totalNumberParcels}}</td>
                            <td>{{$programedPayment->value_payment}}</td>
                            <td>{{date('d/m/Y',strtotime($programedPayment->payment_date))}}</td>
                        </tr>
                @endforeach
            </table><br>
        </div>
    </div>
    @endforeach


    </body>
</html>
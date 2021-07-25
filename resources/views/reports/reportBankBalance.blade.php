<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Relatorio</title>
    <style>
        *{
            font-family: "Arial Narow";
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

        .title{
            font-family: "Arial Narow";
            font-size: 20px;
            text-align: center;
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
            float: right !important;
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

        .info_titulo__item{
            float: left;
            width: 48%;
        }

        .info_titulo__item--left{
            text-align: left;
            padding-left: 10px;
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
            margin-right: 3px;

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
    <div class="title">SALDOS BANCARIOS (entre: {{date('d/m/Y',strtotime($startDate))}} e {{date('d/m/Y',strtotime($finalDate))}})</div><br>
    <hr>
    <div class="info">
        <div class="info_item">
            <div class="info_conteudo">
                <div class="info_titulo">Saldo do período:</div>
                <div class="info_texto">
                    {{number_format($balanceTotal,2)}}
                </div>
            </div>
        </div>
     </div><br><br><br><br>

   
    @foreach ($bankRegisters as $key=>$register)
        <div class="info">
           <div class="info_item">
                <div class="info_conteudo">
                    <div class="info_titulo">
                        <div class="info_titulo__item info_titulo__item--left">{{$register['bank']->name}}</div>
                        <div class="info_titulo__item">Saldo</div>
                    </div>
                    <div class="info_texto">
                        {{number_format($register['balance'],2)}}
                    </div>
               
                    <div class="info_item info--80">
                        <div class="info_conteudo">
                            <table width="100%" cellspacing="0">
                                <tr class="tabelaCabecalho">
                                    <th>Data</th>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                </tr>
                                    @foreach ($register['registers'] as $registerItem)
                                        <tr class="tabelaLinha">
                                            <td>{{date('d/m/Y',strtotime($registerItem['date']))}}</td>
                                            <td>{{$registerItem['description']}}</td>
                                            <td>{{$registerItem['type']==3 || $registerItem['type']==1?'-'.$registerItem['value']:$registerItem['value']}}</td>
                                        </tr>
                                    @endforeach    
                            </table><br><br>
                        </div>
                    </div>
                </div>
            </div>
                <hr>
        @endforeach
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Relatorio</title>
    <style>
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

        .info_item{
            width: 25%;
            float: left;
        }

        .info_item--w-50{
            width: 50%;
        }

        .info_item--w-33{
            width: 33.33%;
        }

        .info_conteudo{
            width: 100%;
            height: 25px;
            border: 1px solid black;
        }

        .info_titulo{
            display: block;
            background-color:black;
            color: white;
            float: left;
            padding: 5px;
            font-size: 10px;
            font-weight: bold;
        
        }
        .info_texto{
            float: left;
            text-align: center;
            padding: 5px;
            
        }

        table{
            border-right: 1px solid black;
            border-top: 1px solid black;

        }
        .tabelaCabecalho th{
            padding: 5px;
            background-color: black;
            color: white;
            font-weight: bold;
            border-collapse: collapse;
        
        }

        .tabelaLinha td{
            border-bottom: 1px solid black;
            border-left: 1px solid black;
            padding: 10px;

        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Todas Parcelas {{$startDate != ""?" Período: "
    .date('d/m/Y',strtotime($startDate))." até ".date('d/m/Y',strtotime($finalDate)):""}}</h2>
    <hr>
    
    <table width="100%" cellspacing="0">
        <tr class="tabelaCabecalho">
            <th>Contrato</th>
            <th>Numero</th>
            <th>Nosso Numero</th>
            <th>Tipo</th>
            <th>Vencimento</th>
            <th>Valor</th>
            <th>Acrescimo</th>
            <th>Atualizado</th>
            <th>Valor Pago</th>
            <th>Data Pagamento</th>
            <th>Status</th>
        </tr>
        @foreach ($parcels as $parcel)
            <tr class="tabelaLinha">
                <td>{{$parcel->contract_number}}</td>
                <td style="width: 50px">{{$parcel->num."/".$parcel->totalParcels}}</td>
                <td style="min-width: 150px">{{$parcel->our_number}}</td>
                <td>
                    @if ($parcel->type==1)
                        Financiamento - {{$parcel->prefix}}
                    @elseif($parcel->type==2)
                        Taxas - {{$parcel->prefix}}
                    @elseif($parcel->type==3)
                        Refinanciamento - {{$parcel->prefix}}
                    @elseif($parcel->type==5) 
                        {{$parcel->prefix}}    
                    @endif
                </td>
                <td>{{date('d/m/Y',strtotime($parcel->date))}}</td>
                <td style="min-width: 90px">{{$parcel->value}}</td>
                <td>{{!empty($parcel->added_value)?$parcel->added_value:'0,00'}}</td>
                <td>{{!empty($parcel->updated_value)?$parcel->updated_value:'0,00'}}</td>
                <td style="min-width: 100px">{{!empty($parcel->pad_value)?$parcel->pad_value:'0,00'}}</td>
                <td>{{!empty($parcel->pad_date)?date('d/m/Y',strtotime($parcel->pad_date)):'Não pago'}}</td>
                <td style="min-width: 125px;">
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
    </table>
    <hr>
</body>
</html>
@extends('adminlte::page')
@extends('layouts/searchSale')
@extends('layouts/modal')


<form action="{{route('payParcel')}}" id="payParcelForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="id" id="idParcel" >

    <div class="form-group">
        <label for="pad_date">Valor Para Pagamento</label>
        <div class="form-control" id="valueParcel"></div>
    </div>

    <div class="form-group">
        <label for="pad_date">Data Pagamento*</label>
        <input class="form-control" type="date" name="pad_date" value="{{date('Y-m-d')}}" min="{{date('Y-m-d')}}">
    </div>

    <div class="form-group">
        <label for="pad_description">Forma Pagamento*</label>
        <textarea class="form-control" maxlength="450" name="pad_description"></textarea>
    </div>

    <div class="form-group">
        <label for="pad_value">Valor Pagamento*</label>
        <input class="form-control money" id="pad_value" type="text" name="pad_value">
    </div>

    <div class="form-group">
        <label for="banks">Escolha o Banco:</label>
        <table class="table table-bordered table-hover dataTable dtr-inline" role="grid">
            <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banks as $bank)
                    <tr>
                        <td><input type="radio" name="idBank" class="idBank" 
                            value="{{$bank->id}}"></td>
                        <td>{{$bank->name}}</td>
                        <td>{{$bank->description}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <center><input type="submit" class="btn btn-success btn-lg w-25" id="btnPayParcel" value="Salvar"></center>
</form>

@section('content')
    <div class="info__title">Todas Parcelas</div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-0">
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 d-flex">
                            <form method="GET" class="w-100 d-flex">
                                <input type="hidden" value={{$contractCheck}} name="contractCheck">
                                <input type="hidden" value={{$deadlineCheck}} name="deadlineCheck">
                                <input type="hidden" value={{$paymentDateCheck}} name="paymentDateCheck">
                                <input type="hidden" value={{$startDatePayment}} name="startDatePayment">
                                <input type="hidden" value={{$finalDatePayment}} name="finalDatePayment">
                               
                                <div class="form-group" >
                                    <label>Periodo (Data Vencimento)</label><br>
                                    <div class="form-group" style="display:flex;">
                                        <input class="form-control" style="width: 40%" required value="{{$startDate}}" type="date" name="startDate">
                                        <input class="form-control" style="width: 40%" required value="{{$finalDate}}" type="date" name="finalDate">
                                        <input class="btn btn-success" type="submit" value="Filtrar">
                                    </div>
                                </div>
                            </form>

                            <form method="GET" class="w-100 d-flex">
                                <input type="hidden" value={{$contractCheck}} name="contractCheck">
                                <input type="hidden" value={{$deadlineCheck}} name="deadlineCheck">
                                <input type="hidden" value={{$paymentDateCheck}} name="paymentDateCheck">
                                <input type="hidden" value={{$startDate}} name="startDate">
                                <input type="hidden" value={{$finalDate}} name="finalDate">
                                
                                <div class="form-group" >
                                    <label>Periodo (Data Pagamento)</label><br>
                                    <div class="form-group" style="display:flex;">
                                        <input class="form-control" style="width: 40%" required value="{{$startDatePayment}}" type="date" name="startDatePayment">
                                        <input class="form-control" style="width: 40%" required value="{{$finalDatePayment}}" type="date" name="finalDatePayment">
                                        <input class="btn btn-success" type="submit" value="Filtrar">
                                    </div>
                                </div>
                            </form>
                            
                            <form action="{{route('parcelsReport')}}" method="POST" target="_blank" style="margin-top: 22px;">
                                @csrf
                                @if($startDate!=="")
                                    <input class="form-control" value="{{$startDate}}" type="hidden" name="startDate">
                                @endif

                                @if($finalDate!=="")
                                    <input class="form-control" value="{{$finalDate}}" type="hidden" name="finalDate">
                                @endif

                                @if($startDatePayment!=="")
                                    <input class="form-control" value="{{$startDatePayment}}" type="hidden" name="startDatePayment">
                                @endif

                                @if($finalDatePayment!=="")
                                    <input class="form-control" value="{{$finalDatePayment}}" type="hidden" name="finalDatePayment">
                                @endif
                                <input class="form-control" value="{{$status}}" type="hidden" name="status">
                                <input class="form-control" value="{{$contract_number}}" type="hidden" name="contract_number">

                                <div class="form-group">
                                    <label></label><br>
                                    <input type="submit" class="btn btn-success" value="Gerar Relatorio">
                                </div>
                            </form>
                        </div>
                        <div class="col-0">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form class="d-flex w-100" style="margin-top: 22px; 
                    width:550px;height:50px; border-bottom:1px solid #ccc; padding-bottom:25px;">
                    @if($startDate!=="")
                        <input class="form-control" value="{{$startDate}}" type="hidden" name="startDate">
                    @endif

                    @if($finalDate!=="")
                        <input class="form-control" value="{{$finalDate}}" type="hidden" name="finalDate">
                    @endif

                    @if($startDatePayment!=="")
                        <input class="form-control" value="{{$startDatePayment}}" type="hidden" name="startDatePayment">
                    @endif

                    @if($finalDatePayment!=="")
                        <input class="form-control" value="{{$finalDatePayment}}" type="hidden" name="finalDatePayment">
                    @endif
                  
                    <label style="line-height:50px; margin-right:10px; font-size:15px;">Ordenar por:</label>
                    <div class="form-group m-1 h-100" style="line-height:40px;">
                        <input type="checkbox" {{$contractCheck?'checked':''}} name="contractCheck" class="md-1">
                        <span>Contrato</span>
                    </div>

                    <div class="form-group m-1 h-100" style="line-height:40px;">
                        <input type="checkbox" {{$deadlineCheck?'checked':''}} name="deadlineCheck" class="md-1">
                        <span>Data Vencimento</span>
                    </div>

                    <div class="form-group m-1 h-100" style="line-height:40px;">
                        <input type="checkbox" {{$paymentDateCheck?'checked':''}}  name="paymentDateCheck" class="md-1">
                        <span>Data Pago</span>
                    </div>

                    <div class="form-group">
                        <label></label><br>
                        <input type="submit" class="btn btn-success ml-2 mt-1" style="height: 30px; line-height:15px;" value="Filtrar">
                    </div>
                </form>
                <table class="table table-bordered table-hover dataTable dtr-inline">
                    <thead class="table table-dark">
                        <th>Ações</th>
                        <th>Contrato</th>
                        <th>Numero</th>
                        <th>Empreendimento</th>
                        <th>Tipo</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Valor Pago</th>
                        <th>Data Pagamento</th>
                        <!--<th>Boleto</th>-->
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <form method="GET" class="formFilter">
                            <input class="form-control" value="{{$startDate}}" type="hidden" name="startDate">
                            <input class="form-control" value="{{$finalDate}}" type="hidden" name="finalDate">
                            <input type="hidden" value={{$contractCheck}} name="contractCheck">
                            <input type="hidden" value={{$deadlineCheck}} name="deadlineCheck">
                            <input type="hidden" value={{$paymentDateCheck}} name="paymentDateCheck">
                            <input type="hidden" value={{$startDatePayment}} name="startDatePayment">
                            <input type="hidden" value={{$finalDatePayment}} name="finalDatePayment">
                            <tr>
                                <td></td>
                                <td>
                                    <input class="form-control" type="number" name="contract_number" value="{{$contract_number}}">
                                </td>
                                <td>
                                    <input class="form-control" type="number" name="num" value="{{$num}}">
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="interpriseName" value="{{$interpriseName}}">
                                </td>
                               
                                <td>
                                    <select name="type" class="form-control selectFilter">
                                        <option value="0"></option>
                                        <option value="1" {{$type==1?'selected':''}}>Financiamento</option>
                                        <option value="2" {{$type==2?'selected':''}}>Taxa</option>
                                    </select>
                                </td>
                                <td >
                                    <input class="form-control" type="date" name="date" value="{{$date}}">
                                </td>
                                
                                <td></td>
                                <td></td>
                                <td><input class="form-control" type="date" name="pad_date" value="{{$pad_date}}"></td>
                                <td>
                                    <select name="status" class="form-control selectFilter">
                                        <option value="0"></option>
                                        <option {{$status==1?'selected':''}} value="1">Paga</option>
                                        <option {{$status==2?'selected':''}} value="2">Não Paga</option>
                                        <option {{$status==3?'selected':''}} value="3">Atrasada</option>
                                    </select>
                                </td>
                                <input type="submit" style="display: none;">
                            <tr>
                        </form>      
                        @foreach ($parcels as $parcel)
                            <tr class={{$parcel->status==3?"table-danger":''}}
                                {{$parcel->type==2 || $parcel->type==3 ?"table-primary":''}}
                                {{$parcel->status==1?"table-success":''}}
                                {{$parcel->status==4?"table-warning":''}}
                                {{$parcel->type==5?"table-info":''}}>
                                <td class="buttons_area">
                                    @if(Auth::user()->type==1)
                                        <div class="btnActions fas fa-receipt btnPayParcel" title="Pagar" 
                                        data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip" 
                                        idParcel={{$parcel->id}} value={{$parcel->value}}></div>
                                    @endif
                                </td>
                                <td style="min-width: 150px"> 
                                    <a style="color: blue;" href="{{route('seeSale',['idSale'=>$parcel->idSale])}}">
                                    {{$parcel->contract_number}}</a>
                                </td>
                                <td style="width: 50px">{{$parcel->num."/".$parcel->totalParcels}}</td>
                                <td>{{$parcel->interprise_name}}</td>

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
                                <td style="min-width: 100px">
                                    @if($parcel->send_bankSlip==0)
                                        {{!empty($parcel->pad_value)?str_replace(['.','.'],['',','],$parcel->pad_value):'0,00'}}
                                    @else
                                        {{!empty($parcel->pad_value)?str_replace(['.','.'],[',',','],$parcel->pad_value):'0,00'}}
                                    @endif
                                </td>
                                <td>{{!empty($parcel->pad_date)?date('d/m/Y',strtotime($parcel->pad_date)):'Não pago'}}</td>
                                <!--<td>
                                    @if($parcel['bankSlip'])
                                        <a href="{{asset('storage/bankSlip/'.$parcel['bankSlip']->path)}}" download>Download Boleto</a>
                                    @endif
                                </td>-->
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function(){
            var payParcelForm= $('#payParcelForm');
            $('.btnPayParcel').each(function(){
                $(this).on('click',function(){
                    let idParcel=$(this).attr('idParcel');
                    let value=$(this).attr('value');
                    $('#valueParcel').html(value);
                    $('#pad_value').val(value);
                    $('#idParcel').val(idParcel);
                    $(payParcelForm).css('display','block');
                    $('#btnAddModal').off();
                    $('#btnEditModal').off();
                    $("#modalAcoes").find(".modal-body").empty();
                    $("#modalAcoes").find(".modal-body").append($(payParcelForm));
                    $("#btnEditModal").hide();
                    $("#btnAddModal").hide();
                    $("#modalAcoes").find(".modal-title").html('Pagar Parcela');
                    
                    $('#btnPayParcel').on('click',function(event){
                        event.preventDefault();
                        if(verifyPayParcel()){
                            $(payParcelForm).trigger('submit');
                        }
                    })
                })
            });

            function verifyPayParcel() {
                let verify=true;

                let paymentDate=$('#payParcelForm').find('input[name=pad_date]').val();
                let pad_description=$('#payParcelForm').find('textarea[name=pad_description]').val();
                let pad_value=$('#payParcelForm').find('input[name=pad_value]').val();
                let checkBanks=()=>{
                let isChecked=false;
                    $('#payParcelForm').find('.idBank').each(function(){
                        if($(this).prop('checked')){
                            isChecked=true;
                        }
                    })
                    return isChecked;
                }

                if(paymentDate==="" || pad_description==="" || pad_value===""){
                    Swal.fire({
                        icon: 'error',
                        text: 'Preencha os valores obrigatórios!',
                        customClass: 'mySweetalert',
                    })   
                    verify=false;
                
                }else if(checkBanks()===false){
                    Swal.fire({
                        icon: 'error',
                        text: 'Escolha algum banco!',
                        customClass: 'mySweetalert',
                    })   
                    verify=false;
                }

                    return verify;
                }
            })
        </script>
    
@endsection
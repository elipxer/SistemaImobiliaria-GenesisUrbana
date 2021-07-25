@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <div class="info__title">Gerar Remessas ({{$financial_account->name}})</div>
                </div>
                  
                <div class="col-6" style="text-align: right">
                    <a href="{{route('sendBankSlip')}}" class="btn btn-success backButton">Voltar</a>  
                </div>
              </div>
            
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover dataTable dtr-inline">
                <thead class="table table-dark">
                    <tr role="row">
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Contrato</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Parcela</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cliente (Pagamento)</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Valor</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Nosso Numero</th>
                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Data Gerado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bankSlipPending as $bankSlip)
                        <tr>
                            <td> <input type="checkbox" checked class="checkBankSlip" name="checkBankSlip" value="{{$bankSlip['bankSlipInfo']->id}}"></td>
                            <td><a style="color: blue;"  href="{{route('seeSale',['idSale'=>$bankSlip['sale']->id])}}">{{$bankSlip['sale']->contract_number}}</a></td>
                            <td>{{$bankSlip['parcel']->num."/".$bankSlip['sale']->parcels}}</td>
                            <td>{{$bankSlip['client']->name}}</td>
                            <td>{{$bankSlip['parcel']->updated_value}}</td>
                            <td>{{$bankSlip['bankSlipInfo']->our_number}}</td>
                            <td>{{date('d/m/Y',strtotime($bankSlip['bankSlipInfo']->date))}} {{$bankSlip['bankSlipInfo']->time}}</td>
                        </tr>
                    @endforeach
                </tbody>    
            </table>
        </div>
        <div class="card-footer">
            <form action="{{route('generateSendBankSlip')}}" id="generateSendBankSlipForm" method="post">
                @csrf
                <input type="hidden" name="bankSlipCheck[]" id="bankSlipCheck">
                <center><input type="submit" id="btnSendBankSlip" class="btn btn-success" value="Gerar Remessa"></center>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('js/pending_bankSlip.min.js')}}"></script>
    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                text: '{{$errors->first()}}',
                customClass: 'mySweetalert',
            })
        </script>
    @endif 

@endsection
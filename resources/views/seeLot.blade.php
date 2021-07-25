@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 buttons_area--left">
                    @if(Auth::user()->type==1)
                        <a href="{{route('editLot',['idInterprise'=>$lot->id])}}"  class="btnActions btnActions--middle btnActions--transparent" title="editar">
                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                        </a>
                        <a href="{{route('deleteLot',['idInterprise'=>$lot->id])}}" 
                            class="btnActions btnActions--middle btnActions--transparent btnDelete btnDelete"
                            msg="Tem certeza que deseja excluir esse lot?">
                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                        </a>
                    @endif
                    <div class="info__title">{{$lot->name}}</div>
                </div>
                
                <div class="col-6" style="text-align: right;">
                    <a href="{{route('allLot',['idInterprise'=>$lot->id_interprise])}}" class="btn btn-success backButton">Voltar</a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Bloco</div>
                        <div class="input__info">
                            {{$lot->block}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Area</div>
                        <div class="input__info">
                            {{$lot->area}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Numero do Lot</div>
                        <div class="input__info">
                            {{$lot->lot_number}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Numero Registro</div>
                        <div class="input__info">
                            {{$lot->registration_number}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group">
                        <div class="input-info__title">Registro Municipal</div>
                        <div class="input__info">
                            {{$lot->municipal_registration}}
                        </div>
                    </div><br>
                </div>

                <div class="col-6">
                    <div class="input-info__group input-info__group--big">
                        <div class="input-info__title">Confrontações</div>
                        <div class="input__info input__info--left">
                            Lote tal
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Valor Atual</div>
                        <div class="input__info">
                            {{"R$: ".$lot->present_value}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Valor Futuro</div>
                        <div class="input__info">
                            {{$lot->future_value}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Entrada</div>
                        <div class="input__info">
                            {{$lot->input}}
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
    </div>
@endsection
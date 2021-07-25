@extends('adminlte::page')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/juridical.min.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">                    
                    <div class="info__title">Contatos Juridicos Adicionados</div>
                </div>
                <div class="card-body">
                    @foreach ($juridical_contacts as $juridical)
                        <div class="juridicalItem">
                            <div class="juridicalItem__header">
                                <div class="row">
                                    <div class="col-4">
                                        <label>Contrato</label><br>
                                        <span>{{$juridical->contractNumber}}</span>
                                    </div>
                                
                                    <div class="col-5">
                                        <div class="row">
                                            <label>Status:</label>
                                            <span style="margin-left: 5px;">
                                                @if($juridical->status==0)
                                                    Pendente    
                                                @elseif($juridical->status==1)
                                                    Aguardando Decisão
                                                @elseif($juridical->status==2)
                                                     Resolvido       
                                                @endif
                                                
                                            </span>
                                        </div>
                                        <div class="row">
                                            <label>Data:</label>
                                            <span style="margin-left: 5px;">{{date('d/m/Y',strtotime($juridical->register_date))." ".$juridical->register_time}}</span>
                                        </div>
                                    </div>
                                    <div class="col-3 buttons_area justify-content-end">
                                        <a href="{{route('seeJuridicalContact',['idJuridical'=>$juridical->id])}}" class="btnActions" title="ver mais">
                                            ...
                                        </a>
                                
                                        <div class="btnActions btnActions--transparent btnSeeMoreJuridical">
                                            <img src="{{asset('storage/general_icons/triangle.png')}}" width="100%" height="100%">
                                        </div>
                                    
                                    </div>
                                </div>
                            </div><br>

                            <div class="juridicalItem__content">
                                <label>Situação</label>
                                <div class="juridicalItem__situation">
                                    {{$juridical->situation}}
                                </div>
                            </div>
                        </div>
                    @endforeach    
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <div class="card">
                <div class="card-header">                    
                    <div class="info__title">Processos Juridicos Autorizado</div>
                </div>
                <div class="card-body">
                    @if (count($juridical_contacts_process)==0)
                        <div class="alert"><center>Nenhuma ação judicial iniciada!</center></div>
                    @endif
                    @foreach ($juridical_contacts_process as $juridical)
                        <div class="juridicalItem">
                            <div class="juridicalItem__header">
                                <div class="row">
                                    <div class="col-4">
                                        <label>Contrato</label><br>
                                        <span>{{$juridical->contractNumber}}</span>
                                    </div>
                                
                                    <div class="col-5">
                                        <div class="row">
                                            <label>Status:</label>
                                            <span style="margin-left: 5px;">
                                                @if($juridical->status==3)
                                                    Ação judicial autorizada
                                                @elseif($juridical->status==4)
                                                    Em andamento
                                                @elseif($juridical->status==2)
                                                     Resolvido       
                                                @endif
                                            </span>
                                        </div>
                                        <div class="row">
                                            <label>Data:</label>
                                            <span style="margin-left: 5px;">{{date('d/m/Y',strtotime($juridical->register_date))." ".$juridical->register_time}}</span>
                                        </div>
                                    </div>
                                    <div class="col-3 buttons_area justify-content-end">
                                        <a href="{{route('seeJuridicalContact',['idJuridical'=>$juridical->id])}}" class="btnActions" title="ver mais">
                                            ...
                                        </a>
                                
                                        <div class="btnActions btnActions--transparent btnSeeMoreJuridical">
                                            <img src="{{asset('storage/general_icons/triangle.png')}}" width="100%" height="100%">
                                        </div>
                                    
                                    </div>
                                </div>
                            </div><br>

                            <div class="juridicalItem__content">
                                <label>Situação</label>
                                <div class="juridicalItem__situation">
                                    {{$juridical->situation}}
                                </div>
                            </div>

                            @if($juridical->resolution != "")
                                <div class="juridicalItem__content">
                                    <label>Resolução</label>
                                    <div class="juridicalItem__situation">
                                        {{$juridical->resolution}}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach    
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('js/juridical.min.js')}}"></script>
@endsection
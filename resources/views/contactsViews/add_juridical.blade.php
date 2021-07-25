@extends('adminlte::page')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <h4>Adicionar Contato (Juridico)</h4>
        </div>
        <div class="col-6" style="text-align: right">
            <a href="{{$allContact==2?route('seeSale',['idSale'=>$sale->id]):route('allContact')}}"
                class="btn btn-success backButton">
                Voltar
            </a>    
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="info__title">Situação do Contrato</div>
                </div>
                <form action="{{route('addJuridicalContact')}}" class="optionFineForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_sale" value={{$idSale}}>

                    <div class="form-group">
                        <textarea class="form-control" autofocus name="situation"  cols="30" rows="10">{{old('situation')}}</textarea>
                    </div>
                </div>

            <div class="card">
                <div class="card-header">
                    <div class="info__title">
                        Usuario Júridico responsavel pelo contato:
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                        <thead class="table table-dark">
                        <tr role="row">
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1"></th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Email</th>
                            <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Tipo</th>
                        </thead>
                        <tbody>
                            @foreach ($usersJuridical as $user)
                                <tr role="row" class="odd">
                                    <td>
                                        <input type="radio" name="id_user" value="{{$user->id}}">
                                    </td>
                                    <td>
                                        <div class="mini-photo_user">
                                            <img src="{{asset('storage/users/'.$user->photo)}}" alt="" 
                                                width="100%" height="100%">
                                        </div>
                                    </td>
                                    <td tabindex="0" class="sorting_1">{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>
                                        @if ($user->type==1)
                                            Administrador
                                        @elseif($user->type==2)
                                            Vendedor
                                        @elseif($user->type==5)
                                            Juridico
                                        @endif
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="info__title">Adicione algum arquivo:</div>
                </div>
                <div class="card-body">
                    <div class="uploadArea">
                        <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                        <div class="uploadAreaDrop">
                            <div class="uploadAreaDrop__img">
                                <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                            </div>
                            <div class="uploadAreaDrop__descriptionFile"></div>
                        </div>
                        <input name="contactFile" type="file" class="uploadInput"/>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <center><input type="submit" class="btn btn-success w-25" id="btnInput" value="Salvar"></center>
            </div>
        </form>
        </div>
        
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="info__title">Informações Venda</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="input-info__group">
                                <div class="input-info__title">Numero Contrato</div>
                                <div class="input__info">
                                    {{$sale->contract_number}}
                                </div>
                            </div><br>

                            <div class="input-info__group">
                                <div class="input-info__title">Empreendimento</div>
                                <div class="input__info">
                                    {{$sale->interprise_name}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Lot</div>
                                <div class="input__info">
                                    {{$sale->lot_number}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Valor</div>
                                <div class="input__info">
                                    {{$sale->value}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Entrada</div>
                                <div class="input__info">
                                    {{$sale->input}}
                                </div>
                            </div><br>
        
                            
                            <div class="input-info__group">
                                <div class="input-info__title">Desconto</div>
                                <div class="input__info">
                                    {{$sale->descont}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Indice</div>
                                <div class="input__info">
                                    {{$sale->index}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Status</div>
                                <div class="input__info">
                                    @if ($sale->type==1)
                                        Proposta
                                    @elseif($sale->type==2)
                                        Ativo    
                                    @endif
                                </div>
                            </div><br>
                        </div>
                        <div class="col-6">
                            <div class="input-info__group">
                                <div class="input-info__title">Juros por ano</div>
                                <div class="input__info">
                                    {{$sale->annual_rate}}%
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Numero Parcelas</div>
                                <div class="input__info">
                                    {{$sale->parcels}}
                                </div>
                            </div><br>
                            
                            <div class="input-info__group">
                                <div class="input-info__title">Primeira parcela</div>
                                <div class="input__info">
                                    {{date('d/m/Y',strtotime($sale->first_parcel))}}
                                </div>
                            </div><br>
                            
                            <div class="input-info__group">
                                <div class="input-info__title">Parcelas Paga</div>
                                <div class="input__info">
                                    {{$parcels_pad}}
                                </div>
                            </div><br>
                            
        
                            <div class="input-info__group">
                                <div class="input-info__title">Dia de vencimento</div>
                                <div class="input__info">
                                    Dia {{$sale->expiration_day}}
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Tipo Contrato</div>
                                <div class="input__info">
                                    @if ($sale->type==1)
                                        Proposta
                                    @elseif($sale->type==2)
                                        Ativo  
                                    @elseif($sale->type==3)
                                        Cancelado  
                                    @endif
                                </div>
                            </div><br>
        
                            <div class="input-info__group">
                                <div class="input-info__title">Data Proposta</div>
                                <div class="input__info">
                                    {{date('d/m/Y',strtotime($sale->propose_date))}}
                                </div>
                            </div><br>

                            <div class="input-info__group">
                                <div class="input-info__title">Data Começo Contrato</div>
                                <div class="input__info">
                                    {{date('d/m/Y',strtotime($sale->begin_contract_date))}}
                                </div>
                            </div><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('js')
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

@endsection
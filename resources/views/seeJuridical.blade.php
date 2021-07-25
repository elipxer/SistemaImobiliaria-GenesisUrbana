@extends('adminlte::page')
@extends('layouts/searchSale')
@extends('layouts/modal')

@section('css')
    <link rel="stylesheet" href="{{asset('css/juridical.min.css')}}">
@endsection

@section('content')
    @if ($juridical->status==0)
        <div class="alert alert-warning fa-2x"><center>Pendente (Aguardando resposta do usuario júridico)</center></div>
    @elseif ($juridical->status==1)
        <div class="alert alert-warning fa-2x"><center> Aguardando decisão de ação judicial</center></div>
    @elseif ($juridical->status==2)
        <div class="alert alert-primary fa-2x"><center>Contato Juridico Resolvido</center></div>
    @elseif ($juridical->status==3)
        <div class="alert alert-primary fa-2x"><center>Aguardando numero e documento do processo (Ação Juridica Autorizada)</center></div>       
    @elseif ($juridical->status==4)
        <div class="alert alert-success fa-2x"><center>Processo em andamento. Numero: {{$juridical->process_number}}</center></div>        
    @endif

    @if ($juridical->status==4)
        <div class="card">
            <div class="card-header">
                <div class="info__title">
                    <center>Documento do Processo</center>
                </div>
            </div>
            <div class="card-body">
                <div class="downloadArea">
                    <div class="download__title">Clique pra fazer download</div>
                    <a href="{{asset('storage/'.$juridical->document_juridical)}}" class="downloadLink" 
                        download></a>   
                </div><br>
            </div>  
        </div>
    @endif

    @if($juridical->status==4)
        <form action="{{route('addJuridicalUpdate')}}" method="post"  enctype="multipart/form-data"
            id="updatedProcessForm" style="display: none">
            @csrf
            <input type="hidden" name="id_juridical" value="{{$juridical->id}}">
            <div class="form-group">
                <label>Assunto</label>
                <input class="form-control" type="text" name="subject" required maxlength="50">
            </div>
            <div class="form-group">
                <label>Atualização do processo</label>
                <textarea class="form-control" required name="update_decription" cols="30" rows="10"></textarea>
            </div>
            
            <div class="form-group">
                <div class="card" >
                    <div class="card-header">
                        <div class="info__title">
                            <center>Insira algum documento (opcional)</center>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="card-body">
                                <div class="uploadArea">
                                    <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                                    <div class="uploadAreaDrop">
                                        <div class="uploadAreaDrop__img">
                                            <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                        </div>
                                        <div class="uploadAreaDrop__descriptionFile"></div>
                                    </div>
                                    <input name="document" multiple="multiple" type="file" class="uploadInput"/>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
                @if(Auth::user()->type !=5)
                    <div class="card">
                        <div class="card-header">
                            <div class="info__title">
                                Ações para o contrato
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="radio" name="decision" value="1"> 
                                <span>Retornar para ativo</span><br>
                                <input type="radio" checked name="decision" value="2">
                                <span>Permanecer júridico</span>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="card-footer">
                    <center><input type="submit" class="btn btn-success btn-lg w-25" value="Salvar"></center>
                </div>
        </form>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 buttons_area--left">
                        @if($juridical->status != 2)
                            <div class="btnActions btnActions--middle" title="adicionar atualizações"
                            data-toggle="modal" data-target="#modalAcoes" data-toggle="tooltip" id="btnAddUpdate">
                                +
                            </div>
                        @endif
                        
                        <div class="info__title">Atualizações Do Processo</div>
                    </div>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                @foreach ($juridicalUpdates as $juridicalUpdate)
                    <div class="updateJuridicalItem">
                        <div class="row updateJuridicalItem__header">
                            <div class="col-8">
                                <div class="updateJuridicalItem__title updateJuridicalItem__title--md font-weight-bold">Assunto: {{$juridicalUpdate['updateJuridicalInfo']->subject}}</div>
                                <div class="updateJuridicalItem__title updateJuridicalItem__title--sm">Adicionado por:
                                    {{$juridicalUpdate['user']->name." (".$typesUser[$juridicalUpdate['user']->type].")"}}</div>
                                <div class="updateJuridicalItem__title updateJuridicalItem__title--sm">Data: {{date('d/m/Y',strtotime($juridicalUpdate['updateJuridicalInfo']->date))}}</div>
                                <div class="updateJuridicalItem__title updateJuridicalItem__title--sm">Hora: {{$juridicalUpdate['updateJuridicalInfo']->time}}</div>
                            </div>
                            <div class="col-4 buttons_area justify-content-end">
                               <div class="btnActions btnActions--transparent btnSeeMoreUpdateJuridical">
                                    <img src="{{asset('storage/general_icons/triangle.png')}}" width="100%" height="100%">
                                </div>
                            </div>
                        </div><br>
                        
                        <div class="updateJuridicalItem__content">
                            <div class="row">
                                <div class="col-8">
                                    <label>Descrição da atualização</label>
                                    <div class="juridicalItem__situation">
                                        {{$juridicalUpdate['updateJuridicalInfo']->update_decription}}
                                    </div>
                                </div>
                                <div class="col-4">
                                    @if ($juridicalUpdate['updateJuridicalInfo']->document != "")
                                    <center>
                                        <label>Download documento</label>
                                        <a href="{{asset('storage/'.$juridicalUpdate['updateJuridicalInfo']->document)}}" download class="downloadUpdateDocument">
                                            <img src="{{asset('storage/general_icons/file.png')}}"
                                                width="40%">
                                        </a>
                                    </center>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div><br>
                @endforeach
            </div>
        </div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 buttons_area--left">
                    <a href="{{route('seeSale',['idSale'=>$juridical->id_sale,'idJuridical'=>$juridical->id])}}" class="btnActions btnActions--middle" title="ver mais">
                        ...
                    </a>
                    
                    <div class="info__title">Informações Básica da Venda</div>
                </div>
            </div>
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
    
    <div class="card">
        <div class="card-header">
            <div class="info__title">Situação</div>
        </div>

        <div class="card-body">
            <div class="juridicalItem__situation">
                {{$juridical->situation}}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="info__title">Usuário Responsavel</div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Nome</div>
                        <div class="input__info">
                            {{$user->name}}
                        </div>
                    </div><br>
                </div>
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Email</div>
                        <div class="input__info">
                            {{$user->email}}
                        </div>
                    </div><br>
                </div>
            </div>
        </div>
    </div>

    @if($juridical->file_path != "")
    <div class="card">
        <div class="card-header">
            <div class="info__title">
                <center>Documento Inserido pelo responsável</center>
            </div>
        </div>
        <div class="card-body">
            <div class="downloadArea">
                <div class="download__title">Clique pra fazer download</div>
                <a href="{{asset('storage/'.$juridical->file_path)}}" class="downloadLink" 
                    download></a>   
            </div><br>
        </div>  
    </div>
    @endif

    @if($juridical->status==1 && Auth::user()->type==5)
        <div class="alert alert-danger"><center>Aguardando Decisão do Usuario Responsável</center></div>
    @endif

    @if($juridical->status!=0)
        <div class="card">
            <div class="card-header">
                <div class="info__title">Resolução feita pelo usuário júridico</div>
            </div>

            <div class="card-body">
                <div class="juridicalItem__situation">
                    {{$juridical->resolution}}
                </div>
            </div>
            @if ($juridical->status==1 && Auth::user()->type==1 || Auth::user()->type==3)
                <div class="card-footer">
                    <form action="{{route('finalResolutionJuridical')}}" method="post">
                        @csrf
                        <input type="hidden" name="id_juridical" value="{{$juridical->id}}">

                        <label>Entrar Com Ação Judicial?</label>
                        <div class="form-group d-flex  align-items-center">
                            <input type="radio" name="decision" value="1"> 
                            <span style="margin-left: 5px;">Sim</span>
                            <input style="margin-left: 15px;" type="radio" checked name="decision" value="2">
                            <span style="margin-left: 5px;">Não</span>
                        </div>
                        <center><input type="submit" class="btn btn-success btn-lg w-25" value="Salvar"></center>
                    </form>
                </div>
            @endif
        </div>
    @endif

    @if($juridical->status==0 && Auth::user()->type==5)
        <div class="card">
            <div class="info__title">Digite a solução do caso (Ou se não houve solução)</div>
            <div class="card-body">
                <form action="{{route('updateResolutionJuridical')}}" method="post">
                    @csrf
                    <input type="hidden" name="id_juridical" value="{{$juridical->id}}">
                    <div class="form-group">
                        <textarea required class="form-control" name="resolution" cols="30" rows="10"
                        value="{{old('resolution')}}" {{old('resolution')!=""?'autofocus':''}}></textarea>
                    </div>
                    
                    <div class="card-footer">
                        <center><input type="submit" class="btn btn-success btn-lg w-25" value="Salvar"></center>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($juridical->status==3 && Auth::user()->type==5)
        <div class="card">
            <div class="card-header">
                <center>
                <div class="info__title">Digite o numero do processo e insira o documento 
                    (Prazo 60 dias desde {{date('d/m/Y',strtotime($juridical->date_authorization_juridical))}})
                </div>
                </center>
            </div>
            <div class="card-body">
                <form action="{{route('startJudicialProcess')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_juridical" value="{{$juridical->id}}">
                        <center>
                            <div class="card w-50">
                                <div class="card-header">
                                    <div class="info__title">
                                        <center>Numero do Processo</center>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input class="form-control" required type="text" 
                                            name="process_number" 
                                            value="{{old('process_number')}}"
                                            {{old('process_number')!=""?'autofocus':''}}>
                                    </div>
                                </div>
                            </div>
                        </center>
                    
                        <div class="card" >
                            <div class="card-header">
                                <div class="info__title">
                                    <center>Insira o documento do processo</center>
                                </div>
                            </div>
                            <div class="card-body">
                                
                                    <input type="hidden" name="idSale" value="{{$sale->id}}">
                                    
                                    <div class="card-body">
                                        <div class="uploadArea">
                                            <div class="uploadArea__title">Clique ou arraste o arquivo</div>
                                            <div class="uploadAreaDrop">
                                                <div class="uploadAreaDrop__img">
                                                    <img src="{{asset('storage/general_icons/file.png')}}" width="70%" height="70%">
                                                </div>
                                                <div class="uploadAreaDrop__descriptionFile"></div>
                                            </div>
                                            <input required name="document_juridical" multiple="multiple" type="file" class="uploadInput"/>
                                        </div>
                                    </div>
                    
                                    <div class="card-footer">
                                        <center><input type="submit" class="btn btn-success btn-lg w-25" 
                                            id="btnFileContract" value="Salvar"></center>
                                    </div>
                            </div> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    
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

        <script src="{{asset('js/juridical.min.js')}}"></script>

  @endsection
   
@endsection
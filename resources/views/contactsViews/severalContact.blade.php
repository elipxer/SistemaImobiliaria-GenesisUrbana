@extends('adminlte::page')
@extends('layouts/searchSale')

@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <h4>Adicionar Contato (Diversos)</h4>
        </div>
        <div class="col-6" style="text-align: right">
            <a href="{{$allContact==2?route('seeSale',['idSale'=>$sale->id]):route('allContact')}}"
                class="btn btn-success backButton">
                Voltar
           </a>    
        </div>
    </div>
    
    <form action="{{route('addSeveralContact')}}" class="optionFineForm" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_sale" value={{$idSale}}>
        <div class="row">
            <div class="col-8">
                <div class="card">
                <div class="card-header">
                    <div class="info__title">Informações Contato</div>
                </div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="contact_client_name">Pessoa*</label>
                                    <input class="form-control" type="text" name="contact_client_name" 
                                        value="{{old('contact_client_name')}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="where">Via*</label>
                                    <input class="form-control" type="text" name="where" value="{{old('where')}}">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="deadline">Prazo*</label>
                                    <input class="form-control" type="date" name="deadline" min="{{date('Y-m-d')}}" value="{{old('deadline')}}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject_matter">Assunto*</label>
                            <textarea class="form-control" type="date" name="subject_matter" rows="5">{{old('subject_matter')}}</textarea>
                        </div>

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

                <div class="card">
                    <div class="card-header">
                        <div class="info__title">
                            Usuario responsavel pelo contato:
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
                                @foreach ($users as $user)
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
                                                Gestão
                                            @elseif($user->type==3)
                                                Operação
                                            @elseif($user->type==4)
                                                Comercialização
                                            @elseif($user->type==5)
                                                Júridico
                                            @elseif($user->type==6)
                                                Cliente
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
                        <div class="info__title">Administrativo:</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="fine_contact">Taxa</label>
                                    <input class="form-control money" type="text" name="fine_contact" 
                                        id="fine_contact" value="{{old('fine_contact')}}">
                                </div>
                            </div>
                            
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="expiration_fine_contact">Vencimento</label>
                                    <input class="form-control" type="date" name="expiration_fine_contact" 
                                        id="expiration_fine_contact" value="{{old('expiration_fine_contact')}}">
                                </div>
                            </div>
                            
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="prefix_parcel_contact">Prefixo Parcela</label>
                                    <input class="form-control" type="int" name="prefix_parcel_contact" 
                                        id="prefix_parcel_contact" value="{{old('prefix_parcel_contact')}}">
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="form-group">
                                    <label for="number_parcel_contact">Num Parcelas</label>
                                    <input class="form-control" type="int" name="number_parcel_contact" 
                                        id="number_parcel_contact" value="{{old('number_parcel_contact')}}">
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="card">
                    <div class="card-footer">
                        <center><input type="submit" class="btn btn-success inputOptionFineBtn w-25" value="Salvar"></center>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
        <script src="{{asset('js/optionContactFine.min.js')}}"></script>

    @endsection
    
@endsection
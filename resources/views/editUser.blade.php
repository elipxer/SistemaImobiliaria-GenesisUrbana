@extends('adminlte::page')
@extends('layouts/searchSale')


@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
<a href="{{route('allUsers')}}" class="btn btn-success btnBackAbsolute">Voltar</a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="photo-user">
                        <img src="{{asset('storage/users/'.$user->photo)}}" width="100%" height="100%" alt="user-default">
                    </div>
                    
                    <form method="POST" action="{{route('editAction')}}">
                        @csrf
                        
                        <input type="hidden" name="idUser" value="{{$user->id}}">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nome</label>

                            <div class="col-md-6">
                                <div class="form-control">
                                    {{$user->name}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <div class="form-control">
                                    {{$user->email}}
                                </div>                            
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Tipo</label>
                            <div class="col-md-6">
                                <select class="form-control" name="type">
                                    <option {{$user->type==1?'selected':''}} value="1">Administrador</option>
                                    <option {{$user->type==2?'selected':''}} value="2">Gestão</option>
                                    <option {{$user->type==3?'selected':''}} value="3">Operação</option>
                                    <option {{$user->type==4?'selected':''}} value="4">Comercialização</option>
                                    <option {{$user->type==5?'selected':''}} value="5">Júridico</option>
                                    <option {{$user->type==6?'selected':''}} value="6">Cliente</option>

                                </select>
                            </div>
                        </div><br>

                        @if($clientInfo != "")
                            <div class="card">
                                <div class="card-header">
                                    <center><h4>Cliente relacionado ao usuario</h4></center>
                                </div>
                                <div class="card-body">
                                    <center><div class="input-info__group">
                                        <div class="input-info__title">Nome</div>
                                        <div class="input__info">
                                            {{$clientInfo->name!=""?$clientInfo->name:$clientInfo->company_name}}
                                        </div>
                                    </div></center><br>

                                    <center><div class="input-info__group">
                                        <div class="input-info__title">Cpf/Cnpj</div>
                                        <div class="input__info">
                                            {{$clientInfo->cpf!=""?$clientInfo->cpf:$clientInfo->cnpj}}
                                        </div>
                                    </div></center><br>

                                    <center><div class="input-info__group">
                                        <div class="input-info__title">Email</div>
                                        <div class="input__info">
                                            {{$clientInfo->email}}
                                        </div>
                                    </div></center><br>
                                </div>
                            </div>
                        @endif
                      
                        <div class="col-md-6 offset-md-3">
                            <input type="submit" class="btn btn-block btn-outline-success btn-md" value="Salvar">
                        </div>
                        
                    </form>
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
</div>


@section('js')
    <script>
        $(()=>{
            $(".photo-user").click(function(e){
                e.preventDefault();
                $('#photo-file').trigger('click');
                
                $('#photo-file').change(function(e){
                    if($(e.target).val()){
                        var img = e.target.files[0];
                        var f = new FileReader(); 
                        f.onload = function(e){ 
                            $(".photo-user img").attr("src",e.target.result); // altera o src da imagem
                        }
                    f.readAsDataURL(img);
                    }
                });
            })
        })
    </script>
@endsection

@endsection

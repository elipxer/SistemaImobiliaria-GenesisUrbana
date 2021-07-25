@extends('adminlte::page')
@extends('layouts/searchSale')
@section('css')
    <link rel="stylesheet" href="{{asset('css/user.min.css')}}">
@endsection

@section('content')
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4>Editar Empreendimento</h4>
                    </div>
                    <div class="col-6" style="text-align: right">
                      <a href="{{route('allInterprises')}}" class="btn btn-success backButton">Voltar</a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informações Empreendimento</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="formInterprise">
                                @csrf
                                <input type="hidden" name="id" value="{{$interprise->id}}">
                                <input type="hidden" name="id_companies" value="{{$interprise->company_ids}}" id="id_companies">
                                <input type="hidden" name="id_companies_porc" value="{{$interprise->company_perc}}" 
                                    id="id_companies_porc">
                                
                                <div class="form-group">
                                    <label for="name" >Nome</label>
                                    <input type="text" class="form-control w-75" name="name" 
                                        value="{{$interprise->name}}" autofocus>
                                </div>

                                <div class="form-group">
                                    <label for="city" >Cidade</label>
                                    <input type="text" name="city" class="form-control w-75" value="{{$interprise->city}}">
                                </div>

                                <div class="form-group">
                                    <label for="state">Estado</label>
                                    <select name="state" class="form-control w-25">
                                        @foreach ($states as $state)
                                            <option {{$interprise->state==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                                        @endforeach
                                    </select>
                                
                                </div>

                                <div class="form-group">
                                    <label for="observation">Observação</label>
                                    <textarea name="observation" class="form-control w-75" rows="5">{{$interprise->observation}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div class="info__title">
                                    Usuarios permitidos:
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
                                                    <input type="checkbox" {{in_array($user->id,$id_user_permission)?'checked':''}} name="id_user_permission[]" value="{{$user->id}}">
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
                    </div>
                    </form>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Empresa</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-hover dataTable dtr-inline" id="companiesAreaTable">
                                    <thead class="table table-dark">
                                    <tr role="row">
                                        <th></th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Cnpj</th>
                                        <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Representante Cpf</th>
                                    <tbody>
                                        <tr>
                                            <form class="formFiltro">
                                                <td></td>
                                                
                                                <td>
                                                    <input class="form-control" type="text" name="company_name" 
                                                        placeholder="Nome Empresa" value="{{$company_name}}">
                                                </td>
                                                
                                                <td>
                                                    <input class="form-control cnpj-not-autoClear" type="text" name="cnpj" 
                                                        placeholder="Cnpj" value="{{$cnpj}}">
                                                </td>
                                                
                                                <td>
                                                    <input class="form-control cpf-not-autoClea" type="text" name="representative_cpf" 
                                                        placeholder="Cpf Representante" value="{{$representative_cpf}}">
                                                </td>
                
                                               <input type="submit" style="display: none;">
                                            </form>
                                        </tr>
                                        @foreach ($companies as $company)
                                            <tr>
                                                <td>
                                                    <input {{in_array($company->id,$companies_interprise_ids)?'checked':''}} type="checkbox" name="companyCheck" value="{{$company->id}}">
                                                </td>
                                                <td>{{$company->company_name}}</td>
                                                <td>{{$company->cnpj}}</td>
                                                <td>{{$company->representative_cpf}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Porcentagem Empresas</h4>
                            </div>
                            <div class="card-body" id="card_porc">
                                @foreach ($companies_interprise as $company)
                                <div class="row companyLinePorc">
                                    <div class="form-control nameCompany" style="width:500px; height:auto;">{{$company['company']->company_name}}</div>
                                    <input type="hidden" class="idCompany" value="{{$company['company']->id}}">
                                    <input type="number" step="any" class="form-control porcValue" value="{{$company['perc']}}" style="width: 70px">
                                    <label style="margin-left:5px;font-size: 25px; font-family:'Source Sans Pro';">%</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <center><input type="submit" class="btn btn-block btn-outline-success w-25" id="btnInput" value="Salvar"></center>
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
        <script src="{{asset('js/interprises.min.js')}}"></script>

    @endsection

@endsection





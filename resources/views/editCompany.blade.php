@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <form method="POST">
        @csrf
        <input type="hidden" name="idCompany" value="{{$company->id}}">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4>Editar Empresa</h4>
                    </div>
                    <div class="col-6" style="text-align: right">
                      <a href="{{route('allCompanies')}}" class="btn btn-success backButton">Voltar</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <center><h4>Informações da empresa</h4></center>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="cnpj">Cnpj</label>
                                    <input type="text" class="form-control cnpj w-50" name="cnpj" 
                                        id="cnpj" value="{{$company->cnpj}}">
                                </div>

                                <div class="form-group">
                                    <label for="company_name">Nome Empresa</label>
                                    <input type="text" class="form-control w-50" name="company_name" 
                                        id="company_name" value="{{$company->company_name}}">
                                </div>

                                <div class="form-group">
                                    <label for="cep">Cep</label>
                                    <input type="text" class="form-control cep w-25" name="cep" 
                                        id="cep" value="{{$company->cep}}">
                                </div>

                                <div class="form-group">
                                    <label for="company_name">Rua</label>
                                    <input type="text" class="form-control w-50" name="street" 
                                        id="street" value="{{$company->street}}">
                                </div>

                                <div class="form-group">
                                    <label for="number">Numero</label>
                                    <input type="text" class="form-control w-25" name="number" 
                                        id="number" value="{{$company->number}}">
                                </div>

                                <div class="form-group">
                                    <label for="complement">Complemento</label>
                                    <input type="text" class="form-control w-50" name="complement" 
                                        id="complement" value="{{$company->complement}}">
                                </div>

                                <div class="form-group">
                                    <label for="neighborhood">Bairro</label>
                                    <input type="text" class="form-control w-50" name="neighborhood" 
                                        id="neighborhood" value="{{$company->neighborhood}}">
                                </div>

                                <div class="form-group">
                                    <label for="city">Cidade</label>
                                    <input type="text" class="form-control w-50" name="city" 
                                        id="city" value="{{$company->city}}">
                                </div>

                                <div class="form-group">
                                    <label for="state">Estado</label>
                                    <select name="state" id="state" class="form-control w-25">
                                        <option value=""></option>
                                        @foreach ($states as $state)
                                            <option {{$company->state==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <center><h4>Informações representante da empresa</h4></center>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="representative_cpf">Cpf Representante</label>
                                    <input type="text" class="form-control cpf w-50" name="representative_cpf" 
                                        id="representative_cpf" value="{{$company->representative_cpf}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_rg">Rg Representante</label>
                                    <input type="text" class="form-control w-25" name="representative_rg" 
                                        id="representative_rg" value="{{$company->representative_rg}}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="representative_name">Nome Representante</label>
                                    <input type="text" class="form-control w-25" name="representative_name" 
                                        id="representative_name" value="{{$company->representative_name}}">
                                </div>
                                
                                <div class="form-group">
                                    <label for="representative_marital_status">Estado Cívil</label>
                                    <select name="representative_marital_status" class="form-control w-25">
                                        <option {{$company->representative_marital_status==1?'selected':''}} 
                                            value="1">Solteiro</option>
                                        <option {{$company->representative_marital_status==2?'selected':''}} 
                                            value="2">Casado</option>
                                        <option {{$company->representative_marital_status==3?'selected':''}} 
                                            value="3">Divorciado</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="representative_occupation">Profissão</label>
                                    <input type="text" class="form-control w-50"  name="representative_occupation"
                                        value="{{$company->representative_occupation}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_nationality">Nacionalidade</label>
                                    <select name="representative_nationality" class="form-control w-50">
                                        <option {{$company->representative_nationality==1?'selected':''}} value="1">Brasileiro</option>
                                        <option {{$company->representative_nationality==2?'selected':''}} value="2">Estrangeiro</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="representative_cep">Cep</label>
                                    <input type="text" class="form-control cep w-25" name="representative_cep" 
                                        id="representative_cep" value="{{$company->representative_cep}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_street">Rua</label>
                                    <input type="text" class="form-control w-50" name="representative_street" 
                                        id="representative_street" value="{{$company->representative_street}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_number">Numero</label>
                                    <input type="text" class="form-control w-25" name="representative_number" 
                                        id="representative_number" value="{{$company->representative_number}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_complement">Complemento</label>
                                    <input type="text" class="form-control w-50" name="representative_complement" 
                                        id="representative_complement" value="{{$company->representative_complement}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_neighborhood">Bairro</label>
                                    <input type="text" class="form-control w-50" name="representative_neighborhood" 
                                        id="representative_neighborhood" value="{{$company->representative_neighborhood}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_city">Cidade</label>
                                    <input type="text" class="form-control w-50" name="representative_city" 
                                        id="representative_city" value="{{$company->representative_city}}">
                                </div>

                                <div class="form-group">
                                    <label for="representative_state">Estado</label>
                                    <select name="representative_state" class="form-control w-25">
                                        <option value=""></option>
                                        @foreach ($states as $state)
                                            <option {{$company->representative_state==$state?'selected':''}}
                                                value="{{$state}}">{{$state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <center><input type="submit" class="btn btn-block btn-outline-success btn-md w-25" value="Salvar"></center>
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
        <script>
            const BASE_URL="{{url('/')}}";
            const ADDRESS_URL='{{route('getAddressByCep')}}';
            const CNPJ_URL='{{route('getAllInfoByCnpj')}}';
            
        </script>
        <script src="{{asset('js/company.min.js')}}"></script>
    @endsection
@endsection

@extends('adminlte::page')
@extends('layouts/modal')
@extends('layouts/searchSale')


@section('header')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('css/clients.min.css')}}">
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-6">
          @if($cpf_representative==null)
            <h4>Adicionar Cliente</h4>
          @else
            <h4>Adicionar Representante</h4>
          @endif
          </div>
        
        <div class="col-6" style="text-align: right">
          <a href="{{$cpf_representative==null?route('allClients'):route('addClient')}}" class="btn btn-success backButton">Voltar</a>  
        </div>
    </div>
  </div>  
  <div class="card-body">
        <form role="form" method="POST" action="{{$cpf_representative==null?route('addClient'):''}}">
          @csrf
          <input type="hidden" value="{{$id_representative}}" name="id_representative" id="idRepresentative">
          <input type="hidden" value="" name="whatsAppNumber" id="whatsAppNumber">

          @if($cpf_representative==null)
            <div class="card-header">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="kind_person">Tipo</label>
                      <select name="kind_person" id="kind_person" class="form-control w-50">
                          <option {{old('kind_person')==1?'selected':''}} value="1">Pessoa Física</option>
                          <option {{old('kind_person')==2?'selected':''}} value="2">Pessoa Juridica</option>
                      </select>
                    </div>
                </div>
              </div>
          </div>
          @else 
            <input type="hidden" name="kind_person" value="1">
          @endif
          
            
            <div class="card" id="card-Cnpj">
              <div class="card-header">
                <h4>Informação Cliente</h4>
              </div>

              <div class="card-body">
                <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" class="form-control w-50 cnpj" name="cnpj" value="{{old('cnpj')}}">
                      </div>
                      
                      <div class="form-group">
                        <label for="company_name">Nome Empresa</label>
                        <input type="text" class="form-control w-50" name="company_name" 
                        id="company_name" value="{{old('company_name')}}">
                      </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control w-50" name="email" 
                        id="email"  value="{{old('email')}}">
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="form-group">
                        <label for="emitting_organ">Orgão Emissor</label>
                        <input type="text" class="form-control w-25" name="emitting_organ"  value="{{old('emmitting_organ')}}">
                    </div>
                      
                    <div class="form-group">
                        <label for="emitting_organ">Representante Legal</label>
                        <div class="row">
                          <input type="text" class="form-control w-50 cpf" name="representative_cpf" 
                            value="">
                          <a href="" class="btnActions btnActions--middle alertBtn" 
                            id="btnRegister" style="display: none">+</a>
                        </div>
                    </div>

                      <div class="form-group">
                        <label for="nationality">Nacionalidade</label>
                        <select name="nationality" class="form-control w-50">
                            <option {{old('nationality')==1?'selected':''}} value="1">Brasileiro</option>
                            <option {{old('nationality')==2?'selected':''}} value="2">Estrangeiro</option>
                        </select>
                      </div>
                    </div>
                </div>
              </div>
            </div>
            <div class="card" id="card-Cpf">
              <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h4><center>Informação Cliente</center></h4>
                            </div>
                              <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control w-75" name="name" value="{{old('name')}}">
                              </div>

                              <div class="form-group">
                                <label for="birth_date">Data Nascimento</label>
                                <input type="date" class="form-control w-50" name="birth_date" value="{{old('birth_date')}}">
                              </div>

                              <div class="form-group">
                                <label for="rg">RG</label>
                                <input type="text" class="form-control w-50" name="rg" value="{{old('rg')}}">
                              </div>

                              <div class="form-group">
                                <label for="emitting_organ">Orgão Emissor</label>
                                <input type="text" class="form-control w-25" name="emitting_organ" value="{{old('emitting_organ')}}">
                              </div>

                              <div class="form-group">
                                <label for="cpf">CPF</label>
                                <input type="text" class="form-control w-50 cpf" name="cpf" 
                                  value="{{$cpf_representative?$cpf_representative:''}}">
                              </div>

                              <div class="form-group">
                                <label for="marital_status">Estado Cívil</label>
                                <select name="marital_status" id="marital_status" class="form-control w-50">
                                    <option {{old('marital_status')==1?'selected':''}} value="1">Solteiro</option>
                                    <option {{old('marital_status')==2?'selected':''}} value="2">Casado</option>
                                    <option {{old('marital_status')==3?'selected':''}} value="3">Divorciado</option>
                                </select>
                              </div>

                              <div class="form-group">
                                <label for="nationality">Nacionalidade</label>
                                <select name="nationality" class="form-control w-50">
                                    <option {{old('nationality')==1?'selected':''}} value="1">Brasileiro</option>
                                    <option {{old('nationality')==2?'selected':''}} value="2">Estrangeiro</option>
                                </select>
                              </div>

                              <div class="form-group">
                                <label for="sex">Sexo</label>
                                <select name="sex" class="form-control w-50">
                                    <option {{old('sex')==1?'selected':''}} value="1">Masculino</option>
                                    <option {{old('sex')==2?'selected':''}} value="2">Feminino</option>
                                </select>
                              </div>

                              <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control w-50" name="email" value="{{old('email')}}">
                              </div>

                              <div class="form-group">
                                <label for="occupation">Profissão</label>
                                <input type="text" class="form-control w-50" name="occupation" value="{{old('occupation')}}">
                              </div>
                            
                      </div>
                    </div>

                    <div class="col-6">
                      <div class="card" id="card-spouse" style="display:{{old('marital_status')==2?'block':'none'}};">
                        <div class="card-header">
                            <h4><center>Informação Cônjuge</center></h4>
                        </div>
                        
                        <div class="form-group">
                          <label for="spouse_name">Nome Cônjuge</label>
                          <input type="text" class="form-control w-75" name="spouse_name" value="{{old('spouse_name')}}">
                        </div>

                        <div class="form-group">
                          <label for="spouse_birth_date">Data Nascimento Cônjuge</label>
                          <input type="date" class="form-control w-50" name="spouse_birth_date" value="{{old('spouse_birth_date')}}">
                        </div>

                      <div class="form-group">
                          <label for="spouse_emitting_organ">RG Cônjuge</label>
                          <input type="text" class="form-control w-50" name="spouse_rg" value="{{old('spouse_rg')}}">
                        </div>

                        <div class="form-group">
                          <label for="spouse_emitting_organ">Orgão Emissor Cônjuge</label>
                          <input type="text" class="form-control w-25" name="spouse_emitting_organ" 
                            value="{{old('spouse_emitting_organ')}}">
                        </div>

                        <div class="form-group">
                          <label for="spouse_cpf">CPF Cônjuge</label>
                          <input type="text" class="form-control w-50 cpf" name="spouse_cpf"  value="{{old('spouse_cpf')}}">
                        </div>

                        
                        <div class="form-group">
                          <label for="spouse_nationality">Nacionalidade Cônjuge</label>
                          <select name="spouse_nationality" class="form-control w-50">
                              <option {{old('spouse_nationality')==1?'selected':''}} value="1">Brasileiro</option>
                              <option {{old('spouse_nationality')==2?'selected':''}} value="2">Estrangeiro</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="spouse_sex">Sexo Cônjuge</label>
                          <select name="spouse_sex" class="form-control w-50">
                              <option {{old('spouse_sex')==1?'selected':''}} value="1">Masculino</option>
                              <option {{old('spouse_sex')==2?'selected':''}} value="2">Feminino</option>
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="spouse_email">Email Cônjuge</label>
                          <input type="text" class="form-control w-50" name="spouse_email" 
                            value="{{old('spouse_email')}}">
                        </div>

                        <div class="form-group">
                          <label for="spouse_occupation">Profissão Cônjuge</label>
                          <input type="text" class="form-control w-50" name="spouse_occupation" 
                            value="{{old('spouse_occupation')}}">
                        </div>
                      </div>
                  </div>
              </div>
          </div>

          <div class="card">
            <div class="card-header">
                <h4>Telefones Cliente</h4>
            </div>

            <div class="card-body">
              <div class="row">
              
              <div class="col-7">
              
                <div class="form-group">
                  <label for="phone">Telefones</label>
                  <div class="row">
                      <input type="text" class="form-control w-50 phone" id="phoneSelected">
                      <button class="btnActions" id="btnAddPhone">+</button>
                  </div>
                  
                  <div class="phoneArea" edit="false">
                      <div class="phoneArea__item" style="display: none;">
                          <input type="text" class="form-control w-75 phone" name="phones[]">
                          <div class="btnActions btnActions--transparent btnDeletePhone">
                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                          </div>

                          <div class="btnActions btnActions--transparent btnWhatsApp">
                              <img src="{{asset('storage/general_icons/whatsApp.png')}}" width="100%" height="100%">
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        

          <div class="card">
              <div class="row">
                <div class="col-6">
                  <div class="card"  id="address_client">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-6">  
                          <h4>Endereço Cliente</h4>
                        </div>
                      </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="cep">Cep</label>
                            <input type="text" class="form-control w-50 cep" name="cep" value="{{old('cep')}}">
                          </div>
      
                          <div class="form-group">
                            <label for="street">Rua</label>
                            <input type="text" class="form-control w-50" name="street" value="{{old('street')}}">
                          </div>
      
                          <div class="form-group">
                            <label for="number">Numero</label>
                            <input type="number" class="form-control w-25" name="number" value="{{old('number')}}">
                          </div>
                          
                          <div class="form-group">
                            <label for="neighborhood">Bairro</label>
                            <input type="text" class="form-control w-50" name="neighborhood" value="{{old('neighborhood')}}">
                          </div>
      
                        <div class="form-group">
                            <label for="city">Cidade</label>
                            <input type="text" class="form-control w-50" name="city" value="{{old('city')}}">
                          </div>
      
                          <div class="form-group">
                            <label for="state">Estado</label>
                            <select name="state" class="form-control w-25">
                              @foreach ($states as $state)
                                  <option {{old('state')==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                              @endforeach
                          </select>
                          </div>
      
                          <div class="form-group">
                            <label for="complement">Complemento</label>
                            <input type="text" class="form-control w-50" name="complement" value="{{old('complement')}}">
                          </div>
                      </div>
                    </div>
                </div>
                <div class="col-6">
                  <div class="card" id="address_payment_collection">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-6">  
                          <h4>Endereço de cobrança</h4>
                        </div>
                        <div class="col-6" style="text-align: right;">
                          <button class="btn btn-dark" id="btnCopyAddress">Copiar Endereço Cliente</button>
                        </div>
                      </div>
                    </div>
                  <div class="form-group">
                    <label for="cep_payment_collection">Cep Cobrança</label>
                    <input type="text" class="form-control w-50 cep" name="cep_payment_collection" 
                      value="{{old('cep_payment_collection')}}">
                  </div>

                  <div class="form-group">
                    <label for="street_payment_collection">Rua Cobrança</label>
                    <input type="text" class="form-control w-50" name="street_payment_collection"
                      value="{{old('street_payment_collection')}}">
                  </div>

                  <div class="form-group">
                    <label for="number_payment_collection">Numero Cobrança</label>
                    <input type="number" class="form-control w-25" name="number_payment_collection"
                      value="{{old('number_payment_collection')}}">
                  </div>

                  <div class="form-group">
                    <label for="neighborhood_payment_collection">Bairro Cobrança</label>
                    <input type="text" class="form-control w-50" name="neighborhood_payment_collection"
                     value="{{old('neighborhood_payment_collection')}}">
                  </div>

                  <div class="form-group">
                    <label for="city_payment_collection">Cidade Cobrança</label>
                    <input type="text" class="form-control w-50" name="city_payment_collection"
                      value="{{old('city_payment_collection')}}">
                  </div>

                  <div class="form-group">
                    <label for="complement_payment_collection">Complemento Cobrança</label>
                    <input type="text" class="form-control w-50" name="complement_payment_collection"
                      value="{{old('complement_payment_collection')}}">
                  </div>

                  <div class="form-group">
                    <label for="state_payment_collection">Estado</label>
                    <select name="state_payment_collection" id="state_payment_collection" class="form-control w-25">
                      @foreach ($states as $state)
                          <option {{old('state_payment_collection')==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
          
          <div class="card-footer">
              <center><input type="submit" class="btn btn-block btn-outline-success btn-md w-25" value="Cadastrar"></center>
          </div>
          
        </form>
    </div>
    @section('js')
      
      <script>
          var cpf_representative_register=false; 
          var edit=false; 
          const BASE_URL="{{url('/')}}";
          const ADDRESS_URL='{{route('getAddressByCep')}}';
          const CNPJ_URL='{{route('getAllInfoByCnpj')}}';
          const CPF_URL='{{route('verifyClientExist')}}';
          const ADDRESS="";    
      </script>
      
      @if ($cpf_representative_register!=null)
        <script>
            cpf_representative_register=true;     
        </script>
      @endif
    
      @if($errors->any())
          <script>
              Swal.fire({
                  icon: 'error',
                  text: '{{$errors->first()}}',
                  customClass: 'mySweetalert',
              })
          </script>
      @endif 
      
      <script src="{{asset('js/clients.min.js')}}"></script>
  @endsection
@endsection


    

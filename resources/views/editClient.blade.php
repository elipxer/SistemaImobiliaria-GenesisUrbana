@extends('adminlte::page')
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
          <h4>Editar Cliente</h4>
        </div>
        <div class="col-6" style="text-align: right">
          <a href="{{route('allClients')}}" class="btn btn-success backButton">Voltar</a>
        </div>
    </div>
  </div>  
  <div class="card-body">
        <form role="form" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$client->id}}">
            <input type="hidden" value="{{$id_representative}}" name="id_representative" id="idRepresentative">
            <input type="hidden" value="{{$client->whatsAppNumber}}" name="whatsAppNumber" id="whatsAppNumber">

            <div class="card-header">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="kind_person">Tipo</label>
                    <div class="form-control w-50">{{$client->kind_person==1?'Pessoa Física':'Pessoa Juridica'}}</div>
                  </div>
              </div>
            </div>
          </div>
          
          @if ($client->kind_person==2)
          <div class="card" id="card-Cnpj" style="display: flex">
            <div class="card-header">
              <h4>Informação Cliente</h4>
            </div>

            <div class="card-body">
              <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label for="cnpj">CNPJ</label>
                      <input type="text" class="form-control w-50 cnpj" name="cnpj" value="{{$client->cnpj}}">
                    </div>
                    
                    <div class="form-group">
                      <label for="company_name">Razão Social</label>
                      <input type="text" class="form-control w-50" name="company_name" value="{{$client->company_name}}">
                    </div>

                    <div class="form-group">
                      <label for="company_name">Nome Fantasia</label>
                      <input type="text" class="form-control w-50" name="fantasy_name" value="{{$client->fantasy_name}}">
                    </div>

                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="text" class="form-control w-50" name="email" value="{{$client->email}}">
                    </div>
                  </div>

                  <div class="col-6">
                    <div class="form-group">
                      <label for="emitting_organ">Orgão Emissor</label>
                      <input type="text" class="form-control w-25" name="emitting_organ" value="{{$client->emitting_organ}}">
                    </div>

                    <div class="form-group">
                      <label for="nationality">Nacionalidade</label>
                      <select name="nationality" class="form-control w-50">
                          <option {{$client->nationality==1?'selected':''}} value="1">Brasileiro</option>
                          <option {{$client->nationality==2?'selected':''}} value="2">Estrangeiro</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="emitting_organ">Representante Legal</label>
                      <div class="row">
                        <input type="text" class="form-control w-50 cpf" name="representative_cpf" 
                          value="{{$cpf_representative_register!=''?$cpf_representative_register:''}}">
                        <a href="" class="btnActions btnActions--middle alertBtn" 
                          id="btnRegister" style="display: none">+</a>
                      </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          @elseif($client->kind_person==1)
          
          <div class="card" id="card-Cpf" style="display: flex">
            <div class="row">
                  <div class="col-6">
                      <div class="card">
                          <div class="card-header">
                              <h4><center>Informação Cliente</center></h4>
                          </div>
                            <div class="form-group">
                              <label for="name">Nome</label>
                              <input type="text" class="form-control w-75" name="name" value="{{$client->name}}">
                            </div>

                            <div class="form-group">
                              <label for="birth_date">Data Nascimento</label>
                              <input type="date" class="form-control w-50" name="birth_date" value="{{$client->birth_date}}">
                            </div>

                            <div class="form-group">
                              <label for="rg">RG</label>
                              <input type="text" class="form-control w-50" name="rg" value="{{$client->rg}}">
                            </div>

                            <div class="form-group">
                              <label for="emitting_organ">Orgão Emissor</label>
                              <input type="text" class="form-control w-25" name="emitting_organ" value="{{$client->emitting_organ}}">
                            </div>

                            <div class="form-group">
                              <label for="cpf">CPF</label>
                              <input type="text" class="form-control w-50 cpf" name="cpf" value="{{$client->cpf}}">
                            </div>

                            <div class="form-group">
                              <label for="marital_status">Estado Cívil</label>
                              <select name="marital_status" class="form-control w-50" id="marital_status">
                                  <option value="1" {{$client->marital_status==1?'selected':''}} >Solteiro</option>
                                  <option value="2" {{$client->marital_status==2?'selected':''}} >Casado</option>
                                  <option value="3" {{$client->marital_status==3?'selected':''}} >Divorciado</option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label for="nationality">Nacionalidade</label>
                              <select name="nationality" class="form-control w-50">
                                  <option value="1" {{$client->nationality==1?'selected':''}}>Brasileiro</option>
                                  <option value="2" {{$client->nationality==2?'selected':''}}>Estrangeiro</option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label for="sex">Sexo</label>
                              <select name="sex" class="form-control w-50">
                                  <option value="1" {{$client->sex==1?'selected':''}}>Masculino</option>
                                  <option value="2" {{$client->sex==2?'selected':''}}>Feminino</option>
                              </select>
                            </div>

                            <div class="form-group">
                              <label for="email">Email</label>
                              <input type="text" class="form-control w-50" name="email" value="{{$client->email}}">
                            </div>

                            <div class="form-group">
                              <label for="occupation">Profissão</label>
                              <input type="text" class="form-control w-50" name="occupation" value="{{$client->occupation}}">
                            </div>
                        </div>
                  </div>

                  <div class="col-6">
                    <div class="card" id="card-spouse" style="display:{{$client->marital_status==2?'block':'none'}};">
                      <div class="card-header">
                          <h4><center>Informação Cônjuge</center></h4>
                      </div>
                      
                      <div class="form-group">
                        <label for="spouse_name">Nome Cônjuge</label>
                        <input type="text" class="form-control w-75" name="spouse_name" value="{{$client->spouse_name}}">
                      </div>

                      <div class="form-group">
                        <label for="spouse_birth_date">Data Nascimento Cônjuge</label>
                        <input type="date" class="form-control w-50" name="spouse_birth_date" value="{{$client->spouse_birth_date}}">
                      </div>

                    <div class="form-group">
                        <label for="spouse_emitting_organ">RG Cônjuge</label>
                        <input type="text" class="form-control w-50" name="spouse_rg" value="{{$client->spouse_rg}}">
                      </div>

                      <div class="form-group">
                        <label for="spouse_emitting_organ">Orgão Emissor Cônjuge</label>
                        <input type="text" class="form-control w-25" name="spouse_emitting_organ" value="{{$client->spouse_emitting_organ}}">
                      </div>

                      <div class="form-group">
                        <label for="spouse_cpf">CPF Cônjuge</label>
                        <input type="text" class="form-control w-50 cpf" name="spouse_cpf" value="{{$client->spouse_cpf}}">
                      </div>

                      <div class="form-group">
                        <label for="spouse_nationality">Nacionalidade Cônjuge</label>
                        <select name="spouse_nationality" class="form-control w-50">
                            <option {{$client->spouse_nationality==1?'selected':''}} value="1">Brasileiro</option>
                            <option {{$client->spouse_nationality==2?'selected':''}} value="2">Estrangeiro</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="spouse_sex">Sexo Cônjuge</label>
                        <select name="spouse_sex" class="form-control w-50">
                            <option {{$client->spouse_sex==1?'selected':''}} value="1">Masculino</option>
                            <option {{$client->spouse_sex==2?'selected':''}} value="2">Feminino</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label for="spouse_email">Email Cônjuge</label>
                        <input type="text" class="form-control w-50" name="spouse_email" value="{{$client->spouse_email}}">
                      </div>

                      <div class="form-group">
                        <label for="spouse_occupation">Profissão Cônjuge</label>
                        <input type="text" class="form-control w-50" name="spouse_occupation" value="{{$client->spouse_occupation}}">
                      </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
          
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
                </div>
                <div class="phoneArea" edit="true">
                  <?php $phones=explode(',',$client->phones);?>
                  @foreach ($phones as $phone)
                    <div class="phoneArea__item" {{$phone==$client->whatsAppNumber?'whatsApp="true"':''}}>
                      <input type="text" class="form-control w-75 phone" name="phones[]" value="{{$phone}}">
                      <div class="btnActions btnActions--transparent btnDeletePhone">
                        <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                      </div>
                      
                      <div class="btnActions btnActions--transparent btnWhatsApp 
                        {{$phone==$client->whatsAppNumber?'whatsApp':'whatsApp__disabled'}}">
                        <img src="{{asset('storage/general_icons/whatsApp.png')}}" width="100%" height="100%">
                      </div>
                  </div>
                  @endforeach
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
                            <input type="text" class="form-control w-50 cep" name="cep" value="{{$client->cep}}">
                          </div>
      
                          <div class="form-group">
                            <label for="street">Rua</label>
                            <input type="text" class="form-control w-50" name="street" value="{{$client->street}}">
                          </div>
      
                          <div class="form-group">
                            <label for="number">Numero</label>
                            <input type="number" class="form-control w-25" name="number" value="{{$client->number}}">
                          </div>
                          
                          <div class="form-group">
                            <label for="neighborhood">Bairro</label>
                            <input type="text" class="form-control w-50" name="neighborhood" value="{{$client->neighborhood}}">
                          </div>
      
                        <div class="form-group">
                            <label for="city">Cidade</label>
                            <input type="text" class="form-control w-50" name="city" value="{{$client->city}}">
                          </div>
      
                          <div class="form-group">
                            <label for="state">Estado</label>
                            <select name="state" class="form-control w-25">
                              @foreach ($states as $state)
                                  <option {{$client->state==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                              @endforeach
                          </select>
                          </div>
      
                          <div class="form-group">
                            <label for="complement">Complemento</label>
                            <input type="text" class="form-control w-50" name="complement" value="{{$client->complement}}">
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
                    <input type="text" class="form-control w-50 cep" name="cep_payment_collection" value="{{$client->cep_payment_collection}}">
                  </div>

                  <div class="form-group">
                    <label for="street_payment_collection">Rua Cobrança</label>
                    <input type="text" class="form-control w-50" name="street_payment_collection" value="{{$client->street_payment_collection}}">
                  </div>

                  <div class="form-group">
                    <label for="number_payment_collection">Numero Cobrança</label>
                    <input type="number" class="form-control w-25" name="number_payment_collection" value="{{$client->number_payment_collection}}">
                  </div>

                  <div class="form-group">
                    <label for="neighborhood_payment_collection">Bairro Cobrança</label>
                    <input type="text" class="form-control w-50" name="neighborhood_payment_collection" value="{{$client->neighborhood_payment_collection}}">
                  </div>

                  <div class="form-group">
                    <label for="city_payment_collection">Cidade Cobrança</label>
                    <input type="text" class="form-control w-50" name="city_payment_collection" value="{{$client->city_payment_collection}}">
                  </div>

                  <div class="form-group">
                    <label for="complement_payment_collection">Complemento Cobrança</label>
                    <input type="text" class="form-control w-50" name="complement_payment_collection" value="{{$client->complement_payment_collection}}">
                  </div>

                  <div class="form-group">
                    <label for="state_payment_collection">Estado</label>
                    <select name="state_payment_collection" class="form-control w-25">
                      @foreach ($states as $state)
                          <option {{$client->state_payment_collection==$state?'selected':''}} value="{{$state}}">{{$state}}</option>
                      @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
            <center><input type="submit" class="btn btn-block btn-outline-success btn-md w-25" value="Salvar"></center>
        </div>
          
        </form>
    </div>
    @section('js')
      <script>
          var edit=true;
          var idClient="{{$client->id}}";
          var idClientRepresentative="{{$client->id_representative}}"; 
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


    

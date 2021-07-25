@extends('adminlte::page')
@extends('layouts/searchSale')


@section('content')

<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col-6">
          <h4>Adicionar Lote</h4>
        </div>
        <div class="col-6" style="text-align: right">
          <a href="{{route('allLot',['idInterprise'=>$idInterprise])}}" class="btn btn-success backButton">Voltar</a>
        </div>
    </div>
  </div>  
  <div class="card-body">
        <form role="form">
            <div class="card-body">
              <div class="row">
                    <div class="col-6">
                      <input type="hidden" name="idInterprise" value="{{$idInterprise}}">
                        
                          <div class="form-group">
                            <label for="name">Quadra*</label>
                            <input type="text" class="form-control w-25" name="block" value="{{old('block')}}">
                          </div>

                          <div class="form-group">
                            <label for="name">Area*</label>
                            <input type="number" step="any" class="form-control w-25" name="area" value="{{old('area')}}">
                          </div>
                          <div class="form-group">
                            <label for="name">Numero Lot*</label>
                            <input type="number" class="form-control w-25" name="lot_number" value="{{old('lot_number')}}">
                          </div>

                          <div class="form-group">
                            <label for="name">Confrontações</label>
                            <textarea name="confrontations" class="form-control" cols="10" rows="5">{{old('confrontations')}}</textarea>
                          </div>

                          
                    </div>

                    <div class="col-1"></div>
                    
                    <div class="col-5">
                        <div class="form-group">
                          <label for="name">Numero Registro</label>
                          <input type="number" step="any" class="form-control" name="registration_number" value="{{old('registration_number')}}" >
                        </div>

                        <div class="form-group">
                          <label for="name">Registro Municipal</label>
                          <input type="number" step="any" class="form-control" name="municipal_registration" value="{{old('municipal_registration')}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Valor Atual</label>
                          <input type="text" step="any" class="form-control w-50 money" name="present_value" value="{{old('present_value')}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Valor Futuro</label>
                          <input type="text" step="any" class="form-control w-50 money" name="future_value" value="{{old('future_value')}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Valor Entrada</label>
                          <input type="text" step="any" class="form-control w-50 money" name="input" value="{{old('input')==''?'0,00':old('input')}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Desconto</label>
                          <input type="text" step="any" class="form-control w-50 money" name="descont" value="{{old('descont')==''?'0,00':old('descont')}}">
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


    

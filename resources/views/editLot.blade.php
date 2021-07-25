@extends('adminlte::page')
@extends('layouts/searchSale')


@section('content')

<div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-6"><h4>Editar Lote</h4></div>
        <div class="col-6" style="text-align: right;"><a href="{{route('allLot',['idInterprise'=>$lot->id_interprise])}}" class="btn btn-success backButton">Voltar</a></div>
      </div>
    </div>
    <div class="card-body">
        <form role="form">
            <div class="card-body">
              <div class="row">
                    <div class="col-6">
                      <input type="hidden" name="idLot" value="{{$lot->id}}">
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control w-75" name="name" value="{{$lot->name}}" required>
                          </div>

                          <div class="form-group">
                            <label for="name">Quadra</label>
                            <input type="text" class="form-control w-25" name="block" value="{{$lot->block}}">
                          </div>

                          <div class="form-group">
                            <label for="name">Area</label>
                            <input type="number" step="any" class="form-control w-25" name="area" value="{{$lot->area}}">
                          </div>
                          <div class="form-group">
                            <label for="name">Numero Lot</label>
                            <input type="number" class="form-control w-25" name="lot_number" value="{{$lot->lot_number}}">
                          </div>

                          <div class="form-group">
                            <label for="name">Confrontações</label>
                            <textarea name="confrontations" class="form-control" cols="10" rows="5">{{$lot->confrontations}}</textarea>
                          </div>

                          <div class="form-group">
                            <label for="name">Visivel</label>
                            <select name="visible" class="form-control w-25">
                                <option value="1"{{$lot->visible?'checked':''}}>Sim</option>
                                <option value="2" {{$lot->visible==2?'checked':''}}>Não</option>
                            </select>
                          </div>
                    </div>

                    <div class="col-1"></div>
                    
                    <div class="col-5">
                        <div class="form-group">
                          <label for="name">Numero Registro</label>
                            <input type="number" step="any" class="form-control" name="registration_number" value="{{$lot->registration_number}}" >
                        </div>

                        <div class="form-group">
                          <label for="name">Registro Municipal</label>
                          <input type="number" step="any" class="form-control" name="municipal_registration" value="{{$lot->municipal_registration}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Valor Atual</label>
                          <input type="text" step="any" class="form-control w-50 money" name="present_value" value="{{$lot->present_value}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Valor Futuro</label>
                          <input type="text" step="any" class="form-control w-50 money" name="future_value" value="{{$lot->future_value}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Valor Entrada</label>
                          <input type="text" step="any" class="form-control w-50 money" name="input" value="{{$lot->input}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Termo de financiamento</label>
                          <input type="text" step="any" class="form-control w-50 money" name="financing_term" value="{{$lot->financing_term}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Desconto</label>
                          <input type="text" step="any" class="form-control w-50 money" name="descont" value="{{$lot->descont}}">
                        </div>

                        <div class="form-group">
                          <label for="name">Termo de avaliação</label>
                          <input type="text" step="any" class="form-control w-50 money" name="financing_rate" value="{{$lot->financing_rate}}">
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
          
          <div class="card-footer">
              <center><input type="submit" class="btn btn-block btn-outline-success btn-md w-25" value="Salvar"></center>
          </div>
          
        </form>
    </div>
@endsection


    

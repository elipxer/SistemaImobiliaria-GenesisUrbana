@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
<h4><center>Adicionar Contato</center></h4>
<a href="{{route('seeSale',['idSale'=>$idSale])}}" class="btn btn-primary btnBackAbsolute">Voltar</a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="idSale" value="{{$idSale}}">
                        <div class="form-group row">
                            <label for="where" class="col-md-4 col-form-label text-md-right">Via</label>
                            <div class="col-md-6">
                                <select name="where" class="form-control">
                                    <option value="Portaria">Portaria</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="subject_matter" class="col-md-4 col-form-label text-md-right">Assunto</label>
                            <div class="col-md-6">
                                <textarea name="subject_matter" class="form-control" cols="15" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="deadline" class="col-md-4 col-form-label text-md-right">Prazo</label>
                            <div class="col-md-6">
                                <input class="form-control" type="date" name="deadline">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category" class="col-md-4 col-form-label text-md-right">Categoria</label>
                            <div class="col-md-6">
                                <select name="category" class="form-control">
                                    <option value="Portaria">Diversos</option>
                                    <option value="Reemitir Parcelas">Reemitir Parcelas</option>
                                    <option value="Alterar Proprietario">Alterar Proprietario</option>
                                    <option value="Alterar Dia Vencimento">Alterar Dia Vencimento</option>
                                    <option value="Alterar Lote">Alterar Lote</option>
                                    <option value="Refinanciamento">Refinanciamento</option>
                                    <option value="Processo Extra Judicial">Processo Extra Judicial</option>
                                    <option value="Processo Judicial">Processo Judicial</option>
                                    <option value="Cancelar Venda">Cancelar Venda</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 offset-md-3">
                            <input type="submit" class="btn btn-block btn-outline-success btn-md" value="Cadastrar">
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
    
@endsection
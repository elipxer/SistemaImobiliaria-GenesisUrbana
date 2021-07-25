@extends('adminlte::page')
@extends('layouts/searchSale')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 buttons_area--left">
                    @if(Auth::user()->type==1)
                        <a href="{{route('editInterprise',['idInterprise'=>$interprise->id])}}"  class="btnActions btnActions--middle btnActions--transparent" title="editar">
                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                        </a>
                        <a href="{{route('deleteInterprise',['idInterprise'=>$interprise->id])}}" 
                            class="btnActions btnActions--middle btnActions--transparent btnDelete btnDelete"
                            msg="Tem certeza que deseja excluir esse empreendimento?">
                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                        </a>
                    @endif
                    <div class="info__title">Informações Empreendimento</div>
                </div>
                
                <div class="col-6" style="text-align: right">
                    <a href="{{route('allInterprises')}}" class="btn btn-success backButton">Voltar</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="input-info__group">
                        <div class="input-info__title">Nome Empreendimento</div>
                        <div class="input__info">
                            {{$interprise->name}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Cidade</div>
                        <div class="input__info">
                            {{$interprise->city}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Estado</div>
                        <div class="input__info">
                            {{$interprise->state}}
                        </div>
                    </div><br>
                    
                    <div class="input-info__group input-info__group--big">
                        <div class="input-info__title">Observação</div>
                        <div class="input__info input__info--left">
                            {{$interprise->observation}}
                        </div>
                    </div><br>

                    <div class="input-info__group">
                        <div class="input-info__title">Data</div>
                        <div class="input__info">
                            {{date('d/m/Y',strtotime($interprise->date))}}
                        </div>
                    </div><br>
                </div>

                <div class="col-6">
                    <div class="card-header">
                        <div class="info__title">
                            Empresas
                        </div>
                    </div>
                    @foreach ($companies as $key=>$company)
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-10 buttons_area--left">
                                    @if(Auth::user()->type==1)
                                        <a href="{{route('editCompany',['idCompany'=>$company->id])}}"  class="btnActions btnActions--middle btnActions--transparent" title="editar">
                                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                        </a>
                                        <a href="{{route('suspend',['idCompany'=>$company->id])}}" 
                                            class="btnActions btnActions--middle btnActions--transparent btnDelete btnDelete"
                                            msg="Tem certeza que deseja excluir esse cliente?">
                                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                                        </a>
                                    @endif
                                    <div class="info__title w-100">
                                        <div class="row">
                                            <div class="col-6">Nome: {{$company->company_name}}</div>
                                            <div class="col-6" style="text-align: right;">Cpf/Cpnj: {{$company->cnpj}}</div>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="col-2" style="display:flex; justify-content:flex-end;">
                                    <div class="btnActions btnActions--middle btnActions--transparent btnSeeMoreCompany">
                                        <img src="{{asset('storage/general_icons/triangle.png')}}" width="100%" height="100%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="display: none">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Nome Empresa</div>
                                        <div class="input__info h-auto">
                                            {{$company->company_name}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Cnpj</div>
                                        <div class="input__info h-auto">
                                            {{$company->cnpj}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Rua</div>
                                        <div class="input__info h-auto">
                                            {{$company->street}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Numero</div>
                                        <div class="input__info h-auto">
                                            {{$company->number}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Bairro</div>
                                        <div class="input__info h-auto">
                                            {{$company->neighborhood}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Cidade</div>
                                        <div class="input__info h-auto">
                                            {{$company->city}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Estado</div>
                                        <div class="input__info h-auto">
                                            {{$company->state}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Cep</div>
                                        <div class="input__info h-auto">
                                            {{$company->cep}}
                                        </div>
                                    </div><br>
                                </div>
                                <div class="col-6">
                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Nome Representante</div>
                                        <div class="input__info h-auto">
                                            {{$company->representative_name}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Estado Cívil</div>
                                        <div class="input__info h-auto">
                                            {{$maritalState[$company->representative_marital_status]}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Profissão</div>
                                        <div class="input__info h-auto">
                                            {{$company->representative_occupation}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Rg</div>
                                        <div class="input__info h-auto">
                                            {{$company->representative_rg}}
                                        </div>
                                    </div><br>
                                    
                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Cpf</div>
                                        <div class="input__info h-auto">
                                            {{$company->representative_cpf}}
                                        </div>
                                    </div><br>

                                    <div class="input-info__group w-100">
                                        <div class="input-info__title">Nacionalidade</div>
                                        <div class="input__info h-auto">
                                            {{$nationality[$company->representative_nationality]}}
                                        </div>
                                    </div><br>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header buttons_area--left">
            @if(Auth::user()->type==1)
                <a href="{{route('addLot',['idInterprise'=>$interprise->id])}}" class="btnActions btnActions--middle">+</a>
            @endif
            <div class="info__title info__title--without-margin-top">Todos os lots</div>
        </div> 

        <div class="card-body">
            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" role="grid" aria-describedby="example2_info">
                <thead class="table table-dark">
                <tr role="row">
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Nome</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1">Lote</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Quadra</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Area</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Confrontações</th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" ><center>Dísponivel</center></th>
                    <th class="sorting" tabindex="0"  rowspan="1" colspan="1" >Acoes</th>
                </thead>
                <tbody>
                    <form method="get" class="formFilter">
                        <tr>
                            <input type="hidden" name="id_interprise" value="{{$interprise->id}}">
                            <td>
                            </td>
                            <td>
                                <input class="form-control" type="number" name="lot_number" value="{{$lot_number}}" placeholder="Lot">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="block" value="{{$block}}" placeholder="Quadra">
                            </td>
                            <td>
                                <input class="form-control" type="text" name="area" value="{{$area}}" placeholder="Area">
                            </td>

                            <td>
                                <input class="form-control" type="text" name="confrontations" placeholder="Confrontações">
                            </td>

                            <td>
                                <select name="available" class="form-control selectFilter">
                                    <option {{$available==1?'selected':''}} value="1">Sim</option>
                                    <option {{$available==2?'selected':''}} value="2">Não</option>
                                    
                                </select>
                            </td>
                           
                            <input type="submit" style="display: none;">
                        </tr>
                    </form>
                
                    
                        @foreach ($allLot as $lot)
                            <tr role="row" class="odd">
                                <td tabindex="0" class="sorting_1">{{$lot->name}}</td>
                                <td>{{$lot->lot_number}}</td>
                                <td>{{$lot->block}}</td>
                                <td>{{$lot->area}}</td>
                                <td>{{$lot->confrontations}}</td>
                                
                                <td><center>{{$lot->available==1?'Sim':'Não'}}</center></td>
                                

                                <td class="buttons_area">
                                    <a href="{{route('seeLot',['idInterprise'=>$lot->id])}}"  class="btnActions" title="ver mais">...</a>
                                    @if(Auth::user()->type==1)
                                        <a href="{{route('editLot',['idInterprise'=>$lot->id])}}"  class="btnActions btnActions--transparent" title="editar">
                                            <img src="{{asset('storage/general_icons/pencil.png')}}" width="100%" height="100%">
                                        </a>
                                        <a href="{{route('deleteLot',['idInterprise'=>$lot->id])}}" 
                                            class="btnActions btnActions--transparent btnDelete btnDelete"
                                            msg="Tem certeza que deseja excluir esse lot?">
                                            <img src="{{asset('storage/general_icons/trash.png')}}" width="100%" height="100%">
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(()=>{
            $('.btnSeeMoreCompany').each(function(){ 
                $(this).on('click',function(){
                    $(this).closest('.card').find('.card-body').slideToggle();
                })
            })
        })
    </script>    
@endsection

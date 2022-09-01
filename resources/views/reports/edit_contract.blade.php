@extends('adminlte::page')

@section('content')
   

    <h2 class="info__title"><center>Edição de contrato</center></h2>
    <div id="content" style="display: none;">
        {!!$content!!}
    </div>
    <div id="editor"></div>
    <div class="d-flex justify-content-center m-5">
        <button class="btn btn-success w-25" id="btnSubmit">Salvar</button>
    </div>
@endsection

@section('js')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(()=>{
            var content=$('#content').html();
            var idSale="{{$sale->id}}"
            const CONTRACT_URL="{{route('contractSaleHtml')}}";
            const CONTRACT_REPORT_URL="{{route('contractSale',['id_sale'=>$sale->id,'edit'=>true])}}";

            tinymce.init({
            plugins : 'autoresize',
            selector: '#editor',
            init_instance_callback:function(editor){
                editor.setContent(content);  
            }
            });
        
            $('#btnSubmit').on('click',()=>{
                let content=tinyMCE.activeEditor.getContent();
                $.ajax({
                    url:CONTRACT_URL,
                    type:'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{'idSale':idSale, 'content':content},
                    dataType:'json',
                    success:function(json){
                        if(json){
                            window.location.href=CONTRACT_REPORT_URL;
                        }
                    },
                    error:function(){
                        alert("algo deu errado!");
                    }
                });
            })
        })
        
    
    </script>
@endsection
$(()=>{ 
    var lineClientTable=$('.clientLineTable');
    var clientChoise=[];

    $(".photo-user").on('click',function(e){
        e.preventDefault();
        $('#photo-file').trigger('click');
        
        $('#photo-file').change(function(e){
            if($(e.target).val()){
                var img = e.target.files[0];
                var f = new FileReader(); 
                f.onload = function(e){ 
                    $(".photo-user img").attr("src",e.target.result);
                }
            f.readAsDataURL(img);
            }
        });
    })

    $('#type_user').on("change",function(){
        if($(this).find('option:selected').val()==="6"){
            $('#search_client_input').prop('disabled',false);
        }else{
            $('#search_client_input').prop('disabled',true);
        }
    })

    $('#search_client_input').on('keyup',function(){
        let clientVal=$(this).val();
        if(clientVal != ""){
            $('#clientArea').fadeIn();
            searchClients(clientVal);
           
        }else{
            $('#clientArea').fadeOut();
        }
    })

    $('#btnInput').on("click",function(event){
        event.preventDefault();
        if($('#type_user').find('option:selected').val()==="6"){
            if($('#idClient').val() === ""){
                Swal.fire({
                    icon: 'error',
                    text: 'O usuario tipo cliente precisa de um cliente para representa-lo!',
                    customClass: 'mySweetalert',
                })
            }else{
                $('#formUser').trigger('submit');
            }
        }else{
            $('#formUser').trigger('submit');
        }
    })

    function searchClients(clientVal){
        $.ajax({
            url:CLIENT_URL,
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'JSON',
            data:{'clientVal':clientVal},
            success:function(json){
                fillTableClient(json.clients);
            }
        })
    }

    function fillTableClient(clientJson){
        $('#clientAreaTable').find('tbody').empty();
        
        for (let key in clientJson) {
            let lineTable=$(lineClientTable).clone(true);
            $(lineTable).css('display','table-row');
            $(lineTable).find('td').eq(0).find('input').val(clientJson[key].id);
            let name=clientJson[key].name;
            if(clientJson[key].kind_person==2){
                name=clientJson[key].company_name;
            }
            $(lineTable).find('td').eq(1).html(name);
            $(lineTable).find('td').eq(2).html(clientJson[key].cpf);
            $(lineTable).find('td').eq(3).html(clientJson[key].cnpj);
            $(lineTable).find('td').eq(4).html(clientJson[key].rg);
            $(lineTable).find('td').eq(5).html(clientJson[key].email);
            $('#clientAreaTable').find('tbody').append(lineTable);
        }

        $('#clientAreaTable').find('input[name=clientCheck]').each(function(){
            $(this).on('change',function(){
                let idClient=$(this).val();
                $('#idClient').val(idClient);
                let name=$(this).closest('tr').find('td').eq(1).html();
                let email=$(this).closest('tr').find('td').eq(5).html();

                $('#name').val(name);
                $('#email').val(email);
            })
        })
    }

})
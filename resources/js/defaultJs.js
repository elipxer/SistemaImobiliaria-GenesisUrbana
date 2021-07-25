$(()=>{
    var date = new Date();
    const NOW = date.getFullYear() + "-" + (date.getMonth()+1) + "-" + date.getDate();
    
    let excluirBtn=$('.btnDelete');
    $(excluirBtn).each(function(){
        $(this).on('click',function(event){
        event.preventDefault();
        Swal.fire({
            title: $(this).attr('msg'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText:'Cancelar',
            confirmButtonText: 'Sim',
            customClass: 'mySweetalert',

            }).then((result) => {
                if (result.value) {
                    window.location = $(this).attr('href');                
                }
            })
        })
    })

    $('.selectFilter2').each(function(){
        $(this).on('change',function(){
            $(this).closest('.formFilter').trigger('submit');
        });
    });
    
    $('.selectFilter').each(function(){
        $(this).on('change',function(){
            $(this).closest('tr').prevAll('.formFilter:first').trigger('submit');
        });
    });

    $('.radioFilter').each(function(){
        $(this).on('change',function(){
            $(this).closest('.formFilter').trigger('submit');
        });
    });

    $('.dateFilter').each(function(){
        $(this).on('change',function(){
            $('.formFilter').trigger('submit');
        });
    });

    $('.downloadArea').on('click',function(e){
        $(this).find('.downloadLink').trigger('submit');
    })
    
    $('.uploadInput').on('dragover',function(){
        $('.uploadArea').addClass('dragOverAnimation');
    })

    $('.uploadInput').on('dragleave',function(){
        $('.uploadArea').removeClass('dragOverAnimation');
    })

    $('.uploadInput').on('drop',function(){
        $('.uploadInput').on('change',function(e){
            $('.uploadArea .uploadArea__title').css('display','none');
            $('.uploadAreaDrop').css('display','flex');
            $('.uploadArea').removeClass('dragOverAnimation');
    
            let fileName=e.target.files[0].name;
            $('.uploadAreaDrop').find('.uploadAreaDrop__descriptionFile').html(fileName);
        });
      
    })

    $('.uploadInput').on('change',function(e){
        $('.uploadArea .uploadArea__title').css('display','none');
        $('.uploadAreaDrop').css('display','flex');
        $('.uploadArea').removeClass('dragOverAnimation');

        let fileName=e.target.files[0].name;
        $('.uploadAreaDrop').find('.uploadAreaDrop__descriptionFile').html(fileName);
    });
    
    
    

    $('#container_search_sale__input').on('keyup',function(event){
        returnIdInterprise($(this).val());
        let url=$('#container_search_sale__input').attr('url');
        let id=$('#container_search_sale__input').attr('idSale');
        url=url.replace('idSale',id);
        if(id != -1){
            if(event.keyCode===13){
                window.location = url; 
            }
        }
    })
    
    function returnIdInterprise(contractNumber){
        $.ajax({
            url:CONTRACT_URL,
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'JSON',
            data:{'contract_number':contractNumber},
            success:function(id){
                if(id===-1){
                    $('#container_search_sale__input').addClass('inputDanger');
                    $('#container_search_sale__input').removeClass("inputOk");
                    $('#container_search_sale__input').attr('idSale',-1);
                }else{
                    $('#container_search_sale__input').removeClass("inputDanger");
                    $('#container_search_sale__input').addClass("inputOk");
                    $('#container_search_sale__input').attr('idSale',id);
                }
            }
        })
    }

   

})

    
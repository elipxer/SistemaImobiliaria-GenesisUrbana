$(()=>{
    
    var updatedProcessForm="";
    if($('#updatedProcessForm').length > 0){
        updatedProcessForm=$('#updatedProcessForm');
    }

    if($('.btnSeeMoreJuridical').length > 0){
        $('.btnSeeMoreJuridical').each(function(){
            $(this).on('click',function(){
                $(this).closest('.juridicalItem').find('.juridicalItem__content').slideToggle();;
            })
        })
    }

    if($('.btnSeeMoreUpdateJuridical').length>0){    
        $('.btnSeeMoreUpdateJuridical').each(function(){
            $(this).on('click',function(){
                $(this).closest('.updateJuridicalItem').find('.updateJuridicalItem__content').slideToggle();;
            })
        })
    }

    $('#btnAddUpdate').on('click',function(){    
        $(updatedProcessForm).show();
        $('#btnAddModal').off();
        $('#btnEditModal').off();
        $("#modalAcoes").find(".modal-dialog").css('max-width','800px');
        $("#modalAcoes").find(".modal-body").empty();
        $("#modalAcoes").find(".modal-body").append(updatedProcessForm);
        $("#btnEditModal").hide();
        $("#btnAddModal").hide();
        $("#modalAcoes").find(".modal-title").html('Adicione atualizações sobre o processo');

       
    })

})
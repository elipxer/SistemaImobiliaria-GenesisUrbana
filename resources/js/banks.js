$(()=>{
    var bankForm=$('#banksForm');

    $('#btnAddBank').on('click',function(){    
        $('#banksForm').attr('action',ADDBANK);
        clearInputs();
        $(bankForm).show();
        $('#btnAddModal').off();
        $('#btnEditModal').off();
        $("#modalAcoes").find(".modal-body").empty();
        $("#modalAcoes").find(".modal-body").append(bankForm);
        $("#modalAcoes").find('.modal-dialog').css('max-width',650);
        $("#btnEditModal").hide();
        $("#btnAddModal").hide();
        $("#modalAcoes").find(".modal-title").html('Adicionar banco');
    })

    $('.btnEditBank').each(function(){
        $(this).on('click',function(){   
            let idBank=$(this).attr('id');
            let idPermissionUser=$(this).attr('id_permission');
            idPermissionUser=idPermissionUser.split(',');
            checkInputsUsers(idPermissionUser);

            $('#banksForm').attr('action',UPDATEBANK);
            $('#idBank').val(idBank);
            let name=$(this).closest('tr').find('td').eq(0).html();
            let description=$(this).closest('tr').find('td').eq(1).html();
            $('#banksForm').find('input[name=name]').val(name);
            $('#banksForm').find('textarea[name=description]').val(description);

            $(bankForm).show();
            $('#btnAddModal').off();
            $('#btnEditModal').off();
            $("#modalAcoes").find(".modal-body").empty();
            $("#modalAcoes").find(".modal-body").append(bankForm);
            $("#modalAcoes").find('.modal-dialog').css('max-width',650);

            $("#btnEditModal").hide();
            $("#btnAddModal").hide();
            $("#modalAcoes").find(".modal-title").html('Atualizar banco');
        })
    })
    
    
    function clearInputs(){
        $(bankForm).find('input[type=text]').val("");
        $(bankForm).find('textarea').val("");
    }

    function checkInputsUsers(idPermissionUsers){
        let checkUsers=$('#tableUsers').find('.idUserCheck');

        $(checkUsers).each(function(){
            let id=$(this).val();
            if(idPermissionUsers.indexOf(id) > -1){
                $(this).prop('checked',true);
            }else{
                $(this).prop('checked',false);
            }
        })
    }

})
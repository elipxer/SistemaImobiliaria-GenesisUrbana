$(()=>{
    var internalAccountForm=$('#internalAccountForm');

    $('#btnInternalAccount').on('click',function(){    
        $('#banksForm').attr('action',ADDINTERNALACCOUNT);
        clearInputs();
        $(internalAccountForm).show();
        $('#btnAddModal').off();
        $('#btnEditModal').off();
        $("#modalAcoes").find(".modal-body").empty();
        $("#modalAcoes").find(".modal-body").append(internalAccountForm);
        $("#modalAcoes").find('.modal-dialog').css('max-width',650);

        $("#btnEditModal").hide();
        $("#btnAddModal").hide();
        $("#modalAcoes").find(".modal-title").html('Adicionar conta Interna');
    })

    $('.btnEditInternalAccount').each(function(){
        $(this).on('click',function(){   
            let idInternalAccount=$(this).attr('id');
            let idPermissionUser=$(this).attr('id_permission');
            idPermissionUser=idPermissionUser.split(',');
            checkInputsUsers(idPermissionUser);
            $('#internalAccountForm').attr('action',UPDATEINTERNALACCOUNT);
            $('#idInternalAccount').val(idInternalAccount);
            let name=$(this).closest('tr').find('td').eq(0).html();
            let description=$(this).closest('tr').find('td').eq(1).html();
            $('#internalAccountForm').find('input[name=name]').val(name);
            $('#internalAccountForm').find('textarea[name=description]').val(description);

            $(internalAccountForm).show();
            $('#btnAddModal').off();
            $('#btnEditModal').off();
            $("#modalAcoes").find(".modal-body").empty();
            $("#modalAcoes").find(".modal-body").append(internalAccountForm);
            $("#modalAcoes").find('.modal-dialog').css('max-width',650);

            $("#btnEditModal").hide();
            $("#btnAddModal").hide();
            $("#modalAcoes").find(".modal-title").html('Atualizar conta Interna');
        })
    })

    function clearInputs(){
        $(internalAccountForm).find('input[type=text]').val("");
        $(internalAccountForm).find('textarea').val("");
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
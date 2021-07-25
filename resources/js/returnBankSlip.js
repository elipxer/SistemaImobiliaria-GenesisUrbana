$(()=>{
    let formReturnBankSlip=$('#formReturnBankSlip');

    $('.uploadInput').on('change',function(e){
        $('#btnReturnBankSlip').attr('disabled',false);
    });

    $('#btnAddReturnBankSlip').on('click',function(){    
        $(formReturnBankSlip).show();
        $('#btnAddModal').off();
        $('#btnEditModal').off();
        $("#modalAcoes").find(".modal-dialog").css('max-width','800px');
        $("#modalAcoes").find(".modal-body").empty();
        $("#modalAcoes").find(".modal-body").append(formReturnBankSlip);
        $("#btnEditModal").hide();
        $("#btnAddModal").hide();
        $("#modalAcoes").find(".modal-header").addClass('justify-content-center');
        $("#modalAcoes").find(".modal-header").html("<div class='info__title'>Insira o arquivo de retorno</div>");
        
        $('#btnReturnBankSlip').on('click',function(event){
            event.preventDefault();
            if($('#id_bank').val() != ""){
                $('#formReturnBankSlip').trigger('submit');
            }else{
                Swal.fire({
                    icon: 'error',
                    text: 'Escolha algum banco!!',
                    customClass: 'mySweetalert',
                })
            }
        });
    })

    $('#btnSicredi').on('click',function(){
        $('#id_bank').val(1);
    })

    $('#btnCaixa').on('click',function(){
        $('#id_bank').val(2);
    })
});
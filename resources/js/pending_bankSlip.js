$(()=>{
    getBankSlipSelected();

    $('.checkBankSlip').each(function(){
        $(this).on('change',function(){
            getBankSlipSelected();
        })
    });

    $('#btnSendBankSlip').on('click',function(event){
        event.preventDefault();
        if(verifyBankSlipSelected()===false){
            Swal.fire({
                icon: 'error',
                text: 'Nenhum boleto selecionado!',
                customClass: 'mySweetalert',
            })
        }else{
            $('#generateSendBankSlipForm').trigger('submit');
        }
    })

    function getBankSlipSelected(){
        let bankSlipIds=[];

        $('.checkBankSlip').each(function(){
            if($(this).prop('checked')){
                let id=$(this).val();
                bankSlipIds.push(id);
            }
        })

        $('#bankSlipCheck').val(bankSlipIds);
    }

    function verifyBankSlipSelected(){
        let exist=false;

        $('.checkBankSlip').each(function(){
            if($(this).prop('checked')){
                exist=true;
            }
        })
        
        return exist;
    }
})
$(()=>{
    parcelsSelectedEvent();

    $('.radioSelectedIdSale').each(function(){
        if($(this).prop('checked')){
            let idSale=$(this).val();
            $('.id_sale').val(idSale);
        }
    })

    $('#viewBankSlipBtn').on('click',function(event){
        event.preventDefault();
        setInformationBankSlip();
        if(verifySaleSelected()==false){    
            Swal.fire({
                icon: 'error',
                text: 'Selecione algum contrato!',
                customClass: 'mySweetalert',
            }) 
        }else if(verifyParcelsSelected()==false){    
            Swal.fire({
                icon: 'error',
                text: 'Selecione alguma parcela!',
                customClass: 'mySweetalert',
            })

        }else if(verifyInputsBankSlip()==false){
            Swal.fire({
                icon: 'error',
                text: 'Preencha as informações do boleto!',
                customClass: 'mySweetalert',
            }) 
        }else{
            $('#formViewBankSlip').trigger('submit'); 
        }
    })

    $('.btnTypeBankSlip').on('click',function(){
        let value=$(this).attr('value');
        $('.btnTypeBankSlip').removeClass('btn_selected');
        $(this).addClass('btn_selected');
        $('#viewBankSlipBtn').prop('disabled',false);
        $('#generateBankSlipBtn').prop('disabled',false);
        $('.typeBankSlip').val(value);
    })

    $('#generateBankSlipBtn').on('click',function(event){
        event.preventDefault();
        setInformationBankSlip();
        if(verifySaleSelected()==false){    
            Swal.fire({
                icon: 'error',
                text: 'Selecione algum contrato!',
                customClass: 'mySweetalert',
            }) 
        }else if(verifyParcelsSelected()==false){    
            Swal.fire({
                icon: 'error',
                text: 'Selecione alguma parcela!',
                customClass: 'mySweetalert',
            })

        }else if(verifyInputsBankSlip()==false){
            Swal.fire({
                icon: 'error',
                text: 'Preencha as informações do boleto!',
                customClass: 'mySweetalert',
            })  
        }else{
            $('#formInfoBankSlip').trigger('submit'); 
        }
    })

    function setInformationBankSlip(){
        let form=$('#formInfoBankSlip');
        
        let descont=$(form).find('input[name=descont]').val();
        let bank_interest_rate=$(form).find('input[name=bank_interest_rate]').val();
        let fine=$(form).find('input[name=fine]').val();
        let id_financial_accounts=$(form).find('select[name=id_financial_accounts] option:selected').val();
        let delay_limit=$(form).find('input[name=delay_limit]').val();
        let observation=$(form).find('textarea[name=observation]').html();

        $('.descont').val(descont);
        $('.bank_interest_rate').val(bank_interest_rate);
        $('.fine').val(fine);
        $('.id_financial_accounts').val(id_financial_accounts);
        $('.delay_limit').val(delay_limit);
        $('.observation').val(observation);

    }

    function verifyInputsBankSlip(){
        let form=$('#formInfoBankSlip');

        let descont=$(form).find('input[name=descont]').val();
        let bank_interest_rate=$(form).find('input[name=bank_interest_rate]').val();
        let fine=$(form).find('input[name=fine]').val();
        let id_financial_accounts=$(form).find('select[name=id_financial_accounts] option:selected').val();
        let delay_limit=$(form).find('input[name=delay_limit]').val();

        if(descont==="" || bank_interest_rate==="" || fine==="" || id_financial_accounts==="" || delay_limit==="" ){
            return false;
        }else{
            return true;
        }
    }

    function verifyParcelsSelected(){
        exist=false;
        $('.checkParcelId').each(function(){
            if($(this).prop('checked')){
                exist=true;
            }
        })

        return exist;
    }

    function verifySaleSelected(){
        exist=false;
        $('.radioSelectedIdSale').each(function(){
            if($(this).prop('checked')){
                exist=true;
            }
        })

        return exist;
    }
    
    function parcelsSelectedEvent(){
        $('.checkParcelId').each(function(){
            $(this).on('change',function(){
                $('.id_parcels').val(getParcelsSelected());
             })
        });
    }

    function getParcelsSelected(){
        let parcelsIds=[];

        $('.checkParcelId').each(function(){
            if($(this).prop('checked')){
                let id=$(this).val();
                parcelsIds.push(id);
            }
        })

        return parcelsIds;
    }

    
})
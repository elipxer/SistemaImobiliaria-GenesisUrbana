$(()=>{

    $('.idBank').each(function(){
        $(this).on('change',function(){
            getIdBankSelected();
        })
    })

    $('#btnAddAlertPayment').on('click',function(event){
        event.preventDefault();
        if(verifyRequiredInputs() && verifyValuePad()){
            $('#formAlertPayment').trigger('submit');
        }
    })

    function getIdBankSelected(){
        $('.idBank').each(function(){
            if($(this).prop('checked')){
                let idBankOrigin=$(this).val();
                $('#idBank').val(idBankOrigin);
            }
        })  
    }

    function verifyRequiredInputs(){
        let verify=true;

        let idBank=$('#idBank').val();
        let value_payment=$('input[name=value_payment]').val();
        let payment_date=$('input[name=payment_date]').val();
        let payment_method=$('input[name=payment_method]').val();
        let proof_payment=$('#proof_payment').val();
        
        
        if(idBank==""){
            Swal.fire({
                icon: 'error',
                text: 'Escolha o banco!',
                customClass: 'mySweetalert',
            })   
            
            verify=false;
        
        }else if(value_payment=="" || payment_date=="" || payment_method==""){
            Swal.fire({
                icon: 'error',
                text: 'Preencha os campos obrigatórios da area de pagamento!',
                customClass: 'mySweetalert',
            })
            verify=false;
        
        }else if(proof_payment==""){
            Swal.fire({
                icon: 'error',
                text: 'Insira o comprovante!',
                customClass: 'mySweetalert',
            })   
            verify=false;
        }

        return verify;
    }

    function verifyValuePad(){
        let value=moneyfloat($('#valueToPad').val());
        let valuePayment=moneyfloat($('input[name=value_payment]').val());
        let verify=true;
       
        if(valuePayment < value){
            Swal.fire({
                icon: 'error',
                text: 'Valor pago menor que o valor a pagar!',
                customClass: 'mySweetalert',
            })   
            verify=false;
        }

        return verify;
    }

    function moneyfloat(money){
        money = money.replace("R$","");    
        money = money.replace(".","");
        money = money.replace(",",".");
        
        return parseFloat(money);
      
    }

    

})
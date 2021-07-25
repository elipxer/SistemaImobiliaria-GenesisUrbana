$(()=>{
    $('.idBankOrigin').each(function(){
        $(this).on('change',function(){
            getIdBankOriginSelected();
        })
    })

    $('.idBankDestiny').each(function(){
        $(this).on('change',function(){
            getIdBankDestinySelected();
        })
    })

    $('#btnAddTransfers').on('click',function(event){
        event.preventDefault();
        if(verifyRequiredInputs()){
             $('#formTransfers').trigger('submit');
        }

    })

    function verifyRequiredInputs(){
        let verify=true;

        let idBankOrigin=$('#idBankOrigin').val();
        let idBankDestiny=$('#idBankDestiny').val();
        let description=$('textarea[name=description]').val();
        let value=$('input[name=value]').val();

      
        if(idBankOrigin==""){
            Swal.fire({
                icon: 'error',
                text: 'Escolha o banco de origem!',
                customClass: 'mySweetalert',
            })   
            
            verify=false;
        
        }else if(idBankDestiny==""){
            Swal.fire({
                icon: 'error',
                text: 'Escolha o banco de destino!',
                customClass: 'mySweetalert',
            })   
            verify=false;

        }else if(description=="" || value==""){
            Swal.fire({
                icon: 'error',
                text: 'Preencha os campos obrigatórios da area de informações da transferência!',
                customClass: 'mySweetalert',
            })
            verify=false;
        } 

        return verify;
    }

    function getIdBankOriginSelected(){
        $('.idBankOrigin').each(function(){
            if($(this).prop('checked')){
                let idBankOrigin=$(this).val();
                $('#idBankOrigin').val(idBankOrigin);
                $('.idSelectedBankOrigin').val(idBankOrigin);
            }
        })  
    }

    function getIdBankDestinySelected(){
        $('.idBankDestiny').each(function(){
            if($(this).prop('checked')){
                let idBankDestiny=$(this).val();
                $('#idBankDestiny').val(idBankDestiny);
                $('.idSelectedBankDestiny').val(idBankDestiny)
                
            }
        })  
    }
})
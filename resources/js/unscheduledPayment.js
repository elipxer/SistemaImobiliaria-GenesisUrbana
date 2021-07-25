$(()=>{
    var lineClientTable=$('.clientLineTable');

    $('#clientName').on('keyup',function(){
        let clientVal=$(this).val();
        if(clientVal != ""){
            $('#clientArea').fadeIn();
            searchClients(clientVal);
           
        }else{
            $('#clientArea').fadeOut();
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
                $('#clientAreaTable').find('input[name=clientCheck]').each(function(){
                    $(this).on('change',function(){
                        setClientPayment();
                        verifyChangeValuesPerc();
                    })
                });
            }
        })
    }
    

    var checkClients=[];
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

            $('#clientAreaTable').find('input[name=clientRadio]').each(function(){
                $(this).on('change',function(){
                    let idClient=$(this).val();
                    $('#idProvider').val(idClient);
                })
            })
        }
    }

    $('.idBank').each(function(){
        $(this).on('change',function(){
            getIdBankSelected();
        })
    })

    $('.idInternalAccount').each(function(){
        $(this).on('change',function(){
            getInternalAccountSelected()
        })
    })

    $('#btnAddUnscheduledPayment').on('click',function(event){
        event.preventDefault();
        if(verifyRequiredInputs()){
            $('#formUnscheduledPayment').trigger('submit');
        }
    })
    

    function verifyRequiredInputs(){
        let verify=true;

        let idBank=$('#idBank').val();
        let idInternalAccount=$('#idInternalAccount').val();
        let idProvider=$('#idProvider').val();
        let description=$('textarea[name=description]').val();
        let value=$('input[name=value]').val();
        let deadline=$('input[name=deadline]').val();
        let payment_value=$('input[name=payment_value]').val();
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
        
        }else if(idInternalAccount==""){
            Swal.fire({
                icon: 'error',
                text: 'Escolha uma conta interna!',
                customClass: 'mySweetalert',
            })   
            verify=false;
        
        }else if(idProvider==""){
            Swal.fire({
                icon: 'error',
                text: 'Escolha um fornecedor!',
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
          

        }else if(description=="" || value=="" || deadline=="" || payment_value=="" || payment_date=="" || payment_method==""){
            Swal.fire({
                icon: 'error',
                text: 'Preencha os campos obrigatórios da area de Informações do pagamento não programado!',
                customClass: 'mySweetalert',
            })
            verify=false;
        } 

        return verify;
    }

    function getIdBankSelected(){
        $('.idBank').each(function(){
            if($(this).prop('checked')){
                let idBankOrigin=$(this).val();
                $('#idBank').val(idBankOrigin);
            }
        })  
    }

    function getInternalAccountSelected(){
        $('.idInternalAccount').each(function(){
            if($(this).prop('checked')){
                let idBankOrigin=$(this).val();
                $('#idInternalAccount').val(idBankOrigin);
            }
        })  
    }

})
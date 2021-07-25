$(()=>{
    var date = new Date();
    const NOW = getTodayDate();
    
    function getTodayDate() {
        var tdate = new Date();
        var dd = tdate.getDate(); //yields day
        var MM = tdate.getMonth()+1; //yields month
        if(MM < 10){
            MM="0"+MM;
        }
        var yyyy = tdate.getFullYear(); //yields year
        var currentDate= yyyy + "-" + MM + "-" + dd ;
    
        return currentDate;
    }


    var lineClientTable=$('.clientLineTable');
    var parcelConfirmation=1;

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

    $('#btnAddProgramedPayment').on('click',function(event){
        event.preventDefault();
    })

    $('.idInternalAccount').each(function(){
        $(this).on('change',function(){
            getInternalAccountSelected()
        })
    })

    $('#btnAddProgramedPayment').on('click',function(event){
        event.preventDefault();
        if(verifyRequiredInputs()){
            $('#formProgramedPayment').trigger('submit');
        }
    })

    function getInternalAccountSelected(){
        $('.idInternalAccount').each(function(){
            if($(this).prop('checked')){
                let idBankOrigin=$(this).val();
                $('#idInternalAccount').val(idBankOrigin);
            }
        })  
    }

    function verifyRequiredInputs(){
        let verify=true;

        let idInternalAccount=$('#idInternalAccount').val();
        let idProvider=$('#idProvider').val();
        let month=$('input[name=month]').val();
        let days=$('input[name=days]').val();
        let firstParcel=$('input[name=firstParcel]').val();
        let value=$('input[name=value]').val();
        let description=$('textarea[name=description]').val();
        let numberParcel=$('input[name=numberParcel]').val();
      
        if(idInternalAccount==""){
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
        
        }else if(value===""){
            Swal.fire({
                icon: 'error',
                text: 'Preencha o campo valor!',
                customClass: 'mySweetalert',
            })   
            verify=false;
        
        }else if(description===""){
            Swal.fire({
                icon: 'error',
                text: 'Preencha a descrição!',
                customClass: 'mySweetalert',
            })   
            verify=false;

        }else if(parcelConfirmation==1){
            if(days==="" && month===""){
                Swal.fire({
                    icon: 'error',
                    text: 'Preencha o campo dias ou o campo meses!',
                    customClass: 'mySweetalert',
                })   
                verify=false;
            }

            if(firstParcel===""){
                Swal.fire({
                    icon: 'error',
                    text: 'Preencha o campo primeira parcela!',
                    customClass: 'mySweetalert',
                })   
                verify=false;
            }

            if(numberParcel===""){
                Swal.fire({
                    icon: 'error',
                    text: 'Preencha o campo numero parcelas!',
                    customClass: 'mySweetalert',
                })   
                verify=false;
            }
        } 

        return verify;
    }


    $('input[name=parcelConfirmation]').each(function(){
        $(this).on('change',function(){
            let value=$(this).val();
            parcelConfirmation=value;
            if(value==1){
                $('#parcelArea').css('display','block');
                $('#noParcelArea').css('display','none');
                $('#payNowArea').css('display','none');
            }else{
                $('#parcelArea').css('display','none');
                $('#noParcelArea').css('display','block');
                $('#payNowArea').css('display','block');

                payNowEvent();
            }
        })
    })
    
    function payNowEvent(){
        $('.payNow').each(function(){
            $(this).on('change',function(){
                let value=$(this).val();
                if(value==1){
                    $('#date_payment').prop('readOnly',true);
                    $('#date_payment').val(NOW);
                }else{
                    $('#date_payment').prop('readOnly',false);
                    $('#date_payment').val("");

                  
                }
            })
        })
    }

    

})
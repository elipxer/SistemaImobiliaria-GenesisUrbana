$(()=>{
    var lineClientTable=$('.clientLineTable');
    var line=$('.clientLinePorc'); 
    
    $('input[name=expiration_day]').on('keyup',function(){
        let val=$(this).val();
        if(val>31){
            $(this).val(31);
        }

    })

    $('#clientName').on('keyup',function(){
        let clientVal=$(this).val();
        if(clientVal != ""){
            $('#clientArea').fadeIn();
            searchClients(clientVal);
           
        }else{
            $('#clientArea').fadeOut();
        }
    })

    $('input[name=interpriseCheck]').each(function(){
        $(this).on('change',function(){
            if($(this).prop('checked')){
                $('#id_interprise').val($(this).val());
                let idLotCheck=$(this).next('input[name=lotCheck]').val();
                getLotInformation(idLotCheck);   
                $('#id_lot').val(idLotCheck);
                let lot_number=$(this).closest('tr').find('td').eq(2).html();
                $('#lot_number_input').html(lot_number);
                $('input[name=parcels]').attr('readOnly',false);
            }
        })
    });

    function getLotInformation(idLotCheck){
        $.ajax({
            url:LOT_URL,
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'idLot':idLotCheck},
            dataType:'json',
            success:function(json){
               fillInputsSale(json.lot);
            },
            error:function(){
                alert("algo deu errado!");
            }
        });
    }

    function fillInputsSale(lotInfo){
        $('input[name=value]').val(lotInfo.present_value);
        $('input[name=input]').val(lotInfo.input);
        $('input[name=descont]').val(lotInfo.descont);
        $('input[name=future_value]').val(lotInfo.future_value);
    }

    $('input[name=interpriseCheck]').each(function(){
        $(this).on('change',function(){
            if($(this).prop('checked')){
                $('#id_interprise').val($(this).val());
                let idLotCheck=$(this).next('input[name=lotCheck]').val();
                getLotInformation(idLotCheck);  
                $('#id_lot').val(idLotCheck);
                let lot_number=$(this).closest('tr').find('td').eq(2).html();
                $('#lot_number_input').html(lot_number);
            }
        })
    });

    $('input[name=parcels]').on('keyup',function(){
        let valueLot=$('#value').val();
        let input=$('input[name=input]').val();
        let descont=$('input[name=descont]').val();
        let future_value=$('input[name=future_value]').val();
        divideParcels(calcFinalValue(valueLot,input,descont,future_value))
    })
    
    function getLotInformation(idLotCheck){
        $.ajax({
            url:LOT_URL,
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'idLot':idLotCheck},
            dataType:'json',
            success:function(json){
                fillInputsSale(json.lot);
                setFinalValue(); 
                $('input[name=contract_number]').trigger('focus');
            },
            error:function(){
                alert("algo deu errado!");
            }
        });
    }

    function fillInputsSale(lotInfo){
        $('#value').val(lotInfo.present_value);
        
        if(lotInfo.input != null){
            $('input[name=input]').val(lotInfo.input);
        }else{
            $('input[name=input]').val("0,00");
        }
        
        if(lotInfo.descont!=null){
            $('input[name=descont]').val(lotInfo.descont);
        }else{
            $('input[name=descont]').val("0,00");
        }

        if(lotInfo.future_value!=null){
            $('input[name=future_value]').val(lotInfo.future_value);
        }else{
            $('input[name=future_value]').val("0,00");
        }
    }

    function setFinalValue(){
        let valueLot=$('#value').val();
        let input=$('input[name=input]').val();
        let descont=$('input[name=descont]').val();
        let futureValue=$('input[name=future_value]').val();
        
        $('input[name=value]').val(calcFinalValue(valueLot,input,descont,futureValue));
        $('input[name=value]').trigger('focus');
        $('input[name=value]').trigger('blur');
        divideParcels(calcFinalValue(valueLot,input,descont,futureValue));
    }
    
    $('#addSale').on('click',function(){
        if(verifySelectedLot()==false){
            Swal.fire({
                icon: 'error',
                text: 'Selecione um lot!',
                customClass: 'mySweetalert',
            })
        }else if(verifySelectedClient()===false){
            Swal.fire({
                icon: 'error',
                text: 'Selecione pelo menos um cliente!',
                customClass: 'mySweetalert',
            })
        
        }else if(verifyPorcClient()===false){
            Swal.fire({
                icon: 'error',
                text: 'As soma das porcentagens do cliente não está dando 100%!',
                customClass: 'mySweetalert',
            })    
        }else if(verifySaleInfo()===false){
            Swal.fire({
                icon: 'error',
                text: 'Preencha os campos obrigatórios da venda!',
                customClass: 'mySweetalert',
            })
        }else if(verifyContractNumber()){
            Swal.fire({
                icon: 'error',
                text: 'Esse número de contrato ja está sendo utilizado!',
                customClass: 'mySweetalert',
            })   
        }else{
            $('#formSale').trigger('submit');
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
                        setClientsChoose();

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

            $('#clientAreaTable').find('input[name=clientCheck]').each(function(){
                $(this).on('change',function(){
                    let idClient=$(this).val();
                    
                    if($(this).prop('checked')){
                        let index=$.inArray( idClient, checkClients)
                        if(index===-1){
                            checkClients.push(idClient);
                        }
                    }

                   

                    $('#id_clients').val(checkClients);

                })
            })
        }
    }

    function verifyChangeValuesPerc(){
        $('.porcValue').each(function(){
            $(this).on('keyup',function(){
                if($(this).val()===""){
                    $(this).val(0);
                }
                let clients_porc=[];
                $('#card_porc').find('.clientLinePorc').each(function(){
                    let valuePorc=parseFloat($(this).find('.porcValue').val());
                    let idClient=$(this).find('.idClient').val();
                    let clientPorc=idClient+"-"+valuePorc;
                    clients_porc.push(clientPorc);
                });
        
                $('#id_clients_porc').val(clients_porc);
            
            })
        });
    }   

    var clientsChoise=[];
    function setClientPayment(){
        $('#clientAreaTable').find('input[name=clientCheck]').each(function(){
            if($(this).prop('checked')){
                let id=$(this).val();
                let name=$(this).closest('tr').find('td').eq(1).html();
                let cpf=$(this).closest('tr').find('td').eq(2).html();
                let cnpj=$(this).closest('tr').find('td').eq(3).html();
                
                let clients={
                    id:id,
                    name:name,
                    cpf:cpf,
                    cnpj:cnpj
                }
                
                let index=clientsChoise.findIndex((client)=>{
                    if(client.id===clients.id){
                        return true;
                    }else{
                        return false;
                    }
                })
                if(index===-1){
                    clientsChoise.push(clients);
                }
            }
        })

        $('#client_payment').empty();
        clientsChoise.forEach(element => {
            let option="<option value='"+element.id+"'>"+'Nome: '+element.name+'    Cpf/Cpnj:  '+element.cpf+'/'+element.cnpj+"</option>"
            $('#client_payment').append(option);
        });

        if(clientsChoise.length>0){
            $('#client_payment_id').val(clientsChoise[0].id);
        }
        
        $('#client_payment').on('change',function(){
            let id=$(this).find('option:selected').val();
            $('#client_payment_id').val(id);
        })

        
        $('#card_porc').empty();
        clientsChoise.forEach(element => {
            let linePorc=line.clone();
            $(linePorc).css('display','flex');
            let nameCpfCnpj="Nome: "+element.name+" Cpf/Cpnj"+element.cpf+'/'+element.cnpj;
            $(linePorc).find('.nameClient').html(nameCpfCnpj);
            $(linePorc).find('.idClient').val(element.id);
            $('#card_porc').append(linePorc);
        });

        calcPorcClient();
    }

    var chooseClientRow=$('.chooseClientRow');
    function setClientsChoose(){
        $('#card_chooseClients').empty();
        clientsChoise.forEach(element => {
            let line=chooseClientRow.clone();
            $(line).css('display','flex');
            $(line).find('input').val(element.id);
            $(line).find('div').eq(0).html(element.name);
            let nameCpfCnpj="Cpf/Cpnj "+element.cpf+'/'+element.cnpj;
            $(line).find('div').eq(1).html(nameCpfCnpj);
            $('#card_chooseClients').append(line);
        });

        deleteClientsChooseEvent();
    }

    function deleteClientsChooseEvent(){
        let clientsChooseRow=$('#card_chooseClients').find('.btnDelete-chooseClientRow');
        $(clientsChooseRow).each(function(){
            $(this).on('click',function(){
                let id=$(this).closest('.chooseClientRow').find('input').val();
                let index=clientsChoise.findIndex(function(item){
                    if(item.id===id){
                        return true;
                    }
                });
                clientsChoise.splice(index,1);
               
                setClientsChoose();
                

                $('#client_payment').empty();
                    clientsChoise.forEach(element => {
                        let option="<option value='"+element.id+"'>"+'Nome: '+element.name+'    Cpf/Cpnj:  '+element.cpf+'/'+element.cnpj+"</option>"
                        $('#client_payment').append(option);
                    });

                    if(clientsChoise.length>0){
                        $('#client_payment_id').val(clientsChoise[0].id);
                    }
                    
                    $('#client_payment').on('change',function(){
                        let id=$(this).find('option:selected').val();
                        $('#client_payment_id').val(id);
                    })

                    
                    $('#card_porc').empty();
                    let checkClientsDelete=[];

                    clientsChoise.forEach(element => {
                        let linePorc=line.clone();
                        $(linePorc).css('display','flex');
                        let nameCpfCnpj="Nome: "+element.name+" Cpf/Cpnj"+element.cpf+'/'+element.cnpj;
                        $(linePorc).find('.nameClient').html(nameCpfCnpj);
                        $(linePorc).find('.idClient').val(element.id);
                        $('#card_porc').append(linePorc);
                        checkClientsDelete.push(element.id);
                    });
                    calcPorcClient();
                    $('#id_clients').val(checkClientsDelete.join(','));
                    checkClients=checkClientsDelete;
                    $('#clientAreaTable').find('tbody').empty();
                    $('#clientName').val("");
                });
            });
        }

    function calcPorcClient(){
        let numberClients=$('#card_porc').find('.clientLinePorc').length;
        let valuePorc=100/parseInt(numberClients);
        $('#card_porc').find('.clientLinePorc').each(function(){
            $(this).find('.porcValue').val(valuePorc.toFixed(2));
        });

        let clients_porc=[];
        $('#card_porc').find('.clientLinePorc').each(function(){
            let valuePorc=parseFloat($(this).find('.porcValue').val());
            let idClient=$(this).find('.idClient').val();
            let clientPorc=idClient+"-"+valuePorc;
            clients_porc.push(clientPorc);
        });

        $('#id_clients_porc').val(clients_porc);
    }

    function verifyPorcClient(){
        let total=0;
        $('#card_porc').find('.clientLinePorc').each(function(){
            let valuePorc=parseFloat($(this).find('.porcValue').val());
            total=total+valuePorc;
        });

        if(total==100){
            return true;
        }else{
            return false;
        }
    }
    
    function calcFinalValue(value=null,input=null,descont=null,future_value=null){
        value=moneyfloat(value);
        input=moneyfloat(input);
        descont=moneyfloat(descont);
        future_value=moneyfloat(future_value);

        let finalValue=future_value-input-descont;
        return finalValue;
    }
    
    function moneyfloat(money){
        money = money.replace("R$","");    
        money = money.replace(".","");
        money = money.replace(",",".");
        
        return parseFloat(money).toFixed(2);
      
     }

    function divideParcels(value){
        let parcelsNumber=parseInt($('#parcels').val());
     
        if($('#parcels').val()<=0){
            parcelsNumber=1;
        }
        let valueParcel=value/parcelsNumber;
        $('#value_parcel').val(valueParcel.toFixed(2));
        
        $('#value_parcel').trigger('focus');
        $('#parcels').trigger('focus');
        
    }

    function verifySelectedLot(){
        let checkedLot=false;

        $('input[name=interpriseCheck]').each(function(){
            if($(this).prop('checked')){
                checkedLot=true;
            }
        })
        
        return checkedLot;
    }   

    function verifySelectedClient(){
        let checkedClient=false;

        $('#clientAreaTable').find('input[name=clientCheck]').each(function(){
            if($(this).prop('checked')){
                checkedClient=true;
            }
        })
        return checkedClient;
    }   

    function verifySaleInfo(){
        let infoSale=true;

        let contract_number=$('input[name=contract_number]').val();
        let input_value=$('input[name=input_value]').val();
        let expiration_day=$('input[name=expiration_day]').val();
        let first_parcel=$('input[name=first_parcel]').val();
        let parcels=$('input[name=parcels]').val();
        let value_parcel=$('input[name=value_parcel]').val();
        let annual_rate=$('input[name=annual_rate]').val();

        if(contract_number==="" || input_value==="" || expiration_day===""|| first_parcel==="" 
            || parcels==="" || parcels===0 || value_parcel==="" || annual_rate===""){
            
            infoSale=false;
        }
        
        return infoSale;    
    }

    function verifyContractNumber(){
        let exists=false;
        let contractNumber=$('input[name=contract_number]').val();
        $.ajax({
            url:VERIFY_CONTRACT_URL,
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'JSON',
            data:{'contract_number':contractNumber},
            async:false,
            success:function(json){
                if(json!=-1){
                    exists=true;
                }
            }
        })

        return exists;
    }

})
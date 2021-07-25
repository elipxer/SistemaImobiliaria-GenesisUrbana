$(()=>{
    verifyCardKindPerson();
    $("input[name=cep]").mask("99999-999",{completed:function(){searchCep(this.val())}});
    $("input[name=cep_payment_collection]").mask("99999-999",{completed:function(){searchCep(this.val(),true)}});
    
    if(edit==false){
        if(cpf_representative_register){
            $('#card-Cnpj').css('display','block');
            $('#card-Cpf').css('display','none');
            $("input[name=representative_cpf]").addClass('inputOk');
        }
    }
    
    function searchCep(cep,payment_collection_address=false){
        $.ajax({
            url:ADDRESS_URL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            dataType:'json',
            data:{'cep':cep},
            success:function(json){
                if(payment_collection_address===false){
                    setAddressInputs(json.address);   
                }else{
                    setAddressInputs(json.address,true);   
                }
            },

            error:function(){
                alert("algo deu errado!");
            }
        });
    }

    function setAddressInputs(address,payment_collection_address=false){
        if(payment_collection_address===false){
            $('input[name=street]').val(address.logradouro);
            $('input[name=neighborhood]').val(address.bairro);
            $('input[name=city]').val(address.localidade);
            $('select[name=state]').find('option').each(function(){
                if($(this).val()===address.uf){
                    $(this).prop('selected',true);
                }
            });
        }else{
            $('input[name=street_payment_collection]').val(address.logradouro);
            $('input[name=neighborhood_payment_collection]').val(address.bairro);
            $('input[name=city_payment_collection]').val(address.localidade);
            $('select[name=state_payment_collection]').find('option').each(function(){
                if($(this).val()===address.uf){
                    $(this).prop('selected',true);
                }
            });
        }
    }

    var clientAddress=[];
    function copyClientAddress(){
        clientAddress=[];
        clientAddress['street']=$('#address_client').find('input[name=street]').val();  
        clientAddress['number']=$('#address_client').find('input[name=number]').val(); 
        clientAddress['neighborhood']=$('#address_client').find('input[name=neighborhood]').val(); 
        clientAddress['city']=$('#address_client').find('input[name=city]').val(); 
        clientAddress['state']=$('#address_client').find('select[name=state]').find('option:selected').val(); 
        clientAddress['complement']=$('#address_client').find('input[name=complement]').val(); 
        clientAddress['cep']=$('#address_client').find('input[name=cep]').val(); 
    }

    copyClientAddress();

    $('#kind_person').on('change',function(){
        verifyCardKindPerson();
    });

    
    $('#marital_status').on('change',function(){
        if($(this).find('option:selected').val()==="2"){
            $('#card-spouse').css('display','block');
        }else{
            $('#card-spouse').css('display','none');
            $('#card-spouse').find('input').val("");
        }
    });
    
    function verifyCardKindPerson(){
       if($('#kind_person').find('option:selected').val()==="1"){
            $('#card-Cpf').css('display','block');
            $('#card-Cnpj').css('display','none');
        }
        
        if($('#kind_person').find('option:selected').val()==="2"){
            $('#card-Cnpj').css('display','block');
            $('#card-Cpf').css('display','none');
            $("input[name=cnpj]").mask("99.999.999/9999-99",{completed:function(){searchCnpjInfo(this.val())}});
        }
    }

    function searchCnpjInfo(cnpj){
        $.ajax({
            url:CNPJ_URL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            data:{'cnpj':cnpj},
            dataType:'json',
            success:function(json){
                setCnpjInputs(json.cnpj_info);
            }
        })
    }

    function setCnpjInputs(json){
        if(json.fantasia != ""){
            $('#company_name').val(json.fantasia);
        }else{
            $('#company_name').val(json.nome);
        }
        
        $('input[name=email]').val(json.email);
        $('#address_client').find('input[name=street]').val(json.logradouro);  
        $('#address_client').find('input[name=number]').val(json.numero); 
        $('#address_client').find('input[name=neighborhood]').val(json.bairro);
        $('#address_client').find('input[name=complement]').val(json.complemento);  
        $('#address_client').find('input[name=city]').val(json.municipio); 
        $('#address_client').find('input[name=cep]').val(json.cep.replace('.','')); 
        $('select[name=state]').find('option').each(function(){
            if($(this).val()===json.uf){
                $(this).prop('selected',true);
            }
        });
    }

    
    $("input[name=representative_cpf]").mask("999.999.999-99",{completed:function(){verifyClientExist(this.val())}});
    
    $("input[name=representative_cpf]").on('keyup',function(event){
        let keyCode=event.keyCode;
        let backspace=8;
        let del=46;   
        
        if(keyCode===backspace || keyCode===del){
            $("input[name=representative_cpf]").removeClass('inputDanger');
            $("input[name=representative_cpf]").removeClass('inputOk');
            $('#btnRegister').css('display','none');
        }
    })

    function verifyClientExist(cpf){
        $.ajax({
            url:CPF_URL,
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'cpf':cpf},
            dataType:'json',
            success:function(idClient){
                validateRepresentativeCPF(idClient,cpf);
            },
            error:function(){
                alert("algo deu errado");
            }
        });
    }

    function validateRepresentativeCPF(idClientVerify,cpf){
        if(isValidCPF(cpf)===false){
            $("input[name=representative_cpf]").val("");
            $("input[name=representative_cpf]").removeClass('inputDanger');
            $("input[name=representative_cpf]").removeClass('inputOk');

            Swal.fire({
                icon: 'error',
                text: 'Cpf Inválido',
                customClass: 'mySweetalert',
            })
           
        }else{
            if(idClientVerify!=0){
                $("input[name=representative_cpf]").addClass('inputOk');
                $('#idRepresentative').val(idClientVerify);
                $('#btnRegister').css('display','none');
            }else if(idClientVerify==0){
                $("input[name=representative_cpf]").addClass('inputDanger');
                $('#btnRegister').css('display','flex');
                
                let cpf_representative=$("input[name=representative_cpf]").val();
                let url="";
                
                if(edit===false){
                    url=BASE_URL+'/addClient/'+cpf_representative;
                    $('#idRepresentative').val("");
                }else{
                    $('#idRepresentative').val(idClientRepresentative);
                    url=BASE_URL+'/addClient/'+cpf_representative+'/'+idClient;
                }
                
                $('#btnRegister').attr('href',url);

            }
        }
    }
    
    $('#btnCopyAddress').on('click',function(event){
        copyClientAddress();
        event.preventDefault();
        $('#address_payment_collection').find('input[name=cep_payment_collection]').val(clientAddress['cep']);
        $('#address_payment_collection').find('input[name=street_payment_collection]').val(clientAddress['street']);
        $('#address_payment_collection').find('input[name=number_payment_collection]').val(clientAddress['number']);
        $('#address_payment_collection').find('input[name=city_payment_collection]').val(clientAddress['city']);
        $('#address_payment_collection').find('input[name=neighborhood_payment_collection]').val(clientAddress['neighborhood']);
        $('#address_payment_collection').find('input[name=complement_payment_collection]').val(clientAddress['complement']);
        $('#state_payment_collection').find('option').each(function(){
            if($(this).val()==clientAddress['state']){
                $(this).prop('selected',true);
            }
        })
    })

    $("input[name=cpf]").mask("999.999.999-99",{completed:function(){
        if(isValidCPF(this.val())===false){
            $("input[name=cpf]").val("");
            Swal.fire({
                icon: 'error',
                text: 'Cpf Inválido',
                customClass: 'mySweetalert',
            })
        }
    }});

    $("input[name=spouse_cpf]").mask("999.999.999-99",{completed:function(){
        if(isValidCPF(this.val())===false){
            $("input[name=spouse_cpf]").val("");
            Swal.fire({
                icon: 'error',
                text: 'Cpf Inválido',
                customClass: 'mySweetalert',
            })
        }
    }});

    $("input[name=cnpj]").mask("99.999.999/9999-99",{completed:function(){
        if(cnpjValidation(this.val())===false){
            $("input[name=cnpj]").val("");
            Swal.fire({
                icon: 'error',
                text: 'CNPJ Inválido',
                customClass: 'mySweetalert',
            })
        }
    }});

    $("input[name=spouse_cnpj]").mask("99.999.999/9999-99",{completed:function(){
        if(cnpjValidation(this.val())===false){
            $("input[name=spouse_cnpj]").val("");
            Swal.fire({
                icon: 'error',
                text: 'CNPJ Inválido',
                customClass: 'mySweetalert',
            })
        }
    }});

   
    function isValidCPF(cpf) {
        if (typeof cpf !== "string") return false
        cpf = cpf.replace(/[\s.-]*/igm, '')
        
        if (cpf.length !== 11 || !Array.from(cpf).filter(e => e !== cpf[0]).length) {
            return false
      }
        var sum = 0
        var rest
        for (var i = 1; i <= 9; i++) 
            sum = sum + parseInt(cpf.substring(i-1, i)) * (11 - i)
            rest = (sum * 10) % 11
        if ((rest == 10) || (rest == 11))  rest = 0
        if (rest != parseInt(cpf.substring(9, 10)) ) return false
        sum = 0
        for (var i = 1; i <= 10; i++) 
            sum = sum + parseInt(cpf.substring(i-1, i)) * (12 - i)
        rest = (sum * 10) % 11
        if ((rest == 10) || (rest == 11))  rest = 0
        if (rest != parseInt(cpf.substring(10, 11) ) ) return false
        return true
    }


    function cnpjValidation(value) {
        if (!value) return false
      
        // Aceita receber o valor como string, número ou array com todos os dígitos
        const validTypes =
          typeof value === 'string' || Number.isInteger(value) || Array.isArray(value)
      
        // Elimina valor em formato inválido
        if (!validTypes) return false
      
        // Guarda um array com todos os dígitos do valor
        const match = value.toString().match(/\d/g)
        const numbers = Array.isArray(match) ? match.map(Number) : []
      
        // Valida a quantidade de dígitos
        if (numbers.length !== 14) return false
        
        // Elimina inválidos com todos os dígitos iguais
        const items = [...new Set(numbers)]
        if (items.length === 1) return false
      
        // Cálculo validador
        const calc = (x) => {
          const slice = numbers.slice(0, x)
          let factor = x - 7
          let sum = 0
      
          for (let i = x; i >= 1; i--) {
            const n = slice[x - i]
            sum += n * factor--
            if (factor < 2) factor = 9
          }
      
          const result = 11 - (sum % 11)
      
          return result > 9 ? 0 : result
        }
      
        // Separa os 2 últimos dígitos de verificadores
        const digits = numbers.slice(12)
        
        // Valida 1o. dígito verificador
        const digit0 = calc(12)
        if (digit0 !== digits[0]) return false
      
        // Valida 2o. dígito verificador
        const digit1 = calc(13)
        return digit1 === digits[1]
    }
     
    
    var phones=[];
    var phoneItem=$('.phoneArea__item').eq(0);
    var phoneArea=$('.phoneArea');
    
    if($(phoneArea).attr('edit')==="false"){
        $('.phoneArea').empty();
    }else if($(phoneArea).attr('edit')==="true"){
        $('.phoneArea__item').find('input').each(function(){
            let whatsApp=$(this).closest('.phoneArea__item').attr('whatsApp');
            if(whatsApp!=undefined){
                whatsApp=true;
            }else{
                whatsApp=false;
            }
           
            phones.push({
                'id':phones.length,
                'phone':$(this).val(),
                'whatsApp':whatsApp
            })
            
        });
    }
   
    $('#btnAddPhone').prop('disabled',true);

    $('#phoneSelected').mask("(99)99999-9999",{completed:function(){
        $('#btnAddPhone').prop('disabled',false);
    }})

    $('#btnAddPhone').on('click',function(e){
        e.preventDefault();
        
        let phoneValue=$('#phoneSelected').val();
        let whatsApp=false; 
        if(phones.length==0){
            whatsApp=true;
        }
        phones.push({
                'id':phones.length,
                'phone':phoneValue,
                'whatsApp':whatsApp
            })
        $('#phoneSelected').val("");    
        setAllPhoneItems(phones);
        $('#btnAddPhone').prop('disabled',true);
    });

    function setAllPhoneItems(phones){
        $(phoneArea).empty();
        $.each(phones, function(index, value) {
            let newPhoneItem=$(phoneItem).clone();
            console.log(value.whatsApp);
            if(value.whatsApp){
                $(newPhoneItem).find('.btnWhatsApp').removeClass('whatsApp__disabled');
                $(newPhoneItem).find('.btnWhatsApp').addClass('whatsApp');
            }else{
                $(newPhoneItem).find('.btnWhatsApp').removeClass('whatsApp');
                $(newPhoneItem).find('.btnWhatsApp').addClass('whatsApp__disabled');
            }
            
            $(newPhoneItem).css('display','flex');
            $(newPhoneItem).find('input').val(value.phone);
            $(newPhoneItem).find('.btnDeletePhone').attr('key',value.id);
            $(phoneArea).append(newPhoneItem);
        });

        $('.btnDeletePhone').each(function(){
            $(this).on('click',function(){
                let key=parseInt($(this).attr('key'));
                phones.splice(key, 1);
                $.each(phones, function(index, value) {
                    phones[index].id=index;
                })
                
                setAllPhoneItems(phones);
            })
        });

        $('.btnWhatsApp').each(function(){
            $(this).on('click',function(){
                $('.btnWhatsApp').each(function(){
                    $(this).removeClass('whatsApp');
                    $(this).addClass('whatsApp__disabled');
                });
                $(this).removeClass('whatsApp__disabled');
                $(this).addClass('whatsApp');
                let phone=$(this).closest('.phoneArea__item').find('input').val();
                $("#whatsAppNumber").val(phone);

            })
        });
    }
        
        
});
$(()=>{
    $("#representative_cpf").mask("999.999.999-99",{completed:function(){
        if(isValidCPF(this.val())===false){
            $("#representative_cpf").val("");
            Swal.fire({
                icon: 'error',
                text: 'Cpf Inválido',
                customClass: 'mySweetalert',
            })
        }
    }});
    
    $("#cnpj").mask("99.999.999/9999-99",{completed:function(){
        if(cnpjValidation(this.val())===false){
            $("input[name=cnpj]").val("");
            Swal.fire({
                icon: 'error',
                text: 'CNPJ Inválido',
                customClass: 'mySweetalert',
            })
        }
    }});

    $('#btnCopyAddress').on('click',function(event){
        event.preventDefault();
        copyClientAddress();
    })

    $("#cep").mask("99999-999",{completed:function(){searchCep(this.val())}});
    $("#representative_cep").mask("99999-999",{completed:function(){searchCep(this.val(),true)}});

    $("#cnpj").mask("99.999.999/9999-99",{completed:function(){searchCnpjInfo(this.val())}});
    
    

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
            $('input[name=representative_street]').val(address.logradouro);
            $('input[name=representative_neighborhood]').val(address.bairro);
            $('input[name=representative_city]').val(address.localidade);
            $('select[name=representative_state]').find('option').each(function(){
                if($(this).val()===address.uf){
                    $(this).prop('selected',true);
                }
            });
        }
    }
    
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
        
        $('#street').val(json.logradouro);
        $('#number').val(json.numero);
        $('#cep').val(json.cep);
        $('#complement').val(json.complemento);
        $('#city').val(json.municipio);
        $('#neighborhood').val(json.bairro);
        $('#state').find('option').each(function(){
            if($(this).val()===json.uf){
                $(this).prop('selected',true);
            }
        });
    }

    function copyClientAddress(){
        $('input[name=representative_street]').val($('#street').val());  
        $('input[name=representative_number]').val($('#number').val()); 
        $('input[name=representative_neighborhood]').val($('#neighborhood').val()); 
        $('input[name=representative_city]').val($('#city').val()); 
        $('input[name=representative_complement]').val($('#complement').val()); 
        $('input[name=representative_cep]').val($('#cep').val()); 
        $('select[name=representative_state]').find('option').each(function(){
            if($(this).val()==$('#state').val()){
                $(this).prop('selected',true);
            }
        })
    }

})

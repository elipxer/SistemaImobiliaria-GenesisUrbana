$(()=>{
    $("input[name=cep]").mask("99999-999",{completed:function(){searchCep(this.val())}});
    function searchCep(cep){
        $.ajax({
            url:ADDRESS_URL,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'POST',
            dataType:'json',
            data:{'cep':cep},
            success:function(json){
                setAddressInputs(json.address);   
            },

            error:function(){
                alert("algo deu errado!");
            }
        });
    }

    function setAddressInputs(address){
        $('input[name=street]').val(address.logradouro);
        $('input[name=neighborhood]').val(address.bairro);
        $('input[name=city]').val(address.localidade);
        $('select[name=uf]').find('option').each(function(){
            if($(this).val()===address.uf){
                $(this).prop('selected',true);
            }
        });
    }

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

})
$(()=>{
    

    var line=$('.companyLinePorc'); 
    $('#companiesAreaTable').find('input[name=companyCheck]').each(function(){
        $(this).on("change",function(){
            setCompanies();
            verifyChangeValuesPerc();
        })
    })

    $('#btnInput').on('click',function(event){
        event.preventDefault();
        if(verifySelectedCompany()===false){
            Swal.fire({
                icon: 'error',
                text: 'Selecione pelo menos uma empresa!',
                customClass: 'mySweetalert',
            }) 
        }else if(verifyPorcCompanies()===false){
            Swal.fire({
                icon: 'error',
                text: 'As soma das porcentagens das empresas não está dando 100%!',
                customClass: 'mySweetalert',
            }) 
        }else{
            $('#formInterprise').trigger('submit');
        }
    });


    function verifyChangeValuesPerc(){
        $('.porcValue').each(function(){
            $(this).on('keyup',function(){
                if($(this).val()===""){
                    $(this).val(0);
                }
                let companies_porc=[];
                $('#card_porc').find('.companyLinePorc').each(function(){
                    let valuePorc=parseFloat($(this).find('.porcValue').val());
                    let idCompany=$(this).find('.idCompany').val();
                    let companiesPorc=idCompany+"-"+valuePorc;
                    companies_porc.push(companiesPorc);
                });
        
                $('#id_companies_porc').val(companies_porc);
            
            })
        });
    }   

    function setCompanies(){
        let companiesChoise=[];
        let companiesIds=[];
        $('#companiesAreaTable').find('input[name=companyCheck]').each(function(){
            if($(this).prop('checked')){
                let idCompany=$(this).val();
                let name=$(this).closest('tr').find('td').eq(1).html();
                let cnpj=$(this).closest('tr').find('td').eq(2).html();
                let representative_cpf=$(this).closest('tr').find('td').eq(3).html();
                
                let company={
                    id:idCompany,
                    name:name,
                    cnpj:cnpj,
                    representative_cpf:representative_cpf
                }
                
                companiesChoise.push(company);
                companiesIds.push(company.id);
            }
        })
        
        $('#id_companies').val(companiesIds);
        
        $('#card_porc').empty();
        companiesChoise.forEach(element => {
            let linePorc=line.clone();
            $(linePorc).css('display','flex');
            let nameCpfCnpj="Nome: "+element.name+" Cpnj"+element.cnpj;
            $(linePorc).find('.nameCompany').html(nameCpfCnpj);
            $(linePorc).find('.idCompany').val(element.id);
            $('#card_porc').append(linePorc);
        });

        calcPorcClient();
    }

    function calcPorcClient(){
        let numberClients=$('#card_porc').find('.companyLinePorc').length;
        let valuePorc=100/parseInt(numberClients);
        $('#card_porc').find('.companyLinePorc').each(function(){
            $(this).find('.porcValue').val(valuePorc);
        });

        let companies_porc=[];
        $('#card_porc').find('.companyLinePorc').each(function(){
            let valuePorc=parseFloat($(this).find('.porcValue').val());
            let idCompany=$(this).find('.idCompany').val();
            let companiesPorc=idCompany+"-"+valuePorc;
            companies_porc.push(companiesPorc);
        });

        $('#id_companies_porc').val(companies_porc);
    }

    function verifyPorcCompanies(){
        let total=0;
        $('#card_porc').find('.companyLinePorc').each(function(){
            let valuePorc=parseFloat($(this).find('.porcValue').val());
            total=total+valuePorc;
        });

        if(total==100){
            return true;
        }else{
            return false;
        }
    }
    
    function verifySelectedCompany(){
        let companyCheck=false;
        $('#companiesAreaTable').find('input[name=companyCheck]').each(function(){
            if($(this).prop('checked')){
                companyCheck=true;
            }
        })
        
        return companyCheck;
    }   


})
$(()=>{
    $('.money').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
   

    $('.cep').mask('99999-999',{autoclear: true});
    $('.cep-not-autoClear').mask('99999-999',{autoclear: false});
    $('.cpf').mask('999.999.999-99',{autoclear: true});
    $('.cpf-not-autoClear').mask('999.999.999-99',{autoclear: false});
    $('.cnpj').mask('99.999.999/9999-99',{autoclear: true});
    $('.cnpj-not-autoClear').mask('99.999.999/9999-99',{autoclear: false});
    $('.phone').mask("(99)99999-9999",{autoclear: true})
    $('.phone-not-autoClear').mask("(99)99999-9999",{autoclear: false})
})
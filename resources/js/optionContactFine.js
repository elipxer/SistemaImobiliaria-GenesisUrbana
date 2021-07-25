$(()=>{
    
    $('.inputOptionFineBtn').on('click',function(event){
        event.preventDefault();
        if(veryfyInputsNotEmpty()){
            if(veryfyInputsEmpty()){
                Swal.fire({
                    icon: 'error',
                    text: 'Para inserir uma taxa a esse contrato, preencha os quatro campos do administrativo!!',
                    customClass: 'mySweetalert',
                })   
            }else{
                $('.optionFineForm').trigger('submit');
            }
        }else{
            $('.optionFineForm').trigger('submit');
        }
    })

    function veryfyInputsNotEmpty(){
        let fine_contact=$('#fine_contact').val();
        let prefix=$('#prefix_parcel_contact').val();
        let numberParcels=$('#number_parcel_contact').val();
        let expiration_fine=$('#expiration_fine_contact').val();

        if(fine_contact!="" || prefix!="" || numberParcels!="" || expiration_fine != ""){
            return true;
        }else{
            return false;
        }
    }

    
    function veryfyInputsEmpty(){
        let fine_contact=$('#fine_contact').val();
        let prefix=$('#prefix_parcel_contact').val();
        let numberParcels=$('#number_parcel_contact').val();
        let expiration_fine=$('#expiration_fine_contact').val();

        if(fine_contact=="" || prefix=="" || numberParcels=="" || expiration_fine==""){
            return true;
        }else{
            return false;
        }
    }
})
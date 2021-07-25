$(()=>{

    $('.uploadInput').on('change',function(e){
        $('#btnFileContact').attr('disabled',false);
    });

    if($('#card-cancel').length >0){
        $('#numberParcel').on('keyup',function(){
            $('#card-finish-cancel').find('input[name=number_parcels_return]').val($(this).val());
            $('#card-finish-cancel').find('input[name=return_value]').val($('#return_value').val());
            $('#value_parcel_return').html(calcValueReturn($(this).val()))
            $('#card-finish-cancel').find('input[name=value_parcel_return]').val(calcValueReturn($(this).val()));
        })

        $('#return_value').on('keyup',function(){
            $('#value_parcel_return').html(calcValueReturn($('#numberParcel').val()))
            $('#card-finish-cancel').find('input[name=number_parcels_return]').val($('#numberParcel').val());
            $('#card-finish-cancel').find('input[name=value_parcel_return]').val(calcValueReturn($('#numberParcel').val()));
        })

        $('#btnFileContact').on('click',function(event){
            let value_parcel_return=$('#card-finish-cancel').find('input[name=value_parcel_return]').val();
            let number_parcels_return=$('#card-finish-cancel').find('input[name=number_parcels_return]').val();
            let return_value=$('#card-finish-cancel').find('input[name=return_value]').val($(this).val());
            
            event.preventDefault();
            Swal.fire({
                title: "Ao inserir o documento o cancelamento do contrato vai ser efetivado. Deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:'Cancelar',
                confirmButtonText: 'Sim',
                customClass: 'mySweetalert',
    
                }).then((result) => {
                    if (result.value) {
                        if(value_parcel_return=="" || number_parcels_return=="" || return_value==""){
                            Swal.fire({
                                icon: 'error',
                                text: 'Há campos que são editaveis que foram deixados em branco!',
                                customClass: 'mySweetalert',
                            })
                        }else{
                            $('#cancelSale').trigger('submit');
                        }               
                    }
                })
        });
    }

    if($('#card_refinancing').length > 0){
        $('#btnRefinancing').on('click',function(event){
            event.preventDefault();
            Swal.fire({
                title: "Ao confirmar, as taxas do refinanciamento serão consideradas pagas. Deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:'Cancelar',
                confirmButtonText: 'Sim',
                customClass: 'mySweetalert',

                }).then((result) => {
                    if (result.value) {
                        $('#refinancingContact').trigger('submit');
                    }
            })
        })
    }

    if($('#card-finish-changeOwner').length > 0){
        $('#btnFileContact').on('click',function(event){
            event.preventDefault();
            Swal.fire({
                title: "Ao inserir a cessão com as assinaturas a troca de proprietário vai ser efetivado. Deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:'Cancelar',
                confirmButtonText: 'Sim',
                customClass: 'mySweetalert',

                }).then((result) => {
                    if (result.value) {
                        $('#changeOwnerForm').trigger('submit');
                    }
            })
        })
    }

    if($('#card-finish-change-lot').length > 0){
        $('#btnFileContact').on('click',function(event){
            event.preventDefault();
            Swal.fire({
                title: "Ao inserir o documento com assinaturas a troca de lote vai ser efetivada. Deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:'Cancelar',
                confirmButtonText: 'Sim',
                customClass: 'mySweetalert',

                }).then((result) => {
                    if (result.value) {
                        $('#changeOwnerForm').trigger('submit');
                    }
            })
        })
    }

    if($('#card_several').length > 0){
        $('#btnSeveral').on('click',function(event){
            event.preventDefault();
            Swal.fire({
                title: "Ao confirmar, as taxas desse contato vão ser consideradas pagas e o contato como resolvido. Deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:'Cancelar',
                confirmButtonText: 'Sim',
                customClass: 'mySweetalert',

                }).then((result) => {
                    if (result.value) {
                        $('#severalContact').trigger('submit');
                    }
            })
        })
    }

    
    if($('#card_several-solution').length > 0){
        $('#btnSeveralSolution').on('click',function(event){
            event.preventDefault();
            Swal.fire({
                title: "Ao confirmar, se as possiveis taxas foram pagas, o contato vai ficar como resolvido. Deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:'Cancelar',
                confirmButtonText: 'Sim',
                customClass: 'mySweetalert',

                }).then((result) => {
                    if (result.value) {
                        $('#severalContactSolution').trigger('submit');
                    }
            })
        })
    }

   
    if($('#card_reissue').length > 0){
        $('#btnReissue').on('click',function(event){
            event.preventDefault();
            Swal.fire({
                title: "Ao confirmar, as parcelas selecionadas serão reemitidas. Deseja continuar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText:'Cancelar',
                confirmButtonText: 'Sim',
                customClass: 'mySweetalert',

                }).then((result) => {
                    $('#reissueContact').trigger('submit');
            })
        }) 
    }

    function calcValueReturn(numberParcels){
        numParcels=parseInt(numberParcels);
        let value_return=moneyfloat($('#return_value').val());
        let value_parcel_return=value_return/numberParcels;

        return moneyString(value_parcel_return);
    }

    function moneyfloat(money){
        money = money.replace("R$","");    
        money = money.replace(".","");
        money = money.replace(",",".");
        
        return parseFloat(money).toFixed(2);
      
     }

     function moneyString(money){
        money=money.toFixed(2);
        money = money.replace(".",",");
        return money;
     }
})
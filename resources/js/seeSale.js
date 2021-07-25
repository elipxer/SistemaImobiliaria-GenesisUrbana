$(function(){

    var payParcelForm= $('#payParcelForm');
    $('.btnPayParcel').each(function(){
        $(this).on('click',function(){
            let idParcel=$(this).attr('idParcel');
            let value=$(this).attr('value');
            $('#valueParcel').html(value);
            $('#pad_value').val(value);
            $('#idParcel').val(idParcel);
            $(payParcelForm).css('display','block');
            $('#btnAddModal').off();
            $('#btnEditModal').off();
            $("#modalAcoes").find(".modal-body").empty();
            $("#modalAcoes").find(".modal-body").append($(payParcelForm));
            $("#btnEditModal").hide();
            $("#btnAddModal").hide();
            $("#modalAcoes").find(".modal-title").html('Pagar Parcela');
            
            $('#btnPayParcel').on('click',function(event){
                event.preventDefault();
                if(verifyPayParcel()){
                    $(payParcelForm).trigger('submit');
                }
            })
        })
    });

    function verifyPayParcel() {
        let verify=true;

        let paymentDate=$('#payParcelForm').find('input[name=pad_date]').val();
        let pad_description=$('#payParcelForm').find('textarea[name=pad_description]').val();
        let pad_value=$('#payParcelForm').find('input[name=pad_value]').val();
        let checkBanks=()=>{
            let isChecked=false;
            $('#payParcelForm').find('.idBank').each(function(){
                if($(this).prop('checked')){
                    isChecked=true;
                }
            })
            return isChecked;
        }

        if(paymentDate==="" || pad_description==="" || pad_value===""){
            Swal.fire({
                icon: 'error',
                text: 'Preencha os valores obrigatórios!',
                customClass: 'mySweetalert',
            })   
            verify=false;
        
        }else if(checkBanks()===false){
            Swal.fire({
                icon: 'error',
                text: 'Escolha algum banco!',
                customClass: 'mySweetalert',
            })   
            verify=false;
        }

        return verify;
    }

    $('.btnSeeMoreClient').each(function(){ 
        $(this).on('click',function(){
            $(this).closest('.card').find('.card-body').slideToggle();
        })
    })
    
    $('.uploadInput').on('change',function(e){
        $('#btnFileContract').attr('disabled',false);
    });

    $('.uploadInput').on('change',function(e){
        $('#btnAlmostFinishSale').attr('disabled',false);
    });

    $('.uploadInput').on('change',function(e){
        $('#btnFinishSaleFile').attr('disabled',false);
    });

    $('#btnAlmostFinishSale').on('click',function(e){
        e.preventDefault();
        Swal.fire({
            title: "Ao inserir o termo de quitação, a venda passará a ser considerada quitada. Deseja continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonText:'Cancelar',
            confirmButtonText: 'Sim',
            customClass: 'mySweetalert',

            }).then((result) => {
                if (result.value) {
                    $("#almostFinishSaleForm").trigger('submit');             
                }
            })
    })

    $('#btnFinishSaleFile').on('click',function(e){
        e.preventDefault();
        Swal.fire({
            title: "Ao inserir a escritura, a venda passará a ser considerada finalizada. Deseja continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonText:'Cancelar',
            confirmButtonText: 'Sim',
            customClass: 'mySweetalert',

            }).then((result) => {
                if (result.value) {
                    $("#almostFinishSaleForm").trigger('submit');             
                }
            })
    })

    $('#btnFileContract').on('click',function(e){
        e.preventDefault();
        Swal.fire({
            title: "Ao inserir o contrato, a venda passará a ser ativa. Deseja continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonText:'Cancelar',
            confirmButtonText: 'Sim',
            customClass: 'mySweetalert',

            }).then((result) => {
                if (result.value) {
                    $("#contractFinishForm").trigger('submit');             
                }
            })
    })
})



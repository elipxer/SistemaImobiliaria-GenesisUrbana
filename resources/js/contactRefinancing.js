$(()=>{
    if(refinancing_failed==false){
        let totalValueFine=calcFineValue();
        $('input[name=fine_total]').val(totalValueFine);
        $('#value_parcel').val(calcParcels());

        $('input[name=fine_contact]').on('keyup',function(){
            $('input[name=fine_total]').val("");
            let totalValue=calcFineValue();
            $('input[name=fine_total]').val(totalValue);
        })

        $('input[name=value]').on('keyup',function(){
            $('#value_parcel').val(calcParcels());
        })

        $('input[name=parcels]').on('keyup',function(){
            $('#value_parcel').val(calcParcels());
        })

        $('input[name=parcels]').on('change',function(){
            $('#value_parcel').val(calcParcels());
        })

        $('input[name=fine_contact]').on('keyup',function(){
            let val=$(this).val();
            if(val==""){
                $(this).val(0);
            }
        })

        $('#btnInput').on('click',function(){
            if(verifyEmptyInputs()){
                Swal.fire({
                    icon: 'error',
                    text: 'Preencha os campos obrigatórios nas informações de refinanciamento!',
                    customClass: 'mySweetalert',
                }) 
            }

        })

        function verifyEmptyInputs(){
            let verify=false;
            let expiration_day=$('#expiration_day').val();
            let value=$('#value').val();
            let parcels=$('#parcels').val();

            if(expiration_day==="" || value==="" || parcels===""){
                verify=true;
            }

            return verify;
        }
    }else{
        $(()=>{
            var formIndexEmpty=$('.formIndexEmpty');
            var tableIndexLine=$('#tableIndexEmpty').find('.tableIndexEmpty_line');
            
            $('.btnIndexEmpty').each(function(){
                $(this).on('click',function(){
                    let allIndexEmpty=$(this).attr('month_index_empty');
                    let idIndex=$(this).attr('idIndex');
                    let idSale=$(this).attr('idSale');
                    $('.formIndexEmpty').find('input[name=idSale]').val(idSale);
                    $('.formIndexEmpty').find('input[name=idIndex]').val(idIndex);
                    allIndexEmpty=allIndexEmpty.split(',');
                    fillTableIndex(allIndexEmpty);
                    $(formIndexEmpty).show();
                    $('#btnAddModal').off();
                    $('#btnEditModal').off();
                    $("#modalAcoes").find(".modal-body").empty();
                    $("#modalAcoes").find(".modal-body").append(formIndexEmpty);
                    $("#modalAcoes").find(".modal-footer").css('justify-content','center');
                    $("#btnEditModal").show();
                    $("#btnEditModal").addClass('w-50');
                    $("#btnAddModal").hide();
                    $("#modalAcoes").find(".modal-title").html('Indices não cadastrados para o reajuste');
                    $('#btnEditModal').on('click',function(event){
                        $('.formIndexEmpty').trigger('submit');
                    
                    });
                })
            })
        
            function fillTableIndex(allIndexEmpty){
                $('#tableIndexEmpty').find('tbody').empty();
               
                for (const key in allIndexEmpty) {
                    let lineTable=$(tableIndexLine).clone();
                    $(lineTable).css('display','table-row');
                    $(lineTable).find('td').eq(1).find('input').val(allIndexEmpty[key]);
                    $('#tableIndexEmpty').find('tbody').append(lineTable);
                }
            }
        })
    }
    function calcFineValue(){
        let valueFine=$('input[name=fine_total]').attr('fine_value');
        let fine_contact=$('input[name=fine_contact]').val();
        let totalValue=valueFine*fine_contact/100;
        
        return totalValue.toFixed(2);
    }

    function calcParcels(){
        let value=moneyfloat($('input[name=value]').val());
        let numberParcel= parseInt($('input[name=parcels]').val());
        let valueParcels=value;
        
        if($('input[name=parcels]').val() != ""){
            valueParcels=value/numberParcel;
        }
        return valueParcels.toFixed(2);
    }

    function moneyfloat(money){
        money = money.replace("R$","");    
        money = money.replace(".","");
        money = money.replace(",",".");
        
        return parseFloat(money);
    }

    function moneyString(money){
        money=money.toFixed(2);
        money = money.replace(".",",");
        return money;
     }
})
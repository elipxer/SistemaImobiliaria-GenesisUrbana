$(()=>{
    $('#total_parcels_pad').trigger('focus');
    $('#total_parcels_pad').trigger('blur');

    if($('#total_parcels_pad').val()==""){
        $('#total_parcels_pad').val("0,00");
    }
    
    $('#table_interprise').find('input[name=interpriseCheck]').each(function(){
        $(this).on('change',function(){
            if($(this).prop('checked')){
                $('#id_interprise').val($(this).val());
                let idLotCheck=$(this).next('input[name=lotCheck]').val();
                let value_lot=$(this).closest('tr').find('input[name=lot_value]').val();
                $('#id_lot').val(idLotCheck);

                $('#value_lot_selected').val(value_lot);
                $('#id_lot').val(idLotCheck);
                let interprise=$(this).closest('tr').find('td').eq(1).html();
                let lot_number=$(this).closest('tr').find('td').eq(2).html();
                let lot_block=$(this).closest('tr').find('td').eq(3).html();
                let official_lot_name=interprise+" - Quadra "+lot_block+" Lot "+lot_number;
                $('#lot_selected').val(official_lot_name);
                calcNewValuePay();
                calcNewParcelValue();
                disabledInputValues();
            }
        })
    });

    $('#value_after_change').on('keyup',function(){
        calcParcelChange();
    })

    $('#number_parcel_change_lot').on('keyup',function(){
        calcParcelChange();
    })

    $('#number_parcel_change_lot').on('change',function(){
        calcParcelChange();
    })

    $('#rate_financing').on('keyup',function(){
        calcNewParcelValue();
    })

    $('#btnInput').on('click',function(event){
        event.preventDefault();
         
        if(verifySelectedLot()==false){
            Swal.fire({
                icon: 'error',
                text: 'Selecione um lot para fazer a troca!',
                customClass: 'mySweetalert',
            })
        }else if(verifyEmptyInputs()){
            Swal.fire({
                icon: 'error',
                text: 'Os campos do administrativos são obrigatórios!',
                customClass: 'mySweetalert',
            })
         
        }else{
            $('#addChangeLotContact').trigger('submit');
        }
    })

    function verifyEmptyInputs(){
        let verify=false;
        
        let value_after_change=$('#value_after_change').val();
        let rate_financing=$('#rate_financing').val();
        let number_parcel_change_lot=$('#number_parcel_change_lot').val();
        let first_parcel=$('#first_parcel').val();

        if(value_after_change==="" || rate_financing==="" 
            || number_parcel_change_lot==="" || first_parcel===""){
                verify=true;
        }

        return verify;
    }

    function disabledInputValues(){
        $('#number_parcel_change_lot').attr('disabled',false);
        $('#value_after_change').attr('disabled',false);
        $('#rate_financing').attr('disabled',false);
    }

    function calcParcelChange(){
        let value_change=moneyfloat($('#value_after_change').val());
        let number_parcel_change=parseInt($('#number_parcel_change_lot').val());
        let parcel=value_change;
        
        if($('#number_parcel_change_lot').val()!=""){
            parcel=value_change/number_parcel_change;
        }
        $('#value_parcel_change_lot').val(moneyString(parcel));
    }

    function calcNewValuePay(){
        let value_selected_lot=moneyfloat($('#value_lot_selected').val());
        let total_parcels_pad=0;
        
        if($('#total_parcels_pad').val() != ""){
            total_parcels_pad=moneyfloat($('#total_parcels_pad').val());
        }
        let new_value=value_selected_lot-total_parcels_pad;
        $('#new_value_pay').val(moneyString(new_value));
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

    function calcNewParcelValue(){
        let new_value_pay=moneyfloat($('#new_value_pay').val());
        let rate_financing=$('#rate_financing').val();
        
        if($('#rate_financing').val()=="" || $('#rate_financing').val()==0){
            rate_financing=0.6;    
        }
        rate_financing=rate_financing/100;
        let deadline_change_lot=parseInt($('#number_parcel_change_lot').val());
        
        let first_calc=(Math.pow(1+rate_financing, deadline_change_lot).toFixed(5))*rate_financing;
        let second_calc=(Math.pow(1+rate_financing, deadline_change_lot).toFixed(5))-1;
        let new_parcel=(new_value_pay*(first_calc/second_calc));
        let value_after_change=new_parcel*deadline_change_lot;
        $('#value_after_change').val(moneyString(value_after_change)); 
        $('#value_parcel_change_lot').val(moneyString(new_parcel));
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

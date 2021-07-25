$(()=>{
    let iptu_debits=moneyfloat($('#iptu_debits').val());
    let others_debits=moneyfloat($('#others_debits').val());
    getValueReturn(others_debits,iptu_debits);
    
    $('.money').each(function(){
        $(this).trigger("focus");
        $(this).trigger("blur");
    });

    $('#others_debits').val('0,00');
    $('#iptu_debits').val('0,00')
    $('#future_value').eq(0).trigger('focus');

    $('#iptu_debits').on('keyup',function(){
        let iptu_debits=moneyfloat($(this).val());
        let others_debits=moneyfloat($('#others_debits').val());
        
        getValueReturn(others_debits,iptu_debits);
    })

    $('#others_debits').on('keyup',function(){
        let others_debits=moneyfloat($(this).val());
        let iptu_debits=moneyfloat($('#others_debits').val());
        
        getValueReturn(others_debits,iptu_debits);
    })

    $('#sale_commission_rate').on('keyup',function(){
        let sale_commission_rate=$(this).val();
        let future_value=moneyfloat($('#future_value').val());
        let sale_commission=future_value*sale_commission_rate/100;
        $('input[name=sale_commission]').val(moneyString(sale_commission));
        let sumIndexRate=$('#sumIndexRate').val();
        let valueReadjust=sale_commission*sumIndexRate/100;
        let sale_commission_adjusted=sale_commission+valueReadjust;
        $('#sale_commission_adjusted').val(moneyString(sale_commission_adjusted));
    })
    
    $('#administrative_expenses').on('keyup',function(){
        let administrative_expenses=$(this).val();
        if(administrative_expenses===""){
            administrative_expenses=10;
        }
        let total_parcels_pad=moneyfloat($('#total_parcels_pad').val()); 
        let administrative_debits=total_parcels_pad*administrative_expenses/100;
        $('#administrative_debits').val(moneyString(administrative_debits));
    })

    $('#number_parcels_return').on('keyup',function(){
        $('#value_parcel_return').val(calcValueReturn($(this).val()))
    })
    
    

    function calcValueReturn(numberParcels){
        numParcels=parseInt(numberParcels);
        let value_return=moneyfloat($('#return_value').val());
        let value_parcel_return=value_return/numberParcels;

        return moneyString(value_parcel_return);
    }
    function getValueReturn(others_debits,iptu_debits){
        let total_parcels_pad=moneyfloat($('#total_parcels_pad').val());
        let sale_commission_adjusted=moneyfloat($('#sale_commission_adjusted').val());
        let administrative_debits=moneyfloat($('#administrative_debits').val());
        let return_value=total_parcels_pad-sale_commission_adjusted-administrative_debits-iptu_debits-others_debits;
        if(return_value<=0){
            $('#card_administrative').css('display','none');
            $('#alert').css('display','block');
        }else{
            $('#card_administrative').css('display','flex');
            $('#alert').css('display','none');
            getReturnValue(return_value);
        }
        return return_value;
    }

    function getReturnValue(return_value){
        let numberParcels=parseInt($('#number_parcels_return').val());
        let value_parcel_return=0;
        
        if(numberParcels>0){
            value_parcel_return=return_value/numberParcels;
        }else{
            value_parcel_return=return_value;
            numberParcels=1;
        }
        
        $('#return_value').val(moneyString(return_value));
        $('#value_parcel_return').val(moneyString(value_parcel_return));
        $('#number_parcels_return').val(numberParcels);
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
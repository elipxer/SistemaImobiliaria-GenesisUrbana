$(()=>{
    var parcelsCheck=[];
    $('#table_parcels').find('tr').find('.checkParcel').each(function(){
        $(this).on('change',function(){
            let valParcel=$(this).closest('tr').find('.updated_value').html();
            let numberParcel=$(this).closest('tr').find('.numberParcel').html()
            let idParcel=$(this).attr('id');
            if($(this).prop('checked')){
                let parcelCheck={
                    id:idParcel,
                    number:numberParcel,
                    valParcel:valParcel
                }
                parcelsCheck.push(parcelCheck);
            }else{
                let index=returnIndexParcelCheck(idParcel);
                parcelsCheck.splice(index,1);
            }
            getValuesReissue();
            $('#parcels_selected').val(getIdsParcelsSelected());
            
            if(parcelsCheck.length>0){
                $('#btnInput').attr('disabled',false);
            }else{
                $('#btnInput').attr('disabled',true);
            }
        })
    });

    function getValuesReissue(){
        $('#parcel_late_sum').val(moneyString(sumValuesParcel()));
        let numberParcelsCheck=parcelsCheck.length;
        let rate_reissue=(numberParcelsCheck*6)+20;
        $('#rate_reissue').val(moneyString(rate_reissue));
        let total_reissue=rate_reissue+sumValuesParcel();
        $('#total_reissue').val(moneyString(total_reissue));
    }

    function sumValuesParcel(){
        let totalParcels=0;
        parcelsCheck.forEach(function(item,key){
            let valParcel=moneyfloat(item.valParcel);
            totalParcels=totalParcels+valParcel;
        });            
        
        return totalParcels;
    }

    function getIdsParcelsSelected(){
        let idsParcels=[];
        parcelsCheck.forEach(function(item,key){
            let idParcel=item.id;
            idsParcels.push(idParcel);
        });
        
        return idsParcels;
    }

    function returnIndexParcelCheck(idParcel){
        let index=0;
        parcelsCheck.forEach(function(item,key){
            if(item.id==idParcel){
                index=key;
            }
        });            

        return index;
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
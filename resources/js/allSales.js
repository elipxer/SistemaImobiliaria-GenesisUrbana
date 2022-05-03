$(()=>{
    $('.btnOrderBy').each(function(){
        $(this).on('click',function(e){
            e.preventDefault();
            let typeOrder=$(this).attr('order');
            changeStatus(typeOrder);
        })
    });

    function changeStatus(typeOrder) {
        let value="";
        let element="";
        
        if(typeOrder==="1"){
            value=parseInt($('input[name=orderContract]').val());
            element=$('input[name=orderContract]');
        
        }else if(typeOrder==="2"){
            value=parseInt($('input[name=orderLot]').val());
            element=$('input[name=orderLot]');
        
        }else if(typeOrder==="3"){
            value=parseInt($('input[name=orderBlock]').val());
            element=$('input[name=orderBlock]');
        
        }else if(typeOrder==="4"){
            value=parseInt($('input[name=orderInterprise]').val());
            element=$('input[name=orderInterprise]');
        }
        
        if(value === 2){
            value=0;
        }else{
            value+=1;
        }
        
        element.val(value);
        $('.formOrder').submit();
    
    }
})
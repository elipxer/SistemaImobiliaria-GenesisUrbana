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
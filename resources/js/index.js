$(()=>{
    let formIndex=$('#formIndex');
    let formIndexEdit=$('#formIndexEdit');
    
    $('#btnAddIndex').on('click',function(){    
        $(formIndex).show();
        $('#btnAddModal').off();
        $('#btnEditModal').off();
        $("#modalAcoes").find(".modal-body").empty();
        $("#modalAcoes").find(".modal-body").append(formIndex);
        $("#btnEditModal").hide();
        $("#btnAddModal").show();
        $("#modalAcoes").find(".modal-title").html('Adicionar Indice');
        $('#btnAddModal').on('click',function(event){
            if($('#formIndex').find('input').val() != ""){
                $('#formIndex').trigger('submit');
            }else{
                Swal.fire({
                icon: 'error',
                text: 'Digite o nome do indice!',
                customClass: 'mySweetalert',
            })   
        }
        });
    });

    $('.btnEditIndex').each(function(){
        $(this).on('click',function(e){
            e.preventDefault();
            let idIndex=$(this).attr('id');
            let nameIndex=$(this).closest('tr').find('td').html();
            $(formIndexEdit).find('input[name=indexName]').val(nameIndex);
            $(formIndexEdit).find('input[name=idIndex]').val(idIndex);

            $(formIndexEdit).show();
            $('#btnAddModal').off();
            $('#btnEditModal').off();
            $("#modalAcoes").find(".modal-body").empty();
            $("#modalAcoes").find(".modal-body").append(formIndexEdit);
            $("#btnEditModal").show();
            $("#btnAddModal").hide();
            $("#modalAcoes").find(".modal-title").html('Editar Indice');
            $('#btnEditModal').on('click',function(event){
                if($('#formIndexEdit').find('input').val() != ""){
                    $('#formIndexEdit').trigger('submit');
                }else{
                    Swal.fire({
                    icon: 'error',
                    text: 'Digite o nome do indice!',
                    customClass: 'mySweetalert',
                })   
            }
            });
        })
    })

    $('#btnAddValueIndex').on('click',function(){    
        $(formIndex).show();
        $('#btnAddModal').off();
        $('#btnEditModal').off();
        $("#modalAcoes").find(".modal-body").empty();
        $("#modalAcoes").find(".modal-body").append(formIndex);
        $("#btnEditModal").hide();
        $("#btnAddModal").show();
        $("#modalAcoes").find(".modal-title").html('Adicionar Valor ao Indice');
        $('#btnAddModal').on('click',function(event){
            let valueIndex=$('#formIndex').find('input[name=index_value]').val();
            let dateIndex=$('#formIndex').find('input[name=index_month]').val();
            if(valueIndex != "" && dateIndex != ""){
                $('#formIndex').trigger('submit');
            }else{
                Swal.fire({
                icon: 'error',
                text: 'Preencha os campos obrigatórios!',
                customClass: 'mySweetalert',
            })   
        }
        });
    });

    $('.btnEditIndexValue').each(function(){
        $(this).on('click',function(e){
            e.preventDefault();
            let idValueIndex=$(this).attr('id');
            let indexName=$(this).closest('tr').find('td').eq(0).html();
            let indexValue=$(this).closest('tr').find('td').eq(1).html();
            let indexDate=$(this).closest('tr').find('td').eq(2).html();
            
            let indexMonth=indexDate.substring(3,5);
            let indexYear=indexDate.substring(6);
            
            indexDate=indexYear+'-'+indexMonth;

            if($(formIndexEdit).find('.indexNameInput').length>0){
                $(formIndexEdit).find('.indexNameInput').html(indexName);
            }else{
                $(formIndexEdit).find('select').find('option').each(function(){
                    let valueSelect=$(this).html();
                    
                    if(valueSelect.trim()===indexName){
                        $(this).prop('selected',true);
                    }
                })
            }
            
            $(formIndexEdit).find('input[name=index_value]').val(indexValue);
            $(formIndexEdit).find('input[name=idIndexValue]').val(idValueIndex);
            $(formIndexEdit).find('input[name=index_month]').val(indexDate);

            $(formIndexEdit).show();
            $('#btnAddModal').off();
            $('#btnEditModal').off();
            $("#modalAcoes").find(".modal-body").empty();
            $("#modalAcoes").find(".modal-body").append(formIndexEdit);
            $("#btnEditModal").show();
            $("#btnAddModal").hide();
            $("#modalAcoes").find(".modal-title").html('Editar Valor Indice');
            $('#btnEditModal').on('click',function(event){
                if($('#formIndexEdit').find('input').val() != ""){
                    $('#formIndexEdit').trigger('submit');
                }else{
                    Swal.fire({
                    icon: 'error',
                    text: 'Digite o nome do indice!',
                    customClass: 'mySweetalert',
                })   
            }
            });
        })
    })

});
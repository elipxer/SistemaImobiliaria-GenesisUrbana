$(()=>{
    var clientSearchArea=$('#clientSearchArea');
    var lineClientTable=$('.clientLineTable');
    var clientSale=[];
    var allClients=[];
    var lineOwner=$('#tableOwner').find('.tableOwneLine').eq(0);

    getClientsBySale();
    
    $('#btnAddClient').on('click',function(){    
        $(clientSearchArea).show();
        $('#btnAddModal').off();
        $('#btnEditModal').off();
        $("#modalAcoes").find(".modal-body").empty();
        $("#modalAcoes").find(".modal-body").append(clientSearchArea);
        $("#btnEditModal").hide();
        $("#btnAddModal").show();
        $("#modalAcoes").find('.modal-dialog').css('max-width',700);
        $("#modalAcoes").find(".modal-title").html('Adicionar Cliente a Venda');
        $('#btnAddModal').on('click',function(event){
            addNewsClients();
           $("#modalAcoes").hide();
           $('#close').trigger('click');
           $('#clientAreaTable').find('tbody').empty();

         });

         $('#clientName').val("");
         $('#clientName').on('keyup',function(){
            let clientVal=$(this).val();
            if(clientVal != ""){
                searchClients(clientVal);
            }else{
                $('#clientAreaTable').find('tbody').empty();
            }
        })
    });

    $('#tableOwner').find('.tableOwneLine').find('.btnDeleteClient').each(function(){
        $(this).on('click',function(){
            let idClient=$(this).attr('id');
            allClients=allClients.concat(clientSale);
            
            let index=allClients.findIndex((item)=>{
                if(parseInt(item.idClient)==idClient){
                    return true;
                }else{
                    return false;
                }
            })
            
            allClients.splice(index,1);
            
            addNewsClients();
            $('#clients').val(allClients.map(function(item){
                return item.idClient;
            }));
            
        })
    });

      
    function searchClients(clientVal){
        $.ajax({
            url:CLIENT_URL,
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'JSON',
            data:{'clientVal':clientVal},
            success:function(json){
                fillTableClient(json.clients);
            }
        })
    }

    function getClientsBySale(){
        $('#tableOwner').find('.tableOwneLine').each(function(){
            let idClient=$(this).attr('id');
            let nameChecked=$(this).find('td').eq(0).html();
            let cpfCnpjChecked=$(this).find('td').eq(1).html();
            
            var clientSaleObject={
                idClient:idClient,
                name:nameChecked.trim(),
                cpfCnpj:cpfCnpjChecked.trim(),
            }

            allClients.push(clientSaleObject);
        });
    }
    
    function fillTableClient(clientJson){
        
        $('#clientAreaTable').find('tbody').empty();
        for (let key in clientJson) {
           
            let lineTable=$(lineClientTable).clone(true);
            $(lineTable).css('display','table-row');
            $(lineTable).find('td').eq(0).find('input').val(clientJson[key].id);
            let name=clientJson[key].name;
            if(clientJson[key].name === null){
                name=clientJson[key].company_name;
                
            }
            $(lineTable).find('td').eq(1).html(name);
            let cpfCnpj="";
            if(clientJson[key].cpf!=null){
                cpfCnpj=clientJson[key].cpf;
            }else{
                cpfCnpj=clientJson[key].cnpj;
            }

            $(lineTable).find('td').eq(2).html(cpfCnpj);
            $(lineTable).find('td').eq(3).html(clientJson[key].email);
            let indexClientSale=allClients.findIndex((item)=>{
                if(parseInt(item.idClient)==clientJson[key].id){
                    return true;
                }else{
                    return false;
                }
            })
            
            if (indexClientSale==-1) {
                $('#clientAreaTable').find('tbody').append(lineTable);
            }
        }

        $('#clientAreaTable').find('input[name=clientCheck]').each(function(){
            $(this).on('change',function(){
                if($(this).prop('checked')){
                    let idClient=$(this).val();
                    let nameChecked=$(this).closest('tr').find('td').eq(1).html();
                    let cpfCnpjChecked=$(this).closest('tr').find('td').eq(2).html();
                    
                    var checkClientObject={
                        idClient:idClient,
                        name:nameChecked,
                        cpfCnpj:cpfCnpjChecked,
                    }
                    allClients.push(checkClientObject);
                }
            })
        })
    }

    $('#btnInput').on('click',function(event){
        event.preventDefault();
        if(verifyPorcClient()===false){
            Swal.fire({
                icon: 'error',
                text: 'As soma das porcentagens do cliente não está dando 100%!',
                customClass: 'mySweetalert',
            })    
        }else{
            $('#changeOwnerForm').trigger('submit');
        }
    })
    
    function addNewsClients(){
        $('#tableOwner').find('tbody').empty();
        let idsClients=[];
        
        allClients.forEach(element => {
            let lineOwnerClone=$(lineOwner).clone();
            $(lineOwnerClone).prop('id',element.idClient);
            
            $(lineOwnerClone).find('td').eq(0).html(element.name);
            $(lineOwnerClone).find('td').eq(1).html(element.cpfCnpj);
            $('#tableOwner').find('tbody').append(lineOwnerClone);
            
            idsClients.push(element.idClient);
        });
        
        $('#clients').val(idsClients);
        addNewOptionsClientPayment();
        calcPorcClient();
    }

    function calcPorcClient(){
        let numberClients=allClients.length;
        let valuePorc=100/parseInt(numberClients);
        
        $('#tableOwner').find('tbody tr').each(function(){
            $(this).find('.porcValue').val(valuePorc);
        });

        let clients_porc=[];

        
        $('#tableOwner').find('tbody tr').each(function(){
            let valuePorc=parseFloat($(this).find('.porcValue').val());
            let idClient=$(this).attr('id');
            let clientPorc=idClient+"-"+valuePorc;
            
            clients_porc.push(clientPorc);
        });

        $('#id_clients_porc').val(clients_porc);
    }

    function addNewOptionsClientPayment(){
        $('#client_payment').empty();
        allClients.forEach(element => {
            let option="<option value='"+element.idClient+"'>"+'Nome: '+element.name+';      Cpf/Cpnj:  '+element.cpfCnpj+"</option>"
            if(element.idClient===parseInt($('#client_payment').val())){
                $(option).prop('selected',true);
            }
            $('#client_payment').append(option);
        });
    }
    
    function verifyPorcClient(){
        let total=0;
        $('#tableOwner').find('tbody tr').each(function(){
            let valuePorc=parseFloat($(this).find('.porcValue').val());
            total=total+valuePorc;
        });

        if(total==100){
            return true;
        }else{
            return false;
        }
    }
})
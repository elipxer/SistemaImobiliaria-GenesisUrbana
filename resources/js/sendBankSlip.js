$(()=>{
    var tableBankSlip=$('#tableBankSlip');
    var rowTableBankSlip=$('#tableBankSlip').find('tbody').find('tr');    

    $('.btnSeeBankSlip').each(function(){
        $(this).on('click',function(event){
            event.preventDefault();
            $(tableBankSlip).show();
            $('#btnAddModal').off();
            $('#btnEditModal').off();
            $("#modalAcoes").find(".modal-title").html('Boletos da Remessa');
            $("#modalAcoes").find(".modal-dialog").css('max-width','800px');
            $("#modalAcoes").find(".modal-body").empty();
            $("#modalAcoes").find(".modal-body").append(tableBankSlip);
            $("#btnEditModal").hide();
            $("#btnAddModal").hide();
            let idBankSlips=$(this).attr('idBankSlips');
            getBankSlip(idBankSlips);
        })
    })

    function getBankSlip(idBankSlips){
        $.ajax({
            url:urlBankSlip,
            method:"POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'JSON',
            data:{'idBankSlips':idBankSlips},
            success:function(json){
                fillTableBankSlip(json);   
            }

        })
    }

    function fillTableBankSlip(bankSlipJson){
        $(tableBankSlip).find('tbody').empty();
        for (const key in bankSlipJson) {
            let newRowTableBankSlip=$(rowTableBankSlip).clone(true);
            $(newRowTableBankSlip).find('td').eq(0).html(bankSlipJson[key].financialAccount.id===1?'Sicredi':'Caixa');
            $(newRowTableBankSlip).find('td').eq(1).html("<a href='"+urlSale+bankSlipJson[key].sale.id+"'>"+bankSlipJson[key].sale.contract_number+"</a>");
            $(newRowTableBankSlip).find('td').eq(2).html(bankSlipJson[key].parcel.num+"/"+bankSlipJson[key].sale.parcels);
            $(newRowTableBankSlip).find('td').eq(3).html("<a href='"+urlDownloadBankSlip+"/"+
                bankSlipJson[key].bankSlip.path+"' download='"+bankSlipJson[key].parcel.num+"/"+bankSlipJson[key].sale.parcels+".pdf'>Download</a>");
            $(tableBankSlip).find('tbody').append(newRowTableBankSlip);
        }
    }
   
})
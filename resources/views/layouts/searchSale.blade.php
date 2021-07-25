<div class="container_search_sale">
    <input url="{{route('seeSale',['idSale'=>'idSale'])}}" class="container_search_sale__input" id="container_search_sale__input" 
        type="text" placeholder="Numero Contrato" autofocus idSale="">    
</div>

<script>
    const CONTRACT_URL="{{route('verifyContractNumber')}}";
</script>


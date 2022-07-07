<template>
    <div class="container-fluid">
        <div class="heading">
            <h2 id="title">GFin (App.vue)</h2>
        </div>
        <transactions-list />
        <transaction-add />
    </div>
</template>

<script>
import TransactionsList from "./TransactionsList";
import TransactionAdd from "./TransactionAdd";
export default{
    components: {
        TransactionsList,
        TransactionAdd
    }
}
</script>

<style scoped>
.appContainer{
    width: 650px;
    margin: auto;
}
.heading{
    background: #e6e6e6;
    padding: 10px;
}
#title{
    text-align: center;
}
</style>
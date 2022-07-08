<template>
    <Sidebar />
    <TransactionsList />
    <TransactionAdd />
</template>

<script>
import TransactionsList from "./TransactionsList";
import TransactionAdd from "./TransactionAdd";
import Sidebar from '../components/Sidebar.vue';
export default{
    components: {
        TransactionsList,
        TransactionAdd,
        Sidebar
    }
}
</script>

<style scoped>
</style>
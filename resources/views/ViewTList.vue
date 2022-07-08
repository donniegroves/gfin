<template>
    <TransactionRow v-for="(tran, index) in transactions" 
        :key="index" 
        :date="tran.trans_date"
        :payee="payees[tran.payee_id] ?? ''"
        :desc="tran.orig_detail"
        :amt="tran.orig_amt"
    />
</template>
<script>
import TransactionRow from "../components/TransactionRow.vue";

export default{
    data: function(){
        return {
            transactions: {},
            payees: {}
        }
    },
    components: {
        TransactionRow,
    },
    methods: {
        getTransactions(){
            console.log('getTransactions');
            axios.get('api/transactions', {
            })
            .then ( response => {
                if( response.status == 200 ){
                    this.transactions = response.data;
                }
            })
            .catch( error => {
                console.log(error);
            })
        },
        getPayees(){
            console.log('getPayees');
            axios.get('api/payees', {
            })
            .then ( response => {
                if( response.status == 200 ){
                    this.payees = response.data;
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
    },
    created: function(){
        this.getTransactions();
        this.getPayees();
    }
}
</script>

<style scoped>
</style>
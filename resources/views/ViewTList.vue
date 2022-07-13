<template>
    <TransactionAdd 
        :all_payees_arr="this.all_payees_arr"
    />

    <!-- <TransactionRow v-for="(tran, index) in transactions" 
        :key="index" 
        :date="tran.trans_date"
        :desc="tran.orig_detail"
        :amt="tran.orig_amt"
    /> -->
    <!-- :payee="payee_name_hashmap[tran.payee_id] ?? ''" -->

</template>
<script>
import TransactionRow from "../components/TransactionRow.vue";
import TransactionAdd from "../components/TransactionAdd.vue";

export default{
    data: function(){
        return {
            transactions: {},
            all_payees_arr: [
                {
                    id: 0,
                    name: 'N/A'
                }
            ]
        }
    },
    components: {
        TransactionRow,
        TransactionAdd,
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
                    this.all_payees_arr = Object.values(response.data);
                    this.all_payees_arr.unshift({
                        id: 0,
                        name: 'N/A'
                    });
                }
            })
            .catch( error => {
                console.log(error);
            });
        }
    },
    created: function(){
        this.getTransactions();
        this.getPayees();
    }
}
</script>

<style scoped>
#topbar{
    height: 50px;
    border-bottom-style: solid;
    border-color: gray;
}
#trans-title-row{
    background: darkgray
}
#trans-title{
    color: black;
}
#trans-id-text{
    color: white;
}
.charge-section input{
    max-width: 100px;
    text-align: center;
}
</style>
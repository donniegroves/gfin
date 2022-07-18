<template>
    <h2>Add a Transaction:</h2>
    <TransactionAdd @transactionAdded="refreshView"/>
    <hr />
    <h2>Transactions:</h2>
    <TransactionRow v-for="(tran, index) in transactions" 
        :tran_id="tran.id" 
        :date="tran.trans_date"
        :payee_id="tran.payee_id"
        :desc="tran.new_detail == null ? tran.orig_detail : tran.new_detail"
        :amt="tran.new_amt == null ? tran.orig_amt : tran.new_amt"
        @transactionDeleted="refreshView"
    />

</template>
<script>
import {store} from '../js/store.js'
import TransactionRow from "../components/TransactionRow.vue";
import TransactionAdd from "../components/TransactionAdd.vue";

export default{
    data: function(){
        return {
            transactions: {},
            store
        }
    },
    components: {
        TransactionRow,
        TransactionAdd,
    },
    methods: {
        refreshView(){
            this.getTransactions();
        },
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
                    this.store.all_payees = Object.values(response.data);
                    this.store.all_payees.unshift({
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
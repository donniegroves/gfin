<template>
    <h2>Add a Transaction:</h2>
    <TransactionAdd
        :all_payees="all_payees"
        :all_categories="all_categories"
        @transactionAdded="refreshView"
    />
    <hr />
    <h2>Transactions:</h2>
    <TransactionRow v-for="(tran, index) in all_trans" 
        :transaction="tran"
        :all_payees="all_payees"
        :all_categories="all_categories"
        :key="tran.id"
        @editTransaction="refreshView"
        @transactionDeleted="refreshView"
    />

</template>
<script>
import axios from "axios";
import TransactionRow from "../components/TransactionRow.vue";
import TransactionAdd from "../components/TransactionAdd.vue";

export default{
    data(){
        return {
            all_trans: [],
            all_payees: [],
            all_categories: []
        }
    },
    created(){
        console.log('created ViewTList');
        this.refreshView();
    },
    components: {
        TransactionRow,
        TransactionAdd,
    },
    methods: {
        refreshView(){
            console.log('refreshView');
            this.refreshTransactions();
            this.refreshPayees();
            this.refreshCategories();
        },
        async refreshTransactions(){
            console.log('refreshTransactions');
            const response = await axios.get('api/transactions', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' transactions.');
                this.all_trans = response.data;
            }
        },
        async refreshPayees(){
            console.log('refreshPayees');
            const response = await axios.get('api/payees', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' payees.');
                this.all_payees = response.data;
            }
        },
        async refreshCategories(){
            console.log('refreshCategories');
            const response = await axios.get('api/categories', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' categories.');
                this.all_categories = response.data;
            }
        }
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
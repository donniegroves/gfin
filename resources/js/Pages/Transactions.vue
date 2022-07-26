<template>
    <GFinLayout>
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
            :selected_payee="filteredPayee(tran.payee_id)"
            :selected_category="filteredCategory(tran.category_id)"
            :selected_payee_name="filteredPayee(tran.payee_id)?.name"
            :selected_category_name="filteredCategory(tran.category_id)?.name"
            :key="tran.id"
            @editTransaction="refreshView"
            @transactionDeleted="refreshView"
        />
    </GFinLayout>
</template>

<script>
import axios from "axios";
import TransactionRow from "@/Components/TransactionRow.vue";
import TransactionAdd from "@/Components/TransactionAdd.vue";
import GFinLayout from "../Layouts/GFinLayout.vue";
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
    GFinLayout
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
            const response = await axios.get('reqs/transactions', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' transactions.');
                this.all_trans = response.data;
            }
        },
        async refreshPayees(){
            console.log('refreshPayees');
            const response = await axios.get('reqs/payees', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' payees.');
                this.all_payees = response.data;
            }
        },
        async refreshCategories(){
            console.log('refreshCategories');
            const response = await axios.get('reqs/categories', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' categories.');
                this.all_categories = response.data;
            }
        },
        filteredPayee(search_payee_id){
            console.log('filtered');
            let final_arr = [];
            if (this.all_payees == null){
                return final_arr;
            }
            
            this.all_payees.forEach(function(payee){
                if (payee.id == search_payee_id){
                    final_arr.push(payee);
                }
            });
            return final_arr[0];
        },
        filteredCategory(search_category_id){
            console.log('filtered');
            let final_arr = [];
            if (this.all_categories == null){
                return final_arr;
            }
            
            this.all_categories.forEach(function(category){
                if (category.id == search_category_id){
                    final_arr.push(category);
                }
            });
            return final_arr[0];
        },
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
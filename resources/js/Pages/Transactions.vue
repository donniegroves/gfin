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
        <table class="table table-striped align-middle">
            <thead>
                <th scope="col">Options</th>
                <th scope="col">Date</th>
                <th scope="col">Payee</th>
                <th scope="col">Category</th>
                <th scope="col">Description</th>
                <th scope="col">Withdrawl / Deposit</th>
            </thead>
            <tbody>
                <TransactionRow v-for="(tran, index) in all_trans" 
                    :transaction="tran"
                    :all_payees="all_payees"
                    :all_categories="all_categories"
                    :selected_payee="filteredPayee(tran.payee_id)"
                    :selected_category="filteredCategory(tran.category_id)"
                    :key="tran.id"
                    @editTransaction="refreshView"
                    @transactionDeleted="refreshView"
                />
            </tbody>
        </table>
        <div class="page-turner">
            <a href="#" @click="changePage" class="paginate first-page">First Page</a> ... 
            <a href="#" @click="changePage" class="paginate prev-page">Previous Page</a> ... 
            <a href="#" @click="changePage" class="paginate next-page">Next Page</a> ... 
            <a href="#" @click="changePage" class="paginate last-page">Last Page</a>
        </div>
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
            all_trans:      [],
            all_payees:     [],
            all_categories: [],
            last_page:   null,
            current_page:null
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
        changePage(e){
            console.log('changePage');
            console.log(e.target.classList[1]);
            let switch_to = e.target.classList[1];
            console.log('switching to ' + switch_to);
            switch(switch_to){
                case 'first-page':
                    this.refreshTransactions(1);
                    break;
                case 'prev-page':
                    this.refreshTransactions(this.current_page-1);
                    break;
                case 'next-page':
                    this.refreshTransactions(this.current_page+1);
                    break;
                case 'last-page':
                    this.refreshTransactions(this.last_page);
                    break;
            }
        },
        refreshView(){
            console.log('refreshView');
            this.refreshTransactions();
            this.refreshPayees();
            this.refreshCategories();
        },
        async refreshTransactions(page=1){
            console.log('refreshTransactions');
            const response = await axios.get('reqs/transactions?page='+page, {});
            if (response.status == 200){
                console.log('received ' + response.data.data.length + ' transactions.');
                this.all_trans = response.data.data;
                this.current_page = response.data.current_page;
                this.last_page = response.data.last_page;               
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
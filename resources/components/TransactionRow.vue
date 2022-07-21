<template>
    <div class="transactionRow row pt-1 pb-1 align-items-center">
        <div class="col-2">{{ date }}</div>
        <div class="col-3 transactionRow-payee">{{ getPayeeName }}</div>
        <div class="col-2 transactionRow-category">{{ getCategoryName }}</div>
        <div class="col-3 transactionRow-desc">{{ desc }}</div>
        <div class="col-1">{{ amt }}</div>
        <div class="col-1 text-right p-0">
            <button @click="editTransaction()" class="btn btn-outline-info btn-sm"><i :class="['fas', edit_mode ? 'fa-check' : 'fa-edit']"></i></button>
            <button @click="deleteTransaction()" class="btn btn-outline-danger btn-sm ml-1"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</template>

<script>
import {store} from '../js/store.js';
export default{
    props: ['tran_id','date','payee_id','category_id','desc','amt'],
    data: function(){
        return {
            transaction: null,
            all_payees: store.all_payees,
            all_categories: store.all_categories,
            edit_mode: false
        }
    },
    methods: {
        deleteTransaction(){
            axios.delete('api/transaction/' + this.tran_id)
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('transactionDeleted');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        editTransaction(){
            this.edit_mode = !this.edit_mode;
        }
    },
    computed: {
        getPayeeName(){
            let found = this.all_payees.find(element => element.id == this.payee_id);
            return found ? found.name : '';
        },
        getCategoryName(){
            let found = this.all_categories.find(element => element.id == this.category_id);
            return found ? found.name : '';
        }
    }
}
</script>

<style scoped>
.transactionRow{
    border-bottom: grey;
    border-bottom-width: thin;
    border-bottom-style: solid;
}
.transactionRow-desc, .transactionRow-payee{
    overflow: hidden;
    white-space: nowrap;
}
</style>
<template>
    <div class="row pt-1 pb-1 align-items-center">
        <div class="col-2 pl-0">
            <input type="date" v-model="transaction.trans_date"/>
        </div>
        <div class="col-3 pl-2">
            <v-select label="name" v-model="transaction.payee" :options="all_payees_arr"></v-select>
        </div>
        <div class="col-4 pl-2">
            <input type="text" v-model="transaction.orig_detail"/>
        </div>
        <div class="col-1 p-0">
            <input type="number" min="1" step="any" v-model="transaction.orig_amt"/>
        </div>
        <div class="col-2 pl-2 pr-0 text-right">
            <button
                icon="plus-square"
                @click="addTransaction()"
                :class="[ transaction.trans_date && transaction.orig_detail && transaction.orig_amt ? 'active' : 'inactive']"
            >Add</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default{
    data: function(){
        return{
            transaction: {
                trans_date: "",
                payee: this.all_payees_arr[0],
                orig_detail: "",
                orig_amt: ""
            }
        }
    },
    props: {
        all_payees_arr: {
            type: Array,
            default: [],
        }
    },
    methods: {
        addTransaction(){
            axios.post('api/transaction/store', {
                transaction: this.transaction
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.transaction.trans_date = this.transaction.orig_detail = this.transaction.orig_amt = "";
                    this.transaction.payee = this.all_payees_arr[0];
                }
            })
            .catch( error => {
                console.log(error);
            })
        }
    }
}
</script>

<style scoped>
input{
    width: 100%;
} 
.active {
    color: #00ce25;
}
.inactive{
    color: #999;
}
</style>
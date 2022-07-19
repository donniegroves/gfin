<template>
    <div class="row pt-1 pb-1 align-items-center">
        <div class="col-2 pl-0">
            <input class="form-control" type="date" v-model="transaction.trans_date"/>
        </div>
        <div class="col-3 pl-2">
            <v-select label="name" v-model="transaction.payee" :options="store.all_payees"></v-select>
        </div>
        <div class="col-4 pl-2">
            <input class="form-control" type="text" v-model="transaction.orig_detail"/>
        </div>
        <div class="col-1 p-0">
            <input class="form-control" type="number" min="1" step="any" v-model="transaction.orig_amt"/>
        </div>
        <div class="col-2 pl-2 pr-0 text-right">
            <button
                @click="addTransaction()"
                :class="['btn', 'btn-primary']"
                :disabled="!transaction.trans_date || !transaction.orig_detail || !transaction.orig_amt"
            >Add</button>
        </div>
    </div>
</template>

<script>
import {store} from '../js/store.js'

export default{
    data: function(){
        return{
            transaction: {
                trans_date: "",
                payee: store.all_payees[0],
                orig_detail: "",
                orig_amt: ""
            },
            store
        }
    },
    props: {
    },
    methods: {
        addTransaction(){
            axios.post('api/transaction/store', {
                transaction: {
                    payee_id: this.transaction.payee.id >= 1 ? this.transaction.payee.id : null,
                    orig_amt: this.transaction.orig_amt,
                    trans_date: this.transaction.trans_date,
                    orig_detail: this.transaction.orig_detail
                    }
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.transaction.trans_date = this.transaction.orig_detail = this.transaction.orig_amt = "";
                    this.transaction.payee = store.all_payees[0];
                    this.$emit('transactionAdded');
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
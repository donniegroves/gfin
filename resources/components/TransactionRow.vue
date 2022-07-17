<template>
    <div class="transactionRow row pt-1 pb-1 align-items-center">
        <div class="col-2">{{ date }}</div>
        <div class="col-3 transactionRow-payee">{{ getPayeeName }}</div>
        <div class="col-4 transactionRow-desc">{{ desc }}</div>
        <div class="col-1">{{ amt }}</div>
        <div class="col-2 text-right p-0">
                <button>Edit</button>
                <button class="ml-2">Delete</button>
        </div>
    </div>
</template>

<script>
import {store} from '../js/store.js';
export default{
    props: ['tran_id','date','payee_id','desc','amt'],
    data: function(){
        return {
            transaction: null,
            all_payees: store.all_payees
        }
    },
    computed: {
        now() {
            return Date.now();
        },
        getPayeeName(){
            let found = this.all_payees.find(element => element.id == this.payee_id);
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
<template>
    <div class="transactionAdd">
        <input type="date" v-model="transaction.trans_date"/>
        <input type="text" v-model="transaction.payee"/>
        <input type="text" v-model="transaction.orig_detail"/>
        <input type="number" min="1" step="any" v-model="transaction.orig_amt"/>
        <button
            icon="plus-square"
            @click="addTransaction()"
            :class="[ transaction.trans_date && transaction.orig_detail && transaction.orig_amt ? 'active' : 'inactive', 'addtrans']"
        >Add Transaction</button>
    </div>
</template>

<script>
import axios from 'axios';

export default{
    data: function(){
        return{
            transaction: {
                trans_date: "",
                payee: null,
                orig_detail: "",
                orig_amt: ""
            }
        }
    },
    methods: {
        addTransaction(){
            if (this.transaction.name == ''){
                return;
            }

            axios.post('api/transaction/store', {
                transaction: this.transaction
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.transaction.trans_date = this.transaction.payee = this.transaction.orig_detail = this.transaction.orig_amt = "";
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
.addtrans{
    font-size: 20px;
}
.active {
    color: #00ce25;
}
.inactive{
    color: #999;
}
</style>
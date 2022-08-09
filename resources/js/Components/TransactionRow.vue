<template>
    <div class="transactionRow row pt-1 pb-1 align-items-center">
        <div class="col-2">
            <!-- <button @click="verifyTransaction()" class="btn btn-outline-danger btn-sm ml-1"><i class="fa-solid fa-square-check"></i></button>
            <span>{{ transaction.verified }}</span> -->
            <input class="form-control" type="date" v-model="row_date" @change="editTransaction"/>
        </div>
        <div class="col-2 transactionRow-payee">
            <v-select appendToBody label="name" v-model="selected_payee" :options="all_payees" @option:selected="editTransaction"></v-select>
        </div>
        <div class="col-2 transactionRow-category">
            <v-select appendToBody label="name" v-model="selected_category" :options="all_categories" @option:selected="editTransaction"></v-select>
        </div>
        <div class="col-4 transactionRow-desc">
            <input class="form-control" type="text" v-model="row_desc" @change="editTransaction"/>
        </div>
        <div class="col-1">
            <input class="form-control" type="number" min="1" step="any" v-model="row_amt" @change="editTransaction"/>
        </div>
        <div class="col-1 d-flex justify-content-around p-0">
            <button @click="toggleVerified()" class="btn btn-outline-info btn-sm"><i :class="['fas', row_verified ? 'fa-check' : 'fa-square-o']"></i></button>
            <button @click="deleteTransaction()" class="btn btn-outline-danger btn-sm ml-1"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select';
export default{
    components: {vSelect},
    props: ['transaction','all_payees','all_categories','selected_payee','selected_category'],
    data: function(){
        return {
            row_date: this.transaction.trans_date,
            row_desc: this.transaction.new_detail?.length > 0 ? this.transaction.new_detail : this.transaction.orig_detail,
            row_amt: this.transaction.new_amt?.length > 0 ? this.transaction.new_amt : this.transaction.orig_amt,
            row_verified: this.transaction.verified
        }
    },
    methods: {
        deleteTransaction(){
            axios.delete('reqs/transactions/destroy/' + this.transaction.id)
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('transactionDeleted');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        toggleVerified(){
            this.row_verified = !this.row_verified;
            this.editTransaction();
        },
        editTransaction(){
            axios.put('reqs/transactions/update/' + this.transaction.id,{
                transaction: {
                    trans_date: this.row_date,
                    payee_id: this.selected_payee?.id,
                    category_id: this.selected_category?.id,
                    new_detail: this.row_desc,
                    new_amt: this.row_amt,
                    verified: this.row_verified
                }
            })
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('editTransaction');
                }
            })
            .catch( error => {
                console.log(error);
            });
        }
    }
}
</script>

<style scoped>
.form-control {
    padding: 0 0.50rem;
    height: calc(1em + 0.75rem + 2px);
}
.transactionRow{
    border-bottom: rgb(235, 235, 235);
    border-bottom-width: thin;
    border-bottom-style: solid;
}
.transactionRow-desc, .transactionRow-payee{
    overflow: hidden;
    white-space: nowrap;
}
</style>
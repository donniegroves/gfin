<template>
    <div class="transactionRow row pt-1 pb-1 align-items-center">
        <div class="col-2">
            <input class="form-control" type="date" v-model="row_date"/>
        </div>
        <div class="col-3 transactionRow-payee">
            <!-- {{ getPayeeName }} -->
        </div>
        <div class="col-2 transactionRow-category">
            <!-- {{ getCategoryName }} -->
        </div>
        <div class="col-3 transactionRow-desc">
            <input class="form-control" type="text" v-model="row_desc"/>
        </div>
        <div class="col-1">
            <input class="form-control" type="number" min="1" step="any" v-model="row_amt"/>
        </div>
        <div class="col-1 text-right p-0">
            <button @click="editTransaction()" class="btn btn-outline-info btn-sm"><i :class="['fas', edit_mode ? 'fa-check' : 'fa-edit']"></i></button>
            <button @click="deleteTransaction()" class="btn btn-outline-danger btn-sm ml-1"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</template>

<script>
export default{
    props: ['transaction','all_payees','all_categories'],
    data: function(){
        return {
            row_date: this.transaction.trans_date,
            row_payee: [],
            row_cat: [],
            row_desc: this.transaction.new_detail?.length > 0 ? this.transaction.new_detail : this.transaction.orig_detail,
            row_amt: this.transaction.new_amt?.length > 0 ? this.transaction.new_amt : this.transaction.orig_amt,
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
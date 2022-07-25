<template>
    <div class="transactionRow row pt-1 pb-1 align-items-center">
        <div class="col-2">
            <input v-if="edit_mode" class="form-control" type="date" v-model="row_date"/>
            <span v-else v-text="row_date"></span>
        </div>
        <div class="col-3 transactionRow-payee">
            <v-select v-if="edit_mode" appendToBody label="name" v-model="selected_payee" :options="all_payees"></v-select>
            <span v-else v-text="selected_payee_name"></span>
        </div>
        <div class="col-2 transactionRow-category">
            <v-select v-if="edit_mode" appendToBody label="name" v-model="selected_category" :options="all_categories"></v-select>
            <span v-else v-text="selected_category_name"></span>
        </div>
        <div class="col-3 transactionRow-desc">
            <input v-if="edit_mode" class="form-control" type="text" v-model="row_desc"/>
            <span v-else>{{ row_desc }}</span>
        </div>
        <div class="col-1">
            <input v-if="edit_mode" class="form-control" type="number" min="1" step="any" v-model="row_amt"/>
            <span v-else>{{ row_amt }}</span>
        </div>
        <div class="col-1 text-right p-0">
            <button @click="toggleEdit()" class="btn btn-outline-info btn-sm"><i :class="['fas', edit_mode ? 'fa-check' : 'fa-edit']"></i></button>
            <button @click="deleteTransaction()" class="btn btn-outline-danger btn-sm ml-1"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select';
export default{
    components: {vSelect},
    props: ['transaction','all_payees','all_categories','selected_payee','selected_category','selected_payee_name','selected_category_name'],
    data: function(){
        return {
            row_date: this.transaction.trans_date,
            row_desc: this.transaction.new_detail?.length > 0 ? this.transaction.new_detail : this.transaction.orig_detail,
            row_amt: this.transaction.new_amt?.length > 0 ? this.transaction.new_amt : this.transaction.orig_amt,
            edit_mode: false
        }
    },
    methods: {
        deleteTransaction(){
            axios.delete('api/transaction/' + this.transaction.id)
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('transactionDeleted');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        toggleEdit(){
            if (this.edit_mode){
                this.editTransaction();
            }
            this.edit_mode = !this.edit_mode;
        },
        editTransaction(){
            axios.put('api/transaction/' + this.transaction.id,{
                transaction: {
                    trans_date: this.row_date,
                    payee_id: this.selected_payee.id,
                    category_id: this.selected_category.id,
                    new_detail: this.row_desc,
                    new_amt: this.row_amt
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
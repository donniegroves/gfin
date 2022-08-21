<template>
    <tr class="text-center">
        <td class="options">
            <button @click="toggleApproved()" :class="['btn', 'btn-sm', row_approved ? 'btn-info' : 'btn-outline-info']">
                <i class="fas fa-check"></i>
            </button>
            <button @click="deleteTransaction()" class="btn btn-outline-danger btn-sm ml-1">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
        <td class="text-center date-field">
            <input class="form-control" type="date" v-model="row_date" @change="editTransaction"/>
        </td>
        <td class="payee-field">
            <v-select appendToBody label="name" v-model="selected_payee" :options="all_payees" @option:selected="editTransaction"></v-select>
        </td>
        <td class="category-field">
            <v-select appendToBody label="name" v-model="selected_category" :options="all_categories" @option:selected="editTransaction"></v-select>
        </td>
        <td class="desc-field">
            <input class="form-control" type="text" v-model="row_desc" @change="editTransaction"/>
        </td>
        <td class="amt-td text-right">
            <input class="text-right form-control minus" type="text" v-model="row_amt_minus" @change="updateRowAmts"/>
            <input class="text-right form-control plus" type="text" v-model="row_amt_plus" @change="updateRowAmts"/>
        </td>
    </tr>
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
            row_amt_minus: this.transaction.new_amt?.length > 0 ? (this.transaction.new_amt < 0 ? (this.transaction.new_amt*-1).toFixed(2) : null) : (this.transaction.orig_amt < 0 ? (this.transaction.orig_amt*-1).toFixed(2) : null),
            row_amt_plus:  this.transaction.new_amt?.length > 0 ? (this.transaction.new_amt > 0 ? this.transaction.new_amt : null) : (this.transaction.orig_amt > 0 ? this.transaction.orig_amt : null),
            row_amt_calc: this.transaction.new_amt?.length > 0 ? this.transaction.new_amt : this.transaction.orig_amt,
            row_approved: this.transaction.approved
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
        toggleApproved(){
            this.row_approved = !this.row_approved;
            this.editTransaction();
        },
        editTransaction(){
            axios.put('reqs/transactions/update/' + this.transaction.id,{
                transaction: {
                    trans_date: this.row_date,
                    payee_id: this.selected_payee?.id,
                    category_id: this.selected_category?.id,
                    new_detail: this.row_desc,
                    new_amt: this.row_amt_calc,
                    approved: this.row_approved
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
        },
        updateRowAmts(e){
            if (e.target.classList.contains('minus')){
                this.row_amt_plus = null;
                this.row_amt_calc = this.row_amt_minus*-1;
            }
            else {
                this.row_amt_minus = null;
                this.row_amt_calc = this.row_amt_plus;
            }
            this.editTransaction();
        }
    }
}
</script>

<style scoped>
.options {
    width: 70px;
}
.date-field input, .date-field{
    width: 135px;
}
.payee-field, .category-field{
    width: 225px;
}
.amt-td{
    width: 190px;
}
.amt-td input{
    display: inline-block;
    width: 50%;
}
.amt-td .plus{
    border-color: #36b9cc;
}
.amt-td .minus{
    border-color: #e74a3b;
}
</style>
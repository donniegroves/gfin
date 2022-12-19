<template>
    <tr class="text-left">
        <td class="options">
            <button
                @click="addTransaction()"
                :class="['btn', 'btn-sm', 'btn-primary']"
                :disabled="!add_date || !add_desc || (!row_amt_minus && !row_amt_plus)"
            >Add</button>
        </td>
        <td class="text-center date-field">
            <input class="form-control" type="date" v-model="add_date"/>
        </td>
        <td class="payee-field">
            <v-select taggable label="name" v-model="add_payee" :options="all_payees"></v-select>
        </td>
        <td class="category-field">
            <v-select taggable label="name" v-model="add_cat" :options="all_categories"></v-select>
        </td>
        <td class="desc-field">
            <input class="form-control" type="text" v-model="add_desc"/>
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
    components: {
        vSelect
    },
    props: ['all_payees','all_categories'],
    data(){
        return{
            add_date: null,
            add_payee: [],
            add_cat: [],
            add_desc: null,
            row_amt_plus: null,
            row_amt_minus: null
        }
    },
    methods: {
        addTransaction(){
            console.log('addTransaction');
            let calc_amt = this.row_amt_plus ?? this.row_amt_minus*-1;
            axios.post('reqs/transactions/store', {
                transaction: {
                    payee_id: this.add_payee.id >= 1 ? this.add_payee.id : this.add_payee.name,
                    category_id: this.add_cat.id >= 1 ? this.add_cat.id : this.add_cat.name,
                    orig_amt: calc_amt,
                    trans_date: this.add_date,
                    orig_detail: this.add_desc
                    }
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.add_date = this.add_desc = this.row_amt_plus = this.row_amt_minus = "";
                    this.add_payee = this.add_cat = [];
                    this.$emit('transactionAdded');
                }
            })
            .catch( error => {
                console.log(error);
            })
        },
        updateRowAmts(e){
            if (e.target.classList.contains('minus')){
                this.row_amt_plus = null;
            }
            else {
                this.row_amt_minus = null;
            }
        }
    }
}
</script>

<style scoped>

.active {
    color: #00ce25;
}
.inactive{
    color: #999;
}

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
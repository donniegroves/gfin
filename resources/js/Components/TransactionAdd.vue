<template>
    <div class="row pt-1 pb-1 align-items-center">
        <div class="col-2">
            <input class="form-control" type="date" v-model="add_date"/>
        </div>
        <div class="col-2 pl-2">
            <v-select label="name" v-model="add_payee" :options="all_payees"></v-select>
        </div>
        
        <div class="col-2 pl-2">
            <v-select label="name" v-model="add_cat" :options="all_categories"></v-select>
        </div>
        
        <div class="col-4 pl-2">
            <input class="form-control" type="text" v-model="add_desc"/>
        </div>
        <div class="col-1">
            <input class="form-control" type="number" min="1" step="any" v-model="add_amt"/>
        </div>
        <div class="col-1 pl-2 pr-0 text-right">
            <button
                @click="addTransaction()"
                :class="['btn', 'btn-primary']"
                :disabled="!add_date || !add_desc || !add_amt"
            >Add</button>
        </div>
    </div>
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
            add_amt: null
        }
    },
    methods: {
        addTransaction(){
            console.log('addTransaction');
            axios.post('reqs/transactions/store', {
                transaction: {
                    payee_id: this.add_payee.id >= 1 ? this.add_payee.id : null,
                    category_id: this.add_cat.id >= 1 ? this.add_cat.id : null,
                    orig_amt: this.add_amt,
                    trans_date: this.add_date,
                    orig_detail: this.add_desc
                    }
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.add_date = this.add_desc = this.add_amt = "";
                    this.add_payee = this.add_cat = [];
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
.form-control {
    padding: 0 0.50rem;
    height: calc(1em + 0.75rem + 2px);
}
.active {
    color: #00ce25;
}
.inactive{
    color: #999;
}
</style>
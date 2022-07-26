<template>
    <div class="row pt-1 pb-1 align-items-center">
        <div class="col-3 pr-1">
            <input class="form-control" id="addPayeeInput" type="text" v-model="payee_name" placeholder="Company Name..." />
        </div>
        <div class="col-2 pl-1 text-left">
            <button
                @click="addPayee()"
                :class="['btn', 'btn-outline-primary', 'btn-sm']"
                :disabled="!payee_name"
            ><i class="fa-solid fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
export default{
    data: function(){
        return{
            payee_name: ''
        }
    },
    methods: {
        addPayee(){
            axios.post('reqs/payees/store', {
                payee: {
                    name: this.payee_name
                    }
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.payee_name = '';
                    this.$emit('payeeAdded');
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
.active {
    color: #00ce25;
}
.inactive{
    color: #999;
}
</style>
<template>
    <div class="row pt-1 pb-1 align-items-center">
        <div class="col-5 pl-0">
            <input type="text" v-model="payee" placeholder="Company Name...">
        </div>
        <div class="col-2 text-left">
            <button
                icon="plus-square"
                @click="addPayee()"
                :class="[ payee ? 'active' : 'inactive']"
            >Add</button>
        </div>
    </div>
</template>

<script>
export default{
    data: function(){
        return{
            payee: ''
        }
    },
    methods: {
        addPayee(){
            axios.post('api/payee/store', {
                payee: {
                    name: this.payee
                    }
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.payee = '';
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
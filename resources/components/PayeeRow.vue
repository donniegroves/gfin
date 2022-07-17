<template>
    <div class="row pt-1 pb-1 align-items-center">
        <div class="col-3">
            <input type="text" :value="this.payee_name" @change="setPayeeName">
        </div>
        <div class="col-2 text-left">
            <button v-if="this.payee_name !== payee.name" @click="confirmPayeeChange()">Confirm</button>
            <button v-if="this.payee_name !== payee.name" @click="revertChange()">Revert</button>
            <button @click="deletePayee()">Delete</button>
        </div>
    </div>
</template>

<script>
export default{
    props: ['payee'],
    data: function(){
        return {
            payee_name: this.payee.name
        }
    },
    methods: {
        setPayeeName(x){
            this.payee_name = x.target.value
        },
        confirmPayeeChange(){
            axios.put('api/payee/' + this.payee.id,{
                payee: {
                    name: this.payee_name
                }
            })
            .then (response => {
                if( response.status == 200 ){
                    console.log(response.data);
                    this.$emit('editPayee');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        revertChange(){
            this.payee_name = this.payee.name
        },
        deletePayee(){
            axios.delete('api/payee/' + this.payee.id)
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('payeeDeleted');
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
.row{
    border-bottom: rgb(201, 201, 201);
    border-bottom-width: thin;
    border-bottom-style: solid;
}
input{
    width: 100%;
}
</style>
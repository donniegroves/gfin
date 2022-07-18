<template>
    <div class="row pb-2 align-items-center">
        <div class="col-6">
            <div class="row">
                <div class="col-9">
                    <input class="form-control" type="text" :value="this.payee_name" @change="setPayeeName" />
                </div>
                <div class="col-3">
                    <button v-if="this.payee_name !== payee.name" @click="confirmPayeeChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-check"></i></button>
                    <button v-if="this.payee_name !== payee.name" @click="revertChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-rotate-left"></i></button>
                    <button @click="deletePayee()" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    <button @click="showPatterns()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-wand-magic-sparkles"></i></button>
                </div>
            </div>
        </div>
        <div class="col-6">
            Patterns: {{ this.payee_patterns }}
        </div>
    </div>
</template>

<script>
export default{
    props: ['payee'],
    data: function(){
        return {
            payee_name: this.payee.name,
            payee_patterns: []
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
        },
        showPatterns(){
            console.log('PayeeRow showPatterns');
            axios.get('api/patterns/' + this.payee.id, {
            })
            .then ( response => {
                if( response.status == 200 ){
                    this.payee_patterns = Object.values(response.data);
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
button{
    margin: 0 3px;
}
</style>
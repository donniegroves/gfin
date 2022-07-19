<template>
    <div class="row align-items-center">
        <div class="col-8 pr-0">
            <input class="form-control" type="text" v-model="cur_input" placeholder="New Pattern..." />
        </div>
        <div class="col-4 pl-2 text-left">
            <button
                @click="addPattern()"
                :class="['btn', 'btn-outline-primary', 'btn-sm']"
                :disabled="!cur_input"
            ><i class="fa-solid fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
export default{
    props: ['payee_id'],
    data: function(){
        return {
            cur_input: null,
            payee_id: this.payee_id
        }
    },
    methods: {
        addPattern(){
            axios.post('api/payeepattern/store', {
                pattern: {
                    pattern: this.cur_input,
                    payee_id: this.payee_id
                    }
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.cur_input = '';
                    this.$emit('patternAdded');
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
</style>
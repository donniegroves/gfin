<template>
    <div class="row pb-2 align-items-baseline">
        <div class="col-6">
            <div class="row align-items-center">
                <div class="col-8 pr-0">
                    <input class="form-control" type="text" v-model="cur_payee_name" />
                </div>
                <div class="col-4 pl-1">
                    <button v-if="cur_payee_name !== orig_payee_name" @click="confirmPayeeChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-check"></i></button>
                    <button v-if="cur_payee_name !== orig_payee_name" @click="revertChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-rotate-left"></i></button>
                    <button @click="deletePayee()" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    <button @click="showPatterns()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-wand-magic-sparkles"></i></button>
                </div>
            </div>
        </div>
        <div v-if="show_patterns" class="col-6">
            <PatternAdd 
                :payee_id="payee.id"
                @patternAdded="showPatterns"
            />
            <PatternRow v-for="(pattern, index) in payee_patterns" 
                class="pt-2"
                :key="pattern.id"
                :pattern="pattern"
                @editPattern="showPatterns"
                @patternDeleted="showPatterns"
            />
        </div>
    </div>
    <hr class="mt-0 mb-2" />
</template>

<script>
import PatternRow from "../components/PatternRow.vue";
import PatternAdd from "../components/PatternAdd.vue";
export default{
    props: ['payee'],
    components: {
        PatternRow,
        PatternAdd
    },
    data: function(){
        return {
            cur_payee_name: this.payee.name,
            orig_payee_name: this.payee.name,
            payee_patterns: [],
            show_patterns: false
        }
    },
    methods: {
        confirmPayeeChange(){
            axios.put('api/payee/' + this.payee.id,{
                payee: {
                    name: this.payee_name
                }
            })
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('editPayee');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        revertChange(){
            this.cur_payee_name = this.orig_payee_name
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
            axios.get('api/payeepatterns/' + this.payee.id, {
            })
            .then ( response => {
                if( response.status == 200 ){
                    this.payee_patterns = Object.values(response.data);
                    this.show_patterns = true;
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
hr {
    border-color: gray;
}
button{
    margin: 0 3px;
}
</style>
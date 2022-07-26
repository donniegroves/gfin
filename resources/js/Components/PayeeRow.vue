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
                    <button @click="toggleShowPatterns()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-wand-magic-sparkles"></i></button>
                </div>
            </div>
        </div>
        <div v-if="show_patterns" class="col-6">
            <PatternAdd 
                :payee_id="payee.id"
                @patternAdded="refreshPatterns"
            />
            <PatternRow v-for="(pattern, index) in row_patterns" 
                class="pt-2"
                :key="pattern.id"
                :pattern="pattern"
                @editPattern="refreshPatterns"
                @patternDeleted="refreshPatterns" 
            />
        </div>
    </div>
    <hr class="mt-0 mb-2" />
</template>

<script>
import PatternRow from "@/Components/PatternRow.vue";
import PatternAdd from "@/Components/PatternAdd.vue";
export default{
    props: ['payee','all_payees','row_patterns'],
    emits: ['editPayee','payeeDeleted','refreshPatterns'],
    created(){
        console.log('created PayeeRow');
    },
    components: {
        PatternRow,
        PatternAdd
    },
    data(){
        return {
            cur_payee_name: this.payee.name,
            orig_payee_name: this.payee.name,
            show_patterns: false,
        }
    },
    methods: {
        confirmPayeeChange(){
            console.log('PayeeRow - confirmPayeeChange');
            axios.put('reqs/payees/update/' + this.payee.id,{
                payee: {
                    name: this.cur_payee_name
                }
            })
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('editPayee');
                    this.orig_payee_name = this.cur_payee_name;
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
            axios.delete('reqs/payees/destroy/' + this.payee.id)
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('payeeDeleted');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        toggleShowPatterns(){
            console.log('PayeeRow - toggleShowPatterns');
            this.show_patterns = !this.show_patterns;
        },
        refreshPatterns(){
            console.log('PayeeRow - refreshPatterns');
            this.$emit('refreshPatterns');
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
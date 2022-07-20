<template>
    <div class="row align-items-center">
        <div class="col-8 pr-0">
            <input class="form-control" type="text" v-model="cur_pattern" />
        </div>
        <div class="col-4 pl-1">
            <button v-if="orig_pattern !== cur_pattern" @click="confirmPatternChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-check"></i></button>
            <button v-if="orig_pattern !== cur_pattern" @click="revertChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-rotate-left"></i></button>
            <button @click="deletePattern()" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</template>

<script>
export default{
    props: ['pattern'],
    data: function(){
        return {
            cur_pattern: this.pattern.pattern,
            orig_pattern: this.pattern.pattern
        }
    },
    methods: {
        revertChange(){
            this.cur_pattern = this.orig_pattern;
        },
        confirmPatternChange(){
            axios.put('api/categorypattern/' + this.pattern.id,{
                pattern: {
                    pattern: this.cur_pattern
                }
            })
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('editPattern');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        deletePattern(){
            axios.delete('api/categorypattern/' + this.pattern.id)
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('patternDeleted');
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
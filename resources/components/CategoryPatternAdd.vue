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
    props: ['category_id'],
    data: function(){
        return {
            cur_input: null,
            category_id: this.category_id
        }
    },
    methods: {
        addPattern(){
            axios.post('api/categorypattern/store', {
                pattern: {
                    pattern: this.cur_input,
                    category_id: this.category_id
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
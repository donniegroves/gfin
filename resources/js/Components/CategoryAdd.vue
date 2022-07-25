<template>
    <div class="row pt-1 pb-1 align-items-center">
        <div class="col-3 pr-1">
            <input class="form-control" id="addCategoryInput" type="text" v-model="category_name" placeholder="Category Name..." />
        </div>
        <div class="col-2 pl-1 text-left">
            <button
                @click="addCategory()"
                :class="['btn', 'btn-outline-primary', 'btn-sm']"
                :disabled="!category_name"
            ><i class="fa-solid fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
export default{
    data: function(){
        return{
            category_name: ''
        }
    },
    methods: {
        addCategory(){
            axios.post('api/category/store', {
                category: {
                    name: this.category_name
                    }
            })
            .then ( response => {
                if( response.status == 201 ){
                    this.category_name = '';
                    this.$emit('categoryAdded');
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
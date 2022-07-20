<template>
    <div class="row pb-2 align-items-baseline">
        <div class="col-6">
            <div class="row align-items-center">
                <div class="col-8 pr-0">
                    <input class="form-control" type="text" v-model="cur_cat_name" />
                </div>
                <div class="col-4 pl-1">
                    <button v-if="cur_cat_name !== orig_cat_name" @click="confirmCategoryChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-check"></i></button>
                    <button v-if="cur_cat_name !== orig_cat_name" @click="revertChange()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-rotate-left"></i></button>
                    <button @click="deleteCategory()" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    <button @click="showPatterns()" class="btn btn-outline-info btn-sm"><i class="fa-solid fa-wand-magic-sparkles"></i></button>
                </div>
            </div>
        </div>
        <div v-if="show_patterns" class="col-6">
            <CategoryPatternAdd 
                :category_id="category.id"
                @patternAdded="showPatterns"
            />
            <CategoryPatternRow v-for="(pattern, index) in category_patterns" 
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
import CategoryPatternRow from "../components/CategoryPatternRow.vue";
import CategoryPatternAdd from "../components/CategoryPatternAdd.vue";
export default{
    props: ['category'],
    components: {
        CategoryPatternRow,
        CategoryPatternAdd
    },
    data: function(){
        return {
            cur_cat_name: this.category.name,
            orig_cat_name: this.category.name,
            category_patterns: [],
            show_patterns: false
        }
    },
    methods: {
        confirmCategoryChange(){
            console.log(this.category.id);
            axios.put('api/category/' + this.category.id,{
                category: {
                    name: this.cur_cat_name
                }
            })
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('editCategory');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        revertChange(){
            this.cur_cat_name = this.orig_cat_name
        },
        deleteCategory(){
            axios.delete('api/category/' + this.category.id)
            .then (response => {
                if( response.status == 200 ){
                    this.$emit('categoryDeleted');
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        showPatterns(){
            console.log('CategoryRow showPatterns');
            axios.get('api/categorypatterns/' + this.category.id, {
            })
            .then ( response => {
                if( response.status == 200 ){
                    this.category_patterns = Object.values(response.data);
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
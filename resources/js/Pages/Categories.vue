<template>
<GFinLayout>
        <h2>Add a Category:</h2>
        <CategoryAdd
            @categoryAdded="refreshView"
        />
        <hr />
        <h2>Categories:</h2>
        <CategoryRow v-for="(category, index) in all_categories"
            :category="category"
            :all_categories="all_categories"
            :row_patterns="filtered(category.id)"
            :key="category.id"
            @editCategory="refreshView"
            @categoryDeleted="refreshView"
            @refreshPatterns="refreshView"
        />
    </GFinLayout>
</template>
<script>
import axios from "axios";
import CategoryAdd from "@/Components/CategoryAdd.vue";
import CategoryRow from "@/Components/CategoryRow.vue";
import GFinLayout from "../Layouts/GFinLayout.vue";
export default{
    data(){
        return {
            all_categories: null,
            all_category_patterns: null
        }
    },
    created(){
        console.log('created ViewCList');
        this.refreshView();
    },
    components: {
    CategoryAdd,
    CategoryRow,
    GFinLayout
},
    methods: {
        filtered(search_category_id){
            console.log('filtered');
            let final_arr = [];
            if (this.all_category_patterns == null){
                return final_arr;
            }
            
            this.all_category_patterns.forEach(function(category){
                if (category.category_id == search_category_id){
                    final_arr.push(category);
                }
            });
            return final_arr;
        },
        refreshView(){
            console.log('refreshView');
            this.refreshCategories();
            this.refreshCategoryPatterns();
        },
        async refreshCategories(){
            console.log('refreshCategories');
            const response = await axios.get('reqs/categories', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' categories.');
                this.all_categories = response.data;
            }
        },
        async refreshCategoryPatterns(){
            console.log('refreshCategoryPatterns');
            const response = await axios.get('reqs/categorypatterns', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' category patterns.');
                this.all_category_patterns = response.data;
            }
        }
    }
}
</script>

<style scoped>
#topbar{
    height: 50px;
    border-bottom-style: solid;
    border-color: gray;
}
#trans-title-row{
    background: darkgray
}
#trans-title{
    color: black;
}
#trans-id-text{
    color: white;
}
.charge-section input{
    max-width: 100px;
    text-align: center;
}
</style>
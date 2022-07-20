<template>
    <h2>Add a Category:</h2>
    <CategoryAdd @categoryAdded="refreshView"/>
    <hr />
    <h2>Categories:</h2>
    <CategoryRow v-for="(category, index) in store.all_categories"
        :category="category"
        :key="category.id"
        @editCategory="refreshView"
        @categoryDeleted="refreshView"
    />
    <!-- 
         -->
</template>
<script>
import {store} from '../js/store.js'
import CategoryAdd from "../components/CategoryAdd.vue";
import CategoryRow from "../components/CategoryRow.vue";
export default{
    data: function(){
        return {
            store
        }
    },
    components: {
        CategoryAdd,
        CategoryRow
    },
    methods: {
        refreshView(){
            console.log('refreshView');
            this.getCategories();
        },
        getCategories(){
            console.log('ViewCList - getCategories');
            axios.get('api/categories', {
            })
            .then ( response => {
                if( response.status == 200 ){
                    this.store.all_categories = Object.values(response.data);
                    this.store.all_categories.unshift({
                        id: 0,
                        name: 'N/A'
                    });
                }
            })
            .catch( error => {
                console.log(error);
            });
        },
        getPatterns(){
            console.log('ViewCList getPatterns');
        }
    },
    created: function(){
        this.getCategories();
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
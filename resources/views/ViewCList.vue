<template>
    <h2>Add a Category:</h2>
    <!-- <PayeeAdd @payeeAdded="refreshView"/> -->
    <hr />
    <h2>Categories:</h2>
    <!-- <PayeeRow v-for="(payee, index) in store.all_payees"
        :payee="payee"
        :key="payee.id"
        @editPayee="refreshView"
        @payeeDeleted="refreshView"
    /> -->
</template>
<script>
import {store} from '../js/store.js'
// import PayeeAdd from "../components/PayeeAdd.vue";
// import PayeeRow from "../components/PayeeRow.vue";
export default{
    data: function(){
        return {
            store
        }
    },
    components: {
        // PayeeAdd,
        // PayeeRow
    },
    methods: {
        refreshView(){
            console.log('refreshView');
            this.getPayees();
        },
        getPayees(){
            console.log('ViewPList - getPayees');
            axios.get('api/payees', {
            })
            .then ( response => {
                if( response.status == 200 ){
                    this.store.all_payees = Object.values(response.data);
                    this.store.all_payees.unshift({
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
            console.log('ViewPList getPatterns');
        }
    },
    created: function(){
        // this.getPayees();
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
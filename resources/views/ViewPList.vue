<template>

    <PayeeAdd @payeeAdded="refreshView"/>
    <hr />

    <!-- <PayeeRow v-for="(payee, index) in payees" 
        :name="tran.name" 
        :payee_id="tran.payee_id"
        @payeeDeleted="refreshView"
    /> -->

</template>
<script>
import {store} from '../js/store.js'
import PayeeAdd from "../components/PayeeAdd.vue";
export default{
    data: function(){
        return {
            store
        }
    },
    components: {
        PayeeAdd
    },
    methods: {
        refreshView(){
            this.getPayees();
        },
        getPayees(){
            console.log('getPayees');
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
        }
    },
    created: function(){
        this.getPayees();
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
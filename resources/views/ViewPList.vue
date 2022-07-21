<template>
    <h2>Add a Payee:</h2>
    <PayeeAdd 
        @payeeAdded="refreshView"
    />
    <hr />
    <h2>Payees:</h2>
    <PayeeRow v-for="(payee, index) in all_payees"
        :payee="payee"
        :all_payees="all_payees"
        :all_payee_patterns="all_payee_patterns"
        :key="payee.id"
        @editPayee="refreshView"
        @payeeDeleted="refreshView"
    />
</template>
<script>
import axios from "axios";
import PayeeAdd from "../components/PayeeAdd.vue";
import PayeeRow from "../components/PayeeRow.vue";
export default{
    data(){
        return {
            all_payees: null,
            all_payee_patterns: null
        }
    },
    created(){
        console.log('created ViewPList');
        this.refreshPayees();
        this.refreshPayeePatterns();
    },
    components: {
        PayeeAdd,
        PayeeRow
    },
    methods: {
        refreshView(){
            console.log('refreshView');
            this.refreshPayees();
        },
        async refreshPayees(){
            console.log('refreshPayees');
            const response = await axios.get('api/payees', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' payees.');
                this.all_payees = response.data;
            }
        },
        async refreshPayeePatterns(){
            console.log('refreshPayeePatterns');
            const response = await axios.get('api/payeepatterns', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' payee patterns.');
                this.all_payee_patterns = response.data;
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
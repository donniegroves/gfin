<template>
    <GFinLayout>
        <h2>Add a Payee:</h2>
        <PayeeAdd 
            @payeeAdded="refreshView"
        />
        <hr />
        <h2>Payees:</h2>
        <PayeeRow v-for="(payee, index) in all_payees"
            :payee="payee"
            :all_payees="all_payees"
            :row_patterns="filtered(payee.id)"
            :key="payee.id"
            @editPayee="refreshView"
            @payeeDeleted="refreshView"
            @refreshPatterns="refreshView"
        />
    </GFinLayout>
</template>

<script>
import axios from "axios";
import PayeeAdd from "@/Components/PayeeAdd.vue";
import PayeeRow from "@/Components/PayeeRow.vue";
import GFinLayout from "@/Layouts/GFinLayout.vue";
export default{
    data(){
        return {
            all_payees: null,
            all_payee_patterns: null
        }
    },
    created(){
        console.log('created ViewPList');
        this.refreshView();
    },
    components: {
    PayeeAdd,
    PayeeRow,
    GFinLayout
},
    methods: {
        filtered(search_payee_id){
            console.log('filtered');
            let final_arr = [];
            if (this.all_payee_patterns == null){
                return final_arr;
            }
            
            this.all_payee_patterns.forEach(function(payee){
                if (payee.payee_id == search_payee_id){
                    final_arr.push(payee);
                }
            });
            return final_arr;
        },
        refreshView(){
            console.log('refreshView');
            this.refreshPayees();
            this.refreshPayeePatterns();
        },
        async refreshPayees(){
            console.log('refreshPayees');
            const response = await axios.get('reqs/payees', {});
            if (response.status == 200){
                console.log('received ' + response.data.length + ' payees.');
                this.all_payees = response.data;
            }
        },
        async refreshPayeePatterns(){
            console.log('refreshPayeePatterns');
            const response = await axios.get('reqs/payeepatterns', {});
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
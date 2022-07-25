<template>
    <div class="col-xl-3 col-md-6 mb-4">
        <div :class="cardClass">
            <div @click="this.$refs.file_input_el.click();" class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <span v-text="bankProfile.bankName"></span>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <span v-text="bankProfile.accountName"></span>
                        </div>
                        <input ref="file_input_el" class="d-none" type="file" @change="onFileChange($event)">
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-file-import fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default{
    props: ['bprofile'],
    data: function(){
        return {
        }
    },
    methods: {
        onFileChange(event){
            console.log(event.target.files[0]);
            let data = new FormData();
            data.append('file', event.target.files[0]);
            axios.post('api/transactions/import', data)
            .then ( response => {
                console.log(response);
                if( response.status == 200 ){
                    console.log('good response received');
                    // this.category_name = '';
                    // this.$emit('fileUploaded');
                }
            })
            .catch( error => {
                console.log(error);
            });
        }
    },
    computed: {
        cardClass(){
            return ['card', 'border-left-' + this.bprofile, 'shadow', 'h-100', 'py-2'];
        },
        bankProfile(){
            switch (this.bprofile){
                case 'wf':
                    return {
                        bankName: 'Wells Fargo',
                        accountName: 'Credit Card'
                    };
                case 'chase':
                    return {
                        bankName: 'Chase Bank',
                        accountName: 'Checking Acct'
                    };
            }
        }
    }
}
</script>

<style scoped>
.card-body{
    cursor: pointer;
}
.border-left-wf{
    border-left: 0.25rem solid rgb(215 30 40);
}
.border-left-chase{
    border-left: 0.25rem solid #126bc5;
}
</style>
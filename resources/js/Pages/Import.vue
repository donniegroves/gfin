<template>
    <GFinLayout>
        <div class="row">
            <div class="col-12">
                <h2>Import Transactions:</h2>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Automatic Transaction Import</h6>
                    </div>
                    <div class="card-body pb-3">
                        <div class="row mb-1">
                            <div v-if="is_account_connected" class="col">
                                <div class="mb-3">
                                    Your account is connected to these bank accounts 
                                </div>
                                <ul>
                                    <li v-for="acct in cur_accts">
                                        {{ acct.name }}-{{ acct.mask }}
                                    </li>
                                </ul>
                                <button type="button" @click="unlinkAccount" class="btn btn-primary btn-lg mr-3">
                                    <span>Unlink Accounts</span>
                                </button>
                                <button type="button" @click="getNewTransactions" class="btn btn-primary btn-lg">
                                    <span>Get Transactions</span>
                                </button>
                            </div>
                            <div v-if="!is_account_connected" class="col">
                                <div class="mb-3">
                                    Your account is not yet linked to any online accounts yet. 
                                </div>
                                <button type="button" id="link_btn" class="btn btn-primary btn-lg">
                                    <span>Link Account</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="is_account_connected" class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Import History</h6>
                    </div>
                    <div class="card-body pb-3">
                        <div class="row mb-1">
                            <div v-if="is_account_connected" class="col">
                                <div>
                                    Import history goes here.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <UploadCard bprofile="wf" />
            <UploadCard bprofile="chase" />
        </div>
    </GFinLayout>
</template>

<script>
import UploadCard from "@/Components/UploadCard.vue";
import axios from "axios";
import GFinLayout from "../Layouts/GFinLayout.vue";
export default{
    data(){
        return {
            is_account_connected: false,
            cur_accts: []
        }
    },
    components: {
        UploadCard,
        GFinLayout
    },
    methods: {
        getConnectedStatus: async function(){
            console.log('checking account connected status.')
            const account = await fetch("/reqs/plaid/is_account_connected");
            const response = await account.json();
            this.is_account_connected = Boolean(response.connected);
            this.cur_accts = response.accts;
        },
        unlinkAccount: async function(){
            try{
                const response = await axios.get('/reqs/plaid/unlink_account', {});
                if (response.status == 200){
                    console.log('successfully unlinked account');
                    this.is_account_connected = false;
                    this.addListenerToLinkBtn();
                }
            }
            catch{
                console.log('problem unlinking account');
            }
        },
        getNewTransactions: async function(){
            try{
                console.log('getting transactions!');
                const response = await axios.get('/reqs/plaid/transactions/import', {});
                if (response.status == 200){
                    console.log('successfully retrieved transactions');
                }
            }
            catch{
                console.log('problem retrieving transactions');
            }
        }
    },
    mounted(){
        this.getConnectedStatus();
        (async ($) => {
            // Grab a Link token to initialize Link
            const createLinkToken = async () => {
                const res = await fetch("/reqs/plaid/create_link_token");
                const data = await res.json();
                const linkToken = data.link_token;
                localStorage.setItem("link_token", linkToken);
                return linkToken;
            };

            // Initialize Link
            const handler = Plaid.create({
                token: await createLinkToken(),
                onSuccess: async (publicToken, metadata) => {
                    console.log('success with createLinkToken, now fetching exchange_public_token');
                    axios.post('/reqs/plaid/exchange_public_token', {
                        public_token: publicToken
                    })
                    .then ( response => {
                        if (response.status == 200){
                            console.log('received access_token successfully');
                            this.getConnectedStatus();
                        }
                        else {
                            console.log('access_token was not received.');
                        }
                    })
                    .catch( error => {
                        console.log(error);
                    })
                },
                onEvent: (eventName, metadata) => {
                    // console.log("Event:", eventName);
                    // console.log("Metadata:", metadata);
                },
                onExit: (error, metadata) => {
                    // console.log(error, metadata);
                },
            });

            // Start Link when button is clicked
            if (!this.is_account_connected) {
                addListenerToLinkBtn(handler);
            }
        })(jQuery);
        
        function addListenerToLinkBtn(handler) {
            console.log('addListenerToLinkBtn ran');
            const linkAccountButton = document.getElementById("link_btn");
            linkAccountButton.addEventListener("click", (event) => {
                console.log('link button clicked');
                handler.open();
            });
        }
    }
}
</script>

<style scoped>

</style>
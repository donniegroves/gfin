<template>
    <GFinLayout>
        <div class="row">
            <div class="col-12">
                <h2>Import Transactions:</h2>
            </div>
        </div>
        <hr />
        <div class="row">
            <UploadCard bprofile="wf" />
            <UploadCard bprofile="chase" />
        </div>
        <div v-if="!isFetching" class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <button type="button" :data-action="this.is_account_connected ? 'unlink' : 'link'" id="link_btn" class="btn btn-primary btn-dark btn-lg" style="
                border: 1px solid black;
                border-radius: 5px;
                background: black;
                height: 48px;
                width: 200px;
                margin-top: 5; 
                margin-left: 10;
                color: white;
                font-size: 18px;
                ">
                    <span v-text="this.is_account_connected ? 'Unlink Account' : 'Link Account'"></span>
                </button>
                <button id="get_trans_btn">Get Transactions</button>
            </div>
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
            isFetching: true,
            is_account_connected: false
        }
    },
    components: {
        UploadCard,
        GFinLayout
    },
    methods: {
        getConnectedStatus: async function(){
            const account = await fetch("/reqs/plaid/is_account_connected");
            const connected = await account.json();
            this.is_account_connected = Boolean(connected);
            this.isFetching = false;
        },
        unlinkAccount: async function(){
            try{
                const response = await axios.get('/reqs/plaid/unlink_account', {});
                if (response.status == 200){
                    console.log('successfully unlinked account');
                    this.is_account_connected = false;
                }
            }
            catch{
                console.log('problem unlinking account');
            }
        },
        getNewTransactions: async function(){
            try{
                const response = await axios.get('/reqs/plaid/transactions/import', {});
                if (response.status == 200){
                    console.log('successfully retrieved transactions');
                    this.is_account_connected = false;
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
                            this.is_account_connected = true;
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
            const linkAccountButton = document.getElementById("link_btn");
            linkAccountButton.addEventListener("click", (event) => {
                if (event.currentTarget.dataset.action == "link"){
                    handler.open();
                }
                else if (event.currentTarget.dataset.action == "unlink"){
                    this.unlinkAccount();
                }
            });

            // Get Transactions from plaid
            const getTransactionsButton = document.getElementById("get_trans_btn");
            getTransactionsButton.addEventListener("click", (event) => {
                console.log('getting transactions!');
                this.getNewTransactions();
            });
        })(jQuery);
    }
}
</script>

<style scoped>

</style>
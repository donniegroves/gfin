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
    </GFinLayout>
</template>

<script>
import UploadCard from "@/Components/UploadCard.vue";
import GFinLayout from "../Layouts/GFinLayout.vue";
export default{
    data(){
        return {
        }
    },
    components: {
    UploadCard,
    GFinLayout
},
    methods: {
    }
}
</script>

<style scoped>

</style>
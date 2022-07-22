<template>
    <h2>Import Transactions:</h2>
    <hr />
    <div class="row">
        <UploadCard bprofile="wf" />
        <UploadCard bprofile="chase" />
    </div>
</template>

<script>
import UploadCard from "../components/UploadCard.vue";
export default{
    data(){
        return {
        }
    },
    components: {
        UploadCard
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
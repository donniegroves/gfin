<template>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <Topbar />

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <Dashboard />
            </div>
        </div>
    </div>
</template>

<script>
    import Topbar from './Topbar.vue';
    import Dashboard from './Dashboard.vue';
    export default {
        data: function(){
            return {
            }
        },
        components: {
            Topbar,
            Dashboard
        }
    }
</script>

<style scoped>

</style>
<template>
    <div id="wrapper">
        <Sidebar :author="this.author"/>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <Topbar />
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3';
import Topbar from '@/Components/Topbar.vue';
import Sidebar from '@/Components/Sidebar.vue';
export default{
    provide() {
        return {logged_in_user: usePage().props.value.auth.user}
    },
    data: function(){
        return {
            author: 'Donnie Groves'
        }
    },
    components: {
        Sidebar,
        Topbar
    },
    mounted(){
        $("#sidebarToggle, #sidebarToggleTop").on('click', function(e) {
            $("body").toggleClass("sidebar-toggled");
            $(".sidebar").toggleClass("toggled");
        });
    }
}
</script>

<style scoped>
</style>
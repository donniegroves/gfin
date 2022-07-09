<template>
    <div id="wrapper">
        <Sidebar :author="this.author"/>
        <MainContentContainer />
        <p>
            <!-- use the router-link component for navigation. -->
            <!-- specify the link by passing the `to` prop. -->
            <!-- `<router-link>` will render an `<a>` tag with the correct `href` attribute -->
            <!-- <router-link to="/settings">Show Settings</router-link> -->
            <!-- <br /> -->
            <!-- <router-link to="/tlist">Show TList</router-link> -->
        </p>

        <!-- route outlet -->
        <!-- component matched by the route will render here -->
        <!-- <router-view></router-view> -->
    </div>
</template>

<script>
import * as $ from 'jquery'; 
import Sidebar from './components/Sidebar.vue';
import MainContentContainer from './components/MainContentContainer.vue';
export default{
    data: function(){
        return {
            author: 'Donnie Groves'
        }
    },
    components: {
        Sidebar,
        MainContentContainer
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
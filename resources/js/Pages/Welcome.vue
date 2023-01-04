<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import RegisterVue from './Auth/Register.vue';
import LoginVue from './Auth/Login.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})
</script>

<template>
    <Head title="Welcome" />
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <img class="logo-svg" src="img/gfin.svg" alt="logo">
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h4 class="text-center">Register</h4>
                <RegisterVue></RegisterVue>
            </div>
            <div class="col-6">
                <h4 class="text-center">Login</h4>
                <LoginVue></LoginVue>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .logo-svg {
        max-width: 300px;
        margin: auto;
    }
    
</style>

<template>
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon">
                <i class="fa-solid fa-piggy-bank fa-rotate-by" style="--fa-rotate-angle: -15deg;"></i>
            </div>
            <div class="sidebar-brand-text mx-3">{{title}}<sup>2</sup></div>
            <div class="sidebar-brand-icon">
                <span class="fa-flip-horizontal" style="display: inline-block;">
                    <i class="fa-solid fa-piggy-bank fa-rotate-by" style="--fa-rotate-angle: -15deg;"></i>
                </span>
            </div>
        </a>

        <hr class="sidebar-divider">

        <!-- Main -->
        <div class="sidebar-heading">Main</div>

        <li class="nav-item active">
            <router-link to="/dashboard" class="nav-link">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </router-link>
        </li>

        
        <li class="nav-item">
            <router-link to="/tlist" class="nav-link">
                <i class="fa-regular fa-fw fa-rectangle-list"></i>
                <span>Transactions</span>
            </router-link>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fa-solid fa-file-import"></i>
                <span>Import</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="fa-regular fa-fw fa-file-lines"></i>
                <span>Reports</span>
            </a>
        </li>
        <!-- End of Main -->

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Settings</div>

        <li class="nav-item">
            <router-link to="/settings" class="nav-link">
                <i class="fa-solid fa-fw fa-gear"></i>
                <span>General</span>
            </router-link>
        </li>

        <!-- Nav Item - Payees -->
        <li class="nav-item">
            <router-link to="/plist" class="nav-link">
                <i class="fa-regular fa-fw fa-building"></i>
                <span>Payees</span>
            </router-link>
        </li>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <router-link to="/clist" class="nav-link">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Categories</span>
            </router-link>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

        <!-- Sidebar Message -->
        <div class="sidebar-card d-none d-lg-flex">
            <img class="author_img sidebar-card-illustration mb-2" src="img/donnie_circle.png" alt="...">
            <p class="text-center mb-2"><strong>{{author}}</strong> made this to sharpen and demonstrate his nerd powers. Like it?</p>
            <a class="btn btn-success btn-sm" href="https://donniegroves.com">Let him know!</a>
        </div>
    </ul>
</template>

<script>
    export default {
        props: {
            title: {
                type: String,
                default: 'GFin',
            },
            author: {
                type: String,
                default: 'Donnie Groves',
            }
        }
    }
</script>

<style scoped>
img.author_img {
  border-radius: 50%;
}
</style>
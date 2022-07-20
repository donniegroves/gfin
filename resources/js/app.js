require('./bootstrap');

import {createApp} from 'vue'
import App from '../App.vue'
import * as VueRouter from 'vue-router'
import ViewSettings from '../views/ViewSettings.vue'
import ViewCList from '../views/ViewCList.vue'
import ViewPList from '../views/ViewPList.vue'
import ViewTList from '../views/ViewTList.vue'
import ViewDashboard from '../views/ViewDashboard.vue'
import vSelect from 'vue-select'

const routes = [
    {
        path: '/dashboard',
        name: 'ViewDashboard',
        component: ViewDashboard
    },
    {
        path: '/clist',
        name: 'ViewCList',
        component: ViewCList
    },
    {
        path: '/plist',
        name: 'ViewPList',
        component: ViewPList
    },
    {
        path: '/tlist',
        name: 'ViewTList',
        component: ViewTList
    },
    {
        path: '/settings',
        name: 'ViewSettings',
        component: ViewSettings
    },
    {
        path: '/inside',
        component: ViewDashboard
    }
];

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory(process.env.BASE_URL),
    routes
})

createApp(App)
.component('v-select', vSelect)
.use(router)
.mount('#app')
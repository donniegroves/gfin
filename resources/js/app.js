require('./bootstrap');

import {createApp} from 'vue'
import App from '../App.vue'
import * as VueRouter from 'vue-router'
import ViewSettings from '../views/ViewSettings.vue'
import ViewTList from '../views/ViewTList.vue'

const routes = [
    {
        path: '/settings',
        name: 'ViewSettings',
        component: ViewSettings
    },
    {
        path: '/tlist',
        name: 'ViewTList',
        component: ViewTList
    }
];

const router = VueRouter.createRouter({
    history: VueRouter.createWebHistory(process.env.BASE_URL),
    routes
})

createApp(App)
.use(router)
.mount('#app')
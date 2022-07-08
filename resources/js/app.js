require('./bootstrap');

import {createApp} from 'vue'
import App from './App.vue'
import * as VueRouter from 'vue-router'
import ViewSettings from '../views/ViewSettings.vue'
import ViewTList from '../views/ViewTList.vue'


/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faPlusSquare, faTrash } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faPlusSquare, faTrash)

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
.component('font-awesome-icon', FontAwesomeIcon)
.mount('#app')
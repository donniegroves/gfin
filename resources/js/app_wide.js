require('./bootstrap');

import {createApp} from 'vue'

import App from './App.vue'
import Zebra from './Zebra.vue'

createApp(App).mount("#app")
createApp(Zebra).mount("#zebra")
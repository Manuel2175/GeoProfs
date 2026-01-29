import './index.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import VCalendar from 'v-calendar'
import 'v-calendar/style.css'
import './services/axios-config';

const app = createApp(App)
app.use(router) // Router moet EERST gekoppeld worden
app.use(VCalendar)
app.mount('#app')

import './index.css'
import {createApp} from 'vue'
import App from './App.vue'
import router from './router'
import VCalendar from 'v-calendar'
import 'v-calendar/style.css'
import './services/axios-config';
import AuthService from './services/AuthService';

const currentUser = AuthService.getCurrentUser();
console.log('Current Logged In User:', currentUser);


const app = createApp(App)
app.use(router)
app.use(VCalendar)
app.mount('#app')

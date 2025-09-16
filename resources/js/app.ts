import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '../js/App.vue';
import router from './router/index';
import '../css/app.css';
import './bootstrap';

console.log('Oliver Borner 🚀 (c)');

const app = createApp(App); 

app.use(createPinia());
app.use(router);

app.mount('#app');

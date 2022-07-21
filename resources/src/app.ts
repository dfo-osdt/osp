import { createApp } from 'vue';
import App from './App.vue';
import { csrf } from './api/sanctum';

const test = await csrf();
console.log(test);

createApp(App).mount('#app');

import { createApp } from 'vue';
import { installQuasar } from './modules/quasar';
import App from './App.vue';

const myApp = createApp(App);

installQuasar(myApp);

// Assumes you have a <div id="app"></div> in your index.html
myApp.mount('#app');

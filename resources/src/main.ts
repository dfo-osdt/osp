import { createApp } from 'vue';
import { installQuasar } from './modules/quasar';
import { installI18n } from './modules/i18n';
import { installPinia } from './modules/pinia';
import { installRouter } from './modules/router';

import App from './App.vue';

const myApp = createApp(App);

installQuasar(myApp);
installI18n(myApp);
installPinia(myApp);
installRouter(myApp);

// Assumes you have a <div id="app"></div> in your index.html
myApp.mount('#app');

import { createApp } from 'vue';
import { installQuasar } from './plugins/quasar';
import { installI18n } from './plugins/i18n';
import { installPinia } from './plugins/pinia';
import { installRouter } from './plugins/router';

import App from './App.vue';

const myApp = createApp(App);

installQuasar(myApp);
installI18n(myApp);
installPinia(myApp);
installRouter(myApp);

// Assumes you have a <div id="app"></div> in your index.html
myApp.mount('#app');

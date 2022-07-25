import { Quasar } from 'quasar';

// Import icon libraries
import '@quasar/extras/roboto-font/roboto-font.css';
import '@quasar/extras/mdi-v6/mdi-v6.css';

// Import Quasar css
import 'quasar/src/css/index.sass';
import { App } from 'vue';

export const installQuasar = (app: App<Element>) => {
    app.use(Quasar, { plugins: {} });
};

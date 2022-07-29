import { Quasar } from 'quasar';
import { App } from 'vue';

// Import icon libraries
import '@quasar/extras/roboto-font/roboto-font.css';
import '@quasar/extras/mdi-v6/mdi-v6.css';
import '@quasar/extras/material-icons/material-icons.css';

// Import Quasar css
import 'quasar/src/css/index.sass';

export const installQuasar = (app: App<Element>) => {
    app.use(Quasar, { plugins: {} });
};

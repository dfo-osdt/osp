import { Quasar, Notify } from 'quasar';
import { App } from 'vue';

// Import icon libraries
import '@quasar/extras/roboto-font/roboto-font.css';
import '@quasar/extras/mdi-v6/mdi-v6.css';
import '@quasar/extras/material-icons/material-icons.css';

// Import Quasar css
import 'quasar/src/css/index.sass';

export const langs = import.meta.glob(
    '../../../node_modules/quasar/lang/(fr|en-US).mjs'
);

export const installQuasar = (app: App<Element>) => {
    app.use(Quasar, {
        plugins: {
            Notify,
        },
        config: {
            notify: {
                position: 'top',
            },
        },
    });
};

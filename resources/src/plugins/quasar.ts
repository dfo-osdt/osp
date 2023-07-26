import { Quasar, Notify, Dialog, LoadingBar } from 'quasar';
import { App } from 'vue';

// Import icon libraries
import '@quasar/extras/roboto-font/roboto-font.css';
import '@quasar/extras/mdi-v7/mdi-v7.css';
import '@quasar/extras/material-icons/material-icons.css';

// Import Quasar css
import 'quasar/src/css/index.sass';

export const langs = import.meta.glob(
    '../../../node_modules/quasar/lang/(fr|en-US).mjs',
);

export const installQuasar = (app: App<Element>) => {
    app.use(Quasar, {
        plugins: {
            Notify,
            Dialog,
            LoadingBar,
        },
        config: {
            notify: {
                position: 'top',
            },
            loadingBar: {
                color: 'primary',
                hijackFilter(url) {
                    // status is check periodically - no need to display loading bar as
                    // it could confuse user.
                    return !url.includes('/api/status?locale=');
                },
            },
        },
    });
};

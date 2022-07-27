import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { quasar } from '@quasar/vite-plugin';
import VueI18n from '@intlify/vite-plugin-vue-i18n';

export default defineConfig({
    plugins: [
        laravel(['resources/src/main.ts']),
        vue({
            reactivityTransform: true,
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        quasar({ sassVariables: 'resources/src/styles/variables.scss' }),

        // https://github.com/intlify/bundle-tools/tree/main/packages/vite-plugin-vue-i18n
        VueI18n({
            runtimeOnly: true,
            compositionOnly: true,
            include: [path.resolve(__dirname, 'resources/src/locales/**')],
        }),

        // Components({
        //     resolvers: [QuasarResolver()],
        // }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/src'),
        },
    },
    server: {
        host: 'osp.test',
    },
});

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import Components from 'unplugin-vue-components/vite';
import { QuasarResolver } from 'unplugin-vue-components/resolvers';
import { quasar } from '@quasar/vite-plugin';

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

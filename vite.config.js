import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';
import path from 'path';
import Components from 'unplugin-vue-components/vite';
import { VuetifyResolver } from 'unplugin-vue-components/resolvers';

export default defineConfig({
    plugins: [
        laravel(['resources/src/main.ts']),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            resolvers: [VuetifyResolver()],
        }),
    ],
    css: {
        additionalData: [
            '@import "./resources/src/styles/variables',
            '@import "vuetify/src/styles/settings/_variables"',
            '',
        ].join('\n'),
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/src'),
        },
    },
    server: {
        host: 'osp.test',
    },
});

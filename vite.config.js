import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { quasar } from '@quasar/vite-plugin';
import VueI18n from '@intlify/vite-plugin-vue-i18n';
import AutoImport from 'unplugin-auto-import/vite';

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
        quasar({ sassVariables: 'resources/src/styles/variables.scss' }),

        // https://github.com/intlify/bundle-tools/tree/main/packages/vite-plugin-vue-i18n
        VueI18n({
            runtimeOnly: true,
            compositionOnly: true,
            include: [path.resolve(__dirname, 'resources/src/locales/**')],
        }),

        // https://github.com/antfu/unplugin-auto-import
        AutoImport({
            imports: [
                'vue',
                'vue-router',
                'vue-i18n',
                'pinia',
                'vue/macros',
                '@vueuse/head',
                '@vueuse/core',
            ],
            dts: 'resources/src/auto-imports.d.ts',
            dirs: ['resources/src/composables/**', 'resources/src/store'],
            vueTemplate: true,
        }),
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

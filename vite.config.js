import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { quasar } from '@quasar/vite-plugin';
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite';
import AutoImport from 'unplugin-auto-import/vite';
import { homedir } from 'os';
import { resolve } from 'path';
import fs from 'fs';
import manifestSRI from 'vite-plugin-manifest-sri';

export default defineConfig({
    plugins: [
        laravel(['resources/src/main.ts']),
        manifestSRI(),
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
        VueI18nPlugin({
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
            dirs: ['resources/src/composables/**', 'resources/src/stores'],
            vueTemplate: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/src'),
        },
    },
    server: detectServerConfig('osp.test'),
});

// see: Making Vite and Valet play nice together
// https://freek.dev/2276-making-vite-and-valet-play-nice-together
function detectServerConfig(host) {
    let keyPath = resolve(homedir(), `.config/valet/Certificates/${host}.key`);
    let certificatePath = resolve(
        homedir(),
        `.config/valet/Certificates/${host}.crt`
    );

    if (!fs.existsSync(keyPath)) {
        return {};
    }

    if (!fs.existsSync(certificatePath)) {
        return {};
    }

    return {
        hmr: { host },
        host,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        },
    };
}

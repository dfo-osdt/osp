import path from 'node:path'
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite'
import { quasar } from '@quasar/vite-plugin'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import AutoImport from 'unplugin-auto-import/vite'
import { defineConfig } from 'vite'
import manifestSRI from 'vite-plugin-manifest-sri'
import vueDevtools from 'vite-plugin-vue-devtools'

export default defineConfig({
  plugins: [
    vueDevtools({
      appendTo: 'resources/src/main.ts',
    }),
    laravel(['resources/src/main.ts']),
    manifestSRI(),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
      script: {
        defineModel: true,
      },
    }),

    quasar({
      sassVariables: path.resolve(import.meta.dirname, 'resources/src/styles/variables.scss'),
    }),

    // https://github.com/intlify/bundle-tools/tree/main/packages/vite-plugin-vue-i18n
    VueI18nPlugin({
      runtimeOnly: true,
      compositionOnly: true,
      include: [path.resolve(import.meta.dirname, 'resources/src/locales/**')],
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
      viteOptimizeDeps: false,
    }),
  ],
  resolve: {
    alias: {
      '@': path.resolve(import.meta.dirname, './resources/src'),
    },
  },
})

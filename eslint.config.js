import antfu from '@antfu/eslint-config'
import vueI18n from '@intlify/eslint-plugin-vue-i18n'
import quasar from 'eslint-plugin-quasar'

export default antfu(
  {
    vue: true,
    typescript: true,
    // Stylistic rules configuration
    stylistic: {
      indent: 2,
      quotes: 'single',
    },
    // Ignore patterns
    ignores: ['**/fixtures/**', '**/dist/**', '**/public/**'],
  },
  {
    plugins: {
      quasar,
    },
  },
  // Vue i18n specific configuration
  {
    name: 'vue-i18n',
    plugins: {
      '@intlify/vue-i18n': vueI18n,
    },
    settings: {
      'vue-i18n': {
        localeDir: './resources/src/locales/*.json',
        messageSyntaxVersion: '^11.0.0',
      },
    },
    rules: {
      // Core Vue i18n rules
      '@intlify/vue-i18n/no-unused-keys': [
        'error',
        {
          src: './resources/src',
          extensions: ['.js', '.ts', '.vue'],
          enableFix: true,
        },
      ],
      '@intlify/vue-i18n/no-missing-keys': 'warn',
      '@intlify/vue-i18n/no-dynamic-keys': 'warn',
      '@intlify/vue-i18n/no-missing-keys-in-other-locales': 'warn',

      // Best practices
      '@intlify/vue-i18n/no-raw-text': [
        'error',
        {
          attributes: {
            '/.+/': [
              'title',
              'aria-label',
              'aria-placeholder',
              'aria-roledescription',
              'aria-valuetext',
            ],
            'input': ['placeholder'],
            'img': ['alt'],
          },
          ignoreNodes: ['md-icon', 'v-icon'],
          ignorePattern: '^[-#:()&,;0-9|*.]+$',
          ignoreText: ['DOI', 'URL'],
        },
      ],
      '@intlify/vue-i18n/key-format-style': [
        'error',
        'kebab-case',
        {
          allowArray: false,
        },
      ],

      // Vue-specific rules
      '@intlify/vue-i18n/no-v-html': 'warn',
      '@intlify/vue-i18n/valid-message-syntax': 'error',
      '@intlify/vue-i18n/no-duplicate-keys-in-locale': 'error',
    },
    files: ['**/*.{vue,js,ts}'],
  },
  // Vue i18n JSON locale files configuration
  {
    name: 'vue-i18n-json',
    plugins: {
      '@intlify/vue-i18n': vueI18n,
    },
    settings: {
      'vue-i18n': {
        localeDir: './resources/src/locales/*.json',
        messageSyntaxVersion: '^11.0.0',
      },
    },
    rules: {
      '@intlify/vue-i18n/no-unused-keys': [
        'error',
        {
          src: './resources/src',
          extensions: ['.js', '.ts', '.vue'],
          enableFix: true,
        },
      ],
      '@intlify/vue-i18n/no-duplicate-keys-in-locale': 'error',
      '@intlify/vue-i18n/valid-message-syntax': 'error',
    },
    files: ['**/locales/*.json'],
  },
)

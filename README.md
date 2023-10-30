# DFO Open Science Portal
A web-based application to promote open science and track science publications within Fisheries and Oceans Canada.
## Contribution Guidelines
For now, we'll use spatie.be's excellent [guidelines](https://spatie.be/guidelines).
Additional guidelines will be added here as we progress.

## Built With
This repo has both the front end and backend code. The front end is a SPA that consumes the API backend.
### Front-end Stack (Single Page Application)
- [TypeScript](https://www.typescriptlang.org/)
- [Vue.js](https://vuejs.org/) (TS with Composition API `script setup`)
- [Vue-i18n](https://vue-i18n.intlify.dev/) (app supports en-CA and fr-CA, with vite-plugin-vue-i18n, `globalInjection: true`)
- [Vue-Router](https://router.vuejs.org/) (SPA routing)
- [Pinia](https://pinia.vuejs.org/) (State management)
- [Quasar](https://quasar.dev/) (Vite Plugin flavour - Vue.js framework component library)

Some helper libraries of note here:
- [VueUse](https://vueuse.org/) (Collection of Vue Composition Utilities)
- [unplugin-auto-import](https://github.com/antfu/unplugin-auto-import)

### Back-end Stack
- [PHP 8.1](https://www.php.net/)
- [Laravel](https://laravel.com/) (framework)
- [Laravel Sanctum](https://laravel.com/docs/9.x/sanctum) (auth)

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

- [PHP 8.3](https://www.php.net/)
- [Laravel](https://laravel.com/) (framework)
- [Laravel Sanctum](https://laravel.com/docs/9.x/sanctum) (auth)

## Contributions

All changes must be done via a PR to the `main` branch. PR should be descriptive and provide
reference to any issues as required.

For commits, messages, use [Coventional Commits](https://www.conventionalcommits.org/en/v1.0.0/)

The commit message should be structured as follows:

```text
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Commit Types

```js
[
  'build',
  'chore',
  'ci',
  'docs',
  'feat',
  'fix',
  'perf',
  'refactor',
  'revert',
  'style',
  'test'
]
```

## Running Tests Locally

### Backend Tests

```sh
php artisan test
```

### Frontend Tests

We use Cypress for the front-end E2E tests. It must run with the
`env.ci` environment.

Before starting the test, start the dev server. For the frontend,
you can either use `pnpnm dev` or `pnmp build`. In most cases, `dev`
is better as changes to the code can instantently be tested again.

```sh
pnpm dev
php artisan server --env=ci
```

Once the local server is up and running, you can launch Cypress
with `pnpm cy:open` or just run the tests with `pnpm cy:run`

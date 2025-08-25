# DFO Open Science Portal

A web-based application to promote open science and track science publications within Fisheries and Oceans Canada.

## Contribution Guidelines

For now, we'll use spatie.be's excellent [guidelines](https://spatie.be/guidelines).
Additional guidelines will be added here as we progress.

## Get Started

Before you begin, ensure you have the following prerequisites installed:

- [PHP 8.3](https://www.php.net/) (see [php.net](https://www.php.net/))
- [WSL](https://docs.microsoft.com/en-us/windows/wsl/install) if you are on Windows
- [Redis](https://redis.io/)
- [MySQL](https://www.mysql.com/) (to mimic production, though SQLite can be used for development)

1. Install dependencies:

   ```bash
   composer install  # This will automatically publish Filament assets
   pnpm install
   ```

2. Set up environment variables:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Run database migrations:

   ```bash
   php artisan migrate
   ```

4. Start the development servers:

   ```bash
   composer run dev
   ```

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

For commits, messages, use [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/)

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
  'test',
];
```

## Running Tests Locally

### Backend Tests

```sh
composer run test
```

### Frontend Tests

We use Cypress for the front-end E2E tests. It must run with the
`env.ci` environment.

Before starting the test, start the dev server. For the frontend,
you can either use `pnpnm dev` or `pnmp build`. In most cases, `dev`
is better as changes to the code can instantly be tested again.

```sh
pnpm dev
php artisan serve --env=ci
```

Once the local server is up and running, you can launch Cypress
with `pnpm cy:open` or just run the tests with `pnpm cy:run`

If using WSL, you will need to follow this [guide](https://learn.microsoft.com/en-us/windows/wsl/tutorials/gui-apps) first.

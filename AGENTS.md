# AGENTS.md

## Build & Test Commands

- **Run tests**: `composer test` (runs lint, types, feature tests, and browser tests)
- **Single test**: `./vendor/bin/pest --filter="TestName"` or `./vendor/bin/pest tests/Feature/TestFile.php`
- **Browser tests**: `./vendor/bin/pest tests/Browser/` (Pest4 automated browser testing)
- **Lint PHP**: `composer lint` (Laravel Pint)
- **Type check**: `composer test:types` (PHPStan level 5)
- **Lint JS/Vue**: `pnpm lint` (ESLint with Antfu config)
- **Build**: `pnpm build` (Vite)
- **Dev server**: `composer dev` (Laravel + Horizon + Pail + Vite in parallel)

## Code Style

- **PHP**: Use Laravel Pint (excluded: lang/, .phpstorm.meta.php, \_ide_helper.php)
- **Vue/TS**: ESLint with @antfu/eslint-config + Quasar plugin - **NO SEMICOLONS** (auto-fixed by ESLint)
- **Imports**: Group by vendor/package, then local files
- **Types**: Full PHPDoc blocks for models, strict TypeScript for Vue
- **Naming**: snake_case (PHP), camelCase (JS/Vue), PascalCase (Vue components)
- **Error handling**: Use Laravel exceptions, validate with form requests
- **Semicolons**: NEVER add semicolons to TypeScript/Vue code - the project uses ESLint with @antfu/eslint-config which removes them automatically

## Framework Stack

- **Backend**: Laravel 12 + PHP 8.4 + Filament admin + Pest testing (including Pest4 browser tests)
- **Frontend**: Vue 3 + TypeScript + Quasar + Pinia + Vue Router
- **Database**: SQLite (testing), activity logging, permissions via Spatie

## Internationalization (i18n)

- **Backend i18n**: Laravel translation files in `lang/en/` and `lang/fr/` directories (PHP arrays)
- **Frontend i18n**: Vue i18n JSON files at `resources/src/locales/en.json` and `fr.json`
- **Usage**: Frontend uses `$t('key.path')` in templates, `t('key.path')` in script sections
- **ESLint rules**: `@intlify/vue-i18n/no-raw-text` prevents hardcoded text, `@intlify/vue-i18n/no-missing-keys` validates translation keys exist

## Core Laravel Principle

**Follow Laravel conventions first.** If Laravel has a documented way to do something, use it. Only deviate when you have a clear justification.

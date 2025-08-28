# AGENTS.md

## Build & Test Commands

- **Run tests**: `composer test` (runs lint, types, and feature tests)
- **Single test**: `./vendor/bin/pest --filter="TestName"` or `./vendor/bin/pest tests/Feature/TestFile.php`
- **Lint PHP**: `composer lint` (Laravel Pint)
- **Type check**: `composer test:types` (PHPStan level 5)
- **Lint JS/Vue**: `pnpm lint` (ESLint with Antfu config)
- **Build**: `pnpm build` (Vite)
- **Dev server**: `composer dev` (Laravel + Horizon + Pail + Vite in parallel)

## Code Style

- **PHP**: Use Laravel Pint (excluded: lang/, .phpstorm.meta.php, \_ide_helper.php)
- **Vue/TS**: ESLint with @antfu/eslint-config + Quasar plugin
- **Imports**: Group by vendor/package, then local files
- **Types**: Full PHPDoc blocks for models, strict TypeScript for Vue
- **Naming**: snake_case (PHP), camelCase (JS/Vue), PascalCase (Vue components)
- **Error handling**: Use Laravel exceptions, validate with form requests

## Framework Stack

- **Backend**: Laravel 12 + PHP 8.4 + Filament admin + Pest testing
- **Frontend**: Vue 3 + TypeScript + Quasar + Pinia + Vue Router
- **Database**: SQLite (testing), activity logging, permissions via Spatie

## Core Laravel Principle

**Follow Laravel conventions first.** If Laravel has a documented way to do something, use it. Only deviate when you have a clear justification.

# Project Guidelines

## Stack & Versions

**Backend:** Laravel 12 (PHP 8.4), Filament v4, Livewire v3, Pest v4, Horizon, SQLite (testing)  
**Frontend:** Vue 3 + TypeScript + Quasar + Pinia, ESLint v9 (@antfu/eslint-config)

## Commands

- **Test:** `composer test` or `./vendor/bin/pest --filter="TestName"`
- **Browser tests:** `./vendor/bin/pest tests/Browser/`
- **Lint:** `composer lint` (Pint), `pnpm lint` (ESLint)
- **Build:** `pnpm build` | **Dev:** `composer dev`
- **Database:** `php artisan migrate` | `php artisan db:seed`

## Core Principles

1. **Follow Laravel conventions.** Research patterns in existing codebase before making changes.
2. **Every change must be tested.** Write/update tests, then run them to verify.
3. **Check sibling files** for existing conventions before creating new files.
4. **Test-driven development:** Run tests after every change to catch regressions early.

## Code Style

### PHP

- Constructor property promotion: `public function __construct(public GitHub $github) {}`
- Explicit return types: `protected function foo(User $user): bool`
- Form Requests for validation (check siblings for array vs string rules)
- Curly braces even for single-line control structures
- Run `vendor/bin/pint --dirty` before finalizing
- Prefer PHPDoc over inline comments

### JavaScript/Vue/TypeScript

- **NO SEMICOLONS in TS/JS/Vue files** (ESLint @antfu/eslint-config removes them automatically)
- Naming: camelCase (JS/Vue), PascalCase (components), snake_case (PHP)
- Group imports: vendor/package first, then local files

### i18n

- **Backend:** `lang/en/` and `lang/fr/` (PHP arrays)
- **Frontend:** `resources/src/locales/en.json` and `fr.json`
- **Usage:** `$t('key.path')` (template), `t('key.path')` (script)
- ESLint enforces translation keys exist and prevents raw text

## Laravel Project-Specific

### Structure (Laravel 10 style - NOT yet migrated to Laravel 11+ structure)

- **Middleware:** `app/Http/Middleware/`, registered in `app/Http/Kernel.php` (NOT in `bootstrap/app.php`)
- **Service Providers:** `app/Providers/` (NOT consolidated into `bootstrap/app.php`)
- **Scheduled tasks:** `app/Console/Kernel.php` (NOT in `routes/console.php`)
- **Note:** This project uses Laravel 12 but retains the L10 structure. Migration to L11+ structure is planned but not prioritized.
- Models use `casts()` method, not `$casts` property

### Database & Models

- Use Eloquent relationships with type hints
- Eager load to prevent N+1 queries
- When modifying columns, include ALL previous attributes or they'll be lost
- Use factories in tests (check for custom states first)

### Testing with Pest

- All tests use Pest: `php artisan make:test --pest <name>`
- Use specific assertions: `assertForbidden()` not `assertStatus(403)`
- Browser tests in `tests/Browser/`
- Use datasets for repetitive test data
- Never remove tests without approval

### Filament v4

- Use Filament artisan commands with `--no-interaction`
- Actions extend `Filament\Actions\Action` (not `Filament\Tables\Actions`)
- Layout components moved to `Filament\Schemas\Components`
- Use `relationship()` method on form components for selects/checkboxes
- Test with `livewire(ListUsers::class)->assertCanSeeTableRecords($users)`
- `deferFilters()` is now default (users click button to apply filters)

### Livewire v3

- `wire:model` is deferred by default, use `wire:model.live` for real-time
- Namespace: `App\Livewire` (not `App\Http\Livewire`)
- Events: `$this->dispatch()` (not `emit`)
- Alpine is included with Livewire

### Logging

- Use `activity()` helper from Spatie Activity Log for application events
- Prefer `activity()` over `Log::` for better auditing and database storage
- Include contextual properties with `->withProperties([...])`
- Example: `activity()->withProperties(['date' => now()])->log('Event occurred')`
- Activity logs stored in `activity_log` table, queryable through application

## Other

- Use `config()` not `env()` outside config files
- Use named routes: `route('home')` not hardcoded paths
- No verification scripts if tests exist
- No documentation files unless explicitly requested
- If frontend changes don't appear, ask user to run `pnpm build` or `composer dev`

===

<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

## Foundational Context

This application is a Laravel application running on PHP 8.5. Always use the APIs that match the installed major version of each package — do not assume a version.

Before relying on a package's API, confirm its installed version:
- PHP packages: run `composer show --direct` to list direct dependencies with versions, or `composer show <vendor/package>` for a single package.
- JS packages: check `package.json` for the installed versions.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If a frontend change doesn't show in the UI or you get a "Unable to locate file in Vite manifest" error, run `pnpm run build` or ask the user to run `pnpm run dev` or `composer run dev`.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Use `search-docs` before changes that depend on Laravel ecosystem APIs, behavior, configuration, or version-specific syntax. Skip it for copy-only edits and other changes where package documentation is irrelevant. Reuse sufficient results already in context instead of searching again.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Project Rules

- This project contains committed, area-grouped rules in `.ai/rules` when that directory exists, including path-scoped framework guidelines under `.ai/rules/boost`. Before you enter plan mode or create/edit any file, you MUST first: open @.ai/rules/index.md (it maps file globs to rule files), read every rule file whose globs cover the path(s) in scope, and run `grep -rin 'keyword' .ai/rules` to catch what a path match alone misses. Do not write code until you have read and are following every matching rule. If `.ai/rules` does not exist, continue without it.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Follow existing application Enum naming conventions.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== tests rules ===

# Test Enforcement

- Add or update tests for behavior and logic changes when a test provides meaningful regression coverage.
- Pure copy, styling, and layout-only changes do not require new or updated tests.
- When test coverage applies, run the affected tests and ensure they pass.
- Test the changed behavior and its important failure modes, but do not add tests beyond them.
- Read the `testing-best-practices` skill before writing tests.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== livewire/core rules ===

# Livewire

- Livewire allows you to build dynamic, reactive interfaces in PHP without writing JavaScript.
- You can use Alpine.js for client-side interactions instead of JavaScript frameworks.
- Keep state server-side so the UI reflects it. Validate and authorize in actions as you would in HTTP requests.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

# Pest

- This project uses Pest. Create tests with `php artisan make:test --pest {name}`.
- Do not include the test suite directory in `{name}`. Use `SomeFeatureTest`, not `Feature/SomeFeatureTest`.
- Read the `testing-best-practices` skill for guidance on coverage, naming, structure, dependency isolation, and review.
- Do not delete tests or test files without approval. They are part of the application.

## Running Tests

- Run the narrowest set of tests that covers the change. Pass a file path or `--filter=testName` to `php artisan test --compact`.
- Rerun a test after each change to it.
- Run `vendor/bin/pest` to call the test runner directly. It accepts the same file path and `--filter=testName` arguments.
- After the feature tests pass, ask the user to run the complete suite with `php artisan test --compact`.

=== maatwebsite/excel/core rules ===

# Laravel Excel

- Use `maatwebsite/excel` for spreadsheet exports, imports, queued spreadsheet work, CSV handling, and PhpSpreadsheet integration in Laravel applications.
- Prefer explicit export/import classes with package concerns over ad-hoc spreadsheet generation in controllers, jobs, or commands.
- Activate the `laravel-excel` skill when working with `Excel::download()`, `Excel::store()`, `Excel::queue()`, `Excel::raw()`, `Excel::import()`, `Excel::toArray()`, `Excel::toCollection()`, export/import concerns, queued imports/exports, validation, CSV settings, styling, events, formulas, charts, drawings, multiple sheets, mapped cells, macros, config, cache, transactions, temporary files, or `Excel::fake()`.
- For broad docs, all-feature tasks, or missing-feature audits, use the skill's `references/package.md` feature matrix before answering.
- For large datasets, prefer `FromQuery` with queued exports or `WithChunkReading` and `WithBatchInserts` for imports.
- Test spreadsheet behavior with `Excel::fake()` when asserting dispatch/download/store/import intent, and inspect generated files only when cell contents, formatting, sheets, or writer behavior must be proven.

=== saloonphp/laravel-plugin/core rules ===

## SaloonPHP

- PHP library for building API integrations and SDKs.
- Documentation: https://docs.saloon.dev
- Check `composer.json` for version (v2, v3 or v4). Use `web-search` tool for latest docs before implementing.
- If the project uses v3, inform the user that v3 is affected by three published security issues and prefer upgrading to v4 before adding new Saloon work, unless the user explicitly needs a v3-compatible change.
- Always use Artisan commands to generate SaloonPHP classes: `php artisan saloon:connector`, `php artisan saloon:request`, `php artisan saloon:response`, `php artisan php artisan saloon:plugin`, `php artisan saloon:auth`.- Documentation: `https://docs.saloon.dev`
- IMPORTANT: Activate saloon-development skill when working with SaloonPHP-related tasks.

=== spatie/laravel-activitylog/core rules ===

## Activitylog

- `spatie/laravel-activitylog` logs model events and manual activities to a database table, with subjects, causers, tracked attribute changes, and custom properties.
- Always activate the `activitylog-development` skill when working with activity logging or any code that uses the `LogsActivity`, `CausesActivity`, or `HasActivity` traits, `LogOptions`, the `Activity` model, or the `activity()` helper.
- If the skill is unavailable, read `vendor/spatie/laravel-activitylog/resources/boost/skills/activitylog-development/SKILL.md` before making changes.

=== spatie/laravel-medialibrary/core rules ===

## Media Library

- `spatie/laravel-medialibrary` associates files with Eloquent models, with support for collections, conversions, and responsive images.
- Always activate the `medialibrary-development` skill when working with media uploads, conversions, collections, responsive images, or any code that uses the `HasMedia` interface or `InteractsWithMedia` trait.

</laravel-boost-guidelines>

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

### Structure (Laravel 10 style - NOT migrated to Laravel 11+)

- Middleware: `app/Http/Middleware/`, registered in `app/Http/Kernel.php`
- Providers: `app/Providers/`
- No `bootstrap/app.php` - use Kernel files instead
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

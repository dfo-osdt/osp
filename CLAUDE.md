<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.17
- filament/filament (FILAMENT) - v5
- laravel/framework (LARAVEL) - v12
- laravel/horizon (HORIZON) - v5
- laravel/prompts (PROMPTS) - v0
- laravel/pulse (PULSE) - v1
- laravel/sanctum (SANCTUM) - v4
- laravel/socialite (SOCIALITE) - v5
- laravel/telescope (TELESCOPE) - v5
- livewire/livewire (LIVEWIRE) - v4
- larastan/larastan (LARASTAN) - v3
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- rector/rector (RECTOR) - v2
- vue (VUE) - v3
- eslint (ESLINT) - v9
- prettier (PRETTIER) - v3

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `livewire-development` — Develops reactive Livewire 4 components. Activates when creating, updating, or modifying Livewire components; working with wire:model, wire:click, wire:loading, or any wire: directives; adding real-time updates, loading states, or reactivity; debugging component behavior; writing Livewire tests; or when the user mentions Livewire, component, counter, or reactive UI.
- `pest-testing` — Tests applications using the Pest 4 PHP framework. Activates when writing tests, creating unit or feature tests, adding assertions, testing Livewire components, browser testing, debugging test failures, working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion, coverage, or needs to verify functionality works.

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

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `pnpm run build`, `pnpm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `pnpm run build` or ask the user to run `pnpm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- This project upgraded from Laravel 10 without migrating to the new streamlined Laravel file structure.
- This is perfectly fine and recommended by Laravel. Follow the existing structure from Laravel 10. We do not need to migrate to the new Laravel structure unless the user explicitly requests it.

## Laravel 10 Structure

- Middleware typically lives in `app/Http/Middleware/` and service providers in `app/Providers/`.
- There is no `bootstrap/app.php` application configuration in a Laravel 10 structure:
    - Middleware registration happens in `app/Http/Kernel.php`
    - Exception handling is in `app/Exceptions/Handler.php`
    - Console commands and schedule register in `app/Console/Kernel.php`
    - Rate limits likely exist in `RouteServiceProvider` or `app/Http/Kernel.php`

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== livewire/core rules ===

# Livewire

- Livewire allows you to build dynamic, reactive interfaces using only PHP — no JavaScript required.
- Instead of writing frontend code in JavaScript frameworks, you use Alpine.js to build the UI when client-side interactions are required.
- State lives on the server; the UI reflects it. Validate and authorize in actions (they're like HTTP requests).
- IMPORTANT: Activate `livewire-development` every time you're working with Livewire-related tasks.

=== pint/core rules ===

# Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.
- CRITICAL: ALWAYS use `search-docs` tool for version-specific Pest documentation and updated code examples.
- IMPORTANT: Activate `pest-testing` every time you're working with a Pest or testing-related task.

=== saloonphp/laravel-plugin rules ===

## SaloonPHP

- SaloonPHP is a PHP library for building beautiful, maintainable API integrations and SDKs with a fluent, expressive API.
- Uses a connector-based architecture where **Connectors** define the base URL and shared configuration, and **Requests** represent specific API endpoints.
- **Version Support**: SaloonPHP v2 and v3 are both actively supported. Check `composer.json` to determine which version-specific documentation to reference.
- Always use Artisan commands to generate SaloonPHP classes: `php artisan saloon:connector`, `php artisan saloon:request`, `php artisan saloon:response`, `php artisan saloon:plugin`, `php artisan saloon:auth`.
- Documentation: `https://docs.saloon.dev`
- **Before implementing features, use the `web-search` tool to get the latest docs. The docs listing is available in <available-docs>**

### Key Concepts

- **Connectors**: Extend `Saloon\Http\Connector`, define base URL via `resolveBaseUrl()`, use constructor property promotion for dependencies, override `defaultHeaders()` and `defaultAuth()`.
- **Requests**: Extend `Saloon\Http\Request`, set `$method` using `Saloon\Enums\Method` enum, override `resolveEndpoint()`, `defaultQuery()`, `defaultHeaders()`, `defaultBody()`.
- **Sending**: `$connector->send($request)` returns a response with methods like `json()`, `body()`, `status()`, `isSuccess()`, `dto()`, `dtoOrFail()`.
- **Body Types**: Implement `HasBody` interface and use traits: `HasJsonBody`, `HasXmlBody`, `HasMultipartBody`, `HasFormBody`, `HasStringBody`, `HasStreamBody`.
- **Authentication**: Use `TokenAuthenticator`, `BasicAuthenticator`, `QueryAuthenticator`, or implement `Saloon\Contracts\Authenticator`.
- **Plugins**: Traits that add reusable functionality. Built-in: `AcceptsJson`, `AlwaysThrowOnErrors`, `HasTimeout`, `HasRetry`, `HasRateLimit`, `WithDebugData`, `DisablesSSLVerification`, `CastsToDto`.
- **Middleware**: Use `middleware()->onRequest()` and `middleware()->onResponse()`, or implement `boot()` method.
- **DTOs**: Implement `createDtoFromResponse()` in request classes, use `$response->dto()` or `$response->dtoOrFail()`.

### Laravel Integration

- **Artisan Commands**: `saloon:connector`, `saloon:request`, `saloon:response`, `saloon:plugin`, `saloon:auth`, `saloon:list`.
- **Facade**: Use `Saloon\Laravel\Facades\Saloon` facade for mocking: `Saloon::fake([RequestClass::class => MockResponse::make(...)])`.
- **Events**: `SendingSaloonRequest` and `SentSaloonRequest` events are emitted during request lifecycle.
- **HTTP Client Sender**: Use `Saloon\Laravel\HttpSender` to integrate with Laravel's HTTP client (enables Telescope recording). Configure in `config/saloon.php`: `'default_sender' => \Saloon\Laravel\HttpSender::class`.
- **File Structure**: Check `config/saloon.php` for `integrations_path` setting. Default is `app/Http/Integrations`. Store connectors/requests in `{integrations_path}/{ServiceName}/` directory.

### Version Notes (v3)

- Global retry system: Set `$tries`, `$retryInterval`, `$useExponentialBackoff` properties directly on connectors/requests.
- Pagination is now a separate installable plugin (required for pagination features).
- Enhanced PSR-7 support with new response methods.

<available-docs>

## Upgrade

- [https://docs.saloon.dev/upgrade/whats-new-in-v3] Use these docs to understand what's new in SaloonPHP v3
- [https://docs.saloon.dev/upgrade/upgrading-from-v2] Use these docs for upgrading from SaloonPHP v2 to v3

## The Basics

- [https://docs.saloon.dev/the-basics/installation] Use these docs for installation instructions, Composer setup, and initial configuration
- [https://docs.saloon.dev/the-basics/connectors] Use these docs for creating connectors, setting base URLs, default headers, and shared configuration
- [https://docs.saloon.dev/the-basics/requests] Use these docs for creating requests, defining endpoints, HTTP methods, query parameters, and request bodies
- [https://docs.saloon.dev/the-basics/authentication] Use these docs for authentication methods including token, basic, OAuth2, and custom authenticators
- [https://docs.saloon.dev/the-basics/request-body-data] Use these docs for sending body data in requests, including JSON, XML, and multipart form data
- [https://docs.saloon.dev/the-basics/sending-requests] Use these docs for sending requests through connectors, handling responses, and request lifecycle
- [https://docs.saloon.dev/the-basics/responses] Use these docs for handling responses, accessing response data, status codes, and headers
- [https://docs.saloon.dev/the-basics/handling-failures] Use these docs for handling failed requests, error responses, and using AlwaysThrowOnErrors trait
- [https://docs.saloon.dev/the-basics/debugging] Use these docs for debugging requests and responses, using the debug() method, and inspecting PSR-7 requests
- [https://docs.saloon.dev/the-basics/testing] Use these docs for testing Saloon integrations, mocking requests, and writing assertions

## Digging Deeper

- [https://docs.saloon.dev/digging-deeper/data-transfer-objects] Use these docs for casting API responses into DTOs, creating DTOs from responses, implementing WithResponse interface, and using DTOs in requests
- [https://docs.saloon.dev/digging-deeper/building-sdks] Use these docs for building SDKs with Saloon, creating resource classes, and organizing API integrations
- [https://docs.saloon.dev/digging-deeper/solo-requests] Use these docs for creating standalone requests without connectors using SoloRequest class
- [https://docs.saloon.dev/digging-deeper/retrying-requests] Use these docs for implementing retry logic with exponential backoff and custom retry strategies (v3 includes global retry system at connector level)
- [https://docs.saloon.dev/digging-deeper/delay] Use these docs for adding delays between requests to prevent rate limiting and server overload
- [https://docs.saloon.dev/digging-deeper/concurrency-and-pools] Use these docs for sending concurrent requests using pools, managing multiple API calls efficiently, and asynchronous request handling
- [https://docs.saloon.dev/digging-deeper/oauth2-authentication] Use these docs for OAuth2 authentication flows including Authorization Code Grant, Client Credentials, and token refresh
- [https://docs.saloon.dev/digging-deeper/middleware] Use these docs for creating and using middleware to modify requests and responses, request lifecycle hooks, and boot methods
- [https://docs.saloon.dev/digging-deeper/psr-support] Use these docs for PSR-7 and PSR-17 support, accessing PSR requests and responses, and modifying PSR-7 requests

## Installable Plugins

- [https://docs.saloon.dev/installable-plugins/pagination] Use these docs for the Pagination plugin to handle paginated API responses with various pagination methods (required in v3, optional in v2)
- [https://docs.saloon.dev/installable-plugins/laravel-integration] Use these docs for Laravel plugin features including Artisan commands, facade, events, and HTTP client sender
- [https://docs.saloon.dev/installable-plugins/caching-responses] Use these docs for the Caching plugin to cache API responses and improve performance
- [https://docs.saloon.dev/installable-plugins/handling-rate-limits] Use these docs for the Rate Limit Handler plugin to prevent and manage rate limits
- [https://docs.saloon.dev/installable-plugins/sdk-generator] Use these docs for the Auto SDK Generator plugin to generate Saloon SDKs from OpenAPI files or Postman collections
- [https://docs.saloon.dev/installable-plugins/lawman] Use these docs for the Lawman plugin, a PestPHP plugin for writing architecture tests for API integrations
- [https://docs.saloon.dev/installable-plugins/xml-wrangler] Use these docs for the XML Wrangler plugin for modern XML reading and writing with dot notation and XPath queries
- [https://docs.saloon.dev/installable-plugins/building-your-own-plugins] Use these docs for building custom plugins (traits), creating boot methods, and extending Saloon functionality
</available-docs>
</laravel-boost-guidelines>

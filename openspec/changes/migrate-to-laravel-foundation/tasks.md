Task groups follow the numbering of `migrate-to-symfony-foundation/tasks.md`, so each phase can be compared directly. "Reuse" means cherry-picking the Symfony variant's framework-agnostic commit; anything that has to change in it is recorded in `docs/dx-comparison.md`.

## 0. Plan

- [x] 0.1 Create branch `lara-3` from `89bc13930` (the parent of `7d22797d0`)
- [x] 0.2 Write this change (proposal, design, specs, tasks) and start `docs/dx-comparison.md`
- [x] 0.3 Get approval for L4 (fallback route), L7 (database owner) and L10 (session bridge)

## 1. Test infrastructure (L16; Symfony 1.x)

- [x] 1.1 Reuse `2faac47a9` (pin the `htdocs/` Composer platform to PHP 8.2)
- [x] 1.2 Reuse `86f94663b` (root `composer.json` with test tooling, `phpunit.xml.dist` with the five suites, `bin/test-db`, snapshot restore, `ServerProcess`, `HttpTestCase`, `IsolatedLegacyTestCase`, `bin/spec-coverage`, CI workflow, smoke tests). Leave out its `openspec/` hunk
- [x] 1.3 Run `bin/test-db` and every suite against plain `2.1-dev`; record the suite timings
  - Library 413 tests 1.3 s, Unit 0.6 s, Integration 5.5 s, Functional 8.8 s, Http 66 s (15 tests); `bin/test-db` 22 s

## 2. Characterization baseline (L15; Symfony 2.x)

- [x] 2.1 The snapshots of `86f94663b` and `98555bb46` (closed site) pass against `htdocs/` unchanged

## 3. Library seams in `Icms\` (Symfony 3.x)

- [x] 3.1 Reuse `e01c18bd0` (autoload guard)
- [x] 3.2 Reuse `a498cc573` and `42c344408` (`Icms\Kernel\Bootstrap`, headless mode, exceptions, isolated tests)
- [x] 3.3 Reuse `e3bbb613e` (`Icms\Db\Factory::setConnectionProvider()`)
- [x] 3.4 Reuse `ff813c6c3` (`Icms\Event::setForwarder()`)
- [x] 3.5 The `Library` suite passes under `htdocs/composer.json` and under the root autoloader; the snapshots are unchanged
  - The seams were reused without a change. One later fix from the Symfony variant's final commit (`94141e7cf`, an uninitialised variable in `Bootstrap`) is its own commit

## 4. Root Composer and Laravel host (L1–L5; Symfony 4.x)

- [x] 4.1 Extend the root `composer.json`: `laravel/framework` `^12.70`, the `htdocs/composer.json` requirements, `php` `>=8.2 <8.5`, autoload `App\` → `app/`, `Icms\`, the `icms` classmap and `files: htdocs/libraries/Autoloader.php`
- [x] 4.2 Keep `composer/composer` in `require` and verify it resolves against the Symfony components Laravel 12 requires; keep the lock-alignment check
  - `bin/check-lock-alignment` comes from `3c1d278a1`; `htdocs/composer.lock` was updated to the versions Laravel 12 resolves
- [x] 4.3 Run the alias-map tests under the root autoloader, modern name first and legacy name first
- [x] 4.4 Add the helper-collision test (L3)
- [x] 4.5 Create `bootstrap/app.php` (`useStoragePath(var)`, `.env.local`, middleware: remove `TrimStrings`/`ConvertEmptyStringsToNull`, aliases), `bootstrap/providers.php`, `config/*.php`, `routes/web.php`, `artisan`, and `GET /up` (`text/plain`, `ok`)
  - Only `.env.local` (`loadEnvironmentFrom()`); defaults live in `config/*.php`. `withRouting(using:)` instead of `web:`, so the routes are outside the `web` group. `config/filesystems.php` turns off Laravel 12's `/storage/{path}` route
- [x] 4.6 Create `config/icms.php` (legacy root, front controller, asset extensions, MIME map, reserved prefixes, non-public directories)
- [x] 4.7 Port `LegacyScriptResolver`, `LegacyResolution`, `PreparedLegacyExecution`, `LegacyRequestHydrator` and `LegacyBridge` to `app/LegacyBridge/` from the Symfony design (not its code)
  - The hydrator fills the superglobals from the request as in the Symfony variant, except `$_POST` for JSON bodies (Laravel replaces its POST bag with the decoded JSON)
- [x] 4.8 Add the catch-all `Route::any('{path}')->fallback()` and `LegacyBridgeController` (file response for assets, `LegacyExecutionResponse` marker for scripts, 405 with `Allow` for paths owned by other methods)
- [x] 4.9 Add reserved-prefix routes from configuration; none by default
- [x] 4.10 Add a route validator and the `whereQuery()` macro; a test route claims `/modules/system/admin.php` for `fct=users`, other variants stay legacy
  - `RequestConditionValidator` with the macros `whereQuery()` and `matching()` (`when()` is taken by `Conditionable`). The compiled matcher of `route:cache` ignores route validators, so the host refuses cached routes instead
- [x] 4.11 Add `artisan icms:routes:ownership <url> [--method=]`
- [x] 4.12 Implement `public/index.php` and `App\Http\HostKernel`: file-scope `require`, `LegacyBridge::complete()`, `RequestHandled` and `terminate()` with the real response, the `exit()` shutdown path, suspended Laravel error handling during the script
  - `App\Runtime\HostRunner` drives the kernel from `public/index.php`. `HandleExceptions::forgetApp()` makes Laravel's error handling inert for the rest of a legacy request; PHP's own error settings, recorded before Laravel boots, apply while the script runs, and the host's handlers are unwound afterwards
- [x] 4.13 Run the characterization suite with `public/` as document root; record the time to the first green `Http` snapshot
  - Green on the first run through `public/`: all 37 `Http` tests, 2 min 4 s. The unit tests of the bridge were reused from the Symfony variant; one test changed because the bridge now restores the exception handler itself

## 5. Bridge hardening (legacy-request-bridge spec; Symfony 5.x)

- [x] 5.1 The hydrator never starts a session; `$_SESSION` is snapshotted and restored only when a session was active
- [x] 5.2 Reject `templates_c`, `cache`, `tests`, the `.htaccess`-protected directories, PHP under `uploads` and the trust path with 404
  - `templates_c`, `cache`, PHP under `uploads` and the trust path, as in the Symfony foundation; `tests/`, `trust_dist/` and the `.htaccess` directories follow with the relocation, as there
- [x] 5.3 Resolver tests for percent-encoded and backslash traversal and for symlinks out of the root
- [x] 5.4 Log bridge failures through the Laravel logger; generic 500 page outside debug, details only with `APP_DEBUG`
- [x] 5.5 Restore output-buffer level, working directory and superglobals in `finally` and on the shutdown path; test with unclosed nested buffers
- [x] 5.6 Characterization of a login POST with a custom session name and the DB session handler; failure responses in debug and non-debug mode against `tests/Fixtures/legacy-root`

## 6. Headless runtime in the host (L6; Symfony 6.x)

- [x] 6.1 `App\Legacy\LegacyRuntime` with an idempotent `bootTo()`
- [x] 6.2 Middleware alias `icms.legacy:<stage>`
- [x] 6.3 `SiteClosedException` → 503, `NotInstalledException` → redirect to `/install/` in `withExceptions()`
  - Laravel's redirector resolves the session store, so every error page did: with the skeleton's `database` session, cache and queue drivers that booted legacy for a 404. The drivers are `array`, `file` and `sync` until L10
- [x] 6.4 Tests: `/up` boots nothing and opens no connection; a test route returns `sitename` without theme output; a closed site gives 503 without terminating the process

## 7. Shared database connection (L7; Symfony 7.x)

- [x] 7.1 Connection `icms` in `config/database.php` from `XOOPS_DB_*` after the `constants` stage; `db.connector.mysql` → `IcmsMySqlConnector`; `Connection::resolverFor('mysql')` → `SharedMySqlConnection` (error mode per query, no reconnect)
  - `DatabaseManager::extend('icms')` completes the configuration from `mainfile.php` and hands it to Laravel's factory. PDO's own defaults (emulated prepares) and no `SET` statements, so the connection is the one legacy would have opened
- [x] 7.2 Register the `Icms\Db\Factory` connection provider from `LegacyRuntime` before `services`
- [x] 7.3 Integration test: `DB::beginTransaction()`, insert through a system-module IPF handler, read through the query builder, roll back, row absent in both layers

## 8. Container exposure (L8; Symfony 8.x)

- [x] 8.1 `IcmsServiceProvider`: lazy `icms.*` bindings and class aliases (`icms.user`/`icms.module` not shared)
- [x] 8.2 `App\Legacy\HandlerLocator` (`icms.handlers`, PSR-11, `EntryNotFoundException`)
- [x] 8.3 Contextual attribute `#[IcmsHandler('name')]`, in controller methods and constructors
  - Tested as a controller argument (`HandlerInjectionTest`); the route dispatcher resolves the attribute before it reflects the legacy class
- [x] 8.4 Tests: same instance as `icms::$security`; a system-module IPF handler from the locator is `icms::handler()`'s instance under both class names; an unknown handler throws
- [x] 8.5 Characterization: an unmodified IPF module page renders identically; `loadService-<name>` preload listeners still fire

## 9. Event forwarding (L9; Symfony 9.x)

- [x] 9.1 `App\Legacy\LegacyEvent` and the forwarder dispatching `icms.<namespace>.<name>`
- [x] 9.2 Forward only events that have listeners (`hasListeners()`)
  - Laravel cannot know its listeners in advance; the forwarder is always installed and checks `hasListeners()` per event
- [x] 9.3 Test: a Laravel listener on `icms.icms.loadService-db` is invoked once with the DB service; preload listeners are unaffected

## 10. Session and identity (L10–L12; Symfony 10.x)

- [ ] 10.1 `IcmsSessionStore`, `StartIcmsSession` and the `icms` middleware group; `services` boots before any session access
- [ ] 10.2 `App\Auth\IcmsUser` (id, uname, display name, groups)
- [ ] 10.3 `Auth::viaRequest('icms')` guard; `redirectGuestsTo()` → `/user.php?xoops_redirect=<url>`
- [ ] 10.4 Gates: `webmaster`, `icms_group:<id>`, `icms_perm:<name>` (module permission, item permission with an integer subject)
- [ ] 10.5 Middleware `icms.token`: validates `icms::$security->check()` on POST
- [ ] 10.6 Demonstration route `/whoami` (`auth:icms`, `can:webmaster`), only in `local` and `testing`
- [ ] 10.7 Tests: legacy-written session value read by Laravel; a custom session name gives one cookie; anonymous → redirect, member → 403, webmaster → 200; logout → anonymous; token accepted once, rejected on replay

## 11. Documentation and verification (Symfony 11.x)

- [ ] 11.1 `docs/laravel-host.md` in the structure of `docs/symfony-host.md` (request flow, stages, seams, container ids, abilities, unsupported runtimes)
- [ ] 11.2 Update `README.md`: `public/` with the host, or `htdocs/` directly
- [ ] 11.3 Fail the suite on Laravel deprecations triggered from `app/`
- [ ] 11.4 Run all suites and `bin/spec-coverage` with no gaps; PHPStan level 6 on `app/` and `Icms\Kernel`
- [ ] 11.5 Update `docs/dx-comparison.md` with the measurements of phases 1–5 and the DX summary in `design.md`

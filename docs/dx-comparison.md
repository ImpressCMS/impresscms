# DX comparison: Laravel 12 (lara-3) versus Symfony 7.4 (pre-3)

lara-3 rebuilds the strangler-fig migration of ImpressCMS that was first built on Symfony 7.4 LTS, from the same commit (`89bc13930`), with the same specs, scenarios, snapshots and phase order. This log records how the two frameworks compare on that identical task. It is written while the work happens, not afterwards.

The decisions are numbered `L<n>` in `openspec/changes/archive/2026-11-02-migrate-to-laravel-foundation/design.md` (and the follow-up changes); `D<n>` refers to the Symfony designs.

## How this is measured

Per phase:
- **Host code**: files and lines added or changed in the host layer (`app/`, `bootstrap/`, `config/`, `routes/`, `public/`, `artisan` for Laravel; `src/`, `config/`, `public/`, `bin/console` for Symfony), from `git diff --shortstat`.
- **Library seams**: how many `Icms\` seams the phase needed, and whether a reused seam had to change.
- **Framework workarounds**: interventions that work against the framework's intended use (replacing a core class, overriding a protected method, suspending a bootstrapper, re-implementing framework logic). Each one is listed with its reason.
- **Suite time**: wall time of each test suite on the same machine (Windows 11, PHP 8.2.35, MySQL 8.0.36 from Herd).
- **Time to first green `Http` snapshot**: from the first host commit of phase 3 to the first passing snapshot through `public/`.
- **Framework lookups**: how often the framework's documentation or source had to be consulted, and for what.

Symfony figures come from the commits on `pre-3`. Its suite timings and lookups were not recorded at the time, so those columns stay empty for Symfony rather than being estimated.

## Measurements

### Symfony baseline (from `pre-3`)

| Phase | Commit | Host code | Library |
|---|---|---|---|
| 1–2 Test infrastructure, characterization | `86f94663b`, `98555bb46` | — | — |
| 3 Seams | `e01c18bd0`, `a498cc573`, `e3bbb613e`, `ff813c6c3` | — | 4 seams; 8 files, +755 −317 |
| 3 Host and bridge | `3c1d278a1`, `dab4b1755` | 32 files, +1565 −3 | — |
| 4 Headless runtime | `7f6e79b4e` | 6 files, +246 | — |
| 4 Database | `6bc20a328` | 8 files, +273 | — |
| 4 Container | `f706fefe8` | 5 files, +227 | — |
| 4 Events | `467ae9f59` | 9 files, +188 −13 | — |
| 5 Session and identity | `d97c195cc` | 14 files, +544 −2 | — |
| Total, foundation | `89bc13930..94141e7cf` | 59 files, +3006 (47 files in `src/`) | 4 seams |
| 6 Relocation into the trust path | `517edf176..1ea034406` | 13 files, +127 −68 | legacy: 26 files, +880 −974 |
| 7 IPF pilot (mimetype admin page) | `4a2aabb76`, `05285172c`, `749b01d28` | 24 files, +1594 −5 | 1 docblock fix |

### lara-3

| Phase | Commit | Host code | Library seams | Workarounds | Lookups |
|---|---|---|---|---|---|
| 0 Plan | `eeb0ed7db` | — | — | — | 9 |
| 1–2 Test infrastructure, characterization | `d7db54160`, `09b7bdb2d`, `12ee144b5` | — | — | — | 0 |
| 3 Seams | `bcde82acc` … `06168341e`, `229818d92` | — | 4 seams reused, **none changed**; 1 fix taken over | — | 0 |
| 3 Host and bridge | `568249bbc` | 31 files, +1719 | — | 10 (1–10) | 6 |
| 4 Shared runtime | `d419ba149` | 22 files, +784 | — | 3 (11–13) | 5 |
| 5 Session and identity | `9043e0c48` | 17 files, +661 −10 | — | 6 (14–19) | 4 |
| Total, foundation | `89bc13930..9043e0c48` | 59 files, +3154 (Symfony: 59 files, +3006) | 4 | 19 | 24 |
| 6 Relocation into the trust path | `553d0011c`, `64ceb9b75`, `3139b6d94`, `ffb5311e2` | 13 files, +104 −59 (Symfony: 13 files, +127 −68) | legacy taken over; 1 file adapted | 0 | 3 |
| 7 IPF pilot (mimetype admin page) | `bd0d07697..36300628c` | 25 files, +1648 −6 (Symfony: 24 files, +1594 −5) | none; the docblock fix taken over | 2 (20–21) | 3 |

The Symfony variant did not count its workarounds. Its task notes name about seven of the same kind: the 405 re-match, the custom runtime runner, the DBAL driver with error-mode switching, the `#[Autowire]` subclass, the listener compiler pass, the decorated session factory with its attribute bag, and the token clearing in the lazy firewall. The host code figures include `config/` and `routes/` on both sides (YAML there, PHP here), but not tests.

**Suite times** at the end of phase 5 (Windows 11, PHP 8.2.35, MySQL 8.0.36, sequential): `Library` 2 s (413 tests), `Unit` 6 s (39), `Integration` 38 s (26), `Functional` 20 s (12), `Http` on `htdocs/` 103 s (49, of which 31 need the host and are skipped) and on `public/` 173 s (49). Against plain `2.1-dev` the `Http` suite took 66 s for 13 tests. Process isolation dominates `Integration` and `Functional`: every test class boots Laravel and legacy in a new process. One unexplained 10-minute `Http` run occurred while a second suite ran against the same database; it did not recur in isolation.

**Time to the first green `Http` snapshot**: about 35 minutes of wall-clock time from installing `laravel/framework` to all 37 `Http` tests passing through `public/`, on the first full run. That figure profits most from the existing design (see below).

**Framework lookups** (Laravel source or docs), after phase 0: phase 3 `CompiledRouteCollection::match()`, `Route::getValidators()`/`toSymfonyRoute()`, the traits of `Route`, `HandleExceptions::forwardsTo()`/`forgetApp()`, `Http\Kernel` properties, the default routes in `route:list`; phase 4 `MySqlConnector::configureConnection()`/`getHostDsn()`, `Connector::$options`, `DatabaseManager::makeConnection()`, `RoutingServiceProvider::registerRedirector()`, `CacheManager` (through a Larastan trace); phase 5 `Session\Store`, `SessionManager::callCustomCreator()`, `Authenticate::redirectTo()`, the order in `Exceptions\Handler::render()`.

## Log

### Phase 0: plan and design (2026-11-02)

**Lookups in the Laravel 12.70.2 source and docs** (9): support policy (`docs/12.x/releases`); `Router::fallback()`; `ResolvesRouteDependencies::transformDependency()`; `HandleExceptions`; the default global and `web` middleware (`Configuration\Middleware`); `Connector::createPdoConnection()` and the `db.connector.*` binding; `Session\Store`; `Http\Kernel::handle()`; `LoadEnvironmentVariables` and `Application::normalizeCachePath()`.

**Findings, per design decision:**

- **L1 Support period.** Laravel 12 lost bug-fix support on 2026-08-13 and loses security support on 2027-02-24, before this work could reach production. Symfony 7.4 LTS is supported until November 2029. Laravel has no LTS; its yearly majors raise the PHP minimum (13 needs 8.3). For a multi-year migration that is a recurring cost the Symfony path does not have. *Unfair to Laravel?* Partly: the assignment fixes Laravel 12 because of the PHP baseline, and Laravel 13 would have about 16 months of bug fixes left.
- **L3 Global helpers.** Laravel loads 175 global functions on every request. None collides with the 871 functions declared under `htdocs/`, but legacy declares its functions without guards, so a third-party module defining for example `e()` or `url()` would fatal. Symfony loads a handful (`dump()`, `dd()`, `trigger_deprecation()`, `u()`/`b()`/`s()` from `symfony/string`), and Laravel loads those too.
- **L4 Fallback route.** `Router::fallback()` is GET only, so it cannot carry a legacy POST. An `any` route marked with `->fallback()` works. Laravel, like Symfony, does not produce 405 when a catch-all accepts every method; both need the same re-match workaround. Laravel has no route condition on the query string. That is a new workaround (a route validator plus macro) that Symfony did not need (`condition:` expressions).
- **L5 File scope.** Laravel's front controller is a plain script, so a file-scope `require` needs no custom runtime. Symfony needed a custom `symfony/runtime` runner. Against that, Laravel has no `kernel.response` event (post-processing is middleware), so the real response bypasses it, and `HandleExceptions` (`error_reporting(-1)`, errors become exceptions) must be suspended around legacy scripts. The default input middleware (`TrimStrings`, `ConvertEmptyStringsToNull`) would change what a takeover controller sees, so they are removed.
- **L7 Database.** Laravel has two documented extension points that fit exactly: a container binding per driver connector (`db.connector.mysql`) and `Connection::resolverFor()`. Symfony needed a custom DBAL driver and middleware for the same result. Making legacy the owner would be even smaller in Laravel (`DB::extend()` with a PDO closure, no seam), but it would break the like-for-like comparison.
- **L8 Container.** Laravel's contextual attributes (`ContextualAttribute::resolve()`) are a direct equivalent of `#[IcmsHandler]`. The route dispatcher resolves them before loading the parameter's class, which avoids the problem the Symfony variant solved by extending `#[Autowire]`.
- **L10 Session.** Laravel's session layer never uses PHP's native session, and there is no equivalent of Symfony's `php_bridge` storage. The bridge needs a custom `Store`, a replacement for `StartSession`, and a custom middleware group instead of `web`, because `web` also encrypts cookies and validates Laravel's CSRF token. This is the largest planned workaround.
- **L11 Identity.** `Auth::viaRequest()` plus `Gate::before()` cover the ImpressCMS user, groups and module permissions with less ceremony than a Symfony authenticator and voter. Laravel has no role concept, so `ROLE_*` became abilities.
- **L16 Tests.** Orchestra Testbench is meant for packages, and it would not load lara-3's `bootstrap/app.php`. Laravel's own `Foundation\Testing\TestCase` is the right tool for an application. As with `KernelBrowser`, Laravel's test client never reaches the file-scope runner, so the `Http` suite stays the only way to test legacy execution.

### Phases 1–2: test infrastructure, characterization, seams (2026-11-02)

- The Symfony variant's commits `2faac47a9`, `86f94663b` (minus its `openspec/` hunk), `98555bb46`, `e01c18bd0`, `a498cc573`, `42c344408`, `e3bbb613e` and `ff813c6c3` were cherry-picked without a conflict or a change. **No seam had to change for Laravel.** One later library fix from the Symfony variant's final commit (`94141e7cf`, an uninitialised variable in `Bootstrap`, found there by PHPStan) is a separate commit.
- The test infrastructure depends on no framework (PHPUnit, Symfony BrowserKit and Process as tools), so it carried over as is. That is a property of how the Symfony variant built it, not of either framework.

### Phase 3: the host and the bridge (2026-11-02)

**Fitted naturally**
- *File scope.* Laravel's front controller is a plain script; `require` at file scope needs no runtime component. Symfony needed a custom `symfony/runtime` runner (`HostRuntime`, `HostRunner`).
- *HttpFoundation underneath.* `Illuminate\Http\Request` and every response are HttpFoundation objects, so the resolver, the hydrator, `LegacyResponse`, `SendsHeadersVerbatim` and `BinaryFileResponse` for assets are the same in substance as on Symfony. The bridge's unit tests from the Symfony variant ran against the Laravel classes unchanged except for one test.
- *Tests.* `Illuminate\Foundation\Testing\TestCase` needed no configuration: its default `createApplication()` found `bootstrap/app.php`.
- *First green.* All 37 `Http` tests, including every characterization snapshot, passed through `public/` on the first run.

**Worked against the framework**
1. `Router::fallback()` is GET only; an `any` route marked `->fallback()` is needed.
2. No 405 with an all-method catch-all: the same re-match in the bridge controller as on Symfony.
3. No route condition on the query. A route validator appended to the static `Route::$validators` plus two macros (`whereQuery()`, `matching()`); `when()` was taken by the `Conditionable` trait.
4. `route:cache` silently drops that condition: the compiled matcher never runs route validators. The host refuses cached routes. Symfony compiles conditions into its cached matcher.
5. `withRouting(web:)` puts the routes into the `web` group (cookie encryption, Laravel session, CSRF); `withRouting(using:)` instead.
6. `TrimStrings` and `ConvertEmptyStringsToNull` are global by default and rewrite input that a takeover controller must see as legacy did; removed.
7. Laravel 12 registers `/storage/{path}` routes for the local disk by default, which claim URL space; turned off in `config/filesystems.php`.
8. `HandleExceptions` sets `error_reporting(-1)`, turns notices into exceptions and renders fatal errors at shutdown. `forgetApp()` (public, intended for tests) makes it inert; PHP's settings are recorded in `public/index.php` before Laravel boots.
9. `RequestHandled` is dispatched inside `Kernel::handle()` with no separate method, so `HostKernel` repeats the parent's `handle()` body to skip it for the marker.
10. For a JSON body Laravel's `Request` replaces the POST bag with the decoded JSON; the hydrator keeps `$_POST` empty then, as PHP does.

Laravel's docblocks are narrower than its behaviour: `Kernel::handle()`, `terminate()` and `RequestHandled` document `Illuminate\Http\Response`, while the router passes any Symfony response (files, the marker). PHPStan with Larastan needed two `@phpstan-ignore` lines for that. PHPStan 2.1 prints advice for AI agents not to add such ignores; they stay, with the reason in the comment.

**Environment, not framework**: `composer.bat` on Windows runs through `cmd`, which strips `^` from a constraint (`^12.70` became `12.70`).

### Phase 4: shared runtime (2026-11-02)

**Fitted naturally**
- *Database.* `db.connector.mysql` (a container binding per driver) supplies ImpressCMS' PDO class, `Connection::resolverFor('mysql')` the shared connection, and `DatabaseManager::extend('icms')` completes the configuration from `mainfile.php` at connect time. Three documented extension points; Symfony needed a custom DBAL driver, a driver-connection middleware, a statement wrapper and an env var processor.
- *Table prefix.* Laravel's connection prefix is ImpressCMS' prefix: `DB::table('users')` is `icms_users`.
- *Container.* Laravel compiles no container, so registering `icms.*` bindings never loads a legacy class file; Symfony needed `class: object` definitions and an `#[Autowire]` subclass to avoid that. Contextual attributes (`#[IcmsHandler('icms_data_page')]`) are a direct equivalent of `#[IcmsHandler]` and resolve before the parameter's class is reflected.
- *Middleware with parameters* (`icms.legacy:siteState`) reads well as the equivalent of `#[RequiresLegacy(stage:)]`, although it lives in the route file instead of on the controller.

**Worked against the framework**
11. Laravel's MySQL connector changes the shared connection unless told otherwise: emulated prepares off (native types for legacy, `LIMIT ?` with strings fails) and `SET NAMES`/`sql_mode`/`time_zone` from configuration. The `icms` connection restores PDO's defaults and sets none of those keys, and puts the charset into the DSN.
12. The skeleton defaults session, cache and queue to the `database` driver. The redirector resolves the session store for every `response()`/`redirect()` helper, so a 404 page booted legacy and opened the connection; `artisan` (and Larastan, which boots the application) did the same through the cache. Overridden to `array`, `file` and `sync`.
13. The shared connection must refuse `reconnect()`; Laravel would otherwise open a second physical connection after a lost connection.

**Neutral**: no compiled listener map, so the event forwarder is always installed and checks `hasListeners()` per event. String-named events cannot be auto-discovered. Test-only listeners need a provider registered from code, where Symfony has `#[When(env: 'test')]`.

### Phase 5: session and identity (2026-11-02)

**Fitted naturally**
- *Identity.* `Auth::viaRequest('icms', …)` is a complete guard in five lines; `#[CurrentUser]` injects the user. Symfony needed an authenticator with an entry point, a user provider and a passport.
- *Permissions.* One `Gate::before()` callback covers webmaster, group and module permissions, with `can:` middleware on routes. A Symfony voter is a class per attribute family; both are similar in size.
- *Token.* One middleware, as one listener on Symfony.

**Worked against the framework**
14. `SessionManager::extend()` wraps a custom driver's handler in Laravel's own `Store`, so a store over `$_SESSION` cannot be registered that way; the `session` binding is replaced by a subclass with `createIcmsDriver()`. Symfony has a native-session bridge (`php_bridge`) and needed only a factory decorator and an attribute bag.
15. `StartSession` sets Laravel's own cookie and saves through Laravel's handler; `StartIcmsSession` replaces it, inside an `icms` group that replaces `web` (whose `EncryptCookies` and `ValidateCsrfToken` do not fit either).
16. Guests denied by `can:` get 403 in Laravel; Symfony sends them to the entry point. Two `withExceptions()` render callbacks restore that, without Laravel's guest redirect, which would write `url.intended` into ImpressCMS' `$_SESSION`.
17. The `auth` middleware computes `route('login')` before anything renders, so `redirectGuestsTo()` must be set even though its target is not used.
18. A headless boot inside a Laravel route ran under Laravel's error handler: one legacy warning (`QUERY_STRING` missing under `php -S`) became a 500. Each stage now runs with PHP's own error handling until ImpressCMS installs its handler. Symfony's production error handler only logged the warning.
19. The default compiled-view path is `realpath(storage_path('framework/views'))`, empty until the directory exists; the first error page failed with "Please provide a valid cache path". `config/view.php` passes the plain path.

**Debuggability.** Every failure in this phase was found from `var/log/test.log`, whose stack traces named the Laravel class that caused it. Laravel's exception messages were clear (`Route [login] not defined`, `Please provide a valid cache path`); the cause was in each case a default that assumes a Laravel-owned application.

### Interlude: PHP 8.3 and Herd (2026-11-02)

- **PHP 8.3.36**: every suite passes with `php -S` (the same counts as on 8.2.35). PHP 8.4 is still untested locally.
- **Herd** (`http://lara3.test`, nginx and PHP 8.3, a clone of `lara-3` with its own schema `lara3`): Herd's own Laravel driver serves `public/` and sends everything else to `public/index.php`, which amounts to routing level 3 without any configuration. The Http suite runs against it with `ICMS_TEST_BASE_URL`: 49 tests, no failure. 12 are skipped: the bridge fixture tests, which need a `php -S` of their own.
- Getting there found one host bug and two test artefacts:
  - *Host*: the bridge removed only the stack frame whose file was `SCRIPT_FILENAME`. Under Herd that is Valet's `server.php`, which `require`s `public/index.php`, so a legacy error page listed `public/index.php (43)`. The bridge now cuts the trace at the real front controller (`public_path('index.php')`), dropping Herd's frames below it as well.
  - *Test*: the snapshot normalizer assumed a port in the base URL (`{HOST}` = host and port, `{HOSTNAME}` = host). Without a port it now tells URLs and cookie domains apart, and masks the host that `bin/test-db` installs the site under (`127.0.0.1`), which installed data repeats.
  - *Server*: Herd's nginx (`charset utf-8`) appends `; charset=utf-8` to `application/javascript`. The normalizer ignores that for external servers only; the host's own header is unchanged.
- The Symfony variant added `ICMS_TEST_BASE_URL` in its relocation and tested Apache, nginx and FrankenPHP in CI, all with a port; Herd was documented there but not part of CI. The same frame and normalizer issues would likely have shown there under Herd, so they are not counted as Laravel workarounds.

### Phase 6: relocation into the trust path (2026-11-02)

Change `relocate-laravel-host-into-trust-path`, design L17–L24.

**What carried over.** The relocation is mostly about ImpressCMS deployment, not about the framework: `include/host.php`, the `index.php` preamble, `installTrustDistribution()`, the installer page, update step 50, the dashboard warning, `htaccess.txt`, `docs/server/` and the test support. The legacy diffs of the Symfony commits `98f7b467d`, `eb4bf8477`, `a7268a413`, `49333b28a` and `133b36e0a` applied without a conflict (`htdocs/` was identical apart from the lock file). The test support needed six small conflict resolutions, all in lines that phase 5 had already changed for `ICMS_TEST_BASE_URL`.

**Where the library names the framework.** `installTrustDistribution()` writes the host's `.env.local` and empties its caches. Symfony wrote `APP_SECRET` and emptied `var/cache`; Laravel needs `APP_KEY=base64:…` and `var/framework/{cache,views}`, and its `bootstrap/cache` is emptied because `bootstrap/` is replaced as a whole. Both variants have this coupling in the same place; it is now two constants and one line.

**Fitted naturally**
- *Front controller.* `public/index.php` became `host.php` with two changed lines (the `php -S` fix-up names the web root's `index.php`). The trace cut of phase 5 (Herd's `server.php`) already covered the preamble and the test stub; Symfony had to add it in this phase.
- *Configuration without the server environment.* Laravel's environment repository is immutable and reads `$_SERVER` first, so the preamble's `$_SERVER` entries win over `.env.local` without code. Symfony's `Dotenv` behaves the same. `HostUpgradeTest` (PHP with `variables_order=GPCS`, nothing but `.env.local`) passed on its first run.
- *The test-only host marker* is a `RequestHandled` listener. Phase 3's `HostKernel` dispatches that event for the real legacy response, so the marker sees Laravel and legacy responses alike, as Symfony's `kernel.response` listener does.
- *Static analysis caught a real bug.* The first version registered the `Icms\` library in `afterLoadingEnvironment()` with `env('ICMS_LEGACY_ROOT')`. Larastan reported `env()` outside `config/`: with a cached configuration Laravel skips `.env.local`, so `artisan` on an installed site would not have found the library. It now runs after `LoadConfiguration` with `config('icms.legacy.root')`.

**Different, not worse**
- *Registering the `Icms\` library.* Symfony's `bin/console` calls `Dotenv::bootEnv()` itself before the kernel exists and can then require `libraries/Autoloader.php`; `host.php`, the console and the test bootstrap each do so. In Laravel the environment is loaded by a bootstrapper inside the application, so the library is registered in one place, `bootstrap/app.php`, through `afterBootstrapping(LoadConfiguration::class)`, a documented hook. One place instead of three.
- *Herd.* In phase 5 Herd's Laravel driver served `public/` (level 3 without configuration). That driver recognises `artisan` plus `public/index.php` only, so a web root is now served by Herd's basic driver (level 2), or at level 3 by the ImpressCMS driver from `docs/server/herd/`, as on Symfony. The Laravel host lost its Herd advantage with `public/`; that is the price of the shared deployment model, not of Laravel.

**Worked against the framework**: nothing in this phase.

**Measurements**
- Host code: 13 files, +104 −59 (Symfony: 13 files, +127 −68). The legacy, server and test changes are shared and not counted.
- `bin/build-dist`: 25 s, `htdocs/trust_dist/` 30.6 MB in 6950 files (Laravel 12 and the legacy dependencies, `--no-dev --classmap-authoritative`). The Symfony size was not recorded.
- Suites on PHP 8.2.35 (Windows 11, MySQL 8.0.36): `Library` 422 tests, `Unit` 46, `Integration` 29, `Functional` 12, `Http` 60 at level 3 (255 s, 3 skipped) and at level 2 (241 s, 24 skipped: the tests that need every request routed through the host), `HostUpgradeTest` 57 s. `bin/spec-coverage` 60 of 60 scenarios.
- Lookups (3): `Application::afterLoadingEnvironment()`/`afterBootstrapping()`, the default adapters of `Env::getRepository()`, the `serves()` rule of Herd's `LaravelValetDriver` and `.valet-env.php` in Herd's `ValetDriver`.
- Herd (`lara3.test`, PHP 8.3.36, level 2 with a basic-derived site driver, 2026-11-03): a fresh installation through the web installer and an upgrade from dbversion 48 both install the host without manual steps. The `Http` suite needed one test fix (Valet's `server.php` as a backtrace frame in a snapshot) and fails twice by design, because that driver serves `trust_dist/` and `templates_c/`. Framework-independent: the same holds for the Symfony host.

### Phase 7: IPF pilot, the mimetype admin page (2026-11-03)

Change `ipf-on-laravel-mimetype-pilot`, design L25–L35. Symfony: `4a2aabb76`, `05285172c`, `749b01d28`.

**What carried over.** The four specs, the fixtures (three mimetypes, `mediaadmin`, `mimetypeadmin`) and the 20-case characterization corpus with its snapshots applied without a conflict. The legacy page matched all 20 snapshots on lara-3 unchanged, so both branches start from identical legacy behaviour. The admin pieces are about ImpressCMS, not the framework: `LegacyOutput`, `AdminPageRenderer`, `LegacyRedirect`, `SystemAdminAccess` and `SystemAdminContext` were ported almost line for line; only the types of the response and the request differ (both are Symfony HttpFoundation classes in Laravel too). With the controller in place, all 20 snapshots matched byte for byte on the first run that reached the controller.

**Fitted naturally**
- *Admin context as middleware.* `icms.admin:<fct>` is route middleware with a parameter, Laravel's place for a precondition that may answer instead of the controller. Symfony needed an attribute, a `kernel.controller` listener ordered before `RequiresLegacyListener`, and `kernel.finish_request` to restore the superglobals; here `try`/`finally` around `$next()` does that.
- *Configuration injection.* `#[Config('icms.legacy.root')]` on a constructor parameter, the counterpart of `#[Autowire('%…%')]`.
- *Module handlers needed nothing new.* The foundation's `HandlerLocator` already resolves `module:<dir>/<name>` through `icms_getModuleHandler()`, so the controller takes `#[IcmsHandler('module:system/mimetype')]`. Symfony added a `ModuleHandlerLocator` and an attribute in this phase. (A first Laravel version duplicated them before the existing support was noticed; it was removed.)
- *Validation rules.* The rules of a variable are a short array (`sometimes`, `required`, `string`, `max:255`, `in:…` on `dirname.*`), with the legacy wording (`_XOBJ_ERR_REQUIRED`, `_XOBJ_ERR_SHORTERTHAN`) as custom messages. `sometimes` gives `postDataToObject()`'s "only the submitted keys" in one word; Symfony needs `submit($data, false)` plus a data mapper that skips unsubmitted children.

**Forms and validation (the assignment's question)**
- Laravel has no form component. What Symfony's Form component provides — a field tree, binding, data mapping, the unnamed root form, extra fields — is host code here: `IpfField` (a value object), `IpfForm` (binding, validation, writing) and `IpfFormFactory` (the field set). `ControlMap` and the value mapping exist on both sides. Forms and persistence: Laravel 7 files, +695 (`app/Ipf`); Symfony 6 files, +535 (`src/Ipf`), plus `symfony/form` and `symfony/validator` as new dependencies. Laravel needs no new package.
- A `FormRequest` was considered and rejected (design L31): it validates when it is resolved, which does not fit one route that dispatches on `op`; its failure path (redirect back with session errors) and `authorize()` would both be replaced; and its rules come from runtime metadata, not from the request class. A `FormRequest` fits a later Blade page with a route per action. What remains of Laravel's validation stack is the validator, which `IpfForm` uses directly.
- Error messages: Laravel's defaults ("The :attribute field is required.") differ from the legacy wording; the custom messages make them equal to Symfony's, at the same cost (one message per rule).
- Net: validation is simpler in Laravel, form handling is more work, because there is no form layer to build on. For pages that keep the legacy IPF form HTML, as here, the missing layer costs about one class (`IpfForm`). For a future Blade rendering it would cost more: Symfony's form theming has no Laravel counterpart.

**Worked against the framework**
20. Laravel's `RouteCollection` stores one route per method, domain and URI; a second route for the same URL replaces the first. Every system admin function is `/modules/system/admin.php`, so the test takeover of `fct=users` silently removed the mimetype takeover for GET and HEAD (POST survived, so the bridge answered 405). `App\Routing\VariantRouteCollection` overrides the protected `addToCollections()` and is installed with `Router::setRoutes()` before any route is added. Symfony's router keys routes by name and has no such limit. The foundation never registered two takeovers of one URL, so phase 3 did not show it.
21. Route validators are a static, process-wide list (`Route::$validators`). Phase 3 appended its validator per application, so in the `Functional` suite (an application per test) the first, stale validator ran with a flushed container ("Target [Config\Repository] is not instantiable"). It is now replaced instead of appended.

**Choices that differ from Symfony**
- `ICMS_TAKEOVERS_DISABLED` is read through `config()` (presented, design L26). With a cached configuration a change needs `php artisan config:clear`; Symfony reads it at runtime and needs no cache clear. The host never caches its configuration itself.
- The access probe for tests is a test route around `SystemAdminAccess::isGranted()`, not a Gate ability: the Gate would resolve the user through the guard, which launches the module and exits for denied visitors (design L28). Symfony found the same with its voter on the page path and kept the voter only for other uses.

**Bugs found by the tests**
- `whereInput()` first let the query string win over the POST body; `admin.php` extracts `$_POST` after `$_GET`, as Symfony's condition had it. Fixed, with a test.

**Measurements**
- Host code: 25 files, +1648 −6 (Symfony: 24 files, +1594 −5). Tests: 30 files, +4300 −5 (Symfony: 30 files, +4328 −4), most of it the shared snapshots.
- Lookups (3): `RouteCollection::addToCollections()` and `Router::setRoutes()`, `Illuminate\Container\Attributes\Config`, wildcard keys in custom validation messages.
- Suites on PHP 8.2.35 (Windows 11, MySQL 8.0.36), from a fresh test site: `Library` 422 tests (2 s), `Unit` 60 (5 s), `Integration` 36 (49 s), `Functional` 18 (19 s), `Http` 114 at level 3 (425 s, 3 skipped) and at level 2 (385 s, 30 skipped), deprecation check 78 tests, `bin/spec-coverage` 88 of 88 scenarios, PHPStan level 6 without errors. One earlier full run had two `IpfFormsTest` failures that did not recur in four later runs; their cause was not found.
- Herd (`lara3.test`, PHP 8.3.36, 2026-11-03). Level 2 with the site's basic-derived driver: `Http` 114 tests, 51 skipped, 2 failures by design (that driver serves `trust_dist/` and `templates_c/`); `admin.php` runs directly there, so the takeover is not reached. Level 3 with `docs/server/herd/ImpressCmsValetDriver.php` as the site's `LocalValetDriver.php` (no global Herd change): `Http` 114 tests green, 41 skipped (among them the 20 rollback cases, which need their own server). In the browser the list and the create form come from the `system.mimetype` route, the form with a security token.

## Where the comparison is unfair

- **The design existed.** The Symfony variant discovered the ImpressCMS constraints (file scope, `exit()`, `ERRMODE_SILENT`, `IConnection`, headless denials, `QUERY_STRING` sorting, header semantics). lara-3 starts from its specs, implementation notes and tests, so Laravel is spared that discovery. Raw effort figures therefore favour Laravel. The comparison is fair on the questions "how much host code" and "how much fighting the framework", less so on "how long".
- **Reused tests.** The `Http` suite uses Symfony BrowserKit and Process. They are framework-agnostic test tools, and Laravel itself depends on Symfony components, but they are more familiar on the Symfony side.
- **Same author and assistant.** Both variants are written by the same people with the same habits, which may fit one framework's style better.
- **Version choice.** Laravel 12 is fixed by the assignment while it is already out of bug-fix support; Symfony 7.4 is a current LTS.

## Conclusion

Written at the end of the IPF pilot, per criterion, without a preference fixed in advance.

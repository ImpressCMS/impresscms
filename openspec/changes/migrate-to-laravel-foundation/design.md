## Context

See `proposal.md` (Why). This change starts from commit `89bc13930`, the same commit the Symfony variant started from. The state of the code there is described in the Context of `migrate-to-symfony-foundation/design.md` (Symfony branch) and is not repeated here: the `Icms\` library and its alias map, the `icms` facade, the boot sequence in `common.php`, `Icms\Db\Factory`, `Icms\Core\Session`, `Icms\Event`, and the module usage of `icms::$xoopsDB`, `icms::handler`, `icms::$user` and `icms::$security`.

The Symfony documents are the **specification**, not code to copy:
- `openspec/changes/archive/2026-10-14-migrate-to-symfony-foundation/` (D1–D13)
- `openspec/changes/archive/2026-10-14-relocate-host-into-trust-path/`
- `openspec/changes/ipf-on-symfony-mimetype-pilot/`
- `docs/symfony-host.md`

Every decision below is numbered `L<n>` and names its Symfony counterpart `D<n>`. Where the ImpressCMS decision is framework-independent, it is taken over unchanged and the decision only describes how Laravel realises it.

Facts about Laravel 12 used in this design were checked against the `laravel/framework` v12.70.2 sources; the file is named where it matters.

## Goals / Non-Goals

**Goals** (identical to the Symfony variant):
- A Laravel application is the outermost runtime. ImpressCMS `2.1-dev` runs inside it unchanged.
- There is one process-wide autoloader, one DB connection, one session and one identity.
- `icms::` becomes a facade over state that the Laravel container can also reach, without changing behaviour.
- The `Icms\` library stays framework-agnostic: it never depends on Laravel. Every integration seam is an optional hook that is inert without the host.
- The same specs, scenarios and characterization snapshots pass as on the Symfony host.

**Non-Goals:**
- Eloquent for legacy data, Laravel facades as a replacement for `icms::`, Blade for legacy pages (L13).
- Migrating legacy pages to Laravel controllers, except a test-only demonstration route.
- Making Laravel the owner of legacy service instances. `icms::boot()` keeps constructing them.
- Long-running runtimes: Octane (Swoole, RoadRunner, FrankenPHP worker mode) (L14).

## Decisions

### L1. Laravel 12.x, PHP `>=8.2 <8.5` (Symfony: D1)
`laravel/framework` `^12.70`. Laravel 12 supports PHP 8.2 to 8.5; the root keeps the `2.1-dev` constraint `>=8.2 <8.5`, equal to the Symfony variant, so the PHP range is not a variable in the comparison. Laravel 13 (2026-03-17) requires PHP 8.3 and is excluded.

| | Laravel 12 | Symfony 7.4 LTS |
|---|---|---|
| Released | 2025-02-24 | November 2025 |
| PHP | 8.2 – 8.5 | ≥ 8.2 |
| Bug fixes until | 2026-08-13 (**ended**) | November 2028 |
| Security fixes until | 2027-02-24 | November 2029 |
| Next step | Laravel 13 (PHP 8.3), then 14 in Q1 2027 | Symfony 8.4 LTS (PHP 8.4) |

On the day this change starts (2026-11-02) Laravel 12 receives security fixes only, for less than four more months. Laravel has no LTS line; every major gets 18 months of bug fixes and 2 years of security fixes, and majors appear yearly. A multi-year strangler migration on Laravel therefore means one major upgrade per year, and the first one (to 13) requires raising the ImpressCMS PHP baseline to 8.3. This is a structural DX difference and is recorded as such; it is not a reason to deviate from the assignment.
*Upgrade path*: keep the host free of Laravel deprecations (CI, as D1) and document what the move to 13 requires.

### L2. Host in the repository root first, Laravel's directory conventions (Symfony: D2)
As in the Symfony variant, the layout is reached in two steps, so that the DX comparison holds per step: this change puts the host in the repository root next to `htdocs/`; `relocate-laravel-host-into-trust-path` moves it into the trust path.

The host uses Laravel's own skeleton layout, because measuring how naturally a framework fits includes its conventions:

| Symfony variant | lara-3 |
|---|---|
| `public/index.php` | `public/index.php` |
| `src/` (`App\`) | `app/` (`App\`) |
| `src/Kernel.php` | `bootstrap/app.php` (`Application::configure(basePath: dirname(__DIR__))`), `bootstrap/providers.php` |
| `config/packages/*.yaml`, `config/services.yaml` | `config/*.php`, service providers in `app/Providers/` |
| `config/routes.yaml`, attributes | `routes/web.php`, `routes/console.php` |
| `bin/console` | `artisan` |
| `var/cache`, `var/log` | `var/` via `useStoragePath(var)`: `var/framework/cache`, `var/framework/views`, `var/logs` |

`bootstrap/cache/` (Laravel's compiled `services.php`, `packages.php` and optional config/route caches) stays where Laravel expects it. Moving it needs `APP_*_CACHE` environment variables that must exist before `.env` is read (`Application::normalizeCachePath()`), which the "no server environment" rule of the relocation forbids. In the relocation the installer replaces `bootstrap/` wholesale, which clears that cache as well.

### L3. Single Composer root (Symfony: D3)
Taken over unchanged: the root `composer.json` requires `laravel/framework` plus the `htdocs/composer.json` requirements, maps `Icms\` to `htdocs/libraries/Icms/`, classmaps `htdocs/libraries/icms.php`, loads `htdocs/libraries/Autoloader.php` through `files`, and drops the PSR-0 `icms_` mapping. `public/index.php` defines `ICMS_HOST_AUTOLOADED`. `composer/composer` stays a runtime requirement and must resolve against the Symfony 7.x components that Laravel 12 pulls in.

**Global helper functions.** `laravel/framework` registers 175 global helpers through Composer `files` (`app()`, `config()`, `e()`, `url()`, `redirect()`, `collect()`, `report()`, …), and legacy defines global functions without `function_exists()` guards, so a collision would be a fatal "cannot redeclare". The 871 function names declared under `htdocs/` were compared with Laravel's helper names: the only match is `report`, which is a method of the installer's `mainfile_manager`, not a global function. There is no collision today. A unit test keeps that check (Laravel helper names versus `function` declarations in `htdocs/`), because third-party modules can still collide; that risk is documented in `docs/laravel-host.md`.

### L4. Legacy fallback: catch-all route marked as fallback; no prefix; query conditions through a route validator (Symfony: D4) — **proposed, awaiting approval**
The bridge route is

```php
Route::any('{path}', LegacyBridgeController::class)->where('path', '.*')->fallback()->name('icms.legacy');
```

registered last in `routes/web.php`, outside every middleware group. `Router::fallback()` itself cannot be used: it registers `GET` only (`Router.php:241`), so POSTs to legacy scripts would 404. `->fallback()` on an `any` route makes the router try it after all other routes (`RouteCollection::matchAgainstRoutes()`).

- **405/Allow.** Laravel answers 405 only when *no* route matches the method (`checkForAlternateVerbs()`); the all-method catch-all always matches, so `POST /up` would reach legacy. The bridge controller therefore re-checks the path against the routes of the other methods (the same logic as `checkForAlternateVerbs()`, which is protected) and throws `MethodNotAllowedHttpException` with `Allow`. This is the same workaround the Symfony bridge needed (task 4.8 there).
- **Claiming legacy URLs by query.** Laravel routes match on method, scheme, host and path only; there is no equivalent of Symfony's `condition` expression. lara-3 adds one route validator (`App\Routing\QueryValidator`, appended to `Route::$validators`) and a macro:

  ```php
  Route::get('/modules/system/admin.php', UsersController::class)->whereQuery(['fct' => 'users']);
  ```

  The condition is stored as plain data in the route action (`icms_query`), so `route:cache` keeps working (closures in route actions other than `uses` cannot be cached). `.php` paths need nothing special.
- **Reserved prefixes**: `Route::any('<prefix>/{rest?}', fn () => abort(404))->where('rest', '.*')`, registered before the fallback, from `config('icms.legacy.reserved_prefixes')`; none by default.
- **`icms:routes:ownership <url> [--method=]`** is an artisan command that runs `Router::getRoutes()->match()` on a synthetic request (so the query validator applies) and prints the route name, or the legacy script and strategy.
- **Laravel's built-in `/up`** (`withRouting(health: '/up')`) renders an HTML view; the spec requires `text/plain` `ok`, so the host defines its own route and does not use `health:`.

*Alternatives*: `Route::fallback()` (GET only, rejected); a global middleware that bridges before routing (rejected: it bypasses routing, so ownership and 405 would be hand-made); a 404 renderer in `withExceptions()` (rejected for the same reason as D4: exceptions as control flow, and it would also bridge 404s raised by Laravel controllers).

### L5. Legacy scripts run at file scope, from `public/index.php`, around a host HTTP kernel (Symfony: D5)
Laravel has no `symfony/runtime`; the front controller is a plain script, which makes file-scope execution straightforward. `public/index.php` does what `Application::handleRequest()` does, with one addition:

```php
$kernel = $app->make(HttpKernel::class);
$response = $kernel->handle($request = Request::capture());
if ($response instanceof LegacyExecutionResponse) {
    $bridge = $app->make(LegacyBridge::class);
    $bridge->begin($response);           // superglobals, cwd, ob level, shutdown function
    require $response->script();         // file scope
    $response = $bridge->complete();     // output, status, raw header lines → LegacyResponse
}
$response->send();
$kernel->terminate($request, $response);
```

- **Kernel events.** Laravel has no `kernel.response` event; response post-processing happens in middleware, which already ran on the marker. `App\Http\HostKernel` (bound in place of `Illuminate\Foundation\Http\Kernel` in `bootstrap/app.php`) skips the `RequestHandled` event for the marker and dispatches it for the completed response, so tooling that listens to it (logging, Telescope, Debugbar) sees the real response. `terminate()` receives the real response. The bridge route has no route middleware, so nothing is lost.
- **`exit()`**: a shutdown function registered by `begin()` completes, sends and terminates the same way.
- **Error handling.** Laravel's `HandleExceptions` bootstrapper sets `error_reporting(-1)`, turns every reported PHP error into an `ErrorException` and registers its own shutdown handler for fatal errors. Legacy scripts produce notices and warnings that must not become exceptions before legacy installs its own handler (`Icms\Core\Logger`). `begin()` therefore restores `error_reporting` to the `php.ini` value and PHP's default error and exception handlers for the duration of the script, and the shutdown function owns fatal errors of legacy scripts. An exception escaping a script goes to the handler the script installed, as on the Symfony host.
- **Input middleware.** Laravel's default global middleware `TrimStrings` and `ConvertEmptyStringsToNull` rewrite request input. They are removed globally (`$middleware->remove([...])`), so a Laravel controller that takes over a legacy URL sees the same input as the legacy script did; the hydrator never writes Laravel-modified input into the superglobals.

*Alternatives*: running the script inside a `Fiber` suspended from the controller, so that middleware post-processing applies (rejected: an `exit()` while a fiber is suspended, and fatal errors, make finalisation unreliable); `require` inside the controller with `extract($GLOBALS)` (rejected for the reason of D5).

### L6. Headless bootstrap: the same `Icms\Kernel\Bootstrap`; boot on demand through middleware (Symfony: D6)
The library side is taken over unchanged (stages, headless mode, `SiteClosedException`, `NotInstalledException`). The host side:
- `App\Legacy\LegacyRuntime` is a singleton with an idempotent `bootTo(string $stage)`.
- The equivalent of `#[RequiresLegacy(stage: …)]` is the route middleware alias `icms.legacy:<stage>` (`->middleware('icms.legacy:language')`). Laravel 12 has no controller or route attributes for middleware; middleware with parameters is the idiom.
- Resolving any `icms.*` service boots the needed stage, so `/up` never boots legacy.
- `withExceptions()` renders `SiteClosedException` as 503 and `NotInstalledException` as a redirect to `/install/`.

### L7. Database: Laravel owns the connection; `Icms\Db\Factory` takes its PDO (Symfony: D7) — **proposed, awaiting approval**
`config/database.php` defines the connection `icms` (driver `mysql`) from the `XOOPS_DB_*` constants, read lazily through the `constants` stage. `LegacyRuntime` installs `Icms\Db\Factory::setConnectionProvider(fn () => DB::connection('icms')->getPdo())` before the `services` stage. Two Laravel extension points make this fit without touching Laravel internals:
- **Connector**: the binding `db.connector.mysql` (`ConnectionFactory::createConnector()`) supplies `App\Database\IcmsMySqlConnector`, whose `createPdoConnection()` instantiates the class from `Icms\Db\Factory::connectionClass()` instead of `PDO`, because the legacy layer calls `escape()` on it (the same constraint as Symfony task 3.5).
- **Connection**: `Connection::resolverFor('mysql', …)` supplies `App\Database\SharedMySqlConnection`, which switches the shared PDO to `ERRMODE_EXCEPTION` for each Laravel query (`run()`) and restores the legacy `ERRMODE_SILENT` afterwards, and which refuses `reconnect()`. A Laravel reconnect would open a second physical connection that legacy never sees, so a lost connection becomes an error.

There is one physical connection and one transaction state: a `DB::transaction()` covers inserts through IPF handlers. Laravel's own transaction counter does not see transactions that legacy starts directly on the PDO, as with DBAL. Legacy DDL (module install/update) commits implicitly; host code never wraps it in a transaction.

*Alternative (the more Laravel-idiomatic one)*: legacy owns the PDO, and Laravel wraps it with `DB::extend('icms', fn ($config) => new SharedMySqlConnection(fn () => Factory::pdoInstance(), …))`. That needs no connector override and no library seam. It is not chosen because it differs from D7 in ownership, so the comparison would no longer be like for like, and because `config/database.php` would then not describe the connection that `artisan db:show` reports. Its smaller footprint is recorded in the DX log.

### L8. Container: lazy bindings plus a handler locator; handler injection through contextual attributes (Symfony: D8)
`App\Providers\IcmsServiceProvider` registers `icms.db`, `icms.db.legacy`, `icms.config`, `icms.security`, `icms.logger`, `icms.session` and `icms.preload` as `singleton()` closures over `LegacyRuntime::service('<name>')`, plus `alias()`es to their classes for autowiring. `icms.user` and `icms.module` are `bind()` (not shared), because legacy reassigns `icms::$user` and `icms::$module` during a request (login, module launch); every resolution then returns the current instance.
- `icms.handlers` is `App\Legacy\HandlerLocator`, a PSR-11 container over `icms::handler($name, true)` that throws `Illuminate\Container\EntryNotFoundException` on `false`.
- The equivalent of `#[IcmsHandler('icms_member')]` is a Laravel **contextual attribute** (`Illuminate\Contracts\Container\ContextualAttribute`, as `#[Config]` and `#[CurrentUser]`): `#[IcmsHandler('icms_member')] Handler $members` in a controller method or constructor. The route dispatcher resolves contextual attributes *before* it touches the parameter's class (`ResolvesRouteDependencies::transformDependency()`), so the legacy class file, which exits without `ICMS_ROOT_PATH`, is not loaded early. The Symfony variant needed an `#[Autowire]` subclass for the same reason.
- `#[IcmsModuleHandler]` belongs to the pilot.

### L9. Events: forward `Icms\Event` to the Laravel dispatcher (Symfony: D9)
The `setForwarder()` seam is reused. The forwarder dispatches `App\Legacy\LegacyEvent` under the string name `icms.<namespace>.<name>`, only when `Dispatcher::hasListeners()` is true for that name or a wildcard (`icms.*`). Laravel has no compiled container that knows the listener names in advance (Symfony's `LegacyEventListenersPass`); the check runs per event and is a hash lookup. String-named events cannot be auto-discovered (`withEvents(discover:)` works from typed `handle()` methods), so listeners are registered with `Event::listen()` in a provider.

### L10. Session: ImpressCMS owns the session; a Laravel `Store` over `$_SESSION` (Symfony: D10) — **proposed, awaiting approval**
Laravel's session never uses PHP's native session: `Illuminate\Session\Store` reads and writes its own handler and `StartSession` sets its own cookie. There is no equivalent of Symfony's `php_bridge` storage. lara-3 provides one:
- `App\Session\IcmsSessionStore extends Store`. `start()` boots the `services` stage (so `Icms\Core\Session::service()` has set the name, lifetime and DB handler and started the session) and binds the attribute array to `$_SESSION` by reference. `getId()`/`getName()` return PHP's. `save()` only ages flash data; PHP's handler writes at shutdown. `migrate()`/`invalidate()` use `session_regenerate_id()`. Laravel's own keys (`_token`, `_flash`, `_previous`) live next to ImpressCMS' keys; no legacy code under `htdocs/` uses a session key starting with `_`.
- `App\Http\Middleware\StartIcmsSession` replaces `StartSession`: it sets the store on the request, but sets no cookie, because PHP has already sent ImpressCMS' cookie. That keeps the "no second session cookie" scenario.
- Laravel's `web` group is not used: it also contains `EncryptCookies` (which would hide ImpressCMS' unencrypted cookies from Laravel code) and `ValidateCsrfToken` (L12). lara-3 defines an `icms` group: `StartIcmsSession`, `ShareErrorsFromSession`, `SubstituteBindings`.
- Legacy-owned requests are untouched: the bridge never starts or names a session (bridge spec).

*Alternatives*: no Laravel session API at all, only `icms.session` and `$_SESSION` (simpler, but then `session()`, flash data, `old()` and validation redirects do not work, which the pilot's FormRequest validation needs); moving sessions to Laravel's `database` driver (rejected as in D10: it breaks live sessions and changes storage).

### L11. Identity: a request guard plus gates (Symfony: D11)
- `Auth::viaRequest('icms', …)` (a `RequestGuard`) wraps `icms::$user` after booting the `module` stage in `App\Auth\IcmsUser implements Authenticatable` (id, uname, display name, groups). No user provider is needed, because login, logout and autologin stay in ImpressCMS; `IcmsUser` refuses `getAuthPassword()` and the remember token.
- Roles become abilities: the `auth:icms` middleware for "logged in", `Gate::define('webmaster')` for `ICMS_GROUP_ADMIN`, and a `Gate::before()` callback for the patterns `icms_group:<id>` and `icms_perm:<name>` (module permission through `icms_member_groupperm`, with the same subject rules as the Symfony voter). `can:webmaster` on a route.
- Denials: anonymous → `redirectGuestsTo()` to `/user.php?xoops_redirect=<url>`; authenticated → `AuthorizationException` → 403. The legacy denials of system admin pages (redirect to `user.php`, `Location: admin.php`) belong to the pilot's admin context, as in the Symfony variant.
- Demonstration route `/whoami` (`auth:icms`, `can:webmaster`), registered only in `local` and `testing`.

### L12. CSRF: the ImpressCMS token is leading (Symfony: D11, `#[RequiresLegacyToken]`)
Laravel's `ValidateCsrfToken` is not used (L10). The route middleware `icms.token` validates `icms::$security->check()` on POST and is the equivalent of `#[RequiresLegacyToken]`. Laravel's `@csrf` and `csrf_token()` are not used by the host.

### L13. Laravel idioms that stay unused
Eloquent, `Model`s and Blade compete with IPF, `icms::` and Smarty and are not used for legacy data or legacy pages. Host code reaches legacy data through the query builder on the `icms` connection or through IPF handlers. Facades are used in providers and routes (`Route`, `Event`, `Gate`, `Auth`), where they are idiomatic; classes in `app/` receive their dependencies by constructor injection, so they stay testable without the application. The DX log records each place where an idiom stays unused and what it costs.

### L14. No long-running runtimes (Symfony: Non-Goals)
Octane (Swoole, RoadRunner, FrankenPHP worker mode) keeps the application in memory between requests and resets Laravel's own state. It cannot reset what legacy keeps per process: constants from `mainfile.php` (`XOOPS_DB_*`, `ICMS_IN_ADMIN`), the `icms::` statics, `static` caches in functions such as `icms_getModuleHandler()`, and output and header state after `exit()`. `docs/laravel-host.md` states this.

### L15. Characterization first, against plain `2.1-dev` (Symfony: D12)
The `Http` characterization suite and its snapshots are those of the Symfony variant, reused literally (the commit that added them is framework-agnostic: PHPUnit, BrowserKit, Process). The same snapshots must pass with `public/` as document root.

### L16. Test strategy (Symfony: D13)
The suites, locations, isolation rules and the `spec:` traceability are those of D13. Laravel-specific choices:
- **PHPUnit 11, not Pest.** The reused suites are PHPUnit classes; Pest would change the test code that is being compared.
- **Functional tests** extend `Illuminate\Foundation\Testing\TestCase` with `createApplication()` returning `bootstrap/app.php`, which is Laravel's way to test an application. Orchestra Testbench builds a skeleton application around a *package* and would not load lara-3's `bootstrap/app.php`; it is not used. Like `KernelBrowser`, Laravel's test client calls `$kernel->handle()` directly and never reaches the file-scope runner (L5), so everything that executes a legacy script stays in the `Http` suite.
- `bin/test-db`, the snapshot restore, `ServerProcess`, `HttpTestCase`, `IsolatedLegacyTestCase` and `bin/spec-coverage` are reused from the Symfony variant's first commit.
- CI: the same workflow and PHP matrix (8.2, 8.3, 8.4), with the deprecation check against `app/`.

## DX summary

Kept in `docs/dx-comparison.md`; the summary per phase is updated here at the end of the change.

| # | Fit | Note |
|---|---|---|
| L1 | worse | Laravel 12 is security-only from the start; yearly majors raise the PHP baseline |
| L3 | neutral | 175 global helpers, no collision with legacy today |
| L4 | worse | `Router::fallback()` is GET only; no query conditions, needs a custom validator |
| L5 | better / mixed | plain front controller makes file scope easy; no response event, `HandleExceptions` must be suspended |
| L7 | good | `db.connector.*` and `Connection::resolverFor()` are clean extension points |
| L8 | better | contextual attributes match `#[IcmsHandler]` directly |
| L10 | worse | no native-session bridge; a custom `Store`, middleware and group are needed |
| L11 | good | `Auth::viaRequest()` and `Gate::before()` fit ImpressCMS groups with little code |

## Risks / Trade-offs

- [The file-scope runner bypasses route middleware and `RequestHandled` for the marker.] → `HostKernel` dispatches `RequestHandled` and `terminate()` with the real response (L5); the bridge route has no middleware.
- [Suspending Laravel's error handling around legacy scripts can hide host errors raised in that window.] → The window is exactly the `require`; the shutdown function logs fatals through the Laravel logger.
- [`Request::capture()` enables `_method` override.] → A legacy POST with a field named `_method` would be routed as another method. No core form uses that name; the bridge test corpus covers it.
- [`ValidatePostSize` answers 413 where legacy saw an empty `$_POST`.] → Kept; documented as a behaviour difference outside the snapshot corpus.
- [A custom session store depends on `Store` internals (`$attributes`, flash keys).] → Pinned by `Functional` tests per scenario; checked again for every Laravel minor.
- [Laravel 12 security support ends on 2027-02-24.] → Recorded (L1); an upgrade to 13 needs PHP 8.3.
- The remaining risks of the Symfony design (central `common.php` extraction, alias-map redeclare race, DDL implicit commit, slow `Http` suite, upstream drift) apply unchanged, with the same mitigations.

## Migration Plan

The same order as the Symfony variant:

1. Test infrastructure and the characterization suite against plain `2.1-dev` (L15, L16), reused from the Symfony commits.
2. Library seams as separate commits, reused: autoload guard, `Icms\Kernel\Bootstrap`, `Icms\Db\Factory` provider, `Icms\Event` forwarder. The snapshots stay unchanged.
3. Root Composer and the Laravel host with the ported, hardened bridge (L1–L6). The snapshots must pass with `public/` as document root.
4. Shared runtime: database, container, handler locator, events (L7–L9).
5. Session and identity plus the demonstration route (L10–L12).
6. Documentation (`docs/laravel-host.md`) and the DX log for phases 1–5.

**Rollback**: point the document root back to `htdocs/`. All library seams are inert without the host.

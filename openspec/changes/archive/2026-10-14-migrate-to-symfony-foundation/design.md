## Context

See `proposal.md` (Why) for motivation. This change builds on `impresscms/impresscms` branch `2.1-dev`. Current state that shapes the approach:

- **`Icms\` library**: about 290 files in `htdocs/libraries/Icms/` (Auth, Config, Core, Data, Db, Feeds, File, Form, Image, Ipf, Member, Messaging, Module, Plugins, Preload, Sys, View, `Event.php`, `Utils.php`). Classes are namespaced and partly typed. Legacy names live in one central map, `Icms/aliases.php` (`icms_x_y` → `Icms\X\Y`, with renames such as `*_Object` → `Entity` and `icms_core_ObjectHandler` → `Icms\Core\EntityHandler`).
- **Autoloading**: `htdocs/composer.json` maps `Icms\\` → `libraries/Icms/`, classmaps `libraries/icms.php` and loads `libraries/Autoloader.php` as a `files` entry. The autoloader lazily turns legacy names into aliases through `aliases.php`, and falls back to a convention-based mapping and to the old lowercase `libraries/icms/` layout. On case-insensitive file systems, `Icms/` and `icms/` are the same directory.
- **`icms` facade**: the global `abstract class icms` in `libraries/icms.php` holds `$security`, `$logger`, `$db`, `$xoopsDB`, `$config`, `$session`, `$user`, `$module` and `$preload` as public statics. `$services['boot']` declares their factories by *legacy* name (`icms_db_Factory::pdoInstance`, `icms_core_Session::service`, …). `icms::handler($name, $optional)` caches handlers by name and constructs them with `icms::$xoopsDB`. `icms::setup()` `require`s `vendor/autoload.php` from the trust path or `htdocs/`.
- **Boot**: `htdocs/mainfile.php` defines the constants. `htdocs/include/common.php` then:
  - requires the Composer autoloader itself, with a self-healing fallback that writes a stub
  - runs `icms::setup()` and `icms::boot()`
  - loads language files
  - on `closesite`, `include`s `site-closed.php`, which exits
  - runs `icms::launchModule()`, the online-user bookkeeping and `finishCoreBoot`
- **Database**: `Icms\Db\Factory::pdoInstance()` creates a PDO from the `XOOPS_DB_*` constants. `Icms\Db\Factory::instance()` wraps it in `Icms\Db\Legacy\PdoDatabase`, which is `icms::$xoopsDB`.
- **Session**: `Icms\Core\Session::service()` registers a DB-backed save handler and sets the session name (`ICMSSESSION` or the configured `session_name`).
- **Events**: `Icms\Event::attach()`/`trigger()` (namespace and name); `Icms\Preload\Handler` for preload classes.
- **Module usage**: modules call `icms::$xoopsDB`, `icms::handler`, `icms::$user` and `icms::$security` hundreds of times, so these are the compatibility surface.
- **Prototype**: the Laravel prototype branch contains a working bridge (`LegacyScriptResolver`, `LegacyRequestHydrator`, `LegacyBridge`, top-level dispatch). It already uses Symfony HttpFoundation types and serves as reference implementation for the port.

## Goals / Non-Goals

**Goals:**
- A Symfony kernel is the outermost runtime. ImpressCMS `2.1-dev` runs inside it unchanged.
- There is one process-wide autoloader, one DB connection, one session and one identity.
- `icms::` becomes a facade over state that the Symfony container can also reach, without changing behaviour.
- The `Icms\` library stays framework-agnostic: it never depends on Symfony. Every integration seam is an optional hook that is inert without the host.
- This prepares `ipf-on-symfony`: IPF handlers become reachable as container services on top of DBAL.

**Non-Goals:**
- Changing IPF classes beyond the DB seam (no Form/Validator/Twig generation yet).
- Migrating legacy pages to Symfony controllers, except a test-only demonstration route.
- Doctrine ORM, Messenger, API Platform, Smarty replacement.
- Making Symfony the owner of legacy service instances. `icms::boot()` keeps constructing them in this change.
- Long-running PHP runtimes (worker mode). The static state in `icms::` makes them unsafe.

## Decisions

### D1. Symfony 7.4 LTS, `MicroKernelTrait`, PHP `>=8.2 <8.5`
`src/Kernel.php` uses `framework-bundle`, `security-bundle`, `twig-bundle`, `monolog-bundle` and `symfony/runtime`. 7.4 LTS gets security support until late 2029 and supports PHP 8.2, the `2.1-dev` baseline.
*Alternatives*: Laravel/Illuminate (the prototype). Rejected because Eloquent and facades compete with IPF and `icms::`. Bare Symfony components without FrameworkBundle. Rejected because security, session and configuration would have to be wired by hand. Symfony 8.x. Rejected for now because it requires PHP 8.4 and its non-LTS minors get only about 8 months of support, which would force a minor upgrade every six months during a multi-year migration.
*Upgrade path*: keep the host free of deprecations under 7.4 (deprecation log checked in CI) so that a move to the 8.4 LTS needs mostly a constraint bump. Raising the PHP baseline to 8.4 is a separate decision.

### D2. Host in the repository root, separate from the library
The host lives in the root of the `impresscms/impresscms` repository, next to `htdocs/`, and ships in the same release. Symfony-dependent code lives in `src/` (`App\`): kernel, bridge, security, container wiring. Library seams and the headless bootstrap live in `htdocs/libraries/Icms/` (`Icms\`) and use plain PHP only. A direct `htdocs/` deployment then still works and can be tested without Symfony installed.
*Alternatives*: Put the integration under `Icms\Symfony\`. Rejected because it would make the library's autoload namespace depend on packages that are absent in direct deployments. Ship the host as a separate package that requires ImpressCMS. Rejected because the goal is ImpressCMS itself on Symfony, not an add-on, and ImpressCMS is not installable as a library package (`mainfile.php`, `uploads/`, `templates_c/`).

### D3. Single Composer root
The root `composer.json`:
- requires the host packages plus everything from `htdocs/composer.json`
- maps `"Icms\\": "htdocs/libraries/Icms/"`
- classmaps `htdocs/libraries/icms.php`
- loads `htdocs/libraries/Autoloader.php` through `files`, so `aliases.php` keeps driving legacy names

`composer/composer` stays a runtime requirement in the root: package management from the admin UI is planned. Under the single root, its Symfony Console, Process, Filesystem and Finder dependencies resolve to the same 7.4 versions as the host. A future package-management UI must therefore operate on the *root* `composer.json`/`vendor/` when the host is used. It must run Composer in a separate process rather than in-process, because the request has already loaded classes from the `vendor/` tree that Composer would replace. That UI is out of scope here; this change only keeps the dependency compatible.

The PSR-0 `icms_` mapping is dropped from the root, because the alias autoloader covers it and the lowercase directory clashes on case-insensitive file systems. `public/index.php` defines `ICMS_HOST_AUTOLOADED`. `icms::setup()` and `include/common.php` skip their own `vendor/autoload.php` include (and the self-healing stub writer) when it is defined. `htdocs/composer.json` stays in place for direct deployments and the installer. A CI check keeps shared package versions aligned between both lock files.

### D4. Legacy fallback as a catch-all route; one shared URL space
The `LegacyBridgeController` route is `/{path}` with `requirements: {path: '.*'}`, `priority: -1000` and all methods. Reserved prefixes are routes with `priority: -900` that throw `NotFoundHttpException`. They are an optional mechanism for areas that must never fall through to legacy; none are configured by default. The router then produces 405 and `Allow` itself, and no custom ownership resolver is needed.

New Symfony routes get **no fixed prefix**. They share the URL space with legacy, and a migrated page keeps its legacy URL. Legacy URLs often identify a page by script path *and* query parameters (`/modules/system/admin.php?fct=users&op=edit`), while Symfony matches on path only. A route therefore claims such a URL with a path that includes `.php`, plus a `condition` on the query, for example `condition: "request.query.get('fct') == 'users'"`. Variants that do not match fall through to the catch-all and stay legacy. A debug command (`icms:routes:ownership <url>`) shows which side owns a given URL, so that overlaps stay visible without a prefix.
*Alternative*: A 404 exception listener. Rejected because it uses exceptions for control flow and would also bridge 404s raised inside Symfony controllers.

### D5. Legacy scripts run at file scope, outside the controller
Legacy code relies on top-level variables being global (`$icmsConfig`, `$xoopsTpl`, `$icmsTheme`, installer `$wizard`). The controller therefore only *prepares* execution and returns a `LegacyExecutionResponse` marker. A custom `symfony/runtime` runner in `public/index.php` detects the marker after `$kernel->handle()` and `require`s the script at file scope. It then builds the real response through `LegacyBridge::complete()`, dispatches `kernel.response`, sends it and calls `terminate()`. The shutdown-function path for `exit()` performs the same finalisation.
*Alternative*: Wrap the include in a closure with `extract($GLOBALS, EXTR_REFS)`. Rejected because `global` statements in later includes would still miss variables created afterwards.

### D6. Headless bootstrap: `Icms\Kernel\Bootstrap` extracted from `common.php`
`common.php` is split into ordered, idempotent stages that follow its current order, with verbatim logic:
1. `constants`: `mainfile.php` and `include/constants.php`
2. `autoload`: guarded per D3
3. `services`: `icms::setup()` and `icms::boot()`, which also set up the session and `icms::$user`
4. `output`: gzip/zlib output compression setup
5. `language`: core and system language files, plus the page-type file
6. `themeSelection`: resolve `theme_set` from POST or session
7. `siteState`: the `closesite` check
8. `module`: `icms::launchModule()`
9. `online`: online-user bookkeeping and `finishCoreBoot`

`common.php` becomes a thin runner that executes all stages in *interactive* mode, which includes `site-closed.php` and `exit`. In *headless* mode:
- `output` is skipped, because Symfony owns response encoding
- `siteState` throws `Icms\Kernel\SiteClosedException` or `NotInstalledException` instead of including a page and exiting
- theme rendering, which happens later in `header.php`, never runs The host's `App\Legacy\LegacyRuntime` boots lazily up to a requested stage, triggered by a `#[RequiresLegacy(stage: …)]` attribute or by resolving any `icms.*` service. `/up` therefore never boots legacy.
*Alternative*: Run all of `common.php` under output buffering and intercept `exit`. Rejected because it cannot be controlled, and an `exit` cannot be recovered from within the kernel.

### D7. DBAL owns the connection; `Icms\Db\Factory` takes PDO from it
`doctrine/dbal` ^4 is configured from the `XOOPS_DB_*` constants. An env processor reads them via the `constants` stage, so configuration is not duplicated. The new hook `Icms\Db\Factory::setConnectionProvider(?\Closure $provider)` changes `pdoInstance()`: when a provider is set, it returns `$provider()`, which is DBAL's native PDO (`getNativeConnection()`), and applies the same PDO attributes as today. `Icms\Db\Legacy\PdoDatabase`, and therefore `icms::$xoopsDB` and every handler, share the physical connection and its transaction state. Without a provider (direct deployment, installer), behaviour is unchanged.
*Alternative*: Wrap the legacy PDO in DBAL. DBAL 4 removed the `pdo` parameter. Rejected.

### D8. Container exposure through factory services and a handler locator
Lazy services `icms.db`, `icms.db.legacy`, `icms.config`, `icms.security`, `icms.logger`, `icms.session`, `icms.preload`, `icms.user` and `icms.module` use `LegacyRuntime::service('<name>')` as factory. That method boots the needed stage and returns `icms::$<name>`, so instances are identical. Each is also aliased to its class (`Icms\Core\Security`, `Icms\Db\Legacy\PdoDatabase`, …) for autowiring. Handlers go through `App\Legacy\HandlerLocator` (`ServiceProviderInterface`, id `icms.handlers`), which delegates to `icms::handler($name, true)` and throws `ServiceNotFoundException` on `false`. An `#[IcmsHandler('icms_member')]` argument resolver injects one handler.
*Alternative*: Register `Icms\Ipf\Handler` subclasses as autowired services now. Deferred to `ipf-on-symfony`. Their constructors take positional metadata (`$itemname`, `$keyname`, …) that is not autowirable, and doing it now would create two instances per handler next to the `icms::handler()` cache.

### D9. Events: forward `Icms\Event` to the EventDispatcher
`Icms\Event` gets the static hook `setForwarder(?\Closure $forwarder)`, which `trigger()` calls after the native listeners. The host installs a forwarder that dispatches `App\Legacy\LegacyEvent` as `icms.<namespace>.<name>` (for example `icms.icms.loadService-db`), but only when the compiled container has listeners for `icms.*`. Preload handling is untouched, and the reverse direction is out of scope.

### D10. Session: legacy configures, Symfony bridges
The ported hydrator loses `hydrateSession()`. For legacy-owned requests, `Icms\Core\Session` behaves as today. For Symfony-owned requests, `framework.session.storage_factory_id` is `session.storage.factory.php_bridge`, and `LegacyRuntime` boots the `services` stage, which runs `Icms\Core\Session::service()`, before any session access. Symfony never starts the session itself first.
*Alternative*: Move sessions to Symfony's `PdoSessionHandler`. Deferred because it changes storage and would break live sessions during rollout.

### D11. Identity: session authenticator plus group voter
`App\Security\IcmsSessionAuthenticator` boots up to the `module` stage. `icms::$user` is resolved during `services`, and the module context is needed for permissions. It then wraps `icms::$user` in `App\Security\IcmsUser` (`UserInterface`), with these roles:
- `ROLE_USER` for any logged-in user
- `ROLE_WEBMASTER` for `ICMS_GROUP_ADMIN`
- `ROLE_GROUP_<id>` for each group

`App\Security\ModulePermissionVoter` checks `icms::handler('icms_member_groupperm')` for the current module. The entry point redirects to `/user.php?xoops_redirect=<url>`. `#[RequiresLegacyToken]` validates `icms::$security->check()` on POST. Login, logout and autologin stay in ImpressCMS.
*Alternative*: A Symfony form login with a user provider. Deferred because it would duplicate `Icms\Core\Password`, autologin and protection logic.

### D12. Characterization first, against plain `2.1-dev`
Before the host exists, the characterization tests in the `Http` suite (`tests/Http/Characterization`, see D13) record snapshots of `php -S` serving `htdocs/` directly: status, selected headers and normalised body for a fixed URL corpus. The same suite must then pass with `public/` (host) as document root. This also proves that direct deployment and host deployment behave the same.

### D13. Test strategy
Legacy ImpressCMS defines constants (`mainfile.php`), keeps static state in `icms::` and runs scripts at file scope (D5). Those properties decide which kind of test can exercise which code:

| Suite | Location | Runs | Database | Isolation | Covers |
|---|---|---|---|---|---|
| `Library` | `htdocs/tests/Unit` | `Icms\` classes, alias map | no | per test | Library seams, aliases. Also runs under `htdocs/phpunit.xml.dist` (direct deployment) |
| `Unit` | `tests/Unit` | `App\` classes with fakes | no | per test | Resolver, hydrator, route conditions, roles mapping |
| `Integration` | `tests/Integration` | Kernel and headless legacy boot, in-process | yes | **separate process per test class** (`#[RunTestsInSeparateProcesses]`), because constants and `icms::` statics cannot be reset | `shared-runtime`: stages, DBAL/legacy transaction, container instances, events |
| `Functional` | `tests/Functional` | Symfony-owned routes via `KernelBrowser` | yes | separate process | `request-ownership` for Symfony routes, `session-identity` on Symfony routes |
| `Http` | `tests/Http` | Real server (`php -S`) via `symfony/browser-kit` `HttpBrowser` with a cookie jar | yes | fresh server per class, DB reset per class | Everything that executes a legacy script: `legacy-request-bridge`, cross-application session and identity, characterization snapshots |

- **Why legacy needs real HTTP**: `KernelBrowser` calls `$kernel->handle()` directly and never reaches the file-scope runner (D5). Superglobals, `header()`, `exit()` and session cookies also behave differently without a real SAPI. The `Http` suite uses a `ServerProcess` helper (Symfony Process) that starts `php -S 127.0.0.1:<free port> -t <docroot> <router>` with a configurable document root (`htdocs/` or `public/`), waits until the port is ready, and stops the server on teardown.
- **Test database**: `bin/test-db` (re)creates a MySQL schema `icms_test` from `htdocs/install/sql/mysql.structure.sql`, `mysql.data.sql` and the English language data. It then calls the installer's `make_groups()`/`make_data()` with fixed values and installs the system module. Finally, it seeds the fixture accounts:
  - `webmaster` (in `ICMS_GROUP_ADMIN`)
  - `member` (registered users only)
  - a closed-site toggle

  It writes a test-only `mainfile.php` and trust path under `var/test/`, with no secrets in the repository. Each `Integration`, `Functional` or `Http` class starts from a restored snapshot (`mysqldump` once, re-import per class). If scripted installation of the system module turns out not to be feasible, the fallback is a committed SQL dump generated from a web install, regenerated with every upstream schema change.
- **Traceability**: every `#### Scenario` in this change's specs maps to at least one test method. The test carries `#[Group('spec:<capability>')]` and a `@scenario <name>` doc tag. `bin/spec-coverage` lists scenarios without a matching test and fails CI on gaps.
- **CI**: one GitHub Actions workflow (`.github/workflows/tests.yml`) with a PHP matrix of 8.2, 8.3 and 8.4 and a MySQL 8.0 service:
  - lint
  - `Library` (twice: via `htdocs/` Composer and via root Composer)
  - `Unit`
  - `Integration`
  - `Functional`
  - `Http`
  - spec coverage
  - PHPStan
  - the deprecation check (D1)
  - the lock-alignment check (D3)

  The `Http` snapshots run against both document roots (`htdocs/` and `public/`) to prove that both deployment modes behave the same.
- **Configuration**: the root `phpunit.xml.dist` declares all five suites. `htdocs/phpunit.xml.dist` stays for standalone library work. Test-only dependencies (`phpunit/phpunit` ^11, `symfony/browser-kit`, `symfony/http-client`, `symfony/process`, `symfony/phpunit-bridge`, `phpstan/phpstan`) go in the root `require-dev`.

## Risks / Trade-offs

- [Legacy requests bypass parts of the kernel's controller flow (argument resolvers, controller events).] → `kernel.response` and `kernel.terminate` still run (D5), and failures go through the kernel logger and error renderer.
- [The `common.php` extraction touches the most central legacy file.] → Logic moves verbatim, the stage order is fixed, interactive mode stays the default, and the D12 snapshots gate the change.
- [`aliases.php` and the convention fallback can load a class through two routes, causing the "redeclare" race already seen during the PSR-4 migration.] → The single root autoloader registers `Autoloader.php` *before* Composer's PSR-4 loader (`files` runs at include time), and an alias-map test runs under the host autoloader as well.
- [A shared connection means a legacy DDL statement (module install/update) implicitly commits an open DBAL transaction.] → Document this. Host code must not wrap module install/update in a transaction.
- [The `Http` and process-isolated suites are slow, and a real server can be flaky (port races, start-up timing).] → Free-port allocation with a readiness probe, one server per test class rather than per test, DB snapshot restore instead of a reinstall, and fast `Library`/`Unit` suites that run first and fail early in CI.
- [Two lock files can drift.] → A CI version-alignment check (D3).
- [Event forwarding adds overhead.] → The forwarder is installed only when `icms.*` listeners exist.
- [Upstream `2.1-dev` keeps evolving.] → Seams are small, additive and inert. They land as separate commits ahead of the host, so they can be reviewed and merged independently.

## Migration Plan

1. Test infrastructure (D13), then the characterization suite against plain `2.1-dev` (D12).
2. Library seams, each as a separate upstream-ready PR: autoload guard (D3), `Icms\Kernel\Bootstrap` with `common.php` as runner (D6), `Icms\Db\Factory` provider (D7), `Icms\Event` forwarder (D9). The snapshots must stay unchanged.
3. Root Composer and the Symfony host with the ported, hardened bridge (D1–D5). The snapshots must pass with `public/` as document root.
4. Shared runtime: DBAL, container services, handler locator, events (D7–D9).
5. Session and identity plus the demonstration route (D10, D11).
6. Update the docs, including a note that the Laravel prototype was superseded.

**Rollback**: point the document root back to `htdocs/`. All library seams are inert without the host.

**Follow-up changes**:
- `ipf-on-symfony`: autoconfigured IPF handler services, `Icms\Db\Criteria` → DBAL QueryBuilder, Symfony Form/Validator generation from `Icms\Ipf\Entity::initVar()` metadata, a Twig bridge for `Icms\Ipf\View\Table`/`Single`, and an `AbstractIpfController` mirroring `Icms\Ipf\Controller`.
- `migrate-system-admin-pages`: per-page strangling of the system module.

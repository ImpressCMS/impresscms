## 1. Test infrastructure (D13)

- [x] 1.1 Create the implementation branch from `upstream/2.1-dev` and bring over the `openspec/` change. Do not bring the Laravel prototype code
- [x] 1.2 Create a minimal root `composer.json` with `require-dev`: `phpunit/phpunit` ^11, `symfony/browser-kit`, `symfony/http-client`, `symfony/process` and `symfony/phpunit-bridge` at `7.4.*`, plus `phpstan/phpstan`. Autoload-dev `"App\Tests\\": "tests/"`
- [x] 1.3 Create the root `phpunit.xml.dist` with the suites `Library` (`htdocs/tests/Unit`), `Unit`, `Integration`, `Functional` and `Http`, using strict settings that match `htdocs/phpunit.xml.dist`
- [x] 1.4 Implement `bin/test-db`: create schema `icms_test` from the install SQL files, call `make_groups()`/`make_data()` with fixed values, install the system module, and seed `webmaster` and `member`. Write a test `mainfile.php` and trust path under `var/test/`
  - The committed stub `htdocs/mainfile.php` honours `ICMS_MAINFILE` from the server environment (the installer overwrites the stub). The system module update moves `vendor/` into the trust path, so the test site gets its own `var/test/trust/vendor` and `htdocs/composer.json` is restored afterwards
- [x] 1.5 Verify that a scripted install yields a working site (the home page and admin login render). If it does not, switch to a committed SQL dump from a web install and document how to regenerate it
- [x] 1.6 Add DB snapshot/restore helpers (`mysqldump` once, re-import per test class) and a base `DatabaseTestCase`
  - Implemented as a shadow schema (`icms_test_snapshot`, `CREATE TABLE … LIKE` + `INSERT … SELECT`) instead of `mysqldump`: no client binaries needed, restore takes about 0.6 s
- [x] 1.7 Implement the `ServerProcess` helper: free port, `php -S` with a configurable document root (`htdocs/` or `public/`) and router, readiness probe, teardown. Add `HttpTestCase` with a BrowserKit `HttpBrowser` and cookie jar, and login helpers for `webmaster` and `member`
- [x] 1.8 Add the `IsolatedLegacyTestCase` base (separate process per class) for `Integration` and `Functional` tests that boot legacy
- [x] 1.9 Add the traceability convention (`#[Group('spec:<capability>')]` with a `@scenario <name>` tag) and `bin/spec-coverage`, which lists spec scenarios without a matching test and exits non-zero on gaps
- [x] 1.10 Add `.github/workflows/tests.yml`: PHP matrix 8.2/8.3/8.4, MySQL 8.0 service, `bin/test-db`, then lint → `Library` (via `htdocs/` and root Composer) → `Unit` → `Integration` → `Functional` → `Http` → spec coverage → PHPStan
- [x] 1.11 Add a smoke test per suite (for example, `Http`: `GET /` on `htdocs/` returns 200) and confirm the workflow is green on plain `2.1-dev`
  - All suites pass locally; the GitHub Actions run happens on the first push

## 2. Characterization baseline (D12)

- [x] 2.1 Add `tests/Http/Characterization` with snapshot assertions parameterised by document root
- [x] 2.2 Define the URL corpus: one CSS/JS/image asset, `/`, `/user.php`, a login POST, `/admin.php` as anonymous, a non-existent path, a 302-plus-`exit()` script, a two-cookie response, and a system-module IPF admin list as webmaster
- [x] 2.3 Implement snapshot normalisation (mask security tokens, session ids, timestamps, ETags). Record the snapshots with `htdocs/` as document root and commit them

## 3. Library seams in `Icms\` (D3, D6, D7, D9; framework-agnostic)

- [x] 3.1 Guard the `vendor/autoload.php` include and the self-healing stub writer in `htdocs/include/common.php` and `icms::setup()` with `!defined('ICMS_HOST_AUTOLOADED')`
- [x] 3.2 Create `htdocs/libraries/Icms/Kernel/Bootstrap.php` with the stages `constants`, `autoload`, `services`, `output`, `language`, `themeSelection`, `siteState`, `module` and `online`, moved verbatim from `common.php`
- [x] 3.3 Add `Icms\Kernel\SiteClosedException` and `NotInstalledException`, and a headless mode in which `output` is skipped and `siteState` throws instead of including or exiting
- [x] 3.4 Reduce `htdocs/include/common.php` to running all stages in interactive mode. The characterization snapshots must stay unchanged
- [x] 3.5 Add `Icms\Db\Factory::setConnectionProvider(?\Closure)`. When it is set, `pdoInstance()` uses the provider's PDO and applies the existing attributes. Add a unit test for both paths
  - The provider must return a PDO that implements `IConnection` (`Factory::connectionClass()`), because the legacy layer calls `escape()`; the error mode stays `ERRMODE_SILENT` for legacy code (the DBAL side handles its own, see 7.x)
- [x] 3.6 Add `Icms\Event::setForwarder(?\Closure)`, called by `trigger()` after the native listeners, with a unit test that it is inert when unset
- [x] 3.7 Add the new classes to the alias/PSR-4 tests where applicable, and confirm that `htdocs/tests` stays green under `htdocs/composer.json`
- [x] 3.8 Package 3.1–3.6 as upstream-ready commits (one per seam)

## 4. Root Composer and Symfony host (D1–D5)

- [x] 4.1 Extend the root `composer.json` from 1.2:
  - require `symfony/framework-bundle`, `runtime`, `yaml`, `monolog-bundle` and `twig-bundle` at `7.4.*`, plus the `htdocs/composer.json` requirements
  - set `php` to `>=8.2 <8.5`
  - autoload `"App\\": "src/"` and `"Icms\\": "htdocs/libraries/Icms/"`, the classmap `htdocs/libraries/icms.php`, and `files: htdocs/libraries/Autoloader.php`
- [x] 4.2 Keep `composer/composer` in the root `require` and verify it resolves against Symfony `7.4.*`
- [x] 4.3 Add a CI script that fails when a package shared by the root and `htdocs/` lock files resolves to different versions
- [x] 4.4 Run `htdocs/tests/Unit/AliasMapTest.php` and the alias tests under the root autoloader. Include a test where the modern name is referenced before the legacy name
- [x] 4.5 Create `src/Kernel.php`, `config/bundles.php`, `config/packages/framework.yaml`, `config/routes.yaml`, and `src/Controller/HealthController.php` (`GET /up`)
- [x] 4.6 Create `config/packages/icms_legacy.yaml` (legacy root, front controller, asset extensions, MIME map, reserved prefixes, non-public directories)
- [x] 4.7 Port `LegacyScriptResolver`, `LegacyResolution`, `PreparedLegacyExecution`, `LegacyRequestHydrator` and `LegacyBridge` from the prototype branch to `src/LegacyBridge/`
- [x] 4.8 Add the `LegacyBridgeController` catch-all route (priority -1000, all methods), which returns a `BinaryFileResponse` for assets and a `LegacyExecutionResponse` marker for scripts
  - The catch-all accepts every method, so the router alone never answers 405; `AppRoutingRouteOwnership::allowedMethods()` re-matches the path with the other methods and the bridge controller throws `MethodNotAllowedHttpException`
- [x] 4.9 Add the reserved-prefix routes (priority -900) that throw `NotFoundHttpException`; none are configured by default
- [x] 4.10 Add a test route that claims `/modules/system/admin.php` with `condition` on `fct`. Test that the matching variant goes to Symfony and other variants stay legacy
- [x] 4.11 Add the `icms:routes:ownership <url>` console command, which reports the owner (route name, or legacy script/strategy)
- [x] 4.12 Implement the custom `symfony/runtime` runner and `public/index.php`. The runner defines `ICMS_HOST_AUTOLOADED`, `require`s marked scripts at file scope, finalises through `LegacyBridge`, dispatches `kernel.response` and calls `terminate()`. Give the `exit()` shutdown path the same finalisation
  - `public/index.php` drives `AppRuntimeHostRuntime`/`HostRunner` itself (no generated `autoload_runtime.php`), so that the `require` happens at file scope. Legacy responses keep their raw header lines (`LegacyResponse`), and an exception that escapes a script goes to the exception handler the script installed, as without the host
- [x] 4.13 Run the characterization suite with `public/` as document root. All snapshots must match

## 5. Bridge hardening (legacy-request-bridge spec, D10)

- [x] 5.1 Remove session start-up from the hydrator. Snapshot and restore `$_SESSION` only when a session was already active
- [x] 5.2 Reject `templates_c`, `cache`, PHP files under `uploads` and anything under the trust path with 404
- [x] 5.3 Add resolver tests for percent-encoded traversal, backslash traversal and symlinks pointing outside the legacy root
- [x] 5.4 Log bridge failures through Monolog. In non-debug mode, render a generic 500 page without paths, messages or captured output. Keep the detailed page only when `kernel.debug` is set
- [x] 5.5 Restore the output-buffer level, working directory and superglobals in `finally` and on the shutdown path. Add a test with unclosed nested buffers
- [x] 5.6 Add characterization cases for a login POST with a custom session name and the DB session handler, and for failure responses in debug and non-debug mode
  - Bridge mechanics are tested against `tests/Fixtures/legacy-root` (`ICMS_LEGACY_ROOT`); failure responses in debug and non-debug mode are asserted there rather than snapshotted, since `htdocs/` has no equivalent

## 6. Headless runtime in the host (D6)

- [x] 6.1 Implement `App\Legacy\LegacyRuntime` with an idempotent `bootTo(string $stage)` over `Icms\Kernel\Bootstrap` in headless mode
- [x] 6.2 Add the `#[RequiresLegacy(stage:)]` attribute and a `kernel.controller` listener that boots the runtime
- [x] 6.3 Map `SiteClosedException` to 503 and `NotInstalledException` to a redirect to `/install/`
- [x] 6.4 Tests: `/up` boots no legacy stage and opens no DB connection, a test route returns the configured `sitename` without theme output, and a closed site gives 503 without terminating the process

## 7. Shared database connection (D7)

- [x] 7.1 Add `doctrine/dbal` ^4 and `doctrine/doctrine-bundle`, and an env processor that exposes `XOOPS_DB_*` after the `constants` stage
  - `Appegacydbalicmsdriver` opens the connection as `icmsdbmysqlconnection` and keeps the legacy `errmode_silent`; `sharedconnection`/`sharedstatement` switch to `errmode_exception` for each dbal call. the env processor is `%env(icms_db:xoops_db_host)%` etc.
- [x] 7.2 Register the `Icms\Db\Factory` connection provider from `LegacyRuntime` before the `services` stage
- [x] 7.3 Integration test: begin a DBAL transaction, insert through a system-module IPF handler, read through DBAL, roll back, then assert the row is absent in both layers

## 8. Container exposure (D8)

- [x] 8.1 Register the lazy factory services `icms.db`, `icms.db.legacy`, `icms.config`, `icms.security`, `icms.logger`, `icms.session`, `icms.preload`, `icms.user` and `icms.module`, plus class aliases for autowiring (`Icms\Core\Security`, `Icms\Db\Legacy\PdoDatabase`, …)
- [x] 8.2 Implement `App\Legacy\HandlerLocator` (`icms.handlers`) that delegates to `icms::handler($name, true)` and throws `ServiceNotFoundException` on `false`
- [x] 8.3 Add the `#[IcmsHandler('name')]` controller argument resolver
  - `#[IcmsHandler]` extends Symfony's `#[Autowire]` (expression `service('icms.handlers').get(...)`), so the controller argument is resolved by the service value resolver and works in autowired constructors too. As an `Autowire` attribute it also stops the container compiler from loading the legacy parameter class, whose file exits without `ICMS_ROOT_PATH`; the `icms.*` definitions live in `config/services/icms.yaml` without autoconfiguration for the same reason
- [x] 8.4 Tests: the container returns the same instance as `icms::$security`; a system-module IPF handler from the locator is the same instance as `icms::handler()`, whether it is looked up by the legacy or the modern class context; an unknown handler throws
- [x] 8.5 Characterization: an unmodified IPF module page renders identically, and `loadService-<name>` preload listeners still fire

## 9. Event forwarding (D9)

- [x] 9.1 Implement `App\Legacy\LegacyEvent` and the forwarder dispatching `icms.<namespace>.<name>`
- [x] 9.2 Install the forwarder only when the compiled container has listeners for `icms.*`
  - `AppDependencyInjectionegacyeventlistenerspass` (after `registerlistenerspass`) hands the `icms.*` event names with listeners to `legacyeventforwarder`; it is installed before a headless boot and before a legacy script runs, and dispatches only those events
- [x] 9.3 Test: a Symfony listener on `icms.icms.loadService-db` is invoked once with the DB service, and preload listeners are unaffected

## 10. Session and identity (D10, D11)

- [x] 10.1 Configure `framework.session` with the PHP bridge storage, and make sure `LegacyRuntime` runs `services` (`Icms\Core\Session::service()`) before any session access
  - `Appegacyegacysessionfactory` (decorates `session.factory`) boots the `services` stage before creating the session and uses `legacysessionattributebag`, so `$session->get('xoopsuserid')` reads the impresscms entry itself instead of symfony's `_sf2_attributes`
- [x] 10.2 Implement `App\Security\IcmsUser` (id, uname, display name, groups; roles `ROLE_USER`, `ROLE_WEBMASTER`, `ROLE_GROUP_<id>`)
- [x] 10.3 Implement `App\Security\IcmsSessionAuthenticator` and an entry point that redirects to `/user.php?xoops_redirect=<url>`
  - The firewall is lazy and therefore not stateless (SecurityBundle disables lazy for stateless firewalls): legacy pages never authenticate, and when a route asks for the user the authenticator re-reads `icms::$user` and clears a token restored from the session for anonymous visitors
- [x] 10.4 Implement `App\Security\ModulePermissionVoter` on top of `icms_member_groupperm`
  - Attributes `icms_perm:<name>`: without subject the right on the current module (module id 1 for the item), with an integer subject the item right in the current module
- [x] 10.5 Add `#[RequiresLegacyToken]`, which validates `icms::$security->check()` on POST
- [x] 10.6 Add the demonstration route `/whoami` (`ROLE_WEBMASTER`, registered only in the `dev` and `test` environments)
- [x] 10.7 Tests:
  - a session value written by legacy is read by Symfony
  - a custom session name results in a single cookie
  - anonymous → redirect, non-webmaster → 403, webmaster → 200
  - logout → anonymous
  - a token is accepted once and rejected on replay

## 11. Cleanup and documentation

- [ ] 11.1 Record in `docs/` that the Laravel prototype (branch `copilot/implement-phase-1-strangler-migration`) and its `migrate-basic-framework-to-laravel` change were superseded by this change
- [ ] 11.2 Write `docs/symfony-host.md` covering request flow, bootstrap stages, library seams, container service ids, roles and unsupported runtimes
- [ ] 11.3 Update `README.md` to describe both deployment modes: `public/` with the host, or `htdocs/` directly
- [ ] 11.4 Configure the test suite to fail on Symfony deprecations triggered by `src/` (`SYMFONY_DEPRECATIONS_HELPER` or the PHPUnit bridge equivalent), so the host stays ready for a later move to the 8.4 LTS
- [ ] 11.5 Run all suites (`Library`, `Unit`, `Integration`, `Functional`, `Http`) on both document roots, and run `bin/spec-coverage` with no gaps. Run PHPStan at level 6 on `src/` and the new `Icms\Kernel` classes with no errors

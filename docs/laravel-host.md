# The Laravel host

ImpressCMS runs inside a Laravel 12 application, the *host*. Laravel routes
answer directly. Every other request is executed by unchanged legacy
ImpressCMS through the *legacy bridge*. Both share one autoloader, one
database connection, one session and one identity, so pages can move to
Laravel one at a time while modules, themes and the `icms::` API keep working.

This is **lara-3**, the Laravel counterpart of the Symfony host of ImpressCMS
pre-3 (`docs/symfony-host.md` on that branch). It exists to compare the two
frameworks on the same task; the comparison is in `docs/dx-comparison.md`.

A site consists of two directories, as ImpressCMS sites always have:

- **the web root**, which holds the contents of `htdocs/`;
- **the trust path**, chosen during installation and recorded in `mainfile.php`
  (`XOOPS_TRUST_PATH`). The host lives here: its code, its dependencies and its
  configuration.

The designs are in `openspec/changes/archive/2026-11-02-migrate-to-laravel-foundation/`
and `openspec/changes/relocate-laravel-host-into-trust-path/`.

## Layout

| Where | What |
|---|---|
| web root `index.php` | entry point: a short preamble hands the request to the host in the trust path. Without an installed host, it is the home page |
| web root `include/host.php` | finds the trust path, `vendor/` and the host, without executing `mainfile.php` |
| web root `trust_dist/` | staging directory of a release package. It holds the host until the installer or the system module update copies it into the trust path, and is never served |
| trust path `host.php` | the front controller. It runs legacy scripts at file scope |
| trust path `composer.json`, `vendor/` | the one Composer root: Laravel and the libraries legacy uses |
| trust path `bootstrap/app.php`, `bootstrap/providers.php` | the application: paths, routing, middleware, exception rendering, service providers |
| trust path `app/` (`App\`) | HTTP kernel, legacy bridge, runtime integration, session, identity |
| trust path `config/*.php` | settings that differ from Laravel's defaults (see *Configuration*) |
| trust path `routes/web.php`, `routes/test.php` | routes and the legacy fallback; test-only routes |
| trust path `artisan` | Laravel console (`icms:routes:ownership`, `route:list`, …) |
| trust path `.env.local` | the site's settings. Written during installation and kept by updates |
| trust path `var/` | Laravel's storage directory: logs (`var/log/<env>.log`), compiled views, file cache |
| trust path `bootstrap/cache/` | Laravel's package and service manifests, written on the first request |
| trust path `<hash>.php`, `cache/`, `modules/` | legacy: database credentials, HTMLPurifier cache, Protector |

In a repository checkout, the repository root is the trust path skeleton and
`htdocs/` is the web root. `bin/build-dist` assembles `htdocs/trust_dist/` for a
release package: the host files plus `vendor/`, installed without development
packages. The `Icms\` library is not in `composer.json`, because the trust path
cannot name a web root that differs per site. `bootstrap/app.php` registers it
from `<web root>/libraries/Autoloader.php` as soon as the configuration is
loaded, for web requests and `artisan` alike; that file also resolves the
legacy `icms_*` names.

## Request flow

1. The web server hands a request to the web root's `index.php` (see
   *Routing levels*). The preamble finds `<trust>/host.php`. It records the web
   root and the trust path in `$_SERVER` (`ICMS_LEGACY_ROOT`,
   `ICMS_TRUST_PATH`) and requires the host. `host.php` defines
   `ICMS_HOST_AUTOLOADED`, so ImpressCMS skips its own autoloader, loads the
   trust path's `vendor/autoload.php`, records PHP's error settings
   (`App\Runtime\PhpEnvironment`) before Laravel changes them, and hands the
   request to `App\Runtime\HostRunner`.
2. The router decides. Laravel routes have no fixed prefix. A route can claim
   an existing legacy URL, including `.php` paths, and with `whereQuery()` or
   `matching()` only some variants of it:

   ```php
   Route::get('/modules/system/admin.php', UsersController::class)
       ->whereQuery(['fct' => 'users'])
       ->matching(TakeoverEnabled::class);   // an App\Routing\RouteCondition
   ```

   Unclaimed requests reach `App\Http\Controllers\LegacyBridgeController`,
   an all-method route marked as fallback. Paths that a Laravel route serves
   with other methods answer 405 there. Reserved prefixes
   (`icms.legacy.reserved_prefixes`, none by default) always answer 404 and
   never reach legacy. `php artisan icms:routes:ownership '<url>' --method=POST`
   shows who owns a URL.
3. The bridge resolves the path under the web root. The following are 404:
   - traversal, and symlinks out of the root;
   - `trust_dist/`, `templates_c/`, `cache/`, `tests/`, and the directories
     legacy protects with `.htaccess`;
   - the trust path, if it lies under the root;
   - PHP files under `uploads/`.

   Static files are served directly, with ETag and Last-Modified. Unknown paths
   run the legacy `index.php`.
4. For a PHP script the controller only returns a marker. After the kernel has
   handled the request, `host.php` prepares the superglobals and the working
   directory and `require`s the script **at file scope**, because legacy code
   relies on top-level variables being globals. For `/` that script is
   `index.php` again; with `ICMS_HOST_AUTOLOADED` defined, its preamble is
   skipped. The script's output, status code and raw header lines become the
   response; `RequestHandled` and `terminate()` run for it. A script that calls
   `exit()` is finished the same way by a shutdown function. Afterwards the
   host's superglobals, working directory, output buffer level and error
   handlers are restored.
5. While a script runs, Laravel's error handling is inert
   (`HandleExceptions::forgetApp()`), and PHP's own error settings apply, as
   without the host. An exception escaping a script goes to the exception
   handler the script installed (ImpressCMS installs one while booting).
   Stack traces end at the script. Otherwise the bridge renders a generic 500
   page. Details appear only with `APP_DEBUG=1`, and are always logged to
   `<trust>/var/log/<env>.log`.

**Route caching is not supported.** Laravel's compiled matcher
(`php artisan route:cache`) ignores `whereQuery()` and `matching()`, so every
takeover would claim all variants of its URL. The host refuses to serve
requests while routes are cached; run `php artisan route:clear`.

## Routing levels and web servers

The host needs no web server configuration. Each step of configuration routes
more requests through it:

| Level | Through the host | Web server |
|---|---|---|
| 1 | `/`, `/index.php`, `/index.php/<route>` | nothing to configure; works right after an upgrade |
| 2 | and unknown paths, so Laravel routes with clean URLs | fallback to `index.php`; the default of FrankenPHP's `php_server`, Herd and `php -S` |
| 3 | and every existing `.php` script (the full strangler fig) | every `.php` request to `index.php` |

Scripts that the web server executes itself (levels 1 and 2) work as without
the host. They share the session and the identity with Laravel routes, because
both use ImpressCMS' database session. Routes that claim existing `.php` URLs,
and the legacy event forwarder, only apply to requests that reach the host.

Example configurations for levels 2 and 3, tested in CI against the HTTP suite
(job `webservers`):

| Server | File |
|---|---|
| Apache 2.4 | `htdocs/htaccess.txt` (rename it to `.htaccess`) and `docs/server/apache-vhost.conf` |
| nginx + PHP-FPM | `docs/server/nginx.conf` |
| FrankenPHP (classic mode) | `docs/server/Caddyfile` |
| Laravel Herd / Valet | `docs/server/herd/ImpressCmsValetDriver.php`: copy it to Herd's `config/valet/Drivers/` folder. Level 3 by default; `ICMS_HERD_ROUTING=2` in `<trust>/.env.local` switches to level 2 |

Every configuration denies the paths in step 3 of the request flow. nginx and
Caddy ignore `.htaccess` files, so on those servers these rules are the only
protection. A trust path inside the document root would be public there.
The installer therefore proposes one next to the document root and asks for
confirmation otherwise, and the admin dashboard warns about it.

Herd's own Laravel driver only recognises a directory with `artisan` and
`public/index.php`. An ImpressCMS web root has neither, so without the driver
above Herd serves it with its basic driver, which is level 2.

## Installation and upgrade

The release package carries the host in `htdocs/trust_dist/`.
`Icms\Core\Filesystem::installTrustDistribution()` installs it:

1. Each entry is copied into the trust path as `<entry>.new` and verified
   (same files, same sizes). A failed copy changes nothing.
2. The verified entries are swapped in. `var/` and `.env.local` are kept;
   `bootstrap/` is replaced, and with it Laravel's generated manifests.
3. A missing `.env.local` is written, with `APP_ENV=prod`, `APP_DEBUG=0`, a
   random `APP_KEY` and `ICMS_LEGACY_ROOT`. `var/framework/cache/` and
   `var/framework/views/` are emptied.
4. A `vendor/` moved into the trust path by system dbversion 49 is kept as
   `vendor.pre-50`, and its autoloader stub (`<trust>/libraries/Autoloader.php`)
   is removed.
5. `trust_dist/` and a leftover web-root `vendor/` are removed.

Two callers run it:

- **The installer**, on the page after the paths.
- **The system module update** (step 50), whenever `trust_dist/` exists. It
  activates at the end of the update request, because that request may still
  load classes from the trees it replaces.

Until the host is installed, legacy loads its dependencies from the first of
these that exists: `<trust>/vendor`, `<web root>/trust_dist/vendor`,
`<web root>/vendor`. So a site boots right after its files are uploaded:

- **From 2.0.x** (no Composer, dbversion 48): upload the files, log in as
  webmaster and update the system module. The dashboard points to the
  update.
- **From 2.1-dev** (dbversion 49, `vendor/` in the trust path): update the
  system module.

If the update cannot copy the files (permissions, disk space), upload the
contents of `trust_dist/` into the trust path yourself, delete `trust_dist/`
and run the update again. The trust path must be writable: Laravel writes
`bootstrap/cache/packages.php` and `services.php` there on the first request.

## Bootstrap stages

`htdocs/include/common.php` is a thin runner over `Icms\Kernel\Bootstrap`:

| Stage | Does |
|---|---|
| `constants` | `mainfile.php` (headless mode) and `include/constants.php` |
| `autoload` | Composer autoloader (skipped with `ICMS_HOST_AUTOLOADED`), `libraries/Autoloader.php`, core function files |
| `services` | `icms::setup()`, `icms::boot()`: security, logger, database, config, session, current user |
| `output` | gzip/zlib output compression (headless: left to the host) |
| `language` | time zone, core and system language files, page-type file |
| `themeSelection` | `theme_set` from POST or session |
| `siteState` | `closesite` check |
| `module` | `icms::launchModule()` |
| `online` | online-user bookkeeping, `finishCoreBoot` |

Legacy pages run all stages in *interactive* mode. Laravel routes boot
*headless*, and only as far as they need, through `App\Legacy\LegacyRuntime`:

```php
Route::get('/example', ExampleController::class)->middleware('icms.legacy:language');   // or no stage: every stage
```

Headless mode sends nothing and never exits: a closed site raises
`Icms\Kernel\SiteClosedException` (503), a missing installation
`Icms\Kernel\NotInstalledException` (redirect to `/install/`). Until legacy
installs its own error handler, PHP handles legacy warnings, not Laravel's
handler. `GET /up` boots nothing.

## Library seams

The `Icms\` library does not depend on Laravel. Each seam is inert without the
host, and all of them are the seams of the Symfony host. Only
`installTrustDistribution()` names Laravel: the key it writes into `.env.local`
and the caches it empties.

| Seam | Purpose |
|---|---|
| `ICMS_HOST_AUTOLOADED` guard in `Icms\Kernel\Bootstrap::autoload()` and `icms::setup()` | one autoloader per process |
| `include/host.php` and the preamble of `index.php` | find and enter the host |
| `Icms\Core\Filesystem::installTrustDistribution()`, system update step 50 | install the host into the trust path |
| `Icms\Kernel\Bootstrap` (+ `SiteClosedException`, `NotInstalledException`) | staged, headless boot |
| `Icms\Db\Factory::setConnectionProvider(?Closure)` | take the PDO from Laravel |
| `Icms\Event::setForwarder(?Closure)` | observe every legacy event |
| `ICMS_MAINFILE` in the stub `htdocs/mainfile.php` | site configuration named by the environment (tests) |

## Database

Laravel owns the one connection, `icms`, the default connection. Host,
database, credentials, charset and table prefix come from `mainfile.php` when
it is first opened, so `DB::table('users')` reads `icms_users`.
- `App\Database\IcmsMySqlConnector` (bound as `db.connector.mysql`) opens it as
  ImpressCMS' own PDO class, with the charset in the DSN.
- `App\Database\SharedMySqlConnection` switches the PDO to exceptions for each
  Laravel query and back to PHP's silent mode, which legacy code expects. It
  never reconnects: a second connection would no longer share transactions.
- The connection keeps PDO's defaults (emulated prepares) and sends no
  `SET NAMES`, `sql_mode`, `time_zone` or isolation level, so legacy sees the
  connection it would have opened itself. Do not add `strict`, `modes`,
  `timezone` or `charset` to `config/database.php`.

`LegacyRuntime` hands the PDO to `Icms\Db\Factory` before the `services` stage,
so `icms::$db`, `icms::$xoopsDB`, IPF handlers and Laravel share transactions.
Legacy DDL (module install and update) commits an open transaction implicitly,
so never wrap it in `DB::transaction()`.

Use the query builder or IPF handlers for ImpressCMS data. Eloquent models
compete with IPF and are not used for it.

## Container services

| Id | Autowiring type | Value |
|---|---|---|
| `icms.db` | `Icms\Db\IConnection` | `icms::$db` |
| `icms.db.legacy` | `Icms\Db\Legacy\PdoDatabase` | `icms::$xoopsDB` |
| `icms.config` | `Icms\Config\Handler` | `icms::$config` |
| `icms.security` | `Icms\Core\Security` | `icms::$security` |
| `icms.logger` | `Icms\Core\Logger` | `icms::$logger` |
| `icms.session` | `Icms\Core\Session` | `icms::$session` |
| `icms.preload` | `Icms\Preload\Handler` | `icms::$preload` |
| `icms.user` | – | `icms::$user` (null for anonymous visitors), resolved anew each time |
| `icms.module` | – | `icms::$module` (may be null), resolved anew each time |
| `icms.handlers` | `App\Legacy\HandlerLocator` | handlers by legacy name (`icms_member`), PSR-4 class (`Icms\Member\Handler::class`) or `module:<dirname>/<name>` |

Each service boots only the stage that creates it, and returns the very
instance legacy code uses. Inject a single handler with a contextual attribute:

```php
public function __invoke(#[IcmsHandler('icms_data_page')] \Icms\Data\Page\Handler $pages)
```

Legacy class files exit unless ImpressCMS constants are defined. Laravel
reflects a type only when it resolves it, and resolves contextual attributes
before the type, so this is safe; a bare legacy type hint without the attribute
is not.

## Events

Legacy events are dispatched to Laravel listeners as `icms.<namespace>.<name>`,
with an `App\Legacy\LegacyEvent`:

```php
Event::listen('icms.icms.loadService-db', function (LegacyEvent $event) { $db = $event->parameters['service']; });
```

Register such listeners in a service provider; string-named events are not
discovered. Legacy handlers and preloads run first and are not affected.

## Session and identity

ImpressCMS owns the session: `Icms\Core\Session` sets the name (`session_name`
with `use_mysession`), the lifetime and the database save handler. Laravel's
session driver `icms` (`App\Session\IcmsSessionStore`) works on `$_SESSION`:
`session('xoopsUserId')` reads the ImpressCMS entry. It sets no cookie and
writes nothing itself; PHP writes the session at shutdown. Routes that need the
session use the `icms` middleware group instead of `web`:

| Group | Middleware |
|---|---|
| `icms` | `StartIcmsSession`, `ShareErrorsFromSession`, `SubstituteBindings` |

`web` is not used: its cookie encryption would hide ImpressCMS' cookies from
Laravel code, and ImpressCMS' token, not Laravel's, protects forms.

The user logged in to ImpressCMS is the Laravel user (`App\Auth\IcmsUser`,
guard `icms`). Login, logout and autologin stay in ImpressCMS.

| Middleware / ability | Granted to |
|---|---|
| `auth` | every logged-in user |
| `can:webmaster` | members of the webmasters group (`ICMS_GROUP_ADMIN`) |
| `can:icms_group:<id>` | members of that group |
| `can:icms_perm:<name>` | ImpressCMS group permission on the current module; `Gate::allows('icms_perm:<name>', $itemId)` checks an item of the current module |

`IcmsUser::roles()` names the roles of the Symfony host (`ROLE_USER`,
`ROLE_WEBMASTER`, `ROLE_GROUP_<id>`). Denied anonymous visitors are redirected
to `/user.php?xoops_redirect=<url>`; denied users get 403. The `icms.token`
middleware makes POST requests carry a valid ImpressCMS security token
(`XOOPS_TOKEN_REQUEST`), which is accepted once. `GET /whoami` (`local` and
`test` only) shows the current user to webmasters.

## Configuration

Laravel reads one file, `<trust>/.env.local` (`loadEnvironmentFrom()`); every
default lives in `config/*.php`. No web server needs to pass environment
variables. Values in `$_SERVER` take precedence over the file: the preamble
puts the web root and the trust path there for every web request.

| Variable | Meaning |
|---|---|
| `APP_ENV` | `prod` (written by the installer; `production`, Laravel's default, behaves the same), `local` or `test`. `/whoami` exists in `local` and `test`; the test routes and the `admin-next` reserved prefix only in `test` |
| `APP_DEBUG` | `1` shows detailed errors; keep it `0` in production |
| `APP_KEY` | Laravel's encryption key, written by the installer (`php artisan key:generate --show` makes another) |
| `ICMS_LEGACY_ROOT` | the web root. Set by `index.php` for web requests; in `.env.local` for `artisan`; `htdocs/` in a checkout |
| `ICMS_TRUST_PATH` | set by `index.php`; refused by the bridge if it lies under the web root |
| `ICMS_HERD_ROUTING` | `2` switches the Herd driver to routing level 2 |

The host changes these Laravel defaults:

| File | Change | Why |
|---|---|---|
| `config/session.php` | driver `icms` | ImpressCMS owns the session |
| `config/cache.php`, `config/queue.php` | `file`, `sync` | the skeleton's `database` drivers would open the ImpressCMS connection whenever they are resolved, for example by artisan |
| `config/filesystems.php` | `local` disk without `serve` | Laravel 12 serves it under `/storage/{path}`, which belongs to ImpressCMS |
| `config/view.php` | compiled path without `realpath()` | error pages need Blade before `var/framework/views` exists |
| `config/logging.php` | `var/log/<env>.log` | |
| `bootstrap/app.php` | no `TrimStrings`, no `ConvertEmptyStringsToNull` | takeovers must see the input legacy saw |

## Unsupported runtimes

Long-running runtimes are not supported: Laravel Octane on Swoole, RoadRunner
or FrankenPHP worker mode. Octane resets Laravel's state between requests, but
not what legacy keeps per process: constants from `mainfile.php`, the `icms::`
statics, static caches such as `icms_getModuleHandler()`'s, and output and
header state after `exit()`.

## Tests

| Suite | Runs |
|---|---|
| `Library` | `htdocs/tests/Unit`, including `installTrustDistribution()` |
| `Unit` | `App\` classes, the alias map, the helper-collision check, `include/host.php` |
| `Integration` | headless boot, the shared connection, the container, events, the dashboard warnings (one process per test) |
| `Functional` | Laravel routes through Laravel's test client (`Illuminate\Foundation\Testing\TestCase`) |
| `Http` | a real web server: characterization snapshots, the bridge, session and identity, the entry point, the installer, the upgrade |

```bash
php bin/test-db
vendor/bin/phpunit
ICMS_TEST_ROUTING=2 vendor/bin/phpunit --testsuite Http
php bin/build-dist && vendor/bin/phpunit tests/Http/HostUpgradeTest.php
php bin/spec-coverage
vendor/bin/phpstan analyse
```

The test site has the production layout. `bin/test-db` installs it with the
trust path `var/test/trust`, and writes two one-line stubs there, `host.php` and
`vendor/autoload.php`, which require the repository's files. Requests therefore
take the production path (`htdocs/index.php` → `<trust>/host.php`) without a
copy of `vendor/`.

The `Http` suite serves `htdocs/` with `php -S`:
- `ICMS_TEST_ROUTING=3` (default): every request goes to `index.php` (router);
- `ICMS_TEST_ROUTING=2`: existing scripts run directly, unknown paths go to
  `index.php`.

Its snapshots are those of the Symfony host. They must match at both levels.
Responses of the host carry `X-Test-Icms-Host: 1` in the `test` environment
(`App\Testing\HostMarker`, on `RequestHandled`), so the tests can tell them
apart from scripts the web server ran itself. `HostUpgradeTest` runs only when
`htdocs/trust_dist` exists, and removes it, as a real update does.

With `ICMS_TEST_BASE_URL` the suite tests an already running server instead:
Apache, nginx and FrankenPHP with the files from `docs/server/` in CI
(`.github/scripts/webserver.sh`), or Laravel Herd. Install that checkout's test
site into its own schema and name it for both the site and the tests:

```bash
ICMS_TEST_DB_NAME=lara3 php bin/test-db
ICMS_TEST_DB_NAME=lara3 ICMS_TEST_BASE_URL=http://lara3.test ICMS_TEST_ROUTING=3 vendor/bin/phpunit --testsuite Http
```

The server must pass `ICMS_MAINFILE=<checkout>/var/test/mainfile.php` and
`APP_ENV=test` to PHP, as `webserver.sh` does; the test site's trust path is
then the repository root's stub. The bridge fixture tests need a `php -S` of
their own and are skipped then. Laravel's test client never reaches the
file-scope runner, so everything that executes a legacy script is tested over
HTTP.

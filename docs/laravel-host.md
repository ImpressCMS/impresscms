# The Laravel host

ImpressCMS runs inside a Laravel 12 application, the *host*. Laravel routes
answer directly. Every other request is executed by unchanged legacy
ImpressCMS through the *legacy bridge*. Both share one autoloader, one
database connection, one session and one identity, so pages can move to
Laravel one at a time while modules, themes and the `icms::` API keep working.

This is **lara-3**, the Laravel counterpart of the Symfony host of ImpressCMS
pre-3 (`docs/symfony-host.md` on that branch). It exists to compare the two
frameworks on the same task; the comparison is in `docs/dx-comparison.md`.
The design is in `openspec/changes/archive/2026-11-02-migrate-to-laravel-foundation/`.

In this stage the host lives in the repository root, next to `htdocs/`, and
`public/` is its document root. The move into the trust path follows, as it did
for the Symfony host.

## Layout

| Where | What |
|---|---|
| `public/index.php` | the front controller. It runs legacy scripts at file scope |
| `bootstrap/app.php`, `bootstrap/providers.php` | the application: paths, routing, middleware, exception rendering, service providers |
| `app/` (`App\`) | HTTP kernel, legacy bridge, runtime integration, session, identity |
| `config/*.php` | settings that differ from Laravel's defaults (see *Configuration*) |
| `routes/web.php`, `routes/test.php` | routes and the legacy fallback; test-only routes |
| `artisan` | Laravel console (`icms:routes:ownership`, `route:list`, …) |
| `.env.local` | settings of this checkout (not committed) |
| `var/` | Laravel's storage directory: logs (`var/log/<env>.log`), compiled views, file cache |
| `bootstrap/cache/` | Laravel's package and service manifests |
| `htdocs/` | ImpressCMS, unchanged; pointing a web server at it directly still works |

There is one Composer root. It requires `laravel/framework` and the packages of
`htdocs/composer.json`, and autoloads `App\`, the `Icms\` library, the `icms`
class and `htdocs/libraries/Autoloader.php`, which resolves the legacy `icms_*`
names. `bin/check-lock-alignment` keeps both lock files at the same versions.

## Request flow

1. `public/index.php` defines `ICMS_HOST_AUTOLOADED`, so ImpressCMS skips its
   own autoloader. It records PHP's error settings (`App\Runtime\PhpEnvironment`)
   before Laravel changes them, and hands the request to
   `App\Runtime\HostRunner`.
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
3. The bridge resolves the path under the legacy root. The following are 404:
   traversal and symlinks out of the root, `templates_c/`, `cache/`, the trust
   path if it lies under the root, and PHP files under `uploads/`. Static files
   are served directly, with ETag and Last-Modified. Unknown paths run the
   legacy `index.php`.
4. For a PHP script the controller only returns a marker. After the kernel has
   handled the request, `public/index.php` prepares the superglobals and the
   working directory and `require`s the script **at file scope**, because
   legacy code relies on top-level variables being globals. The script's
   output, status code and raw header lines become the response;
   `RequestHandled` and `terminate()` run for it. A script that calls `exit()`
   is finished the same way by a shutdown function. Afterwards the host's
   superglobals, working directory, output buffer level and error handlers are
   restored.
5. While a script runs, Laravel's error handling is inert
   (`HandleExceptions::forgetApp()`), and PHP's own error settings apply, as
   without the host. An exception escaping a script goes to the exception
   handler the script installed (ImpressCMS installs one while booting).
   Otherwise the bridge renders a generic 500 page. Details appear only with
   `APP_DEBUG=1`, and are always logged.

**Route caching is not supported.** Laravel's compiled matcher
(`php artisan route:cache`) ignores `whereQuery()` and `matching()`, so every
takeover would claim all variants of its URL. The host refuses to serve
requests while routes are cached; run `php artisan route:clear`.

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
host, and all of them are the seams of the Symfony host, unchanged:

| Seam | Purpose |
|---|---|
| `ICMS_HOST_AUTOLOADED` guard in `Icms\Kernel\Bootstrap::autoload()` and `icms::setup()` | one autoloader per process |
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

Laravel reads one file, `.env.local` (`loadEnvironmentFrom()`); every default
lives in `config/*.php`. Variables the process already has, for example from
`php -S`'s environment or `SetEnv`, take precedence over the file.

| Variable | Meaning |
|---|---|
| `APP_ENV` | `production` (default), `local` or `test`. `/whoami` exists in `local` and `test`; the test routes and the `admin-next` reserved prefix only in `test` |
| `APP_DEBUG` | `1` shows detailed errors; keep it `0` in production |
| `APP_KEY` | Laravel's encryption key (`php artisan key:generate`) |
| `ICMS_LEGACY_ROOT` | the legacy root; `htdocs/` by default |
| `ICMS_TRUST_PATH` | refused by the bridge if it lies under the legacy root |

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
| `Library` | `htdocs/tests/Unit` |
| `Unit` | `App\` classes, the alias map, the helper-collision check |
| `Integration` | headless boot, the shared connection, the container, events (one process per test) |
| `Functional` | Laravel routes through Laravel's test client (`Illuminate\Foundation\Testing\TestCase`) |
| `Http` | a real `php -S`: characterization snapshots, the bridge, session and identity |

```bash
php bin/test-db
vendor/bin/phpunit
ICMS_TEST_DOCROOT=public vendor/bin/phpunit --testsuite Http
php bin/spec-coverage
vendor/bin/phpstan analyse
```

The `Http` suite and its snapshots are those of the Symfony host. They must
pass with `htdocs/` and with `public/` as document root.

With `ICMS_TEST_BASE_URL` the suite tests an already running server instead,
for example Laravel Herd serving a checkout. Herd's Laravel driver serves
`public/` and sends every other request to `public/index.php`, so no driver of
our own is needed. Install that checkout's test site into its own schema and
name it for both the site and the tests:

```bash
ICMS_TEST_DB_NAME=lara3 php bin/test-db
ICMS_TEST_DB_NAME=lara3 ICMS_TEST_BASE_URL=http://lara3.test vendor/bin/phpunit --testsuite Http
```

with `.env.local` in that checkout:

```
APP_ENV=test
APP_DEBUG=0
ICMS_MAINFILE=<checkout>/var/test/mainfile.php
```

The bridge fixture tests need a `php -S` of their own and are skipped then. Laravel's test client
never reaches the file-scope runner, so everything that executes a legacy
script is tested over HTTP.

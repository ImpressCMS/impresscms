# The Symfony host

ImpressCMS runs inside a Symfony 7.4 LTS application, the *host*. Symfony
routes answer directly. Every other request is executed by unchanged legacy
ImpressCMS through the *legacy bridge*. Both share one autoloader, one database
connection, one session and one identity, so pages can move to Symfony one at
a time while modules, themes and the `icms::` API keep working.

A site consists of two directories, as ImpressCMS sites always have:

- **the web root**, which holds the contents of `htdocs/`;
- **the trust path**, chosen during installation and recorded in `mainfile.php`
  (`XOOPS_TRUST_PATH`). The host lives here: its code, its dependencies and its
  configuration.

The designs are in `openspec/changes/archive/*-migrate-to-symfony-foundation/`
and `openspec/changes/relocate-host-into-trust-path/`.

## Layout

| Where | What |
|---|---|
| web root `index.php` | entry point: a short preamble hands the request to the host in the trust path. Without an installed host, it is the home page |
| web root `include/host.php` | finds the trust path, `vendor/` and the host, without executing `mainfile.php` |
| web root `trust_dist/` | staging directory of a release package. It holds the host until the installer or the system module update copies it into the trust path, and is never served |
| trust path `host.php` | the front controller. It runs legacy scripts at file scope |
| trust path `composer.json`, `vendor/` | the one Composer root: Symfony and the libraries legacy uses |
| trust path `src/` (`App\`), `config/` | kernel, legacy bridge, runtime integration, security, configuration |
| trust path `.env`, `.env.local` | settings. `.env.local` is written during installation and kept by updates |
| trust path `var/` | Symfony cache and logs |
| trust path `bin/console` | Symfony console (`icms:routes:ownership`, `debug:router`, …) |
| trust path `<hash>.php`, `cache/`, `modules/` | legacy: database credentials, HTMLPurifier cache, Protector |

In a repository checkout, the repository root is the trust path skeleton and
`htdocs/` is the web root. `bin/build-dist` assembles `htdocs/trust_dist/` for a
release package: the host files plus `vendor/`, installed without development
packages. `bin/build-package` zips the web root of a commit, without
`htdocs/tests`, together with that `trust_dist/` into
`var/dist/impresscms-<version>-<commit>.zip`. The zip's contents go directly
into a web root. The `Icms\` library is not in `composer.json`, because the trust path
cannot name a web root that differs per site. `host.php`, `bin/console` and the
test bootstrap register it from `<web root>/libraries/Autoloader.php`, which
also resolves the legacy `icms_*` names.

## Request flow

1. The web server hands a request to the web root's `index.php` (see
   *Routing levels*). The preamble finds `<trust>/host.php`. It records the web
   root and the trust path in `$_SERVER` (`ICMS_LEGACY_ROOT`,
   `ICMS_TRUST_PATH`) and requires the host. `host.php` defines
   `ICMS_HOST_AUTOLOADED`, so ImpressCMS skips its own autoloader, loads the
   trust path's `vendor/autoload.php` and the web root's
   `libraries/Autoloader.php`, and lets `App\Runtime\HostRunner` hand the
   request to the kernel.
2. The router decides. Symfony routes have no fixed prefix. A route can claim
   an existing legacy URL, including `.php` paths, and with a `condition` only
   some variants of it:

   ```php
   #[Route('/modules/system/admin.php', condition: "request.query.get('fct') == 'users'")]
   ```

   Unclaimed requests reach `App\Controller\LegacyBridgeController`, a
   catch-all route with priority -1000. Paths that a Symfony route serves with
   other methods answer 405 there. Reserved prefixes
   (`icms.legacy.reserved_prefixes`, none by default) always answer 404 and
   never reach legacy. `bin/console icms:routes:ownership '<url>' [-m POST]`
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
   response, and `kernel.response` and `kernel.terminate` run for it. A script
   that calls `exit()` is finished the same way by a shutdown function.
   Afterwards the host's superglobals, working directory and output buffer
   level are restored.
5. An exception escaping a script goes to the exception handler the script
   installed (ImpressCMS installs one while booting), exactly as without the
   host. Stack traces end at the script. Otherwise the bridge renders a
   generic 500 page. Details appear only with `APP_DEBUG=1`, and are always
   logged to `<trust>/var/log/<env>.log`.

## Routing levels and web servers

The host needs no web server configuration. Each step of configuration routes
more requests through it:

| Level | Through the host | Web server |
|---|---|---|
| 1 | `/`, `/index.php`, `/index.php/<route>` | nothing to configure; works right after an upgrade |
| 2 | and unknown paths, so Symfony routes with clean URLs | fallback to `index.php`; the default of FrankenPHP's `php_server`, Herd and `php -S` |
| 3 | and every existing `.php` script (the full strangler fig) | every `.php` request to `index.php` |

Scripts that the web server executes itself (levels 1 and 2) work as without
the host. They share the session and the identity with Symfony routes, because
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

## Installation and upgrade

The release package carries the host in `htdocs/trust_dist/`.
`Icms\Core\Filesystem::installTrustDistribution()` installs it:

1. Each entry is copied into the trust path as `<entry>.new` and verified
   (same files, same sizes). A failed copy changes nothing.
2. The verified entries are swapped in. `var/` and `.env.local` are kept.
3. A missing `.env.local` is written, with `APP_ENV=prod`, a random
   `APP_SECRET` and `ICMS_LEGACY_ROOT`. `var/cache/` is emptied.
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
and run the update again.

## Bootstrap stages

`htdocs/include/common.php` is a thin runner over `Icms\Kernel\Bootstrap`,
which contains the former boot sequence, in order:

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

Legacy pages run all stages in *interactive* mode. Symfony routes boot
*headless*, and only as far as they need, through `App\Legacy\LegacyRuntime`:

```php
#[RequiresLegacy(stage: 'language')]   // or no argument: every stage
public function __invoke(): Response
```

Headless mode sends nothing and never exits:
- a closed site raises `Icms\Kernel\SiteClosedException` (503 unless the
  visitor may bypass it);
- a missing installation raises `Icms\Kernel\NotInstalledException` (redirect
  to `/install/`).

`GET /up` boots nothing.

## Library seams

The `Icms\` library does not depend on Symfony. Each seam is inert without the
host:

| Seam | Purpose |
|---|---|
| `ICMS_HOST_AUTOLOADED` guard in `Icms\Kernel\Bootstrap::autoload()` and `icms::setup()` | one autoloader per process |
| `include/host.php` and the preamble of `index.php` | find and enter the host |
| `Icms\Kernel\Bootstrap` (+ `SiteClosedException`, `NotInstalledException`) | staged, headless boot |
| `Icms\Db\Factory::setConnectionProvider(?Closure)` | take the PDO from DBAL |
| `Icms\Event::setForwarder(?Closure)` | observe every legacy event |
| `ICMS_MAINFILE` in the stub `htdocs/mainfile.php` | site configuration named by the server environment (tests). The installer replaces the stub |

## Database

Doctrine DBAL owns the one connection (`config/packages/doctrine.yaml`). Its
settings come from `mainfile.php` through `%env(icms_db:XOOPS_DB_HOST)%` and
friends. `App\Legacy\Dbal\IcmsDriver` opens it as ImpressCMS' own PDO class.
`LegacyRuntime` hands it to `Icms\Db\Factory` before the `services` stage, so
`icms::$db`, `icms::$xoopsDB`, IPF handlers and DBAL share transactions.
Legacy code keeps PDO's silent error mode. DBAL calls switch to exceptions for
their own duration.

Legacy DDL (module install and update) commits an open transaction implicitly,
so never wrap it in a DBAL transaction.

## Container services

| Service id | Autowiring type | Value |
|---|---|---|
| `icms.db` | `Icms\Db\IConnection` | `icms::$db` |
| `icms.db.legacy` | `Icms\Db\Legacy\PdoDatabase` | `icms::$xoopsDB` |
| `icms.config` | `Icms\Config\Handler` | `icms::$config` |
| `icms.security` | `Icms\Core\Security` | `icms::$security` |
| `icms.logger` | `Icms\Core\Logger` | `icms::$logger` |
| `icms.session` | `Icms\Core\Session` | `icms::$session` |
| `icms.preload` | `Icms\Preload\Handler` | `icms::$preload` |
| `icms.user` | – | `icms::$user` (null for anonymous visitors) |
| `icms.module` | – | `icms::$module` (may be null) |
| `icms.handlers` | `App\Legacy\HandlerLocator` | handlers by legacy name (`icms_member`), PSR-4 class (`Icms\Member\Handler::class`) or `module:<dirname>/<name>` |

Each service boots only the stage that creates it, and returns the very
instance legacy code uses. Inject a single handler with
`#[IcmsHandler('icms_data_page')]`.

Legacy class files exit unless ImpressCMS constants are defined, so the
container compiler must never load them. In controllers and services,
type-hint a legacy class only together with `#[IcmsHandler]` or
`#[Autowire(service: 'icms.security')]`: an `Autowire` attribute keeps the
compiler from inspecting the type. Register only real services from `src/`.
`tests/Unit/KernelBootTest` guards this.

## Events

Legacy events are dispatched to Symfony listeners as
`icms.<namespace>.<name>`, with an `App\Legacy\LegacyEvent`:

```php
#[AsEventListener(event: 'icms.icms.loadService-db')]
public function __invoke(LegacyEvent $event): void { $db = $event->parameters['service']; }
```

The forwarder is only installed when such listeners exist. Legacy handlers and
preloads run first and are not affected.

## Session and identity

ImpressCMS owns the session: `Icms\Core\Session` sets the name (`session_name`
with `use_mysession`), the lifetime and the database save handler. Symfony uses
the `php_bridge` storage, and `App\Legacy\LegacySessionFactory` boots the
legacy services before Symfony touches the session. Symfony's session
attributes are the ImpressCMS `$_SESSION` entries.

The user logged in to ImpressCMS is the Symfony user (`App\Security\IcmsUser`).
Login, logout and autologin stay in ImpressCMS. The lazy firewall reads the
user again on every request that needs it.

| Role / attribute | Granted to |
|---|---|
| `ROLE_USER` | every logged-in user |
| `ROLE_WEBMASTER` | members of the webmasters group (`ICMS_GROUP_ADMIN`) |
| `ROLE_GROUP_<id>` | members of that group |
| `icms_perm:<name>` | ImpressCMS group permission on the current module (`App\Security\ModulePermissionVoter`); with an integer subject, on that item of the current module |

Denied anonymous visitors are redirected to
`/user.php?xoops_redirect=<url>`; denied users get 403.
`#[RequiresLegacyToken]` makes POST requests carry a valid ImpressCMS security
token (`XOOPS_TOKEN_REQUEST`), which is accepted once. `GET /whoami` (dev and
test only) shows the current user to webmasters.

## Configuration

The host reads its settings from `<trust>/.env` and `<trust>/.env.local`, never
from the web server, because servers pass environment variables differently
(`variables_order`, `clear_env`, `SetEnv`).

| Variable | Meaning |
|---|---|
| `APP_ENV`, `APP_DEBUG` | Symfony environment; production is `prod` with debug off |
| `APP_SECRET` | random per site; written during installation |
| `ICMS_LEGACY_ROOT` | the web root. `index.php` passes it for web requests; `.env.local` names it for `bin/console`. The default is `htdocs/` in a checkout |
| `ICMS_TRUST_PATH` | passed by `index.php`; refused by the bridge if it lies under the web root |
| `ICMS_HERD_ROUTING` | `2` makes the Herd driver use routing level 2 |

## Unsupported runtimes

Long-running PHP runtimes are not supported: worker mode in FrankenPHP,
RoadRunner and Swoole. `icms::` keeps request state in static properties, and
legacy scripts run once per process.

## Tests

| Suite | Runs |
|---|---|
| `Library` | `htdocs/tests/Unit` |
| `Unit` | `App\` classes and `include/host.php` |
| `Integration` | headless boot, DBAL, container, events, dashboard warnings (one process per test) |
| `Functional` | Symfony routes through `KernelBrowser` |
| `Http` | a real web server: characterization snapshots, the bridge, session and identity, the entry point, the installer, the upgrade |

```bash
php bin/test-db
vendor/bin/phpunit
ICMS_TEST_ROUTING=2 vendor/bin/phpunit --testsuite Http
php bin/build-dist && vendor/bin/phpunit tests/Http/HostUpgradeTest.php
php bin/spec-coverage
vendor/bin/phpstan analyse
```

`bin/test-db` installs a scripted test site into the `icms_test` schema
(`ICMS_TEST_DB_*` select the server), with the accounts `webmaster` and
`member`. Its trust path `var/test/trust` holds one-line stubs for the
repository's `host.php` and `vendor/`, so requests take the production path.

The `Http` suite serves `htdocs/` with `php -S`:
- at routing level 3 (`htdocs/index.php` as router), the default;
- at level 2 with `ICMS_TEST_ROUTING=2`.

With `ICMS_TEST_BASE_URL` it tests an already running server instead.

The characterization snapshots must match at every level and on every server.
Re-record them with `ICMS_UPDATE_SNAPSHOTS=1`. The upgrade test needs
`htdocs/trust_dist`, and removes it as a real update does.

## History: the Laravel prototype

An earlier prototype ran legacy ImpressCMS behind Laravel (branch
`copilot/implement-phase-1-strangler-migration`, OpenSpec change
`migrate-basic-framework-to-laravel`). It kept legacy as an isolated second
application, without a shared database connection, session, identity or
services, and its conventions (Eloquent, facades) competed with IPF and
`icms::`. This host supersedes it. Its bridge design was ported to Symfony and
hardened: script resolution, asset serving, superglobal hydration, output and
header capture, `exit()` handling.

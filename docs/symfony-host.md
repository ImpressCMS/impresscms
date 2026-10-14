# The Symfony host

ImpressCMS can run inside a Symfony 7.4 LTS application (the *host*). The host
is the single front controller (`public/index.php`): Symfony routes answer
directly, and every other request is executed by unchanged legacy ImpressCMS
through the *legacy bridge*. Both share one autoloader, one database
connection, one session and one identity, so pages can move to Symfony one at a
time while modules, themes and the `icms::` API keep working.

Serving `htdocs/` directly (without the host) keeps working; see the README for
both deployment modes. The design is in
`openspec/changes/migrate-to-symfony-foundation/`.

## Layout

| Path | What |
|---|---|
| `composer.json` | the one Composer root: the host, the `Icms\` library (`htdocs/libraries/Icms/`) and its legacy alias map |
| `public/index.php` | front controller; runs legacy scripts at file scope |
| `src/` (`App\`) | kernel, legacy bridge, runtime integration, security |
| `config/` | Symfony configuration; `config/packages/icms_legacy.yaml` configures the bridge |
| `htdocs/` | ImpressCMS, unchanged apart from small, inert seams |
| `bin/console` | Symfony console (`icms:routes:ownership`, `debug:router`, …) |

`htdocs/composer.json` stays for direct deployments and the installer.
`bin/check-lock-alignment` keeps the packages both lock files share at the same
version. Package management from the admin UI must operate on the *root*
`composer.json` when the host is used, and run Composer in a separate process.

## Request flow

1. `public/index.php` defines `ICMS_HOST_AUTOLOADED` (ImpressCMS then skips its
   own `vendor/autoload.php`), loads the root autoloader and lets
   `App\Runtime\HostRunner` hand the request to the kernel.
2. The router decides. Symfony routes have no fixed prefix: a route can claim an
   existing legacy URL, including `.php` paths, and with a `condition` only some
   variants of it:

   ```php
   #[Route('/modules/system/admin.php', condition: "request.query.get('fct') == 'users'")]
   ```

   Unclaimed requests reach `App\Controller\LegacyBridgeController`, a catch-all
   route with priority -1000. Paths that a Symfony route serves with other
   methods answer 405 there; reserved prefixes
   (`icms.legacy.reserved_prefixes`, none by default) always answer 404 and never
   reach legacy. `bin/console icms:routes:ownership '<url>' [-m POST]` shows who
   owns a URL.
3. The bridge resolves the path under the legacy root. Traversal, symlinks out
   of the root, `templates_c/`, `cache/`, the trust path (`ICMS_TRUST_PATH`, if it
   lies under the root) and PHP files under `uploads/` are 404. Static files are
   served directly, with ETag and Last-Modified. Unknown paths run the legacy
   `index.php`.
4. For a PHP script the controller only returns a marker. After the kernel has
   handled the request, `public/index.php` prepares the superglobals and working
   directory and `require`s the script **at file scope**, because legacy code
   relies on top-level variables being globals. Its output, status code and raw
   header lines become the response; `kernel.response` and `kernel.terminate`
   run for it. A script that calls `exit()` is finished the same way by a
   shutdown function. Afterwards the host's superglobals, working directory and
   output buffer level are restored.
5. An exception escaping a script goes to the exception handler the script
   installed (ImpressCMS installs one while booting), exactly as without the
   host. Otherwise the bridge renders a generic 500 page; details appear only
   with `APP_DEBUG=1`, and are always logged (`var/log/<env>.log`).

## Bootstrap stages

`htdocs/include/common.php` is a thin runner over `Icms\Kernel\Bootstrap`, which
contains the former boot sequence, in order:

| Stage | Does |
|---|---|
| `constants` | `mainfile.php` (headless mode) and `include/constants.php` |
| `autoload` | Composer autoloader (skipped with `ICMS_HOST_AUTOLOADED`), core function files |
| `services` | `icms::setup()`, `icms::boot()`: security, logger, database, config, session, current user |
| `output` | gzip/zlib output compression (headless: left to the host) |
| `language` | time zone, core and system language files, page-type file |
| `themeSelection` | `theme_set` from POST or session |
| `siteState` | `closesite` check |
| `module` | `icms::launchModule()` |
| `online` | online-user bookkeeping, `finishCoreBoot` |

Legacy pages run all stages in *interactive* mode. Symfony routes boot
*headless* and only as far as they need, through `App\Legacy\LegacyRuntime`:

```php
#[RequiresLegacy(stage: 'language')]   // or no argument: every stage
public function __invoke(): Response
```

Headless mode sends nothing and never exits: a closed site raises
`Icms\Kernel\SiteClosedException` (503 unless the visitor may bypass it), a
missing installation `Icms\Kernel\NotInstalledException` (redirect to
`/install/`). `GET /up` boots nothing.

## Library seams

The `Icms\` library does not depend on Symfony. Each seam is inert without the
host:

| Seam | Purpose |
|---|---|
| `ICMS_HOST_AUTOLOADED` guard in `include/common.php` and `icms::setup()` | one autoloader per process |
| `Icms\Kernel\Bootstrap` (+ `SiteClosedException`, `NotInstalledException`) | staged, headless boot |
| `Icms\Db\Factory::setConnectionProvider(?Closure)` | take the PDO from DBAL |
| `Icms\Event::setForwarder(?Closure)` | observe every legacy event |
| `ICMS_MAINFILE` in the stub `htdocs/mainfile.php` | site configuration named by the server environment (tests); the installer replaces the stub |

## Database

Doctrine DBAL owns the one connection (`config/packages/doctrine.yaml`). Its
settings come from `mainfile.php` through `%env(icms_db:XOOPS_DB_HOST)%` and
friends. `App\Legacy\Dbal\IcmsDriver` opens it as ImpressCMS' own PDO class and
`LegacyRuntime` hands it to `Icms\Db\Factory` before the `services` stage, so
`icms::$db`, `icms::$xoopsDB`, IPF handlers and DBAL share transactions. Legacy
code keeps PDO's silent error mode; DBAL calls switch to exceptions for their
own duration.

Legacy DDL (module install and update) commits an open transaction implicitly:
never wrap those in a DBAL transaction.

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

Each service boots only the stage that creates it and returns the very instance
legacy code uses. Inject a single handler with
`#[IcmsHandler('icms_data_page')]`.

Legacy class files exit unless ImpressCMS constants are defined, so the
container compiler must never load them: type-hint legacy classes in
controllers and services only together with `#[IcmsHandler]` or
`#[Autowire(service: 'icms.security')]` (an `Autowire` attribute keeps the
compiler from inspecting the type), and register only real services from
`src/`. `tests/Unit/KernelBootTest` guards this.

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
with `use_mysession`), lifetime and database save handler. Symfony uses the
`php_bridge` storage, and `App\Legacy\LegacySessionFactory` boots the legacy
services before Symfony touches the session. Symfony's session attributes are
the ImpressCMS `$_SESSION` entries.

The user logged in to ImpressCMS is the Symfony user (`App\Security\IcmsUser`).
Login, logout and autologin stay in ImpressCMS; the lazy firewall reads the user
again on every request that needs it.

| Role / attribute | Granted to |
|---|---|
| `ROLE_USER` | every logged-in user |
| `ROLE_WEBMASTER` | members of the webmasters group (`ICMS_GROUP_ADMIN`) |
| `ROLE_GROUP_<id>` | members of that group |
| `icms_perm:<name>` | ImpressCMS group permission on the current module (`App\Security\ModulePermissionVoter`); with an integer subject, on that item of the current module |

Denied anonymous visitors are redirected to
`/user.php?xoops_redirect=<url>`, denied users get 403.
`#[RequiresLegacyToken]` makes POST requests carry a valid ImpressCMS security
token (`XOOPS_TOKEN_REQUEST`), accepted once. `GET /whoami` (dev and test only)
shows the current user to webmasters.

## Configuration

| Variable | Meaning |
|---|---|
| `APP_ENV`, `APP_DEBUG` | Symfony environment; production is `prod` with debug off |
| `APP_SECRET` | required in production; put it in `.env.local` |
| `ICMS_LEGACY_ROOT` | legacy root, default `htdocs/` |
| `ICMS_TRUST_PATH` | the trust path, if it lies under the legacy root |

## Unsupported runtimes

Long-running PHP runtimes (worker mode: FrankenPHP workers, RoadRunner, Swoole)
are not supported: `icms::` keeps request state in static properties and legacy
scripts run once per process.

## Tests

| Suite | Runs |
|---|---|
| `Library` | `htdocs/tests/Unit`, also via `htdocs/phpunit.xml.dist` |
| `Unit` | `App\` classes |
| `Integration` | headless boot, DBAL, container, events (one process per test) |
| `Functional` | Symfony routes through `KernelBrowser` |
| `Http` | a real `php -S` server: characterization snapshots, the bridge, session and identity |

```bash
php bin/test-db
vendor/bin/phpunit
ICMS_TEST_DOCROOT=public vendor/bin/phpunit --testsuite Http
php bin/spec-coverage
vendor/bin/phpstan analyse
```

`bin/test-db` installs a scripted test site into the `icms_test` schema
(`ICMS_TEST_DB_*` select the server) with the accounts `webmaster` and `member`.
The `Http` suite runs against `htdocs/` by default and against the host with
`ICMS_TEST_DOCROOT=public`; the characterization snapshots must match in both.
Re-record them with `ICMS_UPDATE_SNAPSHOTS=1` against `htdocs/` only.

## History: the Laravel prototype

An earlier prototype ran legacy ImpressCMS behind Laravel (branch
`copilot/implement-phase-1-strangler-migration`, OpenSpec change
`migrate-basic-framework-to-laravel`). It kept legacy as an isolated second
application without a shared database connection, session, identity or
services, and its conventions (Eloquent, facades) competed with IPF and `icms::`.
It is superseded by this host. Its bridge design (script resolution, asset
serving, superglobal hydration, output and header capture, `exit()` handling)
was ported to Symfony and hardened.

## Context

`migrate-to-laravel-foundation` (archived 2026-11-02) put the Laravel host at the repository root:
- `public/index.php` as the front controller;
- `composer.json`, `vendor/`, `app/`, `bootstrap/`, `config/`, `routes/` and `var/` next to it;
- `htdocs/` as the legacy root.

ImpressCMS sites are deployed differently, as a web root (the contents of `htdocs/`) and a trust path. The facts about the trust path, the installer's default and dbversion 49 are those in the Context of the Symfony change `relocate-host-into-trust-path` and are not repeated here.

The Symfony change is the **specification**. Its decisions D1–D8 are about ImpressCMS deployment, not about Symfony, so they are taken over; each decision below names its counterpart and only describes what differs for Laravel. The legacy and library code that realises them does not depend on a framework (`include/host.php`, the `index.php` preamble, `installTrustDistribution()`, the installer, update step 50, the dashboard warning, `htaccess.txt`). It is taken over from the Symfony commits `98f7b467d`, `eb4bf8477`, `a7268a413`, `49333b28a` and `133b36e0a`, and adapted where it names Symfony (L22).

Decisions continue the numbering of the foundation design (L1–L16). Laravel facts were checked against `laravel/framework` v12.70.2.

## Goals / Non-Goals

Identical to the Symfony change:
- **Goals:** a deployment is the web root and the trust path only; 2.0.x and 2.1-dev sites upgrade by uploading files and running the system module update; the host works without server configuration, and each further step of configuration routes more traffic through it; Apache, nginx (and Laravel Herd) and FrankenPHP in classic mode are supported and tested in CI.
- **Non-Goals:** long-running runtimes (Octane, FrankenPHP worker mode); managing Composer packages from the admin UI; moving `templates_c/`, `cache/` or `uploads/` out of the web root.

## Decisions

### L17. The trust path is the Laravel project directory (Symfony: D1)
The host files live directly in the trust path: `host.php`, `artisan`, `composer.json`, `composer.lock`, `vendor/`, `app/`, `bootstrap/`, `config/`, `routes/`, `.env.local` and `var/`. `Application::configure(basePath: dirname(__DIR__))` in `bootstrap/app.php` then names the trust path, and every Laravel path follows from it:
- `var/` through `useStoragePath()` (foundation L2): logs, the file cache, compiled views;
- `bootstrap/cache/` for Laravel's generated `packages.php` and `services.php`, which Laravel writes on the first request after an install. The trust path must therefore be writable, as it already is for HTMLPurifier and Protector.

There is no `public/` directory. Laravel's `public_path()` is not used by the host (the `/storage` routes are off, there are no Vite assets), so it is left at its default instead of being pointed at the web root.

The legacy trust-path entries (`<sdata>.php`, `cache/`, `modules/`) do not collide with these names.

*Alternative rejected:* a subdirectory such as `<trust>/laravel`, for the same reason as in D1: a second `vendor/` for legacy, or legacy looking inside the host directory.

### L18. `index.php` in the web root is the entry point, through a preamble (Symfony: D2)
Taken over unchanged. `htdocs/index.php` starts with a preamble that calls `icms_host_entry(__DIR__)`; when it returns `<trust>/host.php` and `ICMS_HOST_AUTOLOADED` is not defined, it records `ICMS_LEGACY_ROOT` and `ICMS_TRUST_PATH` in `$_SERVER`, requires the host and exits. Otherwise the home page runs, or, at routing level 3 without a host, the script the request asked for.

`host.php` is `public/index.php` moved:
- `define('ICMS_HOST_AUTOLOADED')`, the `php -S` fix-up of `SCRIPT_NAME` (now for the web root's `index.php`, taken from `ICMS_LEGACY_ROOT`), `PhpEnvironment::capture()`;
- `HostRunner` over `bootstrap/app.php`, and the file-scope `require` of the legacy script (foundation L5).

Because `index.php` requires `host.php` at its own file scope, that is still the global scope.

The bridge strips the frames of `host.php` and everything below it from legacy stack traces (`base_path('host.php')`), which also covers the preamble and a test stub that requires `host.php`. The phase 5 fix for Herd's `server.php` did the same for `public/index.php`.

### L19. Routing levels are a web-server choice (Symfony: D3)
Taken over unchanged, including `htdocs/htaccess.txt` and `docs/server/` (Apache vhost, nginx, Caddyfile, Herd driver), with the same never-served paths. The configurations do not name a framework: they route to `index.php` of the web root.

**Herd.** In phase 5 Herd's own Laravel driver served `public/` (it recognises `artisan` plus `public/index.php`). With the web root as the site directory, Herd falls back to its basic driver, which is level 2. The ImpressCMS Valet driver from `docs/server/herd/` gives level 3. The driver is framework-independent and taken over.

### L20. Configuration lives in `<trust>/.env.local`, never in the server environment (Symfony: D4)
- `installTrustDistribution()` writes `.env.local` when it is missing: `APP_ENV=prod`, `APP_DEBUG=0`, `APP_KEY=base64:<32 random bytes>` (Laravel's key format, as `key:generate` writes it) and `ICMS_LEGACY_ROOT`, which `artisan` needs. The environment name stays `prod` (foundation L2).
- Laravel reads `.env.local` only (`loadEnvironmentFrom()`, foundation L2), through an immutable repository with `$_SERVER`, `$_ENV` and `putenv` adapters. The preamble's `$_SERVER` entries therefore take precedence over `.env.local` without extra code, as Symfony's `Dotenv` gives them precedence.
- Nothing needs `variables_order` to contain `E`: the preamble writes `$_SERVER`, and Dotenv writes the values it loads into `$_SERVER` and `$_ENV` itself.

### L21. The `Icms\` library is registered from the web root, after the environment is loaded (Symfony: D5)
The trust path's `composer.json` no longer autoloads `Icms\`, the `icms` classmap or `libraries/Autoloader.php`, because it cannot name a web root that differs per site.

In Symfony, `host.php`, `bin/console` and the test bootstrap each require `<web root>/libraries/Autoloader.php`; `bin/console` can do so because it runs `Dotenv::bootEnv()` itself before the kernel exists. In Laravel the environment is loaded by a bootstrapper inside the application, so `artisan` does not know the web root before the application boots. `bootstrap/app.php` therefore registers the library in one place for every entry point: `$app->afterLoadingEnvironment()` (a documented hook) requires `libraries/Autoloader.php` from `ICMS_LEGACY_ROOT` (default `htdocs/` of a development checkout) when that file exists. No `Icms\` class is loaded earlier: the providers are registered after the environment, and `bootstrap/app.php` mentions `Icms\` classes only as closure parameter types, which Laravel reflects without autoloading.

The bridge fixtures have no `libraries/`; their `php -S` router loads the repository's.

The test bootstrap and PHPStan require the file as well, because they load classes before any application exists.

### L22. Staging directory `htdocs/trust_dist/` plus dbversion 50 (Symfony: D6)
Taken over, including the autoloader search order (`icms_host_vendor_autoload()`: `<trust>/vendor`, `<root>/trust_dist/vendor`, `<root>/vendor`), the copy-verify-activate steps of `installTrustDistribution()`, the installer page, update step 50 and the deferred activation.

Laravel-specific:
- `bin/build-dist` copies `host.php`, `artisan`, `composer.json`, `composer.lock`, `app/`, `bootstrap/` (without generated caches), `config/` and `routes/`, and runs `composer install --no-dev --classmap-authoritative`. There is no `.env` to copy (foundation L2).
- **What `installTrustDistribution()` clears.** Symfony's cached container lives in `var/cache`. Laravel's caches are `bootstrap/cache/*.php` (package and service manifests, optional config and route caches) and `var/framework/cache` and `var/framework/views`. `bootstrap/` is a staging entry and is replaced as a whole, which empties its cache; the two `var/framework` directories are removed explicitly. `var/log` is kept.
- **What it writes.** `APP_KEY` instead of `APP_SECRET` (L20).

These two points are the only place where the library knows which framework hosts it. They are constants in `Icms\Core\Filesystem`, so another host would change only those.

### L23. The trust path must not lie under the document root (Symfony: D7)
Taken over unchanged: the installer's confirmation for a trust path under the web root or `DOCUMENT_ROOT`, the dashboard warning, the bridge's refusal of `ICMS_TRUST_PATH` under the legacy root (`config/icms.php`, now taken from the preamble), and `<trust>/.htaccess` as a second line of defence on Apache. `config/icms.php` adds `trust_dist` to the never-served paths.

### L24. Development and tests use the production layout (Symfony: D8)
Taken over:
- the repository root is the trust path skeleton and `htdocs/` is the web root;
- `bin/test-db` writes two one-line stubs into `var/test/trust`, `host.php` and `vendor/autoload.php`, which require the repository's files;
- the Http suite serves `htdocs/` with `php -S`, at level 3 (router `htdocs/index.php`, the default) or level 2 (no router), selected with `ICMS_TEST_ROUTING`, or tests an external server with `ICMS_TEST_BASE_URL` (added in phase 5 for Herd).

`ICMS_TEST_DOCROOT` (`htdocs` or `public`) disappears: without a host the site is the level 2 server without a trust-path host, which `HostDeploymentTest` covers.

The Http tests recognise a response of the host by a header set in the `test` environment only. Symfony sets it in a `kernel.response` listener. Laravel has no response event that also sees legacy responses, so the listener listens to `RequestHandled`, which `HostKernel` dispatches for the real legacy response (foundation L5).

## Risks / Trade-offs

Those of the Symfony change apply unchanged (level 1 leaves most legacy scripts outside the host; copying a large `vendor/` can time out; a preamble in a file that themes may customise; existing sites with a public trust path). In addition:
- **[`bootstrap/cache` must be writable in the trust path]** Laravel writes its package manifest there on the first request. An unwritable trust path fails with a clear Laravel exception; the installer already checks that the trust path is writable.
- **[Registering the library after the environment]** Code in `bootstrap/app.php` that loaded an `Icms\` class at file level would fail under `artisan` on an installed site. The test `HostDiscoveryTest` and the upgrade test boot from a trust path without `Icms\` in its autoloader, which would catch that.

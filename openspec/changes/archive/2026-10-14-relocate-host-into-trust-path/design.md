## Context

`migrate-to-symfony-foundation` (archived 2026-10-14) put the Symfony host at the repository root:
- `public/index.php` as the front controller;
- `composer.json`, `vendor/`, `src/`, `config/` and `var/` next to it;
- `htdocs/` as the legacy root.

ImpressCMS sites are deployed differently, as a web root (the contents of `htdocs/`) and a trust path. Legacy reads the trust path only through `XOOPS_TRUST_PATH` in `mainfile.php`. Today the trust path holds:
- the database credentials file;
- the HTMLPurifier cache;
- Protector's files;
- since system dbversion 49, the Composer `vendor/` directory, which is moved there from the web root and re-based.

The installer's default trust path is a random directory next to the document root: `resolveDocumentRoot()` returns the document root's parent. Nothing stops an admin from entering a path inside it.

## Goals / Non-Goals

**Goals:**
- A deployment consists of the web root and the trust path only.
- 2.0.x and 2.1-dev sites upgrade by uploading files and running the system module update.
- The host works without any server configuration.
- Each further step of web-server configuration routes more traffic through the host.
- Apache, nginx (and Laravel Herd) and FrankenPHP in classic mode are supported, and CI tests each of them.

**Non-Goals:**
- Supporting FrankenPHP worker mode or other long-running runtimes (see the archived change).
- Managing Composer packages from the admin UI.
- Moving `templates_c/`, `cache/` or `uploads/` out of the web root.

## Decisions

### D1. The trust path is the host's project directory
The host files live directly in the trust path: `host.php`, `composer.json`, `vendor/`, `src/`, `config/`, `bin/console`, `.env` and `var/`. `<trust>/vendor` is then the only dependency tree:
- legacy already prefers `<trust>/vendor/autoload.php`;
- `Kernel::getProjectDir()` finds `<trust>/composer.json`;
- all Composer paths are relative to it, so a built tree can be copied anywhere.

The legacy trust-path entries (`<sdata>.php`, `cache/`, `modules/`) do not collide with these names.

*Alternative rejected:* a subdirectory such as `<trust>/app`. That would keep a second `vendor/` for legacy, or force legacy to look inside the host directory.

### D2. `index.php` in the web root is the entry point, through a preamble
`htdocs/index.php` starts with a preamble:
- it calls `icms_host_entry(__DIR__)` from `include/host.php`;
- if the call returns `<trust>/host.php` and `ICMS_HOST_AUTOLOADED` is not yet defined, it records the web root and trust path in `$_SERVER`, requires the host and exits;
- otherwise the unchanged home page code runs.

When the bridge executes `/` or `/index.php`, it requires the same file at file scope. `ICMS_HOST_AUTOLOADED` is then defined, so the home page runs.

`include/host.php` reads `XOOPS_TRUST_PATH` from `mainfile.php` with the installer's regular expression. It does not include the file, because `mainfile.php` includes `common.php` unless `$xoopsOption['nocommon']` is set, and its include guard would prevent a later include. It follows the stub `mainfile.php`'s `ICMS_MAINFILE` exactly as the stub does.

*Alternative rejected:* a new front controller file such as `app.php`. That would need server configuration even for level 1, and `/` would stay legacy-only.

### D3. Routing levels are a web-server choice; the host does not depend on them
| Level | Through the host | Server configuration |
|---|---|---|
| 1 | `/`, `/index.php`, `/index.php/<route>` | none |
| 2 | and unknown paths | fallback to `index.php` (default of `php -S`, FrankenPHP `php_server` and Herd) |
| 3 | and every existing `.php` script | every `.php` request to `index.php` |

`htdocs/htaccess.txt` and `docs/server/` (Apache vhost, nginx, Caddyfile, Herd driver) contain levels 2 and 3. They also deny `trust_dist/`, `templates_c/`, `cache/`, PHP files under `uploads/` and the directories that legacy protects with `.htaccess`, because nginx and Caddy ignore `.htaccess`.

`htaccess.txt` stays a sample and is not activated, so an upload never overwrites a site's own `.htaccess`.

### D4. Configuration lives in `<trust>/.env.local`, never in the server environment
- `installTrustDistribution()` writes `.env.local` when it is missing. It contains `APP_ENV=prod`, `APP_DEBUG=0`, a random `APP_SECRET`, and `ICMS_LEGACY_ROOT`, which `bin/console` needs.
- The preamble puts `ICMS_LEGACY_ROOT` and `ICMS_TRUST_PATH` into `$_SERVER`, so they take precedence over `.env.local` for web requests.
- This sidesteps the server differences in `variables_order` and in environment passing. CI hit one of these with `php -S` and `php.ini-production`.

### D5. The `Icms\` library is registered from the web root at runtime
The trust path's `composer.json` cannot point at a web root that differs per site. It therefore no longer autoloads `Icms\`, the `icms` classmap or `libraries/Autoloader.php`.

`host.php` requires `<web root>/libraries/Autoloader.php` after `vendor/autoload.php`. That file already resolves `Icms\` classes, `icms_*` aliases and the `icms` class. Legacy's `Bootstrap` already requires it after a trust-path vendor; the stub file it wrote into the trust path (`<trust>/libraries/Autoloader.php`) is no longer needed.

The test, PHPStan and console bootstraps require it as well.

### D6. Staging directory `htdocs/trust_dist/` plus dbversion 50
`bin/build-dist` assembles `htdocs/trust_dist/` from the repository: the host files, plus `vendor/` installed with `--no-dev`. The release package ships it.

**Autoloader search order.** `include/host.php` provides `icms_host_vendor_autoload()`, which looks in:
1. `<trust>/vendor`;
2. `<root>/trust_dist/vendor`;
3. the legacy `<root>/vendor`.

`Bootstrap`, `icms::setup()` and the installer use it. A freshly uploaded site therefore boots on the staging tree, and can render the admin pages and run the update.

**`Icms\Core\Filesystem::installTrustDistribution($staging, $trust, $root, $deferSourceRemoval)` steps:**
1. Copy every top-level entry of the staging directory into the trust path, through `<entry>.new` with verification: the same file set excluding `index.html`, and the same `vendor/autoload.php` hash.
2. Swap each entry in, deleting the replaced entry. A `vendor/` without `host.php` next to it came from dbversion 49; it is kept as `vendor.pre-50`.
3. Keep `var/` and `.env.local`. Write `.env.local` when it is missing, and clear `var/cache/`.
4. Remove `<trust>/libraries/Autoloader.php`, and the legacy `<root>/vendor` if present.
5. Remove the staging directory. When the running request loaded classes from it, removal is deferred to shutdown.

**Callers:**
- the installer page (former `movevendor`);
- system update step 50 (`ICMS_SYSTEM_DBVERSION` 50), which aborts the update on failure.

Step 49 becomes a no-op, because its vendor move is subsumed by step 50.

Running the update again without a staging directory reports "already installed".

### D7. The trust path must not lie under the document root
- **Installer:** the default stays next to the document root (it already was, see Context). A trust path under the web root or under `DOCUMENT_ROOT` requires an explicit confirmation.
- **Admin warning:** `system_admin_blocks.php` warns on existing sites whose trust path is under `ICMS_ROOT_PATH` or the document root.
- **Bridge:** it keeps refusing `ICMS_TRUST_PATH` under the legacy root, now taken from the preamble.
- **Apache:** `<trust>/.htaccess` (deny) remains a second line of defence.

### D8. Development and tests use the production layout
- **Repository:** the repository root is the trust path skeleton and `htdocs/` is the web root.
- **Test site:** its trust path stays `var/test/trust`. `bin/test-db` writes two one-line stubs there, `host.php` and `vendor/autoload.php`, which require the repository's files. Requests then take the production path through the preamble, without copying `vendor/`.
- **Http suite:**
  - runs against `htdocs/` with `php -S`, at level 2 (no router) or level 3 (router `htdocs/index.php`), selected with `ICMS_TEST_ROUTING`;
  - runs against an external server with `ICMS_TEST_BASE_URL`, which the CI job `webservers` uses for Apache, nginx and FrankenPHP.

## Risks / Trade-offs

- **[Level 1 leaves most legacy scripts outside the host]**
  - Without rewrite rules, Symfony routes that claim legacy `.php` URLs and the legacy event forwarder only apply to `/`.
  - Session and identity are still shared, because both sides use the database session.
  - Level 3 is documented and is the default of the Herd driver.
- **[Copying a large `vendor/` during an update can time out on shared hosting]**
  - The copy goes through `<entry>.new` and only swaps after verification, so a timed-out update leaves the old tree intact.
  - The update can simply be run again.
  - Manual upload of `trust_dist/` contents is documented.
- **[A preamble in `index.php` changes a file that themes or hosts may have customised]** Upgrades already overwrite `index.php`. The preamble is self-contained and inert when no host is installed.
- **[Existing sites with a trust path under the document root]** These cannot be fixed automatically. The admin warning explains how to move the trust path and update `mainfile.php`.

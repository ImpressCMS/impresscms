## Why

An ImpressCMS site is deployed as two directories:
- the **web root**, which holds the contents of `htdocs/`;
- the **trust path**, chosen during installation and recorded as `XOOPS_TRUST_PATH` in `mainfile.php`, which holds everything that must not be public.

The Symfony host introduced by `migrate-to-symfony-foundation` assumed a third location instead: the repository root. That layout has `public/` as the web root and `composer.json`, `vendor/`, `src/`, `config/` and `var/` next to it. It does not match how ImpressCMS is installed, hosted or upgraded, so existing sites cannot reach it:
- 2.0.x sites have no Composer at all;
- 2.1-dev sites have `vendor/` already moved into the trust path (system dbversion 49).

## What Changes

- **The host lives in the trust path.** `host.php` (formerly `public/index.php`), `composer.json`, `vendor/`, `src/`, `config/`, `bin/console` and `var/` all move there. The web root keeps only ImpressCMS itself. `public/` is removed.
- **`index.php` in the web root is the entry point.**
  - A short preamble finds the trust path without executing `mainfile.php` and hands the request to `<trust>/host.php`.
  - Without an installed host, the page falls back to the legacy home page, and an uninstalled site still redirects to the installer.
- **Three routing levels.** Each works on Apache, nginx (including Laravel Herd) and FrankenPHP:
  1. Without any server configuration, `/` and `/index.php` go through the host.
  2. A fallback rule adds unknown paths, so Symfony routes work.
  3. Rewriting every `.php` request adds the legacy scripts (the full strangler fig).

  Example configurations for each server ship in `docs/server/`, and CI runs the HTTP suite against each of them.
- **Configuration does not depend on the server environment.** The installer and the system update write `<trust>/.env.local` with `APP_ENV`, `APP_SECRET` and the web-root location, so no server needs environment variables.
- **The host is installed automatically.**
  - The release package carries the host as a staging directory, `htdocs/trust_dist/`, which is never served.
  - The installer and a new system-module update step (dbversion 50) copy it into the trust path, keep `var/` and `.env.local`, and remove the staging directory.
  - Until that happens, legacy loads its dependencies from the staging directory, so a freshly uploaded site can boot far enough to run the update.
- **One Composer root.**
  - `htdocs/composer.json`, `htdocs/composer.lock`, the shipped `htdocs/vendor` and `bin/check-lock-alignment` are removed.
  - So are the vendor move, the autoloader re-basing and the trust-path autoloader stub (`Icms\Core\Filesystem::moveVendorToTrust()` and its helpers).
  - The `Icms\` library is registered through `libraries/Autoloader.php` from the web root, which already supports every class name.
- **The trust path must not be public.** The installer proposes a trust path outside the document root, and the admin dashboard warns when the trust path lies under the web root. With the host inside it, a public trust path would expose `vendor/`, `config/` and `.env.local`.

## Capabilities

### New Capabilities
- `host-deployment`: Covers how the host is deployed as part of the web root and the trust path, entered through the web root's `index.php`, configured without server environment variables, and installed or upgraded from a pre-Symfony release.

### Modified Capabilities
- `request-ownership`: The single-entry-point requirement now describes the web root's `index.php` and the routing level the web server provides. It no longer describes a dedicated `public/` front controller.

## Impact

- **New code:**
  - `htdocs/include/host.php` (trust path and autoloader discovery, without dependencies);
  - `host.php`;
  - `bin/build-dist`;
  - `docs/server/` (Apache, nginx, Caddyfile, a Herd driver);
  - `Icms\Core\Filesystem::installTrustDistribution()`;
  - system update step 50;
  - a trust-path location check in the installer and the admin dashboard.
- **Changed code:**
  - `htdocs/index.php` (preamble);
  - the autoloader discovery in `Icms\Kernel\Bootstrap`, `icms::setup()` and `install/common.inc.php`;
  - the installer's vendor page;
  - `composer.json` (no `Icms\` autoload);
  - `config/packages/icms_legacy.yaml`;
  - `LegacyBridge` (host frames);
  - test support (`ICMS_TEST_ROUTING`, `ICMS_TEST_BASE_URL`, test trust path);
  - CI;
  - documentation.
- **Removed:** `public/`, `htdocs/composer.json`, `htdocs/composer.lock`, `bin/check-lock-alignment`, the vendor move code and `run_tests.bat`.
- **Upgrade:**
  - From 2.0.x: upload the files, open the site, then run the system module update.
  - From 2.1-dev at dbversion 49: run the system module update. The re-based `vendor/` is kept as `vendor.pre-50`.

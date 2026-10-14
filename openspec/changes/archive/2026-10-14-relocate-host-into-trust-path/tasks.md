## 1. Entry point and discovery (D2, D5)

- [x] 1.1 Add `htdocs/include/host.php` with `icms_host_trust_path()`, `icms_host_vendor_autoload()` and `icms_host_entry()`. The file needs no autoloader and follows the stub's `ICMS_MAINFILE`. Add unit tests for: an installed mainfile, the stub with and without `ICMS_MAINFILE`, the sdata variant, a missing host, and the vendor search order
- [x] 1.2 Add the preamble to `htdocs/index.php`. It records `ICMS_LEGACY_ROOT` and `ICMS_TRUST_PATH` in `$_SERVER`, requires the host, and is skipped when `ICMS_HOST_AUTOLOADED` is defined
  - Without a host, the preamble runs the script a request routed to `index.php` asked for (`icms_host_requested_script()`), so a level 3 server also works before the host is installed (scenario "Routing level 3 without a host")
- [x] 1.3 Move `public/index.php` to `host.php`:
  - `project_dir` is `__DIR__`;
  - load `<web root>/libraries/Autoloader.php` after `vendor/autoload.php`;
  - keep the `php -S` fix-up for the web root.
  Then remove `public/`
- [x] 1.4 Strip the frames of `host.php` and the preamble in `LegacyBridge::withoutHostFrames()`, instead of the pre-hydration `SCRIPT_FILENAME`
- [x] 1.5 `composer.json`: drop the `Icms\` PSR-4 entry, the classmap and the files entry. Require `htdocs/libraries/Autoloader.php` from `tests/bootstrap.php`, `phpstan-bootstrap.php` and `bin/console`
- [x] 1.6 `config/packages/icms_legacy.yaml`: take the legacy root from `ICMS_LEGACY_ROOT` (default `%kernel.project_dir%/htdocs`) and add `trust_dist` to `non_public_paths`

## 2. Autoloader discovery in legacy (D6)

- [x] 2.1 Use `icms_host_vendor_autoload()` in `Bootstrap::autoload()`, `icms::setup()` and `install/common.inc.php`, and remove the trust-path autoloader stub writer from all three
- [x] 2.2 `install/page_modcheck.php`: check the dependency tree found by `icms_host_vendor_autoload()`
- [x] 2.3 `Icms\Core\HTMLFilter`: locate HTMLPurifier through the loaded class instead of `ICMS_ROOT_PATH/vendor`

## 3. Distribution, installation, upgrade (D6, D7)

- [x] 3.1 Add `bin/build-dist`, which assembles `htdocs/trust_dist/` with `composer install --no-dev --classmap-authoritative`. Add `trust_dist/` to `.gitignore`
- [x] 3.2 Replace `Filesystem::moveVendorToTrust()`, `finalizeTrustVendor()`, `rewriteVendorAutoloadBase()` and `pointComposerJsonToTrust()` with `installTrustDistribution()`, and add unit tests for every rule in D6
- [x] 3.3 Turn the installer page `movevendor` into "install host files" built on `installTrustDistribution()`, and update the English language strings
  - The page's strings moved to `language/english/host.php`, which languages without a translation fall back to; French, Italian and Dutch had none of the former vendor strings, so their installer failed
- [x] 3.4 System update: make step 49 a no-op, add step 50 that calls `installTrustDistribution()` (deferred staging removal) and aborts on failure, and set `ICMS_SYSTEM_DBVERSION` to 50
- [x] 3.5 Installer path page: propose a default trust path outside the document root, and require confirmation for a trust path under it
  - The default was already next to the document root: `resolveDocumentRoot()` returns its parent. It stays as it was; the check for a public trust path uses `DOCUMENT_ROOT`
- [x] 3.6 Admin dashboard: warn when the trust path lies under the web root or the document root

## 4. Web servers (D3)

- [x] 4.1 `htdocs/htaccess.txt`: add level 2 and level 3 blocks in `<IfModule mod_rewrite.c>` and deny rules for the staging directory
  - The sample's `Options Multiviews` reset `FollowSymLinks`, which makes Apache refuse `RewriteRule` (403); now `Options +MultiViews` and `+SymLinksIfOwnerMatch`
- [x] 4.2 Add `docs/server/apache-vhost.conf`, `docs/server/nginx.conf` and `docs/server/Caddyfile`, each with levels 2 and 3 and the deny rules
- [x] 4.3 Add `docs/server/herd/ImpressCmsValetDriver.php`: level 3 by default, `ICMS_HERD_ROUTING=2` to fall back, the same deny rules

## 5. Tests (D8)

- [x] 5.1 `bin/test-db`: write the `host.php` and `vendor/autoload.php` stubs into `var/test/trust`, and stop copying and parking `vendor/`
- [x] 5.2 Make `ServerProcess` serve `htdocs/` with `ICMS_TEST_ROUTING=2|3`, and support `ICMS_TEST_BASE_URL` for external servers
- [x] 5.3 Update the tests that assume `public/`, the old autoload paths or `htdocs/vendor`:
  - `SharedAutoloaderTest`
  - the resolver fixtures
  - the snapshot normaliser
  - `phpunit.xml.dist`
  - `phpstan`
- [x] 5.4 Add Http tests for the entry point:
  - `/` through the host;
  - the fallback without a host;
  - `/up` at level 2;
  - `trust_dist` returns 404;
  - a directly executed script shares the identity.
- [x] 5.5 Add an upgrade test: a dbversion-48 site with a trust path without a host and a staging directory from `bin/build-dist`; run the system update; the host is installed and serves `/`
- [x] 5.6 Add tests for the installer's default trust path and the admin warning

## 6. Cleanup, docs, CI

- [x] 6.1 Remove `htdocs/composer.json`, `htdocs/composer.lock`, `htdocs/phpunit.xml.dist`, `bin/check-lock-alignment` and `run_tests.bat`, and remove `htdocs/vendor` from the package
- [x] 6.2 `docs/symfony-host.md`: new layout, request flow from `htdocs/index.php`, routing levels per server, configuration, upgrade paths, and correct where the autoload guard lives. `README.md`: one installation procedure
- [x] 6.3 CI:
  - one `composer install`, with no lock alignment;
  - the Http suite at levels 2 and 3;
  - `bin/build-dist` and the upgrade test;
  - a job `webservers` (Apache, nginx, FrankenPHP) using `docs/server/`.
- [x] 6.4 Verify on the Herd test site (`C:\Users\david\sites\pre3`, trust path `C:\Users\david\trustpaths\pre3`):
  - a fresh installation;
  - an upgrade from 2.0.3;
  - levels 2 and 3.
  - Done: a fresh installation through the web installer (level 2 with Herd's default driver; levels 2 and 3 with the ImpressCMS driver, login included), then 2.0.3 installed, the new files uploaded over it and the system module updated: host installed, `trust_dist/` removed, dbversion 50, legacy trust path entries kept
- [x] 6.5 `bin/spec-coverage`, PHPStan, the deprecation check, and `openspec validate relocate-host-into-trust-path`
  - `bin/spec-coverage` also reads `htdocs/tests` (the `Filesystem` tests are Library tests)

## 7. PHP version check

- [x] 7.1 `htdocs/include/requirements.php`, in PHP 5.3 syntax: the minimum from Composer's `platform_check.php` of the vendor directory in use, and the requirements page with status 503. `include/host.php` reads the trust path and the vendor directory through it
- [x] 7.2 Run the check first in `index.php`, `include/common.php` and `install/common.inc.php`
- [x] 7.3 `htdocs/requirements.html`: a self-contained page, to be branded later
- [x] 7.4 Unit tests, and a CI job that serves the package's web root with PHP 7.4 and 5.6

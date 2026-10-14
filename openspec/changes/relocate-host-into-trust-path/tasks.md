## 1. Entry point and discovery (D2, D5)

- [ ] 1.1 Add `htdocs/include/host.php` with `icms_host_trust_path()`, `icms_host_vendor_autoload()` and `icms_host_entry()`. The file needs no autoloader and follows the stub's `ICMS_MAINFILE`. Add unit tests for: an installed mainfile, the stub with and without `ICMS_MAINFILE`, the sdata variant, a missing host, and the vendor search order
- [ ] 1.2 Add the preamble to `htdocs/index.php`. It records `ICMS_LEGACY_ROOT` and `ICMS_TRUST_PATH` in `$_SERVER`, requires the host, and is skipped when `ICMS_HOST_AUTOLOADED` is defined
- [ ] 1.3 Move `public/index.php` to `host.php`:
  - `project_dir` is `__DIR__`;
  - load `<web root>/libraries/Autoloader.php` after `vendor/autoload.php`;
  - keep the `php -S` fix-up for the web root.
  Then remove `public/`
- [ ] 1.4 Strip the frames of `host.php` and the preamble in `LegacyBridge::withoutHostFrames()`, instead of the pre-hydration `SCRIPT_FILENAME`
- [ ] 1.5 `composer.json`: drop the `Icms\` PSR-4 entry, the classmap and the files entry. Require `htdocs/libraries/Autoloader.php` from `tests/bootstrap.php`, `phpstan-bootstrap.php` and `bin/console`
- [ ] 1.6 `config/packages/icms_legacy.yaml`: take the legacy root from `ICMS_LEGACY_ROOT` (default `%kernel.project_dir%/htdocs`) and add `trust_dist` to `non_public_paths`

## 2. Autoloader discovery in legacy (D6)

- [ ] 2.1 Use `icms_host_vendor_autoload()` in `Bootstrap::autoload()`, `icms::setup()` and `install/common.inc.php`, and remove the trust-path autoloader stub writer from all three
- [ ] 2.2 `install/page_modcheck.php`: check the dependency tree found by `icms_host_vendor_autoload()`
- [ ] 2.3 `Icms\Core\HTMLFilter`: locate HTMLPurifier through the loaded class instead of `ICMS_ROOT_PATH/vendor`

## 3. Distribution, installation, upgrade (D6, D7)

- [ ] 3.1 Add `bin/build-dist`, which assembles `htdocs/trust_dist/` with `composer install --no-dev --classmap-authoritative`. Add `trust_dist/` to `.gitignore`
- [ ] 3.2 Replace `Filesystem::moveVendorToTrust()`, `finalizeTrustVendor()`, `rewriteVendorAutoloadBase()` and `pointComposerJsonToTrust()` with `installTrustDistribution()`, and add unit tests for every rule in D6
- [ ] 3.3 Turn the installer page `movevendor` into "install host files" built on `installTrustDistribution()`, and update the English language strings
- [ ] 3.4 System update: make step 49 a no-op, add step 50 that calls `installTrustDistribution()` (deferred staging removal) and aborts on failure, and set `ICMS_SYSTEM_DBVERSION` to 50
- [ ] 3.5 Installer path page: propose a default trust path outside the document root, and require confirmation for a trust path under it
- [ ] 3.6 Admin dashboard: warn when the trust path lies under the web root or the document root

## 4. Web servers (D3)

- [ ] 4.1 `htdocs/htaccess.txt`: add level 2 and level 3 blocks in `<IfModule mod_rewrite.c>` and deny rules for the staging directory
- [ ] 4.2 Add `docs/server/apache-vhost.conf`, `docs/server/nginx.conf` and `docs/server/Caddyfile`, each with levels 2 and 3 and the deny rules
- [ ] 4.3 Add `docs/server/herd/ImpressCmsValetDriver.php`: level 3 by default, `ICMS_HERD_ROUTING=2` to fall back, the same deny rules

## 5. Tests (D8)

- [ ] 5.1 `bin/test-db`: write the `host.php` and `vendor/autoload.php` stubs into `var/test/trust`, and stop copying and parking `vendor/`
- [ ] 5.2 Make `ServerProcess` serve `htdocs/` with `ICMS_TEST_ROUTING=2|3`, and support `ICMS_TEST_BASE_URL` for external servers
- [ ] 5.3 Update the tests that assume `public/`, the old autoload paths or `htdocs/vendor`:
  - `SharedAutoloaderTest`
  - the resolver fixtures
  - the snapshot normaliser
  - `phpunit.xml.dist`
  - `phpstan`
- [ ] 5.4 Add Http tests for the entry point:
  - `/` through the host;
  - the fallback without a host;
  - `/up` at level 2;
  - `trust_dist` returns 404;
  - a directly executed script shares the identity.
- [ ] 5.5 Add an upgrade test: a dbversion-48 site with a trust path without a host and a staging directory from `bin/build-dist`; run the system update; the host is installed and serves `/`
- [ ] 5.6 Add tests for the installer's default trust path and the admin warning

## 6. Cleanup, docs, CI

- [ ] 6.1 Remove `htdocs/composer.json`, `htdocs/composer.lock`, `htdocs/phpunit.xml.dist`, `bin/check-lock-alignment` and `run_tests.bat`, and remove `htdocs/vendor` from the package
- [ ] 6.2 `docs/symfony-host.md`: new layout, request flow from `htdocs/index.php`, routing levels per server, configuration, upgrade paths, and correct where the autoload guard lives. `README.md`: one installation procedure
- [ ] 6.3 CI:
  - one `composer install`, with no lock alignment;
  - the Http suite at levels 2 and 3;
  - `bin/build-dist` and the upgrade test;
  - a job `webservers` (Apache, nginx, FrankenPHP) using `docs/server/`.
- [ ] 6.4 Verify on the Herd test site (`C:\Users\david\sites\pre3`, trust path `C:\Users\david\trustpaths\pre3`):
  - a fresh installation;
  - an upgrade from 2.0.3;
  - levels 2 and 3.
- [ ] 6.5 `bin/spec-coverage`, PHPStan, the deprecation check, and `openspec validate relocate-host-into-trust-path`

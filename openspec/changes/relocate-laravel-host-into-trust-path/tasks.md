## 1. Legacy and library, taken over from the Symfony variant (L18, L22, L23)

- [ ] 1.1 Take over the framework-independent `htdocs/` changes of `98f7b467d`, `eb4bf8477`, `a7268a413`, `49333b28a` and `133b36e0a`: `include/host.php`, the `index.php` preamble, the autoloader discovery in `Bootstrap`, `icms::setup()` and the installer, `installTrustDistribution()` and its tests, the installer's host page and `language/english/host.php`, update step 50 (`ICMS_SYSTEM_DBVERSION` 50), the trust-path checks in the installer and the dashboard, `htaccess.txt`. Remove `htdocs/composer.json`, `htdocs/composer.lock`, `htdocs/phpunit.xml.dist` and `htdocs/tests/bootstrap.php`
- [ ] 1.2 Replace the Symfony specifics: `.env.local` with `APP_KEY` (L20), the Laravel caches (L22), comments that name Symfony

## 2. Host in the trust path (L17, L18, L21)

- [ ] 2.1 Move `public/index.php` to `host.php`, with the `php -S` fix-up for the web root's `index.php`; remove `public/`
- [ ] 2.2 `bootstrap/app.php`: register `<web root>/libraries/Autoloader.php` in `afterLoadingEnvironment()`
- [ ] 2.3 `composer.json`: drop the `Icms\` PSR-4 entry, the classmap and the files entry; require the library from `tests/bootstrap.php` and PHPStan
- [ ] 2.4 `config/icms.php`: the web root from `ICMS_LEGACY_ROOT`, `trust_dist` never served; `HostServiceProvider`: the front controller is `base_path('host.php')`
- [ ] 2.5 Update comments and docblocks that name `public/index.php`

## 3. Distribution (L22)

- [ ] 3.1 Add `bin/build-dist`; add `htdocs/trust_dist/` to `.gitignore`

## 4. Web servers (L19)

- [ ] 4.1 Take over `docs/server/` (Apache vhost, nginx, Caddyfile, Herd driver) and `.github/scripts/webserver.sh`

## 5. Tests (L24)

- [ ] 5.1 Take over the test support of the Symfony change: `bin/test-db` with the trust-path stubs, `ServerProcess` with `ICMS_TEST_ROUTING`, the fixture router, the install script, `bin/spec-coverage` over `htdocs/tests`
- [ ] 5.2 A test-only host marker on `RequestHandled`
- [ ] 5.3 Take over `HostDiscoveryTest`, `TrustDistributionTest`, `HostDeploymentTest`, `HostUpgradeTest` and `AdminTrustPathWarningTest`, adapted to the Laravel file names and `APP_KEY`
- [ ] 5.4 `phpunit.xml.dist`: the Library suite from the root only

## 6. Cleanup, docs, CI

- [ ] 6.1 Remove `bin/check-lock-alignment` and `run_tests.bat`
- [ ] 6.2 `docs/laravel-host.md`: layout, request flow from `htdocs/index.php`, routing levels per server, configuration, upgrade; `README.md`
- [ ] 6.3 CI: one `composer install`; the Http suite at levels 2 and 3; `bin/build-dist` and the upgrade test; the `webservers` job
- [ ] 6.4 Verify on the Herd site `lara3.test`: levels 2 and 3, a fresh installation through the web installer, and an upgrade
- [ ] 6.5 `bin/spec-coverage`, PHPStan, the deprecation check, `openspec validate relocate-laravel-host-into-trust-path`
- [ ] 6.6 DX log

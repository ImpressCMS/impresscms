## 1. Event log foundation

- [x] 1.1 Add `icms_core_EventLog` with sink selection, JSON line format, level threshold, request ID, re-entrancy guard and fallback to `error_log`
- [x] 1.2 Implement sanitizer (credentials, paths, truncation) and context allowlist
- [x] 1.3 Implement per-request rate limiting with a suppressed-count record
- [x] 1.4 Call early init from `include/common.php` before `icms::setup()`; add shutdown fatal detection
- [x] 1.5 Integrate `icms_core_Logger::handleError`/`handleException` with the event log without duplicate handlers or changed debug output
- [x] 1.6 Document `ICMS_LOG_FILE`, `ICMS_LOG_LEVEL` in `install/templates/mainfile.dist.php`
- [x] 1.7 Show a generic error page with a log reference when debug mode is off (uncaught exceptions and E_USER_ERROR)

## 2. Upgrade path

- [x] 2.1 Operation ID generation and cross-request persistence for `upgrade/index.php`
- [x] 2.2 Log start/step/result/completion in the legacy upgrade runner
- [x] 2.3 Instrument `modules/system/include/update.php` migrations with verified results
- [x] 2.4 Instrument `modulesadmin.php` module update (module, old/new DB version, failures)
- [x] 2.5 Instrument `icms_db_legacy_updater_Handler` operations
- [x] 2.6 Stop reporting success after swallowed exceptions or ignored failures; preserve retry behaviour; note changes in release notes

## 3. Runtime failures

- [x] 3.1 Bootstrap milestones and failed/last-started service in `icms::setup()`/`boot()`
- [x] 3.2 DB connection, `query()`, `exec()` and `prepare()` failures without duplicates and without SQL/values
- [x] 3.3 Preload, module launch and theme rendering failure records

## 4. Documentation

- [x] 4.1 Operator guide: location, permissions, rotation/retention, disk-full fallback, PHP-FPM/web-server logs, pre-bootstrap limits
- [x] 4.2 Describe the behavior changes of update runs in `docs/logging.md` (release notes are written at release time)

## 5. Verification (manual; 2.0 has no test suite)

Checked with CLI harnesses on PHP 7.4 and 8.4 and on a 2.0.4 test site (PHP 8.2, debug mode off) with temporary scripts that were removed afterwards. The remaining items need an admin session.

- [x] 5.1 `php -l` on every changed file with `php74` and `php84`
- [x] 5.2 Debug-off request with an uncaught exception: record present, response generic
- [x] 5.3 Failed DB connection: one record, no credentials
- [ ] 5.4 Forced migration step failure: failure recorded, no success record, retry works (updater level verified; the full module update screen needs an admin session)
- [x] 5.5 Missing preload and missing theme file: records name the component
- [x] 5.6 Shutdown fatal: critical record present
- [x] 5.7 Unwritable file sink: fallback to `error_log`, request unaffected
- [x] 5.8 Search log output for passwords, tokens, cookies, SQL and bound values: none
- [ ] 5.9 Debug mode on: console output and `ICMS_LOGGING_HOOK` unchanged (page renders and warnings are logged; the console is only shown to admins, so it needs an admin session)

## 6. Follow-up release: security events (opt-in)

Out of scope for the first PR; ships in a separate follow-up.

- [ ] 6.1 Authentication outcomes in `include/checklogin.php` without credentials or submitted identifiers
- [ ] 6.2 Administrative configuration change events

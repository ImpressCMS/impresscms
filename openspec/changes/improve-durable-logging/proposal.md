## Why

`icms_core_Logger` is a per-request debug collector. It keeps errors, queries, blocks and timings in memory and only shows them when debug mode is on and the viewer has permission. With debug mode off, the debug preload calls `error_reporting(0)` and sets `activated = false`, so nothing is recorded anywhere. Its exception handler prints diagnostics instead of persisting them.

Consequences for site owners and maintainers:

- A failed upgrade or module update leaves no trace of which step failed, and some migration exceptions are echoed while execution continues, so the outcome is unclear.
- A white screen after an update cannot be diagnosed from ImpressCMS itself; owners must find the PHP-FPM or web-server log.
- Failures before the logger service exists (preloads run before it is initialized) are never captured.
- Failed `exec()` and prepared-statement operations are not reported, only `query()`.

## What Changes

- Add a durable, structured, database-independent event sink (newline-delimited JSON) that works regardless of `debug_mode`, with PHP's error log as default and an optional protected file outside the web root.
- Initialize a minimal handler before preloads, then integrate it with `icms_core_Logger` without registering duplicate error/exception handlers. Capture uncaught `Throwable`s and shutdown-detectable fatal errors.
- Define an event contract: UTC timestamp, severity, stable event name, request ID, component, outcome, allowlisted context. Add an upgrade operation ID that survives across upgrade requests.
- Instrument the upgrade path (legacy `upgrade/`, system migrations, module update, DB updater): start, step, result, version transitions, failures. Verify real results; never log success after a failed step. Fix swallowed exceptions and ignored failures so success is not reported after failure, preserving retry behaviour.
- Instrument runtime boundaries: bootstrap milestones and last started service, DB connection failures, prepared-statement and `exec()` failures, preload/module/theme loading failures.
- Add operational controls: severity threshold independent of `activated`/`debug_mode`, rate limiting, opt-in verbose activity logging, redaction of secrets and personal data, unwritable-sink fallback, documentation for location, permissions, rotation and retention.
- Follow-up release (not in the first PR): authentication outcomes and administrative configuration changes.

Non-goals:

- Replacing or changing existing debug-mode rendering, the query-only `ICMS_LOGGING_HOOK`, or the debug preload behaviour.
- Capturing failures before ImpressCMS code runs (parse error in the entry script, PHP-FPM crash); these remain a PHP/server log concern and are documented as such.
- A database-backed log table, a log viewer UI, or external log shipping.
- Changes to bundled libraries in `htdocs/libraries/` other than `icms/`.

## Capabilities

### New Capabilities

- `durable-event-log`: sink, event contract, bootstrap-time initialization, error/exception/fatal capture, severity thresholds, redaction, rate limiting, failure isolation.
- `upgrade-event-logging`: correlated, result-verified logging of the legacy upgrade runner, system migrations, module updates and the DB updater.
- `runtime-failure-logging`: logging of bootstrap, database, preload/module and theme failures, plus the later authentication/admin-change phase.

### Modified Capabilities

None. There are no existing specs in `openspec/specs/`; existing logger and debug behaviour is preserved, not changed.

## Impact

- Code: `htdocs/libraries/icms/core/Logger.php`, `htdocs/libraries/icms.php`, `htdocs/include/common.php`, `htdocs/libraries/icms/db/Factory.php`, `htdocs/libraries/icms/db/mysql/Connection.php`, `htdocs/libraries/icms/db/legacy/updater/Handler.php`, `htdocs/modules/system/admin/modulesadmin/modulesadmin.php`, `htdocs/modules/system/include/update.php`, `upgrade/index.php`, `htdocs/libraries/icms/preload/Handler.php`, `htdocs/libraries/icms/view/theme/Object.php`, `htdocs/include/checklogin.php`, plus a new small log class under `htdocs/libraries/icms/core/`. `htdocs/plugins/preloads/debug_mode.php` is only observed, not changed.
- Config: new optional `mainfile.php` constants (read before the database exists); `htdocs/install/templates/mainfile.dist.php` documents them. Existing sites work unchanged without them.
- Docs: new operator documentation (location, permissions, rotation, retention).
- No database schema change; no `ICMS_SYSTEM_DBVERSION` bump.
- Compatibility: PHP 7.4 to 8.4, targeting the 2.0.x branch. Core changes need explicit approval before implementation (see CLAUDE.md).

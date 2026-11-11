## Context

Today `icms_core_Logger` (singleton, `instance()`) installs `set_error_handler` and `set_exception_handler` and collects data in memory. `debug_mode.php` reacts to the `loadService` event for `config` and either enables rendering or sets `error_reporting(0)`. Preloads are loaded before the logger service. `ICMS_LOGGING_HOOK` is only included from `addQuery()`.

## Goals / Non-Goals

**Goals:** durable records that work before DB/boot and with debug off; one contract; trustworthy upgrade outcome; no new failure modes; PHP 7.4 to 8.4.

**Non-goals:** see proposal. No DB table, no UI.

## Decisions

1. **New small final class `Icms\Core\EventLog` (static API, no dependencies)** in `htdocs/libraries/Icms/Core/`. It must work with only `mainfile.php` constants loaded, so it must not use the DB, the config service, handlers or language files. `icms_core_Logger` remains the debug collector and delegates durable events to it.
2. **Initialization point.** Called from `common.php` right after `version.php` is included and before `icms::setup()`. It registers `register_shutdown_function` for fatal detection. Error and exception handler ownership stays with `icms_core_Logger::instance()`, which forwards to `EventLog`, so there is one owner and no duplicates. No temporary early exception handler is installed: an exception that is uncaught before the logger exists ends as a PHP fatal error, which the shutdown check records as `php.fatal`, and PHP's default behavior is unchanged. Rejected: a second independent handler chain (duplicate records, ordering problems).
3. **Sink.** Optional `mainfile.php` constants: `ICMS_LOG_FILE` (absolute path), `ICMS_LOG_LEVEL` (default `warning`); `ICMS_LOG_ACTIVITY` arrives with the follow-up security events; all read via `defined()`. Default sink is `error_log($line, 0)`. The file sink uses `file_put_contents(..., FILE_APPEND | LOCK_EX)` with explicit result checks; any failure falls back to `error_log`, and a static re-entrancy guard prevents recursion. A file path inside `ICMS_ROOT_PATH` is refused with a single warning to `error_log`.
4. **Contract.** JSON line with `ts` (UTC ISO 8601), `level` (PSR-3 names), `event` (dot notation, e.g. `upgrade.step.failed`), `request_id` (once per request, `bin2hex(random_bytes(8))` with a fallback), `component`, `outcome` (`success|failure|skipped|info`), `context` (allowlist; unknown keys dropped).
5. **Redaction.** One sanitizer: strips DSN/credential patterns, replaces `ICMS_ROOT_PATH`/`ICMS_TRUST_PATH` with placeholders, truncates messages (about 500 chars). SQL and bound values are never accepted as context; DB events carry `errno`/SQLSTATE and an operation label (`db.prepare`, `db.exec`, `db.query`).
6. **Rate limiting.** Per request, key = event + component + location, max N (default 10) records, then one `suppressed` record at shutdown.
7. **Upgrade operation ID.** Generated at start; for the multi-request legacy runner persisted in the PHP session when available, otherwise passed as an opaque parameter validated against `^[a-f0-9]{16}$`. It is a correlation token only, never used for authorization. Module updates run in one request, so a generated operation ID plus the request ID suffices.
8. **Result verification and swallowed failures.** Step wrappers log the real return value (updater results, `copy`/`mkdir` results, `PDO::exec` return). Where `update.php` currently echoes an exception and continues, the catch records the failure and marks the operation failed so no completion record is written and the user-facing message says so. Retry semantics are unchanged: versions advance only where they did before, so re-running repeats failed steps. User-visible changes are listed in the release notes.
9. **DB event coverage.** Existing `prepare`/`execute` events stay for debug. Durable DB failure logging sits in the connection classes (`query()`, `exec()`, `prepare()`) and in the factory (connect), each failure reported once at its own layer. Failures of `PDOStatement::execute()` are not covered: that needs a custom statement class, which PDO does not allow on persistent connections. This is documented as a known limitation.
10. **Opt-in activity events** (auth, admin changes) behind `ICMS_LOG_ACTIVITY`, implemented in a later phase.

## Risks / Trade-offs

- Logging changes core: requires explicit approval, PHP 7.4-safe syntax, `php -l` with `php74` and `php84` on each file.
- Reporting swallowed migration failures may surface failures that were previously hidden; accepted because hiding them is the bug. Retry behaviour and release notes mitigate.
- Early init runs before config: it must tolerate missing constants.
- Log growth: no built-in rotation; mitigated by thresholds, rate limiting, opt-in verbosity and documented logrotate guidance.
- Fatal detection uses `error_get_last()` at shutdown; fatals before the early init remain uncapturable (documented).

## Migration Plan

Ship in 2.0.x with defaults that write warnings and above to PHP's error log only. Add commented constants to `mainfile.dist.php`; no migration for existing `mainfile.php`. Rollback: remove the init call; no persisted data.

## Resolved Questions

- Default severity threshold is `warning`.
- A file sink inside the web root (`ICMS_ROOT_PATH`) is always refused; no deny-rule detection.
- The legacy `upgrade/` runner persists the operation ID in the PHP session, falling back to a validated opaque parameter when no session is available.
- The security-events phase (auth and admin changes) ships in a follow-up release, not in the first PR.

## Implementation notes

- Rare, important operations (updates and upgrades) use `EventLog::audit()`, which bypasses the severity threshold and the rate limit, so their lifecycle is always recorded. Everything else uses `EventLog::log()`.
- PHP warnings, notices and deprecations follow `error_reporting()`; with debug mode off only fatal-class errors and uncaught exceptions are written. The debug preload sets `error_reporting(0)` and `@`-silenced errors cannot be told apart from that on PHP 7.4, so following `error_reporting()` avoids logging every silenced error.
- A failed table update or exception in a system update step sets `$abortUpdate`, which the update script already used to stop later steps and show an error. Later steps are now skipped when it is set, so the database version cannot advance past a failed step.
- The exception and `E_USER_ERROR` pages used to show the message, file and backtrace to every visitor. They now show only the request ID when debug mode is off (including before the configuration is loaded), so the ID points to the event log record. Messages prefixed with `notrace:` are written for visitors and stay visible.

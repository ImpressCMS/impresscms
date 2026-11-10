# Durable event log

ImpressCMS writes important events (failed updates, database connection failures, missing preloads or theme files, uncaught exceptions and fatal errors) to a durable log. It works with debug mode off and does not need the database, so it can also explain problems that happen early in a request or during an update.

The debug console (debug mode 1 or 2) is unchanged. It is a per-request view for administrators; the event log is a permanent record for site owners and maintainers.

## Configuration

Both settings are optional and go in `mainfile.php`. They are read before the database is available.

```php
// Write to a file instead of PHP's error log. Absolute path, outside the web root.
define('ICMS_LOG_FILE', XOOPS_TRUST_PATH . '/logs/icms-events.log');

// Minimum severity: debug, info, notice, warning (default), error or critical.
define('ICMS_LOG_LEVEL', 'warning');
```

Without `ICMS_LOG_FILE`, records go to PHP's error log (the `error_log` setting, or the web server/PHP-FPM error log).

Notes:

- A path inside the web root is refused; ImpressCMS then uses PHP's error log and writes one warning there.
- Create the log directory yourself and make it writable by the web server user only (for example mode `0750`, owned by the web server user). Do not put it under `htdocs`.
- Update and upgrade events are always written, regardless of `ICMS_LOG_LEVEL`.
- With the default level only warnings and above are written. Bootstrap milestones are `debug` level and appear only when you set `ICMS_LOG_LEVEL` to `debug`.
- If the file cannot be written (missing directory, permissions, disk full), records go to PHP's error log instead and the request continues normally.

## Record format

One JSON object per line:

```json
{"ts":"2026-11-10T20:05:21Z","level":"error","event":"db.operation.failed","request_id":"6bff3c29b6c038e7","component":"database","outcome":"failure","context":{"operation":"db.query","sqlstate":"42000","errno":1064}}
```

| Field | Meaning |
|---|---|
| `ts` | UTC timestamp |
| `level` | `debug`, `info`, `notice`, `warning`, `error` or `critical` |
| `event` | Stable event name, for example `upgrade.step.failed` |
| `request_id` | Shared by all records of one request |
| `operation_id` | Present during an update or upgrade; shared by all its records, also across the requests of the legacy `upgrade/` runner |
| `component` | `php`, `bootstrap`, `database`, `preload`, `module`, `theme` or `upgrade` |
| `outcome` | `success`, `failure`, `skipped` or `info` |
| `context` | Limited set of fields such as `module`, `step`, `from_version`, `to_version`, `errno`, `file`, `line` |

Common events: `php.fatal`, `php.exception.uncaught`, `php.error`, `bootstrap.service.failed`, `db.connect.failed`, `db.operation.failed`, `preload.load.failed`, `theme.render.failed`, `module.launch.failed`, `upgrade.started`, `upgrade.step.started`, `upgrade.step.completed`, `upgrade.step.failed`, `upgrade.module.started`, `upgrade.module.version`, `upgrade.module.completed`, `upgrade.module.failed`, `upgrade.update.completed`, `upgrade.update.failed`, `log.suppressed`.

## What is never logged

Passwords, tokens, cookies, request bodies, connection strings, SQL text and bound values are not written. Exception messages are sanitized and shortened, and file paths are shown relative to the installation (`TRUSTPATH` for the trust path). Database failures only record the operation, the SQLSTATE and the driver error code.

PHP warnings, notices and deprecations follow `error_reporting`, so with debug mode off only fatal-class errors, uncaught exceptions and the events above are written. Errors silenced with `@` are not written. The same error is written at most 10 times per request; a `log.suppressed` record states how many more were skipped.

## Rotation and retention

ImpressCMS does not rotate the file. Use your platform's tool, for example `logrotate` with `copytruncate`, or send records to PHP's error log and let the web server or PHP-FPM manage it. Keep logs only as long as needed to diagnose problems.

## What the event log cannot see

Failures before ImpressCMS code runs are only visible in PHP's or the web server's own error log: a parse error in an entry script, a PHP-FPM crash, a web server misconfiguration or an exhausted memory limit before startup. For a white screen with no ImpressCMS record, check the PHP-FPM or web server error log for the time of the request first.

Failures of `PDOStatement::execute()` in modules that use prepared statements directly are not reported by the core; failed `prepare()`, `exec()` and `query()` calls are.

## Changes in update behavior

- A step of the system module update that throws an exception, or a table update that fails, now stops the remaining steps and the database version is not advanced past it. The update reports an error, and running the update again repeats the failed step. Previously the exception message was shown, later steps still ran and could advance the version, so the failed step was never retried.
- The system module update function and the module update screen now report failure instead of success when a step failed, and the failure details are shown.
- A module update (`moduleUpgrade`) that had a failed table update no longer advances that module's database version.
- Obsolete files that could not be removed during the update are logged as warnings but do not stop the update.

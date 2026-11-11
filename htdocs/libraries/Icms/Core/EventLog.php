<?php
/**
 * \Icms\Core\EventLog component main class file
 *
 * @copyright   http://www.impresscms.org/ The ImpressCMS Project
 * @license     http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category    ICMS
 * @package     Core
 * @subpackage  EventLog
 */

namespace Icms\Core;

/**
 * Durable, structured event log.
 *
 * Writes one JSON object per line to PHP's error log or to a configurable file outside the web root.
 * It is independent of the database, of debug_mode and of the in-memory \Icms\Core\Logger collector,
 * and only relies on constants defined in mainfile.php, so it can be used before the kernel is booted.
 *
 * Optional mainfile.php constants:
 * - ICMS_LOG_FILE:  absolute path of a log file outside the web root (default: PHP's error log)
 * - ICMS_LOG_LEVEL: minimum severity: debug, info, notice, warning, error or critical (default: warning)
 *
 * Logging never throws and never recurses: any failure of the sink falls back to PHP's error log.
 *
 * @category    ICMS
 * @package     Core
 * @subpackage  EventLog
 */
final class EventLog
{
    public const DEBUG = 'debug';
    public const INFO = 'info';
    public const NOTICE = 'notice';
    public const WARNING = 'warning';
    public const ERROR = 'error';
    public const CRITICAL = 'critical';

    private const LEVELS = [
        'debug' => 100,
        'info' => 200,
        'notice' => 250,
        'warning' => 300,
        'error' => 400,
        'critical' => 500,
    ];

    private const OUTCOMES = ['success', 'failure', 'skipped', 'info'];

    /** Only these context keys are ever written. */
    private const CONTEXT_KEYS = [
        'module', 'step', 'table', 'from_version', 'to_version', 'version', 'errno', 'sqlstate',
        'operation', 'file', 'line', 'class', 'message', 'code', 'service', 'last_service',
        'preload', 'theme', 'template', 'reason', 'count', 'suppressed', 'suppressed_event',
    ];

    private const MAX_PER_KEY = 10;
    private const MAX_KEYS = 100;
    private const MAX_VALUE_LENGTH = 500;

    private static bool $initialized = false;
    private static bool $writing = false;
    private static ?string $requestId = null;
    private static ?string $operationId = null;
    private static ?int $threshold = null;
    private static ?string $logFile = null;

    /** @var array<string, int> */
    private static array $counts = [];

    /** @var array<string, array{event: string, component: string, count: int}> */
    private static array $suppressed = [];

    /**
     * Prepares the event log. Safe to call more than once.
     *
     * Registers the shutdown check for fatal errors. Error and exception handlers are not registered
     * here: \Icms\Core\Logger owns them and forwards to this class, so there are no duplicate handlers.
     */
    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;
        register_shutdown_function([self::class, 'shutdown']);
    }

    /**
     * Writes an event when its severity reaches the configured threshold.
     *
     * @param string $level one of the level constants
     * @param string $event stable event name in dot notation, for example "db.connect.failed"
     * @param string $component the part of the system that emits the event
     * @param string $outcome success, failure, skipped or info
     * @param array<string, mixed> $context allowlisted context; other keys are dropped
     */
    public static function log(string $level, string $event, string $component, string $outcome = 'info', array $context = []): bool
    {
        return self::dispatch($level, $event, $component, $outcome, $context, false);
    }

    /**
     * Writes an event regardless of the severity threshold and the rate limit.
     *
     * Meant for rare, important operations such as upgrades, whose full lifecycle must be recorded.
     *
     * @param array<string, mixed> $context
     */
    public static function audit(string $level, string $event, string $component, string $outcome = 'info', array $context = []): bool
    {
        return self::dispatch($level, $event, $component, $outcome, $context, true);
    }

    /**
     * Writes a failure record for a Throwable.
     *
     * @param array<string, mixed> $context additional allowlisted context
     */
    public static function logThrowable(\Throwable $throwable, string $event, string $component, string $level = self::ERROR, array $context = [], bool $bypass = false): bool
    {
        $context = array_merge($context, [
            'class' => get_class($throwable),
            'message' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'code' => $throwable->getCode(),
        ]);

        return self::dispatch($level, $event, $component, 'failure', $context, $bypass);
    }

    /**
     * Writes a record for a PHP error reported to the error handler.
     *
     * Warnings, notices and deprecations follow error_reporting(), so with debug mode off only
     * fatal-class errors are written. Errors silenced with the @ operator are not written.
     */
    public static function logPhpError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $fatal = in_array($errno, [E_USER_ERROR, E_RECOVERABLE_ERROR], true);
        if (! $fatal && ! (error_reporting() & $errno)) {
            return;
        }

        $level = self::NOTICE;
        if ($fatal) {
            $level = self::ERROR;
        } elseif (in_array($errno, [E_WARNING, E_USER_WARNING, E_CORE_WARNING, E_COMPILE_WARNING], true)) {
            $level = self::WARNING;
        }

        self::dispatch($level, 'php.error', 'php', 'failure', [
            'errno' => $errno,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
        ], false);
    }

    /**
     * Writes a record for a failed database operation.
     *
     * Only the operation label and the driver error codes are recorded, never the SQL or bound values.
     *
     * @param string $operation for example "db.query", "db.exec" or "db.prepare"
     * @param array $errorInfo the result of PDO::errorInfo(): SQLSTATE, driver code, driver message
     */
    public static function logDatabaseFailure(string $operation, array $errorInfo): void
    {
        self::dispatch(self::ERROR, 'db.operation.failed', 'database', 'failure', [
            'operation' => $operation,
            'sqlstate' => (string) ($errorInfo[0] ?? ''),
            'errno' => (int) ($errorInfo[1] ?? 0),
        ], false);
    }

    /**
     * Shutdown check: records fatal errors (including uncaught exceptions before the logger took over)
     * and the number of events suppressed by the rate limit.
     */
    public static function shutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            self::dispatch(self::CRITICAL, 'php.fatal', 'php', 'failure', [
                'errno' => $error['type'],
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
            ], true);
        }

        foreach (self::$suppressed as $entry) {
            self::dispatch(self::WARNING, 'log.suppressed', $entry['component'], 'info', [
                'suppressed_event' => $entry['event'],
                'suppressed' => $entry['count'],
            ], true);
        }

        self::$suppressed = [];
    }

    /**
     * Returns the identifier shared by all events of the current request.
     */
    public static function requestId(): string
    {
        if (self::$requestId === null) {
            self::$requestId = self::randomId(8);
        }

        return self::$requestId;
    }

    /**
     * Starts a correlated operation (for example an upgrade) and returns its identifier.
     */
    public static function beginOperation(): string
    {
        self::$operationId = self::randomId(6);

        return self::$operationId;
    }

    /**
     * Continues an operation started in an earlier request.
     *
     * The identifier is a correlation token only; anything that is not 12 lowercase hex characters is ignored.
     */
    public static function resumeOperation(string $operationId): bool
    {
        if (! preg_match('/^[a-f0-9]{12}$/', $operationId)) {
            return false;
        }

        self::$operationId = $operationId;

        return true;
    }

    /**
     * Ends the current operation.
     */
    public static function endOperation(): void
    {
        self::$operationId = null;
    }

    public static function operationId(): ?string
    {
        return self::$operationId;
    }

    /**
     * Removes credentials and installation paths from text and truncates it.
     */
    public static function sanitize(string $text, int $maxLength = self::MAX_VALUE_LENGTH): string
    {
        $text = str_replace('\\', '/', $text);

        foreach (self::secretValues() as $secret) {
            $text = str_replace($secret, '***', $text);
        }

        foreach (self::pathReplacements() as $path => $replacement) {
            $text = str_replace($path, $replacement, $text);
        }

        $text = preg_replace('~://[^/@\s:]+:[^/@\s]*@~', '://***@', $text) ?? $text;
        $text = preg_replace(
            '~\b(host|dbname|user|username|password|passwd|pwd|pass|port|token|secret|api[_-]?key)\s*[=:]\s*[^;\s,&]+~i',
            '$1=***',
            $text
        ) ?? $text;

        if (strlen($text) > $maxLength) {
            $text = (function_exists('mb_strcut') ? mb_strcut($text, 0, $maxLength, 'UTF-8') : substr($text, 0, $maxLength)) . '...';
        }

        return $text;
    }

    /**
     * @param array<string, mixed> $context
     */
    private static function dispatch(string $level, string $event, string $component, string $outcome, array $context, bool $bypass): bool
    {
        if (self::$writing) {
            return false;
        }

        try {
            $severity = self::LEVELS[$level] ?? self::LEVELS[self::ERROR];
            $context = self::filterContext($context);

            if (! $bypass) {
                if ($severity < self::threshold()) {
                    return false;
                }

                if (self::isRateLimited($event, $component, $context)) {
                    return false;
                }
            }

            $record = [
                'ts' => gmdate('Y-m-d\TH:i:s\Z'),
                'level' => isset(self::LEVELS[$level]) ? $level : self::ERROR,
                'event' => $event,
                'request_id' => self::requestId(),
                'component' => $component,
                'outcome' => in_array($outcome, self::OUTCOMES, true) ? $outcome : 'info',
            ];

            if (self::$operationId !== null) {
                $record['operation_id'] = self::$operationId;
            }

            $record['context'] = (object) $context;

            $json = json_encode(
                $record,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_PARTIAL_OUTPUT_ON_ERROR
            );
            if ($json === false) {
                return false;
            }

            return self::write($json);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * @param array<string, mixed> $context
     * @return array<string, bool|int|float|string>
     */
    private static function filterContext(array $context): array
    {
        $filtered = [];

        foreach ($context as $key => $value) {
            if (! in_array($key, self::CONTEXT_KEYS, true)) {
                continue;
            }

            if (is_bool($value) || is_int($value) || is_float($value)) {
                $filtered[$key] = $value;
                continue;
            }

            if (is_string($value)) {
                $filtered[$key] = self::sanitize($value);
            }
        }

        return $filtered;
    }

    /**
     * @param array<string, mixed> $context
     */
    private static function isRateLimited(string $event, string $component, array $context): bool
    {
        $key = $event . '|' . $component . '|' . ($context['operation'] ?? '') . '|' . ($context['file'] ?? '') . ':' . ($context['line'] ?? '');

        if (! isset(self::$counts[$key])) {
            if (count(self::$counts) >= self::MAX_KEYS) {
                return false;
            }

            self::$counts[$key] = 0;
        }

        self::$counts[$key]++;
        if (self::$counts[$key] <= self::MAX_PER_KEY) {
            return false;
        }

        if (! isset(self::$suppressed[$key])) {
            self::$suppressed[$key] = ['event' => $event, 'component' => $component, 'count' => 0];
        }

        self::$suppressed[$key]['count']++;

        return true;
    }

    private static function threshold(): int
    {
        if (self::$threshold === null) {
            $name = defined('ICMS_LOG_LEVEL') ? strtolower((string) ICMS_LOG_LEVEL) : self::WARNING;
            self::$threshold = self::LEVELS[$name] ?? self::LEVELS[self::WARNING];
        }

        return self::$threshold;
    }

    private static function write(string $line): bool
    {
        self::$writing = true;

        try {
            $file = self::logFile();
            if ($file !== '' && @file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX) !== false) {
                return true;
            }

            return error_log($line);
        } catch (\Throwable $e) {
            return false;
        } finally {
            self::$writing = false;
        }
    }

    /**
     * Returns the validated log file path, or an empty string to use PHP's error log.
     */
    private static function logFile(): string
    {
        if (self::$logFile !== null) {
            return self::$logFile;
        }

        self::$logFile = '';

        if (! defined('ICMS_LOG_FILE') || ! is_string(ICMS_LOG_FILE) || ICMS_LOG_FILE === '') {
            return self::$logFile;
        }

        $file = str_replace('\\', '/', ICMS_LOG_FILE);
        if (! preg_match('~^(/|[A-Za-z]:/)~', $file)) {
            error_log('ICMS event log: ICMS_LOG_FILE must be an absolute path, using the PHP error log');

            return self::$logFile;
        }

        $directory = realpath(dirname($file));
        if ($directory === false) {
            error_log('ICMS event log: the directory of ICMS_LOG_FILE does not exist, using the PHP error log');

            return self::$logFile;
        }

        $directory = rtrim(str_replace('\\', '/', $directory), '/');
        $root = defined('ICMS_ROOT_PATH') ? realpath(ICMS_ROOT_PATH) : false;
        if ($root !== false) {
            $root = rtrim(str_replace('\\', '/', $root), '/');
            if (DIRECTORY_SEPARATOR === '\\') {
                $inside = stripos($directory . '/', $root . '/') === 0;
            } else {
                $inside = strpos($directory . '/', $root . '/') === 0;
            }

            if ($inside) {
                error_log('ICMS event log: ICMS_LOG_FILE must be outside the web root, using the PHP error log');

                return self::$logFile;
            }
        }

        self::$logFile = $directory . '/' . basename($file);

        return self::$logFile;
    }

    /**
     * @return string[]
     */
    private static function secretValues(): array
    {
        $secrets = [];

        foreach (['XOOPS_DB_PASS', 'XOOPS_DB_SALT'] as $constant) {
            if (defined($constant) && is_string(constant($constant)) && strlen(constant($constant)) >= 4) {
                $secrets[] = str_replace('\\', '/', constant($constant));
            }
        }

        return $secrets;
    }

    /**
     * @return array<string, string> path => replacement, trust path first
     */
    private static function pathReplacements(): array
    {
        $replacements = [];

        foreach (['ICMS_TRUST_PATH' => 'TRUSTPATH', 'ICMS_ROOT_PATH' => ''] as $constant => $replacement) {
            if (! defined($constant) || ! is_string(constant($constant)) || constant($constant) === '') {
                continue;
            }

            $path = rtrim(str_replace('\\', '/', constant($constant)), '/');
            if ($path !== '') {
                $replacements[$path] = $replacement;
            }

            $real = realpath(constant($constant));
            if ($real !== false) {
                $real = rtrim(str_replace('\\', '/', $real), '/');
                if ($real !== '' && $real !== $path) {
                    $replacements[$real] = $replacement;
                }
            }
        }

        return $replacements;
    }

    private static function randomId(int $bytes): string
    {
        try {
            return bin2hex(random_bytes($bytes));
        } catch (\Throwable $e) {
            return substr(md5(uniqid('', true)), 0, $bytes * 2);
        }
    }
}

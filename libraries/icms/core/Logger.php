<?php

use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Handler\ChromePHPHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\PHPConsoleHandler;
use Monolog\Handler\ProcessableHandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Proxy logger class to add some compatibility stuff with older ICMS versions
 */
class icms_core_Logger extends Logger
{

	/**
	 * Started timers list
	 *
	 * @var array
	 */
	public $timers = [];

	/**
	 * Get a instance for default logger
	 *
	 * @return  static
	 *
	 * @static
	 */
	public static function &instance()
	{
		static $instance;
		if (!isset($instance)) {
			$enabled = (bool)env('LOGGING_ENABLED', false);
			$instance = new static('default', [
				new RotatingFileHandler(
					ICMS_LOGGING_PATH . '/default.log',
					0,
					$enabled ? Logger::DEBUG : Logger::ERROR
				)
			]);
			// Always catch errors, for security reasons
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			set_error_handler([$instance, 'handleError']);
			set_exception_handler([$instance, 'handleException']);
			if ($enabled) {
				$instance->enableRendering();
			}
		}
		return $instance;
	}

	/**
	 * Enable logger output
	 */
	public function enableRendering()
	{
		$this->disableRendering();
		foreach ($this->getDebugHandlersFromConfig() as $handler) {
			$this->pushHandler($handler);
		}
	}

	/**
	 * Disable logger output rendering.
	 */
	public function disableRendering()
	{
		$this->handlers = array_filter($this->handlers, function ($handler) {
			return !($handler instanceof BrowserConsoleHandler) &&
				!($handler instanceof PHPConsoleHandler) &&
				!($handler instanceof FirePHPHandler) &&
				!($handler instanceof ChromePHPHandler);
		});
	}

	/**
	 * Gets handlers from env variable DEBUG_TOOL
	 *
	 * @return ProcessableHandlerInterface[]
	 */
	protected function getDebugHandlersFromConfig()
	{
		$handlers = [];
		foreach (explode(',', env('DEBUG_TOOL')) as $debugTool) {
			switch (strtolower(trim($debugTool))) {
				case 'firephp':
					$handlers[] = new FirePHPHandler();
					break;
				case 'chromephp':
					$handlers[] = new ChromePHPHandler();
					break;
				case 'browserconsole':
				case 'default':
					$handlers[] = new BrowserConsoleHandler();
					break;
				case 'phpconsole':
					$handlers[] = new PHPConsoleHandler();
					break;
			}
		}
		return $handlers;
	}

	/**
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []): void
	{
		// this is needed to automatically add context substrings
		if (preg_match_all('/{([^}]+)}/', $message, $matches)) {
			foreach ($matches[0] as $i => $str) {
				if (!isset($context[$matches[1][$i]])) {
					continue;
				}
				$message = str_replace($str, $context[$matches[1][$i]], $message);
				unset($context[$matches[1][$i]]);
			}
		}

		parent::log($level, $message, $context);
	}

	/**
	 * Disabling logger
	 */
	public function disableLogger()
	{
		error_reporting(0);
		$this->handlers = array_filter($this->handlers, function ($handler) {
			return !($handler instanceof RotatingFileHandler);
		});
	}

	/**
	 * Start a timer
	 *
	 * @param string $name name of the timers
	 */
	public function startTime($name = 'ICMS')
	{
		$this->timers[$name] = microtime(true);
	}

	/**
	 * Stop a timer and prints to log
	 *
	 * @param string $name name of the timers
	 */
	public function stopTime($name = 'ICMS')
	{
		if (isset($this->timers[$name])) {
			return;
		}
		$this->info(
			sprintf('%s took %d', $name, microtime(true) - $this->timers[$name])
		);
		unset($this->timers[$name]);
	}

	/**
	 * Log a database query
	 *
	 * @param string $sql SQL string
	 * @param string $error error message (if any)
	 * @param int $errno error number (if any)
	 *
	 * @deprecated This method does nothing. Use Aura.SQL logging possibilities!
	 */
	public function addQuery($sql, $error = null, $errno = null)
	{

	}

	/**
	 * Log display of a block
	 *
	 * @param string $name name of the block
	 * @param bool $cached was the block cached?
	 * @param int $cachetime cachetime of the block
	 *
	 * @deprecated Use PSR logging functionality!
	 */
	public function addBlock($name, $cached = false, $cachetime = 0)
	{
		$this->info('Blocks', [
			'name' => $name,
			'cached' => $cached,
			'cachetime' => $cachetime
		]);
	}

	/**
	 * Log extra info
	 *
	 * @param string $name name
	 * @param int $msg message
	 *
	 * @deprecated Use standar PSR logger functionality
	 */
	public function addExtra($name, $msg)
	{
		$this->info('Extra', [
			'name' => $name,
			'msg' => $msg
		]);
	}

	/**
	 * Marks as deprecated
	 *
	 * @param string $msg Message/reason for deprecating
	 *
	 * @deprecated Will be removed in 2.1. Use @deprecated comments.
	 */
	public function addDeprecated($msg)
	{
		$this->handleError(E_USER_DEPRECATED, $msg, '', '');
	}

	/**
	 * Error handling
	 *
	 * @param int $errorNumber
	 * @param string $message
	 * @param string $file
	 * @param string $line
	 */
	public function handleError($errorNumber, $message, $file, $line)
	{
		$message = $this->sanitizePath($message);
		$file = $this->sanitizePath($file);

		$trace = array_slice(
			debug_backtrace(),
			1
		);
		$trace_data = [];
		foreach ($trace as $step) {
			if (!isset($step['file'])) {
				continue;
			}
			$trace_data[] = $this->sanitizePath($step['file']) . ' (' . $step['line'] . ')';
		}

		$data = compact('errorNumber', 'file', 'line', 'trace_data');

		switch ($errorNumber) {
			case E_USER_DEPRECATED:
			case E_DEPRECATED:
				$this->info($message, $data);
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$this->notice($message, $data);
				break;
			case E_PARSE:
			case E_STRICT:
			case E_COMPILE_ERROR:
				$this->emergency($message, $data);
				break;
			case E_CORE_ERROR:
			case E_ERROR:
			case E_RECOVERABLE_ERROR:
			case E_USER_ERROR:
				$this->error($message, $data);
				break;
			case E_WARNING:
			case E_USER_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
				$this->warning($message, $data);
				break;
		}
	}

	/**
	 * Sanitize path / url to file in erorr report
	 *
	 * @param string $path path to sanitize
	 *
	 * @return string  $path   sanitized path
	 *
	 * @access protected
	 */
	protected function sanitizePath($path)
	{
		$path = str_replace(
			array('\\', ICMS_ROOT_PATH, str_replace('\\', '/', realpath(ICMS_ROOT_PATH))),
			array('/', '', ''),
			$path
		);
		return $path;
	}

	/**
	 * Output buffering callback inserting logger dump in page output
	 * @param string $output
	 *
	 * @return string
	 *
	 * @deprecated Does nothing. Use Monolog handler for selecting how and where data should be printed.
	 */
	public function render($output)
	{
		return '';
	}

	/**
	 * Prints the logger output data
	 *
	 * @param string $mode
	 *
	 * @return  string
	 *
	 * @deprecated Does nothing
	 *
	 */
	public function dump($mode = '')
	{
		return '';
	}

	/**
	 * Gets the execution time of specific timer
	 *
	 * @param string $name name of the counter
	 *
	 * @return  float
	 *
	 * @deprecated Does nothing. Stopping timer logs time automatically.
	 */
	public function dumpTime($name = 'ICMS')
	{
		return 0;
	}

}

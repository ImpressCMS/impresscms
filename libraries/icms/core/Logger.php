<?php
/**
 * icms_core_Logger component main class file
 *
 * @copyright    The XOOPS project http://www.xoops.org/
 * @license        http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since        XOOPS 2.0
 *
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license        http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */

/**
 * Collects information for a page request
 *
 * Records information about database queries, blocks, and execution time
 * and can display it as HTML. It also catches php runtime errors.
 *
 * @since        XOOPS
 * @author        Kazumi Ono  <onokazu@xoops.org>
 * @author        Skalpa Keo <skalpa@xoops.org>
 *
 * @package    ICMS\Core
 */
class icms_core_Logger extends \Monolog\Logger
{

	public $queries = array();
	public $blocks = array();
	public $extra = array();
	public $logstart = array();
	public $logend = array();
	public $errors = array();
	public $deprecated = array();

	public $usePopup = false;
	public $activated = true;

	private $renderingEnabled = false;

	/**
	 * Get a reference to the only instance of this class
	 *
	 * @return  object icms_core_Logger  (@link icms_core_Logger) reference to the only instance
	 * @static
	 * @throws Exception
	 */
	public static function &instance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new static('default', [
				new \Monolog\Handler\RotatingFileHandler(
					ICMS_LOGGING_PATH . '/default.log'
				)
			]);
			// Always catch errors, for security reasons
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
			set_error_handler([$instance, 'handleError']);
			set_exception_handler([$instance, 'handleException']);
		}
		return $instance;
	}

	/**
	 * Enable logger output rendering
	 * When output rendering is enabled, the logger will insert its output within the page content.
	 * If the string <!--{xo-logger-output}--> is found in the page content, the logger output will
	 * replace it, otherwise it will be inserted after all the page output.
	 */
	public function enableRendering()
	{
		if (!$this->renderingEnabled) {
			ob_start(array(&$this, 'render'));
			$this->renderingEnabled = true;
		}
	}

	/**
	 * Disable logger output rendering.
	 */
	public function disableRendering()
	{
		if ($this->renderingEnabled) {
			$this->renderingEnabled = false;
		}
	}

	/**
	 * Disabling logger for some special occasion like AJAX requests and XML
	 *
	 * When the logger absolutely needs to be disabled whatever it is enabled or not in the preferences
	 * and whether user has permission or not to view it
	 */
	public function disableLogger()
	{
		$this->activated = false;
	}

	/**
	 * Returns the current microtime in seconds.
	 * @return float
	 */
	private function microtime()
	{
		$now = explode(' ', microtime());
		return (float)$now[0] + (float)$now[1];
	}

	/**
	 * Start a timer
	 * @param string $name name of the timer
	 */
	public function startTime($name = 'ICMS')
	{
		$this->logstart[$name] = $this->microtime();
	}

	/**
	 * Stop a timer
	 * @param string $name name of the timer
	 */
	public function stopTime($name = 'ICMS')
	{
		$this->logend[$name] = $this->microtime();
	}

	/**
	 * Log a database query
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
		if ($this->activated) {
			$this->info('Blocks', [
				'name' => $name,
				'cached' => $cached,
				'cachetime' => $cachetime
			]);
		}
	}

	/**
	 * Log extra information
	 *
	 * @param string $name name for the entry
	 * @param int $msg text message for the entry
	 */
	public function addExtra($name, $msg)
	{
		if ($this->activated) {
			$this->info('Extra', [
				'name' => $name,
				'msg' => $msg
			]);
		}
	}

	/**
	 * Marks as deprecated something
	 *
	 * @deprecated Will be removed in 2.1
	 *
	 * @param string $msg Message that is used for marking deprecation
	 */
	public function addDeprecated($msg)
	{
		$this->handleError(E_USER_DEPRECATED, $msg, '', '');
	}

	/**
	 * Error handling callback (called by the zend engine)
	 * @param string $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 */
	public function handleError($errno, $errstr, $errfile, $errline)
	{
		if (!($this->activated && ($errno & error_reporting()))) {
			return;
		}

		$errstr = $this->sanitizePath($errstr);
		$errfile = $this->sanitizePath($errfile);

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

		$data = compact('errno', 'errfile', 'errline', 'trace_data');

		switch ($errno) {
			case E_USER_DEPRECATED:
			case E_DEPRECATED:
				$this->info($errstr, $data);
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$this->notice($errstr, $data);
				break;
			case E_PARSE:
			case E_STRICT:
			case E_COMPILE_ERROR:
				$this->emergency($errstr, $data);
				break;
			case E_CORE_ERROR:
			case E_ERROR:
			case E_RECOVERABLE_ERROR:
			case E_USER_ERROR:
				$this->error($errstr, $data);
				break;
			case E_WARNING:
			case E_USER_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
				$this->warning($errstr, $data);
				break;
		}
	}

	/**
	 * Sanitize path / url to file in erorr report
	 * @param string $path path to sanitize
	 * @return string  $path   sanitized path
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
	 * Determines wheter output can be shown (based on permissions)
	 * @param string $output
	 * @return string  $output
	 */
	public function render($output)
	{
		global $icmsModule;
		$this->addExtra('Included files', count(get_included_files()) . ' files');
		$this->addExtra(_CORE_MEMORYUSAGE, icms_conv_nr2local(icms_convert_size(memory_get_usage())));
		$groups = (is_object(icms::$user)) ? icms::$user->getGroups() : ICMS_GROUP_ANONYMOUS;
		$moduleid = (isset($icmsModule) && is_object($icmsModule)) ? $icmsModule->getVar('mid') : 1;
		$gperm_handler = icms::handler('icms_member_groupperm');
		if (!$this->renderingEnabled || !$this->activated || !$gperm_handler->checkRight('enable_debug', $moduleid, $groups)) {
			return $output;
		}
		$this->renderingEnabled = $this->activated = false;
		$log = $this->dump($this->usePopup ? 'popup' : '');
		$pattern = '<!--{xo-logger-output}-->';
		$pos = strpos($output, $pattern);
		if ($pos !== false) {
			return substr($output, 0, $pos) . $log . substr($output, $pos + strlen($pattern));
		} else {
			return $output . $log;
		}
	}

	/**
	 * dump the logger output
	 *
	 * @param string $mode
	 * @return  string  $ret
	 * @access protected
	 */
	public function dump($mode = '')
	{
		include ICMS_LIBRARIES_PATH . '/icms/core/Logger_render.php';
		return $ret;
	}

	/**
	 * get the current execution time of a timer
	 *
	 * @param string $name name of the counter
	 * @return  float   current execution time of the counter
	 */
	public function dumpTime($name = 'ICMS')
	{
		if (!isset($this->logstart[$name])) {
			return 0;
		}
		$stop = $this->logend[$name] ?? $this->microtime();
		return $stop - $this->logstart[$name];
	}

}

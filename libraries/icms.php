<?php
/**
 * ICMS Services manager class definition
 *
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license    LICENSE.txt
 * @package    ImpressCMS/core
 * @since    1.3
 *
 * @internal    This class should normally be "icms_Kernel", marcan and I agreed on calling it "icms"
 * @internal    for convenience, as we are not targetting php 5.3+ yet
 */

use League\Container\Container;

/**
 * ICMS Kernel / Services manager
 *
 * The icms services manager handles the core bootstrap process, and paths/urls generation.
 * Global services are made available as static properties of this class.
 *
 * @package    ImpressCMS/core
 * @since    1.3
 */
final class icms extends Container {

	/**
	 * Current response
	 *
	 * @var \icms_response_Text
	 */
	static public $response;

	/**
	 * ImpressCMS paths locations
	 *
	 * @var array
	 */
	public static $paths = array(
		'www' => array(), 'modules' => array(), 'themes' => array(),
	);

	/** @var array */
	public static $urls = false;
	/**
	 * Some vars for compatibility
	 *
	 * @deprecated
	 */
	public static $db, $xoopsDB, $logger, $preload, $config, $security, $session, $module;
	/**
	 * Current logged in user
	 *
	 * @var icms_member_user_Object|null
	 */
	public static $user;
	/**
	 * array of handlers
	 * @var array
	 */
	static protected $handlers;

	/**
	 * Finalizes all processes as the script exits
	 */
	static public function shutdown() {
		// Ensure the session service can write data before the DB connection is closed
		if (session_id()) {
			session_write_close();
		}
		// Ensure the logger can decorate output before objects are destroyed
		while (@ob_end_flush());
	}

	/**
	 * Creates an object instance from an object definition.
	 * The factory parameter can be:
	 * - A fully qualified class name starting with '\': \MyClass or on PHP 5.3+ \ns\sub\MyClass
	 * - A valid PHP callback
	 *
	 * @param mixed $factory
	 * @param array $args Factory/Constructor arguments
	 * @return object
	 */
	static public function create($factory, $args = array()) {
		if (is_string($factory) && substr($factory, 0, 1) == '\\') {
// Class name
			$class = substr($factory, 1);
			if (!isset($args)) {
				$instance = new $class();
			} else {
				$reflection = new ReflectionClass($class);
				$instance = $reflection->newInstanceArgs($args);
			}
		} else {
			$instance = call_user_func_array($factory, $args);
		}
		return $instance;
	}

	/**
	 * Convert a ImpressCMS path to an URL
	 * @param    string $url
	 * @return    string
	 */
	static public function url($url)
	{
		return (false !== strpos($url, '://') ? $url : icms::getInstance()->path($url, true));
	}

	/**
	 * Convert a ImpressCMS path to a physical one
	 * @param    string $url URL string to convert to a physical path
	 * @param    boolean $virtual
	 * @return    string
	 */
	public function path($url, $virtual = false) {
		$path = '';
		@list($root, $path) = explode('/', $url, 2);
		if (!isset(self::$paths[$root])) {
			list($root, $path) = array('www', $url);
		}
		if (!$virtual) {
			// Returns a physical path
			return self::$paths[$root][0] . '/' . $path;
		}
		return !isset(self::$paths[$root][1])?'':(self::$paths[$root][1] . '/' . $path);
	}

	/**
	 * Get instance
	 *
	 * @return icms|null
	 */
	public static function &getInstance()
	{
		static $instance = null;
		if ($instance === null) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Build an URL with the specified request params
	 * @param    string $url
	 * @param    array $params
	 * @return    string
	 */
	static public function buildUrl($url, $params = array()) {
		if ($url == '.') {
			$url = $_SERVER['REQUEST_URI'];
		}
		$split = explode('?', $url);
		if (count($split) > 1) {
			list($url, $query) = $split;
			parse_str($query, $query);
			$params = array_merge($query, $params);
		}
		if (!empty($params)) {
			foreach ($params as $k => $v) {
				$params[$k] = $k . '=' . rawurlencode($v);
			}
			$url .= '?' . implode('&', $params);
		}
		return $url;
	}

	/**
	 * Gets the handler for a class
	 *
	 * @param string $name The name of the handler to get
	 * @param bool $optional Is the handler optional?
	 * @return        object        $inst        The instance of the object that was created
	 */
	static public function &handler($name, $optional = false)
	{
		if (!isset(self::$handlers[$name])) {
			$class = $name . "Handler";
			if (!class_exists($class)) {
				$class = $name . "_Handler";
				if (!class_exists($class)) {
					// Try old style handler loading (should be removed later, in favor of the
					// lookup table present in xoops_gethandler)
					$lower = strtolower(trim($name));
					if (file_exists($hnd_file = ICMS_ROOT_PATH . '/class/' . $lower . '.php')) {
						require_once $hnd_file;
					}
					if (!class_exists($class = 'Xoops' . ucfirst($lower) . 'Handler', false)) {
						if (!class_exists($class = 'Icms' . ucfirst($lower) . 'Handler', false)) {
							// Not found at all
							$class = false;
						}
					}
				}
			}
			self::$handlers[$name] = $class?new $class(self::$xoopsDB):false;
		}
		if (!self::$handlers[$name] && !$optional) {
			//trigger_error(sprintf("Handler <b>%s</b> does not exist", $name), E_USER_ERROR);
			throw new RuntimeException(sprintf("Handler <b>%s</b> does not exist", $name));
		}
		return self::$handlers[$name];
	}

	/**
	 * Initialize ImpressCMS before bootstrap
	 *
	 * @return $this
	 */
	public function setup()
	{
		self::$paths['www'] = array(ICMS_ROOT_PATH, ICMS_URL);
		self::$paths['modules'] = array(ICMS_ROOT_PATH . '/modules', ICMS_URL . '/modules');
		self::$paths['themes'] = array(ICMS_THEME_PATH, ICMS_THEME_URL);
		// Initialize the autoloader
		require_once __DIR__ . '/icms/Autoloader.php';
		icms_Autoloader::setup();
		register_shutdown_function(array(__CLASS__, 'shutdown'));
		$this->buildRelevantUrls();

		return $this;
	}

	/**
	 * Build URLs for global use throughout the application
	 * @return    array
	 */
	protected function buildRelevantUrls() {
		if (isset($_SERVER['HTTP_HOST']) && !self::$urls) {
			$http = strpos(ICMS_URL, "https://") === false
				?"http://"
				: "https://";

			/* $_SERVER variables MUST be sanitized! They don't necessarily come from the server */
			$filters = array(
				'SCRIPT_NAME' => 'str',
				'HTTP_HOST' => 'str',
				'QUERY_STRING' => 'str',
				'HTTP_REFERER' => 'url',
			);

			$clean_SERVER = icms_core_DataFilter::checkVarArray($_SERVER, $filters, false);

			$phpself = $clean_SERVER['SCRIPT_NAME'];
			$httphost = $clean_SERVER['HTTP_HOST'];
			$querystring = $clean_SERVER['QUERY_STRING'];
			if ($querystring != '') {
				$querystring = '?' . $querystring;
			}
			$currenturl = $http . $httphost . $phpself . $querystring;
			self::$urls = array();
			self::$urls['http'] = $http;
			self::$urls['httphost'] = $httphost;
			self::$urls['phpself'] = $phpself;
			self::$urls['querystring'] = $querystring;
			self::$urls['full_phpself'] = $http . $httphost . $phpself;
			self::$urls['full'] = $currenturl;

			$previouspage = '';
			if (array_key_exists('HTTP_REFERER', $_SERVER) && isset($_SERVER['HTTP_REFERER'])) {
				self::$urls['previouspage'] = $clean_SERVER['HTTP_REFERER'];
			}
			//self::$urls['isHomePage'] = (ICMS_URL . "/index.php") == ($http . $httphost . $phpself);
		}
		return self::$urls;
	}

	/**
	 * Launch bootstrap and instanciate global services
	 *
	 * @return $this
	 */
	public function boot()
	{
		$this->addServiceProvider(\ImpressCMS\Core\Providers\PreloadServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\LoggerServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\FilesystemServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\DatabaseServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\SecurityServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\SessionServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\ConfigServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\ModuleServiceProvider::class);
		$this->addServiceProvider(\ImpressCMS\Core\Providers\CacheServiceProvider::class);
		// register links for compatibility
		self::$db = $this->get('db');
		self::$xoopsDB = $this->get('xoopsDB');
		self::$logger = $this->get('logger');
		self::$preload = $this->get('preload');
		self::$config = $this->get('config');
		self::$security = $this->get('security');
		self::$session = $this->get('session');
		//Cant do this here until common.php 100% refactored
		//self::$preload->triggerEvent('finishCoreBoot');

		return $this;
	}
}

<?php
/**
 * ICMS Services manager class definition
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		icms
 * @subpackage	icms
 * @since		1.3
 * @version		SVN: $Id: Kernel.php 19775 2010-07-11 18:54:25Z malanciault $
 * @internal	This class should normally be "icms_Kernel", marcand and I agreed on calling it "icms"
 * @internal	for convenience, as we are not targetting php 5.3+ yet
 */

/**
 * ICMS Kernel / Services manager
 *
 * The icms services manager handles the core bootstrap process, and paths/urls generation.
 * Global services are made available as static properties of this class.
 *
 * @category	ICMS
 * @package		icms
 * @subpackage	icms
 * @since 		1.3
 */
abstract class icms {
	/**
	 * Preload handler
	 * @var icms_preload_Handler
	 */
	static public $preload;
	/**
	 * Logger
	 * @var icms_core_Logger
	 */
	static public $logger;
	/**
	 * Database connection
	 * @var icms_db_IConnection
	 */
	static public $db;

	/**
	 * Registered services definition
	 * @var array
	 */
	static public $services = array(
		"boot" => array(
			//"preload" => "icms_preload_Handler",		// Always created at boot start, no need here
			"logger" => "icms_core_Logger",
		),
		"optional" => array(),
	);

	/**
	 * ImpressCMS paths locations
	 *
	 * @var array
	 */
	static public $paths = array(
		'www' => array(), 'modules' => array(), 'themes' => array(),
	);
	/** @var array */
	static public $urls=false;

	/**
	 * array of handlers
	 * @var array
	 */
	static protected $handlers;

	/**
	 * Initialize ImpressCMS before bootstrap
	 */
	static public function setup() {
		self::$paths['www']		= array(ICMS_ROOT_PATH, ICMS_URL );
		self::$paths['modules']	= array(ICMS_ROOT_PATH . '/modules', ICMS_URL . '/modules' );
		self::$paths['themes']	= array(ICMS_ROOT_PATH . '/themes', ICMS_URL . '/themes' );
		self::buildRelevantUrls();
		// Initialize the autoloader
		require_once(dirname(__FILE__ )  . '/icms/Autoloader.php' );
		icms_Autoloader::setup();
	}
	/**
	 * Launch bootstrap and instanciate global services
	 * @return void
	 */
	static public function boot() {
		// We just hardcode the preload first, as we need to trigger an event
		self::$preload = icms_preload_Handler::getInstance();
		self::$preload->triggerEvent('startCoreBoot');

		foreach (self::$services['boot'] as $name => $class ) {
			if (method_exists($class, "instance")) {
				$inst = call_user_func(array($class,"instance"));
			} else {
				$inst = new $class;
			}
			self::$$name = $inst;
			self::$preload->triggerEvent("loadService", array($name,$inst));
		}
		//Cant do this here until common.php 100% refactored
		//self::$preload->triggerEvent('finishCoreBoot');
	}

	/**
	 * Convert a ImpressCMS path to a physical one
	 * @param	string	$url URL string to convert to a physical path
	 * @param 	boolean	$virtual
	 * @return 	string
	 */
	static public function path($url, $virtual = false) {
		$path = '';
		@list($root, $path) = explode('/', $url, 2);
		if (!isset(self::$paths[$root])) {
			list($root, $path) = array('www', $url);
		}
		if (!$virtual) {
			// Returns a physical path
			return self::$paths[$root][0] . '/' . $path;
		}
		return !isset(self::$paths[$root][1]) ? '' : (self::$paths[$root][1] . '/' . $path );
	}
	/**
	 * Convert a ImpressCMS path to an URL
	 * @param 	string	$url
	 * @return 	string
	 */
	static public function url($url) {
		return (false !== strpos($url, '://' ) ? $url : self::path($url, true ) );
	}
	/**
	 * Build an URL with the specified request params
	 * @param 	string 	$url
	 * @param 	array	$params
	 * @return 	string
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
	 * @param string  $name  The name of the handler to get
	 * @param bool  $optional	Is the handler optional?
	 * @return		object		$inst		The instance of the object that was created
	 */
	static public function handler($name, $optional = false ) {
		$name = strtolower(trim($name));

		if(!isset(self::$handlers[$name])) {
			$class = $name . '_Handler';
			if (class_exists($class))
				$handlers[$name] = new $class($GLOBALS['xoopsDB']);
			else {
				$class = 'icms_' . $name . '_Handler';
				if (!class_exists($class))
					$class = 'icms_core_' . ucfirst($name) . 'Handler';
				if (class_exists($class))
					$handlers[$name] = new $class($GLOBALS['xoopsDB']);
			}
		}

		if(!isset($handlers[$name]) && !$optional) trigger_error(sprintf("Class <b>%s</b> does not exist<br />Handler Name: %s", $class, $name), E_USER_ERROR);
		if(isset($handlers[$name])) return $handlers[$name];
		$inst = false;
		return $inst;
	}

	/**
	 * Build URLs for global use throughout the application
	 * @return 	array
	 */
	static protected function buildRelevantUrls() {
		if (!self::$urls) {
			$http = strpos(ICMS_URL, "https://") === false
				? "http://"
				: "https://";
			$phpself = $_SERVER['PHP_SELF'];
			$httphost = $_SERVER['HTTP_HOST'];
			$querystring = $_SERVER['QUERY_STRING'];
			if ($querystring != '' ) {
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
				self::$urls['previouspage'] = $_SERVER['HTTP_REFERER'];
			}
			//self::$urls['isHomePage'] = (ICMS_URL . "/index.php") == ($http . $httphost . $phpself);
		}
		return self::$urls;
	}


}


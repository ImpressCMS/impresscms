<?php
/**
 * Session Management
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Session
 * @version		SVN: $Id: SessionHandler.php 19775 2010-07-11 18:54:25Z malanciault $
 */
/*
 Based on SecureSession class
 Written by Vagharshak Tozalakyan <vagh@armdex.com>
 Released under GNU Public License
 */
/**
 * Handler for a session
 * @category	ICMS
 * @package     Session
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 */
class icms_core_SessionHandler {
	/**
	 * Database connection
	 * @var	object
	 * @access	private
	 */
	private $db;
	
	private $mainSaltKey = XOOPS_DB_SALT;

	/**
	 * Security checking level
	 * Possible value:
	 *	0 - no check;
	 *	1 - check browser characteristics (HTTP_USER_AGENT);
	 *	2 - check browser and IP A.B;
	 *	3 - check browser and IP A.B.C, recommended;
	 *	4 - check browser and IP A.B.C.D;
	 * @var	int
	 * @access	public
	 */
	public $securityLevel = 3;

	/**
	 * Security checking level for IPv6 Address types
	 * Possible value:
	 *	0 - no check;
	 *	1 - check browser characteristics (HTTP_USER_AGENT);
	 *	2 - check browser and IPv6 aaaa:bbbb;
	 *	3 - check browser and IPv6 aaaa:bbbb:cccc;
	 *	4 - check browser and IPv6 aaaa:bbbb:cccc:dddd;
	 *  5 - check browser and IPv6 aaaa:bbbb:cccc:dddd:eeee;
	 *  6 - check browser and IPv6 aaaa:bbbb:cccc:dddd:eeee:ffff;
	 *  7 - check browser and IPv6 aaaa:bbbb:cccc:dddd:eeee:ffff:gggg; (recommended)
	 *  8 - check browser and IPv6 aaaa:bbbb:cccc:dddd:eeee:ffff:gggg:hhhh;
	 *
	 * @var	int
	 * @access	public
	 */
	public $ipv6securityLevel = 7;

	/**
	 * Enable regenerate_id
	 * @var	bool
	 * @access	public
	 */
	public $enableRegenerateId = false;

	/**
	 * Constructor
	 * @param object $db reference to the {@link XoopsDatabase} object
	 *
	 */
	public function __construct(&$db) {
		$this->db =& $db;
	}

	/**
	 * Open a session
	 * @param	string  $save_path
	 * @param	string  $session_name
	 * @return	bool
	 */
	public function open($save_path, $session_name) {
		return true;
	}

	/**
	 * Close a session
	 * @return	bool
	 */
	public function close()	{
		self::gc_force();
		return true;
	}

	/**
	 * Read a session from the database
	 * @param	string  &sess_id    ID of the session
	 * @return	array   Session data
	 */
	public function read($sess_id) {
		return self::readSession($sess_id);
	}

	/**
	 * Inserts a session into the database
	 * @param   string  $sess_id
	 * @param   string  $sess_data
	 * @return  bool
	 **/
	public function write($sess_id, $sess_data) {
		return self::writeSession($sess_id, $sess_data);
	}

	/**
	 * Destroy a session
	 * @param   string  $sess_id
	 * @return  bool
	 **/
	public function destroy($sess_id) {
		return self::destroySession($sess_id);
	}

	/**
	 * Garbage Collector
	 * @param   int $expire Time in seconds until a session expires
	 * @return  bool
	 **/
	public function gc($expire) {
		return self::gcSession($expire);
	}

	/**
	 * Force gc for situations where gc is registered but not executed
	 **/
	public function gc_force() {
		if (rand(1, 100) < 11) {
			$expiration = empty($GLOBALS['xoopsConfig']['session_expire'])
						? @ini_get('session.gc_maxlifetime')
						: $GLOBALS['xoopsConfig']['session_expire'] * 60;
			$this->gc($expiration);
		}
	}

	/**
	 * Update the current session id with a newly generated one
	 * To be refactored
	 * @param   bool $delete_old_session
	 * @return  bool
	 **/
	public function icms_sessionRegenerateId($regenerate = false) {
		$old_session_id = session_id();
		if ($regenerate) {
			$success = session_regenerate_id(true);
			//			$this->destroy($old_session_id);
		} else {
			$success = session_regenerate_id();
		}
		// Force updating cookie for session cookie is not issued correctly in some IE versions,
		// or not automatically issued prior to PHP 4.3.3 for all browsers
		if ($success) {
			self::update_cookie();
		}
		return $success;
	}

	/**
	 * Update cookie status for current session
	 * To be refactored
	 * @param   string  $sess_id    session ID
	 * @param   int     $expire     Time in seconds until a session expires
	 * @return  bool
	 **/
	public function update_cookie($sess_id = null, $expire = null) {
		global $icmsConfig;
		$secure = substr(ICMS_URL, 0, 5) == 'https' ? 1 : 0; // we need to secure cookie when using SSL
		$session_name = ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '')
				? $icmsConfig['session_name'] : session_name();
		$session_expire = $expire !== NULL ? (int) $expire
				: (($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '')
					? $icmsConfig['session_expire'] * 60 : ini_get('session.cookie_lifetime'));
		$session_id = empty($sess_id) ? session_id() : $sess_id;
		setcookie($session_name, $session_id, $session_expire ? time() + $session_expire : 0, '/',  '', $secure, 0);
	}

	/**
	 * Creates a Fingerprint of the current User Session
	 * Fingerprint stored in current $_SESSION['icms_fprint']
	 * To be refactored
	 * @return  string
	 **/
	public function createFingerprint()
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$userIP = $_SERVER['REMOTE_ADDR'];
		
		return self::sessionFingerprint($userIP, $userAgent);
	}
	
	/**
	 * Compares the Fingerprint stored in $_SESSION['icms_fprint'] by creating a new Fingerprint.
	 * If they match, the Session is valid.
	 * To be refactored
	 * @return  bool
	 **/
	public function checkFingerprint()
	{
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		$userIP = $_SERVER['REMOTE_ADDR'];
		$sessFprint = self::sessionFingerprint($userIP, $userAgent);
		
		if($sessFprint == $_SESSION['icms_fprint'])
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	// Call this when init session.
	public function sessionOpen($regenerate = false) {
		$_SESSION['icms_fprint'] = self::createFingerprint();
		if($regenerate)
		{
			self::icms_sessionRegenerateId(true);
		}
	}

	public function removeExpiredCustomSession($sess)
	{
		global $icmsConfig;
		if($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != ''
				&& !isset($_COOKIE[$icmsConfig['session_name']]) && !empty($_SESSION[$sess]))
		{
			unset($_SESSION[$sess]);
		}
	}

	/**
	 * Closes the Session & removes Session Cookies for specified User Id
	 * To be refactored
	 * @param   string  $uid    User ID of user to close
	 * @return
	 **/
	public function sessionClose($uid)
	{
		global $icmsConfig;

		$uid = (int)$uid;
		
		session_regenerate_id(true);
		$_SESSION = array();
		if ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '')
		{
			setcookie($icmsConfig['session_name'], '', time()- 3600, '/',  '', 0, 0);
		}
		// autologin hack GIJ (clear autologin cookies)
		$icms_cookie_path = defined('ICMS_COOKIE_PATH') ? ICMS_COOKIE_PATH
				: preg_replace('?http://[^/]+(/.*)$?', '$1', ICMS_URL);
		if ($icms_cookie_path == ICMS_URL)
		{
			$icms_cookie_path = '/';
		}
		setcookie('autologin_uname', '', time() - 3600, $icms_cookie_path, '', 0, 0);
		setcookie('autologin_pass', '', time() - 3600, $icms_cookie_path, '', 0, 0);
		// end of autologin hack GIJ
		// clear entry from online users table
		if (is_object($uid))
		{
			$online_handler = icms::handler('icms_core_Online');
			$online_handler->destroy($uid);
		}
		return;
	}

	/**
	 * Creates Session ID & Starts the session
	 * removes Expired Custom Sessions after session Start
	 * @param   string  $sslpost_name    sets the session_id as ssl Name defined in preferences (if SSL enabled)
	 * @return
	 **/
	public function sessionStart($sslpost_name = '')
	{
		global $icmsConfig;
		
		if ($icmsConfig['use_ssl'] && isset($sslpost_name) && $sslpost_name != '')
		{
			session_id($sslpost_name);
		}
		elseif ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '' 
			&& $icmsConfig['session_expire'] > 0)
		{		
			if (isset($_COOKIE[$icmsConfig['session_name']]))
			{
				session_id($_COOKIE[$icmsConfig['session_name']]);
			}
			if (function_exists('session_cache_expire'))
			{
				session_cache_expire($icmsConfig['session_expire']);
			}
			@ini_set('session.gc_maxlifetime', $icmsConfig['session_expire'] * 60);
		}

		if ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '') {
			session_name($icmsConfig['session_name']);
		}
		else
		{		
			session_name('ICMSSESSION');
		}
		session_start();

		self::removeExpiredCustomSession('xoopsUserId');

		return;
	}

	public function sessionAutologin($autologinName, $autologinPass, $_POST)
	{
		return self::autologinSession($autologinName, $autologinPass, $_POST);
	}

	// Internal function. Returns sha256 from fingerprint.
	private function sessionFingerprint($ip, $userAgent)
	{
		$securityLevel = (int)$this->securityLevel;
		$ipv6securityLevel = (int)$this->ipv6securityLevel;

		$fingerprint = $this->mainSaltKey;

		if(isset($ip) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
		{
			if($securityLevel >= 1)
			{
				$fingerprint .= $userAgent;
			}
			if($securityLevel >= 2)
			{
				$num_blocks = abs($securityLevel);
				if($num_blocks > 4)
				{
					$num_blocks = 4;
				}
				$blocks = explode('.', $ip);
				for($i = 0; $i < $num_blocks; $i++)
				{
					$fingerprint .= $blocks[$i].'.';
				}
			}
		}
		elseif(isset($ip) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
		{
			if($securityLevel >= 1)
			{
				$fingerprint .= $userAgent;
			}
			if($securityLevel >= 2)
			{
				$blocks = explode(':', $ip);
				for($i = 0; $i < $num_blocks; $i++)
				{
					$fingerprint .= $blocks[$i].':';
				}
			}
		}
		else
		{
			icms_core_Debug::message('ERROR (Session Fingerprint): Invalid IP format,
				IP must be a valid IPv4 or IPv6 format', false);
			$fingerprint = '';
			return $fingerprint;
		}
		return hash('sha256', $fingerprint);
	}

	/**
	 * Read a session from the database
	 * @param	string  &sess_id    ID of the session
	 * @return	array   Session data
	 */
	private function readSession($sess_id)
	{
		$sql = sprintf('SELECT sess_data, sess_ip FROM %s WHERE sess_id = %s',
			$this->db->prefix('session'), $this->db->quoteString($sess_id));
		if (false != $result = $this->db->query($sql))
		{
			if (list($sess_data, $sess_ip) = $this->db->fetchRow($result))
			{
				if ($this->ipv6securityLevel > 1 && filter_var($sess_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
				{
					$pos = strpos($sess_ip, ":", $this->ipv6securityLevel - 1);

					if (strncmp($sess_ip, $_SERVER['REMOTE_ADDR'], $pos))
					{
						$sess_data = '';
					}
				}
				elseif ($this->securityLevel > 1 && filter_var($sess_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
				{
					$pos = strpos($sess_ip, ".", $this->securityLevel - 1);

					if (strncmp($sess_ip, $_SERVER['REMOTE_ADDR'], $pos))
					{
						$sess_data = '';
					}
				}
				return $sess_data;
			}
		}
		return '';
	}

	/**
	 * Inserts a session into the database
	 * @param   string  $sess_id
	 * @param   string  $sess_data
	 * @return  bool
	 **/
	private function writeSession($sess_id, $sess_data) {
		$sess_id = $this->db->quoteString($sess_id);
		$sess_data = $this->db->quoteString($sess_data);

		$sql = sprintf(
			"UPDATE %s SET sess_updated = '%u', sess_data = %s WHERE sess_id = %s",
			$this->db->prefix('session'), time(), $sess_data, $sess_id
			);
		$this->db->queryF($sql);
		if (!$this->db->getAffectedRows()) {
			$sql = sprintf(
				"INSERT INTO %s (sess_id, sess_updated, sess_ip, sess_data)"
				. " VALUES (%s, '%u', %s, %s)",
				$this->db->prefix('session'),
				$sess_id, time(),
				$this->db->quoteString($_SERVER['REMOTE_ADDR']),
				$sess_data
			);
			return $this->db->queryF($sql);
		}
		return true;
	}

	/**
	 * Destroy a session stored in DB
	 * @param   string  $sess_id
	 * @return  bool
	 **/
	private function destroySession($sess_id) {
		$sql = sprintf(
			'DELETE FROM %s WHERE sess_id = %s',
			$this->db->prefix('session'), $this->db->quoteString($sess_id)
		);
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		return true;
	}

	/**
	 * Garbage Collector
	 * @param   int $expire Time in seconds until a session expires
	 * @return  bool
	 **/
	private function gcSession($expire) {
		if (empty($expire)) {
			return true;
		}
		$mintime = time() - (int) $expire;
		$sql = sprintf("DELETE FROM %s WHERE sess_updated < '%u'", $this->db->prefix('session'), $mintime);
		return $this->db->queryF($sql);
	}

	private function autologinSession($autologinName, $autologinPass, $_POST)
	{
		// autologin V2 GIJ
		if(!empty($_POST))
		{
			$_SESSION['AUTOLOGIN_POST'] = $_POST;
			$_SESSION['AUTOLOGIN_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
			redirect_header(ICMS_URL.'/session_confirm.php', 0, '&nbsp;');
		}
		elseif(!empty($_SERVER['QUERY_STRING']) && substr($_SERVER['SCRIPT_NAME'], -19) != 'session_confirm.php')
		{
			$_SESSION['AUTOLOGIN_REQUEST_URI'] = $_SERVER['REQUEST_URI'];
			redirect_header(ICMS_URL.'/session_confirm.php', 0, '&nbsp;');
		}
		// end of autologin V2

		// redirect to ICMS_URL/ when query string exists (anti-CSRF) V1 code
		/* if (! empty( $_SERVER['QUERY_STRING'] )) {
		redirect_header( ICMS_URL . '/' , 0 , 'Now, logging in automatically' ) ;
		exit ;
		}*/

		$myts =& icms_core_Textsanitizer::getInstance();
		$uname = $myts->stripSlashesGPC($autologinName);
		$pass = $myts->stripSlashesGPC($autologinPass);
		if(empty($uname) || is_numeric($pass))
		{
			$user = false ;
		}
		else
		{
			// V3
			$uname4sql = addslashes($uname);
			$criteria = new icms_criteria_Compo(new icms_criteria_Item('uname', $uname4sql));
			$user_handler = icms::handler('icms_member_user');
			$users =& $user_handler->getObjects($criteria, false);
			if(empty($users) || count($users) != 1)
			{
				$user = false ;
			}
			else
			{
				// V3.1 begin
				$user = $users[0] ;
				$old_limit = time() - (defined('ICMS_AUTOLOGIN_LIFETIME') ? ICMS_AUTOLOGIN_LIFETIME : 604800);
				list($old_Ynj, $old_encpass) = explode(':', $pass);
				if(strtotime($old_Ynj) < $old_limit || md5($user->getVar('pass') .
						ICMS_DB_PASS.ICMS_DB_PREFIX.$old_Ynj) != $old_encpass)
				{
					$user = false;
				}
				// V3.1 end
			}
			unset($users);
		}
		$icms_cookie_path = defined('ICMS_COOKIE_PATH') ? ICMS_COOKIE_PATH
			: preg_replace('?http://[^/]+(/.*)$?', "$1", ICMS_URL);
		if($icms_cookie_path == ICMS_URL)
		{
			$icms_cookie_path = '/';
		}
		if(false != $user && $user->getVar('level') > 0)
		{
			// update time of last login
			$user->setVar('last_login', time());
			if(!$member_handler->insertUser($user, true))
			{
			}
			//$_SESSION = array();
			$_SESSION['xoopsUserId'] = $user->getVar('uid');
			$_SESSION['xoopsUserGroups'] = $user->getGroups();

			$user_theme = $user->getVar('theme');
			$user_language = $user->getVar('language');
			if(in_array($user_theme, $icmsConfig['theme_set_allowed']))
			{
				$_SESSION['xoopsUserTheme'] = $user_theme;
			}
			$_SESSION['UserLanguage'] = $user_language;

			// update autologin cookies
			$secure = substr(ICMS_URL, 0, 5) == 'https' ? 1 : 0; // we need to secure cookie when using SSL
			$expire = time() + (
				defined('ICMS_AUTOLOGIN_LIFETIME') ? ICMS_AUTOLOGIN_LIFETIME : 604800) ; // 1 week default
			setcookie('autologin_uname', $uname, $expire, $icms_cookie_path, '', $secure, 1);
			// V3.1
			$Ynj = date('Y-n-j');
			setcookie('autologin_pass', $Ynj.':'.md5($user->getVar('pass').ICMS_DB_PASS.ICMS_DB_PREFIX.$Ynj),
				$expire, $icms_cookie_path, '', $secure, 1);
		}
		else
		{
			setcookie('autologin_uname', '', time() - 3600, $icms_cookie_path, '', 0, 0);
			setcookie('autologin_pass', '', time() - 3600, $icms_cookie_path, '', 0, 0);
		}
	}
}
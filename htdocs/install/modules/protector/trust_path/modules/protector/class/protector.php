<?php

class Protector {
	var $mydirname;
	var $_conn = null;
	var $_conf = array();
	var $_conf_serialized = '';
	var $_bad_globals = array();
	var $message = '';
	var $warning = false;
	var $error = false;
	var $_doubtful_requests = array();
	var $_bigumbrella_doubtfuls = array();
	var $_dblayertrap_doubtfuls = array();
	var $_dblayertrap_doubtful_needles = array('information_schema', 'select', "'", '"');
	var $_logged = false;
	var $_done_badext = false;
	var $_done_intval = false;
	var $_done_dotdot = false;
	var $_done_nullbyte = false;
	var $_done_contami = false;
	var $_done_isocom = false;
	var $_done_union = false;
	var $_done_dos = false;
	var $_safe_badext = true;
	var $_safe_contami = true;
	var $_safe_isocom = true;
	var $_safe_union = true;
	var $_spamcount_uri = 0;
	var $_should_be_banned_time0 = false;
	var $_should_be_banned = false;
	var $_dos_stage = null;
	var $ip_matched_info = null;
	var $last_error_type = 'UNKNOWN';

	// Constructor
	function __construct() {
		$this->mydirname = 'protector';

		// Preferences from configs/cache
		$this->_conf_serialized = @file_get_contents($this->get_filepath4confighcache());
		$this->_conf = @unserialize($this->_conf_serialized);
		if (empty($this->_conf)) $this->_conf = array();

		if (!empty($this->_conf['global_disabled'])) return true;

		// die if PHP_SELF XSS found (disabled in 2.53)
		// if( preg_match( '/[<>\'";\n ]/' , @$_SERVER['PHP_SELF'] ) ) {
		// $this->message .= "Invalid PHP_SELF '{$_SERVER['PHP_SELF']}' found.\n" ;
		// $this->output_log( 'PHP_SELF XSS' ) ;
		// die( 'invalid PHP_SELF' ) ;
		// }

		// sanitize against PHP_SELF/PATH_INFO XSS (disabled in 3.33)
		// $_SERVER['PHP_SELF'] = strtr( @$_SERVER['PHP_SELF'] , array( '<' => '%3C' , '>' => '%3E' , "'" => '%27' , '"' => '%22' ) ) ;
		// if( ! empty( $_SERVER['PATH_INFO'] ) ) $_SERVER['PATH_INFO'] = strtr( @$_SERVER['PATH_INFO'] , array( '<' => '%3C' , '>' => '%3E' , "'" => '%27' , '"' => '%22' ) ) ;

		$this->_bad_globals = array('GLOBALS', '_SESSION', '_GET', '_POST', '_COOKIE', '_SERVER', '_REQUEST', '_ENV', '_FILES', 'xoopsDB', 'xoopsUser', 'xoopsUserId', 'xoopsUserGroups', 'xoopsUserIsAdmin', 'xoopsConfig', 'icmsConfig', 'xoopsOption', 'xoopsModule', 'xoopsModuleConfig');

		$this->_initial_recursive($_GET, 'G');
		$this->_initial_recursive($_POST, 'P');
		$this->_initial_recursive($_COOKIE, 'C');
	}

	function _initial_recursive($val, $key) {
		if (is_array($val)) {
			foreach ($val as $subkey => $subval) {
				// check bad globals
				if (in_array($subkey, $this->_bad_globals, true)) {
					$this->message .= "Attempt to inject '$subkey' was found.\n";
					$this->_safe_contami = false;
					$this->last_error_type = 'CONTAMI';
				}
				$this->_initial_recursive($subval, $key . '_' . base64_encode($subkey));
			}
		} else {
			// check nullbyte attack
			if (@$this->_conf['san_nullbyte'] && strstr($val, chr(0))) {
				$val = str_replace(chr(0), ' ', $val);
				$this->replace_doubtful($key, $val);
				$this->message .= "Injecting Null-byte '$val' found.\n";
				$this->output_log('NullByte', 0, false, 32);
				// $this->purge() ;
			}

			// register as doubtful requests against SQL Injections
			if (preg_match('?[\s\'"`/]?', $val)) {
				$this->_doubtful_requests["$key"] = $val;
			}
		}
	}

	static function &getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new Protector();
		}
		return $instance;
	}

	function updateConfIntoDb($name, $value) {
		$constpref = '_MI_' . strtoupper($this->mydirname);

		icms::$xoopsDB->queryF("UPDATE `" . icms::$xoopsDB->prefix("config") . "` SET `conf_value`='" . addslashes($value) . "' WHERE `conf_title` like '" . $constpref . "%' AND `conf_name`='" . addslashes($name) . "' LIMIT 1");
		$this->updateConfFromDB();
	}

	function updateConfFromDb() {
		$constpref = '_MI_' . strtoupper($this->mydirname);

		if (empty($this->_conn)) return false;

		$result = icms::$xoopsDB->queryF("SELECT conf_name,conf_value FROM " . XOOPS_DB_PREFIX . "_config WHERE conf_title like '" . $constpref . "%'");
		if (!$result || icms::$xoopsDB->getRowsNum($result) < 5) {
			return false;
		}
		$db_conf = array();
		while (list($key, $val) = icms::$xoopsDB->fetchRow($result)) {
			$db_conf[$key] = $val;
		}
		$db_conf_serialized = serialize($db_conf);

		// update config cache
		if ($db_conf_serialized != $this->_conf_serialized) {
			$fp = fopen($this->get_filepath4confighcache(), 'w');
			fwrite($fp, $db_conf_serialized);
			fclose($fp);
			$this->_conf = $db_conf;
		}
		return true;
	}

	function setConn($conn) {
		$this->_conn = $conn;
	}

	function getConf() {
		return $this->_conf;
	}

	function purge($redirect_to_top = false) {
		// clear all session values
		if (isset($_SESSION)) foreach ($_SESSION as $key => $val) {
			$_SESSION[$key] = '';
			if (isset($GLOBALS[$key])) $GLOBALS[$key] = '';
		}

		if (!headers_sent()) {
			// clear typical session id of PHP
			setcookie('PHPSESSID', '', time() - 3600, '/', '', 0);
			if (isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time() - 3600, '/', '', 0);
			}

			// clear autologin cookie
			$xoops_cookie_path = defined('ICMS_COOKIE_PATH') ? ICMS_COOKIE_PATH : preg_replace('?http://[^/]+(/.*)$?', "$1", ICMS_URL);
			if ($xoops_cookie_path == ICMS_URL) $xoops_cookie_path = '/';
			setcookie('autologin_uname', '', time() - 3600, $xoops_cookie_path, '', 0);
			setcookie('autologin_pass', '', time() - 3600, $xoops_cookie_path, '', 0);
		}

		if ($redirect_to_top) {
			header('Location: ' . ICMS_URL . '/');
			exit();
		} else {
			$ret = $this->call_filter('prepurge_exit');
			if ($ret == false) {
				die('Protector detects attacking actions');
			}
		}
	}

	function output_log($type = 'UNKNOWN', $uid = 0, $unique_check = false, $level = 1) {
		if ($this->_logged) return true;

		if (!($this->_conf['log_level'] & $level)) return true;

		// do we even need this on 1.4?
		if (empty($this->_conn)) {
			// $this->_conn = @mysql_connect( XOOPS_DB_HOST , XOOPS_DB_USER , XOOPS_DB_PASS ) ;
			// $this->_conn = icms::$db; // PDO
			// $this->_conn = icms::$xoopsDB; // non-PDO or PDO
			// if( ! $this->_conn ) die( 'db connection failed.' ) ;
			if (!icms::$xoopsDB) die('No DB connection');
			// if( ! mysql_select_db( XOOPS_DB_NAME , $this->_conn ) ) die( 'db selection failed.' ) ;
		}
		// --

		// this is not reliable - and we need to filter
		$ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
		$agent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING);
		// --

		if ($unique_check) {
			$result = icms::$xoopsDB->queryF('SELECT ip,type FROM ' . XOOPS_DB_PREFIX . '_' . $this->mydirname . '_log ORDER BY timestamp DESC LIMIT 1');
			list($last_ip, $last_type) = icms::$xoopsDB->fetchRow($result);
			if ($last_ip == $ip && $last_type == $type) {
				$this->_logged = true;
				return true;
			}
		}

		icms::$xoopsDB->queryF("INSERT INTO " . XOOPS_DB_PREFIX . "_" . $this->mydirname . "_log SET ip='" . $ip . "',agent='" . $agent . "',type='" . addslashes($type) . "',description='" . addslashes($this->message) . "',uid='" . (int) $uid . "',timestamp=NOW()");
		$this->_logged = true;
		return true;
	}

	function write_file_bwlimit($expire) {
		$expire = min((int) $expire, time() + 300);

		$fp = @fopen($this->get_filepath4bwlimit(), 'w');
		if ($fp) {
			@flock($fp, LOCK_EX);
			fwrite($fp, $expire . "\n");
			@flock($fp, LOCK_UN);
			fclose($fp);
			return true;
		} else {
			return false;
		}
	}

	function get_bwlimit() {
		list($expire) = @file(self::get_filepath4bwlimit());
		$expire = min((int) $expire, time() + 300);

		return $expire;
	}

	function get_filepath4bwlimit() {
		return ICMS_TRUST_PATH . '/modules/protector/configs/bwlimit' . substr(md5(ICMS_ROOT_PATH . XOOPS_DB_USER . XOOPS_DB_PREFIX), 0, 6);
	}

	function write_file_badips($bad_ips) {
		asort($bad_ips);

		$fp = @fopen($this->get_filepath4badips(), 'w');
		if ($fp) {
			@flock($fp, LOCK_EX);
			fwrite($fp, serialize($bad_ips) . "\n");
			@flock($fp, LOCK_UN);
			fclose($fp);
			return true;
		} else {
			return false;
		}
	}

	function register_bad_ips($jailed_time = 0, $ip = null) {
		if (empty($ip)) $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
		if (empty($ip)) return false;

		$bad_ips = $this->get_bad_ips(true);
		$bad_ips[$ip] = $jailed_time ? $jailed_time : 0x7fffffff;

		return $this->write_file_badips($bad_ips);
	}

	function get_bad_ips($with_jailed_time = false) {
		list($bad_ips_serialized) = @file(self::get_filepath4badips());
		$bad_ips = empty($bad_ips_serialized) ? array() : @unserialize($bad_ips_serialized);
		if (!is_array($bad_ips) || isset($bad_ips[0])) $bad_ips = array();

		// expire jailed_time
		$pos = 0;
		foreach ($bad_ips as $bad_ip => $jailed_time) {
			if ($jailed_time >= time()) break;
			$pos++ ;
		}
		$bad_ips = array_slice($bad_ips, $pos);

		if ($with_jailed_time) {
			return $bad_ips;
		} else {
			return array_keys($bad_ips);
		}
	}

	function get_filepath4badips() {
		return ICMS_TRUST_PATH . '/modules/protector/configs/badips' . substr(md5(ICMS_ROOT_PATH . XOOPS_DB_USER . XOOPS_DB_PREFIX), 0, 6);
	}

	function get_group1_ips($with_info = false) {
		list($group1_ips_serialized) = @file(self::get_filepath4group1ips());
		$group1_ips = empty($group1_ips_serialized) ? array() : @unserialize($group1_ips_serialized);
		if (!is_array($group1_ips)) $group1_ips = array();

		if ($with_info) {
			$group1_ips = array_flip($group1_ips);
		}

		return $group1_ips;
	}

	function get_filepath4group1ips() {
		return ICMS_TRUST_PATH . '/modules/protector/configs/group1ips' . substr(md5(ICMS_ROOT_PATH . XOOPS_DB_USER . XOOPS_DB_PREFIX), 0, 6);
	}

	function get_filepath4confighcache() {
		return ICMS_TRUST_PATH . '/modules/protector/configs/configcache' . substr(md5(ICMS_ROOT_PATH . XOOPS_DB_USER . XOOPS_DB_PREFIX), 0, 6);
	}

	function ip_match($ips) {
		foreach ($ips as $ip => $info) {
			if ($ip) {
				switch (substr($ip, -1)) {
					case '.':
						// foward match
						if (substr(filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP), 0, strlen($ip)) == $ip) {
							$this->ip_matched_info = $info;
							return true;
						}
						break;
					case '0':
					case '1':
					case '2':
					case '3':
					case '4':
					case '5':
					case '6':
					case '7':
					case '8':
					case '9':
						// full match
						if (filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP) == $ip) {
							$this->ip_matched_info = $info;
							return true;
						}
						break;
					default:
						// perl regex
						if (@preg_match($ip, filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP))) {
							$this->ip_matched_info = $info;
							return true;
						}
						break;
				}
			}
		}
		$this->ip_matched_info = null;
		return false;
	}

	function deny_by_htaccess($ip = null) {
		if (empty($ip)) $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
		if (empty($ip)) return false;
		if (!function_exists('file_get_contents')) return false;

		$target_htaccess = ICMS_ROOT_PATH . '/.htaccess';
		$backup_htaccess = ICMS_ROOT_PATH . '/uploads/.htaccess.bak';

		$ht_body = file_get_contents($target_htaccess);

		// make backup as uploads/.htaccess.bak automatically
		if ($ht_body && !file_exists($backup_htaccess)) {
			$fw = fopen($backup_htaccess, "w");
			fwrite($fw, $ht_body);
			fclose($fw);
		}

		// if .htaccess is broken, restore from backup
		if (!$ht_body && file_exists($backup_htaccess)) {
			$ht_body = file_get_contents($backup_htaccess);
		}

		// new .htaccess
		if ($ht_body === false) {
			$ht_body = '';
		}

		if (preg_match("/^(.*)#PROTECTOR#\s+(DENY FROM .*)\n#PROTECTOR#\n(.*)$/si", $ht_body, $regs)) {
			if (substr($regs[2], -strlen($ip)) == $ip) return true;
			$new_ht_body = $regs[1] . "#PROTECTOR#\n" . $regs[2] . " $ip\n#PROTECTOR#\n" . $regs[3];
		} else {
			$new_ht_body = "#PROTECTOR#\nDENY FROM $ip\n#PROTECTOR#\n" . $ht_body;
		}

		// error_log( "$new_ht_body\n" , 3 , "/tmp/error_log" ) ;

		$fw = fopen($target_htaccess, "w");
		@flock($fw, LOCK_EX);
		fwrite($fw, $new_ht_body);
		@flock($fw, LOCK_UN);
		fclose($fw);

		return true;
	}

	function getDblayertrapDoubtfuls() {
		return $this->_dblayertrap_doubtfuls;
	}

	function _dblayertrap_check_recursive($val) {
		if (is_array($val)) {
			foreach ($val as $subval) {
				$this->_dblayertrap_check_recursive($subval);
			}
		} else {
			if (strlen($val) < 6) return;
			foreach ($this->_dblayertrap_doubtful_needles as $needle) {
				if (stristr($val, $needle)) {
					$this->_dblayertrap_doubtfuls[] = $val;
				}
			}
		}
	}

	function dblayertrap_init($force_override = false) {
		if (!empty($GLOBALS['xoopsOption']['nocommon']) || defined('_LEGACY_PREVENT_EXEC_COMMON_') || defined('_LEGACY_PREVENT_LOAD_CORE_')) return; // skip

		$this->_dblayertrap_doubtfuls = array();
		$this->_dblayertrap_check_recursive($_GET);
		$this->_dblayertrap_check_recursive($_POST);
		$this->_dblayertrap_check_recursive($_COOKIE);
		if (empty($this->_conf['dblayertrap_wo_server'])) {
			$this->_dblayertrap_check_recursive($_SERVER);
		}

		if (!empty($this->_dblayertrap_doubtfuls) || $force_override) {
			@define('XOOPS_DB_ALTERNATIVE', 'ProtectorMysqlDatabase');
			require_once dirname(__DIR__) . '/class/ProtectorMysqlDatabase.class.php';
		}
	}

	function _bigumbrella_check_recursive($val) {
		if (is_array($val)) {
			foreach ($val as $subval) {
				$this->_bigumbrella_check_recursive($subval);
			}
		} else {
			if (preg_match('/[<\'"].{15}/s', $val, $regs)) {
				$this->_bigumbrella_doubtfuls[] = $regs[0];
			}
		}
	}

	function bigumbrella_init() {
		$this->_bigumbrella_doubtfuls = array();
		$this->_bigumbrella_check_recursive($_GET);
		$this->_bigumbrella_check_recursive(@$_SERVER['PHP_SELF']);

		if (!empty($this->_bigumbrella_doubtfuls)) {
			ob_start(array($this, 'bigumbrella_outputcheck'));
		}
	}

	function bigumbrella_outputcheck($s) {
		if (defined('BIGUMBRELLA_DISABLED')) return $s;

		if (function_exists('headers_list')) {
			foreach (headers_list() as $header) {
				if (stristr($header, 'Content-Type:') && !stristr($header, 'text/html')) {
					return $s;
				}
			}
		}

		if (!is_array($this->_bigumbrella_doubtfuls)) {
			return "bigumbrella injection found.";
		}

		foreach ($this->_bigumbrella_doubtfuls as $doubtful) {
			if (strstr($s, $doubtful)) {
				return "XSS found by Protector.";
			}
		}
		return $s;
	}

	function intval_allrequestsendid() {
		if ($this->_done_intval)
			return true;
		else
			$this->_done_intval = true;

		foreach ($_GET as $key => $val) {
			if (substr($key, -2) == 'id' && !is_array($_GET[$key])) {
				$newval = preg_replace('/[^0-9a-zA-Z_-]/', '', $val);
				$_GET[$key] = $newval;
				if ($_REQUEST[$key] == $_GET[$key]) {
					$_REQUEST[$key] = $newval;
				}
			}
		}
		foreach ($_POST as $key => $val) {
			if (substr($key, -2) == 'id' && !is_array($_POST[$key])) {
				$newval = preg_replace('/[^0-9a-zA-Z_-]/', '', $val);
				$_POST[$key] = $newval;
				if ($_REQUEST[$key] == $_POST[$key]) {
					$_REQUEST[$key] = $newval;
				}
			}
		}
		foreach ($_COOKIE as $key => $val) {
			if (substr($key, -2) == 'id' && !is_array($_COOKIE[$key])) {
				$newval = preg_replace('/[^0-9a-zA-Z_-]/', '', $val);
				$_COOKIE[$key] = $newval;
				if ($_REQUEST[$key] == $_COOKIE[$key]) {
					$_REQUEST[$key] = $newval;
				}
			}
		}

		return true;
	}

	function eliminate_dotdot() {
		if ($this->_done_dotdot)
			return true;
		else
			$this->_done_dotdot = true;

		foreach ($_GET as $key => $val) {
			if (is_array($_GET[$key])) continue;
			if (substr(trim($val), 0, 3) == '../' || strstr($val, '../../')) {
				$this->last_error_type = 'DirTraversal';
				$this->message .= "Directory Traversal '$val' found.\n";
				$this->output_log($this->last_error_type, 0, false, 64);
				$sanitized_val = str_replace(chr(0), '', $val);
				if (substr($sanitized_val, -2) != ' .') $sanitized_val .= ' .';
				$_GET[$key] = $sanitized_val;
				if ($_REQUEST[$key] == $_GET[$key]) {
					$_REQUEST[$key] = $sanitized_val;
				}
			}
		}
		/*
		 * foreach( $_POST as $key => $val ) {
		 * if( is_array( $_POST[ $key ] ) ) continue ;
		 * if( substr( trim( $val ) , 0 , 3 ) == '../' || strstr( $val , '../../' ) ) {
		 * $this->last_error_type = 'ParentDir' ;
		 * $this->message .= "Doubtful file specification '$val' found.\n" ;
		 * $this->output_log( $this->last_error_type , 0 , false , 128 ) ;
		 * $sanitized_val = str_replace( chr(0) , '' , $val ) ;
		 * if( substr( $sanitized_val , -2 ) != ' .' ) $sanitized_val .= ' .' ;
		 * $_POST[ $key ] = $HTTP_POST_VARS[ $key ] = $sanitized_val ;
		 * if( $_REQUEST[ $key ] == $_POST[ $key ] ){
		 * $_REQUEST[ $key ] = $sanitized_val ;
		 * }
		 * }
		 * }
		 * foreach( $_COOKIE as $key => $val ) {
		 * if( is_array( $_COOKIE[ $key ] ) ) continue ;
		 * if( substr( trim( $val ) , 0 , 3 ) == '../' || strstr( $val , '../../' ) ) {
		 * $this->last_error_type = 'ParentDir' ;
		 * $this->message .= "Doubtful file specification '$val' found.\n" ;
		 * $this->output_log( $this->last_error_type , 0 , false , 128 ) ;
		 * $sanitized_val = str_replace( chr(0) , '' , $val ) ;
		 * if( substr( $sanitized_val , -2 ) != ' .' ) $sanitized_val .= ' .' ;
		 * $_COOKIE[ $key ] = $HTTP_COOKIE_VARS[ $key ] = $sanitized_val ;
		 * if( $_REQUEST[ $key ] == $_COOKIE[ $key ] ){
		 * $_REQUEST[ $key ] = $sanitized_val ;
		 * }
		 * }
		 * }
		 */

		return true;
	}

	function &get_ref_from_base64index(&$current, $indexes) {
		foreach ($indexes as $index) {
			$index = base64_decode($index);
			if (!is_array($current)) return false;
			$current = &$current[$index];
		}
		return $current;
	}

	function replace_doubtful($key, $val) {
		$index_expression = '';
		$indexes = explode('_', $key);
		$base_array = array_shift($indexes);

		switch ($base_array) {
			case 'G':
				$main_ref = &$this->get_ref_from_base64index($_GET, $indexes);
				break;
			case 'P':
				$main_ref = &$this->get_ref_from_base64index($_POST, $indexes);
				break;
			case 'C':
				$main_ref = &$this->get_ref_from_base64index($_COOKIE, $indexes);
				break;
			default:
				exit();
		}
		if (!isset($main_ref)) exit();
		$request_ref = &$this->get_ref_from_base64index($_REQUEST, $indexes);
		if ($request_ref !== false && $main_ref == $request_ref) {
			$request_ref = $val;
		}
		$main_ref = $val;
	}

	function check_uploaded_files() {
		if ($this->_done_badext)
			return $this->_safe_badext;
		else
			$this->_done_badext = true;

		// extensions never uploaded
		$bad_extensions = array('php', 'phtml', 'phtm', 'php3', 'php4', 'cgi', 'pl', 'asp');
		// extensions needed image check (anti-IE Content-Type XSS)
		$image_extensions = array(1 => 'gif', 2 => 'jpg', 3 => 'png', 4 => 'swf', 5 => 'psd', 6 => 'bmp', 7 => 'tif', 8 => 'tif', 9 => 'jpc', 10 => 'jp2', 11 => 'jpx', 12 => 'jb2', 13 => 'swc', 14 => 'iff', 15 => 'wbmp', 16 => 'xbm');

		foreach ($_FILES as $_file) {
			if (!empty($_file['error'])) continue;
			if (!empty($_file['name']) && is_string($_file['name'])) {
				$ext = strtolower(substr(strrchr($_file['name'], '.'), 1));
				if ($ext == 'jpeg')
					$ext = 'jpg';
				else if ($ext == 'tiff')
					$ext = 'tif';
				else if ($ext == 'swc') $ext = 'swf';

				// anti multiple dot file (Apache mod_mime.c)
				if (count(explode('.', str_replace('.tar.gz', '.tgz', $_file['name']))) > 2) {
					$this->message .= "Attempt to multiple dot file {$_file['name']}.\n";
					$this->_safe_badext = false;
					$this->last_error_type = 'UPLOAD';
				}

				// anti dangerous extensions
				if (in_array($ext, $bad_extensions)) {
					$this->message .= "Attempt to upload {$_file['name']}.\n";
					$this->_safe_badext = false;
					$this->last_error_type = 'UPLOAD';
				}

				// anti camouflaged image file
				if (in_array($ext, $image_extensions)) {
					$image_attributes = @getimagesize($_file['tmp_name']);
					if ($image_attributes === false && is_uploaded_file($_file['tmp_name'])) {
						// open_basedir restriction
						$temp_file = ICMS_ROOT_PATH . '/uploads/protector_upload_temporary' . md5(time());
						move_uploaded_file($_file['tmp_name'], $temp_file);
						$image_attributes = @getimagesize($temp_file);
						@unlink($temp_file);
					}

					$imagetype = (int) $image_attributes[2];
					if ($imagetype == IMAGETYPE_SWC) $imagetype = IMAGETYPE_SWF;
					if ($image_attributes === false || $image_extensions[$imagetype] != $ext) {
						$this->message .= "Attempt to upload camouflaged image file {$_file['name']}.\n";
						$this->_safe_badext = false;
						$this->last_error_type = 'UPLOAD';
					}
				}
			}
		}

		return $this->_safe_badext;
	}

	function check_contami_systemglobals() {
		/*
		 * if( $this->_done_contami ) return $this->_safe_contami ;
		 * else $this->_done_contami = true ;
		 */

		/*
		 * foreach( $this->_bad_globals as $bad_global ) {
		 * if( isset( $_REQUEST[ $bad_global ] ) ) {
		 * $this->message .= "Attempt to inject '$bad_global' was found.\n" ;
		 * $this->_safe_contami = false ;
		 * $this->last_error_type = 'CONTAMI' ;
		 * }
		 * }
		 */
		return $this->_safe_contami;
	}

	function check_sql_isolatedcommentin($sanitize = true) {
		if ($this->_done_isocom)
			return $this->_safe_isocom;
		else
			$this->_done_isocom = true;

		foreach ($this->_doubtful_requests as $key => $val) {
			$str = $val;
			while ($str = strstr($str, '/*')) { /* */
				$str = strstr(substr($str, 2), '*/');
				if ($str === false) {
					$this->message .= "Isolated comment-in found. ($val)\n";
					if ($sanitize) $this->replace_doubtful($key, $val . '*/');
					$this->_safe_isocom = false;
					$this->last_error_type = 'ISOCOM';
				}
			}
		}
		return $this->_safe_isocom;
	}

	function check_sql_union($sanitize = true) {
		if ($this->_done_union)
			return $this->_safe_union;
		else
			$this->_done_union = true;

		foreach ($this->_doubtful_requests as $key => $val) {

			$str = str_replace(array('/*', '*/'), '', preg_replace('?/\*.+\*/?sU', '', $val));
			if (preg_match('/\sUNION\s+(ALL|SELECT)/i', $str)) {
				$this->message .= "Pattern like SQL injection found. ($val)\n";
				if ($sanitize) $this->replace_doubtful($key, preg_replace('/union/i', 'uni-on', $val));
				$this->_safe_union = false;
				$this->last_error_type = 'UNION';
			}
		}
		return $this->_safe_union;
	}

	function check_dos_attack($uid = 0, $can_ban = false) {
		if ($this->_done_dos) return true;

		$ip4sql = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
		$uri4sql = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);
		if (empty($ip4sql) || $ip4sql == '') return true;

		// gargage collection
		$result = icms::$xoopsDB->queryF("DELETE FROM " . icms::$xoopsDB->prefix($this->mydirname . "_access") . " WHERE expire < UNIX_TIMESTAMP()");

		// for older versions before updating this module
		if ($result === false) {
			$this->_done_dos = true;
			return true;
		}

		// sql for recording access log (INSERT should be placed after SELECT)
		$sql4insertlog = "INSERT INTO " . icms::$xoopsDB->prefix($this->mydirname . "_access") . " SET ip='$ip4sql',request_uri='$uri4sql',expire=UNIX_TIMESTAMP()+'" . (int) $this->_conf['dos_expire'] . "'";

		// bandwidth limitation
		if (@$this->_conf['bwlimit_count'] >= 10) {
			$result = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix($this->mydirname . "_access"));
			list($bw_count) = icms::$xoopsDB->fetchRow($result);
			if ($bw_count > $this->_conf['bwlimit_count']) {
				$this->write_file_bwlimit(time() + $this->_conf['dos_expire']);
			}
		}

		// F5 attack check (High load & same URI)
		$result = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix($this->mydirname . "_access") . " WHERE ip='$ip4sql' AND request_uri='$uri4sql'");
		list($f5_count) = icms::$xoopsDB->fetchRow($result);
		if ($f5_count > $this->_conf['dos_f5count']) {

			// delayed insert
			icms::$xoopsDB->queryF($sql4insertlog);

			// extends the expires of the IP with 5 minutes at least (pending)
			// $result = icms::$xoopsDB->queryF( "UPDATE ".icms::$xoopsDB->prefix($this->mydirname."_access")." SET expire=UNIX_TIMESTAMP()+300 WHERE ip='$ip4sql' AND expire<UNIX_TIMESTAMP()+300" ) ;

			// call the filter first
			$ret = $this->call_filter('f5attack_overrun');

			// actions for F5 Attack
			$this->_done_dos = true;
			$this->last_error_type = 'DoS';
			switch ($this->_conf['dos_f5action']) {
				default:
				case 'exit':
					$this->output_log($this->last_error_type, $uid, true, 16);
					exit();
				case 'none':
					$this->output_log($this->last_error_type, $uid, true, 16);
					return true;
				case 'biptime0':
					if ($can_ban) $this->register_bad_ips(time() + $this->_conf['banip_time0']);
					break;
				case 'bip':
					if ($can_ban) $this->register_bad_ips();
					break;
				case 'hta':
					if ($can_ban) $this->deny_by_htaccess();
					break;
				case 'sleep':
					sleep(5);
					break;
			}
			return false;
		}

		// Check its Agent
		if (trim($this->_conf['dos_crsafe']) != '' && preg_match($this->_conf['dos_crsafe'], @$_SERVER['HTTP_USER_AGENT'])) {
			// welcomed crawler
			$this->_done_dos = true;
			return true;
		}

		// Crawler check (High load & different URI)
		$result = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix($this->mydirname . "_access") . " WHERE ip='$ip4sql'");
		list($crawler_count) = icms::$xoopsDB->fetchRow($result);

		// delayed insert
		icms::$xoopsDB->queryF($sql4insertlog);

		if ($crawler_count > $this->_conf['dos_crcount']) {

			// call the filter first
			$ret = $this->call_filter('crawler_overrun');

			// actions for bad Crawler
			$this->_done_dos = true;
			$this->last_error_type = 'CRAWLER';
			switch ($this->_conf['dos_craction']) {
				default:
				case 'exit':
					$this->output_log($this->last_error_type, $uid, true, 16);
					exit();
				case 'none':
					$this->output_log($this->last_error_type, $uid, true, 16);
					return true;
				case 'biptime0':
					if ($can_ban) $this->register_bad_ips(time() + $this->_conf['banip_time0']);
					break;
				case 'bip':
					if ($can_ban) $this->register_bad_ips();
					break;
				case 'hta':
					if ($can_ban) $this->deny_by_htaccess();
					break;
				case 'sleep':
					sleep(5);
					break;
			}
			return false;
		}

		return true;
	}

	//
	function check_brute_force() {
		$ip4sql = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
		$uri4sql = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);
		if (empty($ip4sql) || $ip4sql == '') return true;

		$victim_uname = empty($_COOKIE['autologin_uname']) ? filter_input(INPUT_POST, 'uname', FILTER_SANITIZE_STRING) : filter_input(INPUT_COOKIE, 'autologin_uname', FILTER_SANITIZE_STRING);
		// some UA send 'deleted' as a value of the deleted cookie.
		if ($victim_uname == 'deleted') return;
		$mal4sql = addslashes("BRUTE FORCE: $victim_uname");

		// gargage collection
		$result = icms::$xoopsDB->queryF("DELETE FROM " . icms::$xoopsDB->prefix($this->mydirname . "_access") . " WHERE expire < UNIX_TIMESTAMP()");

		// sql for recording access log (INSERT should be placed after SELECT)
		$sql4insertlog = "INSERT INTO " . icms::$xoopsDB->prefix($this->mydirname . "_access") . " SET ip='$ip4sql',request_uri='$uri4sql',malicious_actions='$mal4sql',expire=UNIX_TIMESTAMP()+600";

		// count check
		$result = icms::$xoopsDB->query("SELECT COUNT(*) FROM " . icms::$xoopsDB->prefix($this->mydirname . "_access") . " WHERE ip='$ip4sql' AND malicious_actions like 'BRUTE FORCE:%'");
		list($bf_count) = icms::$xoopsDB->fetchRow($result);
		if ($bf_count > $this->_conf['bf_count']) {
			$this->register_bad_ips(time() + $this->_conf['banip_time0']);
			$this->last_error_type = 'BruteForce';
			$this->message .= "Trying to login as '" . addslashes($victim_uname) . "' found.\n";
			$this->output_log('BRUTE FORCE', 0, true, 1);
			$ret = $this->call_filter('bruteforce_overrun');
			if ($ret == false) exit();
		}
		// delayed insert
		icms::$xoopsDB->queryF($sql4insertlog);
	}

	function _spam_check_point_recursive($val) {
		if (is_array($val)) {
			foreach ($val as $subval) {
				$this->_spam_check_point_recursive($subval);
			}
		} else {
			// http_host
			$path_array = parse_url(ICMS_URL);
			$http_host = empty($path_array['host']) ? 'www.xoops.org' : $path_array['host'];

			// count URI up
			$count = -1;
			foreach (preg_split('#https?\:\/\/#i', $val) as $fragment) {
				if (strncmp($fragment, $http_host, strlen($http_host)) !== 0) {
					$count++ ;
				}
			}
			if ($count > 0) $this->_spamcount_uri += $count;

			// count BBCode likd [url=www....] up (without [url=http://...])
			$this->_spamcount_uri += count(preg_split('/\[url=(?!http|\\"http|\\\'http|' . $http_host . ')/i', $val)) - 1;
		}
	}

	function spam_check($points4deny, $uid) {
		$this->_spamcount_uri = 0;
		$this->_spam_check_point_recursive($_POST);

		if ($this->_spamcount_uri >= $points4deny) {
			$this->message .= filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING) . " SPAM POINT: $this->_spamcount_uri\n";
			$this->output_log('URI SPAM', $uid, false, 128);
			$ret = $this->call_filter('spamcheck_overrun');
			if ($ret == false) exit();
		}
	}

	function check_manipulation() {
		if ($_SERVER['SCRIPT_FILENAME'] == ICMS_ROOT_PATH . '/index.php') {
			$root_stat = stat(ICMS_ROOT_PATH);
			$index_stat = stat(ICMS_ROOT_PATH . '/index.php');
			$finger_print = $root_stat['mtime'] . ':' . $index_stat['mtime'] . ':' . $index_stat['ino'];
			if (empty($this->_conf['manip_value'])) {
				$this->updateConfIntoDb('manip_value', $finger_print);
			} else if ($finger_print != $this->_conf['manip_value']) {
				// Notify if finger_print is ident from old one
				$ret = $this->call_filter('postcommon_manipu');
				if ($ret == false) die('Protector detects site manipulation.');
				$this->updateConfIntoDb('manip_value', $finger_print);
			}
		}
	}

	function disable_features() {

		// disable "Notice: Undefined index: ..."
		$error_reporting_level = error_reporting(0);

		//
		// bit 1 : disable XMLRPC , criteria bug
		//
		if ($this->_conf['disable_features'] & 1) {

			// zx 2005/1/5 disable xmlrpc.php in root
			if( /* ! stristr( $_SERVER['SCRIPT_NAME'] , 'modules' ) && */ substr(@$_SERVER['SCRIPT_NAME'], -10) == 'xmlrpc.php') {
				$this->output_log('xmlrpc', 0, true, 1);
				exit();
			}

			// security bug of class/criteria.php 2005/6/27
			if ($_POST['uname'] === '0' || $_COOKIE['autologin_pass'] === '0') {
				$this->output_log('CRITERIA');
				exit();
			}
		}

		//
		// bit 11 : XSS+CSRFs in XOOPS < 2.0.10
		//
		if ($this->_conf['disable_features'] & 1024) {

			// root controllers
			if (!stristr(@$_SERVER['SCRIPT_NAME'], 'modules')) {
				// zx 2004/12/13 misc.php debug (file check)
				if (substr(@$_SERVER['SCRIPT_NAME'], -8) == 'misc.php' && ($_GET['type'] == 'debug' || $_POST['type'] == 'debug') && !preg_match('/^dummy_[0-9]+\.html$/', $_GET['file'])) {
					$this->output_log('misc debug');
					exit();
				}

				// zx 2004/12/13 misc.php smilies
				if (substr(@$_SERVER['SCRIPT_NAME'], -8) == 'misc.php' && ($_GET['type'] == 'smilies' || $_POST['type'] == 'smilies') && !preg_match('/^[0-9a-z_]*$/i', $_GET['target'])) {
					$this->output_log('misc smilies');
					exit();
				}

				// zx 2005/1/5 edituser.php avatarchoose
				if (substr(@$_SERVER['SCRIPT_NAME'], -12) == 'edituser.php' && $_POST['op'] == 'avatarchoose' && strstr($_POST['user_avatar'], '..')) {
					$this->output_log('edituser avatarchoose');
					exit();
				}
			}

			// zx 2005/1/4 findusers
			if (substr(@$_SERVER['SCRIPT_NAME'], -24) == 'modules/system/admin.php' && ($_GET['fct'] == 'findusers' || $_POST['fct'] == 'findusers')) {
				foreach ($_POST as $key => $val) {
					if (strstr($key, "'") || strstr($val, "'")) {
						$this->output_log('findusers');
						exit();
					}
				}
			}

			// comment comment_post.php
			if (isset($_POST['com_dopreview']) && !strstr(substr(@$_SERVER['HTTP_REFERER'], -16), 'comment_post.php')) {
				$_POST['dohtml'] = 0;
			}
			// tpl preview
			if (substr(@$_SERVER['SCRIPT_NAME'], -24) == 'modules/system/admin.php' && ($_GET['fct'] == 'tplsets' || $_POST['fct'] == 'tplsets')) {
				if ($_POST['op'] == 'previewpopup' || $_GET['op'] == 'previewpopup' || isset($_POST['previewtpl'])) {
					die("Danger! don't use this preview.(by Protector)");
				}
			}
		}

		// restore reporting level
		error_reporting($error_reporting_level);
	}

	function call_filter($type, $dying_message = '') {
		require_once __DIR__ . '/ProtectorFilter.php';
		$filter_handler = &ProtectorFilterHandler::getInstance();
		$ret = $filter_handler->execute($type);
		if ($ret == false && $dying_message) {
			die($dying_message);
		}

		return $ret;
	}
}

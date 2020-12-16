<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/**
 * Helper functions available in the ImpressCMS process
 *
 * @copyright    http://www.xoops.org/ The XOOPS Project
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package    ImpressCMS/core
 * @author    http://www.xoops.org The XOOPS Project
 * @author    modified by marcan <marcan@impresscms.org>
 */

if (!function_exists('xoops_header')) {
	/**
	 * The header
	 *
	 * Implements all functions that are executed within the header of the page
	 * (meta tags, header expiration, etc)
	 * It will all be echoed, so no return in this function
	 *
	 * @deprecated 2.0 Use icms_response_* classes instead!
	 *
	 * @param bool $closehead close the <head> tag
	 */
	function xoops_header($closehead = true)
	{
		trigger_error('xoops_header was deprecached from 2.0 use icms_response_* classes instead!', E_USER_DEPRECATED);

		global $icmsConfig, $xoopsOption;

		$xoopsOption['response'] = new \ImpressCMS\Core\Response\ViewResponse([
			'template_canvas' => 'db:system_blank.html'
		]);

		ob_start(function ($buffer) use ($xoopsOption) {
			$i = mb_strpos(strtoupper($buffer), '</HEAD>');
			if ($i !== false) {
				$head = mb_substr($buffer, 0, $i);
			} else {
				$head = '';
			}
			preg_match('/<body([^>]*)>/mis', $buffer, $matches);
			$i = mb_strpos($buffer, $matches[0]);
			$buffer = mb_substr($buffer, $i + mb_strlen($matches[0]));
			if (!empty($matches[1])) {
				preg_match_all('/\h(([A-Za-z_][A-Za-z_0-9\-]*)=("([^"]+)"|\'([^\']+)\'))|([A-Za-z_][A-Za-z_0-9\-]*)/mis', $matches[1], $matches);
				$attributes = [];
				foreach ($matches[2] as $i => $value) {
					if (!empty($value)) {
						if (empty($matches[4][$i])) {
							$attributes[$value] = $matches[5][$i];
						} else {
							$attributes[$value] = $matches[4][$i];
						}
					} else {
						$attributes[$matches[6][$i]] = $matches[6][$i];
					}
				}
				$head .= '<script type="text/javascript">' . PHP_EOL;
				$head .= sprintf("function icms_updateBody() {
                        if (!jQuery) {
                            return;
                        }
                        clearInterval(icms_updateBody.interval);
                        jQuery('body').attr(%s);
                        delete icms_updateBody;
                    }
                    icms_updateBody.interval = setInterval(icms_updateBody, 500);
                    %s", json_encode($attributes), PHP_EOL);
				$head .= '</script>';
			}
			if (!empty($head)) {
				$xoopsOption['response']->assign('icms_module_header', $xoopsOption['response']->get_template_vars('icms_module_header') . $head);
			}
			return $buffer;
		});
	}
}

if (!function_exists('xoops_footer')) {
	/**
	 * The footer
	 *
	 * Implements all functions that are executed in the footer
	 *
	 * @deprecated 2.0 Use icms_response_* classes instead!
	 */
	function xoops_footer()
	{
		global $icmsConfigMetaFooter, $xoopsOption;
		echo $xoopsOption['response']->getBody();
		echo htmlspecialchars($icmsConfigMetaFooter['google_analytics']);
	}
}

if (!function_exists('xoops_getUserTimestamp')) {
	/**
	 * Get the timestamp based on the user settings
	 *
	 * @param string $time String with time
	 * @param string $timeoffset The time offset string
	 * @return string  $usertimestamp  The generated user timestamp
	 * @todo Move to a static class method - user
	 */
	function xoops_getUserTimestamp($time, $timeoffset = "")
	{
		global $icmsConfig;
		if ($timeoffset == '') {
			if (icms::$user) {
				$timeoffset = icms::$user->timezone_offset;
			} else {
				$timeoffset = $icmsConfig['default_TZ'];
			}
		}
		$usertimestamp = (int)($time) + ((float)($timeoffset) - $icmsConfig['server_TZ']) * 3600;
		return $usertimestamp;
	}
}

if (!function_exists('userTimeToServerTime')) {
	/**
	 * Function to calculate server timestamp from user entered time (timestamp)
	 *
	 * @param string $timestamp String with time
	 * @return string  $timestamp  The generated timestamp
	 * @todo Move to a static class method - date and time
	 */
	function userTimeToServerTime($timestamp, $userTZ = null)
	{
		global $icmsConfig;
		if (!isset($userTZ)) {
			$userTZ = $icmsConfig['default_TZ'];
		}
		$timestamp = $timestamp - (($userTZ - $icmsConfig['server_TZ']) * 3600);
		return $timestamp;
	}
}

if (!function_exists('formatURL')) {
	/**
	 * Format an URL
	 *
	 * @param string $url The URL to format
	 * @return string  $url The generated URL
	 * @todo Move to a static class method - string formatting
	 */
	function formatURL($url)
	{
		$url = trim($url);
		if ($url != '') {
			if ((!preg_match("/^http[s]*:\/\//i", $url)) && (!preg_match("/^ftp*:\/\//i", $url)) && (!preg_match("/^ed2k*:\/\//i", $url))) {
				$url = 'http://' . $url;
			}
		}
		return $url;
	}
}

if (!function_exists('redirect_header')) {
	/**
	 * Function to redirect a user to certain pages
	 *
	 * @param string $url The URL to redirect to
	 * @param int $time DEPRECATED: does nothing
	 * @param string $message The message to show while redirecting
	 * @param bool $addredirect Add a link to the redirect URL?
	 * @param string $allowExternalLink Allow external links
	 */
	function redirect_header($url, $time = 3, $message = '', $addredirect = true, $allowExternalLink = false)
	{
		global $icmsConfig, $icmsConfigPersona;
		if (preg_match("/[\\0-\\31]|about:|script:/i", $url) && preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
			$url = ICMS_URL;
		}
		if (!$allowExternalLink && $pos = strpos($url, '://')) {
			$xoopsLocation = substr(ICMS_URL, strpos(ICMS_URL, '://') + 3);
			if (substr($url, $pos + 3, strlen($xoopsLocation)) != $xoopsLocation) {
				$url = ICMS_URL;
			} elseif (substr($url, $pos + 3, strlen($xoopsLocation) + 1) == $xoopsLocation . '.') {
				$url = ICMS_URL;
			}
		}
		// if the user selected a theme in the theme block, let's use this theme

		if (!empty($_SERVER['REQUEST_URI']) && $addredirect && strstr($url, 'user.php')) {
			if (!strstr($url, '?')) {
				$url .= '?xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
			} else {
				$url .= '&amp;xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
			}
		}
		if (defined('SID') && SID && (!isset($_COOKIE[session_name()]) || ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '' && !isset($_COOKIE[$icmsConfig['session_name']])))) {
			if (!strstr($url, '?')) {
				$url .= '?' . SID;
			} else {
				$url .= '&amp;' . SID;
			}
		}
		$url = preg_replace("/&amp;/i", '&', htmlspecialchars($url, ENT_QUOTES, _CHARSET));
		$message = trim($message) != '' ? $message : _TAKINGBACK;
		// GIJ start

		if (empty($url)) {
			$url = './';
		}

		/**
		 * @var \Aura\Session\Session $session
		 */
		$session = \icms::getInstance()
			->get('session');

		$session
			->getSegment(\icms::class)
			->setFlash('redirect_message', $message);

		$session->commit();

		header("Location: " . preg_replace("/[&]amp;/i", '&', $url));
		exit();
	}
}

if (!function_exists('xoops_getenv')) {
	/**
	 * Gets environment key from the $_SERVER or $_ENV superglobal
	 *
	 * @param string $key The key to get
	 * @return string  $ret  The retrieved key
	 */
	function xoops_getenv($key)
	{
		$ret = '';
		if (array_key_exists($key, $_SERVER) && isset($_SERVER[$key])) {
			$ret = $_SERVER[$key];
			return $ret;
		}
		if (array_key_exists($key, $_ENV) && isset($_ENV[$key])) {
			$ret = $_ENV[$key];
			return $ret;
		}
		return $ret;
	}
}

if (!function_exists('xoops_getcss')) {
	/**
	 * Function to get css file for a certain themeset
	 *
	 * @param string $theme The theme set from the config
	 * @return mixed  The generated theme HTML string or an empty string
	 */
	function xoops_getcss($theme = '')
	{
		if ($theme == '') {
			$theme = $GLOBALS['icmsConfig']['theme_set'];
		}
		$uagent = xoops_getenv('HTTP_USER_AGENT');
		if (stristr($uagent, 'mac')) {
			$str_css = 'styleMAC.css';
		} elseif (preg_match("/MSIE ([0-9]\.[0-9]{1,2})/i", $uagent)) {
			$str_css = 'style.css';
		} else {
			$str_css = 'styleNN.css';
		}
		if (is_dir(ICMS_THEME_PATH . '/' . $theme)) {
			if (file_exists(ICMS_THEME_PATH . '/' . $theme . '/' . $str_css)) {
				return ICMS_THEME_URL . '/' . $theme . '/' . $str_css;
			} elseif (file_exists(ICMS_THEME_PATH . '/' . $theme . '/style.css')) {
				return ICMS_THEME_URL . '/' . $theme . '/style.css';
			}
		}
		if (is_dir(ICMS_THEME_PATH . '/' . $theme . '/css')) {
			if (file_exists(ICMS_THEME_PATH . '/' . $theme . '/css/' . $str_css)) {
				return ICMS_THEME_URL . '/' . $theme . '/css/' . $str_css;
			} elseif (file_exists(ICMS_THEME_PATH . '/' . $theme . '/css/style.css')) {
				return ICMS_THEME_URL . '/' . $theme . '/css/style.css';
			}
		}
		return '';
	}
}

if (!function_exists('xoops_gethandler')) {
	/**
	 * Gets the handler for a class
	 *
	 * @param string $name The name of the handler to get
	 * @param bool $optional Is the handler optional?
	 * @return        object        $inst        The instance of the object that was created
	 * @todo This will not be needed when the autoload is complete
	 */
	function &xoops_gethandler($name, $optional = false)
	{
		// Lookup table: old xoops names => fully qualified refactored name
		$lookup = array(
			//"avatar"			=> "",
			//"block"				=> "icms_block",
			//"blockposition"		=> "icms_block_position",
			//"comment"			=> "",
			"config" => "icms_config",
			//"configcategory"	=> "",
			//"configitem"		=> "",
			//"configoption"		=> "",
			//"group"				=> "",
			//"groupperm"			=> "",
			//"image"				=> "",
			//"imagecategory"		=> "",
			//"imageset"			=> "",
			//"member"			=> "",
			//"module"			=> "",
			//"notification"		=> "",
			//"object"			=> "",
			//"online"			=> "",
			//"page"				=> "",
			//"privmessage"		=> "",
			//"session"			=> "",
			//"tplfile"			=> "",
			//"tplset"			=> "",
			//"icmspersistablecategory"	=> "",
		);
		$lower = strtolower($name);
		return icms::handler(isset($lookup[$lower]) ? $lookup[$lower] : $name);
	}
}

// RMV-NOTIFY
// ################ Notification Helper Functions ##################
if (!function_exists('xoops_notification_deletebymodule')) {
	/**
	 * We want to be able to delete by module, by user, or by item.
	 * How do we specify this??
	 *
	 * @param    int $module_id The ID of the module to unsubscribe from
	 * @return    bool    Did the unsubscribing succeed?
	 * @todo Move to a static class method - Notification
	 */
	function xoops_notification_deletebymodule($module_id)
	{
		$notification_handler = icms::handler('icms_data_notification');
		return $notification_handler->unsubscribeByModule($module_id);
	}
}

if (!function_exists('xoops_notification_deletebyuser')) {
	/**
	 * Deletes / unsubscribes by user ID
	 *
	 * @param    int $user_id The User ID to unsubscribe
	 * @return    bool    Did the unsubscribing succeed?
	 * @todo Move to a static class method - Notification
	 */
	function xoops_notification_deletebyuser($user_id)
	{
		$notification_handler = &icms::handler('icms_data_notification');
		return $notification_handler->unsubscribeByUser($user_id);
	}
}

if (!function_exists('xoops_notification_deletebyitem')) {
	/**
	 * Deletes / unsubscribes by Item ID
	 *
	 * @param    int $module_id The Module ID to unsubscribe
	 * @param    int $category The Item ID to unsubscribe
	 * @param    int $item_id The Item ID to unsubscribe
	 * @return    bool    Did the unsubscribing succeed?
	 * @todo Move to a static class method - Notification
	 */
	function xoops_notification_deletebyitem($module_id, $category, $item_id)
	{
		$notification_handler = &icms::handler('icms_data_notification');
		return $notification_handler->unsubscribeByItem($module_id, $category, $item_id);
	}
}

// ################### Comment helper functions ####################
if (!function_exists('xoops_comment_count')) {
	/**
	 * Count the comments belonging to a certain item in a certain module
	 *
	 * @param    int $module_id The Module ID to count the comments for
	 * @param    int $item_id The Item ID to count the comments for
	 * @return    int    The number of comments
	 * @todo Move to a static class method - Comment
	 */
	function xoops_comment_count($module_id, $item_id = null)
	{
		$comment_handler = icms::handler('icms_data_comment');
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('com_modid', (int)($module_id)));
		if (isset($item_id)) {
			$criteria->add(new icms_db_criteria_Item('com_itemid', (int)($item_id)));
		}
		return $comment_handler->getCount($criteria);
	}
}

if (!function_exists('xoops_comment_delete')) {
	/**
	 * Delete the comments belonging to a certain item in a certain module
	 *
	 * @param    int $module_id The Module ID to delete the comments for
	 * @param    int $item_id The Item ID to delete the comments for
	 * @return    bool    Did the deleting of the comments succeed?
	 * @todo Move to a static class method - Comment
	 */
	function xoops_comment_delete($module_id, $item_id)
	{
		if ((int)($module_id) > 0 && (int)($item_id) > 0) {
			$comment_handler = icms::handler('icms_data_comment');
			$comments = &$comment_handler->getByItemId($module_id, $item_id);
			if (is_array($comments)) {
				$count = count($comments);
				$deleted_num = array();
				for ($i = 0; $i < $count; $i++) {
					if (false != $comment_handler->delete($comments[$i])) {
						// store poster ID and deleted post number into array for later use
						$poster_id = $comments[$i]->com_uid;
						if ($poster_id != 0) {
							$deleted_num[$poster_id] = !isset($deleted_num[$poster_id]) ? 1 : ($deleted_num[$poster_id] + 1);
						}
					}
				}
				$member_handler = icms::handler('icms_member');
				foreach ($deleted_num as $user_id => $post_num) {
					// update user posts
					$com_poster = $member_handler->getUser($user_id);
					if (is_object($com_poster)) {
						$member_handler->updateUserByField($com_poster, 'posts', $com_poster->posts - $post_num);
					}
				}
				return true;
			}
		}
		return false;
	}
}

// ################ Group Permission Helper Functions ##################

if (!function_exists('xoops_groupperm_deletebymoditem')) {
	/**
	 * Deletes group permissions by module and item id
	 *
	 * @param    int $module_id The Module ID to delete the permissions for
	 * @param    string $perm_name The permission name (for the module_id and item_id to delete
	 * @param    int $item_id The Item ID to delete the permissions for
	 * @return    int    Did the deleting of the group permissions succeed?
	 * @todo Move to a static class method - Groupperm
	 */
	function xoops_groupperm_deletebymoditem($module_id, $perm_name, $item_id = null)
	{
		// do not allow system permissions to be deleted
		if ((int)($module_id) <= 1) {
			return false;
		}
		$gperm_handler = icms::handler('icms_member_groupperm');
		return $gperm_handler->deleteByModule($module_id, $perm_name, $item_id);
	}
}

/**
 * Converts text to UTF-8 encoded text
 *
 * @param    string $text The Text to convert
 * @return    string    $text    The converted text
 * @todo Move to a static class method - String
 */
if (!function_exists('xoops_utf8_encode')) {
	function xoops_utf8_encode(&$text)
	{
		if (XOOPS_USE_MULTIBYTES == 1) {
			if (function_exists('mb_convert_encoding')) {
				return mb_convert_encoding($text, 'UTF-8', 'auto');
			}
			return $text;
		}
		return utf8_encode($text);
	}
}

if (!function_exists('xoops_convert_encoding')) {
	/**
	 * Converts text to UTF-8 encoded text
	 * @see xoops_utf8_encode
	 * @todo Move to a static class method - String
	 */
	function xoops_convert_encoding(&$text)
	{
		return xoops_utf8_encode($text);
	}
}

if (!function_exists('icms_getModuleInfo')) {
	/**
	 * Get the icmsModule object of a specified module
	 *
	 * @param string $moduleName dirname of the module
	 * @return object icmsModule object of the specified module
	 * @todo Move to a static class method - Module
	 */
	function &icms_getModuleInfo($moduleName = false)
	{
		static $icmsModules;
		if (isset($icmsModules[$moduleName])) {
			$ret = &$icmsModules[$moduleName];
			return $ret;
		}
		global $icmsModule;
		if (!$moduleName) {
			if (isset($icmsModule) && is_object($icmsModule)) {
				$icmsModules[$icmsModule->dirname] = &$icmsModule;
				return $icmsModules[$icmsModule->dirname];
			}
		}
		if (!isset($icmsModules[$moduleName])) {
			if (isset($icmsModule) && is_object($icmsModule) && $icmsModule->dirname == $moduleName) {
				$icmsModules[$moduleName] = &$icmsModule;
			} else {
				$hModule = icms::handler('icms_module');
				if ($moduleName != 'icms') {
					$icmsModules[$moduleName] = $hModule->getByDirname($moduleName);
				} else {
					$icmsModules[$moduleName] = $hModule->getByDirname('system');
				}
			}
		}
		return $icmsModules[$moduleName];
	}
}

if (!function_exists('icms_getModuleConfig')) {
	/**
	 * Get the config array of a specified module
	 *
	 * @param string $moduleName dirname of the module
	 * @return array of configs
	 * @todo Move to a static class method - Module
	 */
	function &icms_getModuleConfig($moduleName = false)
	{
		static $icmsConfigs;
		if (isset ($icmsConfigs[$moduleName])) {
			$ret = &$icmsConfigs[$moduleName];
			return $ret;
		}
		global $icmsModule, $icmsModuleConfig;
		if (!$moduleName) {
			if (isset($icmsModule) && is_object($icmsModule)) {
				$icmsConfigs[$icmsModule->dirname] = &$icmsModuleConfig;
				return $icmsConfigs[$icmsModule->dirname];
			}
		}
		// if we still did not found the icmsModule, this is because there is none
		if (!$moduleName) {
			$ret = false;
			return $ret;
		}
		if (isset($icmsModule) && is_object($icmsModule) && $icmsModule->dirname == $moduleName) {
			$icmsConfigs[$moduleName] = &$icmsModuleConfig;
		} else {
			$module = &icms_getModuleInfo($moduleName);
			if (!is_object($module)) {
				$ret = false;
				return $ret;
			}
			$hModConfig = icms::handler('icms_config');
			$icmsConfigs[$moduleName] = &$hModConfig->getConfigsByCat(0, $module->mid);
		}
		return $icmsConfigs[$moduleName];
	}
}

if (!function_exists('icms_getConfig')) {
	/**
	 * Get a specific module config value
	 *
	 * @param string $key
	 * @param string $moduleName
	 * @param mixed $default
	 * @return mixed
	 * @todo Move to a static class method - Config
	 */
	function icms_getConfig($key, $moduleName = false, $default = 'default_is_undefined')
	{
		if (!$moduleName) {
			$moduleName = icms_getCurrentModuleName();
		}
		$configs = icms_getModuleConfig($moduleName);
		if (isset($configs[$key])) {
			return $configs[$key];
		} else {
			if ($default === 'default_is_undefined') {
				return null;
			} else {
				return $default;
			}
		}
	}
}

if (!function_exists('icms_getCurrentModuleName')) {
	/**
	 * Get the dirname of the current module
	 *
	 * @return mixed dirname of the current module or false if no module loaded
	 * @todo Move to a static class method - Module
	 */
	function icms_getCurrentModuleName()
	{
		global $icmsModule;
		if (is_object($icmsModule)) {
			return $icmsModule->dirname;
		} else {
			return false;
		}
	}
}

if (!function_exists('icms_userIsAdmin')) {
	/**
	 * Checks if a user is admin of $module
	 *
	 * @param mixed    Module to check or false if no module is passed
	 * @return bool : true if user is admin
	 * @todo Move to a static class method - User
	 */
	function icms_userIsAdmin($module = false)
	{
		static $icms_isAdmin;
		if (!$module) {
			global $icmsModule;
			$module = $icmsModule->dirname;
		}
		if (isset ($icms_isAdmin[$module])) {
			return $icms_isAdmin[$module];
		}
		if (!icms::$user) {
			$icms_isAdmin[$module] = false;
			return $icms_isAdmin[$module];
		}
		$icms_isAdmin[$module] = false;
		$icmsModule = icms_getModuleInfo($module);
		if (!is_object($icmsModule)) {
			return false;
		}
		$module_id = $icmsModule->mid;
		$icms_isAdmin[$module] = icms::$user->isAdmin($module_id);
		return $icms_isAdmin[$module];
	}
}

if (!function_exists('icms_loadLanguageFile')) {
	/**
	 * Load a module language file
	 *
	 * If $module = core, file will be loaded from ICMS_ROOT_PATH/language/
	 *
	 * @param string $module dirname of the module
	 * @param string $file name of the file without ".php"
	 * @param bool $admin is this for a core admin side feature ?
	 *
	 */
	function icms_loadLanguageFile($module, $file, $admin = false)
	{
		global $icmsConfig;
		if ($module == 'core') {
			$languagePath = ICMS_ROOT_PATH . '/language/';
		} else {
			$languagePath = ICMS_MODULES_PATH . '/' . $module . '/language/';
		}
		$extraPath = $admin ? 'admin/' : '';
		$filename = $languagePath . $icmsConfig['language'] . '/' . $extraPath . $file . '.php';
		if (!file_exists($filename)) {
			$filename = $languagePath . 'english/' . $extraPath . $file . '.php';
		}
		if (file_exists($filename)) {
			include_once $filename;
		}
	}
}

if (!function_exists('icms_getfloat')) {
	/**
	 * @param string $str String to get float value from
	 * @param mixed $set Array of settings of False if no settings were passed
	 * @param mixed    Float value or 0 if no match was found in the string
	 * @return float|int
	 * @todo Move to a static class method - String/Data
	 * @author pillepop2003 at yahoo dot de
	 *
	 * Use this snippet to extract any float out of a string. You can choose how a single dot is treated with the (bool) 'single_dot_as_decimal' directive.
	 * This function should be able to cover almost all floats that appear in an european environment.
	 *
	 */
	function icms_getfloat($str, $set = false)
	{
		if (preg_match("/([0-9\.,-]+)/", $str, $match)) {
			// Found number in $str, so set $str that number
			$str = $match[0];
			if (strstr($str, ',')) {
				// A comma exists, that makes it easy, cos we assume it separates the decimal part.
				$str = str_replace('.', '', $str); // Erase thousand seps
				$str = str_replace(',', '.', $str); // Convert , to . for (float) command
				return (float)($str);
			} else {
				// No comma exists, so we have to decide, how a single dot shall be treated
				if (preg_match("/^[0-9\-]*[\.]{1}[0-9-]+$/", $str) == true && $set['single_dot_as_decimal'] == true) {
					return (float)($str);
				} else {
					$str = str_replace('.', '', $str); // Erase thousand seps
					return (float)($str);
				}
			}
		} else {
			return 0;
		}
	}
}

if (!function_exists('icms_currency')) {
	/**
	 * Use this snippet to extract any currency out of a string
	 *
	 * @param string $var String to get currency value from
	 * @param mixed $currencyObj Currency object or false if no object was passed
	 * @return string    $ret The returned value
	 * @todo Move to a static class method - Currency
	 */
	function icms_currency($var, $currencyObj = false)
	{
		$ret = icms_getfloat($var, array('single_dot_as_decimal' => true));
		$ret = round($ret, 2);
		// make sure we have at least .00 in the $var
		$decimal_section_original = strstr($ret, '.');
		$decimal_section = $decimal_section_original;
		if ($decimal_section) {
			if (strlen($decimal_section) == 1) {
				$decimal_section = '.00';
			} elseif (strlen($decimal_section) == 2) {
				$decimal_section = $decimal_section . '0';
			}
			$ret = str_replace($decimal_section_original, $decimal_section, $ret);
		} else {
			$ret = $ret . '.00';
		}
		if ($currencyObj) {
			$ret = $ret . ' ' . $currencyObj->getCode();
		}
		return $ret;
	}
}

if (!function_exists('icms_purifyText')) {
	/**
	 * Strip text from unwanted text (purify)
	 *
	 * @param string $text String to purify
	 * @param mixed $keyword The keyword string or false if none was passed
	 * @return string    $text The purified text
	 * @todo Remove this and use HTML Purifier
	 */
	function icms_purifyText($text, $keyword = false)
	{
		$text = str_replace('&nbsp;', ' ', $text);
		$text = str_replace('<br />', ' ', $text);
		$text = str_replace('<br/>', ' ', $text);
		$text = str_replace('<br', ' ', $text);
		$text = strip_tags($text);
		$text = html_entity_decode($text);
		$text = icms_core_DataFilter::undoHtmlSpecialChars($text);
		$text = str_replace(')', ' ', $text);
		$text = str_replace('(', ' ', $text);
		$text = str_replace(':', ' ', $text);
		$text = str_replace('&euro', ' euro ', $text);
		$text = str_replace('&hellip', '...', $text);
		$text = str_replace('&rsquo', ' ', $text);
		$text = str_replace('!', ' ', $text);
		$text = str_replace('?', ' ', $text);
		$text = str_replace('"', ' ', $text);
		$text = str_replace('-', ' ', $text);
		$text = str_replace('\n', ' ', $text);
		$text = str_replace('&#8213;', ' ', $text);

		if ($keyword) {
			$text = str_replace('.', ' ', $text);
			$text = str_replace(',', ' ', $text);
			$text = str_replace('\'', ' ', $text);
		}
		$text = str_replace(';', ' ', $text);

		return $text;
	}
}

if (!function_exists('icms_html2text')) {
	/**
	 * Converts HTML to text equivalents
	 *
	 * @param string $document The document string to convert
	 * @return string    $text The converted text
	 * @todo Remove this and use the proper data filter and HTML Purifier
	 */
	function icms_html2text($document)
	{
		// PHP Manual:: function preg_replace
		// $document should contain an HTML document.
		// This will remove HTML tags, javascript sections
		// and white space. It will also convert some
		// common HTML entities to their text equivalent.
		// Credits : newbb2
		$search = array("'<script[^>]*?>.*?</script>'si", // Strip out javascript
			"'<img.*?/>'si", // Strip out img tags
			"'<[\/\!]*?[^<>]*?>'si", // Strip out HTML tags
			"'([\r\n])[\s]+'", // Strip out white space
			"'&(quot|#34);'i", // Replace HTML entities
			"'&(amp|#38);'i",
			"'&(lt|#60);'i",
			"'&(gt|#62);'i",
			"'&(nbsp|#160);'i",
			"'&(iexcl|#161);'i",
			"'&(cent|#162);'i",
			"'&(pound|#163);'i",
			"'&(copy|#169);'i",
		);

		$replace = array("",
			"",
			"",
			"\\1",
			"\"",
			"&",
			"<",
			">",
			" ",
			chr(161),
			chr(162),
			chr(163),
			chr(169),
		);

		$text = preg_replace($search, $replace, $document);
		$text = preg_replace_callback("'&#(\d+);'", function ($m) {
			return chr($m[1]);
		}, $text);
		return $text;

	}
}

if (!function_exists('icms_cleanTags')) {
	/**
	 * Function to keeps the code clean while removing unwanted attributes and tags.
	 * This function was got from http://www.php.net/manual/en/function.strip-tags.php#81553
	 *
	 * @var $sSource - string - text to remove the tags
	 * @var $aAllowedTags - array - tags that dont will be striped
	 * @var $aDisabledAttributes - array - attributes not allowed, will be removed from the text
	 *
	 * @return string
	 * @todo Remove this and replace with the proper data filter and HTML Purifier
	 */
	function icms_cleanTags($sSource, $aAllowedTags = array('<h1>', '<b>', '<u>', '<a>', '<ul>', '<li>'), $aDisabledAttributes = array('onabort', 'onblur', 'onchange', 'onclick', 'ondblclick', 'onerror', 'onfocus', 'onkeydown', 'onkeyup', 'onload', 'onmousedown', 'onmousemove', 'onmouseover', 'onmouseup', 'onreset', 'onresize', 'onselect', 'onsubmit', 'onunload'))
	{
		if (empty($aDisabledAttributes)) {
			return strip_tags($sSource, implode('', $aAllowedTags));
		}
		return preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $aDisabledAttributes) . ")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($sSource, implode('', $aAllowedTags)));
	}
}

if (!function_exists('icms_setCookieVar')) {
	/**
	 * Store a cookie
	 *
	 * @param string $name name of the cookie
	 * @param string $value value of the cookie
	 * @param int $time duration of the cookie
	 */
	function icms_setCookieVar($name, $value, $time = 0)
	{
		if ($time == 0) {
			$time = time() + 3600 * 24 * 365;
		}
		setcookie($name, $value, $time, '/');
	}
}

if (!function_exists('icms_getCookieVar')) {
	/**
	 * Get a cookie value
	 *
	 * @param string $name name of the cookie
	 * @param string $default value to return if cookie not found
	 *
	 * @return string value of the cookie or default value
	 */
	function icms_getCookieVar($name, $default = '')
	{
		$name = str_replace('.', '_', $name);
		if ((isset($_COOKIE[$name])) && ($_COOKIE[$name] > '')) {
			return $_COOKIE[$name];
		} else {
			return $default;
		}
	}
}

if (!function_exists('icms_get_page_before_form')) {
	/**
	 * Get URL of the page before the form to be able to redirect their after the form has been posted
	 *
	 * @return string url before form
	 */
	function icms_get_page_before_form()
	{
		return isset($_POST['icms_page_before_form'])
			? icms_core_DataFilter::checkVar($_POST['icms_page_before_form'], 'url')
			: \icms::$urls['previouspage'];
	}
}

if (!function_exists('icms_sanitizeCustomtags_callback')) {
	/**
	 * Get URL of the page before the form to be able to redirect their after the form has been posted
	 *
	 * @param    array $matches Array of matches to sanitize
	 * @return mixed The sanitized tag or empty string
	 * @todo Move to a static class method - Customtag
	 */
	function icms_sanitizeCustomtags_callback($matches)
	{
		$icms_customtag_handler = icms_getModuleHandler("customtag", "system");
		$icms_customTagsObj = $icms_customtag_handler->getCustomtagsByName();
		if (!isset($icms_customTagsObj[$matches[1]])) {
			return "";
		}
		return $icms_customTagsObj[$matches[1]]->renderWithPhp();
	}
}

if (!function_exists('icms_sanitizeAdsenses_callback')) {
	/**
	 * Get URL of the page before the form to be able to redirect their after the form has been posted
	 *
	 * @param    array $matches Array of matches to sanitize
	 * @return mixed The sanitized tag or empty string
	 * @todo Move to a static class method - Adsense
	 */
	function icms_sanitizeAdsenses_callback($matches)
	{
		$icms_adsense_handler = icms_getModuleHandler("adsense", "system");
		$icms_adsensesObj = $icms_adsense_handler->getAdsensesByTag();
		if (!isset($icms_adsensesObj[$matches[1]])) {
			return '';
		}
		return $icms_adsensesObj[$matches[1]]->render();
	}
}

if (!function_exists('icms_getTablesArray')) {
	/**
	 * Get an array of the table used in a module
	 *
	 * @param string $moduleName name of the module
	 * @param $items array of items managed by the module
	 * @return array of tables used in the module
	 * @todo Move to a static class method - Module
	 */
	function icms_getTablesArray($moduleName, $items)
	{
		$ret = array();
		if (is_array($items)) {
			foreach ($items as $item) {
				//$table = icms::handler('mod_' . $moduleName . '_' . ucfirst($item))->table;
				$moduleItem = icms_getModuleHandler($item, $moduleName, null, true);
				if (!is_object($moduleItem)) {
					$ret[] = $moduleName . '_' . $item;
				} else {
					$ret[] = $moduleItem->table;
				}
			}
		}
		return $ret;
	}
}

if (!function_exists('showNav')) {
	/**
	 * Function to create a navigation menu in content pages.
	 * This function was based on the function that do the same in mastop publish module
	 *
	 * @param integer $id
	 * @param string $separador
	 * @param string $style
	 * @return string
	 */
	function showNav($id = null, $separador = '/', $style = "style='font-weight:bold'")
	{
		$url = ICMS_URL . '/content.php';
		if ($id == false) {
			return false;
		} else {
			if ($id > 0) {
				/**
				 * @todo this handler doesn't even exist since 1.2. check if the function is still required
				 */
				$content_handler = &xoops_gethandler('content');
				$cont = $content_handler->get($id);
				if ($cont->content_id > 0) {
					$seo = $content_handler->makeLink($cont);
					$ret = "<a href='" . $url . "?page=" . $seo . "'>" . $cont->content_title . "</a>";
					if ($cont->content_supid == 0) {
						return "<a href='" . ICMS_URL . "'>" . _CT_NAV . "</a> $separador " . $ret;
					} elseif ($cont->content_supid > 0) {
						$ret = showNav($cont->content_supid, $separador) . " $separador " . $ret;
					}
				}
			} else {
				return false;
			}
		}
		return $ret;
	}
}

if (!function_exists('StopXSS')) {
	/**
	 * Searches text for unwanted tags and removes them
	 *
	 * @param string $text String to purify
	 * @return string    $text The purified text
	 * @todo Remove and replace with the proper data filter and HTML Purifier
	 */
	function StopXSS($text)
	{
		if (!is_array($text)) {
			$text = preg_replace("/\(\)/si", "", $text);
			$text = strip_tags($text);
			$text = str_replace(array("\"", ">", "<", "\\"), "", $text);
		} else {
			foreach ($text as $k => $t) {
				if (is_array($t)) {
					StopXSS($t);
				} else {
					$t = preg_replace("/\(\)/si", "", $t);
					$t = strip_tags($t);
					$t = str_replace(array("\"", ">", "<", "\\"), "", $t);
					$text[$k] = $t;
				}
			}
		}
		return $text;
	}
}

if (!function_exists('icms_sanitizeContentCss')) {
	/**
	 * Purifies the CSS that is put in the content (pages) system
	 *
	 * @param string $text String to purify
	 * @return string    $text The purified text
	 * @todo Remove and replace with the proper data filter and HTML Purifier
	 */
	function icms_sanitizeContentCss($text)
	{
		if (preg_match_all('/(.*?)\{(.*?)\}/ie', $text, $css)) {
			$css = $css[0];
			$perm = $not_perm = array();
			foreach ($css as $k => $v) {
				if (!preg_match('/^\#impress_content(.*?)/ie', $v)) {
					$css[$k] = '#impress_content ' . icms_cleanTags(trim($v), array()) . "\r\n";
				} else {
					$css[$k] = icms_cleanTags(trim($v), array()) . "\r\n";
				}
			}
			$text = implode($css);
		}
		return $text;
	}
}

if (!function_exists('icms_wordwrap')) {
	/**
	 * Function to wordwrap given text.
	 *
	 * @param string $str The text to be wrapped.
	 * @param string $width The column width - text will be wrapped when longer than $width.
	 * @param string $break The line is broken using the optional break parameter.
	 *            can be '/n' or '<br />'
	 * @param string $cut If cut is set to TRUE, the string is always wrapped at the specified width.
	 *            So if you have a word that is larger than the given width, it is broken apart..
	 * @return string
	 * @todo Move to a static class method - String
	 */
	function icms_wordwrap($str, $width, $break = '/n', $cut = false)
	{
		if (strtolower(_CHARSET) !== 'utf-8') {
			$str = wordwrap($str, $width, $break, $cut);
			return $str;
		} else {
			$splitedArray = array();
			$lines = explode("\n", $str);
			foreach ($lines as $line) {
				$lineLength = strlen($line);
				if ($lineLength > $width) {
					$words = explode("\040", $line);
					$lineByWords = '';
					$addNewLine = true;
					foreach ($words as $word) {
						$lineByWordsLength = strlen($lineByWords);
						$tmpLine = $lineByWords . ((strlen($lineByWords) !== 0) ? ' ' : '') . $word;
						$tmplineByWordsLength = strlen($tmpLine);
						if ($tmplineByWordsLength > $width && $lineByWordsLength <= $width && $lineByWordsLength !== 0) {
							$splitedArray[] = $lineByWords;
							$lineByWords = '';
						}
						$newLineByWords = $lineByWords . ((strlen($lineByWords) !== 0) ? ' ' : '') . $word;
						$newLineByWordsLength = strlen($newLineByWords);
						if ($cut && $newLineByWordsLength > $width) {
							for ($i = 0; $i < $newLineByWordsLength; $i = $i + $width) {
								$splitedArray[] = mb_substr($newLineByWords, $i, $width);
							}
							$addNewLine = false;
						} else {
							$lineByWords = $newLineByWords;
						}
					}
					if ($addNewLine) {
						$splitedArray[] = $lineByWords;
					}
				} else {
					$splitedArray[] = $line;
				}
			}
			return implode($break, $splitedArray);
		}
	}
}

if (!function_exists('getDbValue')) {
	/**
	 * Function to get a query from DB
	 *
	 * @param object $db Reference to the database object
	 * @param string $table The table to get the value from
	 * @param string $field The table to get the value from
	 * @param string $condition The where condition (where clause) to use
	 * @return    mixed
	 * @todo Move to a static class method - database
	 */
	function getDbValue(&$db, $table, $field, $condition = '')
	{
		$table = $db->prefix($table);
		$sql = "SELECT `$field` FROM `$table`";
		if ($condition) {
			$sql .= " WHERE $condition";
		}
		$result = $db->query($sql);
		if ($result) {
			$row = $db->fetchRow($result);
			if ($row) {
				return $row[0];
			}
		}
		return false;
	}
}

if (!function_exists('icms_escapeValue')) {
	/**
	 * Function to escape $value makes safe for DB Queries.
	 *
	 * @param string $quotes - true/false - determines whether to add quotes to the value or not.
	 * @param string $value - $variable that is being escaped for query.
	 * @return string
	 * @todo Move to a static class method - Database or Filter
	 * @todo    get_magic_quotes_gpc is removed in PHP 5.4
	 */
	function icms_escapeValue($value, $quotes = true)
	{
		if (is_string($value)) {
			if (get_magic_quotes_gpc) {
				$value = stripslashes($value);
			}
			$value = icms::$xoopsDB->escape($value);
			if ($quotes) {
				$value = '"' . $value . '"';
			}
		} elseif ($value === null) {
			$value = 'NULL';
		} elseif (is_bool($value)) {
			$value = $value ? 1 : 0;
		} elseif (is_numeric($value)) {
			$value = (int)($value);
		} elseif (is_int($value)) {
			$value = (int)($value);
		} elseif (!is_numeric($value)) {
			$value = icms::$xoopsDB->escape($value);
			if ($quotes) {
				$value = '"' . $value . '"';
			}
		}
		return $value;
	}
}

if (!function_exists('icms_conv_nr2local')) {
	/**
	 * Get a number value in other languages
	 *
	 * @param int $string Content to be transported into another language
	 * @return string inout with replaced numeric values
	 *
	 * Example: In Persian we use, (۱, ۲, ۳, ۴, ۵, ۶, ۷, ۸, ۹, ۰) instead of (1, 2, 3, 4, 5, 6, 7, 8, 9, 0)
	 * Now in a module and we are showing amount of reads, the output in Persian must be ۱۲ (which represents 12).
	 * To developers, please use this function when you are having a numeric output, as this is counted as a string in php so you should use %s.
	 * Like:
	 * $views = sprintf ( 'Viewed: %s Times.', icms_conv_nr2local($string) );
	 * @todo Move to a static class method - String or Localization
	 */
	function icms_conv_nr2local($string)
	{
		$basecheck = defined('_USE_LOCAL_NUM') && _USE_LOCAL_NUM;
		if ($basecheck) {
			$string = str_replace(
				array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
				array(_LCL_NUM0, _LCL_NUM1, _LCL_NUM2, _LCL_NUM3, _LCL_NUM4, _LCL_NUM5, _LCL_NUM6, _LCL_NUM7, _LCL_NUM8, _LCL_NUM9), $string);
		}
		return $string;
	}
}

if (!function_exists('icms_conv_local2nr')) {
	/**
	 * Get a number value in other languages and transform it to English
	 *
	 * This function is exactly the opposite of icms_conv_nr2local();
	 * Please view the notes there for more information.
	 * @todo Move to a static class method - String or Localization
	 */
	function icms_conv_local2nr($string)
	{
		$basecheck = defined('_USE_LOCAL_NUM') && _USE_LOCAL_NUM;
		if ($basecheck) {
			$string = str_replace(
				array(_LCL_NUM0, _LCL_NUM1, _LCL_NUM2, _LCL_NUM3, _LCL_NUM4, _LCL_NUM5, _LCL_NUM6, _LCL_NUM7, _LCL_NUM8, _LCL_NUM9),
				array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
				$string);
		}
		return $string;
	}
}

if (!function_exists('Icms_getMonthNameById')) {
	/**
	 * Get month name by its ID
	 *
	 * @param int $month_id ID of the month
	 * @return string month name
	 * @todo Move to a static class method - Date or Calendar
	 */
	function Icms_getMonthNameById($month_id)
	{
		global $icmsConfig;
		icms_loadLanguageFile('core', 'calendar');
		$month_id = icms_conv_local2nr($month_id);
		if ($icmsConfig['use_ext_date'] == true && defined('_CALENDAR_TYPE') && _CALENDAR_TYPE == "jalali") {
			switch ($month_id) {
				case 1:
					return _CAL_FARVARDIN;
					break;
				case 2:
					return _CAL_ORDIBEHESHT;
					break;
				case 3:
					return _CAL_KHORDAD;
					break;
				case 4:
					return _CAL_TIR;
					break;
				case 5:
					return _CAL_MORDAD;
					break;
				case 6:
					return _CAL_SHAHRIVAR;
					break;
				case 7:
					return _CAL_MEHR;
					break;
				case 8:
					return _CAL_ABAN;
					break;
				case 9:
					return _CAL_AZAR;
					break;
				case 10:
					return _CAL_DEY;
					break;
				case 11:
					return _CAL_BAHMAN;
					break;
				case 12:
					return _CAL_ESFAND;
					break;
			}
		} else {
			switch ($month_id) {
				case 1:
					return _CAL_JANUARY;
					break;
				case 2:
					return _CAL_FEBRUARY;
					break;
				case 3:
					return _CAL_MARCH;
					break;
				case 4:
					return _CAL_APRIL;
					break;
				case 5:
					return _CAL_MAY;
					break;
				case 6:
					return _CAL_JUNE;
					break;
				case 7:
					return _CAL_JULY;
					break;
				case 8:
					return _CAL_AUGUST;
					break;
				case 9:
					return _CAL_SEPTEMBER;
					break;
				case 10:
					return _CAL_OCTOBER;
					break;
				case 11:
					return _CAL_NOVEMBER;
					break;
				case 12:
					return _CAL_DECEMBER;
					break;
			}
		}
	}
}

if (!function_exists('ext_date')) {
	/**
	 * This function is to convert date() function outputs into local values
	 *
	 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
	 * @since        1.2
	 * @author       Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
	 * @param int $type The type of date string?
	 * @param string $maket The date string type
	 * @return mixed The converted date string
	 * @todo Move to a static class method - Date or Calendar
	 */
	function ext_date($time)
	{
		icms_loadLanguageFile('core', 'calendar');
		/*		$string = str_replace(
            array(_CAL_AM, _CAL_PM, _CAL_AM_LONG, _CAL_PM_LONG, _CAL_SAT, _CAL_SUN, _CAL_MON, _CAL_TUE, _CAL_WED, _CAL_THU, _CAL_FRI, _CAL_SATURDAY, _CAL_SUNDAY, _CAL_MONDAY, _CAL_TUESDAY, _CAL_WEDNESDAY, _CAL_THURSDAY, _CAL_FRIDAY),
                array('Am', 'Pm', 'AM', 'PM', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                $string);
        */
		$trans = array('am' => _CAL_AM,
			'pm' => _CAL_PM,
			'AM' => _CAL_AM_CAPS,
			'PM' => _CAL_PM_CAPS,
			'Monday' => _CAL_MONDAY,
			'Tuesday' => _CAL_TUESDAY,
			'Wednesday' => _CAL_WEDNESDAY,
			'Thursday' => _CAL_THURSDAY,
			'Friday' => _CAL_FRIDAY,
			'Saturday' => _CAL_SATURDAY,
			'Sunday' => _CAL_SUNDAY,
			'Mon' => _CAL_MON,
			'Tue' => _CAL_TUE,
			'Wed' => _CAL_WED,
			'Thu' => _CAL_THU,
			'Fri' => _CAL_FRI,
			'Sat' => _CAL_SAT,
			'Sun' => _CAL_SUN,
			'January' => _CAL_JANUARY,
			'February' => _CAL_FEBRUARY,
			'March' => _CAL_MARCH,
			'April' => _CAL_APRIL,
			'May' => _CAL_MAY,
			'June' => _CAL_JUNE,
			'July' => _CAL_JULY,
			'August' => _CAL_AUGUST,
			'September' => _CAL_SEPTEMBER,
			'October' => _CAL_OCTOBER,
			'November' => _CAL_NOVEMBER,
			'December' => _CAL_DECEMBER,
			'Jan' => _CAL_JAN,
			'Feb' => _CAL_FEB,
			'Mar' => _CAL_MAR,
			'Apr' => _CAL_APR,
			'May' => _CAL_MAY,
			'Jun' => _CAL_JUN,
			'Jul' => _CAL_JUL,
			'Aug' => _CAL_AUG,
			'Sep' => _CAL_SEP,
			'Oct' => _CAL_OCT,
			'Nov' => _CAL_NOV,
			'Dec' => _CAL_DEC);

		$timestamp = strtr($time, $trans);
		return $timestamp;
	}
}

if (!function_exists('formatTimestamp')) {
	/**
	 * Function to display formatted times in user timezone
	 *
	 * @param string $time String with time
	 * @param string $format The time format based on PHP function format parameters
	 * @param string $timeoffset The time offset string
	 * @return string  $usertimestamp  The generated user timestamp
	 * @todo Move to a static class method - Date or Calendar
	 */
	function formatTimestamp($time, $format = "l", $timeoffset = null)
	{
		global $icmsConfig;

		$format_copy = $format;
		$format = strtolower($format);

		if ($format == "rss" || $format == "r") {
			$TIME_ZONE = "";
			if (!empty($GLOBALS['icmsConfig']['server_TZ'])) {
				$server_TZ = abs((int)($GLOBALS['icmsConfig']['server_TZ'] * 3600.0));
				$prefix = ($GLOBALS['icmsConfig']['server_TZ'] < 0) ? " -" : " +";
				$TIME_ZONE = $prefix . date("Hi", $server_TZ);
			}
			$date = gmdate("D, d M Y H:i:s", (int)($time)) . $TIME_ZONE;
			return $date;
		}

		if (($format == "elapse" || $format == "e") && $time < time()) {
			$elapse = time() - $time;
			if ($days = floor($elapse / (24 * 3600))) {
				$num = $days > 1 ? sprintf(_DAYS, $days) : _DAY;
			} elseif ($hours = floor(($elapse % (24 * 3600)) / 3600)) {
				$num = $hours > 1 ? sprintf(_HOURS, $hours) : _HOUR;
			} elseif ($minutes = floor(($elapse % 3600) / 60)) {
				$num = $minutes > 1 ? sprintf(_MINUTES, $minutes) : _MINUTE;
			} else {
				$seconds = $elapse % 60;
				$num = $seconds > 1 ? sprintf(_SECONDS, $seconds) : _SECOND;
			}
			$ret = sprintf(_ELAPSE, icms_conv_nr2local($num));
			return $ret;
		}

		// disable user timezone calculation and use default timezone,
		// for cache consideration
		if ($timeoffset === null) {
			$timeoffset = ($icmsConfig['default_TZ'] == '') ? '0.0' : $icmsConfig['default_TZ'];
		}

		$usertimestamp = xoops_getUserTimestamp($time, $timeoffset);

		switch ($format) {
			case 'daynumber':
				$datestring = 'd';
				break;
			case 'D':
				$datestring = 'D';
				break;
			case 'F':
				$datestring = 'F';
				break;
			case 'hs':
				$datestring = 'h';
				break;
			case 'H':
				$datestring = 'H';
				break;
			case 'gg':
				$datestring = 'g';
				break;
			case 'G':
				$datestring = 'G';
				break;
			case 'i':
				$datestring = 'i';
				break;
			case 'j':
				$datestring = 'j';
				break;
			case 'l':
				$datestring = _DATESTRING;
				break;
			case 'm':
				$datestring = _MEDIUMDATESTRING;
				break;
			case 'monthnr':
				$datestring = 'm';
				break;
			case 'mysql':
				$datestring = 'Y-m-d H:i:s';
				break;
			case 'month':
				$datestring = 'M';
				break;
			case 'n':
				$datestring = 'n';
				break;
			case 's':
				$datestring = _SHORTDATESTRING;
				break;
			case 'seconds':
				$datestring = 's';
				break;
			case 'suffix':
				$datestring = 'S';
				break;
			case 't':
				$datestring = 't';
				break;
			case 'w':
				$datestring = 'w';
				break;
			case 'shortyear':
				$datestring = 'y';
				break;
			case 'Y':
				$datestring = 'Y';
				break;
			case 'c':
			case 'custom':
				static $current_timestamp, $today_timestamp, $monthy_timestamp;
				if (!isset($current_timestamp)) {
					$current_timestamp = xoops_getUserTimestamp(time(), $timeoffset);
				}
				if (!isset($today_timestamp)) {
					$today_timestamp = mktime(0, 0, 0, date("m", $current_timestamp), date("d", $current_timestamp), date("Y", $current_timestamp));
				}

				if (abs($elapse_today = $usertimestamp - $today_timestamp) < 24 * 60 * 60) {
					$datestring = ($elapse_today > 0) ? _TODAY : _YESTERDAY;
				} else {
					if (!isset($monthy_timestamp)) {
						$monthy_timestamp[0] = mktime(0, 0, 0, 0, 0, date("Y", $current_timestamp));
						$monthy_timestamp[1] = mktime(0, 0, 0, 0, 0, date("Y", $current_timestamp) + 1);
					}
					if ($usertimestamp >= $monthy_timestamp[0] && $usertimestamp < $monthy_timestamp[1]) {
						$datestring = _MONTHDAY;
					} else {
						$datestring = _YEARMONTHDAY;
					}
				}
				break;

			default:
				if ($format != '') {
					$datestring = $format_copy;
				} else {
					$datestring = _DATESTRING;
				}
				break;
		}

		$basecheck = $icmsConfig['use_ext_date'] == true && defined('_CALENDAR_TYPE') && $format != 'mysql';
		if ($basecheck && file_exists(ICMS_ROOT_PATH . '/language/' . $icmsConfig['language'] . '/local.date.php')) {
			include_once ICMS_ROOT_PATH . '/language/' . $icmsConfig['language'] . '/local.date.php';
			return ucfirst(local_date($datestring, $usertimestamp));
		} elseif ($basecheck && _CALENDAR_TYPE != "jalali" && $icmsConfig['language'] != 'english') {
			return ucfirst(icms_conv_nr2local(ext_date(date($datestring, $usertimestamp))));
		} elseif ($basecheck && _CALENDAR_TYPE == "jalali") {
			include_once 'jalali.php';
			return ucfirst(icms_conv_nr2local(jdate($datestring, $usertimestamp)));
		} else {
			return ucfirst(date($datestring, $usertimestamp));
		}
	}
}

if (!function_exists('icms_getModuleHandler')) {
	/**
	 * Gets module handler instance
	 *
	 * @param string $name The name of the module
	 * @param string $module_dir The module directory where to get the module class
	 * @param string $module_basename The basename of the module
	 * @param bool $optional Is the module optional? Is it bad when the module cannot be loaded?
	 * @return object The module handler instance
	 */
	function &icms_getModuleHandler($name = null, $module_dir = null, $module_basename = null, $optional = false)
	{
		// if $module_dir is not specified
		if (!isset($module_dir)) {
			//if a module is loaded
			if (isset($GLOBALS['icmsModule']) && is_object($GLOBALS['icmsModule'])) {
				$module_dir = $GLOBALS['icmsModule']->dirname;
			} else {
				trigger_error(_CORE_NOMODULE, E_USER_ERROR);
			}
		} else {
			$module_dir = trim($module_dir);
		}

		$realname = sprintf('ImpressCMS/Modules/%s/%s', ucfirst($module_basename ?? $module_dir), $name);
		$container = icms::getInstance();
		if (!$container->has($realname)) {
			$module_basename = isset($module_basename) ? trim($module_basename) : $module_dir;
			$instance = false;
			$name = (!isset($name)) ? $module_dir : trim($name);
			$class = 'mod_' . $module_dir . '_' . ucfirst($name) . 'Handler';
			if (class_exists($class)) {
				$db = $container->get('xoopsDB');
				$instance = new $class($db);
			} elseif (file_exists($hnd_file = ICMS_MODULES_PATH . "/{$module_dir}/class/" . ucfirst($name) . 'Handler.php')) {
				include_once $hnd_file;
				include_once ICMS_MODULES_PATH . "/{$module_dir}/class/" . ucfirst($name) . '.php';
				$class = ucfirst(strtolower($module_basename)) . ucfirst($name) . 'Handler';
				if (class_exists($class)) {
					$db = $container->get('xoopsDB');
					$instance = new $class($db);
				}
			} else {
				$hnd_file = ICMS_MODULES_PATH . "/{$module_dir}/class/{$name}.php";
				if (file_exists($hnd_file)) {
					include_once $hnd_file;
				}
				$class = ucfirst(strtolower($module_basename)) . ucfirst($name) . 'Handler';
				if (class_exists($class)) {
					$db = $container->get('xoopsDB');
					$instance = new $class($db);
				}
			}
			$container->add($class, $instance);
			$container->add($realname, $instance);
		} else {
			$instance = $container->get($realname);
		}

		if (!$instance && !$optional) {
			trigger_error(sprintf(_CORE_MODULEHANDLER_NOTAVAILABLE, $module_dir, $name), E_USER_ERROR);
		}
		return $instance;
	}
}

if (!function_exists('icms_getPreviousPage')) {
	/**
	 * Get URL of previous page
	 *
	 * @param string $default default page if previous page is not found
	 * @return string previous page URL
	 * @todo Move to a static class method - HTTP or URI
	 */
	function icms_getPreviousPage($default = false)
	{
		if (isset(\icms::$urls['previouspage'])) {
			return \icms::$urls['previouspage'];
		} elseif ($default) {
			return $default;
		} else {
			return ICMS_URL;
		}
	}
}

if (!function_exists('icms_getModuleAdminLink')) {
	/**
	 * Get module admin link
	 *
	 * @param string $moduleName dirname of the moodule
	 * @return string URL of the admin side of the module
	 * @todo Move to a static class method - HTTP or URI
	 */
	function icms_getModuleAdminLink($moduleName = false)
	{
		global $icmsModule;
		if (!$moduleName && (isset ($icmsModule) && is_object($icmsModule))) {
			$moduleName = $icmsModule->dirname;
		}
		$ret = '';
		if ($moduleName) {
			$ret = "<a href='" . ICMS_URL . "/modules/$moduleName/admin/index.php'>" . _CO_ICMS_ADMIN_PAGE . "</a>";
		}
		return $ret;
	}
}

if (!function_exists('icms_getImageSize')) {
	/**
	 * Finds the width and height of an image (can also be a flash file)
	 *
	 * @credit phppp
	 *
	 * @var string $url path of the image file
	 * @var string $width reference to the width
	 * @var string $height reference to the height
	 * @return bool false if impossible to find dimension
	 * @todo Move to a static class method - Image
	 */
	function icms_getImageSize($url, & $width, & $height)
	{
		if (empty ($width) || empty ($height)) {
			if (!$dimension = @ getimagesize($url)) {
				return false;
			}
			if (!empty ($width)) {
				$height = $dimension[1] * $width / $dimension[0];
			} elseif (!empty ($height)) {
				$width = $dimension[0] * $height / $dimension[1];
			} else {
				list ($width, $height) = array(
					$dimension[0],
					$dimension[1]
				);
			}
			return true;
		} else {
			return true;
		}
	}
}

if (!function_exists('icms_imageResize')) {
	/**
	 * Resizes an image to maxheight and maxwidth
	 *
	 * @param string $src The image file to resize
	 * @param string $maxWidth The maximum width to resize the image to
	 * @param string $maxHeight The maximum height to resize the image to
	 * @return array The resized image array
	 * @todo Move to a static class method - Image
	 */
	function icms_imageResize($src, $maxWidth, $maxHeight)
	{
		$width = '';
		$height = '';
		$type = '';
		$attr = '';
		if (file_exists($src)) {
			list ($width, $height, $type, $attr) = getimagesize($src);
			if ($width > $maxWidth) {
				$originalWidth = $width;
				$width = $maxWidth;
				$height = $width * $height / $originalWidth;
			}
			if ($height > $maxHeight) {
				$originalHeight = $height;
				$height = $maxHeight;
				$width = $height * $width / $originalHeight;
			}
			$attr = " width='$width' height='$height'";
		}
		return array(
			$width,
			$height,
			$type,
			$attr
		);
	}
}

if (!function_exists('icms_getModuleName')) {
	/**
	 * Generates the module name with either a link or not
	 *
	 * @param bool $withLink Generate the modulename with in an anchor link?
	 * @param bool $forBreadCrumb Is the module name for the breadcrumbs?
	 * @param mixed $moduleName The passed modulename or false if no modulename was passed
	 * @return array
	 * @todo Move to a static class method - Module
	 */
	function icms_getModuleName($withLink = true, $forBreadCrumb = false, $moduleName = false)
	{
		if (!$moduleName) {
			$moduleName = icms::$module->dirname;
		}
		icms::$module = icms_getModuleInfo($moduleName);
		$icmsModuleConfig = icms_getModuleConfig($moduleName);
		if (!isset (icms::$module)) {
			return '';
		}

		if (!$withLink) {
			return icms::$module->name;
		} else {
			$seoMode = icms_getModuleModeSEO($moduleName);
			if ($seoMode == 'rewrite') {
				$seoModuleName = icms_getModuleNameForSEO($moduleName);
				$ret = ICMS_URL . '/' . $seoModuleName . '/';
			} elseif ($seoMode == 'pathinfo') {
				$ret = ICMS_MODULES_URL . '/' . $moduleName . '/seo.php/' . $seoModuleName . '/';
			} else {
				$ret = ICMS_MODULES_URL . '/' . $moduleName . '/';
			}
			return '<a href="' . $ret . '">' . icms::$module->name . '</a>';
		}
	}
}

if (!function_exists('icms_convert_size')) {
	/**
	 * Converts size to human readable text
	 *
	 * @param int $size The size to convert
	 * @return string The converted size
	 * @todo Move to a static class method - String
	 */
	function icms_convert_size($size)
	{
		if ($size >= 1073741824) {
			$ret = round(((($size / 1024) / 1024) / 1024), 1) . ' ' . _CORE_GIGABYTES_SHORTEN;
		} elseif ($size >= 1048576 && $size < 1073741824) {
			$ret = round((($size / 1024) / 1024), 1) . ' ' . _CORE_MEGABYTES_SHORTEN;
		} elseif ($size >= 1024 && $size < 1048576) {
			$ret = round(($size / 1024), 1) . ' ' . _CORE_KILOBYTES_SHORTEN;
		} else {
			$ret = ($size) . ' ' . _CORE_BYTES;
		}
		return icms_conv_nr2local($ret);
	}
}

if (!function_exists('icms_random_str')) {
	/**
	 * Generates a random string
	 *
	 * @param int $numchar How many characters should the string consist of?
	 * @return string The generated random string
	 * @todo Move to a static class method -
	 */
	function icms_random_str($numchar)
	{
		$letras = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,x,w,y,z,1,2,3,4,5,6,7,8,9,0";
		$array = explode(",", $letras);
		shuffle($array);
		$senha = implode($array, "");
		return substr($senha, 0, $numchar);
	}
}

if (!function_exists('icms_adminMenu')) {
	/**
	 * Generates a random string
	 *
	 * @param int $currentoption What current admin option are we in?
	 * @param string $breadcrumb The breadcrumb if it is passed, otherwise empty string
	 * @todo Move to a static class method - module
	 */
	function icms_adminMenu($currentoption = 0, $breadcrumb = '')
	{
		global $icmsModule;
		$icmsModule->displayAdminMenu($currentoption, $icmsModule->name . ' | ' . $breadcrumb);
	}
}

if (!function_exists('icms_loadCommonLanguageFile')) {
	/**
	 * Loads common language file
	 */
	function icms_loadCommonLanguageFile()
	{
		icms_loadLanguageFile('system', 'common');
	}
}

if (!function_exists('icms_getCurrentPage')) {
	/**
	 * Gets current page
	 *
	 * @return string The URL of the current page
	 * @todo Move to a static class method - HTTP or URI
	 * @deprecated Use \icms::$urls['full']. Since 2.0
	 */
	function icms_getCurrentPage()
	{
		trigger_error('Use \icms::$urls[\'full\']', E_USER_DEPRECATED);

		return \icms::$urls['full'];
	}
}

if (!function_exists('icms_getModuleNameForSEO')) {
	/**
	 * Gets module name in SEO format
	 *
	 * @param mixed $moduleName Modulename if it is passed, otherwise false
	 * @return string The modulename in SEO format
	 * @todo Move to a static class method - Module
	 */
	function icms_getModuleNameForSEO($moduleName = false)
	{
		$icmsModule = &icms_getModuleInfo($moduleName);
		$icmsModuleConfig = &icms_getModuleConfig($moduleName);
		if (isset ($icmsModuleConfig['seo_module_name'])) {
			return $icmsModuleConfig['seo_module_name'];
		}
		$ret = icms_getModuleName(false, false, $moduleName);
		return (strtolower($ret));
	}
}

if (!function_exists('icms_getModuleModeSEO')) {
	/**
	 * Determines if the module is in SEO mode
	 *
	 * @param mixed $moduleName Modulename if it is passed, otherwise false
	 * @return bool Is the module in SEO format?
	 * @todo Move to a static class method - Module
	 */
	function icms_getModuleModeSEO($moduleName = false)
	{
		$icmsModule = &icms_getModuleInfo($moduleName);
		$icmsModuleConfig = &icms_getModuleConfig($moduleName);
		return isset ($icmsModuleConfig['seo_mode']) ? $icmsModuleConfig['seo_mode'] : false;
	}
}

if (!function_exists('icms_getModuleIncludeIdSEO')) {
	/**
	 * Gets the include ID if the module is in SEO format (otherwise nothing)
	 *
	 * @param mixed $moduleName Modulename if it is passed, otherwise false
	 * @return mixed The module include ID otherwise nothing
	 * @todo Move to a static class method - Module
	 */
	function icms_getModuleIncludeIdSEO($moduleName = false)
	{
		$icmsModule = &icms_getModuleInfo($moduleName);
		$icmsModuleConfig = &icms_getModuleConfig($moduleName);
		return !empty ($icmsModuleConfig['seo_inc_id']);
	}
}

if (!function_exists('icms_getenv')) {
	/**
	 * Gets environment key from the $_SERVER or $_ENV superglobal
	 *
	 * @param string $key The key to get
	 * @return string  $ret  The retrieved key
	 * @todo Move to a static class method - HTTP
	 */
	function icms_getenv($key)
	{
		$ret = '';
		$ret = isset ($_SERVER[$key]) ? $_SERVER[$key] : (isset ($_ENV[$key]) ? $_ENV[$key] : '');
		return $ret;
	}
}

if (!function_exists('icms_get_module_status')) {
	/**
	 * Gets the status of a module to see if it's active or not.
	 *
	 * @param string $module_name The module's name to get
	 * @param bool True if module exists and is active, otherwise false
	 * @todo Move to a static class method - Module
	 */
	function icms_get_module_status($module_name)
	{
		$module_handler = icms::handler('icms_module');
		$this_module = $module_handler->getByDirname($module_name);
		if ($this_module && $this_module->isactive) {
			return true;
		}
		return false;
	}
}

if (!function_exists('one_wordwrap')) {
	/**
	 * Wrap a long term or word
	 *
	 * @author    <admin@jcink.com>
	 * @param    string $string The string
	 * @param    string $width The length
	 * @return   bool    Returns a long term, in several small parts with the length of $width
	 * @todo Move to a static class method - String
	 */
	function one_wordwrap($string, $width = false)
	{
		$width = $width ? $width : '15';
		$new_string = '';
		$s = explode(" ", $string);
		foreach ($s as $k => $v) {
			$cnt = strlen($v);
			if ($cnt > $width) {
				$v = icms_wordwrap($v, $width, ' ', true);
			}
			$new_string .= "$v ";
		}
		return $new_string;
	}
}

if (!function_exists('icms_PasswordMeter')) {
	/**
	 * Adds required jQuery files to header for Password meter.
	 *
	 * @param    string $password_fieldclass element id for the password field
	 * @param    string $username_fieldid element id for the username field
	 *
	 * @todo Move to a static class method - Password
	 */
	function icms_PasswordMeter($password_fieldclass = "password_adv", $username_fieldid = "uname")
	{
		global $xoTheme, $icmsConfigUser;
		$xoTheme->addScript(ICMS_URL . '/libraries/jquery/jquery.js', array('type' => 'text/javascript'));
		$xoTheme->addScript(ICMS_URL . '/libraries/jquery/password_strength_plugin.js', array('type' => 'text/javascript'));
		$xoTheme->addScript('', array('type' => 'text/javascript'), '
				$(document).ready( function() {
					$.fn.shortPass = "' . _CORE_PASSLEVEL1 . '";
					$.fn.badPass = "' . _CORE_PASSLEVEL2 . '";
					$.fn.goodPass = "' . _CORE_PASSLEVEL3 . '";
					$.fn.strongPass = "' . _CORE_PASSLEVEL4 . '";
					$.fn.samePassword = "' . _CORE_UNAMEPASS_IDENTIC . '";
					$.fn.resultStyle = "";
				$(".' . $password_fieldclass . '").passStrength({
					minPass: ' . $icmsConfigUser['minpass'] . ',
					strongnessPass: ' . $icmsConfigUser['pass_level'] . ',
					shortPass: 		"top_shortPass",
					badPass:		"top_badPass",
					goodPass:		"top_goodPass",
					strongPass:		"top_strongPass",
					baseStyle:		"top_testresult",
					userid:			"#' . $username_fieldid . '",
					messageloc:		0
				});
			});
');
	}
}

if (!function_exists('icms_buildCriteria')) {
	/**
	 * Build criteria automatically from an array of key=>value
	 *
	 * @param array $criterias array of fieldname=>value criteria
	 * @return icms_db_criteria_Element
	 * @todo Move to a static class method - Criteria
	 */
	function icms_buildCriteria($criterias)
	{
		$criteria = new icms_db_criteria_Compo();
		foreach ($criterias as $k => $v) {
			$criteria->add(new icms_db_criteria_Item($k, $v));
		}
		return $criteria;
	}
}

if (!function_exists('icms_getBreadcrumb')) {
	/**
	 * Build a breadcrumb
	 *
	 * @param array $items of the breadcrumb to be displayed
	 * @return str HTML code of the breadcrumb to be inserted in another template
	 * @todo Move to a static class method - Breadcrumb
	 */
	function icms_getBreadcrumb($items)
	{
		$icmsBreadcrumb = new icms_view_Breadcrumb($items);
		return $icmsBreadcrumb->render(true);
	}
}

if (!function_exists('icms_makeSmarty')) {
	/**
	 * Build a template assignement
	 *
	 * @deprecated 2.0  Use icms_response_* classes instead
	 *
	 * @param array $items to build the smarty to be used in templates
	 * @return bool
	 */
	function icms_makeSmarty(array $items)
	{
		global $icmsTpl, $icmsAdminTpl;
		trigger_error('Use icms_response_* classes instead', E_USER_DEPRECATED);
		if ($icmsAdminTpl) {
			$icmsAdminTpl->assign($items);
		} else {
			$icmsTpl->assign($items);
		}

		return true;
	}
}

if (!function_exists('icms_moduleAction')) {
	/**
	 * Is a module being installed, updated or uninstalled
	 * Used for setting module configuration default values or options
	 *
	 * The function should be in functions.admin.php, however it requires extra inclusion in xoops_version.php if so
	 *
	 * @param    string $dirname dirname of current module
	 * @return    bool
	 * @todo Move to a static class method - Module
	 */
	function icms_moduleAction($dirname = 'system')
	{
		global $icmsModule;
		$ret = @(
			// action module 'system'
			!empty($icmsModule) && 'system' == $icmsModule->dirname
			&&
			// current dirname
			($dirname == $_POST['dirname'] || $dirname == $_POST['module'])
			&&
			// current op
			('update_ok' == $_POST['op'] || 'install_ok' == $_POST['op'] || 'uninstall_ok' == $_POST['op'])
			&&
			// current action
			'modules' == $_POST['fct']
		);
		return $ret;
	}
}

if (!function_exists("mod_constant")) {
	/**
	 * Get localized string if it is defined
	 *
	 * @param    string $name string to be localized
	 */
	function mod_constant($name)
	{
		global $icmsModule;
		if (!empty($GLOBALS["VAR_PREFIXU"]) && defined($GLOBALS["VAR_PREFIXU"] . "_" . strtoupper($name))) {
			return CONSTANT($GLOBALS["VAR_PREFIXU"] . "_" . strtoupper($name));
		} elseif (!empty($icmsModule) && defined(strtoupper($icmsModule->getVar("dirname", "n") . "_" . $name))) {
			return CONSTANT(strtoupper($icmsModule->getVar("dirname", "n") . "_" . $name));
		} elseif (defined(strtoupper($name))) {
			return CONSTANT(strtoupper($name));
		} else {
			return str_replace("_", " ", strtolower($name));
		}
	}
}

if (!function_exists("icms_collapsableBar")) {
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $title
	 * @param unknown_type $dsc
	 * @todo Move to a static class method
	 */
	function icms_collapsableBar($id = '', $title = '', $dsc = '')
	{
		global $icmsModule;
		echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"togglecollapse('" . $id . "'); toggleIcon('" . $id . "_icon')\";>";
		echo "<img id='" . $id . "_icon' src=" . ICMS_URL . "/images/close12.gif alt='' /></a>&nbsp;" . $title . "</h3>";
		echo "<div id='" . $id . "'>";
		if ($dsc != '') {
			echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $dsc . "</span>";
		}
	}
}

if (!function_exists("icms_ajaxCollapsableBar")) {
	/**
	 *
	 * Enter description here ...
	 * @param $id
	 * @param $title
	 * @param $dsc
	 * @todo Move to a static class method
	 */
	function icms_ajaxCollapsableBar($id = '', $title = '', $dsc = '')
	{
		global $icmsModule;
		$onClick = "ajaxtogglecollapse('$id')";
		//$onClick = "togglecollapse('$id'); toggleIcon('" . $id . "_icon')";
		echo '<h3 style="border: 1px solid; color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; " onclick="' . $onClick . '">';
		echo "<img id='" . $id . "_icon' src=" . ICMS_URL . "/images/close12.gif alt='' /></a>&nbsp;" . $title . "</h3>";
		echo "<div id='" . $id . "'>";
		if ($dsc != '') {
			echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $dsc . "</span>";
		}
	}
}

/**
 * Ajax testing......
 */
/*
 function icms_collapsableBar($id = '', $title = '', $dsc='')
 {

global $icmsModule;
//echo "<h3 style=\"color: #2F5376; font-weight: bold; font-size: 14px; margin: 6px 0 0 0; \"><a href='javascript:;' onclick=\"toggle('" . $id . "'); toggleIcon('" . $id . "_icon')\";>";

?>
<h3 class="icms_collapsable_title"><a href="javascript:Effect.Combo('<? echo $id ?>');"><? echo $title ?></a></h3>
<?

echo "<img id='" . $id . "_icon' src=" . ICMS_URL . "/images/close12.gif alt='' /></a>&nbsp;" . $title . "</h3>";
echo "<div id='" . $id . "'>";
if ($dsc != '') {
echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . $dsc . "</span>";
}
}
*/

if (!function_exists("icms_openclose_collapsable")) {
	/**
	 *
	 * Enter description here ...
	 * @param unknown_type $name
	 * @todo Move to a static class method
	 */
	function icms_openclose_collapsable($name)
	{
		$path = \icms::$urls['phpself'];
		$cookie_name = $path . '_icms_collaps_' . $name;
		$cookie_name = str_replace('.', '_', $cookie_name);
		$cookie = icms_getCookieVar($cookie_name, '');
		if ($cookie == 'none') {
			echo '
		<script type="text/javascript"><!--
		togglecollapse("' . $name . '"); toggleIcon("' . $name . '_icon");
		//-->
		</script>
		';
		}
		/*	if ($cookie == 'none') {
         echo '
        <script type="text/javascript"><!--
        hideElement("' . $name . '");
        //-->
        </script>
        ';
        }
        */
	}
}

if (!function_exists("icms_close_collapsable")) {
	/**
	 * @todo Move to a static class method
	 * Enter description here ...
	 * @param unknown_type $name
	 */
	function icms_close_collapsable($name)
	{
		echo "</div>";
		icms_openclose_collapsable($name);
		echo "<br />";
	}
}

if (!function_exists("icms_getUnameFromUserEmail")) {
	/**
	 * @todo Move to a static class method - user
	 * Enter description here ...
	 * @param $email
	 */
	function icms_getUnameFromUserEmail($email = '')
	{
		$db = icms_db_Factory::instance();
		if ($email !== '') {
			$sql = $db->query("SELECT uname, email FROM " . $db->prefix('users') . " WHERE email = '" . @htmlspecialchars($email,
					ENT_QUOTES, _CHARSET) . "'");
			list($uname, $email) = $db->fetchRow($sql);
		} else {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}
		return $uname;
	}
}

if (!function_exists("icms_need_do_br")) {
	/**
	 * Check if the module currently uses WYSIWYG and decied wether to do_br or not
	 *
	 * @return bool true | false
	 * @todo Move to a static class method - text area?
	 */
	function icms_need_do_br($moduleName = false)
	{
		global $icmsConfig;

		$editor_default = $icmsConfig['editor_default'];
		return !file_exists(ICMS_EDITOR_PATH . "/" . $editor_default . "/xoops_version.php");
	}
}

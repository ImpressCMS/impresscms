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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Handles all security functions within ImpressCMS
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
/**
 * Class for managing security aspects such as checking referers, applying tokens and checking global variables for contamination
 *
 * @package	ICMS\Core
 *
 * @author	Jan Pedersen <mithrandir@xoops.org>
 * @copyright	(c) 2000-2005 The Xoops Project - www.xoops.org
 */
class icms_core_Security {

	public $errors = array();

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
	}

	/**
	 * Check if there is a valid token in $_REQUEST[$name . '_REQUEST'] - can be expanded for more wide use, later (Mith)
	 *
	 * @param bool   $clearIfValid whether to clear the token after validation
	 * @param string $token token to validate
	 * @param string $name session name
	 *
	 * @return bool
	 */
	public function check($clearIfValid = true, $token = false, $name = _CORE_TOKEN) {
		return $this->validateToken($token, $clearIfValid, $name);
	}

	/**
	 * Check if a token is valid. If no token is specified, $_REQUEST[$name . '_REQUEST'] is checked
	 *
	 * @param string $token token to validate
	 * @param bool   $clearIfValid whether to clear the token value if valid
	 * @param string $name session name to validate
	 *
	 * @return bool
	 */
	public function validateToken($token = false, $clearIfValid = true, $name = _CORE_TOKEN) {
		$token = ($token !== false)?$token:(isset($_REQUEST[$name . '_REQUEST'])?$_REQUEST[$name . '_REQUEST']:'');
		if (empty($token) || empty($_SESSION[$name . '_SESSION'])) {
			icms::$logger->addExtra(_CORE_TOKENVALID, _CORE_TOKENNOVALID);
			return false;
		}
		$validFound = false;
		$token_data = & $_SESSION[$name . '_SESSION'];
		foreach (array_keys($token_data) as $i) {
			if ($token === md5($token_data[$i]['id'] . $_SERVER['HTTP_USER_AGENT'] . env('DB_PREFIX'))) {
				if ($this->filterToken($token_data[$i])) {
					if ($clearIfValid) {
						// token should be valid once, so clear it once validated
						unset($token_data[$i]);
					}
					icms::$logger->addExtra(_CORE_TOKENVALID, _CORE_TOKENISVALID);
					$validFound = true;
				} else {
					$str = _CORE_TOKENEXPIRED;
					$this->setErrors($str);
					icms::$logger->addExtra(_CORE_TOKENVALID, $str);
				}
			}
		}
		if (!$validFound) {
			icms::$logger->addExtra(_CORE_TOKENVALID, _CORE_TOKENINVALID);
		}
		$this->garbageCollection($name);
		return $validFound;
	}

	/**
	 * Check whether a token value is expired or not
	 *
	 * @param string $token
	 *
	 * @return bool
	 */
	public function filterToken($token) {
		return (!empty($token['expire']) && $token['expire'] >= time());
	}

	/**
	 * Perform garbage collection, clearing expired tokens
	 *
	 * @param string $name session name
	 *
	 * @return void
	 */
	public function garbageCollection($name = _CORE_TOKEN) {
		if (isset($_SESSION[$name . '_SESSION']) && count($_SESSION[$name . '_SESSION']) > 0) {
			$_SESSION[$name . '_SESSION'] = array_filter($_SESSION[$name . '_SESSION'], array($this, 'filterToken'));
		}
	}

	/**
	 * Create a token in the user's session
	 *
	 * @param int $timeout time in seconds the token should be valid
	 * @param string $name session name
	 *
	 * @return string token value
	 */
	public function createToken($timeout = 0, $name = _CORE_TOKEN)
	{
		$this->garbageCollection($name);
		if ($timeout == 0) {
			$timeout = $GLOBALS['icmsConfig']['session_expire'] * 60; //session_expire is in minutes, we need seconds
		}
		$token_id = md5(uniqid(rand(), true));
		// save token data on the server
		if (!isset($_SESSION[$name . '_SESSION'])) {
			$_SESSION[$name . '_SESSION'] = array();
		}
		$token_data = array('id' => $token_id, 'expire' => time() + (int)($timeout));
		array_push($_SESSION[$name . '_SESSION'], $token_data);
		return md5($token_id . $_SERVER['HTTP_USER_AGENT'] . env('DB_PREFIX'));
	}

	/**
	 * Clear all token values from user's session
	 *
	 * @param string $name session name
	 */
	public function clearTokens($name = _CORE_TOKEN)
	{
		$_SESSION[$name . '_SESSION'] = array();
	}

	/**
	 * Check the user agent's HTTP REFERER against ICMS_URL
	 *
	 * @param int $docheck 0 to not check the referer (used with XML-RPC), 1 to actively check it
	 *
	 * @return bool
	 */
	public function checkReferer($docheck = 1) {
		$ref = xoops_getenv('HTTP_REFERER');
		if ($docheck == 0) {
			return true;
		}
		if ($ref == '') {
			return false;
		}
		if (strpos($ref, ICMS_URL) !== 0) {
			return false;
		}
		return true;
	}

	/**
	 * Check superglobals for contamination
	 *
	 * @return void
	 */
	public function checkSuperglobals() {
		foreach (array('GLOBALS', '_SESSION', 'HTTP_SESSION_VARS', '_GET', 'HTTP_GET_VARS', '_POST', 'HTTP_POST_VARS',
						'_COOKIE', 'HTTP_COOKIE_VARS', '_REQUEST', '_SERVER', 'HTTP_SERVER_VARS',
						'_ENV', 'HTTP_ENV_VARS', '_FILES', 'HTTP_POST_FILES',
						'xoopsDB', 'xoopsUser', 'xoopsUserId', 'xoopsUserGroups', 'xoopsUserIsAdmin',
						'icmsConfig', 'xoopsOption', 'xoopsModule', 'xoopsModuleConfig', 'xoopsRequestUri',
						'xoopsConfig', 'icmsOption', 'icmsConfigUser', 'icmsConfigMetaFooter', 'icmsConfigMailer',
						'icmsConfigAuth', 'icmsConfigMultilang', 'icmsConfigPersona', 'icmsConfigPlugins',
						'icmsConfigCaptcha', 'icmsConfigSearch',
		) as $bad_global) {
			if (isset($_REQUEST[$bad_global])) {
				header('Location: ' . ICMS_URL);
				exit();
			}
		}
	}

	/**
	 * Check if visitor's IP address is banned
	 * @todo : Should be changed to return bool and let the action be up to the calling script
	 *
	 * @return void
	 */
	public function checkBadips() {
		global $icmsConfig;
		if ($icmsConfig['enable_badips'] == 1 && isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') {
			foreach ($icmsConfig['bad_ips'] as $bi) {
				if (!empty($bi) && preg_match("/" . $bi . "/", $_SERVER['REMOTE_ADDR'])) {
					exit();
				}
			}
		}
		unset($bi);
		unset($bad_ips);
		unset($icmsConfig['badips']);
	}

	/**
	 * Get the HTML code for a @link icms_form_elements_Hiddentoken object - used in forms that do not use XoopsForm elements
	 *
	 * @return string
	 */
	public function getTokenHTML($name = _CORE_TOKEN) {
		$token = new icms_form_elements_Hiddentoken($name);
		return $token->render();
	}

	/**
	 * Get generated errors
	 *
	 * @param    bool    $ashtml Format using HTML?
	 *
	 * @return    array|string    Array of array messages OR HTML string
	 */
	public function &getErrors($ashtml = false) {
		if (!$ashtml) {
			return $this->errors;
		} else {
			$ret = '';
			if (count($this->errors) > 0) {
				foreach ($this->errors as $error) {
					$ret .= $error . '<br />';
				}
			}
			return $ret;
		}
	}

	/**
	 * Add an error
	 *
	 * @param   string $error
	 */
	public function setErrors($error)
	{
		$this->errors[] = trim($error);
	}
}


<?php
/**
 * A static class for on-screen messages
 *
 * Using a static class instead of a include file with global functions, along with
 * autoloading of classes, reduces the memory usage and only includes files when needed.
 *
 * @copyright	(c) 2007-2008 The ImpressCMS Project - www.impresscms.org
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Core
 * @since	1.3
 */

namespace ImpressCMS\Core;

use ImpressCMS\Core\Response\ConfirmationResponse;

/**
 * Create and display messages on the screen
 */
class Message
{

	/* Since all the methods are static, there is no __construct necessary	 */

	/**
	 * Replaces xoops_warning() and icms_warning_msg()
	 * Given either an array of messages or a string, and an optional title, create a formatted warning
	 * message
	 *
	 * @param string $msg
	 * @param string $title
	 * @param boolean $render
	 * @return string
	 * @author        modified by skenow <skenow@impresscms.org>
	 * @copyright    copyright (c) 2000-2003 XOOPS.org
	 *                You should have received a copy of XOOPS_copyrights.txt with
	 *                this file. If not, you may obtain a copy from xoops.org
	 *
	 * @author        XOOPS - include/functions.php :: xoops_warning()
	 */
	public static function warning($msg, $title = '', $render = false) {
		$ret = '<div class="warningMsg alert alert-warning" role="alert">';
		if ($title) {
			$ret .= '<h4>' . $title . '</h4>';
		}
		if (is_array($msg)) {
			foreach ($msg as $m) {
				$ret .= $m . '<br />';
			}
		} else {
			$ret .= $msg;
		}
		$ret .= '</div>';
		if ($render) {
			echo $ret;
		} else {
			return $ret;
		}
	}

	/**
	 * Replaces icms_error_msg()
	 *
	 * @author	XOOPS - include/functions.php :: xoops_error()
	 * @param	string $msg
	 * @param	string $title
	 * @param	boolean $render
	 * @return	mixed
	 */
	public static function error($msg, $title = '', $render = true) {
		$ret = '<div class="errorMsg alert alert-danger" role="alert">';
		if ($title) {
			$ret .= '<h4>' . $title . '</h4>';
		}
		if (is_array($msg)) {
			foreach ($msg as $m) {
				$ret .= $m . '<br />';
			}
		} else {
			$ret .= $msg;
		}
		$ret .= '</div>';
		if ($render) {
			echo $ret;
		} else {
			return $ret;
		}
	}

	/**
	 * Render result message (echo, so no return string)
	 *
	 * @author	XOOPS - include/functions.php :: xoops_result()
	 * @author	modified by skenow <skenow@impresscms.org>
	 * @param	string $msg
	 * @param	string $title
	 * @return	void
	 */
	public static function result($msg, $title = '') {
		echo 'div class="resultMsg alert alert-info" role="alert"';
		if ($title) {
			echo '<h4>' . $title . '</h4>';
		}
		if (is_array($msg)) {
			foreach ($msg as $m) {
				echo $m . '<br />';
			}
		} else {
			echo $msg;
		}
		echo '</div>';

	}

	/**
	 * Will render (echo) the form so no return in this function
	 *
	 * @param array $hiddens Array of Hidden values
	 * @param string $action The Form action
	 * @param string $msg The message in the confirm form
	 * @param string $submit The text on the submit button
	 * @param boolean $addtoken Whether or not to add a security token
	 *
	 * @return    void
	 * @deprecated Use ConfirmationResponse if possible. This will be removed in the future.
	 *
	 * @author    XOOPS - include/functions.php :: xoops_confirm()
	 * @author    modified by skenow <skenow@impresscms.org>
	 */
	public static function confirm($hiddens, $action, $msg, $submit = '', $addtoken = true): void
	{
		$response = new ConfirmationResponse($hiddens, $action, $msg, $submit, $addtoken);
		echo $response->getForm()->render();
	}
}

<?php
/**
 * Prevent email harvesting
 *
 * This file adds event triggers to convert email addresses to images
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		libraries
 * @since		1.3.6
 * @author		Steve Kenow <skenow@impresscms.org>
 */
/**
 *
 * Event triggers for email conversion to images
 * @since	1.3.6
 */
class IcmsPreloadProtectEmail extends icms_preload_Item {

	/**
	 * Function to be triggered after completing icms_core_DataFilter::filterTextareaDisplay() function
	 *
	 * The $array var is structured like this:
	 * $array[0] = $text
	 * $array[1] = $html
	 * $array[2] = $smiley
	 * $array[3] = $xcode
	 * $array[4] = $image
	 * $array[5] = $br
	 *
	 * @param array array containing parameters passed by filterTextareaDisplay()
	 *
	 * @return	void
	 */
	function eventAfterFilterTextareaDisplay($array) {
		$array[0] = self::convertEmail($array[0]);
	}

	/**
	 * Function to be triggered after icms_core_DataFilter::filterHTMLdisplay() function
	 *
	 * The $array var is structured like this:
	 * $array[0] = $text
	 * $array[1] = $html
	 * $array[2] = $smiley
	 * $array[3] = $xcode
	 * $array[4] = $image
	 * $array[5] = $br
	 *
	 * @param array array containing parameters passed by filterHTMLdisplay
	 *
	 * @return	void
	 */
	function eventAfterFilterHTMLdisplay($array) {
		$array[0] = self::convertEmail($array[0]);
	}

	/**
	 * Find and convert email addresses in the output to images
	 *
	 * This will convert email addresses in content to images, only if the personalization preference is set to protect emails
	 *
	 * @author	Rodrigo Pereira Lima (aka TheRplima) <therplima@impresscms.org>
	 * @param	string $_smarty_results
	 * @return	string
	 */
	function convertEmail(&$_smarty_results) {
		// @todo - this should be passed in and not coupled here
		global $icmsConfigPersona;
		// @todo - the regex needs improving: It is capturing delimiters that is not with other content
		if (preg_match_all("/([a-z0-9\-_\.]+?)@([^, \r\n\"\(\)'<>\[\]]+)/i", $_smarty_results, $texto)) {
			$patterns = array();
			$replacements = array();
			foreach ($texto[0] as $email) {
				//Don't change emails inside input or textarea form fields
				if (preg_match_all("/mailto(.*?)$email/i", $_smarty_results, $texto1)
					|| preg_match_all("/value=['\"]$email/i", $_smarty_results, $texto1)
					|| preg_match_all("/$email(.*?)<\/textarea>/i", $_smarty_results, $texto1)
				) {
					continue;
				}
				// @todo - this should be decoupled, too. This entire plugin could be more of a factory method, not knowing what types of changes to make
				$protection_type = (int) $icmsConfigPersona['email_protect'];

				if ($protection_type == 1  /* uses gd protection method */ ) {
					$patterns[] = '/' . $email . '/';
					$replacements[] = "<img style='vertical-align:middle;' class='email_protect' src='" . ICMS_URL . "/include/protection.php?p=" . base64_encode(urlencode($email)) . "'>";
				} elseif ($protection_type == 2  /* using script method */) {
					$encoded_email = json_encode(array_map("urlencode", explode(PHP_EOL, chunk_split($email, 3, PHP_EOL))));
					$patterns[] = '/' . $email . '/';
					$replacements[] = '<script type="text/javascript">document.write(decodeURIComponent('.$encoded_email.'.join("")));</script>';
				}
			}
			$_smarty_results = preg_replace($patterns, $replacements, $_smarty_results);
		}
		return $_smarty_results;
	}
}

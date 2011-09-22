<?php
/**
 * Input validation and processing, BB code conversion, Smiley conversion
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @subpackage	Textsanitizer
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		SVN: $Id$
 */

/**
 * Class to "clean up" text for various uses
 *
 * <b>Singleton</b>
 *
 * @category	ICMS
 * @package		Core
 * @subpackage	Textsanitizer
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @author		Goghs Cheng
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class icms_core_Textsanitizer {
	/**
	 * @public	array
	 */
	public $displaySmileys = array();
	/**
	 * @public	array
	 */
	public $allSmileys = array();
	/**
	 *
	 */
	public $censorConf;
	/**
	 * Constructor of this class
	 * Gets allowed html tags from admin config settings
	 * <br> should not be allowed since nl2br will be used
	 * when storing data.
	 *
	 * @todo Sofar, this does nuttin' ;-)
	 **/
	public function __construct() {}

	/**
	 * Access the only instance of this class
	 *
	 * @return   object
	 *
	 * @static
	 * @staticvar   object
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_core_Textsanitizer();
		}
		return $instance;
	}

	/**
	 * Filters out invalid strings included in URL, if any
	 *
	 * @param   array  $matches
	 * @return  string
	 */
	public function _filterImgUrl($matches) {
		if ($this->checkUrlString($matches[2])) {
			return $matches[0];
		} else {
			return '';
		}
	}

	/**
	 * Checks if invalid strings are included in URL
	 *
	 * @param   string  $text
	 * @return  bool
	 */
	public function checkUrlString($text) {
		// Check control code
		if (preg_match("/[\0-\31]/", $text)) {
			return false;
		}
		// check black pattern(deprecated)
		return !preg_match("/^(javascript|vbscript|about):/i", $text);
	}

	/**
	 * Filters textarea form data in DB for display
	 *
	 * @param   string  $text
	 * @param   bool	$html   allow html?
	 * @param   bool	$smiley allow smileys?
	 * @param   bool	$xcode  allow xoopscode?
	 * @param   bool	$image  allow inline images?
	 * @param   bool	$br	 convert linebreaks?
	 * @return  string
	 **/
	public function displayTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1) {
		// Before this can be deprecated, the events for dispalyTarea need to be added, first
		//icms_core_Debug::setDeprecated('icms_core_DataFilter::checkVar - type = text or html, $options1 = input or output', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		icms::$preload->triggerEvent('beforeDisplayTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		if ($html != 1) {
			$text = icms_core_DataFilter::htmlSpecialChars($text);
		}

		$text = icms_core_DataFilter::codePreConv($text, $xcode);
		$text = icms_core_DataFilter::makeClickable($text);
		if ($smiley != 0) {
			$text = icms_core_DataFilter::smiley($text);
		}
		if ($xcode != 0) {
			if ($image != 0) {
				$text = icms_core_DataFilter::codeDecode($text);
			} else {
				$text = icms_core_DataFilter::codeDecode($text, 0);
			}
		}
		$config_handler = icms::handler('icms_config');
		$icmsConfigPurifier = $config_handler->getConfigsByCat(ICMS_CONF_PURIFIER);
		if ($br !== 0 || ($html !== 0 && $icmsConfigPurifier['enable_purifier'] !== 1)) {
			$text = icms_core_DataFilter::nl2Br($text);
		}
		$text = icms_core_DataFilter::codeConv($text, $xcode, $image);

		if ($html != 0 && $icmsConfigPurifier['enable_purifier'] !== 0) {
			$text = icms_core_DataFilter::checkVar($text, 'html');
		}

		// ################# Preload Trigger afterDisplayTarea ##############
		icms::$preload->triggerEvent('afterDisplayTarea', array(&$text, $html, $smiley, $xcode, $image, $br));
		return $text;
	}

	/**
	 * Filters textarea form data submitted for preview
	 *
	 * @param   string  $text
	 * @param   bool	$html   allow html?
	 * @param   bool	$smiley allow smileys?
	 * @param   bool	$xcode  allow xoopscode?
	 * @param   bool	$image  allow inline images?
	 * @param   bool	$br	 convert linebreaks?
	 * @return  string
	 **/
	public function previewTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1) {
		 /* @deprecated Use icms_core_DataFilter::checkVar, instead - the events for previewTarea need to be added, first */
		//icms_core_Debug::setDeprecated('icms_core_DataFilter::checkVar - type = text or html, $options1 = input', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
		icms::$preload->triggerEvent('beforePreviewTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		$text = icms_core_DataFilter::stripSlashesGPC($text);
		if ($html != 1) {
			$text = icms_core_DataFilter::htmlSpecialChars($text);
		}

		$text = icms_core_DataFilter::codePreConv($text, $xcode);
		$text = icms_core_DataFilter::makeClickable($text);
		if ($smiley != 0) {
			$text = icms_core_DataFilter::smiley($text);
		}
		if ($xcode != 0) {
			if ($image != 0) {
				$text = icms_core_DataFilter::codeDecode($text);
			} else {
				$text = icms_core_DataFilter::codeDecode($text, 0);
			}
		}
		$config_handler = icms::handler('icms_config');
		$icmsConfigPurifier = $config_handler->getConfigsByCat(ICMS_CONF_PURIFIER);
		if ($br !== 0 || ($html !== 0 && $icmsConfigPurifier['enable_purifier'] !== 1)) {
			$text = icms_core_DataFilter::nl2Br($text);
		}
		$text = icms_core_DataFilter::codeConv($text, $xcode, $image);

		if ($html != 0 && $icmsConfigPurifier['enable_purifier'] !== 0) {
			$text = icms_core_DataFilter::checkVar($text, 'html');
		}

		icms::$preload->triggerEvent('afterPreviewTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		return $text;
	}
}
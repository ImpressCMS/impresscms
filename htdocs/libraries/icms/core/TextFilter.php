<?php
/**
 * Class to filter Text
 * @category	ICMS
 * @package		Core
 * @since		1.3
 * @author		vaughan montgomery (vaughan@impresscms.org)
 * @author		ImpressCMS Project
 * @copyright	(c) 2007-2010 The ImpressCMS Project - www.impresscms.org
 * @version		SVN: $Id$
 **/
/**
 * Provide functions to filter text
 *
 * @category	ICMS
 * @package		Core
 *
 */
class icms_core_TextFilter extends icms_core_DataFilter {

	/**
	 * Constructor for the DataFilter object
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Access the only instance of this class
	 * @return       object
	 * @static       $TextFilter_instance
	 * @staticvar    object
	 **/
	static public function getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_core_TextFilter();
		}
		return $instance;
	}

	// -------- Public Functions --------

	/**
	 *
	 * @param string $data
	 * @param string $type
	 * @param string $option1
	 * @param string $option2
	 */
	static public function checkVar($data, $type, $option1 = '', $option2 = '') {
		return parent::checkVar($data, $type, $option1, $option2);
	}

	/**
	 * Filters textarea form data in DB for display purposes
	 *
	 * @param   string  $text
	 * @param   bool	$smiley allow smileys?
	 * @param   bool	$imcode  allow icmscode?
	 * @param   bool	$image  allow inline images?
	 * @param   bool	$br	 convert linebreaks?
	 * @return  string
	 **/
	public function filterTarea($text, $smiley = 1, $imcode = 1, $image = 1, $br = 1) {
		icms::$preload->triggerEvent('beforeFilterTarea', array(&$text, $smiley, $imcode, $image, $br));

		$text = parent::htmlSpecialChars($text);

		$text = parent::codePreConv($text, $imcode);
		$text = parent::makeClickable($text);
		if ($smiley != 0) {
			$text = parent::smiley($text);
		}
		if ($imcode != 0) {
			if ($image != 0) {
				$text = parent::codeDecode($text);
			} else {
				$text = parent::codeDecode($text, 0);
			}
		}

		if ($br !== 0) {
			$text = parent::nl2Br($text);
		}
		$text = parent::codeConv($text, $imcode, $image);

		// ################# Preload Trigger afterFilterTarea ##############
		icms::$preload->triggerEvent('afterFilterTarea', array(&$text, $smiley, $imcode, $image, $br));
		return $text;
	}

	// -------- Private Functions --------
}
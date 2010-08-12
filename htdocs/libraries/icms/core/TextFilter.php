<?php
/**
 * Class to filter Text
 * @package      libraries
 * @subpackage   core
 * @since        1.3
 * @author       vaughan montgomery (vaughan@impresscms.org)
 * @author       ImpressCMS Project
 * @copyright    (c) 2007-2010 The ImpressCMS Project - www.impresscms.org
 * @version      $Id: TextFilter.php 19858 2010-07-15 12:01:13Z m0nty_ $
 **/
class icms_core_TextFilter extends icms_core_DataFilter
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Access the only instance of this class
	 * @return       object
	 * @static       $TextFilter_instance
	 * @staticvar    object
	 **/
	public static function getInstance()
	{
		static $instance;
		if(!isset($instance))
		{
			$instance = new icms_core_TextFilter();
		}
		return $instance;
	}

	// -------- Public Functions --------

	public function checkVar($data, $type, $option1 = '', $option2 = '')
	{
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
	public function filterTarea($text, $smiley = 1, $imcode = 1, $image = 1, $br = 1)
	{
		// ################# Preload Trigger beforefilterTarea ##############
		global $icmsPreloadHandler;

		// FIXME: Review this fix, is not the best. I dont found the problem,
		// it really should never worked in the admin side!
		// Maybe the preload handler should be in the install kernel.
		if(!is_object($icmsPreloadHandler))
		{
			$icmsPreloadHandler = icms_preload_Handler::getInstance();
		}
		$icmsPreloadHandler->triggerEvent('beforeFilterTarea', array(&$text, $smiley, $imcode, $image, $br));

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
		$icmsPreloadHandler->triggerEvent('afterFilterTarea', array(&$text, $smiley, $imcode, $image, $br));
		return $text;
	}

	// -------- Private Functions --------

}
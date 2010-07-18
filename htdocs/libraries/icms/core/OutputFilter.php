<?php
/**
 * Class to filter Content Output. Extends DataFilter
 * @package      libraries
 * @subpackage   core
 * @since        1.3
 * @author       vaughan montgomery (vaughan@impresscms.org)
 * @author       ImpressCMS Project
 * @copyright    (c) 2007-2010 The ImpressCMS Project - www.impresscms.org
 * @version      $Id: OutputFilter.php 19857 2010-07-15 11:51:18Z m0nty_ $
 **/
class icms_core_OutputFilter extends icms_core_DataFilter
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Access the only instance of this class
	 * @return       object
	 * @static       $OutputFilter_instance
	 * @staticvar    object
	 **/
	public static function getInstance()
	{
		static $instance;
		if(!isset($instance))
		{
			$instance = new icms_core_OutputFilter();
		}
		return $instance;
	}

	/**
	 * Filters Textarea form data in DB for display
	 *
	 * @param   string  $text
	 * @param   bool	$smiley allow smileys?
	 * @param   bool	$icode  allow and convert icmscode?
	 * @param   bool	$img  allow inline images?
	 * @param   bool	$br	 convert linebreaks?
	 * @return  string
	 **/
/*	function filterTextarea($text, $smiley = 1, $icode = 1, $img = 1, $br = 1)
	{
		// ################# Preload Trigger beforeFilterTextarea ##############
		global $icmsPreloadHandler;

		// FIXME: Review this fix, is not the best. I dont found the problem,
		// it really should never worked in the admin side!
		// Maybe the preload handler should be in the install kernel.
		if(!is_object($icmsPreloadHandler))
			$icmsPreloadHandler = icms_preload_Handler::getInstance();
		$icmsPreloadHandler->triggerEvent('beforeFilterTextarea', array(&$text, $smiley, $icode, $img, $br));

		$text = icms_core_DataFilter::htmlSpecialChars($text);

		$text = icms_core_DataFilter::codePreConv($text, $icode); // Ryuji_edit(2003-11-18)
		$text = icms_core_DataFilter::makeClickable($text);
		if($smiley != 0)
		{
			$text = icms_core_DataFilter::smiley($text);
		}
		if($icode != 0)
		{
			if($img != 0)
			{
				$text = icms_core_DataFilter::icmsCodeDecode($text);
			}
			else
			{
				$text = icms_core_DataFilter::icmsCodeDecode($text, 0);
			}
		}
		if($br !== 0)
		{
			$text = icms_core_DataFilter::nl2Br($text);
		}
		$text = icms_core_DataFilter::codeConv($text, $icode, $img);	// Ryuji_edit(2003-11-18)

		// ################# Preload Trigger afterFilterTextarea ##############
		$icmsPreloadHandler->triggerEvent('afterFilterTextarea', array(&$text, $smiley, $icode, $img, $br));
		return $text;
	} */

}
?>
<?php
/**
 * Class to filter User Input. Extends DataFilter
 * @package      libraries
 * @subpackage   core
 * @since        1.3
 * @author       vaughan montgomery (vaughan@impresscms.org)
 * @author       ImpressCMS Project
 * @copyright    (c) 2007-2010 The ImpressCMS Project - www.impresscms.org
 * @version       $Id: InputFilter.php 19866 2010-07-17 20:00:30Z phoenyx $
 **/
class icms_core_InputFilter extends icms_core_DataFilter
{
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Access the only instance of this class
	 * @return       object
	 * @static       $InputFilter_instance
	 * @staticvar    object
	 **/
	public static function getInstance()
	{
		static $instance;
		if(!isset($instance))
		{
			$instance = new icms_core_InputFilter();
		}
		return $instance;
	}

	/**
	* Filters HTMLarea form data for input to DB
	*
	* @param   string	$html
	* @param   bool		$icode	allow & convert icmscode to html?
	* @param   bool		$img	allow inline images?
	* @return  string
	**/
/*	function filterHTMLarea($html, $icode = 0, $img = 0)
	{
		// ################# Preload Trigger beforeFilterHTMLarea ##############
		global $icmsPreloadHandler;

		if(!is_object($icmsPreloadHandler))
			$icmsPreloadHandler = icms_preload_Handler::getInstance();
		$icmsPreloadHandler->triggerEvent('beforeFilterHTMLarea', array(&$html, $icode, $img));

		$html = icms_core_DataFilter::codePreConv($html, $icode); // Ryuji_edit(2003-11-18)
		$html = icms_core_DataFilter::makeClickable($html);
		if($icode != 0)
		{
			if($img != 0)
			{
				$html = icms_core_DataFilter::icmsCodeDecode($html);
			}
			else
			{
				$html = icms_core_DataFilter::icmsCodeDecode($html, 0);
			}
		}

		$html = icms_core_DataFilter::codeConv($html, $icode, $img);

		$config_handler = icms::handler('icms_config');
		$icmsConfigPurifier = $config_handler->getConfigsByCat(ICMS_CONF_PURIFIER);

		if($icmsConfigPurifier['enable_purifier'] !== 0)
		{
			$html = icms_core_DataFilter::html_purifier($html);
		}

		// ################# Preload Trigger afterFilterHTMLarea ##############
		$icmsPreloadHandler->triggerEvent('afterFilterHTMLarea', array(&$html, $icode, $img));
		return $html;
	} */
}
?>
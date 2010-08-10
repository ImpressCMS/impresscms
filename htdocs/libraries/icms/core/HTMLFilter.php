<?php
/**
 * Class to Clean & Filter HTML for various uses.
 * Class uses external HTML Purifier for filtering.
 * @package      libraries
 * @subpackage   core
 * @since        1.3
 * @author       vaughan montgomery (vaughan@impresscms.org)
 * @author       ImpressCMS Project
 * @copyright    (c) 2007-2010 The ImpressCMS Project - www.impresscms.org
 * @version       $Id: HTMLFilter.php 19866 2010-07-17 20:00:30Z phoenyx $
**/
class icms_core_HTMLFilter extends icms_core_DataFilter
{
	/**
	 * variable used by HTML Filter Library
	 **/
	public $purifier;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$config_handler = icms::handler('icms_config');
		$icmsConfigPurifier = $config_handler->getConfigsByCat(ICMS_CONF_PURIFIER);

		require_once ICMS_ROOT_PATH.'/libraries/htmlpurifier/HTMLPurifier.standalone.php';
		require_once ICMS_ROOT_PATH.'/libraries/htmlpurifier/HTMLPurifier.autoload.php';
		if($icmsConfigPurifier['purifier_Filter_ExtractStyleBlocks'] !== 0)
		{
			require_once ICMS_ROOT_PATH.'/plugins/csstidy/class.csstidy.php';
		}
	}

	/**
	 * Access the only instance of this class
	 * @return      object
	 * @static      $instance
	 * @staticvar   object
	 **/
	public static function getInstance()
	{
		static $instance;
		if(!isset($instance))
		{
			$instance = new icms_core_HTMLFilter();
		}
		return $instance;
	}

// ----- Public Functions -----

	/**
	 * Allows HTML Purifier library to be called when required
	 * @param    string  $html    input to be cleaned
	 * @return   string
	 **/
	public function htmlpurify($html)
	{
		$icmsPurifyConf = self::getPurifierConfig(); // gets the Config Data
		//parent::filterDebugInfo('icmsPurifyConf', $icmsPurifyConf); // uncomment for specific config debug info

		$this->purifier = new HTMLPurifier($icmsPurifyConf);
		$html = $this->purifier->purify($html);

		return $html;
	}

// ----- Private Functions -----

	/**
	 * Gets Custom Purifier configurations ** this function will improve in time **
	 * @return  array    $icmsPurifierConf
	 **/
	protected function getPurifierConfig()
	{
		$config_handler = icms::handler('icms_config');
		$icmsConfigPurifier = $config_handler->getConfigsByCat(ICMS_CONF_PURIFIER);

		$icmsPurifierConf = array(
            'HTML.DefinitionID' => $icmsConfigPurifier['purifier_HTML_DefinitionID'],
            'HTML.DefinitionRev' => $icmsConfigPurifier['purifier_HTML_DefinitionRev'],
            'HTML.Doctype' => $icmsConfigPurifier['purifier_HTML_Doctype'],
            'HTML.AllowedElements' => $icmsConfigPurifier['purifier_HTML_AllowedElements'],
            'HTML.AllowedAttributes' => $icmsConfigPurifier['purifier_HTML_AllowedAttributes'],
            'HTML.ForbiddenElements' => $icmsConfigPurifier['purifier_HTML_ForbiddenElements'],
            'HTML.ForbiddenAttributes' => $icmsConfigPurifier['purifier_HTML_ForbiddenAttributes'],
            'HTML.MaxImgLength' => $icmsConfigPurifier['purifier_HTML_MaxImgLength'],
            'HTML.TidyLevel' => $icmsConfigPurifier['purifier_HTML_TidyLevel'],
            'HTML.SafeEmbed' => $icmsConfigPurifier['purifier_HTML_SafeEmbed'],
            'HTML.SafeObject' => $icmsConfigPurifier['purifier_HTML_SafeObject'],
            'HTML.Attr.Name.UseCDATA' => $icmsConfigPurifier['purifier_HTML_AttrNameUseCDATA'],
            'Output.FlashCompat' => $icmsConfigPurifier['purifier_Output_FlashCompat'],
            'CSS.DefinitionRev' => $icmsConfigPurifier['purifier_CSS_DefinitionRev'],
            'CSS.AllowImportant' => $icmsConfigPurifier['purifier_CSS_AllowImportant'],
            'CSS.AllowTricky' => $icmsConfigPurifier['purifier_CSS_AllowTricky'],
            'CSS.AllowedProperties' => $icmsConfigPurifier['purifier_CSS_AllowedProperties'],
            'CSS.MaxImgLength' => $icmsConfigPurifier['purifier_CSS_MaxImgLength'],
            'CSS.Proprietary' => $icmsConfigPurifier['purifier_CSS_Proprietary'],
            'AutoFormat.AutoParagraph' => $icmsConfigPurifier['purifier_AutoFormat_AutoParagraph'],
            'AutoFormat.DisplayLinkURI' => $icmsConfigPurifier['purifier_AutoFormat_DisplayLinkURI'],
            'AutoFormat.Linkify' => $icmsConfigPurifier['purifier_AutoFormat_Linkify'],
            'AutoFormat.PurifierLinkify' => $icmsConfigPurifier['purifier_AutoFormat_PurifierLinkify'],
            'AutoFormat.Custom' => $icmsConfigPurifier['purifier_AutoFormat_Custom'],
            'AutoFormat.RemoveEmpty' => $icmsConfigPurifier['purifier_AutoFormat_RemoveEmpty'],
            'AutoFormat.RemoveEmpty.RemoveNbsp' => $icmsConfigPurifier['purifier_AutoFormat_RemoveEmptyNbsp'],
            'AutoFormat.RemoveEmpty.RemoveNbsp.Exceptions' =>
		$icmsConfigPurifier['purifier_AutoFormat_RemoveEmptyNbspExceptions'],
            'Core.EscapeNonASCIICharacters' => $icmsConfigPurifier['purifier_Core_EscapeNonASCIICharacters'],
            'Core.HiddenElements' => $icmsConfigPurifier['purifier_Core_HiddenElements'],
            'Core.RemoveInvalidImg' => $icmsConfigPurifier['purifier_Core_RemoveInvalidImg'],
            'Core.Encoding' => _CHARSET,
            'Cache.DefinitionImpl' => 'Serializer',
            'Cache.SerializerPath' => ICMS_TRUST_PATH.'/cache/htmlpurifier',
            'URI.Host' => $icmsConfigPurifier['purifier_URI_Host'],
            'URI.Base' => $icmsConfigPurifier['purifier_URI_Base'],
            'URI.Disable' => $icmsConfigPurifier['purifier_URI_Disable'],
            'URI.DisableExternal' => $icmsConfigPurifier['purifier_URI_DisableExternal'],
            'URI.DisableExternalResources' => $icmsConfigPurifier['purifier_URI_DisableExternalResources'],
            'URI.DisableResources' => $icmsConfigPurifier['purifier_URI_DisableResources'],
            'URI.MakeAbsolute' => $icmsConfigPurifier['purifier_URI_MakeAbsolute'],
            'URI.HostBlacklist' => $icmsConfigPurifier['purifier_URI_HostBlacklist'],
            'URI.AllowedSchemes' => $icmsConfigPurifier['purifier_URI_AllowedSchemes'],
            'URI.DefinitionID' => $icmsConfigPurifier['purifier_URI_DefinitionID'],
            'URI.DefinitionRev' => $icmsConfigPurifier['purifier_URI_DefinitionRev'],
            'URI.AllowedSchemes' => $icmsConfigPurifier['purifier_URI_AllowedSchemes'],
            'Attr.AllowedFrameTargets' => $icmsConfigPurifier['purifier_Attr_AllowedFrameTargets'],
            'Attr.AllowedRel' => $icmsConfigPurifier['purifier_Attr_AllowedRel'],
            'Attr.AllowedClasses' => $icmsConfigPurifier['purifier_Attr_AllowedClasses'],
            'Attr.ForbiddenClasses' => $icmsConfigPurifier['purifier_Attr_ForbiddenClasses'],
            'Attr.DefaultInvalidImage' => $icmsConfigPurifier['purifier_Attr_DefaultInvalidImage'],
            'Attr.DefaultInvalidImageAlt' => $icmsConfigPurifier['purifier_Attr_DefaultInvalidImageAlt'],
            'Attr.DefaultImageAlt' => $icmsConfigPurifier['purifier_Attr_DefaultImageAlt'],
            'Attr.ClassUseCDATA' => $icmsConfigPurifier['purifier_Attr_ClassUseCDATA'],
            'Attr.IDPrefix' => $icmsConfigPurifier['purifier_Attr_IDPrefix'],
            'Attr.EnableID' => $icmsConfigPurifier['purifier_Attr_EnableID'],
            'Attr.IDPrefixLocal' => $icmsConfigPurifier['purifier_Attr_IDPrefixLocal'],
            'Attr.IDBlacklist' => $icmsConfigPurifier['purifier_Attr_IDBlacklist'],
            'Filter.ExtractStyleBlocks.Escaping' => $icmsConfigPurifier['purifier_Filter_ExtractStyleBlocks_Escaping'],
            'Filter.ExtractStyleBlocks.Scope' => $icmsConfigPurifier['purifier_Filter_ExtractStyleBlocks_Scope'],
            'Filter.ExtractStyleBlocks' => $icmsConfigPurifier['purifier_Filter_ExtractStyleBlocks'],
            'Filter.YouTube' => $icmsConfigPurifier['purifier_Filter_YouTube'],
		);
		return parent::cleanArray($icmsPurifierConf);
	}
}
?>
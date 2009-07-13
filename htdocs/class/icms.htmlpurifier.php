<?php
/**
 * Class to Clean & Filter HTML for various uses.
 *
 * Class uses external HTML Purifier for filtering.
 *
 * @package	core
 *
 * @author	vaughan montgomery (vaughan@impresscms.org)
 * @author      ImpressCMS Project
 * @copyright	(c) 2007-2008 The ImpressCMS Project - www.impresscms.org
 **/
class icms_HTMLPurifier
{
	/**
	* variable used by HTMLPurifier Library
	**/
	var $purifier;

	/**
	 * Constructor
	 */
	function icms_HTMLPurifier()
	{
		require_once ICMS_ROOT_PATH.'/libraries/htmlpurifier/HTMLPurifier.standalone.php';
		require_once ICMS_ROOT_PATH.'/libraries/htmlpurifier/HTMLPurifier.autoload.php';
	}

	/**
	* Access the only instance of this class
	*
	* @return	object
 	*
 	* @static 	$purify_instance
 	* @staticvar   	object
	**/
	public static function &getPurifierInstance()
	{
		static $purify_instance;
		if (!isset($purify_instance))
		{
			$purify_instance = new icms_HTMLPurifier();
		}
		return $purify_instance;
	}

	/**
	* Gets Custom Purifier configurations ** this function is for future development **
	*
	* @param   string  $icmsSecurity configuration to use.
	* @return  string
	**/
	function icms_getPurifierConfig()
	{
	}

	/**
	* Allows HTML Purifier library to be called when required
	* requires HTMLPurifier 4.0.0
	*
	* @param   string  $html input to be cleaned
	* @param   string  $config custom filtering config?
	* @param   string  $icms_PurifyConfig instanciate HTMLPurifier Library with default settings
	* @return  string
	**/
	function icms_html_purifier($html, $config = 'system-global')
	{
		if(get_magic_quotes_gpc()) {$html = stripslashes($html);}

		$host_domain = icms_get_base_domain(ICMS_URL);
		$host_base = icms_get_url_domain(ICMS_URL);

		// Allowed Elements in HTML
		$HTML_Allowed_Elms = 'a, abbr, acronym, b, blockquote, br, caption, cite, code, dd, del, dfn, div, dl, dt, em, font, h1, h2, h3, h4, h5, h6, i, img, ins, kbd, li, ol, p, pre, s, span, strike, strong, sub, sup, table, tbody, td, tfoot, th, thead, tr, tt, u, ul, var';

		// Allowed Element Attributes in HTML, element must also be allowed in Allowed Elements for these attributes to work.
		$HTML_Allowed_Attr = 'a.class, a.href, a.id, a.rev, a.style, a.title, a.target, a.rel, abbr.title, acronym.title, blockquote.cite, div.align, div.style, div.class, div.id, font.size, font.color, h1.style, h2.style, h3.style, h4.style, h5.style, h6.style, img.src, img.alt, img.title, img.class, img.align, img.style, img.height, img.width, ol.style, p.style, span.style, span.class, span.id, table.class, table.id, table.border, table.cellpadding, table.cellspacing, table.style, table.width, td.abbr, td.align, td.class, td.id, td.colspan, td.rowspan, td.style, td.valign, tr.align, tr.class, tr.id, tr.style, tr.valign, th.abbr, th.align, th.class, th.id, th.colspan, th.rowspan, th.style, th.valign, ul.style';

		// Filters used by Custom filter. Filter must be located in libraries/htmlpurifier/standalone/HTMLPurifier/Filter/
		$Filter_Custom = array(new HTMLPurifier_Filter_WeGame(), new HTMLPurifier_Filter_LiveLeak(), new HTMLPurifier_Filter_Vimeo(), new HTMLPurifier_Filter_LocalMovie(), new HTMLPurifier_Filter_GoogleVideo());

		// sets default config settings for htmpurifier
		$icms_PurifyConfig = HTMLPurifier_Config::createDefault();

		// Custom Configuration
		// these in future could be defined from admin interface allowing more advanced customised configurations.
		if($config = 'system-global')
		{
			$icms_PurifyConfig->set('HTML.DefinitionID', 'system-global');
			$icms_PurifyConfig->set('HTML.DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
			$icms_PurifyConfig->set('HTML.AllowedElements', $HTML_Allowed_Elms); // sets allowed html elements that can be used.
			$icms_PurifyConfig->set('HTML.AllowedAttributes', $HTML_Allowed_Attr); // sets allowed html attributes that can be used.
			$icms_PurifyConfig->set('HTML.TidyLevel', 'medium');
			$icms_PurifyConfig->set('HTML.SafeEmbed', true);
			$icms_PurifyConfig->set('HTML.SafeObject', true);

			$icms_PurifyConfig->set('CSS.DefinitionRev', 1);
			$icms_PurifyConfig->set('CSS.AllowTricky', true);

			$icms_PurifyConfig->set('AutoFormat.AutoParagraph', false);
			$icms_PurifyConfig->set('AutoFormat.Linkify', true);
		
			$icms_PurifyConfig->set('Core.Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core.EscapeNonASCIICharacters', true); // escapes Non ASCII characters that non utf-8 character sets recognise.
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI.DefinitionID', 'system-global');
			$icms_PurifyConfig->set('URI.DefinitionRev', 1);
			$icms_PurifyConfig->set('URI.Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.AllowedSchemes', array('http' => true,
									'https' => true,
									'mailto' => true,
									'ftp' => true,
									'nntp' => true,
									'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI.HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI.DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Attr.EnableID', true);
			$icms_PurifyConfig->set('Attr.IDPrefix', 'user_css_');
			$icms_PurifyConfig->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
			$icms_PurifyConfig->set('Attr.AllowedRel', 'external, nofollow, external nofollow, lightbox');

			$icms_PurifyConfig->set('Filter.ExtractStyleBlocks', false);
			$icms_PurifyConfig->set('Filter.YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.
			$icms_PurifyConfig->set('Filter.Custom', $Filter_Custom);
		}
		elseif($config = 'system-basic')
		{
			$icms_PurifyConfig->set('HTML.DefinitionID', 'system-basic');
			$icms_PurifyConfig->set('HTML.DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
			$icms_PurifyConfig->set('HTML.AllowedElements', $HTML_Allowed_Elms); // sets allowed html elements that can be used.
			$icms_PurifyConfig->set('HTML.AllowedAttributes', $HTML_Allowed_Attr); // sets allowed html attributes that can be used.
			$icms_PurifyConfig->set('HTML.TidyLevel', 'none');
			$icms_PurifyConfig->set('HTML.SafeEmbed', true);
			$icms_PurifyConfig->set('HTML.SafeObject', true);

			$icms_PurifyConfig->set('CSS.DefinitionRev', 1);
			$icms_PurifyConfig->set('CSS.AllowTricky', true);

			$icms_PurifyConfig->set('AutoFormat.AutoParagraph', false);
			$icms_PurifyConfig->set('AutoFormat.Linkify', true);
		
			$icms_PurifyConfig->set('Core.Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core.EscapeNonASCIICharacters', true); // escapes Non ASCII characters that non utf-8 character sets recognise.
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI.DefinitionID', 'system-basic');
			$icms_PurifyConfig->set('URI.DefinitionRev', 1);
			$icms_PurifyConfig->set('URI.Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.AllowedSchemes', array(	'http' => true,
									'https' => true,
									'mailto' => true,
									'ftp' => true,
									'nntp' => true,
									'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI.HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI.DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Attr.EnableID', true);
			$icms_PurifyConfig->set('Attr.IDPrefix', 'user_css_');
			$icms_PurifyConfig->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
			$icms_PurifyConfig->set('Attr.AllowedRel', 'external, nofollow, external nofollow, lightbox');

			$icms_PurifyConfig->set('Filter.ExtractStyleBlocks', false);
			$icms_PurifyConfig->set('Filter.YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.
			$icms_PurifyConfig->set('Filter.Custom', $Filter_Custom);
		}
		elseif($config = 'protector')
		{
			$icms_PurifyConfig->set('HTML.DefinitionID', 'protector');
			$icms_PurifyConfig->set('HTML.DefinitionRev', 1);
			$icms_PurifyConfig->set('Core.Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_ROOT_PATH.'/cache');
			}
		}
		elseif($config = 'display') // config id level for display HTMLArea
		{
			$icms_PurifyConfig->set('HTML.DefinitionID', 'display');
			$icms_PurifyConfig->set('HTML.DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
			$icms_PurifyConfig->set('HTML.AllowedElements', $HTML_Allowed_Elms); // sets allowed html elements that can be used.
			$icms_PurifyConfig->set('HTML.AllowedAttributes', $HTML_Allowed_Attr); // sets allowed html attributes that can be used.
			$icms_PurifyConfig->set('HTML.TidyLevel', 'medium');
			$icms_PurifyConfig->set('HTML.SafeEmbed', true);
			$icms_PurifyConfig->set('HTML.SafeObject', true);

			$icms_PurifyConfig->set('CSS.DefinitionRev', 1);
			$icms_PurifyConfig->set('CSS.AllowTricky', true);

			$icms_PurifyConfig->set('AutoFormat.AutoParagraph', false);
			$icms_PurifyConfig->set('AutoFormat.Linkify', true);
		
			$icms_PurifyConfig->set('Core.Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core.EscapeNonASCIICharacters', true); // escapes Non ASCII characters that non utf-8 character sets recognise.
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI.DefinitionID', 'display');
			$icms_PurifyConfig->set('URI.DefinitionRev', 1);
			$icms_PurifyConfig->set('URI.Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.AllowedSchemes', array(	'http' => true,
									'https' => true,
									'mailto' => true,
									'ftp' => true,
									'nntp' => true,
									'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI.HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI.DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Attr.EnableID', true);
			$icms_PurifyConfig->set('Attr.IDPrefix', 'user_css_');
			$icms_PurifyConfig->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
			$icms_PurifyConfig->set('Attr.AllowedRel', 'external, nofollow, external nofollow, lightbox');

			$icms_PurifyConfig->set('Filter.ExtractStyleBlocks', false);
			$icms_PurifyConfig->set('Filter.YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.
			$icms_PurifyConfig->set('Filter.Custom', $Filter_Custom);
		}
		elseif($config = 'preview') // config id level for preview HTMLArea
		{
			$icms_PurifyConfig->set('HTML.DefinitionID', 'preview');
			$icms_PurifyConfig->set('HTML.DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
			$icms_PurifyConfig->set('HTML.AllowedElements', $HTML_Allowed_Elms); // sets allowed html elements that can be used.
			$icms_PurifyConfig->set('HTML.AllowedAttributes', $HTML_Allowed_Attr); // sets allowed html attributes that can be used.
			$icms_PurifyConfig->set('HTML.TidyLevel', 'light');
			$icms_PurifyConfig->set('HTML.SafeEmbed', true);
			$icms_PurifyConfig->set('HTML.SafeObject', true);

			$icms_PurifyConfig->set('CSS.DefinitionRev', 1);
			$icms_PurifyConfig->set('CSS.AllowTricky', true);

			$icms_PurifyConfig->set('AutoFormat.AutoParagraph', false);
			$icms_PurifyConfig->set('AutoFormat.Linkify', true);
		
			$icms_PurifyConfig->set('Core.Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core.EscapeNonASCIICharacters', true); // escapes Non ASCII characters that non utf-8 character sets recognise.
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache.DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache.SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI.DefinitionID', 'preview');
			$icms_PurifyConfig->set('URI.DefinitionRev', 1);
			$icms_PurifyConfig->set('URI.Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI.AllowedSchemes', array(	'http' => true,
									'https' => true,
									'mailto' => true,
									'ftp' => true,
									'nntp' => true,
									'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI.HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI.DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Attr.EnableID', true);
			$icms_PurifyConfig->set('Attr.IDPrefix', 'user_css_');
			$icms_PurifyConfig->set('Attr.AllowedFrameTargets', '_blank, _parent, _self, _top');
			$icms_PurifyConfig->set('Attr.AllowedRel', 'external, nofollow, external nofollow, lightbox');

			$icms_PurifyConfig->set('Filter.ExtractStyleBlocks', false);
			$icms_PurifyConfig->set('Filter.YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.
			$icms_PurifyConfig->set('Filter.Custom', $Filter_Custom);
		}
		$icms_PurifyDef = $icms_PurifyConfig->getHTMLDefinition(true);

		$this->purifier = new HTMLPurifier($icms_PurifyConfig);

		if($config = 'protector') {$html = $this->icms_purify_recursive($html);}
		else {$html = $this->purifier->purify($html);}

		return $html;
	}


	/**
	 * Purifies $data recursively
	 *
	 * @param	  array   $data   data array to purify
	 * @return	string  $data   purified data array
	 */
	function icms_purify_recursive($data)
	{
		if(is_array($data)) {return array_map(array($this, 'icms_purify_recursive'), $data);}
		else {return strlen($data) > 32 ? $this->purifier->purify($data) : $data;}
	}

	/**
	 * Filters & Cleans HTMLArea form data submitted for display
	 *
	 * @param   string  $html
	 * @param   string  $config custom filtering config?
	 * @return  string
	 **/
	function &displayHTMLarea($html, $config = 'display')
	{
		// ################# Preload Trigger beforeDisplayTarea ##############
		global $icmsPreloadHandler;
		$icmsPreloadHandler->triggerEvent('beforedisplayHTMLarea', array(&$html, $config));

		$html = $this->icms_html_purifier($html, $config);

		// ################# Preload Trigger afterDisplayTarea ##############
		global $icmsPreloadHandler;
		$icmsPreloadHandler->triggerEvent('afterdisplayHTMLarea', array(&$html, $config));		
		return $html;
	}

	/**
	 * Filters & Cleans HTMLArea form data submitted for preview
	 *
	 * @param   string  $html
	 * @param   string  $config custom filtering config?
	 * @return  string
	 **/
	function &previewHTMLarea($html, $config = 'preview')
	{
		$html = $this->icms_html_purifier($html, $config);

		return $html;
	}

}

?>
<?php
/**
 * Class to Clean & Filter HTML for various uses.
 *
 * Class uses external HTML Purifier for filtering.
 *
 * @package	kernel
 * @subpackage	core
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

	function icms_HTMLPurifier()
	{
		require ICMS_ROOT_PATH.'/libraries/htmlpurifier/HTMLPurifier.standalone.php';
		require ICMS_ROOT_PATH.'/libraries/htmlpurifier/HTMLPurifier.autoload.php';
	}

	/**
	* Access the only instance of this class
	*
	* @return	object
     	*
     	* @static 	$purify_instance
     	* @staticvar   	object
	**/
	function &getPurifierInstance()
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
/*
		global $icmsSecurity;

		if(!isset($icmsSecurity['htmlpurifier']['icms_PurifyConfig']))
		{
			// sets default config settings for htmpurifier
			$icms_PurifyConfig = HTMLPurifier_Config::createDefault();
			
			$host_domain = icms_get_base_domain(ICMS_URL);
			$host_base = icms_get_url_domain(ICMS_URL);
		
			$icms_PurifyConfig->set('HTML', 'DefinitionID', 'system-global');
			$icms_PurifyConfig->set('HTML', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML', 'Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
			$icms_PurifyConfig->set('HTML', 'Allowed', 'a[href|title], abbr[title], acronym[title], b, blockquote[cite], br, caption, cite,
						code, dd, del, dfn, div, dl, dt, em, i, img[src|alt|title|class], ins, kbd, li, ol, p, pre, s, strike,
						strong, sub, sup, table, tbody, td, tfoot, th, thead, tr, tt, u, ul, var'); // sets allowed html tags.
			$icms_PurifyConfig->set('HTML', 'TidyLevel', 'medium');

			$icms_PurifyConfig->set('AutoFormat', 'AutoParagraph', true);
			$icms_PurifyConfig->set('AutoFormat', 'Linkify', true);
		
			$icms_PurifyConfig->set('Core', 'AggressivelyFixLt', true);
			$icms_PurifyConfig->set('Core', 'Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core', 'EscapeNonASCIICharacters', true);
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI', 'DefinitionID', 'system-global');
			$icms_PurifyConfig->set('URI', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('URI', 'Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'AllowedSchemes', array(	'http' => true,
										'https' => true,
										'mailto' => true,
										'ftp' => true,
										'nntp' => true,
										'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI', 'HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI', 'DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Filter', 'YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.

			$icms_PurifyDef = $icms_PurifyConfig->getHTMLDefinition(true);

		}
		else
		{
			$icms_PurifyConfig = HTMLPurifier_Config::create($icmsSecurity['htmlpurifier']['icms_PurifyConfig']);
		}
    		return $icms_PurifyConfig;
*/
	}

	/**
	* Allows HTML Purifier library to be called when required
	*
	* @param   string  $html input to be cleaned
	* @param   string  $config custom filtering config?
	* @param   string  $icms_PurifyConfig instanciate HTMLPurifier Library with default settings
	* @return  string
	**/
	function icms_html_purifier($html, $config = 'system-global')
	{
		$host_domain = icms_get_base_domain(ICMS_URL);
		$host_base = icms_get_url_domain(ICMS_URL);

		// HTML_Allowed: allowed tags for html content. format: element, element[attribute] - seperate attributes using pipe '|'
		$HTML_Allowed = 'a[href|title|target|rel], abbr[title], acronym[title], b, blockquote[cite], br, caption, cite, code, dd,
					del, dfn, div[align|style], dl, dt, em, font[size|color], h1, h2, h3, h4, h5, h6, i, img[src|alt|title|class|align|style], ins, kbd, li, ol, p[style], pre, s, span[style], strike, strong, sub, sup, table, tbody, td, tfoot, th, thead, tr, tt, u, ul, var';
		
		$HTML_Allowed_Elms = 'a, abbr, acronym, b, blockquote, br, caption, cite, code, dd, del, dfn, div, dl, dt, em, font, h1, h2, h3, h4, h5, h6, i, img, ins, kbd, li, ol, p, pre, s, span, strike, strong, sub, sup, table, tbody, td, tfoot, th, thead, tr, tt, u, ul, var';

		$HTML_Allowed_Attr = 'a.href, a.rev, a.title, a.target, a.rel, abbr.title, acronym.title, blockquote.cite, div.align, div.style, div.class, font.size, font.color,	h1.style, h2.style, h3.style, h4.style, h5.style, h6.style, img.src, img.alt, img.title, img.class, img.align, img.style, p.style, span.style, span.class, table.class, table.border, table.cellpadding, table.cellspacing, table.style, table.width, td.abbr, td.align, td.class, td.colspan, td.rowspan, td.style, td.valign, tr.align, tr.class, tr.style, tr.valign, th.abbr, th.align, th.class, th.colspan, th.rowspan, th.style, th.valign';

		// sets default config settings for htmpurifier
		$icms_PurifyConfig = HTMLPurifier_Config::createDefault();

		// Custom Configuration
		// these in future could be defined from admin interface allowing more advanced customised configurations.
		if($config = 'system-global')
		{
			$icms_PurifyConfig->set('HTML', 'DefinitionID', 'system-global');
			$icms_PurifyConfig->set('HTML', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML', 'Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
//			$icms_PurifyConfig->set('HTML', 'Allowed', $HTML_Allowed); // sets allowed html tags that can be used.
			$icms_PurifyConfig->set('HTML', 'AllowedElements', $HTML_Allowed_Elms); // sets allowed html elements that can be used.
			$icms_PurifyConfig->set('HTML', 'AllowedAttributes', $HTML_Allowed_Attr); // sets allowed html attributes that can be used.
			$icms_PurifyConfig->set('HTML', 'TidyLevel', 'medium');

			$icms_PurifyConfig->set('AutoFormat', 'AutoParagraph', true);
			$icms_PurifyConfig->set('AutoFormat', 'Linkify', true);
		
			$icms_PurifyConfig->set('Core', 'AggressivelyFixLt', true);
			$icms_PurifyConfig->set('Core', 'Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core', 'EscapeNonASCIICharacters', true); // escapes Non ASCII characters that non utf-8 character sets recognise.
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI', 'DefinitionID', 'system-global');
			$icms_PurifyConfig->set('URI', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('URI', 'Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'AllowedSchemes', array(	'http' => true,
									'https' => true,
									'mailto' => true,
									'ftp' => true,
									'nntp' => true,
									'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI', 'HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI', 'DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Attr', 'AllowedFrameTargets', '_blank, _parent, _self, _top');
			$icms_PurifyConfig->set('Attr', 'AllowedRel', 'external, nofollow, external nofollow, lightbox');

			$icms_PurifyConfig->set('Filter', 'YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.
		}
		elseif($config = 'protector')
		{
			$icms_PurifyConfig->set('HTML', 'DefinitionID', 'protector');
			$icms_PurifyConfig->set('HTML', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('Core', 'Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_ROOT_PATH.'/cache');
			}
		}
		elseif($config = 'display') // config id level for display HTMLArea
		{
			$icms_PurifyConfig->set('HTML', 'DefinitionID', 'display');
			$icms_PurifyConfig->set('HTML', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML', 'Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
//			$icms_PurifyConfig->set('HTML', 'Allowed', $HTML_Allowed); // sets allowed html tags that can be used.
			$icms_PurifyConfig->set('HTML', 'AllowedElements', $HTML_Allowed_Elms); // sets allowed html elements that can be used.
			$icms_PurifyConfig->set('HTML', 'AllowedAttributes', $HTML_Allowed_Attr); // sets allowed html attributes that can be used.
			$icms_PurifyConfig->set('HTML', 'TidyLevel', 'medium');

			$icms_PurifyConfig->set('AutoFormat', 'AutoParagraph', true);
			$icms_PurifyConfig->set('AutoFormat', 'Linkify', true);
		
			$icms_PurifyConfig->set('Core', 'AggressivelyFixLt', true);
			$icms_PurifyConfig->set('Core', 'Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core', 'EscapeNonASCIICharacters', true); // escapes Non ASCII characters that non utf-8 character sets recognise.
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI', 'DefinitionID', 'display');
			$icms_PurifyConfig->set('URI', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('URI', 'Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'AllowedSchemes', array(	'http' => true,
									'https' => true,
									'mailto' => true,
									'ftp' => true,
									'nntp' => true,
									'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI', 'HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI', 'DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Attr', 'AllowedFrameTargets', '_blank, _parent, _self, _top');
			$icms_PurifyConfig->set('Attr', 'AllowedRel', 'external, nofollow, external nofollow, lightbox');

			$icms_PurifyConfig->set('Filter', 'YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.
		}
		elseif($config = 'preview') // config id level for preview HTMLArea
		{
			$icms_PurifyConfig->set('HTML', 'DefinitionID', 'preview');
			$icms_PurifyConfig->set('HTML', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('HTML', 'Doctype', 'XHTML 1.0 Transitional'); // sets purifier to use specified Doctype when tidying etc.
//			$icms_PurifyConfig->set('HTML', 'Allowed', $HTML_Allowed); // sets allowed html tags that can be used.
			$icms_PurifyConfig->set('HTML', 'AllowedElements', $HTML_Allowed_Elms); // sets allowed html elements that can be used.
			$icms_PurifyConfig->set('HTML', 'AllowedAttributes', $HTML_Allowed_Attr); // sets allowed html attributes that can be used.
			$icms_PurifyConfig->set('HTML', 'TidyLevel', 'none');

			$icms_PurifyConfig->set('AutoFormat', 'AutoParagraph', true);
			$icms_PurifyConfig->set('AutoFormat', 'Linkify', true);
		
			$icms_PurifyConfig->set('Core', 'AggressivelyFixLt', true);
			$icms_PurifyConfig->set('Core', 'Encoding', _CHARSET); // sets purifier to use specified encoding. default = UTF-8
			if(strtolower(_CHARSET) !== 'utf-8')
			{
  				$icms_PurifyConfig->set('Core', 'EscapeNonASCIICharacters', true); // escapes Non ASCII characters that non utf-8 character sets recognise.
			}

			// sets the path where HTMLPurifier stores it's serializer cache.
			if(is_dir(ICMS_PURIFIER_CACHE))
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_PURIFIER_CACHE);
			}
			else
			{
				$icms_PurifyConfig->set('Cache', 'DefinitionImpl', 'Serializer');
				$icms_PurifyConfig->set('Cache', 'SerializerPath', ICMS_ROOT_PATH.'/cache');
			}

			$icms_PurifyConfig->set('URI', 'DefinitionID', 'preview');
			$icms_PurifyConfig->set('URI', 'DefinitionRev', 1);
			$icms_PurifyConfig->set('URI', 'Host', $host_domain); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'Base', $host_base); // sets host URI for filtering. this should be the base domain name. ie. impresscms.org and not community.impresscms.org.
			$icms_PurifyConfig->set('URI', 'AllowedSchemes', array(	'http' => true,
									'https' => true,
									'mailto' => true,
									'ftp' => true,
									'nntp' => true,
									'news' => true,)); // sets allowed URI schemes to be allowed in Forms.
			$icms_PurifyConfig->set('URI', 'HostBlacklist', ''); // array of domain names to filter out (blacklist).
			$icms_PurifyConfig->set('URI', 'DisableExternal', false); // if enabled will disable all links/images from outside your domain (requires Host being set)

			$icms_PurifyConfig->set('Attr', 'AllowedFrameTargets', '_blank, _parent, _self, _top');
			$icms_PurifyConfig->set('Attr', 'AllowedRel', 'external, nofollow, external nofollow, lightbox');

			$icms_PurifyConfig->set('Filter', 'YouTube', true); // setting to true will allow Youtube files to be embedded into your site & w3c validated.
		}
		$icms_PurifyDef = $icms_PurifyConfig->getHTMLDefinition(true);

		$this->purifier = new HTMLPurifier($icms_PurifyConfig);

		if($config = 'protector') {$html = $this->icms_purify_recursive($html);}
		else {$html = $this->purifier->purify($html);}

		return $html;
	
	}

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

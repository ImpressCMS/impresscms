<?php
/**
*All BB codes allowed in the site are generated through here.
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/

/**
 * Class to "clean up" text for various uses
 *
 * <b>Singleton</b>
 *
 * @package		kernel
 * @subpackage	core
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @author      Goghs Cheng
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class MyTextSanitizer
{
	/**
	* @var	array
	*/
	var $smileys = array();
	/**
	*
	*/
	var $censorConf;
	/**
	* Constructor of this class
	* Gets allowed html tags from admin config settings
	* <br> should not be allowed since nl2br will be used
	* when storing data.
    	*
    	* @access	private
    	*
    	* @todo Sofar, this does nuttin' ;-)
	**/
	function MyTextSanitizer()
    	{
	}

	function html_purifier($text, $config = 'system-global')
	{
		include_once ICMS_ROOT_PATH.'/class/icms.htmlpurifier.php';
		$html_purifier = &icms_HTMLPurifier::getPurifierInstance();

		if($config = 'system-global')
		{
			$text = $html_purifier->icms_html_purifier($text, 'system-global');
		}
		elseif($config = 'display')
		{
			$text = $html_purifier->displayHTMLarea($text, 'display');
		}
		elseif($config = 'preview')
		{
			$text = $html_purifier->previewHTMLarea($text, 'preview');
		}
		elseif($config = 'system-basic')
		{
			$text = $html_purifier->icms_html_purifier($text, 'system-basic');
		}

		return $text;
	}

	/**
	* Access the only instance of this class
     	*
     	* @return	object
     	*
     	* @static
     	* @staticvar   object
	*/
	function &getInstance()
	{
		static $instance;
		if(!isset($instance))
		{
			$instance = new MyTextSanitizer();
		}
		return $instance;
	}

	/**
	* Get the smileys
     	*
     	* @return	array
	*/
	function getSmileys($all=0)
	{
		if(count($this->smileys) == 0)
		{
			if($getsmiles = $GLOBALS["xoopsDB"]->query("SELECT * FROM ".$GLOBALS["xoopsDB"]->prefix("smiles").(!$all?" WHERE display='1'":'')))
			{
				while($smiles = $GLOBALS["xoopsDB"]->fetchArray($getsmiles))
				{
					array_push($this->smileys, $smiles);
				}
			}
		}
		return $this->smileys;
	}

    	/**
     	* Replace emoticons in the message with smiley images
     	*
     	* @param	string  $message
     	* @return	string
     	*/
    	function smiley($message)
	{
		$smileys = $this->getSmileys();
		foreach($smileys as $smile)
		{
			$message = str_replace($smile['code'], '<img src="'.ICMS_UPLOAD_URL.'/'.htmlspecialchars($smile['smile_url']).'" alt="" />', $message);
		}
		return $message;
	}

	/**
	* Make links in the text clickable
	*
	* @param   string  $text
	* @return  string
	**/
	function makeClickable(&$text)
	{
		$config_handler =& xoops_gethandler('config');
		$xoopsConfigPersona =& $config_handler->getConfigsByCat(XOOPS_CONF_PERSONA);
        	if($xoopsConfigPersona['shorten_url'] == 1)
		{
			$text = ' '.$text;
			$patterns = array("/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([^, \r\n\"\(\)'<>\[\]]+)/i");
			$replacements = array("\\1<a href=\"\\2://\\3\" rel=\"external\">\\2://\\3</a>", "\\1<a href=\"http://www.\\2.\\3\" rel=\"external\">www.\\2.\\3</a>", "\\1<a href=\"ftp://ftp.\\2.\\3\" rel=\"external\">ftp.\\2.\\3</a>", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>");
			$text = preg_replace($patterns, $replacements, $text);

			$links = explode('<a', $text);
			$countlinks = count($links);
   			for($i = 0; $i < $countlinks; $i++)
			{
				$link = $links[$i];
				$link = (preg_match('#(.*)(href=")#is', $link)) ? '<a'.$link : $link;
				$begin = strpos($link, '>') + 1;
				$end = strpos($link, '<', $begin);
				$length = $end - $begin;
				$urlname = substr($link, $begin, $length);

				$maxlength = intval($xoopsConfigPersona['max_url_long']);
				$cutlength = intval($xoopsConfigPersona['pre_chars_left']);
				$endlength = -intval($xoopsConfigPersona['last_chars_left']);
				$middleurl = " ... ";
				$chunked = (strlen($urlname) > $maxlength && preg_match('#^(https://|http://|ftp://|www\.)#is', $urlname)) ? substr_replace($urlname, $middleurl, $cutlength, $endlength) : $urlname;
				$text = str_replace('>'.$urlname.'<', '>'.$chunked.'<', $text);
   			}
			$text = substr($text, 1);
			return($text);
		}
		else
		{
			$patterns = array("/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i", "/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([^, \r\n\"\(\)'<>\[\]]+)/i");
			$replacements = array("\\1<a href=\"\\2://\\3\" rel=\"external\">\\2://\\3</a>", "\\1<a href=\"http://www.\\2.\\3\" rel=\"external\">www.\\2.\\3</a>", "\\1<a href=\"ftp://ftp.\\2.\\3\" rel=\"external\">ftp.\\2.\\3</a>", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>");
			return preg_replace($patterns, $replacements, $text);
		}
	}

	/**
	* Replace XoopsCodes with their equivalent HTML formatting
	*
	* @param   string  $text
	* @param   bool    $allowimage Allow images in the text?
     	*                  On FALSE, uses links to images.
	* @return  string
	**/
	function &xoopsCodeDecode(&$text, $allowimage = 1)
	{
		$patterns = array();
		$replacements = array();
		//$patterns[] = "/\[code](.*)\[\/code\]/esU";
		//$replacements[] = "'<div class=\"xoopsCode\"><code><pre>'.wordwrap(MyTextSanitizer::htmlSpecialChars('\\1'), 100).'</pre></code></div>'";
		// RMV: added new markup for intrasite url (allows easier site moves)
		// TODO: automatically convert other URLs to this format if ICMS_URL matches??
		$config_handler =& xoops_gethandler('config');
		$xoopsConfigPersona =& $config_handler->getConfigsByCat(XOOPS_CONF_PERSONA);
        	if($xoopsConfigPersona['use_hidden'] == 1)
		{
        		$patterns[] = "/\[hide](.*)\[\/hide\]/sU";
			if($_SESSION['xoopsUserId'])
			{
				$replacements[] = _HIDDENC.'<div class="xoopsQuote">\\1</div>';
			}
			else
			{
				$replacements[] = _HIDDENC.'<div class="xoopsQuote">'._HIDDENTEXT.'</div>';
			}
		}
		else
		{
        		$patterns[] = "/\[hide](.*)\[\/hide\]/sU";
			$replacements[] = '\\1';
		}
		$patterns[] = "/\[siteurl=(['\"]?)([^\"'<>]*)\\1](.*)\[\/siteurl\]/sU";
		$replacements[] = '<a href="'.ICMS_URL.'/\\2">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)(http[s]?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" rel="external">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)(ftp?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" rel="external">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)([^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="http://\\2" rel="external">\\3</a>';
		$patterns[] = "/\[color=(['\"]?)([a-zA-Z0-9]*)\\1](.*)\[\/color\]/sU";
		$replacements[] = '<span style="color: #\\2;">\\3</span>';
		$patterns[] = "/\[size=(['\"]?)([a-z0-9-]*)\\1](.*)\[\/size\]/sU";
		$replacements[] = '<span style="font-size: \\2;">\\3</span>';
		$patterns[] = "/\[font=(['\"]?)([^;<>\*\(\)\"']*)\\1](.*)\[\/font\]/sU";
		$replacements[] = '<span style="font-family: \\2;">\\3</span>';
		$patterns[] = "/\[email]([^;<>\*\(\)\"']*)\[\/email\]/sU";
		$replacements[] = '<a href="mailto:\\1">\\1</a>';
		$patterns[] = "/\[b](.*)\[\/b\]/sU";
		$replacements[] = '<b>\\1</b>';
		$patterns[] = "/\[i](.*)\[\/i\]/sU";
		$replacements[] = '<i>\\1</i>';
		$patterns[] = "/\[u](.*)\[\/u\]/sU";
		$replacements[] = '<u>\\1</u>';
		$patterns[] = "/\[d](.*)\[\/d\]/sU";
		$replacements[] = '<del>\\1</del>';
    	$patterns[] = "/\[center](.*)\[\/center\]/sU";
		$replacements[] = '<div align=center>\\1</div>';
    	$patterns[] = "/\[left](.*)\[\/left\]/sU";
		$replacements[] = '<div align=left>\\1</div>';
    	$patterns[] = "/\[right](.*)\[\/right\]/sU";
		$replacements[] = '<div align=right>\\1</div>';
    	$patterns[] = "/\[img align=center](.*)\[\/img\]/sU";
		if($allowimage != 1)
		{
			$replacements[] = '<div align=center><a href="\\1" rel="external">\\1</a></div>';
		}
		else
		{
			$replacements[] = '<div align=center><img src="\\1" alt="" /></div>';
		}
		$patterns[] = "/\[img align=(['\"]?)(left|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img align=(['\"]?)(left|right)\\1 id=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img id=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		if($allowimage != 1)
		{
			$replacements[] = '<a href="\\3" rel="external">\\3</a>';
			$replacements[] = '<a href="\\1" rel="external">\\1</a>';
			$replacements[] = '<a href="'.ICMS_URL.'/image.php?id=\\4" rel="external">\\5</a>';
			$replacements[] = '<a href="'.ICMS_URL.'/image.php?id=\\2" rel="external">\\3</a>';
		}
		else
		{
			$replacements[] = '<img src="\\3" align="\\2" alt="" />';
			$replacements[] = '<img src="\\1" alt="" />';
			$replacements[] = '<img src="'.ICMS_URL.'/image.php?id=\\4" align="\\2" alt="\\5" />';
			$replacements[] = '<img src="'.ICMS_URL.'/image.php?id=\\2" alt="\\3" />';
		}
		$patterns[] = "/\[quote]/sU";
		$replacements[] = _QUOTEC.'<div class="xoopsQuote"><blockquote>';
		$patterns[] = "/\[\/quote]/sU";
		$replacements[] = '</blockquote></div>';
		$text = str_replace( "\x00", "", $text );
		$c = "[\x01-\x1f]*";
		$patterns[] = "/j{$c}a{$c}v{$c}a{$c}s{$c}c{$c}r{$c}i{$c}p{$c}t{$c}:/si";
		$replacements[] = "(script removed)";
		$patterns[] = "/a{$c}b{$c}o{$c}u{$c}t{$c}:/si";
		$replacements[] = "about :";
		$text = preg_replace($patterns, $replacements, $text);
		return $text;
	}

/**
     * Filters out invalid strings included in URL, if any
     *
     * @param   array  $matches
     * @return  string
     */
    function _filterImgUrl($matches)
    {
        if ($this->checkUrlString($matches[2])) {
            return $matches[0];
        } else {
            return "";
        }
    }

    /**
     * Checks if invalid strings are included in URL
     *
     * @param   string  $text
     * @return  bool
     */
    function checkUrlString($text)
    {
        // Check control code
        if (preg_match("/[\0-\31]/", $text)) {
            return false;
        }
        // check black pattern(deprecated)
        return !preg_match("/^(javascript|vbscript|about):/i", $text);
    }

	/**
	* Convert linebreaks to <br /> tags
     	*
     	* @param	string  $text
     	* @return	string
	*/
	function nl2Br($text)
	{
		return preg_replace("/(\015\012)|(\015)|(\012)/","<br />",$text);
	}

	/**
	* Add slashes to the text if magic_quotes_gpc is turned off.
	*
	* @param   string  $text
	* @return  string
	**/
	function addSlashes($text)
	{
		if(!get_magic_quotes_gpc())
		{
			$text = addslashes($text);
		}
		return $text;
	}
	/**
	* if magic_quotes_gpc is on, stirip back slashes
    	*
    	* @param	string  $text
    	* @return	string
	**/
	function stripSlashesGPC($text)
	{
		if(get_magic_quotes_gpc())
		{
			$text = stripslashes($text);
		}
		return $text;
	}

	/**
	*  for displaying data in html textbox forms
    	*
    	* @param	string  $text
    	* @return	string
	**/
	function htmlSpecialChars($text)
	{
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"), array('&', '&amp;nbsp;'), @htmlspecialchars($text, ENT_QUOTES, _CHARSET));
	}

	/**
	* Reverses {@link htmlSpecialChars()}
	*
	* @param   string  $text
	* @return  string
	**/
	function undoHtmlSpecialChars($text) // not needed with PHP 5.1, use htmlspecialchars_decode() instead
	{
		return htmlspecialchars_decode($text, ENT_NOQUOTES);
	}

	function icms_htmlEntities($text)
	{
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"), array('&', '&amp;nbsp;'), @htmlentities($text, ENT_QUOTES, _CHARSET));
	}

	/**
	* Filters textarea form data in DB for display
	*
	* @param   string  $text
	* @param   bool    $html   allow html?
	* @param   bool    $smiley allow smileys?
	* @param   bool    $xcode  allow xoopscode?
	* @param   bool    $image  allow inline images?
	* @param   bool    $br     convert linebreaks?
	* @return  string
	**/
	function &displayTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1, $config = 'display')
	{
		// ################# Preload Trigger beforeDisplayTarea ##############
		global $icmsPreloadHandler;
		$icmsPreloadHandler->triggerEvent('beforeDisplayTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		if($html != 1)
		{
			$text = $this->htmlSpecialChars($text);
		}

		$text = $this->codePreConv($text, $xcode); // Ryuji_edit(2003-11-18)
		$text = $this->makeClickable($text);
		if($smiley != 0)
		{
			$text = $this->smiley($text);
		}
		if($xcode != 0)
		{
			if($image != 0)
			{
				$text = $this->xoopsCodeDecode($text);
			}
			else
			{
				$text = $this->xoopsCodeDecode($text, 0);
			}
		}
		if($br != 0)
		{
			$text = $this->nl2Br($text);
		}
		$text = $this->codeConv($text, $xcode, $image);	// Ryuji_edit(2003-11-18)
		if($html != 0)
		{
			$text = $this->html_purifier($text, $config);
		}
		// ################# Preload Trigger afterDisplayTarea ##############
		global $icmsPreloadHandler;
		$icmsPreloadHandler->triggerEvent('afterDisplayTarea', array(&$text, $html, $smiley, $xcode, $image, $br));
		return $text;
	}

	/**
	* Filters textarea form data submitted for preview
	*
	* @param   string  $text
	* @param   bool    $html   allow html?
	* @param   bool    $smiley allow smileys?
	* @param   bool    $xcode  allow xoopscode?
	* @param   bool    $image  allow inline images?
	* @param   bool    $br     convert linebreaks?
	* @return  string
	**/
	function &previewTarea($text, $html = 0, $smiley = 1, $xcode = 1, $image = 1, $br = 1, $config = 'preview')
	{
		// ################# Preload Trigger beforePreviewTarea ##############
		global $icmsPreloadHandler;
		$icmsPreloadHandler->triggerEvent('beforePreviewTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		$text = $this->stripSlashesGPC($text);
		if($html != 1)
		{
			$text = $this->htmlSpecialChars($text);
		}

		$text = $this->codePreConv($text, $xcode); // Ryuji_edit(2003-11-18)
		$text = $this->makeClickable($text);
		if($smiley != 0)
		{
			$text = $this->smiley($text);
		}
		if($xcode != 0)
		{
			if($image != 0)
			{
				$text = $this->xoopsCodeDecode($text);
			}
			else
			{
				$text = $this->xoopsCodeDecode($text, 0);
			}
		}
		if($br != 0)
		{
			$text = $this->nl2Br($text);
		}
		$text = $this->codeConv($text, $xcode, $image);	// Ryuji_edit(2003-11-18)
		if($html != 0)
		{
			$text = $this->html_purifier($text, $config);
		}

		// ################# Preload Trigger afterPreviewTarea ##############
		global $icmsPreloadHandler;
		$icmsPreloadHandler->triggerEvent('afterPreviewTarea', array(&$text, $html, $smiley, $xcode, $image, $br));

		return $text;
	}

	/**
	* Replaces banned words in a string with their replacements
	*
	* @param   string $text
	* @return  string
	* @deprecated
	**/
	function &censorString(&$text)
	{
		if(!isset($this->censorConf))
		{
			$config_handler =& xoops_gethandler('config');
			$this->censorConf =& $config_handler->getConfigsByCat(XOOPS_CONF_CENSOR);
		}
		if($this->censorConf['censor_enable'] == 1)
		{
			$replacement = $this->censorConf['censor_replace'];
			foreach($this->censorConf['censor_words'] as $bad)
			{
				if(!empty($bad))
				{
	 				$bad = quotemeta($bad);
					$patterns[] = "/(\s)".$bad."/siU";
					$replacements[] = "\\1".$replacement;
					$patterns[] = "/^".$bad."/siU";
					$replacements[] = $replacement;
					$patterns[] = "/(\n)".$bad."/siU";
					$replacements[] = "\\1".$replacement;
					$patterns[] = "/]".$bad."/siU";
					$replacements[] = "]".$replacement;
					$text = preg_replace($patterns, $replacements, $text);
				}
   			}
		}
   		return $text;
	}

	/**#@+
	* Sanitizing of [code] tag
	*/
	function codePreConv($text, $xcode = 1)
	{
		if($xcode != 0)
		{
			$patterns = "/\[code](.*)\[\/code\]/esU";
			$replacements = "'[code]'.base64_encode('$1').'[/code]'";
			$text =  preg_replace($patterns, $replacements, $text);
		}
		return $text;
	}

	function codeConv($text, $xcode = 1, $image = 1)
	{
		if($xcode != 0)
		{
			$patterns = "/\[code](.*)\[\/code\]/esU";
			if($image != 0)
			{
				$replacements = "'<div class=\"xoopsCode\"><code><pre>'.MyTextSanitizer::codeSanitizer('$1').'</pre></code></div>'";
			}
			else
			{
				$replacements = "'<div class=\"xoopsCode\"><code><pre>'.MyTextSanitizer::codeSanitizer('$1', 0).'</pre></code></div>'";
			}
			$text = preg_replace($patterns, $replacements, $text);
		}
		return $text;
	}

	function codeSanitizer($str, $image = 1)
	{
		if($image != 0)
		{
			$str = $this->xoopsCodeDecode($this->htmlSpecialChars(str_replace('\"', '"', base64_decode($str))));
		}
		else
		{
			$str = $this->xoopsCodeDecode($this->htmlSpecialChars(str_replace('\"', '"', base64_decode($str))),0);
		}
		return $str;
	}

	/**#@-*/
##################### Deprecated Methods ######################

	/**#@+
	* @deprecated
	*/
	function sanitizeForDisplay($text, $allowhtml = 0, $smiley = 1, $bbcode = 1)
	{
		if($allowhtml == 0)
		{
			$text = $this->htmlSpecialChars($text);
		}
		else
		{
			$text = $this->makeClickable($text);
		}
		if($smiley == 1)
		{
			$text = $this->smiley($text);
		}
		if($bbcode == 1)
		{
			$text = $this->xoopsCodeDecode($text);
		}
		$text = $this->nl2Br($text);
		return $text;
	}

	function sanitizeForPreview($text, $allowhtml = 0, $smiley = 1, $bbcode = 1)
	{
		$text = $this->oopsStripSlashesGPC($text);
		if($allowhtml == 0)
		{
			$text = $this->htmlSpecialChars($text);
		}
		else
		{
			$text = $this->makeClickable($text);
		}
		if($smiley == 1)
		{
			$text = $this->smiley($text);
		}
		if($bbcode == 1)
		{
			$text = $this->xoopsCodeDecode($text);
		}
		$text = $this->nl2Br($text);
		return $text;
	}

	function makeTboxData4Save($text)
	{
		return $this->addSlashes($text);
	}

	function makeTboxData4Show($text, $smiley=0)
	{
		$text = $this->htmlSpecialChars($text);
		return $text;
	}

	function makeTboxData4Edit($text)
	{
		return $this->htmlSpecialChars($text);
	}

	function makeTboxData4Preview($text, $smiley=0)
	{
		$text = $this->stripSlashesGPC($text);
		$text = $this->htmlSpecialChars($text);
		return $text;
	}

	function makeTboxData4PreviewInForm($text)
	{
		$text = $this->stripSlashesGPC($text);
		return $this->htmlSpecialChars($text);
	}

	function makeTareaData4Save($text)
	{
		return $this->addSlashes($text);
	}

	function &makeTareaData4Show(&$text, $html=0, $smiley=1, $xcode=1)
	{
		$text = $this->displayTarea($text, $html, $smiley, $xcode);
		return $text;
	}

	function makeTareaData4Edit($text)
	{
		return $this->htmlSpecialChars($text);
	}

	function &makeTareaData4Preview(&$text, $html=0, $smiley=1, $xcode=1)
	{
		$text = $this->previewTarea($text, $html, $smiley, $xcode);
		return $text;
	}

	function makeTareaData4PreviewInForm($text)
	{
		$text = $this->stripSlashesGPC($text);
		return $this->htmlSpecialChars($text);
	}

	function makeTareaData4InsideQuotes($text)
	{
		return $this->htmlSpecialChars($text);
	}

	function oopsStripSlashesGPC($text)
	{
		return $this->stripSlashesGPC($text);
	}

	function oopsStripSlashesRT($text)
	{
		if(get_magic_quotes_runtime())
		{
			$text = stripslashes($text);
		}
		return $text;
	}

	function oopsAddSlashes($text)
	{
		return $this->addSlashes($text);
	}

	function oopsHtmlSpecialChars($text)
	{
		return $this->htmlSpecialChars($text);
	}

	function oopsNl2Br($text)
	{
		return $this->nl2br($text);
	}
	/**#@-*/
}
?>
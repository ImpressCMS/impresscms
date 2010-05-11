<?php
/**
 * Class to filter Data
 * @package      kernel
 * @subpackage   core
 * @since        1.3
 * @author       vaughan montgomery (vaughan@impresscms.org)
 * @author       ImpressCMS Project
 * @copyright    (c) 2007-2010 The ImpressCMS Project - www.impresscms.org
 * @version      $Id$
 **/
class icms_DataFilter
{
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

	function __construct()
	{
	}

	/**
	 * Access the only instance of this class
	 * @return       object
	 * @static       $DataFilter_instance
	 * @staticvar    object
	 **/
	public static function getInstance()
	{
		static $instance;
		if(!isset($instance))
		{
			$instance = new icms_DataFilter();
		}
		return $instance;
	}

	/**
	* Starts HTML Purifier (from icms_HTMLPurifier class)
	*
	* @param	string	$html	Text to purify
	* @return	string	$html	the purified text
	*/
	public function html_purifier($html)
	{
		if($icmsConfigPurifier['enable_purifier'] !== 0)
		{
			include_once ICMS_ROOT_PATH.'/class/icms_HTMLPurifier.php';
			$html_purifier = &icms_HTMLPurifier::getPurifierInstance();

			$html = $html_purifier->icms_html_purifier($html);

			return $html;
		}
		else
		{
			return $html;
		}
	}

	// -------- Public Functions --------

	/*
	 * Public Function checks if email is of correct formatting
	 *
	 * @param string     $email      The email address
	 * @param string     $antispam   Generate an email address that is protected from spammers
	 * @return string    $email      The generated email address
	 */
	public function icms_CheckEmail($email, $antispam = false)
	{
		if(!$email)
		{
			return false;
		}
		return $this->icms_FilterEmail($email, $antispam);
	}

	/*
	 * Public Function checks if URL is valid & clean
	 *
	 * @copyright The ImpressCMS Project <http://www.impresscms.org>
	 *
	 * @param string  $url  The URL to check
	 * @param string  $options   Options to use during validation check
	 *        valid options are  'scheme' > URL must be an RFC compliant URL (like http://example)
	 *                           'host' > URL must include host name (like http://www.example.com)
	 *                           'path' > URL must have a path after the domain name (like www.example.com/example1/)
	 *                           'query' > URL must have a query string (like "example.php?name=Vaughan&age=34")
	 * @param string  $encode    Whether to URLEncode the URL or not (ie. http://www.example > http%3A%2F%2Fwww.example)
	 *
	 * @return string  $url  The validated URL
	 */
	public function icms_CheckURL($url, $options = '', $encode = false)
	{
		if(!$url)
		{
			return false;
		}
		return $this->icms_FilterURL($url, $options, $encode);
	}

	/**
	* Get the smileys
	*
	* @param	bool	$all
	* @return   array
	*/
	public function getSmileys($all = false)
	{
		return $this->icms_getSmileys($all);
	}


	/**
	 * Replace emoticons in the message with smiley images
	 *
	 * @param	string  $message
	 * @return   string
	 */
	public function smiley($message)
	{
		$smileys = $this->getSmileys(true);
		foreach($smileys as $smile)
		{
			$message = str_replace($smile['code'],
				'<img src="'.ICMS_UPLOAD_URL.'/'.htmlspecialchars($smile['smile_url']).'" alt="" />', $message);
		}
		return $message;
	}

	/**
	 * Make links in the text clickable
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	public function makeClickable(&$text)
	{
		global $icmsConfigPersona;
		$text = ' '.$text;
		$patterns = array(
			"/(^|[^]_a-z0-9-=\"'\/])([a-z]+?):\/\/([^, \r\n\"\(\)'<>]+)/i",
			"/(^|[^]_a-z0-9-=\"'\/])www\.([a-z0-9\-]+)\.([^, \r\n\"\(\)'<>]+)/i",
			"/(^|[^]_a-z0-9-=\"'\/])ftp\.([a-z0-9\-]+)\.([^,\r\n\"\(\)'<>]+)/i"
			/*,	"/(^|[^]_a-z0-9-=\"'\/:\.])([a-z0-9\-_\.]+?)@([^, \r\n\"\(\)'<>\[\]]+)/i"*/
		);
		$replacements = array(
			"\\1<a href=\"\\2://\\3\" rel=\"external\">\\2://\\3</a>",
			"\\1<a href=\"http://www.\\2.\\3\" rel=\"external\">www.\\2.\\3</a>",
			"\\1<a href=\"ftp://ftp.\\2.\\3\" rel=\"external\">ftp.\\2.\\3</a>"
			/*,	"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"*/
		);
		$text = preg_replace($patterns, $replacements, $text);
		if($icmsConfigPersona['shorten_url'] == 1)
		{
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

				$maxlength = (int) ($icmsConfigPersona['max_url_long']);
				$cutlength = (int) ($icmsConfigPersona['pre_chars_left']);
				$endlength = - (int) ($icmsConfigPersona['last_chars_left']);
				$middleurl = " ... ";
				$chunked = (strlen($urlname) > $maxlength && preg_match('#^(https://|http://|ftp://|www\.)#is',
				$urlname)) ? substr_replace($urlname, $middleurl, $cutlength, $endlength) : $urlname;
				$text = str_replace('>'.$urlname.'<', '>'.$chunked.'<', $text);
			}
			$text = substr($text, 1);
		}
		return($text);
	}

	/**
	 * Filters out invalid strings included in URL, if any
	 *
	 * @param   array  $matches
	 * @return  string
	 */
	public function _filterImgUrl($matches)
	{
		if($this->checkUrlString($matches[2]))
		{
			return $matches[0];
		}
		else
		{
			return '';
		}
	}

	/**
	 * Checks if invalid strings are included in URL
	 *
	 * @param   string  $text
	 * @return  bool
	 */
	public function checkUrlString($text)
	{
		// Check control code
		if(preg_match("/[\0-\31]/", $text))
		{
			return false;
		}
		// check black pattern(deprecated)
		return !preg_match("/^(javascript|vbscript|about):/i", $text);
	}

	/**
	 * Convert linebreaks to <br /> tags
	 *
	 * @param	string  $text
	 * @return   string
	 */
	public function nl2Br($text)
	{
		return preg_replace("/(\015\012)|(\015)|(\012)/", "<br />", $text);
	}

	/**
	 * Add slashes to the text if magic_quotes_gpc is turned off.
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	public function addSlashes($text)
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
	 * @return   string
	 **/
	public function stripSlashesGPC($text)
	{
		if(get_magic_quotes_gpc())
		{
			$text = stripslashes($text);
		}
		return $text;
	}

	/**
	 * for displaying data in html textbox forms
	 *
	 * @param	string  $text
	 * @return   string
	 **/
	public function htmlSpecialChars($text)
	{
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"), array('&', '&amp;nbsp;'),
		@htmlspecialchars($text, ENT_QUOTES, _CHARSET));
	}

	/**
	 * Reverses {@link htmlSpecialChars()}
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	public function undoHtmlSpecialChars($text)
	{
		return htmlspecialchars_decode($text, ENT_QUOTES);
	}

	public function icms_htmlEntities($text)
	{
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"), array('&', '&amp;nbsp;'),
		@htmlentities($text, ENT_QUOTES, _CHARSET));
	}

	/**
	* Replace icmsCodes with their equivalent HTML formatting
	*
	* @param	string	$text
	* @param	bool	$allowimage Allow images in the text?
	*					On FALSE, uses links to images.
	* @return	string
	**/
	public function icmsCodeDecode(&$text, $allowimage = 1)
	{
		$patterns = array();
		$replacements = array();
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
		$replacements[] = '<strong>\\1</strong>';
		$patterns[] = "/\[i](.*)\[\/i\]/sU";
		$replacements[] = '<em>\\1</em>';
		$patterns[] = "/\[u](.*)\[\/u\]/sU";
		$replacements[] = '<u>\\1</u>';
		$patterns[] = "/\[d](.*)\[\/d\]/sU";
		$replacements[] = '<del>\\1</del>';
		$patterns[] = "/\[center](.*)\[\/center\]/sU";
		$replacements[] = '<div align="center">\\1</div>';
		$patterns[] = "/\[left](.*)\[\/left\]/sU";
		$replacements[] = '<div align="left">\\1</div>';
		$patterns[] = "/\[right](.*)\[\/right\]/sU";
		$replacements[] = '<div align="right">\\1</div>';
		$patterns[] = "/\[img align=center](.*)\[\/img\]/sU";
		if($allowimage != 1)
		{
			$replacements[] = '<div style="margin: 0 auto; text-align: center;"><a href="\\1" rel="external">\\1</a></div>';
		}
		else
		{
			$replacements[] = '<div style="margin: 0 auto; text-align: center;"><img src="\\1" alt="" /></div>';
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
		$replacements[] = _QUOTEC.'<div class="xoopsQuote"><blockquote><p>';
		$patterns[] = "/\[\/quote]/sU";
		$replacements[] = '</p></blockquote></div>';
		$text = str_replace( "\x00", "", $text );
		$c = "[\x01-\x1f]*";
		$patterns[] = "/j{$c}a{$c}v{$c}a{$c}s{$c}c{$c}r{$c}i{$c}p{$c}t{$c}:/si";
		$replacements[] = "(script removed)";
		$patterns[] = "/a{$c}b{$c}o{$c}u{$c}t{$c}:/si";
		$replacements[] = "about :";
		$text = preg_replace($patterns, $replacements, $text);
		$text = $this->icmsCodeDecode_extended($text);

		return $text;
	}

	/**
	 * Replaces banned words in a string with their replacements
	 *
	 * @param   string $text
	 * @return  string
	 * @deprecated
	 **/
	public function censorString(&$text)
	{
		$config_handler = xoops_gethandler('config');
		$icmsConfigCensor =& $config_handler->getConfigsByCat(ICMS_CONF_CENSOR);
		if($icmsConfigCensor['censor_enable'] == true)
		{
			$replacement = $icmsConfigCensor['censor_replace'];
			if(!empty($icmsConfigCensor['censor_words']))
			{
				foreach($icmsConfigCensor['censor_words'] as $bad)
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
		}
		return $text;
	}

	/**#@+
	 * Sanitizing of [code] tag
	 */
	public function codePreConv($text, $icode = 1)
	{
		if($icode != 0)
		{
			$patterns = "/\[code](.*)\[\/code\]/esU";
			$replacements = "'[code]'.base64_encode('$1').'[/code]'";
			$text = preg_replace($patterns, $replacements, $text);
		}
		return $text;
	}

	/**
	 * Converts text to icode
	 *
	 * @param	 string	$text	 Text to convert
	 * @param	 int	   $xcode	Is the code icode?
	 * @param	 int	   $image	configuration for the purifier
	 * @return	string	$text	 the converted text
	 */
	public function codeConv($text, $icode = 1, $image = 1)
	{
		if($icode != 0)
		{
			$patterns = "/\[code](.*)\[\/code\]/esU";
			if($image != 0)
			{
				$replacements = "'<div class=\"icmsCode\">'.icms_DataFilter::textsanitizer_syntaxhighlight(icms_DataFilter::codeSanitizer('$1')).'</div>'";
			}
			else
			{
				$replacements = "'<div class=\"icmsCode\">'.icms_DataFilter::textsanitizer_syntaxhighlight(icms_DataFilter::codeSanitizer('$1',0)).'</div>'";
			}
			$text = preg_replace($patterns, $replacements, $text);
		}
		return $text;
	}

	/**
	 * Sanitizes decoded string
	 *
	 * @param   string	$str	  String to sanitize
	 * @param   string	$image	Is the string an image
	 * @return  string	$str	  The sanitized decoded string
	 */
	public function codeSanitizer($str, $image = 1)
	{
		$str = $this->htmlSpecialChars(str_replace('\"', '"', base64_decode($str)));
		$str = $this->icmsCodeDecode($str, $image);
		return $str;
	}

	/*
	 * This function gets allowed plugins from DB and loads them in the sanitizer
	 * @param	int	 $id			 ID of the config
	 * @param	bool	$withoptions	load the config's options now?
	 * @return	object  reference to the {@link XoopsConfig}
	 */
	public function icmsCodeDecode_extended($text, $allowimage = 1)
	{
		global $icmsConfigPlugins;
		if(!empty($icmsConfigPlugins['sanitizer_plugins']))
		{
			foreach($icmsConfigPlugins['sanitizer_plugins'] as $item)
			{
				$text = $this->icmsExecuteExtension($item, $text);
			}
		}
		return $text;
	}

	/**
	 * Loads Extension from Plugins Folder if not already loaded
	 *
	 * @param	 string	$name	 Name of the extension to load
	 * @return	bool
	 */
	public function icmsloadExtension($name)
	{
		if(empty($name) || !include_once ICMS_ROOT_PATH."/plugins/textsanitizer/{$name}/{$name}.php")
		{
			return false;
		}
	}

	/**
	 * Executes file with a certain extension using call_user_func_array
	 *
	 * @param	 string	$name	 Name of the file to load
	 * @param	 string	$text	 Text to show if the function doesn't exist
	 * @return	array	 the return of the called function
	 */
	public function icmsExecuteExtension($name, $text)
	{
		$this->icmsloadExtension($name);
		$func = "textsanitizer_{$name}";
		if(!function_exists($func))
		{
			return $text;
		}
		$args = array_slice(func_get_args(), 1);
		return call_user_func_array($func, array_merge(array(&$this), $args));
	}

	/**
	 * Syntaxhighlight the code
	 *
	 * @param	 string	$text	 purifies (lightly) and then syntax highlights the text
	 * @return	string	$text	 the syntax highlighted text
	 */
	public function textsanitizer_syntaxhighlight(&$text)
	{
		global $icmsConfigPlugins;
		if($icmsConfigPlugins['code_sanitizer'] == 'php')
		{
			$text = $this->undoHtmlSpecialChars($text);
			$text = $this->textsanitizer_php_highlight($text);
		}
		elseif($icmsConfigPlugins['code_sanitizer'] == 'geshi')
		{
			$text = $this->undoHtmlSpecialChars($text);
			$text = '<code>'.$this->textsanitizer_geshi_highlight($text).'</code>';
		}
		else
		{
			$text = '<pre><code>'.$text.'</code></pre>';
		}
	    return $text;
	}

	/**
	 * Syntaxhighlight the code using PHP highlight
	 *
	 * @param	 string	$text	 Text to highlight
	 * @return	string	$buffer   the highlighted text
	 */
	function textsanitizer_php_highlight($text)
	{
		$text = trim($text);
		$addedtag_open = 0;
		if(!strpos($text, '<?php') and (substr($text, 0, 5) != '<?php'))
		{
			$text = "<?php\n".$text;
			$addedtag_open = 1;
		}
		$addedtag_close = 0;
		if(!strpos($text, '?>'))
		{
			$text .= '?>';
			$addedtag_close = 1;
		}
		$oldlevel = error_reporting(0);
		$buffer = highlight_string($text, true);
		error_reporting($oldlevel);
		$pos_open = $pos_close = 0;
		if($addedtag_open)
		{
			$pos_open = strpos($buffer, '&lt;?php');
		}
		if($addedtag_close)
		{
			$pos_close = strrpos($buffer, '?&gt;');
		}

		$str_open = ($addedtag_open) ? substr($buffer, 0, $pos_open) : '';
		$str_close = ($pos_close) ? substr($buffer, $pos_close + 5) : '';

		$length_open = ($addedtag_open) ? $pos_open + 8 : 0;
		$length_text = ($pos_close) ? $pos_close - $length_open : 0;
		$str_internal = ($length_text) ? substr($buffer, $length_open, $length_text) : substr($buffer, $length_open);

		$buffer = $str_open.$str_internal.$str_close;
		return $buffer;
	}

	/**
	 * Syntaxhighlight the code using Geshi highlight
	 *
	 * @param	 string	$text	 The text to highlight
	 * @return	string	$code	 the highlighted text
	 */
	function textsanitizer_geshi_highlight($text)
	{
		global $icmsConfigPlugins;

		if(!@include_once ICMS_LIBRARIES_PATH.'/geshi/geshi.php')
		{
			return false;
		}
		$language = str_replace('.php', '', $icmsConfigPlugins['geshi_default']);

		// Create the new GeSHi object, passing relevant stuff
		$geshi = new GeSHi($text, $language);

		// Enclose the code in a <div>
		$geshi->set_header_type(GESHI_HEADER_NONE);

		// Sets the proper encoding charset other than "ISO-8859-1"
		$geshi->set_encoding(_CHARSET);

		$geshi->set_link_target('_blank');

		// Parse the code
		$code = $geshi->parse_code();

		return $code;
	}

	// -------- Private Functions --------

	/*
	 * Private Function checks if URL is valid & clean
	 *
	 * @copyright The ImpressCMS Project <http://www.impresscms.org>
	 *
	 * See public function icms_CheckURL() for parameters
	 *
	 * @return string  $url  The validated URL
	 */
	private function icms_FilterURL($url, $options, $encode)
	{
		$url = filter_var($url, FILTER_SANITIZE_URL);

		if(isset($options) && $options !== '')
		{
			if($options = 'scheme')
			{
				$url = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);
			}
			elseif($options = 'host')
			{
				$url = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
			}
			elseif($options = 'path')
			{
				$url = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
			}
			elseif($options = 'query')
			{
				$url = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED);
			}
			else
			{
				$url = filter_var($url, FILTER_VALIDATE_URL);
			}
		}
		else
		{
			$url = filter_var($url, FILTER_VALIDATE_URL);
		}

		if($encode)
		{
			$url = filter_var($url, FILTER_SANITIZE_ENCODED);
		}

		return $url;
	}

	/*
	 * Private Function checks if email is of correct formatting
	 *
	 * @param string     $email      The email address
	 * @param string     $antispam   Generate an email address that is protected from spammers
	 * @return string    $email      The generated email address
	 */
	private function icms_FilterEmail($email, $antispam)
	{
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return false;
		}
		if($antispam)
		{
			$email = str_replace('@', ' at ', $email);
			$email = str_replace('.', ' dot ', $email);
		}
		return $email;
	}

	/**
	* Get the smileys
	*
	* @param	bool	$all
	* @return   array
	*/
	private function icms_getSmileys($all)
	{
		global $xoopsDB;

		if(count($this->allSmileys) == 0)
		{
			if($result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('smiles')))
			{
				while($smiley = $GLOBALS['xoopsDB']->fetchArray($result))
				{
					if($smiley['display'])
					{
						array_push($this->displaySmileys, $smiley);
					}
					array_push($this->allSmileys, $smiley);
				}
			}
		}
		return $all ? $this->allSmileys : $this->displaySmileys;
	}

}
?>
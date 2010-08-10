<?php
/**
* Class to filter Data
* @package      libraries
* @subpackage   core
* @since        1.3
* @author       vaughan montgomery (vaughan@impresscms.org)
* @author       ImpressCMS Project
* @copyright    (c) 2007-2010 The ImpressCMS Project - www.impresscms.org
* @version      $Id: DataFilter.php 19858 2010-07-15 12:01:13Z m0nty_ $
**/
class icms_core_DataFilter
{
	/**
	* @public	array
	*/
	public $displaySmileys = array();
	/**
	* @public	array
	*/
	public $allSmileys = array();

	public $censorConf;

	public function __construct()
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
			$instance = new icms_core_DataFilter();
		}
		return $instance;
	}

// -------- Public Functions --------

	public function filterDebugInfo($text, $msg)
	{
		echo "<div style='padding: 5px; color: red; font-weight: bold'>$text</div>";
		echo "<div><pre>";
		print_r($msg);
		echo "</pre></div>";
	}

	/**
	* Filters out invalid strings included in URL, if any
	*
	* @param   array  $matches
	* @return  string
	*/
	public function _filterImgUrl($matches) {
		if (self::checkUrlString($matches[2])) {
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
	* Convert linebreaks to <br /> tags
	*
	* @param	string  $text
	* @return   string
	*/
	public function nl2Br($text) {
		return preg_replace("/(\015\012)|(\015)|(\012)/", "<br />", $text);
	}

	/**
	* for displaying data in html textbox forms
	*
	* @param	string  $text
	* @return   string
	**/
	public function htmlSpecialChars($text) {
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"), array('&', '&amp;nbsp;'),
				@htmlspecialchars($text, ENT_QUOTES, _CHARSET));
	}

	/**
	* Reverses {@link htmlSpecialChars()}
	*
	* @param   string  $text
	* @return  string
	**/
	static public function undoHtmlSpecialChars($text) {
		return htmlspecialchars_decode($text, ENT_QUOTES);
	}

	public function htmlEntities($text) {
		return preg_replace(array("/&amp;/i", "/&nbsp;/i"), array('&', '&amp;nbsp;'),
				@htmlentities($text, ENT_QUOTES, _CHARSET));
	}

	/**
	 * Note: magic_quotes_gpc and magic_quotes_runtime are deprecated as of PHP5.3.0
	 *
	 * Add slashes to the text if magic_quotes_gpc is turned off.
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	public function addSlashes($text) {
		if (!get_magic_quotes_gpc()) {
			$text = addslashes($text);
		}
		return $text;
	}

	/**
	 * Note: magic_quotes_gpc and magic_quotes_runtime are deprecated as of PHP5.3.0
	 *		does that mean we can remove this function once 5.3 is minimum req?
	 * if magic_quotes_gpc is on, strip back slashes
	 *
	 * @param	string  $text
	 * @return   string
	 **/
	public function stripSlashesGPC($text) {
		if (get_magic_quotes_gpc()) {
			$text = stripslashes($text);
		}
		return $text;
	}

	/**
	* Starts HTML Filter
	* @TODO		Allow ability to choose what html filter you want.
	 *			ex. HTMLPurifier, HTMLawed
	*
	* @param	string	$html	Text to purify
	* @return	string	$html	the purified text
	*/
	public function html_filter($html)
	{
		if($icmsConfigPurifier['enable_purifier'] !== 0)
		{
			$htmlfilter = &icms_core_HTMLFilter::getInstance();

			$html = $htmlfilter->htmlpurify($html);

			return $html;
		}
		else
		{
			return $html;
		}
	}

	/**
	* Filters Multidimensional Array Recursively removing keys with empty values
	* @param       array     $array       Array to be filtered
	* @return      array     $array
	**/
	public function cleanArray($arr)
	{
		$rtn = array();

		foreach($arr as $key => $a)
		{
			if(!is_array($a) && (!empty($a) || $a === 0))
			{
				$rtn[$key] = $a;
			}
			elseif(is_array($a))
			{
				if(count($a) > 0)
				{
					$a = self::cleanArray($a);
					$rtn[$key] = $a;
					if(count($a) == 0)
					{
						unset($rtn[$key]);
					}
				}
			}
		}
		return $rtn;
	}

	/*
	* Public Function checks Variables using specified filter type
	*
	* @param	string		$data		Data to be checked
	* @param	string		$type		Type of Filter To use for Validation
	*			Valid Filter Types:
	*					'url' = Checks & validates URL
	*					'email' = Checks & validates Email Addresses
	*					'ip' = Checks & validates IP Addresses
	*					'str' = Checks & Sanitizes String Values
	*					'int' = Validates Integer Values
	*					'html' = Validates HTML
	*
	* @param	mixed		$options1	Options to use with specified filter
	*			Valid Filter Options:
	*				URL:
	*					'scheme' = URL must be an RFC compliant URL (like http://example)
	*					'host' = URL must include host name (like http://www.example.com)
	*					'path' = URL must have a path after the domain name (like www.example.com/example1/)
	*					'query' = URL must have a query string (like "example.php?name=Vaughan&age=34")
	*				EMAIL:
	*					'true' = Generate an email address that is protected from spammers
	*					'false' = Generate an email address that is NOT protected from spammers
	*				IP:
	*					'ip4' = Requires the value to be a valid IPv4 IP (like 255.255.255.255)
	*					'ip6' = Requires the value to be a valid IPv6 IP (like 2001:0db8:85a3:08d3:1319:8a2e:0370:7334)
	*					'rfc' = Requires the value to be a RFC specified private range IP (like 192.168.0.1)
	*					'res' = Requires that the value is not within the reserved IP range. both IPV4 and IPV6 values
	*				STR:
	*					'noencode' = Do NOT encode quotes
	*					'strlow' = Strip characters with ASCII value below 32
	*					'strhigh' = Strip characters with ASCII value above 127
	*					'encodelow' = Encode characters with ASCII value below 32
	*					'encodehigh' = Encode characters with ASCII value above 127
	*					'encodeamp' = Encode the & character to &amp;
	*				INT:
	*					minimum integer range value
	*
	* @param	mixed		$options2	Options to use with specified filter options1
	*				URL:
	*					'true' = URLEncode the URL (ie. http://www.example > http%3A%2F%2Fwww.example)
	*					'false' = Do Not URLEncode the URL
	*				EMAIL:
	*					NOT USED!
	*				IP:
	*					NOT USED!
	*				INT:
	*					maximum integer range value
	*
	* @return	mixed
	*/
	public function checkVar($data, $type, $options1 = '', $options2 = '')
	{
		if(!$data || !$type)
		{
			return false;
		}
		$valid_types = array('url', 'email', 'ip', 'str', 'int', 'html');
		if(!in_array($type, $valid_types))
		{
			return false;
		}
		else
		{
			if($type == 'url')
			{
				$valid_options1 = array('scheme', 'path', 'host', 'query');
				$valid_options2 = array(0, 1);

				if(!isset($options1) || $options1 == '' || !in_array($options1, $valid_options1))
				{
					$options1 = '';
				}
				if(!isset($options2) || $options2 == '' || !in_array($options2, $valid_options2))
				{
					$options2 = 0;
				}
				else
				{
					$options2 = 1;
				}
			}

			if($type == 'email')
			{
				$valid_options1 = array(0, 1);
				$options2 = '';

				if(!isset($options1) || $options1 == '' || !in_array($options1, $valid_options1))
				{
					$options1 = 0;
				}
				else
				{
					$options1 = 1;
				}
			}

			if($type == 'ip')
			{
				$valid_options1 = array('ipv4', 'ipv6', 'rfc', 'res');
				$options2 = '';

				if(!isset($options1) || $options1 == '' || !in_array($options1, $valid_options1))
				{
					$options1 = 'ipv4';
				}
			}

			if($type == 'str')
			{
				$valid_options1 = array('noencode', 'striplow', 'striphigh', 'encodelow', 'encodehigh', 'encodeamp');
				$options2 = '';

				if(!isset($options1) || $options1 == '' || !in_array($options1, $valid_options1))
				{
					$options1 = '';
				}
			}

			if($type == 'int')
			{
				if(!is_int($options1) || !is_int($options2))
				{
					$options1 = '';
					$options2 = '';
				}
				else
				{
					$options1 = (int)$options1;
					$options2 = (int)$options2;
				}
			}

			if($type == 'html')
			{
				$options1 = '';
				$options2 = '';
			}
		}

		return self::priv_checkVar($data, $type, $options1, $options2);
	}

	/**
	 * Replace icmsCodes with their equivalent HTML formatting
	 *
	 * @param   string  $text
	 * @param   bool	$allowimage Allow images in the text?
	 *				  On FALSE, uses links to images.
	 * @return  string
	 **/
	public function codeDecode(&$text, $allowimage = 1) {
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
		if ($allowimage != 1) {
			$replacements[] = '<div style="margin: 0 auto; text-align: center;"><a href="\\1" rel="external">\\1</a></div>';
		} else {
			$replacements[] = '<div style="margin: 0 auto; text-align: center;"><img src="\\1" alt="" /></div>';
		}
		$patterns[] = "/\[img align=(['\"]?)(left|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img align=(['\"]?)(left|right)\\1 id=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img id=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		if ($allowimage != 1) {
			$replacements[] = '<a href="\\3" rel="external">\\3</a>';
			$replacements[] = '<a href="\\1" rel="external">\\1</a>';
			$replacements[] = '<a href="'.ICMS_URL.'/image.php?id=\\4" rel="external">\\5</a>';
			$replacements[] = '<a href="'.ICMS_URL.'/image.php?id=\\2" rel="external">\\3</a>';
		} else {
			$replacements[] = '<img src="\\3" align="\\2" alt="" />';
			$replacements[] = '<img src="\\1" alt="" />';
			$replacements[] = '<img src="'.ICMS_URL.'/image.php?id=\\4" align="\\2" alt="\\5" />';
			$replacements[] = '<img src="'.ICMS_URL.'/image.php?id=\\2" alt="\\3" />';
		}
		$patterns[] = "/\[quote]/sU";
		$replacements[] = _QUOTEC.'<div class="icmsQuote"><blockquote><p>';
		$patterns[] = "/\[\/quote]/sU";
		$replacements[] = '</p></blockquote></div>';
		$text = str_replace( "\x00", "", $text );
		$c = "[\x01-\x1f]*";
		$patterns[] = "/j{$c}a{$c}v{$c}a{$c}s{$c}c{$c}r{$c}i{$c}p{$c}t{$c}:/si";
		$replacements[] = "(script removed)";
		$patterns[] = "/a{$c}b{$c}o{$c}u{$c}t{$c}:/si";
		$replacements[] = "about :";
		$text = preg_replace($patterns, $replacements, $text);
		$text = self::codeDecode_extended($text);
		return $text;
	}

	/**
	 * Make links in the text clickable
	 *
	 * @param   string  $text
	 * @return  string
	 **/
	public function makeClickable(&$text) {
		global $icmsConfigPersona;
		$text = ' ' . $text;
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
		if ($icmsConfigPersona['shorten_url'] == 1) {
			$links = explode('<a', $text);
			$countlinks = count($links);
			for ($i = 0; $i < $countlinks; $i++) {
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
		}
		$text = substr($text, 1);
		return $text;
	}

	public function smiley($message)
	{
		return self::priv_smiley($message);
	}

	/**
	 * Replaces banned words in a string with their replacements
	 *
	 * @param   string $text
	 * @return  string
	 *
	 **/
	public function censorString(&$text) {
		$config_handler = icms::handler('icms_config');
		$icmsConfigCensor =& $config_handler->getConfigsByCat(ICMS_CONF_CENSOR);
		if ($icmsConfigCensor['censor_enable'] == true) {
			$replacement = $icmsConfigCensor['censor_replace'];
			if (!empty($icmsConfigCensor['censor_words'])) {
				foreach ($icmsConfigCensor['censor_words'] as $bad) {
					if (!empty($bad)) {
						$bad = quotemeta($bad);
						$patterns[] = "/(\s)" . $bad . "/siU";
						$replacements[] = "\\1" . $replacement;
						$patterns[] = "/^" . $bad . "/siU";
						$replacements[] = $replacement;
						$patterns[] = "/(\n)" . $bad . "/siU";
						$replacements[] = "\\1" . $replacement;
						$patterns[] = "/]" . $bad . "/siU";
						$replacements[] = "]" . $replacement;
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
	public function codePreConv($text, $imcode = 1) {
		if ($imcode != 0) {
			$patterns = "/\[code](.*)\[\/code\]/esU";
			$replacements = "'[code]'.base64_encode('$1').'[/code]'";
			$text = preg_replace($patterns, $replacements, $text);
		}
		return $text;
	}

	/**
	 * Converts text to imcode
	 *
	 * @param	 string	$text	 Text to convert
	 * @param	 int	   $imcode	Is the code Xcode?
	 * @param	 int	   $image	configuration for the purifier
	 * @return	string	$text	 the converted text
	 */
	public function codeConv($text, $imcode = 1, $image = 1) {
		if ($imcode != 0) {
			$patterns = "/\[code](.*)\[\/code\]/esU";
			if ($image != 0) {
				$replacements = "'<div class=\"icmsCode\">' .
					icms_core_DataFilter::textsanitizer_syntaxhighlight(icms_core_DataFilter::codeSanitizer('$1')) .
					'</div>'";
			} else {
				$replacements = "'<div class=\"icmsCode\">' .
					icms_core_DataFilter::textsanitizer_syntaxhighlight(icms_core_DataFilter::codeSanitizer('$1',0)) .
					'</div>'";
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
	public function codeSanitizer($str, $image = 1) {
		$str = self::htmlSpecialChars(str_replace('\"', '"', base64_decode($str)));
		$str = self::codeDecode($str, $image);
		return $str;
	}

	/**
	 * This function gets allowed plugins from DB and loads them in the sanitizer
	 * @param	int	 $id			 ID of the config
	 * @param	bool	$withoptions	load the config's options now?
	 * @return	object  reference to the {@link IcmsConfig}
	 */
	public function codeDecode_extended($text, $allowimage = 1) {
		global $icmsConfigPlugins;
		if (!empty($icmsConfigPlugins['sanitizer_plugins'])) {
			foreach ($icmsConfigPlugins['sanitizer_plugins'] as $item) {
				$text = self::executeExtension($item, $text);
			}
		}
		return $text;
	}

	/**
	 * loads the textsanitizer plugins
	 *
	 * @param	 string	$name	 Name of the extension to load
	 * @return	bool
	 */
	public function loadExtension($name) {
		if (empty($name) || !include_once ICMS_ROOT_PATH . "/plugins/textsanitizer/{$name}/{$name}.php") {
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
	public function executeExtension($name, $text) {
		self::loadExtension($name);
		$func = "textsanitizer_{$name}";
		if (!function_exists($func)) {
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
	public function textsanitizer_syntaxhighlight(&$text) {
		global $icmsConfigPlugins;
		if ($icmsConfigPlugins['code_sanitizer'] == 'php') {
			$text = self::undoHtmlSpecialChars($text);
			$text = self::textsanitizer_php_highlight($text);
		} elseif ($icmsConfigPlugins['code_sanitizer'] == 'geshi') {
			$text = self::undoHtmlSpecialChars($text);
			$text = '<code>' . self::textsanitizer_geshi_highlight($text) . '</code>';
		} else {
			$text = '<pre><code>' . $text . '</code></pre>';
		}
	    return $text;
	}

	/**
	 * Syntaxhighlight the code using PHP highlight
	 *
	 * @param	 string	$text	 Text to highlight
	 * @return	string	$buffer   the highlighted text
	 */
	public function textsanitizer_php_highlight($text) {
		$text = trim($text);
		$addedtag_open = 0;
		if (!strpos($text, '<?php') and (substr($text, 0, 5) != '<?php')) {
			$text = "<?php\n" . $text;
			$addedtag_open = 1;
		}
		$addedtag_close = 0;
		if (!strpos($text, '?>')) {
			$text .= '?>';
			$addedtag_close = 1;
		}
		$oldlevel = error_reporting(0);
		$buffer = highlight_string($text, true);
		error_reporting($oldlevel);
		$pos_open = $pos_close = 0;
		if ($addedtag_open) {
			$pos_open = strpos($buffer, '&lt;?php');
		}
		if ($addedtag_close) {
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
	public function textsanitizer_geshi_highlight($text) {
		global $icmsConfigPlugins;

		if (!@include_once ICMS_LIBRARIES_PATH.'/geshi/geshi.php') {
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
	* Private Function checks & Validates Data
	*
	* @copyright The ImpressCMS Project <http://www.impresscms.org>
	*
	* See public function checkVar() for parameters
	*
	* @return
	*/
	private function priv_checkVar($data, $type, $options1, $options2)
	{
		if($type == 'url')
		{
			$data = filter_var($data, FILTER_SANITIZE_URL);

			if($options1 == 'scheme')
			{
				$opt1 = FILTER_FLAG_SCHEME_REQUIRED;
			}
			elseif($options1 == 'host')
			{
				$opt1 = FILTER_FLAG_HOST_REQUIRED;
			}
			elseif($options1 == 'path')
			{
				$opt1 = FILTER_FLAG_PATH_REQUIRED;
			}
			elseif($options1 == 'query')
			{
				$opt1 = FILTER_FLAG_QUERY_REQUIRED;
			}

			if(isset($opt1) && $opt1 !== '')
			{
				$data = filter_var($data, FILTER_VALIDATE_URL, $opt1);
			}
			else
			{
				$data = filter_var($data, FILTER_VALIDATE_URL);
			}

			if(is_set($options2) && $options2 == 1)
			{
				$data = filter_var($data, FILTER_SANITIZE_ENCODED);
			}

			return $data;
		}

		if($type == 'email')
		{
			$data = filter_var($data, FILTER_SANITIZE_EMAIL);

			if(!filter_var($data, FILTER_VALIDATE_EMAIL))
			{
				return false;
			}
			if(isset($options1) && $options1 == 1)
			{
				$data = str_replace('@', ' at ', $data);
				$data = str_replace('.', ' dot ', $data);
			}

			return $data;
		}

		if($type == 'ip')
		{
			if($options1 == 'ipv4')
			{
				$opt1 = FILTER_FLAG_IPV4;
			}
			elseif($options1 == 'ipv6')
			{
				$opt1 = FILTER_FLAG_IPV6;
			}
			elseif($options1 == 'rfc')
			{
				$opt1 = FILTER_FLAG_NO_PRIV_RANGE;
			}
			elseif($options1 == 'res')
			{
				$opt1 = FILTER_FLAG_NO_RES_RANGE;
			}

			if(isset($opt1) && $opt1 !== '')
			{
				$data = filter_var($data, FILTER_VALIDATE_IP, $opt1);
			}
			else
			{
				$data = filter_var($data, FILTER_VALIDATE_IP);
			}

			return $data;
		}

		if($type == 'str')
		{
			if($options1 == 'noencode')
			{
				$opt1 = FILTER_FLAG_NO_ENCODE_QUOTES;
			}
			elseif($options1 == 'striplow')
			{
				$opt1 = FILTER_FLAG_STRIP_LOW;
			}
			elseif($options1 == 'striphigh')
			{
				$opt1 = FILTER_FLAG_STRIP_HIGH;
			}
			elseif($options1 == 'encodelow')
			{
				$opt1 = FILTER_FLAG_ENCODE_LOW;
			}
			elseif($options1 == 'encodehigh')
			{
				$opt1 = FILTER_FLAG_ENCODE_HIGH;
			}
			elseif($options1 == 'encodeamp')
			{
				$opt1 = FILTER_FLAG_ENCODE_AMP;
			}

			if(isset($opt1) && $opt1 !== '')
			{
				$data = filter_var($data, FILTER_SANITIZE_STRING, $opt1);
			}
			else
			{
				$data = filter_var($data, FILTER_SANITIZE_STRING);
			}

			return $data;
		}

		if($type == 'int')
		{
			if((isset($options1) && is_int($options1)) && (isset($options2) && is_int($options2)))
			{
				$option = array('options' => array('min_range' => $options1,
													'max_range' => $options2
													));

				return filter_var($data, FILTER_VALIDATE_INT, $option);
			}
			else
			{
				return filter_var($data, FILTER_VALIDATE_INT);
			}
		}

		if($type == 'html')
		{
			$data = self::stripSlashesGPC($data);
			return filter_var($data, FILTER_CALLBACK, array("options"=>"html_filter"));
		}
	}

	/**
	 * Replace emoticons in the message with smiley images
	 *
	 * @param	string  $message
	 * @return   string
	 */
	private function priv_smiley($message) {
		$smileys = self::priv_getSmileys(true);
		foreach ($smileys as $smile) {
			$message = str_replace(
				$smile['code'],
				'<img src="'.ICMS_UPLOAD_URL . '/' . htmlspecialchars($smile['smile_url'])
				. '" alt="" />', $message
				);
		}
		return $message;
	}

	/**
	 * Get the smileys
	 *
	 * @param	bool	$all
	 * @return   array
	 */
	private function priv_getSmileys($all = false) {
		global $xoopsDB;

		if (count($this->allSmileys) == 0) {
			if ($result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix('smiles'))) {
				while ($smiley = $GLOBALS['xoopsDB']->fetchArray($result)) {
					if ($smiley['display']) {
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
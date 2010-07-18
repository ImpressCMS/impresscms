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
//			include_once ICMS_ROOT_PATH.'/class/icms_HTMLPurifier.php';
			$html_purifier = &icms_core_HTMLFilter::getPurifierInstance();

			$html = $html_purifier->icms_html_purifier($html);

			return $html;
		}
		else
		{
			return $html;
		}
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
	public function icms_CheckVar($data, $type, $options1 = '', $options2 = '')
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

		return $this->icms_FilterVar($data, $type, $options1, $options2);
	}


	// -------- Private Functions --------

	/*
	* Private Function checks & Validates Data
	*
	* @copyright The ImpressCMS Project <http://www.impresscms.org>
	*
	* See public function icms_CheckVar() for parameters
	*
	* @return
	*/
	private function icms_FilterVar($data, $type, $options1, $options2)
	{
		if($type == 'url')
		{
			$data = filter_var($data, FILTER_SANITIZE_URL);

			if($options1 == 'scheme')
			{
				$opt1 = FILTER_FLAG_SCHEME_REQUIRED;
			}
			elseif($options == 'host')
			{
				$opt1 = FILTER_FLAG_HOST_REQUIRED;
			}
			elseif($options == 'path')
			{
				$opt1 = FILTER_FLAG_PATH_REQUIRED;
			}
			elseif($options == 'query')
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
			return $this->html_purifier($data);
		}
	}
}
?>
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
}
?>
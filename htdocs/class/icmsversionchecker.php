<?
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

require_once XOOPS_ROOT_PATH.'/class/snoopy.php';
include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';

/**
* IcmsVersionChecker
*
* Class used to check if the ImpressCMS install is up to date
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/
class IcmsVersionChecker {

	/*
	 * errors
	 * @var $errors array
	 */
	var $errors = array();

	/*
	 * URL of the XML containing version information
	 * @var $version_xml string 
	 */
	var $version_xml = "http://www.impresscms.org/impresscms_version.xml";
	
	/*
	 * Path of the file containing the cached version of the $version_xml content
	 * @var cache_version_xml string 
	 */
	var $cache_version_xml = "impresscms_version.xml";
	
	/*
	 * Time before fetching the $version_xml again and store it in $cache_version_xml
	 * @var $cache_time integer
	 * @todo set this to a day at least or make it configurable in System Admin > Preferences
	 */
	var $cache_time=1;
	
	/*
	 * Name of the latest version
	 * @var $latest_version_name string
	 */
	var $latest_version_name;
	
	/*
	 * Name of installed version
	 * @var $installed_version_name string
	 */
	var $installed_version_name;	

	/*
	 * Number of the latest build
	 * @var $latest_build integer
	 */
	var $latest_build;	
	
	/*
	 * Status of the latest build
	 * 
 	 * 1  = Alpha
 	 * 2  = Beta
 	 * 3  = RC
 	 * 10 = Final 
	 * 
	 * @var $latest_status integer
	 */
	var $latest_status;	

	/*
	 * URL of the latest release
	 * @var $latest_url string
	 */
	var $latest_url;

	/*
	 * Changelog of the latest release
	 * @var $latest_changelog string
	 */
	var $latest_changelog;		
	
	/**
	 * Constructor
     *
     * @return	void
     *
     */
	function IcmsVersionChecker() {
		$this->installed_version_name = ICMS_VERSION_NAME;
		
		$this->cache_version_xml = XOOPS_CACHE_PATH . '/' . $this->cache_version_xml;
	}
	
	/**
	 * Access the only instance of this class
     *
     * @static
     * @staticvar object
     *
     * @return	object
     *
     */
	function &getInstance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new IcmsVersionChecker();
		}
		return $instance;
	}

	/**
	 * Check for a newer version of ImpressCMS
     *
     * @return	TRUE if there is an update, FALSE if no update OR errors occuered
     *
     */	
	function check() {
	
		$ret = false;
		
		$rssdata = '';
		
		//$time_before_cache_again = 86400;
		$time_before_cache_again = 1;
		
		if (!file_exists($this->cache_version_xml) || filemtime($this->cache_version_xml) < time() - $time_before_cache_again) {
	        $snoopy = new Snoopy;
	        if ($snoopy->fetch($this->version_xml)) {
	            $rssdata = $snoopy->results;
	            if (false !== $fp = fopen($this->cache_version_xml, 'w')) {
	                fwrite($fp, $rssdata);
	            }
	        }
		} else {
			if (false !== $fp = fopen($this->cache_version_xml, 'r')) {
				while (!feof ($fp)) {
					$rssdata .= fgets($fp, 4096);
				}
				fclose($fp);
			}
		}     
		
		if ($rssdata != '') {
			$rss2parser = new XoopsXmlRss2Parser($rssdata);
			if (false != $rss2parser->parse()) {
				// get the only item on that xml
				$items =& $rss2parser->getItems();
		
				$latest_item = $items[0];
				$this->latest_version_name = $latest_item['title'];
				$this->latest_changelog = $latest_item['description'];
				$build_info = explode('|', $latest_item['guid']);
				$this->latest_build = $build_info[0];
				$this->latest_status = $build_info[1];
				
				if ($this->latest_build > ICMS_VERSION_BUILD) {
					// There is an update available
					$this->latest_url = $latest_item['link'];
					$ret = true;
				}
				
			} else {
        $this->errors[] = _AM_VERSION_CHECK_RSSDATA_EMPTY;//$rss2parser->getErrors();
				$ret = false;
			}
		} else {
			$this->errors[] = _AM_VERSION_CHECK_RSSDATA_EMPTY;
			$ret = false;
		}
		return $ret;
	}
	
	/**
	 * Gets all the error messages
	 *
	 * @param	$ashtml	bool	return as html?
	 * @return	mixed
	 */
	function getErrors($ashtml=true) {
	    if (!$ashtml) {
            return $this->errors;
        } else {
        	$ret = '';
        	if (count($this->errors) > 0) {
            	foreach ($this->errors as $error) {
            	    $ret .= $error.'<br />';
            	}
        	}
        	return $ret;
        }		
	}
}
?>
<?php
if (!defined('XOOPS_ROOT_PATH')) {
	die("ImpressCMS root path not defined");
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
* @version		$Id: icmsversionchecker.php 7171 2008-11-23 10:36:17Z pesian_stranger $
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

		// Create a new instance of the SimplePie object
		include_once(ICMS_ROOT_PATH . '/class/icmssimplerss.php');
		$feed = new IcmsSimpleRss($this->version_xml, 0);
		if ($feed) {
			$versionInfo['title'] = $feed->get_title();
			$versionInfo['link'] = $feed->get_link();
			$versionInfo['image_url'] = $feed->get_image_url();
			$versionInfo['image_title'] = $feed->get_image_title();
			$versionInfo['image_link'] = $feed->get_image_link();
			$versionInfo['description'] = $feed->get_description();
			$feed_item = $feed->get_item(0);
			$versionInfo['permalink'] = $feed_item->get_permalink();
			$versionInfo['title'] = $feed_item->get_title();
			$versionInfo['content'] = $feed_item->get_content();
			$guidArray = $feed_item->get_item_tags('', 'guid');
			$versionInfo['guid'] = $guidArray[0]['data'];
		} else {
			$this->errors[] = _AM_VERSION_CHECK_RSSDATA_EMPTY;
			return false;
		}
		$this->latest_version_name = $versionInfo['title'];
		$this->latest_changelog = $versionInfo['description'];
		$build_info = explode('|', $versionInfo['guid']);
		$this->latest_build = $build_info[0];
		$this->latest_status = $build_info[1];

		if ($this->latest_build > ICMS_VERSION_BUILD) {
			// There is an update available
			$this->latest_url = $versionInfo['link'];
			return true;
		}
		return false;
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
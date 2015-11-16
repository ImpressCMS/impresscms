<?php
/**
 * UrlLink Object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.3
 * @author		Phoenyx
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * Url Link object
 * 
 * @property int    $urllinkid     URL Link ID
 * @property int    $mid           Module ID
 * @property string $caption       Caption
 * @property string $description   Description
 * @property string $url           URL
 * @property string $target        Target
 * 
 * @package	ICMS/Data/UrlLink 
 */
class icms_data_urllink_Object extends icms_ipf_Object {
	/**
	 * constructor
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar("urllinkid", self::DTYPE_INTEGER, 0, TRUE);
		$this->initVar("mid", self::DTYPE_INTEGER,0,  TRUE, 5);
		$this->initVar("caption", self::DTYPE_STRING, '', FALSE, 255);
		$this->initVar("description", self::DTYPE_STRING, '', FALSE, 255);
		$this->initVar("url", self::DTYPE_STRING, '', FALSE, 255);
		$this->initVar("target", self::DTYPE_STRING, '', TRUE, 6);

		$this->setControl("target", array("options" => array("_self" => _CO_ICMS_URLLINK_SELF,
			"_blank" => _CO_ICMS_URLLINK_BLANK)));
                
                parent::__construct($handler, $data);
	}

	/**
	 * get value for variable
	 *
	 * @param string $key field name
	 * @param string $format format
	 * @return mixed value
	 */
	public function getVar($key, $format = "e"){
		if (substr($key, 0, 4) == "url_") {
			return parent::getVar("url", $format);
		} elseif (substr($key, 0, 4) == "mid_") {
			return parent::getVar("mid", $format);
		} elseif(substr($key, 0, 8) == "caption_") {
			return parent::getVar("caption", $format);
		} elseif(substr($key, 0, 5) == "desc_") {
			return parent::getVar("description", $format);
		} else {
			return parent::getVar($key, $format);
		}
	}

	/**
	 * generate html for clickable link
	 *
	 * @return string html
	 */
	public function render() {
		$ret  = "<a href='" . $this->getVar("url") . "' target='" . $this->getVar("target") . "' ";
		$ret .= "title='" . $this->getVar("description") . "'>";
		$ret .= $this->getVar("caption") . "</a>";
		return $ret;
	}
}
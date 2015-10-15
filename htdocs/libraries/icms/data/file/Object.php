<?php

/**
 * Richfile Object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	icms
 * @package		data
 * @subpackage	richfile
 * @since		1.3
 * @author		Phoenyx
 * @version		$Id$
 */
defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * File object
 * 
 * @property int    $fileid        File ID
 * @property int    $mid           Module ID
 * @property string $caption       Caption
 * @property string $description   Description
 * @property string $url           URL of file
 */
class icms_data_file_Object extends icms_ipf_Object {

    /**
     * constructor
     */
    public function __construct(&$handler, $data = array()) {
        $this->initVar("fileid", self::DTYPE_INTEGER, 0, TRUE);
        $this->initVar("mid", self::DTYPE_INTEGER, 0, TRUE);
        $this->initVar("caption", self::DTYPE_STRING, '', false, 255);
        $this->initVar("description", self::DTYPE_STRING, '', false, 255);
        $this->initVar("url", self::DTYPE_STRING, '', false, 255);

        parent::__construct($handler, $data);
    }

    /**
     * get value for variable
     *
     * @param string $key field name
     * @param string $format format
     * @return mixed value
     */
    public function getVar($key, $format = "e") {
        if (substr($key, 0, 4) == "url_") {
            return parent::getVar("url", $format);
        } elseif (substr($key, 0, 4) == "mid_") {
            return parent::getVar("mid", $format);
        } elseif (substr($key, 0, 8) == "caption_") {
            return parent::getVar("caption", $format);
        } elseif (substr($key, 0, 5) == "desc_") {
            return parent::getVar("description", $format);
        } else {
            return parent::getVar($key, $format);
        }
    }

    public function render() {
        $url = str_replace("{ICMS_URL}", ICMS_URL, $this->getVar("url"));
        $caption = $this->getVar("caption") != "" ? $this->getVar("caption") : $url;
        return "<a href='" . $url . "' title='" . $this->getVar("description") . "' target='_blank'>" . $caption . "</a>";
    }

}

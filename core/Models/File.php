<?php

/**
 * Richfile Object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.3
 * @author	Phoenyx
 */

namespace ImpressCMS\Core\Models;

use ImpressCMS\Core\Models\AbstractDatabaseModel;

/**
 * File object
 *
 * @property int    $fileid        File ID
 * @property int    $mid           Module ID
 * @property string $caption       Caption
 * @property string $description   Description
 * @property string $url           URL of file
 *
 * @package	ICMS\Data\File
 */
class File extends AbstractDatabaseModel {

	/**
	 * constructor
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar('fileid', self::DTYPE_INTEGER, 0, true);
		$this->initVar('mid', self::DTYPE_INTEGER, 0, true);
		$this->initVar('caption', self::DTYPE_STRING, '', false, 255);
		$this->initVar('description', self::DTYPE_STRING, '', false, 255);
		$this->initVar('url', self::DTYPE_STRING, '', false, 255);

		parent::__construct($handler, $data);
	}

	/**
	 * get value for variable
	 *
	 * @param string $key field name
	 * @param string $format format
	 * @return mixed value
	 */
	public function getVar($key, $format = 'e') {
		if (strpos($key, 'url_') === 0) {
			return parent::getVar('url', $format);
		} elseif (strpos($key, 'mid_') === 0) {
			return parent::getVar('mid', $format);
		} elseif (strpos($key, 'caption_') === 0) {
			return parent::getVar('caption', $format);
		} elseif (strpos($key, 'desc_') === 0) {
			return parent::getVar('description', $format);
		} else {
			return parent::getVar($key, $format);
		}
	}

	public function render() {
		$url = str_replace('{ICMS_URL}', ICMS_URL, $this->getVar('url'));
		$caption = $this->getVar('caption') ?:$url;
		return "<a href='" . $url . "' title='" . $this->getVar('description') . "' target='_blank'>" . $caption . '</a>';
	}

}

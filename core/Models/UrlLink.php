<?php
/**
 * UrlLink Object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.3
 * @author	Phoenyx
 */

namespace ImpressCMS\Core\Models;

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
 * @package	ICMS\Data\UrlLink
 */
class UrlLink extends AbstractExtendedModel {

	/**
	 * @inheritDoc
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar('urllinkid', self::DTYPE_INTEGER, 0, true);
		$this->initVar('mid', self::DTYPE_INTEGER, 0, true, 5);
		$this->initVar('caption', self::DTYPE_STRING, '', false, 255);
		$this->initVar('description', self::DTYPE_STRING, '', false, 255);
		$this->initVar('url', self::DTYPE_STRING, '', false, 255);
		$this->initVar('target', self::DTYPE_STRING, '', true, 6);

		$this->setControl('target', ['options' => ['_self' => _CO_ICMS_URLLINK_SELF,
			'_blank' => _CO_ICMS_URLLINK_BLANK]]);

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

	/**
	 * generate html for clickable link
	 *
	 * @return string html
	 */
	public function render() {
		$ret  = "<a href='" . $this->getVar('url') . "' target='" . $this->getVar('target') . "' ";
		$ret .= "title='" . $this->getVar('description') . "'>";
		$ret .= $this->getVar('caption') . "</a>";
		return $ret;
	}
}
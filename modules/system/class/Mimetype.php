<?php
/**
 * ImpressCMS Mimetypes
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package     ImpressCMS\Modules\System\Class\Mimetype
 */

/* This may be loaded by other modules - and not just through the cpanel */
icms_loadLanguageFile('system', 'mimetype', true);

/**
 * Mimetype management for file handling
 *
 * @package     ImpressCMS\Modules\System\Class\Mimetype
 *
 * @property int        $mimetypeid    Mimetype ID
 * @property string     $extension     File extention
 * @property string     $types         Mimetypes
 * @property string     $name          Name
 * @property string[]   $dirname       Modules allowed to use this mimetype
 */
class mod_system_Mimetype extends icms_ipf_Object {
	public $content = false;

	/**
	 * Constructor
	 *
	 * @param object $handler
	 */
	function __construct(&$handler) {
				$this->initVar('mimetypeid', self::DTYPE_INTEGER, 0, true);
				$this->initVar('extension', self::DTYPE_STRING, '', true, 60, null, null, _CO_ICMS_MIMETYPE_EXTENSION, _CO_ICMS_MIMETYPE_EXTENSION_DSC);
				$this->initVar('types', self::DTYPE_STRING, '', true, null, null, null, _CO_ICMS_MIMETYPE_TYPES, _CO_ICMS_MIMETYPE_TYPES_DSC);
				$this->initVar('name', self::DTYPE_STRING, '', true, 255, null, null, _CO_ICMS_MIMETYPE_NAME, _CO_ICMS_MIMETYPE_NAME_DSC);
				$this->initVar('dirname', self::DTYPE_LIST, null, true, null, null, null, _CO_ICMS_MIMETYPE_DIRNAME);

		parent::__construct($handler);

		$this->setControl('dirname', array(
			'name' => 'selectmulti',
			'itemHandler' => 'icms_module',
			'method' => 'getActive'));
	}

	/**
	 * (non-PHPdoc)
	 * @see icms_ipf_Object::getVar()
	 * @return	mixed	Value of the selected property
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array())) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * Determines if a variable is a zero length string
	 * @param string $var
	 * @return	boolean
	 */
	public function emptyString($var) {
		return strlen($var) > 0;
	}

	/**
	 * Get the name property of the selected mimetype
	 * @return	string
	 */
	public function getMimetypeName() {
		$ret = $this->getVar('name');
		return $ret;
	}

	/**
	 * Get the type of the selected mimetype
	 * @return	string
	 */
	public function getMimetypeType() {
		$ret = $this->getVar('types');
		return $ret;
	}

	/**
	 * Get the ID of the selected mimetype
	 * @return	int
	 */
	public function getMimetypeId() {
		$ret = (int) $this->getVar('mimetypeid');
		return $ret;
	}
}

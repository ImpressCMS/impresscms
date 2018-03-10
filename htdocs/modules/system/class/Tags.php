<?php
/**
 * ImpressCMS Tags
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	2.0
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * The tag object - for tagging content
 *
 * @package     ImpressCMS\Modules\System\Class\Tags
 *
 * @property int    $id     Unique identifier for the tag
 * @property string $tag    Text of the tag
 * @property int    $status Status of the tag - enabled, disabled
 * @property int    $count  The number of occurrences of the tag across the site
 */
class mod_system_Tags extends icms_ipf_seo_Object {

	/**
	 * Construct the tag object
	 *
	 * @param @mod_sys_TagsHandler $handler
	 */
	public function __construct(&$handler) {
                $this->initVar('id', self::DTYPE_INTEGER, null, TRUE);
                $this->initVar('tag', self::DTYPE_STRING, '', true, 255, null, null, _CO_SYSTEM_TAGS_TAG, _CO_SYSTEM_TAGS_TAG_DSC);
                $this->initVar('status', self::DTYPE_INTEGER, null, true, null, null, null, _CO_SYSTEM_TAGS_STATUS, _CO_SYSTEM_TAGS_STATUS_DSC);
                $this->initVar('count', self::DTYPE_INTEGER, null, false, null, null, null, _CO_SYSTEM_TAGS_COUNT, _CO_SYSTEM_TAGS_COUNT_DSC);

		$this->setControl("status", "yesno");

                parent::__construct($handler);

		$this->initiateSEO();
	}

   /**
    * Overriding the icms_ipf_Object::getVar method to assign a custom method on some
    * specific fields to handle the value before returning it
     *
    * @param str $key key of the field
    * @param str $format format that is requested
    * @return mixed value of the field that is requested
    */
    function getVar($key, $format = 's') {
        if ($format == 's' && in_array($key, array())) {
            return call_user_func(array($this,$key));
        }
        return parent::getVar($key, $format);
    }
}

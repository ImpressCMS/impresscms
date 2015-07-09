<?php
/**
 * ImpressCMS Tags
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICsMS
 * @package		Administration
 * @subpackage	Tags
 * @since		2.0
 * @version		SVN: $Id: Customtag.php 11583 2012-02-19 05:10:24Z skenow $
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * The tag object - for tagging content
 *
 * @category	ICMS
 * @package		Administration
 * @subpackage	Tags
 *
 */
class mod_system_Tags extends icms_ipf_seo_Object {

	/**
	 * Unique identifier for the tag
	 *
	 * @var integer
	 */
	public $id;
	/**
	 * Text of the tag
	 *
	 * @var string
	 */
	public $tag;
	/**
	 * Status of the tag - enabled, disabled
	 *
	 * @var boolean
	 */
	public $status;
	/**
	 * The number of occurrences of the tag across the site
	 *
	 * @var integer
	 */
	public $count;

	/**
	 * Construct the tag object
	 *
	 * @param @mod_sys_TagsHandler $handler
	 */
	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar("id", self::DTYPE_INTEGER, TRUE);
		$this->quickInitVar("tag", self::DTYPE_DEP_TXTBOX, TRUE, _CO_SYSTEM_TAGS_TAG, _CO_SYSTEM_TAGS_TAG_DSC);
		$this->quickInitVar("status", self::DTYPE_INTEGER, TRUE, _CO_SYSTEM_TAGS_STATUS, _CO_SYSTEM_TAGS_STATUS_DSC);
		$this->quickInitVar("count", self::DTYPE_INTEGER, FALSE, _CO_SYSTEM_TAGS_COUNT, _CO_SYSTEM_TAGS_COUNT_DSC);

		$this->setControl("status", "yesno");

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

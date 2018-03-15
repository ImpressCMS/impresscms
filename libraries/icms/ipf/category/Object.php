<?php
/**
 * Contains the basic classe for managing a category object based on icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	marcan <marcan@impresscms.org>
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @todo	Properly set visibility of variables - in version 1.4
 */

/**
 * Persistble category object
 *
 * @property int    $categoryid    Category ID
 * @property int    $parentid      Parent category ID
 * @property string $name          Name
 * @property string $description   Description
 * @property string $image         image
 *
 * @package	ICMS\IPF\Category
 */
class icms_ipf_category_Object extends icms_ipf_seo_Object {
	/** Path that corresponds to the category */
	private $_categoryPath;
	/**
	 * Constructor for icms_ipf_category_Object
	 * @return icms_ipf_category_Object
	 */
	public function __construct() {
		$this->initVar('categoryid', self::DTYPE_INTEGER, '', true);
		$this->initVar('parentid', self::DTYPE_INTEGER, '', false, null, '', false, _CO_ICMS_CATEGORY_PARENTID, _CO_ICMS_CATEGORY_PARENTID_DSC);
		$this->initVar('name', self::DTYPE_STRING, '', false, 255, '', false, _CO_ICMS_CATEGORY_NAME, _CO_ICMS_CATEGORY_NAME_DSC);
		$this->initVar('description', self::DTYPE_STRING, '', false, null, '', false, _CO_ICMS_CATEGORY_DESCRIPTION, _CO_ICMS_CATEGORY_DESCRIPTION_DSC);
		$this->initVar('image', self::DTYPE_STRING, '', false, 255, '',  false, _CO_ICMS_CATEGORY_IMAGE, _CO_ICMS_CATEGORY_IMAGE_DSC);

		$this->initCommonVar('doxcode');

		$this->setControl('image', array('name' => 'image'));
		$this->setControl('parentid', array('name' => 'parentcategory'));
		$this->setControl('description', array('name' => 'textarea',
                                            'itemHandler' => false,
                                            'method' => false,
                                            'module' => false,
                                            'form_editor' => 'default'));

		// call parent constructor to get SEO fields initiated
		parent::__construct();
	}

	/**
	 * returns a specific variable for the object in a proper format
	 *
	 * @access public
	 * @param string $key key of the object's variable to be returned
	 * @param string $format format to use for the output
	 * @return mixed formatted value of the variable
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array('description', 'image'))) {
			return call_user_func(array($this,$key));
		}
		return parent::getVar($key, $format);
	}
	/**
	 * Returns the description for the category
	 * @see 	icms_ipf_Object::getValueFor()
	 * @return 	string	Text to display as the description
	 */
	public function description() {
		return $this->getValueFor('description', false);
	}
	/**
	 * Returns the image for the category
	 *
	 * @return 	mixed	Returns false if there is no image, or the image, if it exists
	 */
	public function image() {
		$ret = $this->getVar('image', 'e');
		if ($ret == '-1') {
			return false;
		} else {
			return $ret;
		}
	}
	/**
	 * Create an array of the category's properties
	 *
	 * @return 	array An array of the category's properties
	 */
	public function toArray() {
		$this->setVar('doxcode', true);
		global $myts;
		$objectArray = parent::toArray();
		if ($objectArray['image']) {
			$objectArray['image'] = $this->getImageDir() . $objectArray['image'];
		}
		return $objectArray;
	}
	/**
	 * Create the complete path of a category
	 *
	 * @todo this could be improved as it uses multiple queries
	 * @param bool $withAllLink make all name clickable
	 * @return string complete path (breadcrumb)
	 */
	public function getCategoryPath($withAllLink=true, $currentCategory=false)	{

		$controller = new icms_ipf_Controller($this->handler);

		if (!$this->_categoryPath) {
			if ($withAllLink && !$currentCategory) {
				$ret = $controller->getItemLink($this);
			} else {
				$currentCategory = false;
				$ret = $this->getVar('name');
			}
			$parentid = $this->getVar('parentid');
			if ($parentid != 0) {
				$parentObj =& $this->handler->get($parentid);
				if ($parentObj->isNew()) {
					exit;
				}
				$parentid = $parentObj->getVar('parentid');
				$ret = $parentObj->getCategoryPath($withAllLink, $currentCategory) . " > " .$ret;
			}
			$this->_categoryPath = $ret;
		}

		return $this->_categoryPath;
	}

}

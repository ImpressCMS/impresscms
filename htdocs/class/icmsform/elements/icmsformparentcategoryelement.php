<?php
/**
 * Form control creating a parent category selectbox for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_ipf_Object
 * @since		  1.1
 * @author		  marcan <marcan@impresscms.org>
 * @version		$Id$
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormParentcategoryElement extends XoopsFormSelect {

	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	function IcmsFormParentcategoryElement($object, $key) {
		$category_title_field = $object->handler->identifierName;

		$addNoParent = isset($object->controls[$key]['addNoParent']) ? $object->controls[$key]['addNoParent'] : true;
		$criteria = new icms_criteria_Compo();
		$criteria->setSort("weight, " . $category_title_field);
		$category_handler = icms_getModuleHandler('category', $object->handler->_moduleName);
		$categories = $category_handler->getObjects($criteria);

		include_once ICMS_ROOT_PATH . "/class/tree.php" ;
		$mytree = new icms_core_ObjectTree($categories, "category_id", "category_pid");
		$this->XoopsFormSelect( $object->vars[$key]['form_caption'], $key, $object->getVar($key, 'e') );

		$ret = array();
		$options = $this->getOptionArray($mytree, $category_title_field, 0, "", $ret);
		if ($addNoParent) {
			$newOptions = array('0'=>'----');
			foreach ($options as $k=>$v) {
				$newOptions[$k] = $v;
			}
			$options = $newOptions;
		}
		$this->addOptionArray($options);
	}

	/**
	 * Get options for a category select with hierarchy (recursive)
	 *
	 * @param object  $tree         icms_core_ObjectTree $tree (@link icms_core_ObjectTree)
	 * @param string  $fieldName    The fieldname to get the option array for
	 * @param int     $key          the key to get the optionarray for
	 * @param string  $prefix_curr  the prefix
	 * @param array   $ret          passed return array
	 *
	 * @return array  $ret          the constructed option array
	 */
	function getOptionArray($tree, $fieldName, $key, $prefix_curr = "", &$ret) {

		if ($key > 0) {
			$value = $tree->_tree[$key]['obj']->getVar($tree->_myId);
			$ret[$key] = $prefix_curr.$tree->_tree[$key]['obj']->getVar($fieldName);
			$prefix_curr .= "-";
		}

		if (isset($tree->_tree[$key]['child']) && !empty($tree->_tree[$key]['child'])) {
			foreach ($tree->_tree[$key]['child'] as $childkey) {
				$this->getOptionArray($tree, $fieldName, $childkey, $prefix_curr, $ret);
			}
		}
		return $ret;
	}
}

?>
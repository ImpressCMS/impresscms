<?php
/**
* Form control creating a parent category selectbox for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormParentcategoryElement extends XoopsFormSelect {
    function IcmsFormParentcategoryElement($object, $key) {

    	$addNoParent = isset($object->controls[$key]['addNoParent']) ? $object->controls[$key]['addNoParent'] : true;
    	$criteria = new CriteriaCompo();
        $criteria->setSort("weight, name");
        $category_handler = xoops_getmodulehandler('category', $object->handler->_moduleName);
        $categories = $category_handler->getObjects($criteria);

        include_once(XOOPS_ROOT_PATH . "/class/tree.php");
        $mytree = new XoopsObjectTree($categories, "categoryid", "parentid");
        $this->XoopsFormSelect( $object->vars[$key]['form_caption'], $key, $object->getVar($key, 'e') );

        $ret = array();
        $options = $this->getOptionArray($mytree, "name", 0, "", $ret);
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
     * @param XoopsObjectTree $tree
     * @param string $fieldName
     * @param int $key
     * @param string $prefix_curr
     * @param array $ret
     *
     * @return array
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
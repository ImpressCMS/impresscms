<?php
/**
 * Extended User Profile
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

if (!defined("ICMS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}
include_once(ICMS_ROOT_PATH."/modules/smartobject/include/common.php");
include_once(ICMS_ROOT_PATH."/modules/smartobject/class/smartobject.php");
include_once(ICMS_ROOT_PATH."/modules/smartobject/class/smartobjecthandler.php");
/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class ProfileCategory extends SmartObject {
    function ProfileCategory() {
        $this->initVar('catid', XOBJ_DTYPE_INT, null, true);
        $this->initVar('cat_title', XOBJ_DTYPE_TXTBOX);
        $this->initVar('cat_description', XOBJ_DTYPE_TXTAREA);
        $this->initVar('cat_weight', XOBJ_DTYPE_INT);
        //$this->initVar('cat_moduleid', XOBJ_DTYPE_INT);
    }

    /**
    * Get {@link XoopsThemeForm} for adding/editing categories
    *
    * @param mixed $action URL to submit to or false for $_SERVER['REQUEST_URI']
    *
    * @return object
    */
    function getForm($action = false) {
        if ($action === false) {
            $action = $_SERVER['REQUEST_URI'];
        }
        $title = $this->isNew() ? sprintf(_PROFILE_AM_ADD, _PROFILE_AM_CATEGORY) : sprintf(_PROFILE_AM_EDIT, _PROFILE_AM_CATEGORY);

        include_once(ICMS_ROOT_PATH."/class/xoopsformloader.php");

        $form = new XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->addElement(new XoopsFormText(sprintf(_PROFILE_AM_TITLE, _PROFILE_AM_CATEGORY), 'cat_title', 35, 255, $this->getVar('cat_title')));
        if (!$this->isNew()) {
            //Load groups
            $form->addElement(new XoopsFormHidden('id', $this->getVar('catid')));
        }
        $form->addElement(new XoopsFormTextArea(_PROFILE_AM_DESCRIPTION, 'cat_description', $this->getVar('cat_description', 'e')));
        $form->addElement(new XoopsFormText(_PROFILE_AM_CATEGORY." "._PROFILE_AM_WEIGHT, 'cat_weight', 35, 35, $this->getVar('cat_weight', 'e')));

        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

        return $form;
    }
}

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */
class ProfileCategoryHandler extends SmartPersistableObjectHandler {
    function ProfileCategoryHandler(&$db) {
        parent::SmartPersistableObjectHandler($db, "category", "catid", 'cat_title', 'cat_description', 'profile');
    }
}
?>
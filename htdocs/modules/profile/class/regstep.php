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

include_once(ICMS_KERNEL_PATH."icmspersistableobject.php");

class ProfileRegstep extends IcmsPersistableObject  {
    function ProfileRegstep() {
        $this->initVar('step_id', XOBJ_DTYPE_INT);
        $this->initVar('step_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('step_intro', XOBJ_DTYPE_TXTAREA);
        $this->initVar('step_order', XOBJ_DTYPE_INT, 1);
        $this->initVar('step_save', XOBJ_DTYPE_INT, 0);
    }

    /**
     * Get add/edit form for a ProfileRegstep
     *
     * @return RegistrationStepForm
     */
    function getForm() {
        include_once(ICMS_ROOT_PATH."/modules/".basename(  dirname(  dirname( __FILE__ ) ) )."/class/forms/step.php");
        $form = new RegistrationStepForm(_PROFILE_AM_STEP, 'stepform', 'step.php');
        $form->createElements($this);
        return $form;
    }
}

class ProfileRegstepHandler extends IcmsPersistableObjectHandler {
    function ProfileRegstepHandler($db) {
        parent::IcmsPersistableObjectHandler($db, 'regstep', 'step_id', 'step_name', '', 'profile');
    }

    /**
     * Insert a new object
     * @see IcmsPersistableObjectHandler
     *
     * @param ProfileRegstep $obj
     * @param bool $force
     *
     * @return bool
     */
    function insert($obj, $force = false) {
        if (parent::insert($obj, $force)) {
            if ($obj->getVar('step_save') == 1) {
                return $this->updateAll('step_save', 0, new Criteria('step_id', $obj->getVar('step_id'), "!="));
            }
            return true;
        }
        return false;
    }

    /**
     * Delete an object from the database
     * @see IcmsPersistableObjectHandler
     *
     * @param ProfileRegstep $obj
     * @param bool $force
     *
     * @return bool
     */
    function delete($obj, $force = false) {
        if (parent::delete($obj, $force)) {
            $field_handler = icms_getmodulehandler( 'field', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
            return $field_handler->updateAll('step_id', 0, new Criteria('step_id', $obj->getVar('step_id')));
        }
        return false;
    }
}
?>
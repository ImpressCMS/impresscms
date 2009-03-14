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

include_once(ICMS_ROOT_PATH."/class/xoopsformloader.php");

class RegistrationStepForm extends XoopsThemeForm {
    
    /**
     * Add elements to the form
     * 
     * @param ProfileRegstep $target
     * 
     * @return void
     *
     */
    function createElements($target) {
        if (!$target->isNew()) {
            $this->addElement(new XoopsFormHidden('id', $target->getVar('step_id')));
        }
        $this->addElement(new XoopsFormHidden('op', 'save'));
        $this->addElement(new XoopsFormText(_PROFILE_AM_STEPNAME, 'step_name', 25, 255, $target->getVar('step_name', 'e')));
        $this->addElement(new XoopsFormText(_PROFILE_AM_STEPINTRO, 'step_intro', 25, 255, $target->getVar('step_intro', 'e')));
        $this->addElement(new XoopsFormText(_PROFILE_AM_STEPORDER, 'step_order', 10, 10, $target->getVar('step_order', 'e')));
        $this->addElement(new XoopsFormRadioYN(_PROFILE_AM_STEPSAVE, 'step_save', $target->getVar('step_save', 'e')));
        $this->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    }
}
?>
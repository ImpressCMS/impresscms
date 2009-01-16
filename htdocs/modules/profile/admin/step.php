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

include 'header.php';
xoops_cp_header();

icms_adminMenu(4, "");

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : (isset($_REQUEST['id']) ? "edit" : 'list');

$handler =& icms_getmodulehandler( 'regstep', basename(  dirname(  dirname( __FILE__ ) ) ), 'profile' );
switch ($op) {
    case "list":
        $xoopsTpl->assign('steps', $handler->getObjects(null, true, false));
        $smartOption['template_main'] = "profile_admin_steplist.html";
        break;

    case "new":
        $obj =& $handler->create();
        $form =& $obj->getForm();
        $form->display();
        break;

    case "edit":
        $obj =& $handler->get($_REQUEST['id']);
        $form =& $obj->getForm();
        $form->display();
        break;

    case "save":
        if (isset($_REQUEST['id'])) {
            $obj =& $handler->get($_REQUEST['id']);
        }
        else {
            $obj =& $handler->create();
        }
        $obj->setVar('step_name', $_REQUEST['step_name']);
        $obj->setVar('step_order', $_REQUEST['step_order']);
        $obj->setVar('step_intro', $_REQUEST['step_intro']);
        $obj->setVar('step_save', $_REQUEST['step_save']);
        if ($handler->insert($obj)) {
            redirect_header('step.php', 3, sprintf(_PROFILE_AM_SAVEDSUCCESS, _PROFILE_AM_STEP));
        }
        echo $obj->getHtmlErrors();
        $form =& $obj->getForm();
        $form->display();
        break;

    case "delete":
        $obj =& $handler->get($_REQUEST['id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if ($handler->delete($obj)) {
                redirect_header('step.php', 3, sprintf(_PROFILE_AM_DELETEDSUCCESS, _PROFILE_AM_STEP));
            }
            else {
                echo $obj->getHtmlErrors();
            }
        }
        else {
            xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_PROFILE_AM_RUSUREDEL, $obj->getVar('step_name')));
        }
        break;
}

$xoopsTpl->display("db:".$smartOption['template_main']);

xoops_cp_footer();
?>
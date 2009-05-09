<?php
/**
 * Extended User Profile
 *
 * @copyright       The ImpressCMS Project <http://www.impresscms.org/>
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         profile
 * @since           1.2
 * @author          Jan Pedersen
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @author			Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @version         $Id$
 */

$adminmenu[7]['title'] = _PROFILE_MI_INDEX;
$adminmenu[7]['link'] = 'admin/admin.php';
$adminmenu[1]['title'] = _PROFILE_MI_USERS;
$adminmenu[1]['link'] = 'admin/user.php';
$adminmenu[2]['title'] = _AM_PROFILE_CATEGORYS;
$adminmenu[2]['link'] = 'admin/category.php';
$adminmenu[3]['title'] = _AM_PROFILE_FIELDS;
$adminmenu[3]['link'] = 'admin/field.php';
$adminmenu[4]['title'] = _AM_PROFILE_REGSTEPS;
$adminmenu[4]['link'] = 'admin/regstep.php';
$adminmenu[5]['title'] = _PROFILE_MI_PERMISSIONS;
$adminmenu[5]['link'] = 'admin/permissions.php';
$gperm =& xoops_gethandler ( 'groupperm' );
$xoopsUser = $GLOBALS['xoopsUser'];
$ugroups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(ICMS_GROUP_ANONYMOUS);
$agroups = $gperm->getGroupIds('system_admin',7); //ICMS_SYSTEM_BLOCK constant not available?
if (array_intersect($ugroups, $agroups)) {
$adminmenu[6]['title'] = _PROFILE_MI_FINDUSER;
$adminmenu[6]['link'] = '../system/admin.php?fct=findusers';
//$adminmenu[6]['link'] = 'admin/finduser.php'; // This one is removed because feature is at this stage incomplete.
}
global $xoopsModule;
if (isset($xoopsModule)) {

	$i = -1;

	$i++;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');

	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_GOTOMODULE;
	$headermenu[$i]['link'] = ICMS_URL.'/modules/'.$xoopsModule->getVar('dirname') . '/';

	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_UPDATE_MODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $xoopsModule->getVar('dirname');

	$i++;
	$headermenu[$i]['title'] = _MODABOUT_ABOUT;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/'.$xoopsModule->getVar('dirname').'/admin/about.php';
}
?>
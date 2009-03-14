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

/*
 * new page by felix<inbox> for validusdc
 * adding report page
 */
die('Feature not ready yet!');
include_once('header.php');

$xoopsOption['template_main'] = 'profile_report.html';
include_once(ICMS_ROOT_PATH . "/header.php");
include_once("footer.php");


$profile_profile_handler = icms_getmodulehandler( 'profile', basename( dirname( __FILE__ ) ), 'profile' );

$icms_registry = IcmsPersistableObjectsRegistry::getInstance();
/*$icms_registry->addObjectsFromItemName('account');
$icms_registry->addObjectsFromItemName('project');
$icms_registry->addObjectsFromItemName('activity');*/

include_once ICMS_KERNEL_PATH."icmspersistabletable.php";
$objectTable = new IcmsPersistableTable($profile_profile_handler);
$objectTable->isForUserSide();
$objectTable->addIntroButton('', 'report.php?op=export', _AM_SPROFILE_EXPORT);
//Filters
$criteria_new_reg = new CriteriaCompo();
$criteria_new_reg->add(new Criteria('reg_date', time()-(3600*24*7), '>'));
$objectTable->addFilter(_CO_SOFFSET_NEW_REG, array(
							'key' => 'reg_date',
							'criteria' => $criteria_new_reg
));


$objectTable->addColumn(new IcmsPersistableColumn('uname', _GLOBAL_LEFT, 150));
$objectTable->addColumn(new IcmsPersistableColumn('reg_date', _GLOBAL_LEFT, 150));
/*$objectTable->addColumn(new IcmsPersistableColumn('activityid', _GLOBAL_LEFT, 150));
$objectTable->addColumn(new IcmsPersistableColumn('accountid', _GLOBAL_LEFT, false));
$objectTable->addColumn(new IcmsPersistableColumn('duration', _GLOBAL_RIGHT, 100));*/


//$objectTable->addFilter('accountid', 'getAccounts');

$xoopsTpl->assign('profile_report',$objectTable->fetch());
//$xoopsTpl->assign('categoryPath', _MD_SBILLING_LOG_MYLOG);


$xoopsTpl->assign('module_home', icms_getModuleName(false, true));

include_once(ICMS_ROOT_PATH . '/footer.php');
?>
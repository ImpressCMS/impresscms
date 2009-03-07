<?php
/**
 * Extended User Profile
 *
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          Marcello Brandao <marcello.brandao@gmail.com>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

include_once 'header.php';
include_once ICMS_ROOT_PATH.'/class/criteria.php';

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/');
	exit();
}

/**
* Receiving info from get parameters  
*/ 
$reltribeuser_id = intval($_POST['reltribe_id']);
if(!isset($_POST['confirm']) || $_POST['confirm']!=1)
{
	xoops_confirm(array('reltribe_id'=> $reltribeuser_id ,'confirm'=>1), 'abandontribe.php', _MD_PROFILE_ASKCONFIRMABANDONTRIBE, _MD_PROFILE_CONFIRMABANDON);
}
else
{

	/**
	* Creating the factory  and the criteria to delete the picture
	* The user must be the owner
	*/
	$reltribeuser_factory = icms_getmodulehandler('reltribeuser', $modname, 'profile' );
	$criteria_rel_id = new Criteria('rel_id',$reltribeuser_id);
	$uid = intval($xoopsUser->getVar('uid'));
	$criteria_uid = new Criteria('rel_user_uid',$uid);
	$criteria = new CriteriaCompo($criteria_rel_id);
	$criteria->add($criteria_uid);

	/**
	* Try to delete  
	*/
	if($reltribeuser_factory->deleteAll($criteria)) {redirect_header('tribes.php', 1, _MD_PROFILE_TRIBEABANDONED);}
	else {redirect_header('tribes.php', 1, _MD_PROFILE_NOCACHACA);}
}
include 'footer.php';
?>

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
$modname = basename( dirname( __FILE__ ) );
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}

include_once ICMS_ROOT_PATH.'/class/criteria.php';

/**
* Factories of tribes  
*/
$reltribeuser_factory = icms_getmodulehandler('reltribeuser', $modname, 'profile' );
$tribes_factory = icms_getmodulehandler('tribes', $modname, 'profile' );

$tribe_id = intval($_POST['tribe_id']);

if(!isset($_POST['confirm']) || $_POST['confirm']!=1) {
	xoops_confirm(array('tribe_id'=> $tribe_id,'confirm'=>1), 'delete_tribe.php', _MD_PROFILE_ASKCONFIRMTRIBEDELETION, _MD_PROFILE_CONFIRMTRIBEDELETION);
} else {
	/**
	* Creating the factory  and the criteria to delete the picture
	* The user must be the owner
	*/
	$criteria_tribe_id = new Criteria ('tribe_id',$tribe_id);
	$uid = intval($xoopsUser->getVar('uid'));
	$criteria_uid = new Criteria ('owner_uid',$uid);
	$criteria = new CriteriaCompo ($criteria_tribe_id);
	$criteria->add($criteria_uid);
	
	/**
	* Try to delete  
	*/
	if($tribes_factory->getCount($criteria)==1) {
		if($tribes_factory->deleteAll($criteria)) {
			$criteria_rel_tribe_id = new Criteria('rel_tribe_id',$tribe_id);
			$reltribeuser_factory->deleteAll($criteria_rel_tribe_id);
			redirect_header('tribes.php?uid='.$uid, 3, _MD_PROFILE_TRIBEDELETED);
		}
		else {redirect_header('tribes.php?uid='.$uid, 3, _MD_PROFILE_NOCACHACA);}
	}
}

include 'footer.php';
?>
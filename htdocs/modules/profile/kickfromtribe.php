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

$modname = basename( dirname( __FILE__ ) );
if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}


$tribe_id = intval($_POST['tribe_id']);
$rel_user_uid = intval($_POST['rel_user_uid']);
$confirm = isset($_POST['confirm'])? intval($_POST['confirm']):'';
if($confirm != 1){
	xoops_confirm(array('rel_user_uid' => $rel_user_uid, 'tribe_id' => $tribe_id ,'confirm' => 1 ) , 'kickfromtribe.php', _MD_PROFILE_ASKCONFIRMKICKFROMTRIBE, _MD_PROFILE_CONFIRMKICK);
}else{
	/**
	* Creating the factory  and the criteria to delete the picture
	* The user must be the owner
	*/
	$reltribeuser_factory = icms_getmodulehandler('reltribeuser', $modname, 'profile' );
	$tribes_factory = icms_getmodulehandler('tribes', $modname, 'profile' );
	$tribe = $tribes_factory->get($tribe_id);
	//	echo "<pre>";
	//	print_r($tribe);
	if($icmsUser->getVar('uid')==$tribe->getVar('owner_uid'))	{
		$criteria_rel_user_uid = new Criteria('rel_user_uid',$rel_user_uid);
		$criteria_tribe_id 	   = new Criteria('rel_tribe_id',$tribe_id);
		$criteria = new CriteriaCompo($criteria_rel_user_uid);
		$criteria->add($criteria_tribe_id);
		/**
		* Try to delete  
		*/
		if($reltribeuser_factory->deleteAll($criteria)){redirect_header('tribes.php', 2, _MD_PROFILE_TRIBEKICKED);}
		else {redirect_header('tribes.php', 2, _MD_PROFILE_NOCACHACA);}
	}
	else {redirect_header('tribes.php', 2, _MD_PROFILE_NOCACHACA);}
}

include 'footer.php';
?>
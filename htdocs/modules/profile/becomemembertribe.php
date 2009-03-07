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

$uid = !empty($_GET['uid'])?intval($_GET['uid']):'';

if ($uid <= 0) {
	if(is_object($xoopsUser)){
		$uid = $xoopsUser->getVar('uid');
	}else{
		header('location: '.ICMS_URL);
		exit();
	}
}

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/userinfo.php?uid='.$uid);
	exit();
}

/**
* Factories of tribes... testing for zend editor  
*/
$reltribeuser_factory = icms_getmodulehandler('reltribeuser', $modname, 'profile' );
$tribes_factory = icms_getmodulehandler('tribes', $modname, 'profile' );

$tribe_id = intval($_POST['tribe_id']);
$uid = intval($xoopsUser->getVar('uid'));

$criteria_uid = new Criteria('rel_user_uid',$uid);
$criteria_tribe_id = new Criteria('rel_tribe_id',$tribe_id);
$criteria = new CriteriaCompo($criteria_uid);
$criteria->add($criteria_tribe_id);
if($reltribeuser_factory->getCount($criteria)<1) {
	$reltribeuser = $reltribeuser_factory->create();
	$reltribeuser->setVar('rel_tribe_id',$tribe_id);
	$reltribeuser->setVar('rel_user_uid',$uid);
	if($reltribeuser_factory->insert($reltribeuser)) {redirect_header('tribes.php',1,_MD_PROFILE_YOUAREMEMBERNOW);}
	else {redirect_header('tribes.php',1,_MD_PROFILE_NOCACHACA);}
} else {
  redirect_header('tribes.php',1,_MD_PROFILE_YOUAREMEMBERALREADY);
}

include 'footer.php';
?>
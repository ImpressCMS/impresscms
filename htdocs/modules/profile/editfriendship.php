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

$modname = basename( dirname( __FILE__ ) );

if(!$xoopsUser) {redirect_header('index.php');}

$friendship_factory = icms_getmodulehandler('friendship', $modname, 'profile' );
$friend2_uid = intval($_POST['friend_uid']);
$marker = (!empty($_POST['marker'])) ? intval($_POST['marker']) : 0;

$friend = new XoopsUser($friend2_uid);

if($marker==1){
	$level = $_POST['level'];
	$cool = $_POST['cool'];
	$sexy = $_POST['hot'];
	$trusty = $_POST['trust'];
	$fan = $_POST['fan'];
	$friendship_id = intval($_POST['friendship_id']);
	
	$criteria= new criteria('friendship_id',$friendship_id);
	$friendships = $friendship_factory->getObjects($criteria);
	$friendship = $friendships[0];
	$friendship->setVar('level',$level);
	$friendship->setVar('cool',$cool);
	$friendship->setVar('hot',$sexy);
	$friendship->setVar('trust',$trusty);
	$friendship->setVar('fan' ,$fan);
	$friend2_uid = intval($friendship->getVar('friend2_uid'));
	$friendship->unsetNew();
	$friendship_factory->insert($friendship);
	redirect_header('friends.php',2,_MD_PROFILE_FRIENDSHIPUPDATED);
	
}
else {$friendship_factory->renderFormSubmit($friend);}

include 'footer.php';
?>
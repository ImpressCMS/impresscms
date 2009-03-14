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


/**
* Factory of petitions created  
*/
$friendpetition_factory = icms_getmodulehandler('friendpetition', $modname, 'profile' );
$friendship_factory = icms_getmodulehandler('friendship', $modname, 'profile' );

/**
* Getting the uid of the user which user want to ask to be friend
*/
$friend1_uid = intval($_POST['friend_uid']);
$friend2_uid = intval($xoopsUser->getVar('uid'));

$criteria_friend1 = new Criteria('friend1_uid',$friend1_uid);
$criteria_friend2 = new Criteria('friend2_uid',$friend2_uid);
	
$criteria_delete1 = new CriteriaCompo($criteria_friend1);
$criteria_delete1->add($criteria_friend2);

$friendship_factory->deleteAll($criteria_delete1);

$criteria_friend1 = new Criteria('friend1_uid',$friend2_uid);
$criteria_friend2 = new Criteria('friend2_uid',$friend1_uid);

$criteria_delete1 = new CriteriaCompo($criteria_friend1);
$criteria_delete1->add($criteria_friend2);

$friendship_factory->deleteAll($criteria_delete1);

redirect_header('friends.php',3,_MD_PROFILE_FRIENDSHIPTERMINATED);	

include 'footer.php';
?>
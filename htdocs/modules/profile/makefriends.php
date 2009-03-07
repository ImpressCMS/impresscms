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

$xoopsOption['template_main'] = 'profile_index.html';
include_once 'header.php';

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/');
	exit();
}

/**
* Factory of petitions created
*/
$friendpetition_factory = icms_getmodulehandler('friendpetition', $modname, 'profile' );
$friendship_factory = icms_getmodulehandler('friendship', $modname, 'profile' );

$petition_id = intval($_POST['petition_id']);
$friendship_level = intval($_POST['level']);
$uid = intval($xoopsUser->getVar('uid'));

if(!($GLOBALS['xoopsSecurity']->check())) {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);}

$criteria= new criteriaCompo(new criteria('friendpet_id',$petition_id));
$criteria->add(new criteria('petioned_uid',$uid));
if($friendpetition_factory->getCount($criteria)>0)
{
	if(($friendship_level>0) && ($petition = $friendpetition_factory->getObjects($criteria)))
	{
		$friend1_uid = $petition[0]->getVar('petitioner_uid');
		$friend2_uid = $petition[0]->getVar('petioned_uid');
		
		$newfriendship1 = $friendship_factory->create(true);
		$newfriendship1->setVar('level',3);
		$newfriendship1->setVar('friend1_uid',$friend1_uid);
		$newfriendship1->setVar('friend2_uid',$friend2_uid);
		$newfriendship2 = $friendship_factory->create(true);
		$newfriendship2->setVar('level',$friendship_level);
		$newfriendship2->setVar('friend1_uid',$friend2_uid);
		$newfriendship2->setVar('friend2_uid',$friend1_uid);
		$friendpetition_factory->deleteAll($criteria);
		$friendship_factory->insert($newfriendship1);
		$friendship_factory->insert($newfriendship2);
	
		redirect_header(ICMS_URL.'/modules/profile/friends.php?uid='.$friend2_uid,3,_MD_PROFILE_FRIENDMADE);
	}
	else
	{
		if($friendship_level==0)
		{
			$friendpetition_factory->deleteAll($criteria);
			redirect_header(ICMS_URL.'/modules/profile/video.php?uid='.$uid,3,_MD_PROFILE_FRIENDSHIPNOTACCEPTED);
		}
		redirect_header(ICMS_URL.'/modules/profile/index.php?uid='.$uid,3,_MD_PROFILE_NOCACHACA);
	}
}
else {redirect_header(ICMS_URL.'/modules/profile/index.php?uid='.$uid,3,_MD_PROFILE_NOCACHACA);}

include 'footer.php';
?>
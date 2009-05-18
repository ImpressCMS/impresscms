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

$modname = basename( dirname( __FILE__ ) );
if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}


/**
* Factory of pictures created  
*/
$ishot_factory = icms_getmodulehandler('ishot', $modname, 'profile' );

$uid_voted = intval($_POST['uid_voted']);
$ishot = intval($_POST['ishot']);
$uid_voter = intval($icmsUser->getVar('uid'));

if(!($GLOBALS['xoopsSecurity']->check())) {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);}

/**
* Verify if user is trying to vote for himself
*/
if($uid_voter==$uid_voted) {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_CANTVOTEOWN);}

/**
* Verify that this user hasn't voted or added this user yet
*/
$criteria_uidvoter = new criteria('uid_voter',$uid_voter);
$criteria_uidvoted = new criteria('uid_voted',$uid_voted);
$criteria = new criteriaCompo($criteria_uidvoter);
$criteria->add($criteria_uidvoted);

if($ishot_factory->getCount($criteria)==0)
{
	
	$vote = $ishot_factory->create(true);
	$vote->setVar('uid_voted',$uid_voted);
	$vote->setVar('uid_voter',$uid_voter);
	
	if($ishot==1) {$vote->setVar('ishot',1);}
	else {$vote->setVar('ishot',0);}
	
	$ishot_factory->insert($vote);
	redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_VOTED);
}
else {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_ALREADYVOTED);}

include 'footer.php';
?>
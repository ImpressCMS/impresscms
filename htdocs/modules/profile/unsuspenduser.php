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

if(!($GLOBALS['xoopsSecurity']->check())) {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);}

$uid = intval($_POST['uid']);
/**
* Creating the factory  loading the picture changing its caption
*/
$suspensions_factory = icms_getmodulehandler('suspensions', $modname, 'profile' );
$suspension = $suspensions_factory->create(false);
$suspension->load($uid);

if ($xoopsUser->isAdmin(1))
{
	$member_handler =& xoops_gethandler('member');
	$thisUser =& $member_handler->getUser($uid);

	$thisUser->setVar('email', $suspension->getVar('old_email','n'));
	$thisUser->setVar('pass', $suspension->getVar('old_pass','n'));
		$thisUser->setVar('salt', $suspension->getVar('old_salt','n'));
		$thisUser->setVar('pass_expired', $suspension->getVar('old_pass_expired','n'));
		$thisUser->setVar('enc_type', $suspension->getVar('old_enc_type','n'));
	$thisUser->setVar('user_sig', $suspension->getVar('old_signature','n'));
	$member_handler->insertUser($thisUser);
	
	$criteria = new Criteria('uid',$uid);
	$suspensions_factory->deleteAll($criteria);
	redirect_header('index.php?uid='.$uid,3,_MD_PROFILE_USERUNSUSPENDED);
}

include 'footer.php';
?>
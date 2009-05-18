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
if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}

include_once ICMS_ROOT_PATH.'/class/criteria.php';

if(!($GLOBALS['xoopsSecurity']->check())) {redirect_header($_SERVER['HTTP_REFERER'], 5, _MD_PROFILE_TOKENEXPIRED);}

$uid = intval($_POST['uid']);
/**
* Creating the factory  loading the picture changing its caption
*/
$suspensions_factory = icms_getmodulehandler('suspensions', $modname, 'profile' );
$suspension = $suspensions_factory->create(false);
$suspension->load($uid);

if($icmsUser->isAdmin(1))
{
	$member_handler =& xoops_gethandler('member');
	$thisUser =& $member_handler->getUser($uid);
	$suspension->setVar('uid', $uid);
	$suspension->setVar('old_email', $thisUser->getVar('email'));
	$suspension->setVar('old_pass',$thisUser->getVar('pass'));
		$thisUser->setVar('old_salt', $thisUser->getVar('salt'));
		$thisUser->setVar('old_pass_expired', $thisUser->getVar('pass_expired'));
		$thisUser->setVar('old_enc_type', $thisUser->getVar('enc_type'));
	$suspension->setVar('old_signature',$thisUser->getVar('user_sig'));
	$suspension->setVar('suspension_time',time()+intval($_POST['time']));
	$suspensions_factory->insert($suspension);
	$thisUser->setVar('email',md5(time()));
	$thisUser->setVar('pass',md5(time()));
	
	$thisUser->setVar('user_sig',sprintf(_MD_PROFILE_SUSPENDED,formatTimestamp( time()+intval($_POST['time']),'m')));
	$member_handler->insertUser($thisUser);
	redirect_header('index.php?uid='.$uid,300,_MD_PROFILE_USERSUSPENDED);
}

include_once 'footer.php';
?>
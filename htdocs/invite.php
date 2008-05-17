<?php
/**
* All functions for Registering users by invitation are going through here.
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		XOOPS
* @author		http://www.xoops.org The XOOPS Project
* @author		modified by stranger <stranger@impresscms.ir>
* @version		$Id$
*/

$xoopsOption['pagetype'] = 'user';

include 'mainfile.php';
$myts =& MyTextSanitizer::getInstance();

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

// If not a user and invite needs one, redirect
if ($xoopsConfigUser['activation_type'] == 3 && $xoopsConfigUser['allow_register'] == 0 && !is_object($xoopsUser)) {
	redirect_header('index.php', 6, _US_INVITEBYMEMBER);
    exit();
}

$op = !isset($_POST['op']) ? 'invite' : $_POST['op'];
$email = isset($_POST['email']) ? trim($myts->stripSlashesGPC($_POST['email'])) : '';

switch ( $op ) {
case 'finish':
	include 'header.php';
	$stop = '';
	if (!$GLOBALS['xoopsSecurity']->check()) {
	    $stop .= implode('<br />', $GLOBALS['xoopsSecurity']->getErrors())."<br />";
	}
	if (!checkEmail($email)) {
		$stop .= _US_INVALIDMAIL.'<br />';
	}
	if ( empty($stop) ) {
		$invite_code = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$xoopsDB =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$sql = sprintf('INSERT INTO '.$xoopsDB->prefix('invites').' (invite_code, from_id, invite_to, invite_date, extra_info) VALUES (%s, %d, %s, %d, %s)', 
			$xoopsDB->quoteString(addslashes($invite_code)),
			is_object($xoopsUser)?$xoopsUser->getVar('uid'):0,
			$xoopsDB->quoteString(addslashes($email)),
			time(),
			$xoopsDB->quoteString(addslashes(serialize(array())))
		);
		$xoopsDB->query($sql);
		// if query executed successful
		if ($xoopsDB->getAffectedRows() == 1) {
			$xoopsMailer =& getMailer();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('invite.tpl');
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
			$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
			$xoopsMailer->assign('SITEURL', ICMS_URL."/");
			$xoopsMailer->assign('USEREMAIL', $email);
			$xoopsMailer->assign('REGISTERLINK', ICMS_URL.'/register.php?code='.$invite_code);
			$xoopsMailer->setToEmails($email);
			$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
			$xoopsMailer->setFromName($xoopsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_INVITEREGLINK,ICMS_URL));
			if ( !$xoopsMailer->send() ) {
				$stop .= _US_INVITEMAILERR;
			} else {
				echo _US_INVITESENT;
			}
		} else {
			$stop .= _US_INVITEDBERR;
		}
	} 
	if (! empty($stop)) {
		echo "<span style='color:#ff0000; font-weight:bold;'>$stop</span>";
		include 'include/inviteform.php';
		$invite_form->display();
	}
	include 'footer.php';
	break;
case 'invite':
default:
	include 'header.php';
	include 'include/inviteform.php';
	$invite_form->display();
	include 'footer.php';
	break;
}
?>
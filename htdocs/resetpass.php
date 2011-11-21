<?php
/**
 * All functions for Password Expiry & Reset Password generator are going through here.
 * Form and process for resetting password and sending to user
 * 
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Member
 * @subpackage	Users
 * @since		ImpressCMS 1.1
 * @author		Vaughan Montgomery <vaughan@impresscms.org>
 * @author		The ImpressCMS Project
 * @version		SVN: $Id$
 */

$xoopsOption['pagetype'] = 'user';
include 'mainfile.php';
/* the following are passed through $_POST/$_GET
 *	'email' => array('email', 'options' => array(0, 1)),
 *	'username' => 'str',
 *	'c_password' => 'str',
 *	'password' => 'str',
 *	'password2' => 'str',
 */
$filter_get = array(
	'email' => array('email', 'options' => array(0, 1)),
);
$filter_post = array(
	'email' => array('email', 'options' => array(0, 1)),
);
if (!empty($_GET)) {
    $clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
    extract($clean_GET);
}
if (!empty($_POST)) {
    $clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
    extract($clean_POST);
}

global $icmsConfigUser;

if ($email == '' || $username == '') {
	redirect_header('user.php', 2, _US_SORRYNOTFOUND);
} elseif ($password == '' || $password2 == '') {
	redirect_header('user.php', 2, _US_SORRYMUSTENTERPASS);
}
if ((isset($password)) && ($password !== $password2)) {
	redirect_header('user.php', 2, _US_PASSNOTSAME);
} elseif (($password !== '') && (strlen($password) < $icmsConfigUser['minpass'])) {
	redirect_header('user.php', 2, sprintf(_US_PWDTOOSHORT, $icmsConfigUser['minpass']));
}

$member_handler = icms::handler('icms_member');
$getuser =& $member_handler->getUsers(new icms_db_criteria_Item('email', icms_core_DataFilter::addSlashes($email)));

if (empty($getuser)) {
	redirect_header('user.php', 2, _US_SORRYNOTFOUND);
} else {
	if (strtolower($getuser[0]->getVar('uname')) !== strtolower($username)) {
		redirect_header('user.php', 2, _US_SORRYUNAMENOTMATCHEMAIL);
	} else {
		$current_pass = $getuser[0]->getVar('pass');
		$current_salt = $getuser[0]->getVar('salt');
		$enc_type = $getuser[0]->getVar('enc_type');

		$icmspass = new icms_core_Password();

		$c_pass = $icmspass->encryptPass($c_password, $current_salt, $enc_type, 1);

		if ($c_pass !== $current_pass) {
			redirect_header('user.php', 2, _US_SORRYINCORRECTPASS);
		}

		$salt = $icmspass->createSalt();
		$pass = $icmspass->encryptPass($password, $salt, $icmsConfigUser['enc_type']);
		$xoopsMailer = new icms_messaging_Handler();
		$xoopsMailer->useMail();
		$xoopsMailer->setTemplate('resetpass2.tpl');
		$xoopsMailer->assign('SITENAME', $icmsConfig['sitename']);
		$xoopsMailer->assign('ADMINMAIL', $icmsConfig['adminmail']);
		$xoopsMailer->assign('SITEURL', ICMS_URL.'/');
		$xoopsMailer->assign('IP', $_SERVER['REMOTE_ADDR']);
		$xoopsMailer->setToUsers($getuser[0]);
		$xoopsMailer->setFromEmail($icmsConfig['adminmail']);
		$xoopsMailer->setFromName($icmsConfig['sitename']);
		$xoopsMailer->setSubject(sprintf(_US_PWDRESET, ICMS_URL));
		if (!$xoopsMailer->send()) {
			echo $xoopsMailer->getErrors();
		}

		$sql = sprintf("UPDATE %s SET pass = '%s', salt = '%s', pass_expired = '%u', enc_type = '%u' WHERE uid = '%u'",
						icms::$xoopsDB->prefix('users'),
						$pass,
						$salt,
						0,
						(int) $icmsConfigUser['enc_type'],
						(int) $getuser[0]->getVar('uid')
					);
		if (!icms::$xoopsDB->queryF($sql)) {
			include 'header.php';
			echo _US_RESETPWDNG;
			include 'footer.php';
			exit();
		}
		unset($salt, $pass);
		redirect_header('user.php', 3, sprintf(_US_PWDRESET, $getuser[0]->getVar('uname')), FALSE);
	}
}

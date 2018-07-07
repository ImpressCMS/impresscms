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
 */

$xoopsOption['pagetype'] = 'user';
/* the following are passed through $_POST/$_GET
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
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, false);
	extract($clean_GET);
}
if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, false);
	extract($clean_POST);
}

global $icmsConfigUser;
if ($password == '' || $password2 == '') {
	redirect_header('user.php?op=resetpass', 3, sprintf(_US_SORRYMUSTENTERPASS, icms::$user->getVar('uname')), false);
}
if ((isset($password)) && ($password !== $password2)) {
	redirect_header('user.php?op=resetpass', 3, sprintf(_US_PASSNOTSAME, ''), false);
} elseif (($password !== '') && (strlen($password) < $icmsConfigUser['minpass'])) {
	redirect_header('user.php?op=resetpass', 2, sprintf(_US_PWDTOOSHORT, $icmsConfigUser['minpass']), false);
}

if (!icms::$user) {
	redirect_header('user.php', 2, sprintf(_US_SORRYNOTFOUND, 3, ''), false);
	} else {
		$icmspass = new icms_core_Password();

	if (!$icmspass->verifyPass($c_password, icms::$user->getVar('login_name'))) {
		redirect_header('user.php?op=resetpass', 2, _US_SORRYINCORRECTPASS);
		}

		$pass = $icmspass->encryptPass($password);
		$mailer = new icms_messaging_Handler();
		$mailer->useMail();
		$mailer->setTemplate('resetpass2.tpl');
		$mailer->assign('SITENAME', $icmsConfig['sitename']);
		$mailer->assign('ADMINMAIL', $icmsConfig['adminmail']);
		$mailer->assign('SITEURL', ICMS_URL.'/');
		$mailer->assign('IP', $_SERVER['REMOTE_ADDR']);
	$mailer->setToUsers(icms::$user->getVar('uid'));
		$mailer->setFromEmail($icmsConfig['adminmail']);
		$mailer->setFromName($icmsConfig['sitename']);
		$mailer->setSubject(sprintf(_US_PWDRESET, ICMS_URL));
		if (!$mailer->send()) {
			echo $mailer->getErrors();
		}

		$sql = sprintf("UPDATE %s SET pass = '%s', pass_expired = '%u' WHERE uid = '%u'",
						icms::$xoopsDB->prefix('users'),
						$pass,
						0,
					(int) icms::$user->getVar('uid')
					);
	if (!icms::$xoopsDB->query($sql)) {
			include 'header.php';
			echo _US_RESETPWDNG;
			include 'footer.php';
			exit();
		}
		unset($pass);
	redirect_header('user.php', 3, sprintf(_US_PWDRESET, icms::$user->getVar('uname')), false);
}

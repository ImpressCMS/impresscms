<?php
/**
 * Reveals an email address hidden by the email protection preference, after the captcha
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Captcha
 */

$xoopsOption['pagetype'] = 'user';
include 'mainfile.php';
include ICMS_ROOT_PATH . '/header.php';

$token = (string) ($_REQUEST['c'] ?? '');
$email = \Icms\Captcha\Mailhide::decode($token);
if ($email === null || !icms_core_DataFilter::checkVar($email, 'email')) {
	redirect_header(ICMS_URL, 3, ICMS_CAPTCHA_MAILHIDE_INVALID);
}

$captcha = \Icms\Captcha\Manager::instance();
$revealed = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$result = $captcha->verify();
	$revealed = $result->isValid();
	$message = $result->getMessage();
} elseif (!$captcha->isActive()) {
	$revealed = true;
}

if ($revealed) {
	$address = htmlspecialchars($email, ENT_QUOTES);
	echo "<p><a href='mailto:{$address}'>{$address}</a></p>";
	include ICMS_ROOT_PATH . '/footer.php';
	exit;
}

if ($message !== '') {
	echo "<div class='errorMsg'>{$message}</div>";
}

echo "<form method='post' action='" . ICMS_URL . "/mailhide.php'>"
	. "<input type='hidden' name='c' value='" . htmlspecialchars($token, ENT_QUOTES) . "' />"
	. "<p><label for='icmscaptcha'>" . ICMS_CAPTCHA_CAPTION . "</label> " . $captcha->render() . "</p>"
	. "<p><input type='submit' value='" . htmlspecialchars(ICMS_CAPTCHA_MAILHIDE_REVEAL, ENT_QUOTES) . "' /></p>"
	. "</form>";

include ICMS_ROOT_PATH . '/footer.php';

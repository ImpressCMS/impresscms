<?php
// $Id$
/**
 * Administration of banners, mainfile
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	Administration
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id$
 */

if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {exit('Access Denied');}
include_once ICMS_ROOT_PATH.'/modules/system/admin/banners/banners.php';
$allowedHTML = array('htmlcode');

if (!empty($_POST)) { foreach ($_POST as $k => $v) { if (!in_array($k,$allowedHTML)) {${$k} = StopXSS($v);} else {${$k} = $v;}}}
if (!empty($_GET)) { foreach ($_GET as $k => $v) { if (!in_array($k,$allowedHTML)) {${$k} = StopXSS($v);} else {${$k} = $v;}}}

$op = (isset($_GET['op']))?trim(StopXSS($_GET['op'])):((isset($_POST['op']))?trim(StopXSS($_POST['op'])):'BannersAdmin');

switch($op)
{
	case 'BannersAdmin':
		BannersAdmin();
		break;

	case 'BannersAdd':
		if (!icms::$security->check())
		{
			redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top', 3, implode('<br />', icms::$security->getErrors()));
		}
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$cid = isset($_POST['cid']) ? (int) ($_POST['cid']) : 0;
		$imageurl = isset($_POST['imageurl']) ? trim($_POST['imageurl']) : '';
		$clickurl = isset($_POST['clickurl']) ? trim($_POST['clickurl']) : '';
		$imptotal = isset($_POST['imptotal']) ? (int) ($_POST['imptotal']) : 0;
		$htmlbanner = isset($_POST['htmlbanner']) ? (int) ($_POST['htmlbanner']) : 0;
		$htmlcode = isset($_POST['htmlcode']) ? trim($_POST['htmlcode']) : '';
		if ($cid <= 0) {redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top');}
		$db =& icms_db_Factory::instance();
		$myts =& icms_core_Textsanitizer::getInstance();
		$newid = $db->genId($db->prefix('banner').'_bid_seq');
		$sql = sprintf("INSERT INTO %s (bid, cid, imptotal, impmade, clicks, imageurl, clickurl, date, htmlbanner, htmlcode) VALUES ('%d', '%d', '%d', '1', '0', %s, %s, '%d', '%d', %s)", $db->prefix('banner'), (int) ($newid), $cid, $imptotal, $db->quoteString($myts->stripSlashesGPC($imageurl)), $db->quoteString($myts->stripSlashesGPC($clickurl)), time(), $htmlbanner, $db->quoteString($myts->stripSlashesGPC($htmlcode)));
		$db->query($sql);
		redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top',1,_AM_DBUPDATED);
		break;

	case 'BannerAddClient':
		if (!icms::$security->check())
		{
			redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top', 3, implode('<br />', icms::$security->getErrors()));
		}
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$login = isset($_POST['login']) ? trim($_POST['login']) : '';
		$passwd = isset($_POST['passwd']) ? trim($_POST['passwd']) : '';
		$extrainfo = isset($_POST['extrainfo']) ? trim($_POST['extrainfo']) : '';
		$db =& icms_db_Factory::instance();
		$myts =& icms_core_Textsanitizer::getInstance();
		$newid = $db->genId($xoopsDB->prefix('bannerclient').'_cid_seq');
		$sql = sprintf("INSERT INTO %s (cid, name, contact, email, login, passwd, extrainfo) VALUES ('%d', %s, %s, %s, %s, %s, %s)", $db->prefix("bannerclient"), (int) ($newid), $db->quoteString($myts->stripSlashesGPC($name)), $db->quoteString($myts->stripSlashesGPC($contact)), $db->quoteString($myts->stripSlashesGPC($email)), $db->quoteString($myts->stripSlashesGPC($login)), $db->quoteString($myts->stripSlashesGPC($passwd)), $db->quoteString($myts->stripSlashesGPC($extrainfo)));
		$db->query($sql);
		redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top',1,_AM_DBUPDATED);
		break;

	case 'BannerFinishDelete':
		icms_cp_header();
		icms_core_Message::confirm(array('op' => 'BannerFinishDelete2', 'bid' => (int) ($_GET['bid']), 'fct' => 'banners'), 'admin.php', _AM_SUREDELE);
		icms_cp_footer();
		break;

	case 'BannerFinishDelete2':
		$bid = isset($_POST['bid']) ? (int) ($_POST['bid']) : 0;
		if ($bid <= 0 | !icms::$security->check())
		{
			redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top', 3, implode('<br />', icms::$security->getErrors()));
		}
		$db =& icms_db_Factory::instance();
		$sql = sprintf('DELETE FROM %s WHERE bid = %u', $db->prefix('bannerfinish'), $bid);
		$db->query($sql);
		redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top',1,_AM_DBUPDATED);
		break;

	case 'BannerDelete':
		$bid = isset($_GET['bid']) ? (int) ($_GET['bid']) : 0;
		if ($bid > 0) {BannerDelete($bid);}
		break;

	case 'BannerDelete2':
		$bid = isset($_POST['bid']) ? (int) ($_POST['bid']) : 0;
		if ($bid <= 0 | !icms::$security->check())
		{
			redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top', 3, implode('<br />', icms::$security->getErrors()));
		}
		$db =& icms_db_Factory::instance();
		$sql = sprintf('DELETE FROM %s WHERE bid = %u', $db->prefix('banner'), $bid);
		$db->query($sql);
		redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top',1,_AM_DBUPDATED);
		break;

	case 'BannerEdit':
		$bid = isset($_GET['bid']) ? (int) ($_GET['bid']) : 0;
		if ($bid > 0) {BannerEdit($bid);}
		break;

	case 'BannerChange':
		$bid = isset($_POST['bid']) ? (int) ($_POST['bid']) : 0;
		$cid = isset($_POST['cid']) ? (int) ($_POST['cid']) : 0;
		if (($cid <= 0 || $bid <= 0) | !icms::$security->check())
		{
			redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top', 3, implode('<br />', icms::$security->getErrors()));
		}
		$imageurl = isset($_POST['imageurl']) ? trim($_POST['imageurl']) : '';
		$clickurl = isset($_POST['clickurl']) ? trim($_POST['clickurl']) : '';
		$imptotal = isset($_POST['imptotal']) ? (int) ($_POST['imptotal']) : 0;
		$impadded = isset($_POST['impadded']) ? (int) ($_POST['impadded']) : 0;
		$htmlbanner = isset($_POST['htmlbanner']) ? (int) ($_POST['htmlbanner']) : 0;
		$htmlcode = isset($_POST['htmlcode']) ? trim($_POST['htmlcode']) : '';
		$db =& icms_db_Factory::instance();
		$myts =& icms_core_Textsanitizer::getInstance();
		$sql = sprintf("UPDATE %s SET cid = '%d', imptotal = '%d', imageurl = %s, clickurl = %s, htmlbanner = '%d', htmlcode = %s WHERE bid = '%d'", $db->prefix('banner'), $cid, $imptotal + $impadded, $db->quoteString($myts->stripSlashesGPC($imageurl)), $db->quoteString($myts->stripSlashesGPC($clickurl)), $htmlbanner, $db->quoteString($myts->stripSlashesGPC($htmlcode)), $bid);
		$db->query($sql);
		redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top',1,_AM_DBUPDATED);
		break;

	case 'BannerClientDelete':
		$cid = isset($_GET['cid']) ? (int) ($_GET['cid']) : 0;
		if ($cid > 0) {BannerClientDelete($cid);}
		break;

	case 'BannerClientDelete2':
		$cid = isset($_POST['cid']) ? (int) ($_POST['cid']) : 0;
		$db =& icms_db_Factory::instance();
		if ($cid <= 0 | !icms::$security->check())
		{
			redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top', 3, implode('<br />', icms::$security->getErrors()));
		}
		$sql = sprintf("DELETE FROM %s WHERE cid = '%u'", $db->prefix('banner'), $cid);
		$db->query($sql);
		$sql = sprintf("DELETE FROM %s WHERE cid = '%u'", $db->prefix('bannerclient'), $cid);
		$db->query($sql);
		redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top',1,_AM_DBUPDATED);
		break;

	case 'BannerClientEdit':
		$cid = isset($_GET['cid']) ? (int) ($_GET['cid']) : 0;
		if ($cid > 0) {BannerClientEdit($cid);}
		break;

	case 'BannerClientChange':
		$cid = isset($_POST['cid']) ? (int) ($_POST['cid']) : 0;
		if ($cid <= 0 | !icms::$security->check())
		{
			redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top', 3, implode('<br />', icms::$security->getErrors()));
		}
		$name = isset($_POST['name']) ? trim($_POST['name']) : '';
		$contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';
		$email = isset($_POST['email']) ? trim($_POST['email']) : '';
		$login = isset($_POST['login']) ? trim($_POST['login']) : '';
		$passwd = isset($_POST['passwd']) ? trim($_POST['passwd']) : '';
		$extrainfo = isset($_POST['extrainfo']) ? trim($_POST['extrainfo']) : '';
		$db =& icms_db_Factory::instance();
		$myts =& icms_core_Textsanitizer::getInstance();
		$sql = sprintf("UPDATE %s SET name = %s, contact = %s, email = %s, login = %s, passwd = %s, extrainfo = %s WHERE cid = '%d'",
		$db->prefix("bannerclient"),
		$db->quoteString( $myts->stripSlashesGPC($name) ),
		$db->quoteString( $myts->stripSlashesGPC($contact) ),
		$db->quoteString( $myts->stripSlashesGPC($email) ),
		$db->quoteString( $myts->stripSlashesGPC($login) ),
		$db->quoteString( $myts->stripSlashesGPC($passwd) ),
		$db->quoteString( $myts->stripSlashesGPC($extrainfo) ),
		$cid
		);
		$db->query($sql);
		redirect_header('admin.php?fct=banners&amp;op=BannersAdmin#top',1,_AM_DBUPDATED);
		break;

	default:
		BannersAdmin();
		break;
}

?>
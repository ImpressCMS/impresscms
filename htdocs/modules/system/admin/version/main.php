<?php
/**
* ImpressCMS Version Checker
*
* This page checks if the ImpressCMS install runs the latest released version
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		malanciault <marcan@impresscms.org)
* @version		$Id: error.php 429 2008-01-02 22:21:41Z malanciault $
*/

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin() ) {
icms_debug($_GET['mid']);
    exit("Access Denied");
}


$rssurl= "http://www.impresscms.org/impresscms_version.xml";
$rssfile = XOOPS_CACHE_PATH.'/impresscms_version.xml';
$rssdata = '';

//$time_before_cache_again = 86400;
$time_before_cache_again = 1;

if (!file_exists($rssfile) || filemtime($rssfile) < time() - $time_before_cache_again) {
	require_once XOOPS_ROOT_PATH.'/class/snoopy.php';
        $snoopy = new Snoopy;
        if ($snoopy->fetch($rssurl)) {
            $rssdata = $snoopy->results;
            if (false !== $fp = fopen($rssfile, 'w')) {
                fwrite($fp, $rssdata);
            }
        }
} else {
	if (false !== $fp = fopen($rssfile, 'r')) {
		while (!feof ($fp)) {
			$rssdata .= fgets($fp, 4096);
		}
		fclose($fp);
	}
}     

if ($rssdata != '') {
	include_once XOOPS_ROOT_PATH.'/class/xml/rss/xmlrss2parser.php';
	$rss2parser = new XoopsXmlRss2Parser($rssdata);
	if (false != $rss2parser->parse()) {
		// get the only item on that xml
		$items =& $rss2parser->getItems();

		$latest_item = $items[0];
		$latest_build = $latest_item['guid'];
		$icmsAdminTpl->assign('latest_version', $latest_item['title']);
		$icmsAdminTpl->assign('your_version', XOOPS_VERSION);
		if ($latest_build > ICMS_BUILD) {
			$icmsAdminTpl->assign('update_available', true);	
			$icmsAdminTpl->assign('latest_url', $latest_item['link']);			
		}
		
	} else {
		echo $rss2parser->getErrors();
	}
}

xoops_cp_header();
$icmsAdminTpl->display(XOOPS_ROOT_PATH.'/modules/system/templates/admin/system_adm_version.html');
xoops_cp_footer();
?>
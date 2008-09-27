<?php
/**
*
*
*
* @copyright		http://lexode.info/mods/ Venom (Original_Author)
* @copyright		Author_copyrights.txt
* @copyright		http://www.impresscms.org/ The ImpressCMS Project
* @license			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package			modules
* @since			XOOPS
* @author			Venom <webmaster@exode-fr.com>
* @author			modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version			$Id$
*/

include("admin_header.php");


xoops_cp_header();

$module_handler =& xoops_gethandler('module');
$versioninfo =& $module_handler->get($xoopsModule->getVar('mid'));

mp_adminmenu(0, _MP_ABOUT );

// Left headings...
echo "<img src='" . XOOPS_URL . "/modules/".$xoopsModule->dirname()."/" . $versioninfo->getInfo('image') . "' alt='' hspace='0' vspace='0' align='left' style='margin-right: 10px;' /></a>";
echo "<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $versioninfo->getInfo('name') . " version  (" . $versioninfo->getInfo('status_version') . ")</div>";

if  ( $versioninfo->getInfo('author_realname') != '')
{
	$author_name = $versioninfo->getInfo('author') . " ";
} else
{
	$author_name = $versioninfo->getInfo('author');
}

echo "<div style = 'line-height: 16px; font-weight: bold; display: block;'>" . _MP_BY . " " .$author_name;
echo "</div>";
echo "<div style = 'line-height: 16px; display: block;'>" . $versioninfo->getInfo('license') . "</div><br /><br /></>\n";


// Author Information
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
echo "<tr>";
echo "<td colspan='2' class='bg3' align='left'><strong>" . _MP_AUTHOR_INFO . "</strong></td>";
echo "</tr>";

If ( $versioninfo->getInfo('$author_name') != '' )
{
	echo "<tr>";
	echo "<td class='head' width='150px' align='left'>" ._MP_AUTHOR_NAME . "</td>";
	echo "<td class='even' align='left'>" . $author_name . "</td>";
	echo "</tr>";
}
If ( $versioninfo->getInfo('author_website_url') != '' )
{
	echo "<tr>";
	echo "<td class='head' width='150px' align='left'>" . _MP_AUTHOR_WEBSITE . "</td>";
	echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('author_website_url') . "' target='_blank'>" . $versioninfo->getInfo('author_website_name') . "</a></td>";
	echo "</tr>";
}
If ( $versioninfo->getInfo('author_email') != '' )
{
	echo "<tr>";
	echo "<td class='head' width='150px' align='left'>" . _MP_AUTHOR_EMAIL . "</td>";
	echo "<td class='even' align='left'><a href='mailto:" . $versioninfo->getInfo('author_email') . "'>" . $versioninfo->getInfo('author_email') . "</a></td>";
	echo "</tr>";
}
If ( $versioninfo->getInfo('credits') != '' )
{
	echo "<tr>";
	echo "<td class='head' width='150px' align='left'>" . _MP_AUTHOR_CREDITS . "</td>";
	echo "<td class='even' align='left'>" . $versioninfo->getInfo('credits') . "</td>";
	echo "</tr>";
}

echo "</table>";
echo "<br />\n";

// Module Developpment information
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
echo "<tr>";
echo "<td colspan='2' class='bg3' align='left'><strong>" . _MP_MODULE_INFO . "</strong></td>";
echo "</tr>";

If ( $versioninfo->getInfo('status') != '' )
{
	echo "<tr>";
	echo "<td class='head' width='200' align='left'>" . _MP_MODULE_STATUS . "</td>";
	echo "<td class='even' align='left'>" . $versioninfo->getInfo('status') . "</td>";
	echo "</tr>";
}

If ( $versioninfo->getInfo('demo_site_url') != '' )
{
	echo "<tr>";
	echo "<td class='head' align='left'>" . _MP_MODULE_DEMO . "</td>";
	echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('demo_site_url') . "' target='blank'>" . $versioninfo->getInfo('demo_site_name') . "</a></td>";
	echo "</tr>";
}

If ( $versioninfo->getInfo('support_site_url') != '' )
{
	echo "<tr>";
	echo "<td class='head' align='left'>" . _MP_MODULE_SUPPORT . "</td>";
	echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('support_site_url') . "' target='blank'>" . $versioninfo->getInfo('support_site_name') . "</a></td>";
	echo "</tr>";
}

If ( $versioninfo->getInfo('submit_bug') != '' )
{
	echo "<tr>";
	echo "<td class='head' align='left'>" . _MP_MODULE_BUG . "</td>";
	echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('submit_bug') . "' target='blank'>" . _MP_MODULE_BUG . "</a></td>";
	echo "</tr>";
}
If ( $versioninfo->getInfo('submit_feature') != '' )
{
	echo "<tr>";
	echo "<td class='head' align='left'>" . _MP_MODULE_FEATURE . "</td>";
	echo "<td class='even' align='left'><a href='" . $versioninfo->getInfo('submit_feature') . "' target='_blank'>" . _MP_MODULE_FEATURE . "</a></td>";
	echo "</tr>";
}

echo "</table>";

// Warning
If ( $versioninfo->getInfo('warning') != '' )
{
	echo "<br />\n";
	echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
	echo "<tr>";
	echo "<td class='bg3' align='left'><strong>" . _MP_MODULE_DISCLAIMER . "</strong></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class='even' align='left'>" . $versioninfo->getInfo('warning') . "</td>";
	echo "</tr>";
	
	echo "</table>";
}


// Author's note
If ( $versioninfo->getInfo('author_word') != '' )
{
	echo "<br />\n";
	echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
	echo "<tr>";
	echo "<td class='bg3' align='left'><strong>" . _MP_AUTHOR_WORD . "</strong></td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class='even' align='left'>" . $versioninfo->getInfo('author_word') . "</td>";
	echo "</tr>";
	
	echo "</table>";
}

xoops_cp_footer();

?>
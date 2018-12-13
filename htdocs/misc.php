<?php
// $Id: misc.php 12399 2014-01-25 17:02:01Z skenow $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/**
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	    core
 * @since		XOOPS
 * @author		http://www.xoops.org The XOOPS Project
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version	    $Id: misc.php 12399 2014-01-25 17:02:01Z skenow $
 * @version	$Id: misc.php 12399 2014-01-25 17:02:01Z skenow $
 **/

include 'mainfile.php';
icms_loadLanguageFile('core', 'misc');
/* set filter types, if not strings */
$filter_post[] = array(
		'uid' => 'int',
		'start' => 'int',
);

$filter_get[] = array(
		'uid' => 'int',
		'start' => 'int',
);

/* set default values for variables */
$action = $type = "";

/* filter the user input */
if (!empty($_GET)) {
	// in places where strict mode is not used for checkVarArray, make sure filter_ vars are not overwritten
	if (isset($_GET['filter_post'])) unset ($_GET['filter_post']);
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
	extract($clean_GET);
}
if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
	extract($clean_POST);
}

if ($action == 'showpopups') {
	xoops_header(false);
	// show javascript close button?
	$closebutton = 1;
	switch($type)
	{
		case 'smilies':
			if ($target == '' || !preg_match('/^[0-9a-z_]*$/i', $target)) {} else {
				echo "<script type=\"text/javascript\"><!--//
				function doSmilie(addSmilie) {
				var currentMessage = window.opener.xoopsGetElementById(\"".$target."\").value;
				window.opener.xoopsGetElementById(\"".$target."\").value=currentMessage+addSmilie;
				return;
				}
				//-->
				</script>
				";
				echo '</head><body>
				<table width="100%" class="outer">
				<tr><th colspan="3">'._MSC_SMILIES.'</th></tr>
				<tr class="head"><td>'._MSC_CODE.'</td><td>'._MSC_EMOTION.'</td><td>'._IMAGE.'</td></tr>';
				$smiles = icms_core_DataFilter::getSmileys(1);
				$count = count($smiles);
				if ($count > 0)
				{
					$rcolor = 'even';
					for ($i = 0; $i < $count; $i++)
					{
						echo "<tr class='$rcolor'><td>".$smiles[$i]['code']."</td>
							<td>".$smiles[$i]['emotion']."</td>
							<td><img onmouseover='style.cursor=\"pointer\"' onclick='doSmilie(\" "
							. $smiles[$i]['code'] . " \");' src='"
							. ICMS_UPLOAD_URL . "/" . $smiles[$i]['smile_url'] . "' alt='' /></td></tr>";
						$rcolor = ($rcolor == 'even') ? 'odd' : 'even';
					}
				}
				else {echo 'Could not retrieve data from the database.';}
				echo '</table>'._MSC_CLICKASMILIE;
			}
			break;
		case 'avatars':
			?>
			<script language='javascript'>
				<!--//
				function myimage_onclick(counter) {
					window.opener.xoopsGetElementById("user_avatar").options[counter].selected = true;
					showAvatar();
					window.opener.xoopsGetElementById("user_avatar").focus();
					window.close();
				}
				function showAvatar() {
					window.opener.xoopsGetElementById("avatar").src='<?php echo ICMS_UPLOAD_URL;?>/'
					+ window.opener.xoopsGetElementById("user_avatar")
					.options[window.opener.xoopsGetElementById("user_avatar").selectedIndex].value;
				}
				//-->
			</script>
			</head>
			<body>
			<h4><?php echo _MSC_AVAVATARS;?></h4>
			<table width='100%'>
				<tr>
				<?php
				$avatar_handler = icms::handler('icms_data_avatar');
				$avatarslist =& $avatar_handler->getList('S');
				$cntavs = 0;
				$counter = isset($start) ? (int) ($start) : 0;
				foreach ($avatarslist as $file => $name)
				{
					echo '<td><img src="uploads/'.$file.'" alt="'.$name.'" style="padding:10px; vertical-align:top;" />
						<br />'.$name.'<br />
						<input name="myimage" type="button" value="'._SELECT.'" onclick="myimage_onclick('.$counter.')" />
						</td>';
					$counter++;
					$cntavs++;
					if ($cntavs > 8)
					{
						echo '</tr><tr>';
						$cntavs=0;
					}
				}
				echo '</tr></table></div>';
				break;
			case 'friend':
				if (!icms::$security->check() || !isset($op) || $op == 'sendform') {
					if (icms::$user) {
						$yname = icms::$user->getVar('uname', 'e');
						$ymail = icms::$user->getVar('email', 'e');
						$fname = '';
						$fmail = '';
					} else {
						$yname = '';
						$ymail = '';
						$fname = '';
						$fmail = '';
					}
					printCheckForm();
					echo '</head><body>';
					echo "<div class='errorMsg'>".implode('<br />', icms::$security->getErrors())."</div>";
					echo '<form action="'.ICMS_URL.'/misc.php" method="post" onsubmit="return checkForm();"><table  width="100%" class="outer" cellspacing="1"><tr><th colspan="2">'._MSC_RECOMMENDSITE.'</th></tr>';
					echo "<tr><td class='head'>
								<input type='hidden' name='op' value='sendsite' />
								<input type='hidden' name='action' value='showpopups' />
								<input type='hidden' name='type' value='friend' />\n";
					echo _MSC_YOURNAMEC."</td>
						<td class='even'><input type='text' name='yname' value='$yname' id='yname' /></td></tr>
						<tr><td class='head'>"._MSC_YOUREMAILC."</td><td class='odd'>
						<input type='text' name='ymail' value='".$ymail."' id='ymail' /></td></tr>
						<tr><td class='head'>"._MSC_FRIENDNAMEC."</td>
						<td class='even'><input type='text' name='fname' value='$fname' id='fname' /></td></tr>
						<tr><td class='head'>"._MSC_FRIENDEMAILC."</td>
						<td class='odd'><input type='text' name='fmail' value='$fmail' id='fmail' /></td></tr>
						<tr><td class='head'>&nbsp;</td><td class='even'>
						<input type='submit' value='"._SEND."' />&nbsp;
						<input value='"._CLOSE."' type='button' onclick='javascript:window.close();' />"
						. icms::$security->getTokenHTML()."</td></tr>
						</table></form>\n";
					$closebutton = 0;
				} elseif ($op == 'sendsite') {
					if (icms::$user) {
						$ymail = icms::$user->getVar('email');
					} else {
						$ymail = isset($ymail) ? icms_core_DataFilter::stripSlashesGPC(trim($ymail)) : '';
					}
					if (!isset($yname) || trim($yname) == '' || $ymail == ''
						|| !isset($fname) || trim($fname) == ''
						|| !isset($fmail) || trim($fmail) == '') {
						redirect_header(ICMS_URL.'/misc.php?action=showpopups&amp;type=friend&amp;op=sendform',2,_MSC_NEEDINFO);
					}
					$yname = icms_core_DataFilter::stripSlashesGPC(trim($_POST['yname']));
					$fname = icms_core_DataFilter::stripSlashesGPC(trim($_POST['fname']));
					$fmail = icms_core_DataFilter::stripSlashesGPC(trim($_POST['fmail']));
					if (!checkEmail($fmail) || !checkEmail($ymail) || preg_match('/[\\0-\\31]/', $yname)) {
						$errormessage = _MSC_INVALIDEMAIL1.'<br />'._MSC_INVALIDEMAIL2.'';
						redirect_header(ICMS_URL.'/misc.php?action=showpopups&amp;type=friend&amp;op=sendform',2,$errormessage);
					}
					$xoopsMailer = new icms_messaging_Handler();
					$xoopsMailer->setTemplate('tellfriend.tpl');
					$xoopsMailer->assign('SITENAME', $icmsConfig['sitename']);
					$xoopsMailer->assign('ADMINMAIL', $icmsConfig['adminmail']);
					$xoopsMailer->assign('SITEURL', ICMS_URL.'/');
					$xoopsMailer->assign('YOUR_NAME', $yname);
					$xoopsMailer->assign('FRIEND_NAME', $fname);
					$xoopsMailer->setToEmails($fmail);
					$xoopsMailer->setFromEmail($ymail);
					$xoopsMailer->setFromName($yname);
					$xoopsMailer->setSubject(sprintf(_MSC_INTSITE,$icmsConfig['sitename']));
					//OpenTable();
					if (!$xoopsMailer->send()) {echo $xoopsMailer->getErrors();}
					else {echo '<div><h4>'._MSC_REFERENCESENT.'</h4></div>';}
					//CloseTable();
				}
				break;
			case 'online':
				echo '<table  width="100%" cellspacing="1" class="outer"><tr><th colspan="3">'._WHOSONLINE.'</th></tr>';
				$online_handler = icms::handler('icms_core_Online');
				$online_total =& $online_handler->getCount();
				$limit = ($online_total > 20) ? 20 : $online_total;
				$criteria = new icms_db_criteria_Compo();
				$criteria->setLimit($limit);
				$criteria->setStart($start);
				$onlines =& $online_handler->getAll($criteria);
				$count = count($onlines);
				$module_handler = icms::handler('icms_module');
				$modules =& $module_handler->getList(new icms_db_criteria_Item('isactive', 1));
				for ($i = 0; $i < $count; $i++) {
					if ($onlines[$i]['online_uid'] == 0) {
						$onlineUsers[$i]['user'] = '';
					} else {
						$onlineUsers[$i]['user'] = new icms_member_user_Object($onlines[$i]['online_uid']);
					}
					$onlineUsers[$i]['ip'] = $onlines[$i]['online_ip'];
					$onlineUsers[$i]['updated'] = $onlines[$i]['online_updated'];
					$onlineUsers[$i]['module'] = ($onlines[$i]['online_module'] > 0) ? $modules[$onlines[$i]['online_module']] : '';
				}
				$class = 'even';
				for ($i = 0; $i < $count; $i++) {
					$class = ($class == 'odd') ? 'even' : 'odd';
					echo '<tr valign="middle" align="center" class="'.$class.'">';
					if (is_object($onlineUsers[$i]['user'])) {
						$avatar = $onlineUsers[$i]['user']->getVar('user_avatar')
							? '<img src="'.ICMS_UPLOAD_URL.'/'.$onlineUsers[$i]['user']->getVar('user_avatar').'" alt="" />' : '&nbsp;';
						echo '<td>'.$avatar."</td><td>
							<a href=\"javascript:window.opener.location='".ICMS_URL."/userinfo.php?uid="
							. $onlineUsers[$i]['user']->getVar('uid')."';window.close();\">"
							. $onlineUsers[$i]['user']->getVar('uname')."</a>";
					} else {
						echo '<td>&nbsp;</td><td>'.$icmsConfig['anonymous'];
					}
					if (icms::$user->isAdmin()) {
						echo '<br />('.$onlineUsers[$i]['ip'].')';
					}
					echo '</td><td>'.$onlineUsers[$i]['module'].'</td></tr>';
				}
				echo '</table><br />';
				if ($online_total > 20) {
					$nav = new icms_view_PageNav($online_total, 20, $start, 'start', 'action=showpopups&amp;type=online');
					echo '<div style="text-align: right;">'.$nav->renderNav().'</div>';
				}
				break;
			case 'ssllogin':
				if ($icmsConfig['use_ssl'] && isset($_POST[$icmsConfig['sslpost_name']]) && is_object(icms::$user)) {
					icms_loadLanguageFile('core', 'user');
					echo sprintf(_US_LOGGINGU, icms::$user->getVar('uname'));
					echo '<div style="text-align:center;">
						<input class="formButton" value="'._CLOSE.'" type="button" onclick="window.opener.location.reload();window.close();" />
						</div>';
					$closebutton = false;
				}
				break;
			default:
				break;
				}
				if ($closebutton) {
					echo '<div style="text-align:center;">
						<input class="formButton" value="'._CLOSE.'" type="button" onclick="javascript:window.close();" />
						</div>';
				}
				xoops_footer();
			}

			function printCheckForm() {
				?>
					<script language='javascript'>
					<!--//
					function checkForm() {
						if (xoopsGetElementById("yname").value == "") {
							alert("<?php echo _MSC_ENTERYNAME;?>");
							xoopsGetElementById("yname").focus();
							return false;
						} elseif (xoopsGetElementById("fname").value == "") {
							alert("<?php echo _MSC_ENTERFNAME;?>");
							xoopsGetElementById("fname").focus();
							return false;
						} elseif (xoopsGetElementById("fmail").value =="") {
							alert("<?php echo _MSC_ENTERFMAIL;?>");
							xoopsGetElementById("fmail").focus();
							return false;
						} else {
							return true;
						}
					}
					//-->
					</script>
				<?php
			}

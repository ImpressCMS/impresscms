<?php
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

/**
 * Administration of finding users, main file
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Users
 */

if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
}
/*
if (!empty($_POST)) foreach ($_POST as $k => $v) ${$k} = StopXSS($v);
if (!empty($_GET)) foreach ($_GET as $k => $v) ${$k} = StopXSS($v);
*/
$filter_post = array();
$filter_get = array();

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, false);
	extract($clean_POST);
}
if (!empty($_GET)) {
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, false);
	extract($clean_GET);
}

$op = (isset($_GET['op']))
	? trim(filter_input(INPUT_GET, 'op'))
	: ((isset($_POST['op']))
		? trim(filter_input(INPUT_POST, 'op'))
		: 'form');

icms_cp_header();

if ($op == "form") {
	$member_handler = icms::handler('icms_member');
	$acttotal = icms_conv_nr2local($member_handler->getUserCount(new icms_db_criteria_Item('level', 0, '>')));
	$inacttotal = icms_conv_nr2local($member_handler->getUserCount(new icms_db_criteria_Item('level', 0)));
	$group_select = new icms_form_elements_select_Group(_AM_GROUPS, "selgroups", null, false, 5, true);
	$uname_text = new icms_form_elements_Text("", "user_uname", 30, 60);
	$uname_match = new icms_form_elements_select_Matchoption("", "user_uname_match");
	$uname_tray = new icms_form_elements_Tray(_AM_UNAME, "&nbsp;");
	$uname_tray->addElement($uname_match);
	$uname_tray->addElement($uname_text);
	$name_text = new icms_form_elements_Text("", "user_name", 30, 60);
	$name_match = new icms_form_elements_select_Matchoption("", "user_name_match");
	$name_tray = new icms_form_elements_Tray(_AM_REALNAME, "&nbsp;");
	$name_tray->addElement($name_match);
	$name_tray->addElement($name_text);
	$email_text = new icms_form_elements_Text("", "user_email", 30, 60);
	$email_match = new icms_form_elements_select_Matchoption("", "user_email_match");
	$email_tray = new icms_form_elements_Tray(_AM_EMAIL, "&nbsp;");
	$email_tray->addElement($email_match);
	$email_tray->addElement($email_text);
	$login_name_text = new icms_form_elements_Text("", "user_login_name", 30, 60);
	$login_name_match = new icms_form_elements_select_Matchoption("", "user_login_name_match");
	$login_name_tray = new icms_form_elements_Tray(_AM_LOGINNAME, "&nbsp;");
	$login_name_tray->addElement($login_name_match);
	$login_name_tray->addElement($login_name_text);
	$url_text = new icms_form_elements_Text(_AM_URLC, "user_url", 30, 100);
	//$theme_select = new icms_form_elements_select_Theme(_AM_THEME, "user_theme");
	//$timezone_select = new icms_form_elements_select_Timezone(_AM_TIMEZONE, "user_timezone_offset");
	$location_text = new icms_form_elements_Text(_AM_LOCATION, "user_from", 30, 100);
	$occupation_text = new icms_form_elements_Text(_AM_OCCUPATION, "user_occ", 30, 100);
	$interest_text = new icms_form_elements_Text(_AM_INTEREST, "user_intrest", 30, 100);

	//$bio_text = new icms_form_elements_Text(_AM_EXTRAINFO, "user_bio", 30, 100);
	$lastlog_more = new icms_form_elements_Text(_AM_LASTLOGMORE, "user_lastlog_more", 10, 5);
	$lastlog_less = new icms_form_elements_Text(_AM_LASTLOGLESS, "user_lastlog_less", 10, 5);
	$reg_more = new icms_form_elements_Text(_AM_REGMORE, "user_reg_more", 10, 5);
	$reg_less = new icms_form_elements_Text(_AM_REGLESS, "user_reg_less", 10, 5);
	$posts_more = new icms_form_elements_Text(_AM_POSTSMORE, "user_posts_more", 10, 5);
	$posts_less = new icms_form_elements_Text(_AM_POSTSLESS, "user_posts_less", 10, 5);
	$mailok_radio = new icms_form_elements_Radio(_AM_SHOWMAILOK, "user_mailok", "both");
	$mailok_radio->addOptionArray(array("mailok"=>_AM_MAILOK, "mailng"=>_AM_MAILNG, "both"=>_AM_BOTH));
	$type_radio = new icms_form_elements_Radio(_AM_SHOWTYPE, "user_type", "actv");
	$type_radio->addOptionArray(array("actv"=>_AM_ACTIVE, "inactv"=>_AM_INACTIVE, "both"=>_AM_BOTH));
	$sort_select = new icms_form_elements_Select(_AM_SORT, "user_sort");
	$sort_select->addOptionArray(array("uname"=>_AM_UNAME, "login_name"=>_AM_LOGINNAME, "email"=>_AM_EMAIL, "last_login"=>_AM_LASTLOGIN, "user_regdate"=>_AM_REGDATE, "posts"=>_AM_POSTS));
	$order_select = new icms_form_elements_Select(_AM_ORDER, "user_order");
	$order_select->addOptionArray(array("ASC"=>_AM_ASC, "DESC"=>_AM_DESC));
	$limit_text = new icms_form_elements_Text(_AM_LIMIT, "limit", 6, 2);
	$fct_hidden = new icms_form_elements_Hidden("fct", "findusers");
	$op_hidden = new icms_form_elements_Hidden("op", "submit");
	$submit_button = new icms_form_elements_Button("", "user_submit", _SUBMIT, "submit");

	$form = new icms_form_Theme(_AM_FINDUS, "uesr_findform", "admin.php", 'post', true);
	$form->addElement($uname_tray);
	$form->addElement($name_tray);
	$form->addElement($login_name_tray);
	$form->addElement($email_tray);
	$form->addElement($group_select);
	$form->addElement($url_text);
	$form->addElement($location_text);
	$form->addElement($occupation_text);
	$form->addElement($interest_text);
	//$form->addElement($bio_text);
	$form->addElement($lastlog_more);
	$form->addElement($lastlog_less);
	$form->addElement($reg_more);
	$form->addElement($reg_less);
	$form->addElement($posts_more);
	$form->addElement($posts_less);
	$form->addElement($mailok_radio);
	$form->addElement($type_radio);
	$form->addElement($sort_select);
	$form->addElement($order_select);
	$form->addElement($fct_hidden);
	$form->addElement($limit_text);
	$form->addElement($op_hidden);
	// if this is to find users for a specific group
	if (!empty($group) && (int) $group > 0) {
		$group_hidden = new icms_form_elements_Hidden("group", (int) $group);
		$form->addElement($group_hidden);
	}
	$form->addElement($submit_button);
	echo '<div class="CPbigTitle" style="background-image: url(' . ICMS_MODULES_URL . '/system/admin/findusers/images/findusers_big.png)">' . _AM_FINDUS . '</div><br />';
	echo "(" . sprintf(_AM_ACTUS, "<span style='color:#ff0000;'>$acttotal</span>") . " " . sprintf(_AM_INACTUS, "<span style='color:#ff0000;'>$inacttotal</span>") . ")<br /><br />";
	$form->display();
} elseif ($op == "submit" & icms::$security->check()) {
	$criteria = new icms_db_criteria_Compo();
	if (!empty($user_uname)) {
		$match = (!empty($user_uname_match))?(int) $user_uname_match:XOOPS_MATCH_START;
		switch ($match) {
			case XOOPS_MATCH_START:
				$criteria->add(new icms_db_criteria_Item('uname', icms_core_DataFilter::addSlashes(trim($user_uname)) . '%', 'LIKE'));
				break;

			case XOOPS_MATCH_END:
				$criteria->add(new icms_db_criteria_Item('uname', '%' . icms_core_DataFilter::addSlashes(trim($user_uname)), 'LIKE'));
				break;

			case XOOPS_MATCH_EQUAL:
				$criteria->add(new icms_db_criteria_Item('uname', icms_core_DataFilter::addSlashes(trim($user_uname))));
				break;

			case XOOPS_MATCH_CONTAIN:
				$criteria->add(new icms_db_criteria_Item('uname', '%' . icms_core_DataFilter::addSlashes(trim($user_uname)) . '%', 'LIKE'));
				break;

			default:
				break;
		}
	}
	if (!empty($user_name)) {
		$match = (!empty($user_name_match))?(int) $user_name_match:XOOPS_MATCH_START;
		switch ($match) {
			case XOOPS_MATCH_START:
				$criteria->add(new icms_db_criteria_Item('name', icms_core_DataFilter::addSlashes(trim($user_name)) . '%', 'LIKE'));
				break;

			case XOOPS_MATCH_END:
				$criteria->add(new icms_db_criteria_Item('name', '%' . icms_core_DataFilter::addSlashes(trim($user_name)), 'LIKE'));
				break;

			case XOOPS_MATCH_EQUAL:
				$criteria->add(new icms_db_criteria_Item('name', icms_core_DataFilter::addSlashes(trim($user_name))));
				break;

			case XOOPS_MATCH_CONTAIN:
				$criteria->add(new icms_db_criteria_Item('name', '%' . icms_core_DataFilter::addSlashes(trim($user_name)) . '%', 'LIKE'));
				break;

			default:
				break;
		}
	}
	if (!empty($user_login_name)) {
		$match = (!empty($user_login_name_match))?(int) $user_login_name_match:XOOPS_MATCH_START;
		switch ($match) {
			case XOOPS_MATCH_START:
				$criteria->add(new icms_db_criteria_Item('login_name', icms_core_DataFilter::addSlashes(trim($user_login_name)) . '%', 'LIKE'));
				break;

			case XOOPS_MATCH_END:
				$criteria->add(new icms_db_criteria_Item('login_name', '%' . icms_core_DataFilter::addSlashes(trim($user_login_name)), 'LIKE'));
				break;

			case XOOPS_MATCH_EQUAL:
				$criteria->add(new icms_db_criteria_Item('login_name', icms_core_DataFilter::addSlashes(trim($user_login_name))));
				break;

			case XOOPS_MATCH_CONTAIN:
				$criteria->add(new icms_db_criteria_Item('login_name', '%' . icms_core_DataFilter::addSlashes(trim($user_login_name)) . '%', 'LIKE'));
				break;

			default:
				break;
		}
	}
	if (!empty($user_email)) {
		$match = (!empty($user_email_match))?(int) $user_email_match:XOOPS_MATCH_START;
		switch ($match) {
			case XOOPS_MATCH_START:
				$criteria->add(new icms_db_criteria_Item('email', icms_core_DataFilter::addSlashes(trim($user_email)) . '%', 'LIKE'));
				break;

			case XOOPS_MATCH_END:
				$criteria->add(new icms_db_criteria_Item('email', '%' . icms_core_DataFilter::addSlashes(trim($user_email)), 'LIKE'));
				break;

			case XOOPS_MATCH_EQUAL:
				$criteria->add(new icms_db_criteria_Item('email', icms_core_DataFilter::addSlashes(trim($user_email))));
				break;

			case XOOPS_MATCH_CONTAIN:
				$criteria->add(new icms_db_criteria_Item('email', '%' . icms_core_DataFilter::addSlashes(trim($user_email)) . '%', 'LIKE'));
				break;

			default:
				break;
		}
	}
	if (!empty($user_url)) {
		$url = formatURL(trim($user_url));
		$criteria->add(new icms_db_criteria_Item('url', $url . '%', 'LIKE'));
	}
	if (!empty($user_from)) {
		$criteria->add(new icms_db_criteria_Item('user_from', '%' . icms_core_DataFilter::addSlashes(trim($user_from)) . '%', 'LIKE'));
	}
	if (!empty($user_intrest)) {
		$criteria->add(new icms_db_criteria_Item('user_intrest', '%' . icms_core_DataFilter::addSlashes(trim($user_intrest)) . '%', 'LIKE'));
	}
	if (!empty($user_occ)) {
		$criteria->add(new icms_db_criteria_Item('user_occ', '%' . icms_core_DataFilter::addSlashes(trim($user_occ)) . '%', 'LIKE'));
	}

	if (!empty($user_lastlog_more) && is_numeric($user_lastlog_more)) {
		$f_user_lastlog_more = (int) trim($user_lastlog_more);
		$time = time() - (60 * 60 * 24 * $f_user_lastlog_more);
		if ($time > 0) {
			$criteria->add(new icms_db_criteria_Item('last_login', $time, '<'));
		}
	}
	if (!empty($user_lastlog_less) && is_numeric($user_lastlog_less)) {
		$f_user_lastlog_less = (int) trim($user_lastlog_less);
		$time = time() - (60 * 60 * 24 * $f_user_lastlog_less);
		if ($time > 0) {
			$criteria->add(new icms_db_criteria_Item('last_login', $time, '>'));
		}
	}
	if (!empty($user_reg_more) && is_numeric($user_reg_more)) {
		$f_user_reg_more = (int) trim($user_reg_more);
		$time = time() - (60 * 60 * 24 * $f_user_reg_more);
		if ($time > 0) {
			$criteria->add(new icms_db_criteria_Item('user_regdate', $time, '<'));
		}
	}
	if (!empty($user_reg_less) && is_numeric($user_reg_less)) {
		$f_user_reg_less = (int) $user_reg_less;
		$time = time() - (60 * 60 * 24 * $f_user_reg_less);
		if ($time > 0) {
			$criteria->add(new icms_db_criteria_Item('user_regdate', $time, '>'));
		}
	}
	if (!empty($user_posts_more) && is_numeric($user_posts_more)) {
		$criteria->add(new icms_db_criteria_Item('posts', (int) $user_posts_more, '>'));
	}
	if (!empty($user_posts_less) && is_numeric($user_posts_less)) {
		$criteria->add(new icms_db_criteria_Item('posts', (int) $user_posts_less, '<'));
	}
	if (isset($user_mailok)) {
		if ($user_mailok == "mailng") {
			$criteria->add(new icms_db_criteria_Item('user_mailok', 0));
		} elseif ($user_mailok == "mailok") {
			$criteria->add(new icms_db_criteria_Item('user_mailok', 1));
		} else {
			$criteria->add(new icms_db_criteria_Item('user_mailok', 0, '>='));
		}
	}
	if (isset($user_type)) {
		if ($user_type == "inactv") {
			$criteria->add(new icms_db_criteria_Item('level', 0, '='));
		} elseif ($user_type == "actv") {
			$criteria->add(new icms_db_criteria_Item('level', 0, '>'));
		} else {
			$criteria->add(new icms_db_criteria_Item('level', 0, '>='));
		}
	}
	$groups = empty($selgroups)?array():array_map('intval', $selgroups);
	$validsort = array("uname", "login_name", "email", "last_login", "user_regdate", "posts");
	$sort = (!in_array($user_sort, $validsort))?"uname":$user_sort;
	$order = "ASC";
	if (isset($user_order) && $user_order == "DESC") {
		$order = "DESC";
	}
	$limit = (!empty($limit))?(int) $limit:50;
	if ($limit == 0 || $limit > 50) {
		$limit = 50;
	}
	$start = (!empty($start))?(int) $start:0;
	$member_handler = icms::handler('icms_member');
	$total = $member_handler->getUserCountByGroupLink($groups, $criteria);
	if ($total == 0) {

	} elseif ($start < $total) {

		$criteria->setSort($sort);
		$criteria->setOrder($order);
		$criteria->setLimit($limit);
		$criteria->setStart($start);
		$foundusers = $member_handler->getUsersByGroupLink($groups, $criteria, true);
		$ucount = 0;
		foreach (array_keys($foundusers) as $j) {
			$ucount++;
			$fuser_avatar = $foundusers[$j]->getVar("user_avatar")?"<img src='" . ICMS_UPLOAD_URL . "/"
				. $foundusers[$j]->getVar("user_avatar") . "' alt='' />":"&nbsp;";
			$fuser_name = $foundusers[$j]->getVar("name")?$foundusers[$j]->getVar("name"):"&nbsp;";
			$users[$j]['uid'] = $foundusers[$j]->getVar("uid");
			$users[$j]['email'] = $foundusers[$j]->getVar("email");
			$users[$j]['uname'] = $foundusers[$j]->getVar("uname");
			$users[$j]['uname_e'] = $foundusers[$j]->getVar("uname", "E");
			$users[$j]['name'] = $foundusers[$j]->getVar("name")?$foundusers[$j]->getVar("name"):"&nbsp;";
			$users[$j]['login_name'] = $foundusers[$j]->getVar("login_name");
			$users[$j]['user_avatar'] = $fuser_avatar;
			$users[$j]['user_regdate'] = formatTimeStamp($foundusers[$j]->getVar("user_regdate"), "s");
			$users[$j]['url'] = $foundusers[$j]->getVar("url", "E");
			$users[$j]['posts'] = icms_conv_nr2local($foundusers[$j]->getVar("posts"));
			$users[$j]['last_login_m'] = formatTimeStamp($foundusers[$j]->getVar("last_login"), "m");

			$icmsAdminTpl->assign("users", $users);
		}

		$group = !empty($group)?(int) $group:0;
		$icmsAdminTpl->assign("groupvalue", $group);
		if ($group > 0) {
			$member_handler = icms::handler('icms_member');
			$add2group = & $member_handler->getGroup($group);
			$icmsAdminTpl->assign("groupvalue_name", sprintf(_AM_ADD2GROUP, $add2group->getVar('name')));
		}

		$totalpages = ceil($total / $limit);
		if ($totalpages > 1) {
			$hiddenform = "<form name='findnext' action='admin.php' method='post'>";
			$skip_vars = array('selgroups');
			foreach ($_POST as $k => $v) {
				if ($k == 'selgroups') {
					foreach ($selgroups as $_group) {
						$hiddenform .= "<input type='hidden' name='selgroups[]' value='" . $_group . "' />\n";
					}
				} elseif ($k == 'XOOPS_TOKEN_REQUEST') {
					// regenerate token value
					$hiddenform .= icms::$security->getTokenHTML() . "\n";
				} else {
					$hiddenform .= "<input type='hidden' name='" . icms_core_DataFilter::htmlSpecialChars($k) . "' value='" . icms_core_DataFilter::htmlSpecialChars(icms_core_DataFilter::stripSlashesGPC($v)) . "' />\n";
				}
			}

			if (!isset($limit)) {
				$hiddenform .= "<input type='hidden' name='limit' value='" . $limit . "' />\n";
			}

			if (!isset($start)) {
				$hiddenform .= "<input type='hidden' name='start' value='" . $start . "' />\n";
			}

			$prev = $start - $limit;
			if ($start - $limit >= 0) {
				$hiddenform .= "<a href='#0' onclick='javascript:document.findnext.start.value=" . $prev . ";document.findnext.submit();'>" . _AM_PREVIOUS . "</a>&nbsp;\n";
			}

			$counter = 1;
			$currentpage = ($start + $limit) / $limit;
			while ($counter <= $totalpages) {
				if ($counter == $currentpage) {
					$hiddenform .= "<strong>" . $counter . "</strong> ";
				} elseif (($counter > $currentpage - 4 && $counter < $currentpage + 4) || $counter == 1 || $counter == $totalpages) {
					if ($counter == $totalpages && $currentpage < $totalpages - 4) {
						$hiddenform .= "... ";
					}

					$hiddenform .= "<a href='#" . $counter . "' onclick='javascript:document.findnext.start.value=" . ($counter - 1) * $limit . ";document.findnext.submit();'>" . $counter . "</a> ";
					if ($counter == 1 && $currentpage > 5) {
						$hiddenform .= "... ";
					}

				}

				$counter++;
			}

			$next = $start + $limit;
			if ($total > $next) {
				$hiddenform .= "&nbsp;<a href='#" . $total . "' onclick='javascript:document.findnext.start.value=" . $next . ";document.findnext.submit();'>" . _AM_NEXT . "</a>\n";
			}

			$hiddenform .= "</form>";
			echo "<div style='text-align:center'>" . $hiddenform . "<br />";
			printf(_AM_USERSFOUND, $total);
			echo "</div>";
		}
	}

	$icmsAdminTpl->assign("totalfound", sprintf(_AM_USERSFOUND, icms_conv_nr2local($total)));
	$icmsAdminTpl->assign("security", icms::$security->getTokenHTML());
	$icmsAdminTpl->assign("total", $total);
	$icmsAdminTpl->display('db:admin/findusers/system_adm_findusers.html');

	} else {
	redirect_header('admin.php?fct=findusers', 3, implode('<br />', icms::$security->getErrors()));
}

icms_cp_footer();

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
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Modules
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

/* set get and post filters before including admin_header, if not strings */
$filter_get = array('mid' => 'int');

$filter_post = array('mid' => 'int');

/* set default values for variables. $op and $fct are handled in the header */
$mid = 0;

/** common header for the admin functions */
include 'admin_header.php';

!empty($op) || $op = 'list';

$icmsAdminTpl = new icms_view_Tpl();

include_once ICMS_MODULES_PATH . '/system/admin/modules/modules.php';
icms_loadLanguageFile('system', 'blocks', true); // @todo - why is this here?

if (in_array($op, array('submit', 'install_ok', 'update_ok', 'uninstall_ok')) && !icms::$security->check()) {
	$op = 'list';
}

switch ($op) {
	case 'list':
		icms_cp_header();
		echo xoops_module_list();
		icms_cp_footer();
		break;

	case 'confirm':
		icms_cp_header();
		$error = array();
		if (!is_writable(ICMS_CACHE_PATH . '/')) {
			// attempt to chmod 666
			if (!chmod(ICMS_CACHE_PATH . '/', 0777)) {
				$error[] = sprintf(_MUSTWABLE, '<strong>' . ICMS_CACHE_PATH . '/</strong>');
			}
		}

		if (count($error) > 0) {
			icms_core_Message::error($error);
			printf("<p><a class='btn btn-primary' href='admin.php?fct=modules'>%s</a></p>", _MD_AM_BTOMADMIN);
			icms_cp_footer();
			break;
		}

		printf("<h4 style='text-align:%s;'>%s</h4><form action='admin.php' method='post'><input type='hidden' name='fct' value='modules' /><input type='hidden' name='op' value='submit' /><table width='100%%' border='0' cellspacing='1' class='table outer'><tr class='center' align='center'><th>%s</th><th>%s</th><th>%s</th></tr>", _GLOBAL_LEFT, _MD_AM_PCMFM, _CO_ICMS_MODULE, _AM_ACTION, _MD_AM_ORDER);
		$mcount = 0;
		foreach ($module as $mid) {
			$class = ($mcount % 2 != 0) ? 'odd' : 'even';
			echo '<tr class="' . $class . '"><td align="center">' . icms_core_DataFilter::stripSlashesGPC($oldname[$mid]);
			$newname[$mid] = trim(icms_core_DataFilter::stripslashesGPC($newname[$mid]));
			if ($newname[$mid] != $oldname[$mid]) {
				printf('&nbsp;&raquo;&raquo;&nbsp;<span style="color:#ff0000;font-weight:bold;">%s</span>', $newname[$mid]);
			}
			echo '</td><td align="center">';
			if (isset($newstatus[$mid]) && $newstatus[$mid] == 1) {
				if ($oldstatus[$mid] == 0) {
					printf("<span style='color:#ff0000;font-weight:bold;'>%s</span>", _MD_AM_ACTIVATE);
				} else {
						echo _MD_AM_NOCHANGE;
					}
				} else {
					$newstatus[$mid] = 0;
					if ($oldstatus[$mid] == 1) {
						printf("<span style='color:#ff0000;font-weight:bold;'>%s</span>", _MD_AM_DEACTIVATE);
					} else {
						echo _MD_AM_NOCHANGE;
					}
			}
			echo "</td><td align='center'>";
			if ($oldweight[$mid] != $weight[$mid]) {
				printf("<span style='color:#ff0000;font-weight:bold;'>%s</span>", $weight[$mid]);
			} else {
				echo $weight[$mid];
			}
			printf("<input type='hidden' name='module[]' value='%d' /><input type='hidden' name='oldname[%d]' value='%s' /><input type='hidden' name='newname[%d]' value='%s' /><input type='hidden' name='oldstatus[%d]' value='%d' /><input type='hidden' name='newstatus[%d]' value='%d' /><input type='hidden' name='oldweight[%d]' value='%d' /><input type='hidden' name='weight[%d]' value='%d' /></td></tr>", (int)$mid, $mid, htmlspecialchars($oldname[$mid], ENT_QUOTES, _CHARSET), $mid, htmlspecialchars($newname[$mid], ENT_QUOTES, _CHARSET), $mid, (int)$oldstatus[$mid], $mid, (int)$newstatus[$mid], $mid, (int)$oldweight[$mid], $mid, (int)$weight[$mid]);
		}

		printf("<tr class='foot' align='center'><td colspan='3'><input class='btn btn-primary' type='submit' value='%s' />&nbsp;<input class='btn btn-warning' type='button' value='%s' onclick='location=\"admin.php?fct=modules\"' />%s</td></tr></table></form>", _MD_AM_SUBMIT, _MD_AM_CANCEL, icms::$security->getTokenHTML());
		icms_cp_footer();
		break;

	case 'submit':
		$ret = array();
		$write = false;
		$buffer = new \Symfony\Component\Console\Output\BufferedOutput();
		$output = new \ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator($buffer);
		/**
		 * @var icms_module_Handler $module_handler
		 */
		$module_handler = icms::handler('icms_module');

		foreach ($module as $mid) {
			if (isset($newstatus[$mid]) && $newstatus[$mid] == 1) {
				if ($oldstatus[$mid] == 0) {
					$module_handler->activate($mid, $output);
				}
			} else {
				if ($oldstatus[$mid] == 1) {
					$module_handler->deactivate($mid, $output);
				}
			}
			$newname[$mid] = trim($newname[$mid]);
			if ($oldname[$mid] != $newname[$mid] || $oldweight[$mid] != $weight[$mid]) {
				$module_handler->change($mid, $weight[$mid], $newname[$mid], $output);
				$write = true;
			}
			$ret[] = nl2br(
				$buffer->fetch() . PHP_EOL
			);
		}
			if ($write) {
				$contents = impresscms_get_adminmenu();
				if (!xoops_module_write_admin_menu($contents)) {
					$ret[] = sprintf('<p>%s</p>', _MD_AM_FAILWRITE);
				}
			}
		icms_cp_header();
		if (count($ret) > 0) {
			foreach ($ret as $msg) {
				if ($msg != '') {
					echo $msg;
				}
			}
		}
		printf("<br /><a class='btn btn-primary' href='admin.php?fct=modules'>%s</a>", _MD_AM_BTOMADMIN);
		icms_cp_footer();
		break;

		case 'install':
			$module_handler = icms::handler('icms_module');
			$mod = &$module_handler->create();
			$mod->loadInfoAsVar($module);
			if ($mod->getInfo('image') != false && trim($mod->getInfo('image')) != '') {
				$msgs = sprintf("<img src=\"%s/%s/%s\" alt=\"\" />", ICMS_MODULES_URL, $mod->getVar('dirname'), trim($mod->getInfo('image')));
			}
			$msgs .= sprintf('<br /><span style="font-size:smaller;">%s</span><br /><br />%s', $mod->getVar('name'), _MD_AM_RUSUREINS);
			if (empty($from_112)) {
				$from_112 = false;
			}
			icms_cp_header();
			icms_core_Message::confirm(array('module' => $module, 'op' => 'install_ok', 'fct' => 'modules', 'from_112' => $from_112), 'admin.php', $msgs, _MD_AM_INSTALL);
			icms_cp_footer();
			break;

		case 'install_ok':
			/**
			 * @var icms_module_Handler $module_handler
			 */
			$module_handler = icms::handler('icms_module');
			$buffer = new \Symfony\Component\Console\Output\BufferedOutput();
			$module_handler->install(
				$module,
				new \ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator($buffer)
			);
			if ($from_112) {
				$module_handler->update(
					$module,
					new \ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator($buffer)
				);
			}
			$ret[] = nl2br(
				$buffer->fetch()
			);
			$contents = impresscms_get_adminmenu();
			if (!xoops_module_write_admin_menu($contents)) {
				$ret[] = sprintf('<p>%s</p>', _MD_AM_FAILWRITE);
			}
			icms_cp_header();
			if (count($ret) > 0) {
				foreach ($ret as $msg) {
					if ($msg != '') {
						echo $msg;
					}
				}
			}
			printf("<br /><a class='btn btn-primary' href='admin.php?fct=modules'>%s</a>", _MD_AM_BTOMADMIN);
			icms_cp_footer();
			break;

		case 'uninstall':
			$module_handler = icms::handler('icms_module');
			$mod = &$module_handler->getByDirname($module);
			$mod->registerClassPath();

			if ($mod->getInfo('image') != false && trim($mod->getInfo('image')) != '') {
				$msgs = sprintf('<img src="%s/%s/%s" alt="" />', ICMS_MODULES_URL, $mod->getVar('dirname'), trim($mod->getInfo('image')));
			}
			$msgs .= sprintf('<br /><span style="font-size:smaller;">%s</span><br /><br />%s', $mod->getVar('name'), _MD_AM_RUSUREUNINS);
			icms_cp_header();
			icms_core_Message::confirm(array('module' => $module, 'op' => 'uninstall_ok', 'fct' => 'modules'), 'admin.php', $msgs, _YES);
			icms_cp_footer();
			break;

		case 'uninstall_ok':
			$module_handler = icms::handler('icms_module');
			$buffer = new \Symfony\Component\Console\Output\BufferedOutput();
			$module_handler->uninstall(
				$module,
				new \ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator($buffer)
			);
			$ret[] = nl2br(
				$buffer->fetch()
			);
			$contents = impresscms_get_adminmenu();
			if (!xoops_module_write_admin_menu($contents)) {
				$ret[] = sprintf('<p>%s</p>', _MD_AM_FAILWRITE);
			}
			icms_cp_header();
			if (count($ret) > 0) {
				foreach ($ret as $msg) {
					if ($msg != '') {
						echo $msg;
					}
				}
			}
			printf("<a class='btn btn-primary' href='admin.php?fct=modules'>%s</a>", _MD_AM_BTOMADMIN);
			icms_cp_footer();
			break;

		case 'update':
			$module_handler = icms::handler('icms_module');
			$mod = &$module_handler->getByDirname($module);
			if ($mod->getInfo('image') != false && trim($mod->getInfo('image')) != '') {
				$msgs = sprintf('<img src="%s/%s/%s" alt="" />', ICMS_MODULES_URL, $mod->getVar('dirname'), trim($mod->getInfo('image')));
			}
			$msgs .= sprintf('<br /><span style="font-size:smaller;">%s</span><br /><br />%s', $mod->getVar('name'), _MD_AM_RUSUREUPD);
			icms_cp_header();

			if (icms_getModuleInfo('system')->getDBVersion() < 14 && (!is_writable(ICMS_PLUGINS_PATH) || !is_dir(ICMS_ROOT_PATH . '/plugins/preloads') || !is_writable(ICMS_ROOT_PATH . '/plugins/preloads'))) {
				icms_core_Message::error(sprintf(_MD_AM_PLUGINSFOLDER_UPDATE_TEXT, ICMS_PLUGINS_PATH, ICMS_ROOT_PATH . '/plugins/preloads'), _MD_AM_PLUGINSFOLDER_UPDATE_TITLE, true);
			}
			if (!is_writable(ICMS_IMANAGER_FOLDER_PATH) && icms_getModuleInfo('system')->getDBVersion() < 37) {
				icms_core_Message::error(sprintf(_MD_AM_IMAGESFOLDER_UPDATE_TEXT, str_ireplace(ICMS_ROOT_PATH, "", ICMS_IMANAGER_FOLDER_PATH)), _MD_AM_IMAGESFOLDER_UPDATE_TITLE, true);
			}

			icms_core_Message::confirm(array('module' => $module, 'op' => 'update_ok', 'fct' => 'modules'), 'admin.php', $msgs, _MD_AM_UPDATE);
			icms_cp_footer();
			break;

		case 'update_ok':
			$module_handler = icms::handler('icms_module');
			$buffer = new \Symfony\Component\Console\Output\BufferedOutput();
			$module_handler->update(
				$module,
				new \ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator($buffer)
			);
			$ret[] = nl2br(
				$buffer->fetch()
			);
			$contents = impresscms_get_adminmenu();
			if (!xoops_module_write_admin_menu($contents)) {
				$ret[] = sprintf('<p>%s</p>', _MD_AM_FAILWRITE);
			}
			icms_cp_header();
			if (count($ret) > 0) {
				foreach ($ret as $msg) {
					if ($msg != '') {
						echo $msg;
					}
				}
			}
			printf("<br /><a class='btn btn-primary' href='admin.php?fct=modules'>%s</a>", _MD_AM_BTOMADMIN);
			icms_cp_footer();
			break;

		default:
			break;
}
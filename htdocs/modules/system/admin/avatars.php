<?php
/**
 * Administration of avatars
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		Administration
 * @subpackage	Avatars
 * @version		SVN: $Id: avatars.php 11719 2012-05-22 00:40:10Z skenow $
 */

/* set get and post filters before including admin_header, if not strings */
$filter_get = array(
	'start' => 'int',
	'user_id' => 'int',
	'avatar_id' => 'int',
);

/* set default values for variables. $op and $fct are handled in the header */
$start = $user_id = $avatar_id = 0;

/** common header for the admin functions */
include "admin_header.php";
$avt_handler = $icms_admin_handler;

switch ($op) {
	default:
	case 'list':
		icms_loadLanguageFile('system', 'preferences', TRUE);
		icms_cp_header();
		$icmsAdminTpl->assign("list", "1");
		$savatar_count = $avt_handler->getCount(new icms_db_criteria_Item('avatar_type', 'S'));
		$cavatar_count = $avt_handler->getCount(new icms_db_criteria_Item('avatar_type', 'C'));
		$icmsAdminTpl->assign("systemavatars", sprintf(_NUMIMAGES, '<b>' . icms_conv_nr2local($savatar_count) . '</b>'));	
		$icmsAdminTpl->assign("usersavatars", sprintf(_NUMIMAGES, '<b>' . icms_conv_nr2local($cavatar_count) . '</b>'));	
		$icmsAdminTpl->display(ICMS_MODULES_PATH . '/system/templates/admin/avatars/system_adm_avatars.html');
		$form = new icms_form_Theme(_MD_ADDAVT, 'avatar_form', 'admin.php', "post", TRUE);
		$form->setExtra('enctype="multipart/form-data"');
		$form->addElement(new icms_form_elements_Text(_IMAGENAME, 'avatar_name', 50, 255), TRUE);
		$form->addElement(new icms_form_elements_File(_IMAGEFILE, 'avatar_file', $icmsConfigUser['avatar_maxsize']));
		$form->addElement(new icms_form_elements_Text(_IMGWEIGHT, 'avatar_weight', 3, 4, 0));
		$form->addElement(new icms_form_elements_Radioyn(_IMGDISPLAY, 'avatar_display', 1, _YES, _NO));
		$restrictions  = _MD_AM_AVATARMAX . ": " . $icmsConfigUser['avatar_maxsize'] . "<br />";
		$restrictions .= _MD_AM_AVATARW . ": " . $icmsConfigUser['avatar_width'] . "px<br />";
		$restrictions .= _MD_AM_AVATARH . ": ". $icmsConfigUser['avatar_height']. "px";
		$form->addElement(new icms_form_elements_Label(_MD_RESTRICTIONS, $restrictions));
		$form->addElement(new icms_form_elements_Hidden('op', 'addfile'));
		$form->addElement(new icms_form_elements_Hidden('fct', 'avatars'));
		$form->addElement(new icms_form_elements_Button('', 'avt_button', _SUBMIT, 'submit'));
		$form->display();
		icms_cp_footer();
		break;

	case 'listavt':
		icms_cp_header();
		$type = ($type && $type == 'C') ? 'C' : 'S';
		$icmsAdminTpl->assign("listavt", "1");
		if ($type == 'S') {
		$icmsAdminTpl->assign("typeS", "1");
		} else {
		}
		$criteria = new icms_db_criteria_Item('avatar_type', $type);
		$avtcount = $avt_handler->getCount($criteria);
		$criteria->setStart($start);
		$criteria->setLimit(10);
		$avatars =& $avt_handler->getObjects($criteria, TRUE);
		if ($type == 'S') {
			foreach (array_keys($avatars) as $i) {
				$id = $avatars[$i]->getVar('avatar_id');
				$avatar[$i]['id'] = $avatars[$i]->getVar('avatar_id') ;
				$avatar[$i]['file'] = $avatars[$i]->getVar('avatar_file');
				$avatar[$i]['name'] = $avatars[$i]->getVar('avatar_name', 'E');
				$avatar[$i]['mimetype'] = $avatars[$i]->getVar('avatar_mimetype');
				$avatar[$i]['count'] = $avatars[$i]->getUserCount() ;
				$avatar[$i]['weight'] = $avatars[$i]->getVar('avatar_weight') ;
				if ($avatars[$i]->getVar('avatar_display') == 1) {
				$avatar[$i]['display'] = "1";}
			}
			$icmsAdminTpl->assign("avatararray", $avatar );
		} else {
			foreach (array_keys($avatars) as $i) {
				$userids =& $avt_handler->getUser($avatars[$i]);
				$avatar[$i]['user'] = $userids[0] ;
				$avatar[$i]['name'] = $avatars[$i]->getVar('avatar_name');
				$avatar[$i]['file'] = $avatars[$i]->getVar('avatar_file');
				$avatar[$i]['mimetype'] = $avatars[$i]->getVar('avatar_mimetype');
				$avatar[$i]['id'] = $avatars[$i]->getVar('avatar_id') ;	
			}
			$icmsAdminTpl->assign("avatararray", $avatar );
		}
		if ($avtcount > 0) {
		$icmsAdminTpl->assign("avtcount", $avtcount);
			if ($avtcount > 10) {
			$nav = new icms_view_PageNav($avtcount, 10, $start, 'start', 'fct=avatars&amp;type=' . $type . '&amp;op=listavt');
			$icmsAdminTpl->assign("nav", $nav->renderImageNav());
			}
			if ($type == 'S') {
			$icmsAdminTpl->assign("security", icms::$security->getTokenHTML());	
			}
		}
		$icmsAdminTpl->display(ICMS_MODULES_PATH . '/system/templates/admin/avatars/system_adm_avatars.html');
		icms_cp_footer();
		break;

	case 'save':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=avatars', 3, implode('<br />', icms::$security->getErrors()));
			exit();
		}
		$count = count($avatar_id);
		if ($count > 0) {
			$error = array();
			for ($i = 0; $i < $count; $i++) {
				$avatar =& $avt_handler->get($avatar_id[$i]);
				if (!is_object($avatar)) {
					$error[] = sprintf(_FAILGETIMG, $avatar_id[$i]);
					continue;
				}
				$avatar_display[$i] = empty($avatar_display[$i]) ? 0 : 1;
				$avatar->setVar('avatar_display', $avatar_display[$i]);
				$avatar->setVar('avatar_weight', $avatar_weight[$i]);
				$avatar->setVar('avatar_name', $avatar_name[$i]);
				if (!$avt_handler->insert($avatar)) {
					$error[] = sprintf(_FAILSAVEIMG, $avatar_id[$i]);
				}
				unset($avatar_id[$i]);
				unset($avatar_name[$i]);
				unset($avatar_weight[$i]);
				unset($avatar_display[$i]);
			}
			if (count($error) > 0) {
				icms_cp_header();
				foreach ($error as $err) {
					echo $err . '<br />';
				}
				icms_cp_footer();
				exit();
			}
		}
		redirect_header('admin.php?fct=avatars', 2, _MD_AM_DBUPDATED);
		break;

	case 'addfile':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=avatars', 3, implode('<br />', icms::$security->getErrors()));
		}
		$uploader = new icms_file_MediaUploadHandler(ICMS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $icmsConfigUser['avatar_maxsize'], $icmsConfigUser['avatar_width'], $icmsConfigUser['avatar_height']);
		$uploader->setPrefix('savt');
		$err = array();
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if (!$uploader->upload()) {
				$err[] = $uploader->getErrors();
			} else {
				$avatar =& $avt_handler->create();
				$avatar->setVar('avatar_file', $uploader->getSavedFileName());
				$avatar->setVar('avatar_name', $avatar_name);
				$avatar->setVar('avatar_mimetype', $uploader->getMediaType());
				$avatar_display = empty($avatar_display) ? 0 : 1;
				$avatar->setVar('avatar_display', $avatar_display);
				$avatar->setVar('avatar_weight', $avatar_weight);
				$avatar->setVar('avatar_type', 'S');
				if (!$avt_handler->insert($avatar)) {
					$err[] = sprintf(_FAILSAVEIMG, $avatar->getVar('avatar_name'));
				}
			}
		} else {
			$err = array_merge($err, $uploader->getErrors(FALSE));
		}
		if (count($err) > 0) {
			icms_cp_header();
			icms_core_Message::error($err);
			icms_cp_footer();
			exit();
		}
		redirect_header('admin.php?fct=avatars', 2, _MD_AM_DBUPDATED);
		break;

	case 'delfile':
		icms_cp_header();
		icms_core_Message::confirm(array('op' => 'delfileok', 'avatar_id' => $avatar_id, 'fct' => 'avatars', 'user_id' => $user_id), 'admin.php', _MD_RUDELIMG);
		icms_cp_footer();
		break;

	case 'delfileok':
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=avatars', 1, 3, implode('<br />', icms::$security->getErrors()));
		}
		$avatar_id = $avatar_id;
		if ($avatar_id <= 0) {
			redirect_header('admin.php?fct=avatars', 1);
		}
		$avatar =& $avt_handler->get($avatar_id);
		if (!is_object($avatar)) {
			redirect_header('admin.php?fct=avatars', 1);
		}
		if (!$avt_handler->delete($avatar)) {
			icms_cp_header();
			icms_core_Message::error(sprintf(_MD_FAIL_DEL_AVATAR, $avatar->getVar('avatar_id')));
			icms_cp_footer();
			exit();
		}
		$file = $avatar->getVar('avatar_file');
		@unlink(ICMS_UPLOAD_PATH . '/' . $file);
		if (isset($user_id) && $avatar->getVar('avatar_type') == 'C') {
			icms::$xoopsDB->query("UPDATE " . icms::$xoopsDB->prefix('users') . " SET user_avatar='blank.gif' WHERE uid='". $user_id . "'");
		} else {
			icms::$xoopsDB->query("UPDATE " . icms::$xoopsDB->prefix('users') . " SET user_avatar='blank.gif' WHERE user_avatar='" . $file . "'");
		}
		redirect_header('admin.php?fct=avatars', 2, _MD_AM_DBUPDATED);
		break;

}
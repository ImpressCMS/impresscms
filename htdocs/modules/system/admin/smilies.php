<?php
/**
 * Administration of smilies, main file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		System
 * @subpackage	Smilies
 * @version		SVN: $Id$
 */

/* user input variables - sanitize these properly!
 *
 * GET variables
 * (int) id, default 'SmilesAdmin'
 * (str) op
 *
 * POST variables
 * (str) op, default 'SmilesAdmin'
 * (int|arr) smile_id
 * (arr) smile_display
 * (arr) old_display
 * (str) xoops_upload_file
 * (str) smile_code
 * (str) smile_desc
 * (int) id
 */

/* set filter types, if not strings */
$filter_get = array(
	'id' => 'int',
);
$filter_post = array(
	'id' => 'int',
	'smile_id' => 'int',
	'smile_display' => array('int', array(0,1)),
);

/* set default values for variables, $op and $fct are handled in the header */

/** common header for the admin functions */
include 'admin_header.php';

/** load helper functions for smilies administration */
include_once ICMS_MODULES_PATH . "/system/admin/smilies/smilies.php";

$db =& icms_db_Factory::instance();

switch($op) {
	case "SmilesUpdate":
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=smilies', 3, implode('<br />', icms::$security->getErrors()));
		}
		$count = (!empty($smile_id) && is_array($smile_id)) ? count($smile_id) : 0;

		for ($i = 0; $i < $count; $i++) {
			$smileid = (int) $smile_id[$i];
			if (empty($smileid)) {
				continue;
			}
			$smiledisplay = empty($smile_display[$i]) ? 0 : 1;
			if (isset($old_display[$i]) && $old_display[$i] != $smiledisplay) {
				$db->query("UPDATE " . $db->prefix('smiles') . " SET display='" . (int) $smiledisplay . "' WHERE id ='" . $smileid . "'");
			}
		}
		redirect_header('admin.php?fct=smilies', 2, _AM_DBUPDATED);
		break;

	case "SmilesAdd":
		if (!icms::$security->check()) {
			redirect_header('admin.php?fct=smilies', 3, implode('<br />', icms::$security->getErrors()));
		}
		$uploader = new icms_file_MediaUploadHandler(ICMS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), 100000, 120, 120);
		$uploader->setPrefix('smil');
		if ($uploader->fetchMedia($xoops_upload_file[0])) {
			if (!$uploader->upload()) {
				$err = $uploader->getErrors();
			} else {
				$smile_url = $uploader->getSavedFileName();
				$smile_code = icms_core_DataFilter::stripSlashesGPC($smile_code);
				$smile_desc = icms_core_DataFilter::stripSlashesGPC($smile_desc);
				$smile_display = (int) $smile_display > 0 ? 1 : 0;
				$newid = $db->genId($db->prefix('smilies') . "_id_seq");
				$sql = sprintf("INSERT INTO %s (id, code, smile_url, emotion, display) VALUES ('%d', %s, %s, %s, '%d')", $db->prefix('smiles'), (int) $newid, $db->quoteString($smile_code), $db->quoteString($smile_url), $db->quoteString($smile_desc), $smile_display);
				if (!$db->query($sql)) {
					$err = _CO_ICMS_SAVE_ERROR;
				}
			}
		} else {
			$err = $uploader->getErrors();
		}

		if (!isset($err)) {
			redirect_header('admin.php?fct=smilies&amp;op=SmilesAdmin', 2, _AM_DBUPDATED);
		} else {
			icms_cp_header();
			icms_core_Message::error($err);
			icms_cp_footer();
		}
		break;

	case "SmilesEdit":
		if ($id > 0) {
			SmilesEdit($id);
		}
		break;

	case "SmilesSave":
		if ($id <= 0 | !icms::$security->check()) {
			redirect_header('admin.php?fct=smilies', 3, implode('<br />', icms::$security->getErrors()));
		}
		$smile_code = icms_core_DataFilter::stripSlashesGPC($smile_code);
		$smile_desc = icms_core_DataFilter::stripSlashesGPC($smile_desc);
		$smile_display = (int) $smile_display > 0 ? 1 : 0;
		if ($_FILES['smile_url']['name'] != "") {
			$uploader = new icms_file_MediaUploadHandler(ICMS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), 100000, 120, 120);
			$uploader->setPrefix('smil');
			if ($uploader->fetchMedia($xoops_upload_file[0])) {
				if (!$uploader->upload()) {
					$err = $uploader->getErrors();
				} else {
					$smile_url = $uploader->getSavedFileName();
					if (!$db->query(sprintf("UPDATE %s SET code = %s, smile_url = %s, emotion = %s, display = %d WHERE id = '%d'", $db->prefix('smiles'), $db->quoteString($smile_code), $db->quoteString($smile_url), $db->quoteString($smile_desc), $smile_display, $id))) {
						$err = _CO_ICMS_SAVE_ERROR;
					} else {
						$oldsmile_path = str_replace("\\", "/", realpath(ICMS_UPLOAD_PATH . '/' . trim($old_smile)));
						if (0 === strpos($oldsmile_path, ICMS_UPLOAD_PATH) && is_file($oldsmile_path)) {
							unlink($oldsmile_path);
						}
					}
				}
			} else {
				$err = $uploader->getErrors();
			}
		} else {
			$sql = sprintf("UPDATE %s SET code = %s, emotion = %s, display = '%d' WHERE id = '%d'", $db->prefix('smiles'), $db->quoteString($smile_code), $db->quoteString($smile_desc), $smile_display, $id);
			if (!$db->query($sql)) {
				$err = _CO_ICMS_SAVE_ERROR;
			}
		}

		if (!isset($err)) {
			redirect_header('admin.php?fct=smilies&amp;op=SmilesAdmin', 2, _AM_DBUPDATED);
		} else {
			icms_cp_header();
			icms_core_Message::error($err);
			icms_cp_footer();
			exit();
		}
		break;

	case "SmilesDel":
		if ($id > 0) {
			icms_cp_header();
			icms_core_Message::confirm(array('fct' => 'smilies', 'op' => 'SmilesDelOk', 'id' => $id), 'admin.php', _AM_WAYSYWTDTS);
			icms_cp_footer();
		}
		break;

	case "SmilesDelOk":
		if ($id <= 0 | !icms::$security->check()) {
			redirect_header('admin.php?fct=smilies', 3, implode('<br />', icms::$security->getErrors()));
		}
		$sql = sprintf("DELETE FROM %s WHERE id = '%u'", $db->prefix('smiles'), $id);
		$db->query($sql);
		redirect_header("admin.php?fct=smilies&amp;op=SmilesAdmin", 2, _AM_DBUPDATED);
		break;

	case "SmilesAdmin":
	default:
		SmilesAdmin();
		break;
}

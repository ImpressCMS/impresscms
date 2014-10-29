<?php
/**
 * Administration of smilies, main functions file
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @package		System
 * @subpackage	Smilies
 * @todo		Extract HTML and CSS to a template
 * @version		SVN: $Id$

if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
}

/**
 * Logic and rendering for Smilies administration
 * 
 */
function SmilesAdmin() {
	global $icmsAdminTpl;
	$db =& icms_db_Factory::instance();
	$url_smiles = ICMS_UPLOAD_URL;
	icms_cp_header();

	if ($getsmiles = $db->query("SELECT * FROM " . $db->prefix("smiles"))) {
		if (($numsmiles = $db->getRowsNum($getsmiles)) == "0") {
			//EMPTY
		} else {
			$i = 0;
			while ($smiles = $db->fetchArray($getsmiles)) {
				$smile[$i]['code'] = icms_core_DataFilter::htmlSpecialChars($smiles['code']);
				$smile[$i]['smile_url'] = icms_core_DataFilter::htmlSpecialChars($smiles['smile_url']);
				$smile[$i]['smile_emotion'] = icms_core_DataFilter::htmlSpecialChars($smiles['emotion']);
				$smile[$i]['id'] = icms_core_DataFilter::htmlSpecialChars($smiles['id']);
				$smile[$i]['display'] = icms_core_DataFilter::htmlSpecialChars($smiles['display']);
				$i++;
				$icmsAdminTpl->assign("smilesarray", $smile);
			}
			$icmsAdminTpl->assign("security", icms::$security->getTokenHTML());		
		}
	} else {
		echo _AM_CNRFTSD;
	}
	$smiles['smile_code'] = '';
	$smiles['smile_url'] = 'blank.gif';
	$smiles['smile_desc'] = '';
	$smiles['smile_display'] = 1;
	$smiles['smile_form'] = _AM_ADDSMILE;
	$smiles['op'] = 'SmilesAdd';
	$smiles['id'] = '';
	$icmsAdminTpl->display(ICMS_MODULES_PATH . '/system/templates/admin/smilies/system_adm_smilies.html');
	include ICMS_MODULES_PATH . '/system/admin/smilies/smileform.php';
	$smile_form->display();
	icms_cp_footer();
}

/**
 * Logic and rendering for editing a smilie
 * 
 * @param int $id
 */
function SmilesEdit($id) {
	$db =& icms_db_Factory::instance();
	icms_cp_header();
	echo '<a href="admin.php?fct=smilies">' . _AM_SMILESCONTROL .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . _AM_EDITSMILE . '<br /><br />';
	if ($getsmiles = $db->query("SELECT * FROM " . $db->prefix("smiles") . " WHERE id = '". (int) $id . "'")) {
		$numsmiles = $db->getRowsNum($getsmiles);
		if ($numsmiles == 0) {
			//EMPTY
		} else {
			if ($smiles = $db->fetchArray($getsmiles)) {
				$smiles['smile_code'] = icms_core_DataFilter::htmlSpecialChars($smiles['code']);
				$smiles['smile_url'] = icms_core_DataFilter::htmlSpecialChars($smiles['smile_url']);
				$smiles['smile_desc'] = icms_core_DataFilter::htmlSpecialChars($smiles['emotion']);
				$smiles['smile_display'] = $smiles['display'];
				$smiles['smile_form'] = _AM_EDITSMILE;
				$smiles['op'] = 'SmilesSave';
				include ICMS_MODULES_PATH . '/system/admin/smilies/smileform.php';
				$smile_form->addElement(new icms_form_elements_Hidden('old_smile', $smiles['smile_url']));
				$smile_form->display();
			}
		}
	} else {
		echo _AM_CNRFTSD;
	}
	icms_cp_footer();
}

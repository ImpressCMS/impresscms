<?php
/**
* Block Positions Manager
*
* System tool that allow create and manage positions/areas to disply the blocks
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.1
* @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
* @version		$Id: pblockform.php 1244 2008-03-18 17:09:11Z TheRplima $
*/

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$form = new XoopsThemeForm($pblock['form_title'], 'pblockform', 'admin.php', "post", true);

$pname = new XoopsFormText(_AM_NAME, "pname", 30, 30, $pblock['pname']);
$pname->setDescription(_AM_PBNAME_DESC);
$form->addElement($pname);
$form->addElement(new XoopsFormText(_AM_TITLE, 'title', 30, 90, $pblock['title']), false);
$textarea = new XoopsFormTextArea(_AM_BPDESC, 'description', $pblock['description'], 5, 50);
$form->addElement($textarea);

if (isset($pblock['pbid'])) {
    $form->addElement(new XoopsFormHidden('pbid', $pblock['pbid']));
}
$form->addElement(new XoopsFormHidden('op', $pblock['act']));
$form->addElement(new XoopsFormHidden('fct', 'blockspadmin'));
$button_tray = new XoopsFormElementTray('', '&nbsp;');

$button_tray->addElement(new XoopsFormButton('', 'submitblock', _SUBMIT, "submit"));
$btn = new XoopsFormButton('', 'reset', _CANCEL, 'button');
$btn->setExtra('onclick="document.getElementById(\'addbposform\').style.display = \'none\'; return false;"');
$button_tray->addElement($btn);
$form->addElement($button_tray);
?>

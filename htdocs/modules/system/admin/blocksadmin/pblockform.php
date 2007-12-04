<?php
// $Id: blockform.php 755 2006-09-24 21:30:21Z phppp $
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
$form->addElement(new XoopsFormHidden('act', $pblock['act']));
$form->addElement(new XoopsFormHidden('fct', 'blocksadmin'));
$form->addElement(new XoopsFormHidden('op', 'adminpblocks'));
$button_tray = new XoopsFormElementTray('', '&nbsp;');

$button_tray->addElement(new XoopsFormButton('', 'submitblock', _SUBMIT, "submit"));
$form->addElement($button_tray);
?>

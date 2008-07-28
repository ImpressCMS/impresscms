<?php
// $Id: main.php 2 2005-11-02 18:23:29Z skalpa $
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

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}
include_once XOOPS_ROOT_PATH."/modules/system/admin/smilies/smilies.php";
if(!empty($_POST)) foreach($_POST as $k => $v) ${$k} = StopXSS($v);
if(!empty($_GET)) foreach($_GET as $k => $v) ${$k} = StopXSS($v);
$op = (isset($_GET['op']))?trim(StopXSS($_GET['op'])):((isset($_POST['op']))?trim(StopXSS($_POST['op'])):'SmilesAdmin');

switch($op) {
case "SmilesUpdate":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin.php?fct=smilies', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    $count = (!empty($_POST['smile_id']) && is_array($_POST['smile_id'])) ? count($_POST['smile_id']) : 0;
    $db =& Database::getInstance();
    for ($i = 0; $i < $count; $i++) {
        $smile_id = intval($_POST['smile_id'][$i]);
        if (empty($smile_id)) {
            continue;
        }
        $smile_display = empty($_POST['smile_display'][$i]) ? 0 : 1;
        if (isset($_POST['old_display'][$i]) && $_POST['old_display'][$i] != $smile_display[$i]) {
            $db->query("UPDATE ".$db->prefix('smiles')." SET display='".$smile_display."' WHERE id ='".$smile_id."'");
        }
    }
    redirect_header('admin.php?fct=smilies',2,_AM_DBUPDATED);
    break;

case "SmilesAdd":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin.php?fct=smilies', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    $db =& Database::getInstance();
    $myts =& MyTextSanitizer::getInstance();
    include_once XOOPS_ROOT_PATH.'/class/uploader.php';
    $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), 100000, 120, 120);
    $uploader->setPrefix('smil');
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
        if (!$uploader->upload()) {
            $err = $uploader->getErrors();
        } else {
            $smile_url = $uploader->getSavedFileName();
            $smile_code = $myts->stripSlashesGPC($_POST['smile_code']);
            $smile_desc = $myts->stripSlashesGPC($_POST['smile_desc']);
            $smile_display = intval($_POST['smile_display']) > 0 ? 1 : 0;
            $newid = $db->genId($db->prefix('smilies')."_id_seq");
            $sql = sprintf("INSERT INTO %s (id, code, smile_url, emotion, display) VALUES ('%d', %s, %s, %s, '%d')", $db->prefix('smiles'), intval($newid), $db->quoteString($smile_code), $db->quoteString($smile_url), $db->quoteString($smile_desc), $smile_display);
            if (!$db->query($sql)) {
                $err = 'Failed storing smiley data into the database';
            }
        }
    } else {
        $err = $uploader->getErrors();
    }
    if (!isset($err)) {
        redirect_header('admin.php?fct=smilies&amp;op=SmilesAdmin',2,_AM_DBUPDATED);
    } else {
        xoops_cp_header();
        xoops_error($err);
        xoops_cp_footer();
    }
    break;

case "SmilesEdit":
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        SmilesEdit($id);
    }
    break;

case "SmilesSave":
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id <= 0 | !$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin.php?fct=smilies', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    $myts =& MyTextSanitizer::getInstance();
    $smile_code = $myts->stripSlashesGPC($_POST['smile_code']);
    $smile_desc = $myts->stripSlashesGPC($_POST['smile_desc']);
    $smile_display = intval($_POST['smile_display']) > 0 ? 1 : 0;
    $db =& Database::getInstance();
    if ($_FILES['smile_url']['name'] != "") {
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), 100000, 120, 120);
        $uploader->setPrefix('smil');
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            if (!$uploader->upload()) {
                $err = $uploader->getErrors();
            } else {
                $smile_url = $uploader->getSavedFileName();
                if (!$db->query(sprintf("UPDATE %s SET code = %s, smile_url = %s, emotion = %s, display = %d WHERE id = '%d'", $db->prefix('smiles'), $db->quoteString($smile_code), $db->quoteString($smile_url), $db->quoteString($smile_desc), $smile_display, $id))) {
                    $err = 'Failed storing smiley data into the database';
                } else {
                    $oldsmile_path = str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH.'/'.trim($_POST['old_smile'])));
                    if (0 === strpos($oldsmile_path, XOOPS_UPLOAD_PATH) && is_file($oldsmile_path)) {
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
            $err = 'Failed storing smiley data into the database';
        }
    }
    if (!isset($err)) {
        redirect_header('admin.php?fct=smilies&amp;op=SmilesAdmin',2,_AM_DBUPDATED);
    } else {
        xoops_cp_header();
        xoops_error($err);
        xoops_cp_footer();
        exit();
    }
    break;

case "SmilesDel":
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        xoops_cp_header();
        xoops_confirm(array('fct' => 'smilies', 'op' => 'SmilesDelOk', 'id' => $id), 'admin.php', _AM_WAYSYWTDTS);
        xoops_cp_footer();
    }
    break;

case "SmilesDelOk":
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id <= 0 | !$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin.php?fct=smilies', 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    $db =& Database::getInstance();
    $sql = sprintf("DELETE FROM %s WHERE id = '%u'", $db->prefix('smiles'), $id);
    $db->query($sql);
    redirect_header("admin.php?fct=smilies&amp;op=SmilesAdmin",2,_AM_DBUPDATED);
    break;

case "SmilesAdmin":
default:
    SmilesAdmin();
    break;
}
?>
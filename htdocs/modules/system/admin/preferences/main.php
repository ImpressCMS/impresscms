<?php
// $Id: main.php 1029 2007-09-09 03:49:25Z phppp $
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
} else {
    $op = 'list';
    if (isset($_POST)) {
        foreach ( $_POST as $k => $v ) {
            ${$k} = $v;
        }
    }
    if (isset($_GET['op'])) {
        $op = trim($_GET['op']);
    }

    if (isset($_GET['confcat_id'])) {
        $confcat_id = intval($_GET['confcat_id']);
    }
    if ($op == 'list') {
        $confcat_handler = xoops_gethandler('configcategory');
        $confcats = $confcat_handler->getObjects();
        $catcount = count($confcats);
        xoops_cp_header();
        echo '<div class="CPbigTitle" style="background-image: url('.XOOPS_URL.'/modules/system/admin/preferences/images/preferences_big.png)">'._MD_AM_SITEPREF.'</div><br />';
        for ($i = 0; $i < $catcount; $i++) {
            echo '<li>'.constant($confcats[$i]->getVar('confcat_name')).' [<a href="admin.php?fct=preferences&amp;op=show&amp;confcat_id='.$confcats[$i]->getVar('confcat_id').'">'._EDIT.'</a>]</li>';
        }
        echo '</ul>';
        xoops_cp_footer();
        exit();
    }

    if ($op == 'show') {
        if (empty($confcat_id)) {
            $confcat_id = 1;
        }
        $confcat_handler =& xoops_gethandler('configcategory');
        $confcat =& $confcat_handler->get($confcat_id);
        if (!is_object($confcat)) {
            redirect_header('admin.php?fct=preferences', 1);
        }
        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
        $form = new XoopsThemeForm(constant($confcat->getVar('confcat_name')), 'pref_form', 'admin.php?fct=preferences', 'post', true);
        $config_handler =& xoops_gethandler('config');
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('conf_modid', 0));
        $criteria->add(new Criteria('conf_catid', $confcat_id));
        $config = $config_handler->getConfigs($criteria);
        $confcount = count($config);
        for ($i = 0; $i < $confcount; $i++) {
            $title = (!defined($config[$i]->getVar('conf_desc')) || constant($config[$i]->getVar('conf_desc')) == '') ? constant($config[$i]->getVar('conf_title')) : constant($config[$i]->getVar('conf_title')).'<br /><br /><span style="font-weight:normal;">'.constant($config[$i]->getVar('conf_desc')).'</span>';
            switch ($config[$i]->getVar('conf_formtype')) {
            case 'textarea':
                $myts =& MyTextSanitizer::getInstance();
                if ($config[$i]->getVar('conf_valuetype') == 'array') {
                    // this is exceptional.. only when value type is arrayneed a smarter way for this
                    $ele = ($config[$i]->getVar('conf_value') != '') ? new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars(implode('|', $config[$i]->getConfValueForOutput())), 5, 50) : new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), '', 5, 50);
                } else {
                    $ele = new XoopsFormDhtmlTextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                }
                break;
            case 'select':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $options = $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
                    $optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'select_multi':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), 5, true);
                $options = $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
                    $optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'yesno':
                $ele = new XoopsFormRadioYN($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), _YES, _NO);
                break;
            case 'theme':
            case 'theme_multi':
                $ele = ($config[$i]->getVar('conf_formtype') != 'theme_multi') ? new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput()) : new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), 5, true);
				require_once XOOPS_ROOT_PATH."/class/xoopslists.php";
				$dirlist = XoopsLists::getThemesList();
                if (!empty($dirlist)) {
                    asort($dirlist);
                    $ele->addOptionArray($dirlist);
                }
                //$themeset_handler =& xoops_gethandler('themeset');
                //$themesetlist =& $themeset_handler->getList();
                //asort($themesetlist);
                //foreach ($themesetlist as $key => $name) {
                //  $ele->addOption($key, $name.' ('._MD_AM_THEMESET.')');
                //}
                // old theme value is used to determine whether to update cache or not. kind of dirty way
                $form->addElement(new XoopsFormHidden('_old_theme', $config[$i]->getConfValueForOutput()));
                break;
            case 'editor':
            case 'editor_multi':
                $ele = ($config[$i]->getVar('conf_formtype') != 'editor_multi') ? new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput()) : new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), 5, true);
				$ele->addOption("default");
				require_once XOOPS_ROOT_PATH."/class/xoopslists.php";
				$dirlist = XoopsLists::getEditorsList();

                if (!empty($dirlist)) {
                    asort($dirlist);
                    $ele->addOptionArray($dirlist);
                }
                break;
            case 'select_font':
            	$ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
            	require_once XOOPS_ROOT_PATH."/class/xoopslists.php";
            	$dirlist = XoopsLists::getFileListAsArray(ICMS_ROOT_PATH."/libraries/captcha/fonts/");
            	if (!empty($dirlist)) {
            		asort($dirlist);
            		$ele->addOptionArray($dirlist);
            	}
            	$form->addElement(new XoopsFormHidden('_old_theme', $config[$i]->getConfValueForOutput()));
            	break;
            case 'tplset':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $tplset_handler =& xoops_gethandler('tplset');
                $tplsetlist = $tplset_handler->getList();
                asort($tplsetlist);
                foreach ($tplsetlist as $key => $name) {
                    $ele->addOption($key, $name);
                }
                // old theme value is used to determine whether to update cache or not. kind of dirty way
                $form->addElement(new XoopsFormHidden('_old_theme', $config[$i]->getConfValueForOutput()));
                break;
            case 'timezone':
                $ele = new XoopsFormSelectTimezone($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                break;
            case 'language':
                $ele = new XoopsFormSelectLang($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                break;
            case 'startpage':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $module_handler =& xoops_gethandler('module');
                $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
                $criteria->add(new Criteria('isactive', 1));
                $moduleslist = $module_handler->getList($criteria, true);
                $moduleslist['--'] = _MD_AM_NONE;
                //Adding support to select custom links to be the start page
                $page_handler =& xoops_gethandler('page');
                $criteria = new CriteriaCompo(new Criteria('page_status', 1));
                $criteria->add(new Criteria('page_url', '%*','NOT LIKE'));
                $pagelist = $page_handler->getList($criteria);
                $list = array_merge($moduleslist,$pagelist);
                asort($list);
                $ele->addOptionArray($list);
                break;
            case 'group':
                $ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'group_multi':
                $ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
                break;
            // RMV-NOTIFY - added 'user' and 'user_multi'
            case 'user':
                $ele = new XoopsFormSelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'user_multi':
                $ele = new XoopsFormSelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
                break;
            case 'module_cache':
                $module_handler =& xoops_gethandler('module');
                $modules = $module_handler->getObjects(new Criteria('hasmain', 1), true);
                $currrent_val = $config[$i]->getConfValueForOutput();
                $cache_options = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK);
                if (count($modules) > 0) {
                    $ele = new XoopsFormElementTray($title, '<br />');
                    foreach (array_keys($modules) as $mid) {
                        $c_val = isset($currrent_val[$mid]) ? intval($currrent_val[$mid]) : null;
                        $selform = new XoopsFormSelect($modules[$mid]->getVar('name'), $config[$i]->getVar('conf_name')."[$mid]", $c_val);
                        $selform->addOptionArray($cache_options);
                        $ele->addElement($selform);
                        unset($selform);
                    }
                } else {
                    $ele = new XoopsFormLabel($title, _MD_AM_NOMODULE);
                }
                break;
            case 'site_cache':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $ele->addOptionArray(array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK));
                break;
            case 'password':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormPassword($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            case 'color':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormColorPicker($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            case 'hidden':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormHidden( $config[$i]->getVar('conf_name'), $myts->htmlspecialchars( $config[$i]->getConfValueForOutput() ) );
                break;
            case 'select_pages':
                $myts =& MyTextSanitizer::getInstance();
                $content_handler =& xoops_gethandler('content');
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $ele->addOptionArray($content_handler->getContentList());
                break;
                ##############################################################################################
                # Added by Fábio Egas in XTXM version
                ##############################################################################################
            case 'select_image':
            	include_once XOOPS_ROOT_PATH."/class/xoopsform/formimage.php";
            	$myts =& MyTextSanitizer::getInstance();
            	$ele = new MastopFormSelectImage($title, $config[$i]->getVar('conf_name'),$config[$i]->getConfValueForOutput());
            	break;
            case 'textbox':
            default:
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormText($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            }
            $hidden = new XoopsFormHidden('conf_ids[]', $config[$i]->getVar('conf_id'));
            $form->addElement($ele);
            $form->addElement($hidden);
            unset($ele);
            unset($hidden);
        }
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'button', _GO, 'submit'));
        xoops_cp_header();
        echo '<div class="CPbigTitle" style="background-image: url('.XOOPS_URL.'/modules/system/admin/preferences/images/preferences_big.png)"><a href="admin.php?fct=preferences">'. _MD_AM_PREFMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.constant($confcat->getVar('confcat_name')).'<br /><br /></div><br />';
        $form->display();
        xoops_cp_footer();
        exit();
    }

    if ($op == 'showmod') {
        $config_handler =& xoops_gethandler('config');
        $mod = isset($_GET['mod']) ? intval($_GET['mod']) : 0;
        if (empty($mod)) {
            header('Location: admin.php?fct=preferences');
            exit();
        }
        $config = $config_handler->getConfigs(new Criteria('conf_modid', $mod));
        $count = count($config);
        if ($count < 1) {
            redirect_header('admin.php?fct=preferences', 1);
        }
        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        $form = new XoopsThemeForm(_MD_AM_MODCONFIG, 'pref_form', 'admin.php?fct=preferences', 'post', true);
        $module_handler =& xoops_gethandler('module');
        $module =& $module_handler->get($mod);
        if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php')) {
            include_once XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php';
        }

        // if has comments feature, need comment lang file
        if ($module->getVar('hascomments') == 1) {
            include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/comment.php';
        }
        // RMV-NOTIFY
        // if has notification feature, need notification lang file
        if ($module->getVar('hasnotification') == 1) {
            include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/notification.php';
        }

        $modname = $module->getVar('name');
        if ($module->getInfo('adminindex')) {
            $form->addElement(new XoopsFormHidden('redirect', XOOPS_URL.'/modules/'.$module->getVar('dirname').'/'.$module->getInfo('adminindex')));
        }
        for ($i = 0; $i < $count; $i++) {
            $title = (!defined($config[$i]->getVar('conf_desc')) || constant($config[$i]->getVar('conf_desc')) == '') ? constant($config[$i]->getVar('conf_title')) : constant($config[$i]->getVar('conf_title')).'<br /><br /><span style="font-weight:normal;">'.constant($config[$i]->getVar('conf_desc')).'</span>';
            switch ($config[$i]->getVar('conf_formtype')) {
            case 'textarea':
                $myts =& MyTextSanitizer::getInstance();
                if ($config[$i]->getVar('conf_valuetype') == 'array') {
                    // this is exceptional.. only when value type is arrayneed a smarter way for this
                    $ele = ($config[$i]->getVar('conf_value') != '') ? new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars(implode('|', $config[$i]->getConfValueForOutput())), 5, 50) : new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), '', 5, 50);
                } else {
                    $ele = new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()), 5, 50);
                }
                break;
            case 'select':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $options =& $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
                    $optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'select_multi':
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), 5, true);
                $options =& $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
                $opcount = count($options);
                for ($j = 0; $j < $opcount; $j++) {
                    $optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
                    $optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
                    $ele->addOption($optval, $optkey);
                }
                break;
            case 'yesno':
                $ele = new XoopsFormRadioYN($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), _YES, _NO);
                break;
            case 'group':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'group_multi':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
                break;
            // RMV-NOTIFY: added 'user' and 'user_multi'
            case 'user':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
                break;
            case 'user_multi':
                include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
                $ele = new XoopsFormSelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
                break;
            case 'password':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormPassword($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            case 'color':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormColorPicker($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            case 'hidden':
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormHidden( $config[$i]->getVar('conf_name'), $myts->htmlspecialchars( $config[$i]->getConfValueForOutput() ) );
                break;
            case 'select_pages':
                $myts =& MyTextSanitizer::getInstance();
                $content_handler =& xoops_gethandler('content');
                $ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
                $ele->addOptionArray($content_handler->getContentList());
                break;
            case 'textbox':
            default:
                $myts =& MyTextSanitizer::getInstance();
                $ele = new XoopsFormText($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
                break;
            }
            $hidden = new XoopsFormHidden('conf_ids[]', $config[$i]->getVar('conf_id'));
            $form->addElement($ele);
            $form->addElement($hidden);
            unset($ele);
            unset($hidden);
        }
        $form->addElement(new XoopsFormHidden('op', 'save'));
        $form->addElement(new XoopsFormButton('', 'button', _GO, 'submit'));
        xoops_cp_header();
        $form->display();
        xoops_cp_footer();
        exit();
    }

    if ($op == 'save') {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header("admin.php?fct=preferences", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        require_once(XOOPS_ROOT_PATH.'/class/template.php');
        $xoopsTpl = new XoopsTpl();
        $count = count($conf_ids);
        $tpl_updated = false;
        $theme_updated = false;
        $startmod_updated = false;
        $lang_updated = false;
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $config =& $config_handler->getConfig($conf_ids[$i]);
                $new_value =& ${$config->getVar('conf_name')};
                if (is_array($new_value) || $new_value != $config->getVar('conf_value')) {
                    // if language has been changed
                    if (!$lang_updated && $config->getVar('conf_catid') == XOOPS_CONF && $config->getVar('conf_name') == 'language') {
                        $xoopsConfig['language'] = ${$config->getVar('conf_name')};
                        $lang_updated = true;
                    }
                    // if default theme has been changed
                    if (!$theme_updated && $config->getVar('conf_catid') == XOOPS_CONF && $config->getVar('conf_name') == 'theme_set') {
                        $member_handler =& xoops_gethandler('member');
                        $member_handler->updateUsersByField('theme', ${$config->getVar('conf_name')});
                        $theme_updated = true;
                    }

                    // if default template set has been changed
                    if (!$tpl_updated && $config->getVar('conf_catid') == XOOPS_CONF && $config->getVar('conf_name') == 'template_set') {
                        // clear cached/compiled files and regenerate them if default theme has been changed
                        if ($xoopsConfig['template_set'] != ${$config->getVar('conf_name')}) {
                            $newtplset = ${$config->getVar('conf_name')};

                            // clear all compiled and cachedfiles
                            $xoopsTpl->clear_compiled_tpl();

                            // generate compiled files for the new theme
                            // block files only for now..
                            $tplfile_handler =& xoops_gethandler('tplfile');
                            $dtemplates =& $tplfile_handler->find('default', 'block');
                            $dcount = count($dtemplates);

                            // need to do this to pass to xoops_template_touch function
                            $GLOBALS['xoopsConfig']['template_set'] = $newtplset;

                            for ($i = 0; $i < $dcount; $i++) {
                                $found =& $tplfile_handler->find($newtplset, 'block', $dtemplates[$i]->getVar('tpl_refid'), null);
                                if (count($found) > 0) {
                                    // template for the new theme found, compile it
                                    xoops_template_touch($found[0]->getVar('tpl_id'));
                                } else {
                                    // not found, so compile 'default' template file
                                    xoops_template_touch($dtemplates[$i]->getVar('tpl_id'));
                                }
                            }

                            // generate image cache files from image binary data, save them under cache/
                            $image_handler =& xoops_gethandler('imagesetimg');
                            $imagefiles =& $image_handler->getObjects(new Criteria('tplset_name', $newtplset), true);
                            foreach (array_keys($imagefiles) as $i) {
                                if (!$fp = fopen(XOOPS_CACHE_PATH.'/'.$newtplset.'_'.$imagefiles[$i]->getVar('imgsetimg_file'), 'wb')) {
                                } else {
                                    fwrite($fp, $imagefiles[$i]->getVar('imgsetimg_body'));
                                    fclose($fp);
                                }
                            }
                        }
                        $tpl_updated = true;
                    }

                    // add read permission for the start module to all groups
                    if (!$startmod_updated  && $new_value != '--' && $config->getVar('conf_catid') == XOOPS_CONF && $config->getVar('conf_name') == 'startpage') {
                        $member_handler =& xoops_gethandler('member');
                        $groups =& $member_handler->getGroupList();
                        $moduleperm_handler =& xoops_gethandler('groupperm');
                        $module_handler =& xoops_gethandler('module');
                        $arr = explode('-',$new_value);
                        if (count($arr) > 1){
                        	$mid = $arr[0];
                        	$module =& $module_handler->get($mid);
                        }else{
                        	$module =& $module_handler->getByDirname($new_value);
                        }
                        if (is_object($module)){
                        	foreach ($groups as $groupid => $groupname) {
                        		if (!$moduleperm_handler->checkRight('module_read', $module->getVar('mid'), $groupid)) {
                        			$moduleperm_handler->addRight('module_read', $module->getVar('mid'), $groupid);
                        		}
                        	}
                        }
                        $startmod_updated = true;
                    }

                    $config->setConfValueForInput($new_value);
                    $config_handler->insertConfig($config);
                }
                unset($new_value);
            }
        }

        if (!empty($use_mysession) && $xoopsConfig['use_mysession'] == 0 && $session_name != '') {
            setcookie($session_name, session_id(), time()+(60*intval($session_expire)), '/',  '', 0);
        }

        // Clean cached files, may take long time
        // User reigister_shutdown_function to keep running after connection closes so that cleaning cached files can be finished
        // Cache management should be performed on a separate page
        register_shutdown_function( array( &$xoopsTpl, 'clear_all_cache' ) );

        // If language is changed, leave the admin menu file to be regenerated upon next request,
        // otherwise regenerate admin menu file for now
        if (!$lang_updated) {
            // regenerate admin menu file
            register_shutdown_function( 'xoops_module_write_admin_menu', xoops_module_get_admin_menu() );
        } else {
	        $redirect = XOOPS_URL . "/admin.php";
        }

        if (isset($redirect) && $redirect != '') {
            redirect_header($redirect, 2, _MD_AM_DBUPDATED);
        } else {
            redirect_header("admin.php?fct=preferences",2,_MD_AM_DBUPDATED);
        }
    }
}

?>

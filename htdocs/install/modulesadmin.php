<?php
// $Id$
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
* Installer tables creation page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The ImpressCMS project http://www.impresscms.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        1.0
* @author		Kazumi Ono (AKA onokazu)
* @author		RpLima
* @author		Martijn Hertog (AKA wtravel) <martin@efqconsultancy.com>
* @version		$Id$
*/

function xoops_module_install($dirname)
{
    global $xoopsUser, $xoopsConfig;
    $dirname = trim($dirname);
    $db =& Database::getInstance();
    $reservedTables = array('avatar', 'avatar_users_link', 'block_module_link', 'xoopscomments', 'config', 'configcategory', 'configoption', 'image', 'imagebody', 'imagecategory', 'imgset', 'imgset_tplset_link', 'imgsetimg', 'groups','groups_users_link','group_permission', 'online', 'bannerclient', 'banner', 'bannerfinish', 'priv_msgs', 'ranks', 'session', 'smiles', 'users', 'newblocks', 'modules', 'tplfile', 'tplset', 'tplsource', 'xoopsnotifications', 'banner', 'bannerclient', 'bannerfinish');
    $module_handler =& xoops_gethandler('module');
    if ($module_handler->getCount(new Criteria('dirname', $dirname)) == 0) {
        $module =& $module_handler->create();
        $module->loadInfoAsVar($dirname);
        $module->setVar('weight', 1);
        $error = false;
        $errs = array();
        $sqlfile =& $module->getInfo('sqlfile');
        $msgs = array();
        $msgs[] = '<h4 style="text-align:left;margin-bottom: 0px;border-bottom: dashed 1px #000000;">'.sprintf(_INSTALL_INSTALLING, $module->getInfo('name')).'...</h4>';
        if ($module->getInfo('image') != false && trim($module->getInfo('image')) != '') {
            $msgs[] ='<img src="'.XOOPS_URL.'/modules/'.$dirname.'/'.trim($module->getInfo('image')).'" alt="" />';
        }
        $msgs[] ='<b>Version:</b> '.$module->getInfo('version');
        $msgs[] = '';
        $errs[] = '<h4 style="text-align:left;margin-bottom: 0px;border-bottom: dashed 1px #000000;">'.sprintf(_INSTALL_INSTALLING, $module->getInfo('name')).'...</h4>';
        if ($sqlfile != false && is_array($sqlfile)) {

            $sql_file_path = XOOPS_ROOT_PATH."/modules/".$dirname."/".$sqlfile[XOOPS_DB_TYPE];
            if (!file_exists($sql_file_path)) {
                //$errs[] = "SQL file not found at <b>$sql_file_path</b>";
                $error = true;
            } else {
                //$msgs[] = "SQL file found at <b>$sql_file_path</b>.<br  /> Creating tables...";
                include_once XOOPS_ROOT_PATH.'/class/database/sqlutility.php';
                $sql_query = fread(fopen($sql_file_path, 'r'), filesize($sql_file_path));
                $sql_query = trim($sql_query);
                SqlUtility::splitSqlFile($pieces, $sql_query);
                $created_tables = array();
                foreach ($pieces as $piece) {
                    // [0] contains the prefixed query
                    // [4] contains unprefixed table name
                    $prefixed_query = SqlUtility::prefixQuery($piece, $db->prefix());
                    if (!$prefixed_query) {
                        $errs[] = "<b>$piece</b> is not a valid SQL!";
                        $error = true;
                        break;
                    }
                    // check if the table name is reserved
                    if (!in_array($prefixed_query[4], $reservedTables)) {
                        // not reserved, so try to create one
                        if (!$db->query($prefixed_query[0])) {
                            $errs[] = $db->error();
                            $error = true;
                            break;
                        } else {

                            if (!in_array($prefixed_query[4], $created_tables)) {
                                //$msgs[] = '&nbsp;&nbsp;Table <b>'.$db->prefix($prefixed_query[4]).'</b> created.';
                                $created_tables[] = $prefixed_query[4];
                            } else {
                                //$msgs[] = '&nbsp;&nbsp;Data inserted to table <b>'.$db->prefix($prefixed_query[4]).'</b>.';
                            }
                        }
                    } else {
                        // the table name is reserved, so halt the installation
                        //$errs[] = '<b>'.$prefixed_query[4]."</b> is a reserved table!";
                        $error = true;
                        break;
                    }
                }
                // if there was an error, delete the tables created so far, so the next installation will not fail
                if ($error == true) {
                    foreach ($created_tables as $ct) {
                        //echo $ct;
                        $db->query("DROP TABLE ".$db->prefix($ct));
                    }
                }
            }
        }
        // if no error, save the module info and blocks info associated with it.
        if ($error == false) {
            if (!$module_handler->insert($module)) {
                $errs[] = sprintf(_INSTALL_COULD_NOT_INSERT, $module->getVar('name'));
                foreach ($created_tables as $ct) {
                    $db->query("DROP TABLE ".$db->prefix($ct));
                }
                $ret = "<p>".sprintf(_INSTALL_COULD_NOT_INSERT, "<b>".$module->name()."</b>")."&nbsp;"._INSTALL_ERRORS."<br />";
                foreach ( $errs as $err ) {
                    $ret .= " - ".$err."<br />";
                }
                $ret .= "</p>";
                unset($module);
                unset($created_tables);
                unset($errs);
                unset($msgs);
                return $ret;
            } else {
                $newmid = $module->getVar('mid');
                unset($created_tables);
                $tplfile_handler =& xoops_gethandler('tplfile');
                $templates = $module->getInfo('templates');
                if ($templates != false) {
                    foreach ($templates as $tpl) {
                        $tplfile =& $tplfile_handler->create();
                        $tpldata =& xoops_module_gettemplate1($dirname, $tpl['file']);
                        $tplfile->setVar('tpl_source', $tpldata, true);
                        $tplfile->setVar('tpl_refid', $newmid);
                        $tplfile->setVar('tpl_tplset', 'default');
                        $tplfile->setVar('tpl_file', $tpl['file']);
                        $tplfile->setVar('tpl_desc', $tpl['description'], true);
                        $tplfile->setVar('tpl_module', $dirname);
                        $tplfile->setVar('tpl_lastmodified', time());
                        $tplfile->setVar('tpl_lastimported', 0);
                        $tplfile->setVar('tpl_type', 'module');
                        if ($tplfile_handler->insert($tplfile)) {
                            $newtplid = $tplfile->getVar('tpl_id');
                            // generate compiled file
                            include_once XOOPS_ROOT_PATH.'/class/template.php';
                            if (!xoops_template_touch($newtplid)) {
                                //$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>'.$tpl['file'].'</b>.</span>';
                            } else {
                                //$msgs[] = '&nbsp;&nbsp;Template <b>'.$tpl['file'].'</b> compiled.</span>';
                            }
                        }
                        unset($tpldata);
                    }
                }
                include_once XOOPS_ROOT_PATH.'/class/template.php';
                xoops_template_clear_module_cache($newmid);
                $blocks = $module->getInfo('blocks');
                if ($blocks != false) {
                    foreach ($blocks as $blockkey => $block) {
                        // break the loop if missing block config
                        if (!isset($block['file']) || !isset($block['show_func'])) {
                            break;
                        }
                        $options = '';
                        if (!empty($block['options'])) {
                            $options = trim($block['options']);
                        }
                        $newbid = $db->genId($db->prefix('newblocks').'_bid_seq');
                        $edit_func = isset($block['edit_func']) ? trim($block['edit_func']) : '';
                        $template = '';
                        if ((isset($block['template']) && trim($block['template']) != '')) {
                            $content =& xoops_module_gettemplate1($dirname, $block['template'], true);
                        }
                        if (!$content) {
                            $content = '';
                        } else {
                            $template = trim($block['template']);
                        }
                        $block_name = addslashes(trim($block['name']));
                        $sql = "INSERT INTO ".$db->prefix("newblocks")." (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, c_type, isactive, dirname, func_file, show_func, edit_func, template, bcachetime, last_modified) VALUES ($newbid, $newmid, ".intval($blockkey).", '$options', '".$block_name."','".$block_name."', '', 0, 0, 0, 'M', 'H', 1, '".addslashes($dirname)."', '".addslashes(trim($block['file']))."', '".addslashes(trim($block['show_func']))."', '".addslashes($edit_func)."', '".$template."', 0, ".time().")";
                        if (!$db->query($sql)) {
                        } else {
                            if (empty($newbid)) {
                                $newbid = $db->getInsertId();
                            }
                            $sql = 'INSERT INTO '.$db->prefix('block_module_link').' (block_id, module_id) VALUES ('.$newbid.', -1)';
                            $db->query($sql);
                            if ($template != '') {
                                $tplfile =& $tplfile_handler->create();
                                $tplfile->setVar('tpl_refid', $newbid);
                                $tplfile->setVar('tpl_source', $content, true);
                                $tplfile->setVar('tpl_tplset', 'default');
                                $tplfile->setVar('tpl_file', $block['template']);
                                $tplfile->setVar('tpl_module', $dirname);
                                $tplfile->setVar('tpl_type', 'block');
                                $tplfile->setVar('tpl_desc', $block['description'], true);
                                $tplfile->setVar('tpl_lastimported', 0);
                                $tplfile->setVar('tpl_lastmodified', time());
                                if ($tplfile_handler->insert($tplfile)) {
                                    $newtplid = $tplfile->getVar('tpl_id');
                                    // generate compiled file
                                    include_once XOOPS_ROOT_PATH.'/class/template.php';
                                    if (!xoops_template_touch($newtplid)) {
                                        //$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Failed compiling template <b>'.$block['template'].'</b>.</span>';
                                    } else {
                                        //$msgs[] = '&nbsp;&nbsp;Template <b>'.$block['template'].'</b> compiled.</span>';
                                    }
                                }
                            }
                        }
                        unset($content);
                    }
                    unset($blocks);
                }
                $configs = $module->getInfo('config');
                if ($configs != false) {
                    if ($module->getVar('hascomments') != 0) {
                        include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
                        array_push($configs, array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE, '_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN)));
                        array_push($configs, array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0));
                    }
                } else {
                    if ($module->getVar('hascomments') != 0) {
                        $configs = array();
                        include_once(XOOPS_ROOT_PATH.'/include/comment_constants.php');
                        $configs[] = array('name' => 'com_rule', 'title' => '_CM_COMRULES', 'description' => '', 'formtype' => 'select', 'valuetype' => 'int', 'default' => 1, 'options' => array('_CM_COMNOCOM' => XOOPS_COMMENT_APPROVENONE, '_CM_COMAPPROVEALL' => XOOPS_COMMENT_APPROVEALL, '_CM_COMAPPROVEUSER' => XOOPS_COMMENT_APPROVEUSER, '_CM_COMAPPROVEADMIN' => XOOPS_COMMENT_APPROVEADMIN));
                        $configs[] = array('name' => 'com_anonpost', 'title' => '_CM_COMANONPOST', 'description' => '', 'formtype' => 'yesno', 'valuetype' => 'int', 'default' => 0);
                    }
                }
                // RMV-NOTIFY
                if ($module->getVar('hasnotification') != 0) {
                    if (empty($configs)) {
                        $configs = array();
                    }
                    // Main notification options
                    include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    include_once XOOPS_ROOT_PATH . '/include/notification_functions.php';
                    $options = array();
                    $options['_NOT_CONFIG_DISABLE'] = XOOPS_NOTIFICATION_DISABLE;
                    $options['_NOT_CONFIG_ENABLEBLOCK'] = XOOPS_NOTIFICATION_ENABLEBLOCK;
                    $options['_NOT_CONFIG_ENABLEINLINE'] = XOOPS_NOTIFICATION_ENABLEINLINE;
                    $options['_NOT_CONFIG_ENABLEBOTH'] = XOOPS_NOTIFICATION_ENABLEBOTH;

                    $configs[] = array ('name' => 'notification_enabled', 'title' => '_NOT_CONFIG_ENABLE', 'description' => '_NOT_CONFIG_ENABLEDSC', 'formtype' => 'select', 'valuetype' => 'int', 'default' => XOOPS_NOTIFICATION_ENABLEBOTH, 'options' => $options);
                    // Event-specific notification options
                    // FIXME: doesn't work when update module... can't read back the array of options properly...  " changing to &quot;
                    $options = array();
                    $categories =& notificationCategoryInfo('',$module->getVar('mid'));
                    foreach ($categories as $category) {
                        $events =& notificationEvents ($category['name'], false, $module->getVar('mid'));
                        foreach ($events as $event) {
                            if (!empty($event['invisible'])) {
                                continue;
                            }
                            $option_name = $category['title'] . ' : ' . $event['title'];
                            $option_value = $category['name'] . '-' . $event['name'];
                            $options[$option_name] = $option_value;
                        }
                    }
                    $configs[] = array ('name' => 'notification_events', 'title' => '_NOT_CONFIG_EVENTS', 'description' => '_NOT_CONFIG_EVENTSDSC', 'formtype' => 'select_multi', 'valuetype' => 'array', 'default' => array_values($options), 'options' => $options);
                }

                if ($configs != false) {
                    $config_handler =& xoops_gethandler('config');
                    $order = 0;
                    foreach ($configs as $config) {
                        $confobj =& $config_handler->createConfig();
                        $confobj->setVar('conf_modid', $newmid);
                        $confobj->setVar('conf_catid', 0);
                        $confobj->setVar('conf_name', $config['name']);
                        $confobj->setVar('conf_title', $config['title'], true);
                        $confobj->setVar('conf_desc', $config['description'], true);
                        $confobj->setVar('conf_formtype', $config['formtype']);
                        $confobj->setVar('conf_valuetype', $config['valuetype']);
                        $confobj->setConfValueForInput($config['default'], true);
                        $confobj->setVar('conf_order', $order);
                        $confop_msgs = '';
                        if (isset($config['options']) && is_array($config['options'])) {
                            foreach ($config['options'] as $key => $value) {
                                $confop =& $config_handler->createConfigOption();
                                $confop->setVar('confop_name', $key, true);
                                $confop->setVar('confop_value', $value, true);
                                $confobj->setConfOptions($confop);
                                $confop_msgs .= '<br />&nbsp;&nbsp;&nbsp;&nbsp;Config option added. Name: <b>'.$key.'</b> Value: <b>'.$value.'</b>';
                                unset($confop);
                            }
                        }
                        $order++;
                        if ($config_handler->insertConfig($confobj) != false) {
                            //$msgs[] = '&nbsp;&nbsp;Config <b>'.$config['name'].'</b> added to the database.'.$confop_msgs;
                        } else {
                            //$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not insert config <b>'.$config['name'].'</b> to the database.</span>';
                        }
                        unset($confobj);
                    }
                    unset($configs);
                }
            }
            //#################################################################################
            //#Alterei o código comentado pelo código abaixo e a instalação dos módulos funcionou blz.
            //#$groups =& $xoopsUser->getGroups();
            //#Depois tire esse comentário daqui.
            //#by RpLima
            //#####################################################################################
            $groups = ($xoopsUser) ? $xoopsUser->getGroups() : '';
            // retrieve all block ids for this module
            $blocks =& XoopsBlock::getByModule($newmid, false);
            $gperm_handler =& xoops_gethandler('groupperm');
            foreach ($groups as $mygroup) {
                if ($gperm_handler->checkRight('module_admin', 0, $mygroup)) {
                    $mperm =& $gperm_handler->create();
                    $mperm->setVar('gperm_groupid', $mygroup);
                    $mperm->setVar('gperm_itemid', $newmid);
                    $mperm->setVar('gperm_name', 'module_admin');
                    $mperm->setVar('gperm_modid', 1);
                    if (!$gperm_handler->insert($mperm)) {
                        //$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not add admin access right for Group ID <b>'.$mygroup.'</b></span>';
                    } else {
                        //$msgs[] = '&nbsp;&nbsp;Added admin access right for Group ID <b>'.$mygroup.'</b>';
                    }
                    unset($mperm);
                }
                $mperm =& $gperm_handler->create();
                $mperm->setVar('gperm_groupid', $mygroup);
                $mperm->setVar('gperm_itemid', $newmid);
                $mperm->setVar('gperm_name', 'module_read');
                $mperm->setVar('gperm_modid', 1);
                if (!$gperm_handler->insert($mperm)) {
                    //$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not add user access right for Group ID: <b>'.$mygroup.'</b></span>';
                } else {
                   // $msgs[] = '&nbsp;&nbsp;Added user access right for Group ID: <b>'.$mygroup.'</b>';
                }
                unset($mperm);
                foreach ($blocks as $blc) {
                    $bperm =& $gperm_handler->create();
                    $bperm->setVar('gperm_groupid', $mygroup);
                    $bperm->setVar('gperm_itemid', $blc);
                    $bperm->setVar('gperm_name', 'block_read');
                    $bperm->setVar('gperm_modid', 1);
                    if (!$gperm_handler->insert($bperm)) {
                        //$msgs[] = '&nbsp;&nbsp;<span style="color:#ff0000;">ERROR: Could not add block access right. Block ID: <b>'.$blc.'</b> Group ID: <b>'.$mygroup.'</b></span>';
                    } else {
                        //$msgs[] = '&nbsp;&nbsp;Added block access right. Block ID: <b>'.$blc.'</b> Group ID: <b>'.$mygroup.'</b>';
                    }
                    unset($bperm);
                }
            }
            unset($blocks);
            unset($groups);

            // execute module specific install script if any
            $install_script = $module->getInfo('onInstall');
            if (false != $install_script && trim($install_script) != '') {
                include_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/'.trim($install_script);
                if (function_exists('xoops_module_install_'.$dirname)) {
                    $func = 'xoops_module_install_'.$dirname;
                    if (!$func($module)) {
                        $msgs[] = _INSTALL_FAILED_TO_EXECUTE.$func;
                    } else {
                        $msgs[] = '<b>'.$func.'</b>'._INSTALL_EXECUTED_SUCCESSFULLY;
                    }
                }
            }

            $ret = '<p><code>';
            foreach ($msgs as $m) {
                $ret .= $m.'<br />';
            }
            unset($msgs);
            unset($errs);
            $ret .= '</code>'.sprintf(_INSTALL_MOD_INSTALL_SUCCESSFULLY, "<b>".$module->getVar('name')."</b>").'</p>';
            unset($module);
            return $ret;
        } else {
            $ret = '<p>';
            foreach ($errs as $er) {
                $ret .= '&nbsp;&nbsp;'.$er.'<br />';
            }
            unset($msgs);
            unset($errs);
            $ret .= '<br />'.sprintf(_INSTALL_MOD_INSTALL_FAILED, '<b>'.$dirname.'</b>').'&nbsp;'._INSTALL_ERRORS.':</p>';
            return $ret;
        }
    } else {
        return "<p>".sprintf(_INSTALL_IMPOSSIBLE_MOD_INSTALL, "<b>".$dirname."</b>")."&nbsp;"._INSTALL_ERRORS.":"."<br />&nbsp;&nbsp;".sprintf(_INSTALL_MOD_ALREADY_INSTALLED, $dirname)."</p>";
    }
}

function &xoops_module_gettemplate1($dirname, $template, $block=false)
{
    global $xoopsConfig;
    if ($block) {
        $path = XOOPS_ROOT_PATH.'/modules/'.$dirname.'/templates/blocks/'.$template;
    } else {
        $path = XOOPS_ROOT_PATH.'/modules/'.$dirname.'/templates/'.$template;
    }
    if (!file_exists($path)) {
        return false;
    } else {
        $lines = file($path);
    }
    if (!$lines) {
        return false;
    }
    $ret = '';
    $count = count($lines);
    for ($i = 0; $i < $count; $i++) {
        $ret .= str_replace("\n", "\r\n", str_replace("\r\n", "\n", $lines[$i]));
    }
    return $ret;
}

/*
 * gets list of name of directories inside a directory
 */
function getDirList($dirname)
{
	require_once dirname(dirname(__FILE__))."/class/xoopslists.php";
	return XoopsLists::getDirListAsArray($dirname);
}

?>

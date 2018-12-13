<?php
// $Id: blauer-fisch $
//%%%%%%	File Name  modulesadmin.php 	%%%%%
define("_MD_AM_MODADMIN","模块管理");
define("_MD_AM_MODULE","模块");
define("_MD_AM_VERSION","版本");
define("_MD_AM_LASTUP","上次更新");
define("_MD_AM_DEACTIVATED","未激活");
define("_MD_AM_ACTION","动作");
define("_MD_AM_DEACTIVATE","撤销");
define("_MD_AM_ACTIVATE","激活");
define("_MD_AM_UPDATE","更新");
define("_MD_AM_DUPEN","复制模块!");
define("_MD_AM_DEACTED","所选模块已撤销，现在可以安全地卸载此模块");
define("_MD_AM_ACTED","所选模块已激活!");
define("_MD_AM_UPDTED","所选模块已更新!");
define("_MD_AM_SYSNO","系统模块不能撤销");
define("_MD_AM_STRTNO","此模块已设为默认起始页面，请更改起始模块");

// added in RC2
define("_MD_AM_PCMFM","请确认:");

// added in RC3
define("_MD_AM_ORDER","次序");
define("_MD_AM_ORDER0","(0 = 隐藏)");
define("_MD_AM_ACTIVE","激活");
define("_MD_AM_INACTIVE","撤销");
define("_MD_AM_NOTINSTALLED","未安装");
define("_MD_AM_NOCHANGE","没有更改");
define("_MD_AM_INSTALL","安装");
define("_MD_AM_UNINSTALL","卸载");
define("_MD_AM_SUBMIT","提交");
define("_MD_AM_CANCEL","取消");
define("_MD_AM_DBUPDATE","数据库更新成功!");
define("_MD_AM_BTOMADMIN","返回模块管理页面");

// %s represents module name
define("_MD_AM_FAILINS","不能安装 %s ");
define("_MD_AM_FAILACT","不能激活 %s ");
define("_MD_AM_FAILDEACT","不能撤销 %s ");

define("_MD_AM_FAILUNINS","不能卸载 %s ");
define("_MD_AM_FAILORDER","不能重排序 %s ");
define("_MD_AM_FAILWRITE","不能写入主菜单");
define("_MD_AM_ALEXISTS","模块 %s 已存在");
define("_MD_AM_ERRORSC", "错误:");
define("_MD_AM_OKINS","模块 %s 已安装");
define("_MD_AM_OKACT","模块 %s 已激活");
define("_MD_AM_OKDEACT","模块 %s 已撤销");
define("_MD_AM_OKUPD","模块 %s 已更新");
define("_MD_AM_OKUNINS","模块 %s 已卸载");
define("_MD_AM_OKORDER","模块 %s 已更改");

define('_MD_AM_RUSUREINS', '点击下面的按钮安装此模块');
define('_MD_AM_RUSUREUPD', '点击下面的按钮更新此模块');
define('_MD_AM_RUSUREUNINS', '确认要卸载此模块吗?');
define('_MD_AM_LISTUPBLKS', '以下区块将被更新<br />所选区块的内容(模板和选项)将被覆盖<br />');
define('_MD_AM_NEWBLKS', '新区块');
define('_MD_AM_DEPREBLKS', '降级的区块');

define('_MD_AM_MODULESADMIN_SUPPORT', '模块的支持站点');
define('_MD_AM_MODULESADMIN_STATUS', '状态');
define('_MD_AM_MODULESADMIN_MODULENAME', '模块名称');
define('_MD_AM_MODULESADMIN_MODULETITLE', '模块标题');
######################## Added in 1.2 ###################################
define('_MD_AM_MOD_DATA_UPDATED',' 模块数据已更新');
define('_MD_AM_MOD_REBUILD_BLOCKS','正在重建区块...');
define('_MD_AM_INSTALLED', '已安装的模块');
define('_MD_AM_NONINSTALL', '已卸载的模块');
define('_MD_AM_IMAGESFOLDER_UPDATE_TITLE', '图像管理文件夹需可写入');
define('_MD_AM_IMAGESFOLDER_UPDATE_TEXT', '新版本的图像管理器已更改图像的储存目录。更新过程会将图像移至新目录，新目录需要可写入的权限。 <strong>刷新此页面</strong> ，然后再点击下面的更新按钮<br /><strong>图片管理文件夹</strong>: %s');
define('_MD_AM_PLUGINSFOLDER_UPDATE_TITLE', '插件/预读取目录需可写入');
define('_MD_AM_PLUGINSFOLDER_UPDATE_TEXT', '新版本的ImpressCMS已更改至预读取目录。此更改会将预读取目录移至新目录，插件与预读取目录的文件夹都需要可写入的权限。<strong>刷新此页面</strong>，然后点击下面的更新按钮<br /><strong>插件目录</strong>: %s<br /><strong>预读取目录</strong>: %s');

// Added and Changed in 1.3
define("_MD_AM_UPDATE_FAIL","不能更新 %s ");
define('_MD_AM_FUNCT_EXEC',' %s 功能已执行');
define('_MD_AM_FAIL_EXEC',' %s 执行失败');
define('_MD_AM_INSTALLING','安装中 ');
define('_MD_AM_SQL_NOT_FOUND', '在 %s 未发现SQL文件');
define('_MD_AM_SQL_FOUND', " %s 已找到SQL文件. <br  /> 正在创建数据表...");
define('_MD_SQL_NOT_VALID', ' 不是有效的SQL!');
define('_MD_AM_TABLE_CREATED', 	'数据表 %s 已创建');
define('_MD_AM_DATA_INSERT_SUCCESS', '数据已插入数据表 %s ');
define('_MD_AM_RESERVED_TABLE', '%s 是保留数据表!');
define('_MD_AM_DATA_INSERT_FAIL', '不能Could not insert %s to database.');
define('_MD_AM_CREATE_FAIL', 'ERROR: Could not create %s');

define('_MD_AM_MOD_DATA_INSERT_SUCCESS', 'Module data inserted successfully. Module ID: %s');

define('_MD_AM_BLOCK_UPDATED', 'Block %s updated. Block ID: %s.');
define('_MD_AM_BLOCK_CREATED', 'Block %s created. Block ID: %s.');

define('_MD_AM_BLOCKS_ADDING', 'Adding blocks...');
define('_MD_AM_BLOCKS_ADD_FAIL', 'ERROR: Could not add block %1$s to the database! Database error: %2$s');
define('_MD_AM_BLOCK_ADDED', 'Block %1$s added. Block ID: %2$s');
define('_MD_AM_BLOCKS_DELETE', 'Deleting block...');
define('_MD_AM_BLOCK_DELETE_FAIL', 'ERROR: Could not delete block %1$s. Block ID: %2$s');
define('_MD_AM_BLOCK_DELETED', 'Block %1$s deleted. Block ID: %2$s');
define('_MD_AM_BLOCK_TMPLT_DELETE_FAILED', 'ERROR: Could not delete block template %1$s  from the database. Template ID: %2$s');
define('_MD_AM_BLOCK_TMPLT_DELETED', 'Block template %1$s  deleted from the database. Template ID: %2$s');
define('_MD_AM_BLOCK_ACCESS_FAIL', 'ERROR: Could not add block access right. Block ID: %1$s  Group ID: %2$s');
define('_MD_AM_BLOCK_ACCESS_ADDED', 'Added block access right. Block ID: %1$s, Group ID: %2$s');

define('_MD_AM_CONFIG_ADDING', 'Adding module config data...');
define('_MD_AM_CONFIGOPTION_ADDED', 'Config option added. Name: %1$s Value: %2$s');
define('_MD_AM_CONFIG_ADDED', 'Config %s  added to the database.');
define('_MD_AM_CONFIG_ADD_FAIL', 'ERROR: Could not insert config %s to the database.');

define('_MD_AM_PERMS_ADDING', 'Setting group rights...');
define('_MD_AM_ADMIN_PERM_ADD_FAIL', 'ERROR: Could not add admin access right for Group ID %s');
define('_MD_AM_ADMIN_PERM_ADDED', 'Added admin access right for Group ID %s');
define('_MD_AM_USER_PERM_ADD_FAIL', 'ERROR: Could not add user access right for Group ID: %s');
define('_MD_AM_USER_PERM_ADDED', 'Added user access right for Group ID: %s');

define('_MD_AM_AUTOTASK_FAIL', 'ERROR: Could not insert autotask to db. Name: %s');
define('_MD_AM_AUTOTASK_ADDED', 'Added task to autotasks list. Task Name: %s');
define('_MD_AM_AUTOTASK_UPDATE', 'Updating autotasks...');
define('_MD_AM_AUTOTASKS_DELETE', 'Deleting Autotasks...');

define('_MD_AM_SYMLINKS_DELETE', 'Deleting links from Symlink Manager...');
define('_MD_AM_SYMLINK_DELETE_FAIL', 'ERROR: Could not delete link %1$s from the database. Link ID: %2$s');
define('_MD_AM_SYMLINK_DELETED', 'Link %1$s deleted from the database. Link ID: %2$s');

define('_MD_AM_DELETE_FAIL', 'ERROR: Could not delete %s');

define('_MD_AM_MOD_UP_TEM','Updating templates...');
define('_MD_AM_TEMPLATE_INSERT_FAIL','ERROR: Could not insert template %s to the database.');
define('_MD_AM_TEMPLATE_UPDATE_FAIL','ERROR: Could not update template %s.');
define('_MD_AM_TEMPLATE_INSERTED','Template %s added to the database. (ID: %s)');
define('_MD_AM_TEMPLATE_COMPILE_FAIL','ERROR: Failed compiling template %s.');
define('_MD_AM_TEMPLATE_COMPILED','Template %s compiled.');
define('_MD_AM_TEMPLATE_RECOMPILED','Template %s recompiled.');
define('_MD_AM_TEMPLATE_RECOMPILE_FAIL','ERROR: Could not recompile template %s.');

define('_MD_AM_TEMPLATES_ADDING', 'Adding templates...');
define('_MD_AM_TEMPLATES_DELETE', 'Deleting templates...');
define('_MD_AM_TEMPLATE_DELETE_FAIL', 'ERROR: Could not delete template %1$s from the database. Template ID: %2$s');
define('_MD_AM_TEMPLATE_DELETED', 'Template %1$s  deleted from the database. Template ID: %2$s');
define('_MD_AM_TEMPLATE_UPDATED', 'Template %s updated.');

define('_MD_AM_MOD_TABLES_DELETE', 'Deleting module tables...');
define('_MD_AM_MOD_TABLE_DELETE_FAIL', 'ERROR: Could not drop table %s');
define('_MD_AM_MOD_TABLE_DELETED', 'Table %s dropped.');
define('_MD_AM_MOD_TABLE_DELETE_NOTALLOWED', 'ERROR: Not allowed to drop table %s!');

define('_MD_AM_COMMENTS_DELETE', 'Deleting comments...');
define('_MD_AM_COMMENT_DELETE_FAIL', 'ERROR: Could not delete comments');
define('_MD_AM_COMMENT_DELETED', 'Comments deleted');

define('_MD_AM_NOTIFICATIONS_DELETE', 'Deleting notifications...');
define('_MD_AM_NOTIFICATION_DELETE_FAIL', 'ERROR: Could not delete notifications');
define('_MD_AM_NOTIFICATION_DELETED', 'Notifications deleted');

define('_MD_AM_GROUPPERM_DELETE', 'Deleting group permissions...');
define('_MD_AM_GROUPPERM_DELETE_FAIL', 'ERROR: Could not delete group permissions');
define('_MD_AM_GROUPPERM_DELETED', 'Group permissions deleted');

define('_MD_AM_CONFIGOPTIONS_DELETE', 'Deleting module config options...');
define('_MD_AM_CONFIGOPTION_DELETE_FAIL', 'ERROR: Could not delete config data from the database. Config ID: %s');
define('_MD_AM_CONFIGOPTION_DELETED', 'Config data deleted from the database. Config ID: %s');


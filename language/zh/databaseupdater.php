<?php

/**
 * $Id$
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define("_DATABASEUPDATER_IMPORT", "导入");
define("_DATABASEUPDATER_CURRENTVER", "当前版本: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "数据库版本%s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "数据已被添加到表 %s 中");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "出错。当添加数据到表 %s 时");
define("_DATABASEUPDATER_MSG_CHGFIELD", "更改表区 %s 在表 %s 中");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "出错。当更改表区 %s 在表 %s 中时");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "数据表 %s 已创建");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "出错。在创建表 %s 时");
define("_DATABASEUPDATER_MSG_NEWFIELD", "成功添加表区 %s");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "出错。在添加表区 %s 时");
define("_DATABASEUPDATER_NEEDUPDATE", "数据库已过期，请更新。<br /><b>备注: ImpressCMS 强烈建议你在更新前备份好数据表.</b>");
define("_DATABASEUPDATER_NOUPDATE", "数据库未过期，无需更新.");
define("_DATABASEUPDATER_UPDATE_DB", "正在更新数据库");
define("_DATABASEUPDATER_UPDATE_ERR", "Errors updating to version %s");
define("_DATABASEUPDATER_UPDATE_NOW", "现在更新!");
define("_DATABASEUPDATER_UPDATE_OK", "成功更新到版本 %s");
define("_DATABASEUPDATER_UPDATE_TO", "正在更新至版 %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "正在更新数据库...");

define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "数据表 %s 的记录成功更新");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "更新数据表 %s 记录时发生了错误");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "数据表 %s 指定的记录已被移除。");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "出错。在移除数据表 %s 指定的记录时。");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "不能更新模块的数据库版本");
define("_DATABASEUPDATER_LATESTVER", "最新的数据库版本: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "不能插入配置 %s");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "成功地插入 %s 配置");

/* added in 1.3 */
define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modules&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', '出错！当从数据表 %2$s 移除特定表区 %1$s 时。');
define("_DATABASEUPDATER_MSG_DROPFIELD", '已成功从数据表 %2$s 移除表区 %1$s ');

// Added in 1.2.7/1.3.1
define("_DATABASEUPDATER_MSG_DROP_TABLE", "Successfully dropped database table %s");
define("_DATABASEUPDATER_MSG_DROP_TABLE_ERR", "Error dropping database table %s");

// Added in 1.3.2
define("_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL", "Query successful: %s");
define("_DATABASEUPDATER_MSG_QUERY_FAILED", "Query failed: %s");

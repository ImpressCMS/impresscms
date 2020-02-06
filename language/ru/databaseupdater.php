<?php

/**
 * $Id$
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define("_DATABASEUPDATER_IMPORT", "Импорт");
define("_DATABASEUPDATER_CURRENTVER", "Текущая версия: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Версия базы данных %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "Данные добавлены в таблицу %s");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Ошибка при добавлении данных в таблицу %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Изменение поля %s в таблице %s");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Ошибка при изменении поля %s в таблице %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Таблица %s создана");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Ошибка создания таблицы %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Выполнено добавление поля %s");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Ошибка при добавлении поля %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Ваша база данных устарела. Пожалуйста, проведите обновление таблиц Вашей базы данных!<br /><b>Замечание : ImpressCMS настоятельно рекомендует Вам выполнить сохранение всех таблиц базы данных до запуска скрипта обновления.</b><br />");
define("_DATABASEUPDATER_NOUPDATE", "Ваша база данных не требует обновления.");
define("_DATABASEUPDATER_UPDATE_DB", "Обновление базы данных");
define("_DATABASEUPDATER_UPDATE_ERR", "Ошибка при выполнении обновления на версию %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Выполнить обновление сейчас!");
define("_DATABASEUPDATER_UPDATE_OK", "Выполнено обновление на версию %s");
define("_DATABASEUPDATER_UPDATE_TO", "Обновление на версию %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Выполняется обновление базы данных...");

define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Выполнено обновление записей в таблице %s");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "Ошибка при выполнении обновления записей в таблице %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "Специфические записи в таблице %s удалены");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "Ошибка при выполнении удаления специфических записей в таблице %s");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "Unable to update module dbversion");
define("_DATABASEUPDATER_LATESTVER", "Latest database version : <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "Unable to insert config %s");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "Successfully inserted %s config");

/* added in 1.3 */
define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modules&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', 'An error occured while deleting specified fields %1$s from table %2$s');
define("_DATABASEUPDATER_MSG_DROPFIELD", 'Выполнено удаление поля %s');

// Added in 1.2.7/1.3.1
define("_DATABASEUPDATER_MSG_DROP_TABLE", "Successfully dropped database table %s");
define("_DATABASEUPDATER_MSG_DROP_TABLE_ERR", "Error dropping database table %s");

// Added in 1.3.2
define("_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL", "Query successful: %s");
define("_DATABASEUPDATER_MSG_QUERY_FAILED", "Query failed: %s");

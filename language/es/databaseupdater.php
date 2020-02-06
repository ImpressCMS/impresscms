<?php

/**
 * $Id$
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define("_DATABASEUPDATER_IMPORT", "Importar");
define("_DATABASEUPDATER_CURRENTVER", "Versión actual: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Versión de base de datos %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "Datos añadidos en la tabla %s");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Error al añadir datos en la tabla %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Cambiando campo %s en tabla %s");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Error al cambiar el campo %s en la tabla %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Tabla %s creada");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Error al crear la tabla %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Campo añadido correctamente %s");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Error al añadir el campo %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Su base de datos está desactualizada. ¡Actualiza las tablas de tu base de datos!<br /><b>Nota: ImpressCMS le recomienda hacer una copia de seguridad de todas las tablas de su base de datos antes de ejecutar este script de actualización.</b>");
define("_DATABASEUPDATER_NOUPDATE", "Su base de datos está actualizada. No son necesarias actualizaciones.");
define("_DATABASEUPDATER_UPDATE_DB", "Actualizando base de datos");
define("_DATABASEUPDATER_UPDATE_ERR", "Errores actualizando a la versión %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Actualiza ahora!");
define("_DATABASEUPDATER_UPDATE_OK", "Actualizado correctamente a la versión %s");
define("_DATABASEUPDATER_UPDATE_TO", "Actualizando a la versión %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Actualizando base de datos...");

define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Los registros de la tabla %s se actualizaron correctamente");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "Se ha producido un error al actualizar los registros en la tabla %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "Los registros especificados de la tabla %s se eliminaron correctamente");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "Se ha producido un error al eliminar los registros especificados en la tabla %s");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "No se puede actualizar el módulo dbversion");
define("_DATABASEUPDATER_LATESTVER", "Última versión de la base de datos: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "No se puede insertar la configuración %s");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "Se ha insertado con éxito la configuración %s");

/* added in 1.3 */
define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modules&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', 'Se ha producido un error al eliminar los campos especificados %1$s de la tabla %2$s');
define("_DATABASEUPDATER_MSG_DROPFIELD", 'El campo %1$s se ha eliminado con éxito de la tabla %2$s');

// Added in 1.2.7/1.3.1
define("_DATABASEUPDATER_MSG_DROP_TABLE", "Se ha eliminado correctamente la tabla de base de datos %s");
define("_DATABASEUPDATER_MSG_DROP_TABLE_ERR", "Error al eliminar la tabla de base de datos %s");

// Added in 1.3.2
define("_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL", "Consulta exitosa: %s");
define("_DATABASEUPDATER_MSG_QUERY_FAILED", "Consulta fallida: %s");

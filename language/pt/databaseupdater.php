<?php

/**
 * $Id$
 * Author: The SmartFactory <www.smartfactory.ca>
 * Licence: GNU
 */
define("_DATABASEUPDATER_IMPORT", "Importar");
define("_DATABASEUPDATER_CURRENTVER", "Versão Atual: <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_DBVER", "Versão do Banco de Dados %s");
define("_DATABASEUPDATER_MSG_ADD_DATA", "Dados acrescentados na tabela %s");
define("_DATABASEUPDATER_MSG_ADD_DATA_ERR", "Erro enquanto estava adicionando dados na tabela %s");
define("_DATABASEUPDATER_MSG_CHGFIELD", "Mudar o campo %s na tabela %s");
define("_DATABASEUPDATER_MSG_CHGFIELD_ERR", "Erro enquanto alterava o campo %s na tabela %s");
define("_DATABASEUPDATER_MSG_CREATE_TABLE", "Tabela %s foi criada");
define("_DATABASEUPDATER_MSG_CREATE_TABLE_ERR", "Erro durante a criação da tabela %s");
define("_DATABASEUPDATER_MSG_NEWFIELD", "Foi adicionado com sucesso o campo %s ");
define("_DATABASEUPDATER_MSG_NEWFIELD_ERR", "Erro durante a inclusão do campo %s");
define("_DATABASEUPDATER_NEEDUPDATE", "Sua base de dados está desatualizada. Atualize seu banco de dados e tabelas!<br /><b>Nota : O ImpressCMS recomenda fortemente que você faça um backup de todas as suas tabelas de dados antes de executar este script de atualização.</b><br />");
define("_DATABASEUPDATER_NOUPDATE", "O seu Banco de Dados está atualizado. Não será necessário atualizações.");
define("_DATABASEUPDATER_UPDATE_DB", "Atualizando o Banco de Dados");
define("_DATABASEUPDATER_UPDATE_ERR", "Erros durante a atualização para a versão %s");
define("_DATABASEUPDATER_UPDATE_NOW", "Atualizar Agora!");
define("_DATABASEUPDATER_UPDATE_OK", "A versão foi atualizada com sucesso para %s");
define("_DATABASEUPDATER_UPDATE_TO", "Atualizando a versão %s");
define("_DATABASEUPDATER_UPDATE_UPDATING_DATABASE", "Atualização de dados...");

define("_DATABASEUPDATER_MSG_UPDATE_TABLE", "Registros da tabela %s foram atualizadas com êxito");
define("_DATABASEUPDATER_MSG_UPDATE_TABLE_ERR", "Ocorreu um erro ao atualizar registros na tabela %s");
define("_DATABASEUPDATER_MSG_DELETE_TABLE", "Específicos registros na tabela %s foram excluídos com êxito");
define("_DATABASEUPDATER_MSG_DELETE_TABLE_ERR", "Ocorreu um erro ao excluir registros na tabela %s");
############# added since 1.2 #############
define("_DATABASEUPDATER_MSG_DB_VERSION_ERR", "Unable to update module dbversion");
define("_DATABASEUPDATER_LATESTVER", "Latest database version : <span class='currentVer'>%s</span>");
define("_DATABASEUPDATER_MSG_CONFIG_ERR", "Unable to insert config %s");
define("_DATABASEUPDATER_MSG_CONFIG_SCC", "Successfully inserted %s config");

/* added in 1.3 */
define( '_DATABASEUPDATER_MSG_FROM_112', "<code><h3>You have updated your site from ImpressCMS 1.1.x to ImpressCMS 1.2 so you <strong>must install the new Content module</strong> to update the core content manager. You will be redirected to the installation process in 20 seconds. If this does not happen click <a href='" . ICMS_URL . "/modules/system/admin.php?fct=modules&op=install&module=content&from_112=1'>here</a>.</h3></code>" );
define('_DATABASEUPDATER_MSG_DROPFIELD_ERR', 'An error occured while deleting specified fields %1$s from table %2$s');
define("_DATABASEUPDATER_MSG_DROPFIELD", 'Realizado com sucesso a exclusão do campo %s');

// Added in 1.2.7/1.3.1
define("_DATABASEUPDATER_MSG_DROP_TABLE", "Successfully dropped database table %s");
define("_DATABASEUPDATER_MSG_DROP_TABLE_ERR", "Error dropping database table %s");

// Added in 1.3.2
define("_DATABASEUPDATER_MSG_QUERY_SUCCESSFUL", "Query successful: %s");
define("_DATABASEUPDATER_MSG_QUERY_FAILED", "Query failed: %s");

<?php


namespace ImpressCMS\Core\ModuleInstallationHelpers;

use icms_module_Object;
use Psr\Log\LoggerInterface;

class TablesModuleInstallationHelper implements ModuleInstallationHelperInterface
{
	/**
	 * Reserved tables list
	 *
	 * @var string[]
	 */
	protected $reservedTables = [
		'avatar',
		'avatar_users_link',
		'block_module_link',
		'xoopscomments',
		'config',
		'configcategory',
		'configoption',
		'image',
		'imagebody',
		'imagecategory',
		'imgset',
		'imgset_tplset_link',
		'imgsetimg',
		'groups',
		'groups_users_link',
		'group_permission',
		'online',
		'priv_msgs',
		'ranks',
		'session',
		'smiles',
		'users',
		'newblocks',
		'modules',
		'tplfile',
		'tplset',
		'tplsource',
		'xoopsnotifications'
	];

	/**
	 * @inheritDoc
	 */
	public function executeModuleInstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		$sqlfile = &$module->getInfo('sqlfile');
		if ($sqlfile === false || !is_array($sqlfile)) {
			return true;
		}
		if (substr(env('DB_TYPE'), 0, 4) == 'pdo.') {
			$driver = substr(env('DB_TYPE'), 4);
		}
		$sql_file_path = ICMS_MODULES_PATH . '/' . $module->getVar('dirname') . '/' . $sqlfile[$driver];
		if (!file_exists($sql_file_path)) {
			$logger->error(
				sprintf(_MD_AM_SQL_NOT_FOUND, $sql_file_path)
			);
			return false;
		}
		$logger->info(
			sprintf(_MD_AM_SQL_FOUND, $sql_file_path)
		);
		$sql_query = trim(
			file_get_contents($sql_file_path)
		);
		\icms_db_legacy_mysql_Utility::splitSqlFile($pieces, $sql_query);
		$created_tables = array();
		foreach ($pieces as $piece) {
			// [0] contains the prefixed query
			// [4] contains unprefixed table name
			$prefixed_query = \icms_db_legacy_mysql_Utility::prefixQuery($piece, $module->handler->db->prefix());
			if (!$prefixed_query) {
				$logger->error(
					sprintf('"%s", %s', addslashes($piece), _MD_SQL_NOT_VALID)
				);
				return false;
			}
			if (in_array($prefixed_query[4], $this->reservedTables, true)) {
				$logger->emergency(
					sprintf(_MD_AM_RESERVED_TABLE, $prefixed_query[4])
				);
				return false;
			}

			if (!$module->handler->db->query($prefixed_query[0])) {
				$logger->error($module->handler->db->error());
				return false;
			}

			if (!in_array($prefixed_query[4], $created_tables, true)) {
				$logger->info(
					sprintf('  ' . _MD_AM_TABLE_CREATED,
						$module->handler->db->prefix($prefixed_query[4])
					)
				);
				$created_tables[] = $prefixed_query[4];
			} else {
				$logger->info(
					sprintf('  ' . _MD_AM_DATA_INSERT_SUCCESS,
						$module->handler->db->prefix($prefixed_query[4])
					)
				);
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleInstallStepPriority(): int
	{
		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public function executeModuleUninstallStep(icms_module_Object $module, LoggerInterface $logger): bool
	{
		// delete tables used by this module
		$modtables = $module->getInfo('tables');
		$is_IPF = $module->getInfo('object_items');
		if ($modtables === false || !is_array($modtables)) {
			return true;
		}

		$logger->info(
			_MD_AM_MOD_TABLES_DELETE
		);

		foreach ($modtables as $table) {
			if ($is_IPF) {
				$table = str_replace(env('DB_PREFIX') . '_', '', $table);
			}
			$prefix_table = $module->handler->db->prefix($table);

			if (in_array($table, $this->reservedTables, true)) {
				$logger->error(
					sprintf('  ' . _MD_AM_MOD_TABLE_DELETE_NOTALLOWED, $prefix_table)
				);
				continue;
			}

			$sql = 'DROP TABLE ' . $prefix_table;
			if ($module->handler->db->query($sql)) {
				$logger->info(
					sprintf('  ' . _MD_AM_MOD_TABLE_DELETED, $prefix_table)
				);
			} else {
				$logger->error(
					sprintf('  ' . _MD_AM_MOD_TABLE_DELETE_FAIL, $prefix_table)
				);
			}
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getModuleUninstallStepPriority(): int
	{
		return 0;
	}
}
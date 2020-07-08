<?php


namespace ImpressCMS\Core\SetupSteps\Module\Install;


use ImpressCMS\Core\Models\Module;
use ImpressCMS\Core\SetupSteps\OutputDecorator;
use ImpressCMS\Core\SetupSteps\SetupStepInterface;

class TablesSetupStep implements SetupStepInterface
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
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
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
			$output->error(_MD_AM_SQL_NOT_FOUND, $sql_file_path);
			return false;
		}
		$output->info(_MD_AM_SQL_FOUND, $sql_file_path);
		$sql_query = trim(
			file_get_contents($sql_file_path)
		);
		\ImpressCMS\Core\Database\Legacy\Mysql\DatabaseUtility::splitSqlFile($pieces, $sql_query);
		$created_tables = array();
		$output->incrIndent();
		foreach ($pieces as $piece) {
			// [0] contains the prefixed query
			// [4] contains unprefixed table name
			$prefixed_query = \ImpressCMS\Core\Database\Legacy\Mysql\DatabaseUtility::prefixQuery($piece, $module->handler->db->prefix());
			if (!$prefixed_query) {
				$output->error('"%s", %s', addslashes($piece), _MD_SQL_NOT_VALID);
				return false;
			}
			if (in_array($prefixed_query[4], $this->reservedTables, true)) {
				$output->fatal(_MD_AM_RESERVED_TABLE, $prefixed_query[4]);
				return false;
			}

			if (!$module->handler->db->query($prefixed_query[0])) {
				$output->error($module->handler->db->error());
				return false;
			}

			if (!in_array($prefixed_query[4], $created_tables, true)) {
				$output->success(
					_MD_AM_TABLE_CREATED,
					$module->handler->db->prefix($prefixed_query[4])
				);
				$created_tables[] = $prefixed_query[4];
			} else {
				$output->success(
					_MD_AM_DATA_INSERT_SUCCESS,
					$module->handler->db->prefix($prefixed_query[4])
				);
			}
		}
		$output->resetIndent();

		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getPriority(): int
	{
		return 0;
	}
}
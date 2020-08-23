<?php


namespace ImpressCMS\Core\Extensions\SetupSteps\Module\Uninstall;

use ImpressCMS\Core\Extensions\SetupSteps\Module\Install\TablesSetupStep as InstallTablesSetupStep;
use ImpressCMS\Core\Extensions\SetupSteps\OutputDecorator;
use ImpressCMS\Core\Models\Module;

class TablesSetupStep extends InstallTablesSetupStep
{

	/**
	 * @inheritDoc
	 */
	public function execute(Module $module, OutputDecorator $output, ...$params): bool
	{
		// delete tables used by this module
		$modtables = $module->getInfo('tables');
		$is_IPF = $module->getInfo('object_items');
		if ($modtables === false || !is_array($modtables)) {
			return true;
		}

		$output->info(
			_MD_AM_MOD_TABLES_DELETE
		);
		$output->incrIndent();

		foreach ($modtables as $table) {
			if ($is_IPF) {
				$table = str_replace(env('DB_PREFIX') . '_', '', $table);
			}
			$prefix_table = $module->handler->db->prefix($table);

			if (in_array($table, $this->reservedTables, true)) {
				$output->error(_MD_AM_MOD_TABLE_DELETE_NOTALLOWED, $prefix_table);
				continue;
			}

			$sql = 'DROP TABLE ' . $prefix_table;
			if ($module->handler->db->query($sql)) {
				$output->success(_MD_AM_MOD_TABLE_DELETED, $prefix_table);
			} else {
				$output->error(_MD_AM_MOD_TABLE_DELETE_FAIL, $prefix_table);
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
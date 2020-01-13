<?php

use Phoenix\Migration\AbstractMigration;

class SetupBlockLinks extends AbstractMigration
{
    protected function up(): void
    {
		/**
		 * @var \icms_db_Connection $dbm
		 */
		$dbm = \icms::getInstance()->get('db-connection-1');

		if (((int)($dbm->fetchCol('SELECT COUNT(*) FROM `' . $dbm->prefix('block_module_link') . '`;')[0]) > 0)) {
			// skipping this migration if at least one block_module_link is found
			return;
		}

		// data for table 'block_module_link'
		$sql = 'SELECT bid, side, template FROM ' . $dbm->prefix('newblocks');
		$result = $dbm->query($sql);

		$links = [];
		while ($newBlocksRow = $result->fetch(PDO::FETCH_ASSOC)) {
			$side = (int)$newBlocksRow['side'];
			if ($side === 1 || $side === 2 || $side === 7) {
				$links[] = [
					'block_id' => $newBlocksRow['bid'],
					'module_id' => 0,
					'page_id' => 0
				];
			} elseif (in_array($newBlocksRow['template'], ['system_admin_block_warnings.html', 'system_admin_block_cp.html', 'system_admin_block_modules.html', 'system_block_newusers.html', 'system_block_online.html', 'system_block_waiting.html', 'system_block_topusers.html'])) {
				$links[] = [
					'block_id' => $newBlocksRow['bid'],
					'module_id' => 1,
					'page_id' => 2
				];
			} else {
				$links[] = [
					'block_id' => $newBlocksRow['bid'],
					'module_id' => 0,
					'page_id' => 1
				];
			}
		}

		$this->insert($dbm->prefix('block_module_link'), $links);
    }

    protected function down(): void
    {

    }
}

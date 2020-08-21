<?php

use Phoenix\Migration\AbstractMigration;

class RemoveOldMessangersFromUserIfExists extends AbstractMigration
{
    protected function up(): void
    {
		if ($this->tableColumnExists($this->prefix('users'), 'user_msnm')) {
			$this->getTable($this->prefix('users'))->removeColumn('user_msnm');
		}
		if ($this->tableColumnExists($this->prefix('users'), 'user_yim')) {
			$this->getTable($this->prefix('users'))->removeColumn('user_yim');
		}
		if ($this->tableColumnExists($this->prefix('users'), 'user_aim')) {
			$this->getTable($this->prefix('users'))->removeColumn('user_aim');
		}
		if ($this->tableColumnExists($this->prefix('users'), 'user_icq')) {
			$this->getTable($this->prefix('users'))->removeColumn('user_icq');
		}
    }

    protected function down(): void
    {
    	$this->execute(
    		sprintf('ALTER TABLE %s ADD COLUMN `user_msnm` varchar(100) NOT NULL default \'\'', $this->prefix('users'))
		);
		$this->execute(
			sprintf('ALTER TABLE %s ADD COLUMN `user_yim` varchar(25) NOT NULL default \'\'', $this->prefix('users'))
		);
		$this->execute(
			sprintf('ALTER TABLE %s ADD COLUMN `user_aim` varchar(18) NOT NULL default \'\'', $this->prefix('users'))
		);
		$this->execute(
			sprintf('ALTER TABLE %s ADD COLUMN `user_icq` varchar(15) NOT NULL default \'\'', $this->prefix('users'))
		);
    }

	/**
	 * Prefix table
	 *
	 * @param string $table Table to prefix
	 *
	 * @return string
	 */
	private function prefix(string $table): string
	{
		return \icms::getInstance()->get('db-connection-1')->prefix($table);
	}
}

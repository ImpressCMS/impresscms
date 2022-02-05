<?php

use ImpressCMS\Core\Database\AbstractDatabaseMigration;

class RemoveOldMessangersFromUserIfExists extends AbstractDatabaseMigration
{
    protected function up(): void
    {
		if ($this->tableColumnExists($this->prefix('users'), 'user_msnm')) {
			$this->table($this->prefix('users'))->dropColumn('user_msnm')->save();
		}
		if ($this->tableColumnExists($this->prefix('users'), 'user_yim')) {
			$this->table($this->prefix('users'))->dropColumn('user_yim')->save();
		}
		if ($this->tableColumnExists($this->prefix('users'), 'user_aim')) {
			$this->table($this->prefix('users'))->dropColumn('user_aim')->save();
		}
		if ($this->tableColumnExists($this->prefix('users'), 'user_icq')) {
			$this->table($this->prefix('users'))->dropColumn('user_icq')->save();
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
}

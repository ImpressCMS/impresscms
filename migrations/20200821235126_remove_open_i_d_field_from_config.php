<?php

use Phoenix\Migration\AbstractMigration;

class RemoveOpenIDFieldFromConfig extends AbstractMigration
{
    protected function up(): void
    {
		if ($this->tableColumnExists($this->prefix('users'), 'openid')) {
			$this->table($this->prefix('users'))->dropColumn('openid')->save();
		}
		if ($this->tableColumnExists($this->prefix('users'), 'user_viewoid')) {
			$this->table($this->prefix('users'))->dropColumn('user_viewoid')->save();
		}
		$this->delete($this->prefix('config'), [
			'conf_name' => 'auth_openid'
		]);
    }

    protected function down(): void
    {
		if (!$this->tableColumnExists($this->prefix('users'), 'openid')) {
			$this->execute(
				sprintf('ALTER TABLE %s ADD COLUMN `openid` varchar(255) NOT NULL default \'\'', $this->prefix('users'))
			);
		}
		if (!$this->tableColumnExists($this->prefix('users'), 'user_viewoid')) {
			$this->execute(
				sprintf('ALTER TABLE %s ADD COLUMN `user_viewoid` tinyint(1) unsigned NOT NULL default \'0\'', $this->prefix('users'))
			);
		}
		$this->insert(
			$this->prefix('config'),
		[
			'conf_modid' => 0,
			'conf_catid' => 7,
			'conf_name' => 'auth_openid',
			'conf_title' => '_MD_AM_AUTHOPENID',
			'conf_value' => '0',
			'conf_desc' => '_MD_AM_AUTHOPENIDDSC',
			'conf_formtype' => 'yesno',
			'conf_valuetype' => 'int',
			'conf_order' => 1
		]);
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

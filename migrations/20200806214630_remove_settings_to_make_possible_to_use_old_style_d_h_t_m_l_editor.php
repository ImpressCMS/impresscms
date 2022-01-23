<?php

use ImpressCMS\Core\Database\AbstractDatabaseMigration;
use ImpressCMS\Core\Extensions\Editors\EditorsRegistry;

class RemoveSettingsToMakePossibleToUseOldStyleDHTMLEditor extends AbstractDatabaseMigration
{
    protected function up(): void
    {
		$this->delete(
			$this->prefix('config'),
			[
				'conf_name' => 'sanitizer_plugins',
				'conf_catid' => 12
			]
		);
		$this->delete(
			$this->prefix('config'),
			[
				'conf_name' => 'editor_enabled_list',
				'conf_catid' => 1
			]
		);

		if (!$this->getContainer()->has('\\' . EditorsRegistry::class)) {
			icms::getInstance()->boot(false);
		}

		/**
		 * @var EditorsRegistry $editorRegistry
		 */
		$editorRegistry = $this->getContainer()->get('\\' . EditorsRegistry::class);

		$this->getContainer()->get('cache')->clear();

		$this->update(
			$this->prefix('config'),
			[
				'conf_value' => key(
					$editorRegistry->getList('content')
				)
			],
			[
				'conf_value' => 'dhtmltextarea',
				'conf_name' => 'editor_default',
				'conf_catid' => 1,
			]
		);
    }

    protected function down(): void
    {
		$this->insert(
			$this->prefix('config'),
			[
				'conf_modid' => 0,
				'conf_name' => 'sanitizer_plugins',
				'conf_catid' => 12,
				'conf_title' => '_MD_AM_SELECTSPLUGINS',
				'conf_value' => 'a:2:{i:0;s:18:"syntaxhighlightphp";i:1;s:13:"hiddencontent";}',
				'conf_desc' => '_MD_AM_SELECTSPLUGINS_DESC',
				'conf_formtype' => 'select_plugin',
				'conf_valuetype' => 'array',
				'conf_order' => 0
			]
		);
		$this->insert(
			$this->prefix('config'),
			[
				'conf_modid' => 0,
				'conf_name' => 'editor_enabled_list',
				'conf_catid' => 1,
				'conf_title' => '_MD_AM_EDITOR_ENABLED_LIST',
				'conf_value' => 'a:3:{i:0;s:8:"CKeditor";i:1;s:13:"dhtmltextarea";i:2;s:7:"tinymce";}',
				'conf_desc' => '_MD_AM_EDITOR_ENABLED_LIST_DESC',
				'conf_formtype' => 'editor_multi',
				'conf_valuetype' => 'array',
				'conf_order' => 14
			]
		);
		// We can't rollback default editor, so we doing here nothing about that
    }
}

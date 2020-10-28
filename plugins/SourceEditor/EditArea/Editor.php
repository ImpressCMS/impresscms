<?php

namespace ImpressCMS\Plugins\SourceEditors\EditArea;

use icms\plugins\EditorInterface;

/**
 * Defines EditArea editor
 *
 * @package ImpressCMS\Plugins\SourceEditors\EditArea
 */
class Editor implements EditorInterface
{

	/**
	 * @inheritDoc
	 */
	public function getTitle(): string
	{
		global $icmsConfig;

		if (!defined('_ICMS_SOURCEEDITOR_EDITAREA')) {
			$langPath = __DIR__ . '/language/';
			$lang = $icmsConfig['language'];

			if (!file_exists($langPath . $lang . '.php')) {
				$lang = 'english';
			}

			require_once $langPath . $lang . '.php';
		}

		return _ICMS_SOURCEEDITOR_EDITAREA;
	}

	/**
	 * @inheritDoc
	 */
	public function getVersion(): string
	{
		return '0.8.0';
	}

	/**
	 * @inheritDoc
	 */
	public function getLicense(): string
	{
		return 'LGPLv2.1';
	}

	/**
	 * @inheritDoc
	 */
	public function create(array $configs, $checkCompatible = false): \icms_form_elements_Textarea
	{
		require_once __DIR__ . '/editarea.php';
		return new \IcmsSourceEditorEditArea($configs, $checkCompatible);
	}

	/**
	 * @inheritDoc
	 */
	public function getOrder(): ?int
	{
		return 1;
	}

	/**
	 * @inheritDoc
	 */
	public function supportsHTML(): bool
	{
		return false;
	}
}

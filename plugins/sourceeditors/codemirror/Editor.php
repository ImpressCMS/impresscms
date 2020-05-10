<?php


namespace ImpressCMS\Plugins\SourceEditors\CodeMirror;

use icms\plugins\EditorInterface;

/**
 * Defines CodeMirror editor
 *
 * @package ImpressCMS\Plugins\SourceEditors\CodeMirror
 */
class Editor implements EditorInterface
{

	/**
	 * @inheritDoc
	 */
	public function getTitle(): string
	{
		global $icmsConfig;

		if (!defined('_ICMS_SOURCEEDITOR_CODEMIRROR')) {
			$langPath = __DIR__ . '/language/';
			$lang = $icmsConfig['language'];

			if (!file_exists($langPath . $lang . '.php')) {
				$lang = 'english';
			}

			require_once $langPath . $lang . '.php';
		}

		return _ICMS_SOURCEEDITOR_CODEMIRROR;
	}

	/**
	 * @inheritDoc
	 */
	public function getVersion(): string
	{
		return '0.0.0';
	}

	/**
	 * @inheritDoc
	 */
	public function getLicense(): string
	{
		return 'MIT';
	}

	/**
	 * @inheritDoc
	 */
	public function create(array $configs, $checkCompatible = false): \icms_form_elements_Textarea
	{
		require_once __DIR__ . '/codemirror.php';
		return new \IcmsSourceEditorCodeMirror($configs, $checkCompatible);
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
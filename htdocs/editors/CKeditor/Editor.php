<?php


namespace ImpressCMS\Editors\CKeditor;

use icms\plugins\EditorInterface;
use ImpressCMS\Core\View\Form\Elements\TextareaElement;

/**
 * Defines CKEditor
 *
 * @package ImpressCMS\Editors\CKeditor
 */
class Editor implements EditorInterface
{

	/**
	 * @inheritDoc
	 */
	public function getVersion(): string
	{
		return '4.5.3';
	}

	/**
	 * @inheritDoc
	 */
	public function getLicense(): string
	{
		return 'GPLv2';
	}

	/**
	 * @inheritDoc
	 */
	public function create(array $configs, $checkCompatible = false): TextareaElement
	{
		require_once __DIR__ . '/formCkeditor.php';
		return new \icmsFormCKEditor($configs, $checkCompatible);
	}

	/**
	 * @inheritDoc
	 */
	public function getOrder(): ?int
	{
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function supportsHTML(): bool
	{
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): string
	{
		global $icmsConfig;

		if (!defined('_ICMS_EDITOR_CKEDITOR')) {
			$langPath = __DIR__ . '/language/';
			$lang = $icmsConfig['language'];

			if (!file_exists($langPath . $lang . '.php')) {
				$lang = 'english';
			}

			require_once $langPath . $lang . '.php';
		}

		return _ICMS_EDITOR_CKEDITOR;
	}
}
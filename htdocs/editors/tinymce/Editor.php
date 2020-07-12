<?php


namespace ImpressCMS\Editors\TinyMCE;

use icms\plugins\EditorInterface;
use ImpressCMS\Core\View\Form\Elements\TextareaElement;

/**
 * Defines TinyMCE editor
 *
 * @package ImpressCMS\Editors\TinyMCE
 */
class Editor implements EditorInterface
{

	/**
	 * @inheritDoc
	 */
	public function getVersion(): string
	{
		return '1.0';
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
	public function create(array $configs, $checkCompatible = false): TextareaElement
	{
		require_once __DIR__ . '/formtinymce.php';
		return new \XoopsFormTinymce($configs, $checkCompatible);
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

		if (!defined('_XOOPS_EDITOR_TINYMCE')) {
			$langPath = __DIR__ . '/language/';
			$lang = $icmsConfig['language'];

			if (!file_exists($langPath . $lang . '.php')) {
				$lang = 'english';
			}

			require_once $langPath . $lang . '.php';
		}

		return _XOOPS_EDITOR_TINYMCE;
	}
}
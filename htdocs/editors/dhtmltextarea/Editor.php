<?php


namespace ImpressCMS\Editors\DHTMLTextArea;

use icms\plugins\EditorInterface;
use ImpressCMS\Core\View\Form\Elements\TextAreaElement;

/**
 * Defines DHTMLTextArea editor
 *
 * @package ImpressCMS\Editors\DHTMLTextArea
 */
class Editor implements EditorInterface
{

	/**
	 * @inheritDoc
	 */
	public function getVersion(): string
	{
		return '1.0.0';
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
	public function create(array $configs, $checkCompatible = false): TextAreaElement
	{
		require_once __DIR__ . '/dhtmltextarea.php';
		return new \FormDhtmlTextArea($configs, $checkCompatible);
	}

	/**
	 * @inheritDoc
	 */
	public function getOrder(): ?int
	{
		return 0;
	}

	/**
	 * @inheritDoc
	 */
	public function supportsHTML(): bool
	{
		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function getTitle(): string
	{
		global $icmsConfig;

		if (!defined('_XOOPS_EDITOR_DHTMLTEXTAREA')) {
			$langPath = __DIR__ . '/language/';
			$lang = $icmsConfig['language'];

			if (!file_exists($langPath . $lang . '.php')) {
				$lang = 'english';
			}

			require_once $langPath . $lang . '.php';
		}

		return _XOOPS_EDITOR_DHTMLTEXTAREA;
	}
}
<?php

namespace ImpressCMS\Core\Providers;

use icms;
use Imponeer\Smarty\Extensions\DBResource\DBResource;
use Imponeer\Smarty\Extensions\Debug\DebugPrintVarModifier;
use Imponeer\Smarty\Extensions\ForeachQ\ForeachQCloseTagCompiler;
use Imponeer\Smarty\Extensions\ForeachQ\ForeachQOpenTagCompiler;
use Imponeer\Smarty\Extensions\Image\ResizeImageFunction;
use Imponeer\Smarty\Extensions\IncludeQ\IncludeQCompiler;
use Imponeer\Smarty\Extensions\Translate\TransBlock;
use Imponeer\Smarty\Extensions\Translate\TransVarModifier;
use Imponeer\Smarty\Extensions\XO\XOAppUrlCompiler;
use Imponeer\Smarty\Extensions\XO\XOImgUrlCompiler;
use Imponeer\Smarty\Extensions\XO\XOInboxCountFunction;
use Imponeer\Smarty\Extensions\XO\XOPageNavFunction;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Registers all internal smarty plugins
 *
 * @package ImpressCMS\Core\Providers
 */
class SmartyPluginsProvider extends AbstractServiceProvider
{
	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'smarty.plugin',
	];

	/**
	 * @inheritDoc
	 */
	public function register() {
		$this->addDBResource();
		$this->leagueContainer->add(ForeachQOpenTagCompiler::class)->addTag('smarty.plugin');
		$this->leagueContainer->add(ForeachQCloseTagCompiler::class)->addTag('smarty.plugin');
		$this->leagueContainer->add(IncludeQCompiler::class)->addTag('smarty.plugin');
		$this->leagueContainer->add(XOPageNavFunction::class)->addArgument("icms::url")->addTag('smarty.plugin');
		if (class_exists(DebugPrintVarModifier::class)) {
			$this->leagueContainer->add(DebugPrintVarModifier::class)->addTag('smarty.plugin');
		}
		$this->addXOAppUrl();
		$this->addXOAppImg();
		$this->addTranslationTags();
		$this->addImageTags();
		$this->addXOInboxCount();
	}

	/**
	 * Adds DB resource plugin
	 */
	private function addDBResource() {
		$this->leagueContainer
			->add(DBResource::class)
			->addArgument('db')
			->addArgument($GLOBALS['icmsConfig']['template_set'])
			->addArgument(
				$this->leagueContainer->get('db')->prefix('tplfile')
			)
			->addArgument('tpl_source')
			->addArgument('tpl_lastmodified')
			->addArgument('tpl_tplset')
			->addArgument('tpl_file')
			->addArgument(static function (array $row) {
				$theme = $GLOBALS['icmsConfig']['theme_set'] ?? 'default';
				$module = $row['tpl_module'];
				$type = $row['tpl_type'];
				$tpl_name = $row['tpl_file'];
				$blockpath = ($type === 'block') ? 'blocks/' : '';
				$filepath = ICMS_THEME_PATH . "/$theme/modules/$module/$blockpath$tpl_name";
				if (!file_exists($filepath)) {
					$filepath = ICMS_ROOT_PATH . "/modules/$module/templates/$blockpath$tpl_name";
					if (!file_exists($filepath)) {
						return null;
					}
				}
				return $filepath;
			})
			->addTag('smarty.plugin')
		;
	}

	/**
	 * Registers XOAppURL plugin
	 */
	private function addXOAppUrl() {
		$this->leagueContainer
			->add(XOAppUrlCompiler::class)
			->addArguments([
				function (string $url) {
					return icms::getInstance()->path($url, true);
				},
				'\icms::buildUrl'
			])
			->addTag('smarty.plugin');
	}

	/**
	 * Adds xoAppImg tag
	 */
	private function addXOAppImg() {
		$this->leagueContainer
			->add(XOImgUrlCompiler::class)
			->addArgument(
				function (string $path) {
					$path = ( isset($GLOBALS['xoTheme']) && is_object( $GLOBALS['xoTheme'] ) ) ? $GLOBALS['xoTheme']->resourcePath( $path ) : $path;
					return icms::url($path);
				}
			)
			->addTag('smarty.plugin');
	}

	/**
	 * Registers tags for translations
	 */
	private function addTranslationTags() {
		$this->leagueContainer
			->add(TransVarModifier::class)
			->addArgument('translator')
			->addTag('smarty.plugin');

		$this->leagueContainer
			->add(TransBlock::class)
			->addArgument('translator')
			->addTag('smarty.plugin');
	}

	/**
	 * Adds image tags
	 */
	private function addImageTags() {
		$this->leagueContainer
			->add(ResizeImageFunction::class)
			->addArgument('cache')
			->addTag('smarty.plugin');
	}

	/**
	 * Adds xo inbox count function
	 */
	private function addXOInboxCount() {
		$this->leagueContainer
			->add(XOInboxCountFunction::class)
			->addArgument( "\\ImpressCMS\\Core\\Models\\PrivateMessage::getCountForUser")
			->addTag('smarty.plugin');
	}
}

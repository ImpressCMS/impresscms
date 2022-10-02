<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Module;

use Composer\Factory;
use Composer\IO\NullIO;
use ImpressCMS\Core\Extensions\ExtensionDescriber\DescribedItemInfoInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ModuleInfo;

/**
 * Describes module that is defined only with composer
 *
 * @package ImpressCMS\Core\Extensions\ExtensionDescriber\Module
 */
class ImpressCMSComposerModuleDescriber implements ExtensionDescriberInterface
{
	/**
	 * @inheritDoc
	 */
	public function canDescribe(string $path): bool
	{
		return file_exists($path . DIRECTORY_SEPARATOR . 'composer.json');
	}

	/**
	 * @inheritDoc
	 */
	public function describe(string $path): DescribedItemInfoInterface
	{
		chdir($path);
		putenv('COMPOSER_HOME=' . ICMS_STORAGE_PATH . '/composer');
		$composer = Factory::create(
			new NullIO()
		);

		$package = $composer->getPackage();
		$extra = $package->getExtra();

		$moduleInfo = new ModuleInfo();

		$moduleInfo->name = $package->getName();
		$moduleInfo->version = $package->getVersion();
		$moduleInfo->description = $package->getDescription();
		$moduleInfo->teammembers = implode(
			',',
			array_map(
				function ($author) {
					return $author['name'];
				},
				$package->getAuthors()
			)
		);
		$moduleInfo->credits = $extra['credits'] ?? null;
		$moduleInfo->author = $extra['author'] ?? null;
		$moduleInfo->help = $extra['help'] ?? null;
		$moduleInfo->license = $extra['license'] ?? null;
		$moduleInfo->official = false; // XoopsCube similar things did for this value (https://xoopscube.org/modules/wiki/?XOOPSCubeLegacy%2FReference%2Fxoops_version#o11a0915), so probably we do not need that too
		$moduleInfo->dirname = basename($path);
		$moduleInfo->modname = $package->getName();
		$moduleInfo->iconsmall = $extra['icon']['small'];
		$moduleInfo->iconbig = $extra['icon']['big'];
		$moduleInfo->image = $extra['icon']['big']; // for backward compatibilty; image is alias of big icon
		$moduleInfo->author_word = null; // probably here should be release message could be fetched... but how?
		$moduleInfo->date = $package->getReleaseDate() ? $package->getReleaseDate()->format('Y-m-d') : null;
		$moduleInfo->status = null; // deprecated - use version string
		$moduleInfo->status_version = null; // deprecated - use version string
		$moduleInfo->warning = $extra['warning'] ?? null;
		$moduleInfo->developer_website_url = $extra['website_url'] ?? null;
		$moduleInfo->developer_website_name = isset($extra['website_url']) ? $this->getTitleFromUrl($extra['website_url']) : null; // now automatically title will be detected
		$moduleInfo->developer_email = $extra['email'] ?? null;
		$moduleInfo->people = $extra['people'] ?? [];
		$moduleInfo->autotasks = $extra['autotasks'] ?? [];
		$moduleInfo->manual = $extra['manual'] ?? [];
		$moduleInfo->hasAdmin = isset($extra['admin']['index']) && !empty($extra['admin']) && !empty($extra['admin']['index']);
		$moduleInfo->adminindex = $extra['admin']['index'] ?? null;
		$moduleInfo->adminmenu = isset($extra['admin']['index']) ? $extra['admin']['menu'] : null;
		$moduleInfo->object_items = $extra['object_items'] ?? [];
		$moduleInfo->tables = isset($extra['object_items']) ? icms_getTablesArray($moduleInfo->dirname, $extra['object_items']) : [];
		$moduleInfo->hasSearch = isset($extra['search']['file']);
		$moduleInfo->search = [
			'file' => $extra['search']['file'] ?? null,
			'func' => isset($extra['search']['file']) ? $extra['search']['func'] : null,
		];
		$moduleInfo->hasComments = isset($extra['comments']['itemName']);
		$moduleInfo->comments = $extra['comments'] ?? [];
		$moduleInfo->templates = $extra['templates'] ?? [];
		$moduleInfo->hasMain = isset($extra['has_main'] ) && $extra['has_main'];
		$moduleInfo->demo_site_name = null;
		$moduleInfo->demo_site_url = null;
		$moduleInfo->author_email = null;
		$moduleInfo->author_realname = null;
		$moduleInfo->author_website_url = null;
		$moduleInfo->author_website_name = null;
		$moduleInfo->support_site_name = null;
		$moduleInfo->support_site_url = null;
		$moduleInfo->submit_bug = null;
		$moduleInfo->submit_feature = null;
		$moduleInfo->author_credits = null;
		$moduleInfo->onUpdate = $extra['events']['update'] ?? null;
		$moduleInfo->onInstall = $extra['events']['install'] ?? null;
		$moduleInfo->onUninstall = $extra['events']['uninstall'] ?? null;
		$moduleInfo->blocks = $extra['blocks'] ?? [];
		$moduleInfo->sub = $extra['menu'] ?? [];
		$moduleInfo->config = $extra['config'] ?? [];
		$moduleInfo->hasNotification = isset($extra['notification']);
		$moduleInfo->notification = $extra['notification'] ?? [];
		$moduleInfo->assets = $extra['assets'] ?? [];

		chdir(__DIR__);

		return $moduleInfo;
	}

	/**
	 * Gets title from URL
	 *
	 * @param string $url url from where to get page title
	 *
	 * @return string
	 */
	protected function getTitleFromUrl(string $url): string {
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => 'https://impresscms.org',
			CURLOPT_RETURNTRANSFER => 1
		]);
		$output = curl_exec($ch);

		if (($output === false) || (preg_match_all('/[<]title[>]([^<]*)[<][\/]title/i', $output, $matches) < 1)) {
			return $url;
		}

		curl_close($ch);

		return $matches[1][0];
	}
}

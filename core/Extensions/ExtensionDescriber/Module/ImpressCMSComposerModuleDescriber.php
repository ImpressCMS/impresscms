<?php

namespace ImpressCMS\Core\Extensions\ExtensionDescriber\Module;

use Composer\Factory;
use Composer\IO\NullIO;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;

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
	public function describe(string $path): array
	{
		chdir($path);
		putenv('COMPOSER_HOME=' . ICMS_STORAGE_PATH . '/composer');
		$composer = Factory::create(
			new NullIO()
		);

		$package = $composer->getPackage();
		$extra = $package->getExtra();

		$modinfo = [
			'name' => $package->getName(),
			'version' => $package->getVersion(),
			'description' => $package->getDescription(),
			'teammembers' => implode(
				',',
				array_map(
					function ($author) {
						return $author['name'];
					},
					$package->getAuthors()
				)
			),
			'credits' => $extra['credits'] ?? null,
			'author' => $extra['author'] ?? null,
			'help' => $extra['help'] ?? null,
			'license' => implode(', ', $package->getLicense()),
			'official' => false, // XoopsCube similar things did for this value (https://xoopscube.org/modules/wiki/?XOOPSCubeLegacy%2FReference%2Fxoops_version#o11a0915), so probably we do not need that too
			'dirname' => $dirname = basename($path),
			'modname' => $package->getName(),
			'iconsmall' => $extra['icon']['small'],
			'iconbig' => $extra['icon']['big'],
			'image' => $extra['icon']['big'], // for backward compatibilty; image is alias of big icon
			'author_word' => null, // probably here should be release message could be fetched... but how?
			'date' => $package->getReleaseDate()->format('Y-m-d'),
			'status' => null, // deprecated - use version string
			'status_version' => null, // deprecated - use version string
			'warning' => $extra['warning'] ?? null,
			'developer_website_url' => $extra['website_url'] ?? null,
			'developer_website_name' => isset($extra['website_url']) ? $this->getTitleFromUrl($extra['website_url']) : null, // now automatically title will be detected
			'developer_email' => $extra['email'] ?? null,
			'people' => $extra['people'] ?? [],
			'autotasks' => $extra['autotasks'] ?? [],
			'manual' => $extra['manual'] ?? [],
			'hasAdmin' => isset($extra['admin']['index']) && !empty($extra['admin']) && !empty($extra['admin']['index']),
			'adminindex' => $extra['admin']['index'] ?? null,
			'adminmenu' => isset($extra['admin']['index']) ? $extra['admin']['menu'] : null,
			'object_items' => $extra['object_items'] ?? [],
			'tables' => isset($extra['object_items']) ? icms_getTablesArray($dirname, $extra['object_items']) : [],
			'hasSearch' => isset($extra['search']['file']),
			'search' => [
				'file' => $extra['search']['file'] ?? null,
				'func' => isset($extra['search']['file']) ? $extra['search']['func'] : null,
			],
			'hasComments' => isset($extra['comments']['itemName']),
			'comments' => $extra['comments'] ?? null,
			'templates' => $extra['templates'] ?? [],
			'hasMain' => isset($extra['has_main'] ) && $extra['has_main'],
			'demo_site_url' => null,
			'demo_site_name' => null,
			'author_email' => null,
			'author_realname' => null,
			'author_website_url' => null,
			'author_website_name' => null,
			'support_site_name' => null,
			'support_site_url' => null,
			'submit_bug' => null,
			'submit_feature' => null,
			'author_credits' => null,
			'onUpdate' => $extra['events']['update'] ?? null,
			'blocks' => $extra['blocks'] ?? [],
			'sub' => $extra['menu'] ?? [],
			'config' => $extra['config'] ?? [],
			'hasNotification' => isset($extra['notification']),
			'notification' => $extra['notification'],
		];

		chdir(__DIR__);

		return array_filter(
			$modinfo
		);
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
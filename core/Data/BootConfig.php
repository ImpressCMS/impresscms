<?php

namespace ImpressCMS\Core\Data;

use Dotenv\Dotenv;
use ImpressCMS\Core\Exceptions\PublicPathResolveException;
use function env;

/**
 * Class to used for dealing with configuration that is build on runtime
 */
class BootConfig
{

	/**
	 * Loaded config data
	 *
	 * @var array<string, string|integer|bool>
	 */
	protected $config = [];

	public function __construct()
	{
		if (empty($this->config)) {
			Dotenv::create($this->resolveRootPath())->safeLoad();

			$this->updateMainData();
		}
	}

	/**
	 * Checks if is possible to get some config data from env
	 *
	 * @return bool
	 */
	public function isEnvDataExists(): bool
	{
		return function_exists('env');
	}

	/**
	 * Gets value
	 *
	 * @param string $name
	 * @param $defaultValue
	 * @return mixed
	 */
	public function get(string $name, $defaultValue = null) {
		if (!isset($this->config[$name])) {
			$this->config[$name] = env(
				strtoupper($name),
				$defaultValue
			);
		}

		return $this->config[$name];
	}

	/**
	 * Returns all items
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		return $this->config;
	}

	/**
	 * Updates current config loaded data
	 *
	 * @return void
	 */
	public function updateMainData(): void {
		$rootPath = $this->resolveRootPath();
		$url = $this->resolveUrl();

		$publicPath = $this->resolvePublicPath($rootPath);
		if ($publicPath === null) {
			throw new PublicPathResolveException();
		}

		$uploadPath = $rootPath . DIRECTORY_SEPARATOR . "uploads";
		$uploadUrl = $url . DIRECTORY_SEPARATOR . "uploads";
		$storagePath = $rootPath . DIRECTORY_SEPARATOR . "storage";
		$imagesUrl = $url . DIRECTORY_SEPARATOR . "images";

		$this->config = [
			'root_path' => $rootPath,
			'url' => $url,

			'public_path' => $publicPath,

			'plugins_path' => $rootPath . DIRECTORY_SEPARATOR . "plugins",
			'plugins_url' => $url . DIRECTORY_SEPARATOR . "plugins",

			'preload_path' => $rootPath . DIRECTORY_SEPARATOR . "preloads",

			'modules_path' => $rootPath . DIRECTORY_SEPARATOR . "modules",
			'modules_url' => $url . DIRECTORY_SEPARATOR . "modules",

			'libraries_path' => $rootPath . DIRECTORY_SEPARATOR . "libraries",
			'libraries_url' => $url . DIRECTORY_SEPARATOR . "libraries",

			'include_path' => $rootPath . DIRECTORY_SEPARATOR . "include",
			'include_url' => $url . DIRECTORY_SEPARATOR . "include",

			'upload_path' => $uploadPath,
			'upload_url' => $uploadUrl,

			'theme_path' => $rootPath . DIRECTORY_SEPARATOR . "themes",
			'theme_url' => $url . DIRECTORY_SEPARATOR . "themes",

			'storage_path' => $storagePath,
			'cache_path' => $storagePath . DIRECTORY_SEPARATOR . 'cache',
			'logging_path' => $storagePath . DIRECTORY_SEPARATOR . 'log',
			'purifier_path' => $storagePath . DIRECTORY_SEPARATOR . 'htmlpurifier',
			'compile_path' => $storagePath . DIRECTORY_SEPARATOR . 'templates_c',

			'images_url' => $imagesUrl,

			'editor_path' => $publicPath . DIRECTORY_SEPARATOR . "editors",
			'editor_url' => $url . DIRECTORY_SEPARATOR . "editors",

			'imanager_folder_path' => $uploadPath . DIRECTORY_SEPARATOR . "imagemanager",
			'imanager_folder_url' => $uploadUrl . DIRECTORY_SEPARATOR . "editors",

			'images_set_url' => $imagesUrl . DIRECTORY_SEPARATOR . 'kfaenza', // todo: make this changeble in config
		];
	}

	/**
	 * Resolves root path
	 *
	 * @return string
	 */
	protected function resolveRootPath(): string {
		return dirname(__DIR__, 2);
	}

	/**
	 * Resolve web URL
	 *
	 * @return string
	 */
	protected function resolveUrl(): string
	{
		return rtrim(
			(string)env('URL', 'http://localhost'),
			'/'
		);
	}

	/**
	 * Tries to detect public documents path.
	 *
	 * @return string|null Returns string if detected sucessfully and null if failed
	 */
	protected function resolvePublicPath(string $rootPath): ?string {
		$path = env('public_path');
		if ($path) {
			return $path;
		}

		foreach (['public_html', 'htdocs', 'wwwroot'] as $dirname) {
			$path = $rootPath . DIRECTORY_SEPARATOR . $dirname;
			if (file_exists($path) && is_dir($path)) {
				return $dirname;
			}
		}

		return null;
	}

}
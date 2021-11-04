<?php


namespace ImpressCMS\Core\Controllers;

use Exception;
use GuzzleHttp\Psr7\Response;
use icms;
use ImpressCMS\Core\Models\Module;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function GuzzleHttp\Psr7\mimetype_from_filename;

/**
 * This controller is used when dealing with legacy code
 *
 * @package ImpressCMS\Core\Controllers
 */
class LegacyController
{

	/**
	 * Proxy legacy file
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function proxy(ServerRequestInterface $request): ResponseInterface
	{
		$path = ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . $request->getUri()->getPath();
		if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
			$currentModule = $request->getAttribute('module');

			$module_handler = icms::handler('icms_module');
			try {
				$modules = $module_handler->getObjects();
				/**
				 * @var Module $module
				 */
				foreach ($modules as $module) {
					$module->registerClassPath(TRUE);
				}
			} catch (Exception $exception) {

			}

			global $icmsTpl, $xoopsTpl, $xoopsOption, $icmsAdminTpl, $icms_admin_handler, $icmsModule;
			if (!isset($icmsModule)) {
				$icmsModule = icms::$module;
			}

			ob_start();

			// Never PHP needs to have all constants in file defined and this is problem with some older modules that loads a bit later translations
			// so here is hack to fix this issue - load all language files at once
			if (version_compare(phpversion(), '8.0', '>=')) {
				/**
				 * @var Filesystem $modulesFs
				 */
				$modulesFs = icms::getInstance()->get('filesystem.modules');
				foreach ((array)$modulesFs->listContents(icms::$module->dirname . '/language/english/', true) as $file) {
					if (!($file instanceof FileAttributes)) {
						continue;
					}
					$relativePath = $file->path();
					$basename = basename($relativePath);
					$ext = pathinfo($relativePath, PATHINFO_EXTENSION);
					if ($basename === '.' || $ext !== 'php') {
						continue;
					}
					icms_loadLanguageFile(icms::$module->dirname, $relativePath);
				}
			}

			require $path;
			$headers = [];
			foreach(headers_list() as $header) {
				[$headerName, $headerValue] = explode(':', $header, 2);
				$headerName = strtolower(trim($headerName));
				$headerValue = trim($headerValue);
				if ((strtolower($headerName) === 'location') && !filter_var($headerValue, FILTER_VALIDATE_URL) && $headerValue[0] !== '/') {
					$headerValue = ICMS_URL . dirname($request->getUri()->getPath()) . '/' . $headerValue;
				}
				$headers[$headerName] = $headerValue;
			}

			return new Response(
				isset($headers['location']) ? 301 : 200,
				$headers,
				ob_get_clean()
			);
		}

		return new Response(
			200,
			[
				'Content-Type' => mimetype_from_filename($path),
				'Content-Length' => filesize($path),
				'E-Tag' => sprintf('"%s"', sha1_file($path)),
				'Last-Modified' => gmdate('D, d M Y H:i:s ', filemtime($path)) . 'GMT'
			],
			fopen($path, 'rb')
		);
	}

}

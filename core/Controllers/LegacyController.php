<?php


namespace ImpressCMS\Core\Controllers;

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
		$path = ICMS_ROOT_PATH . $request->getUri()->getPath();
		if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
			global $icmsTpl, $xoopsTpl, $xoopsOption, $icmsAdminTpl, $icms_admin_handler;
			ob_start();
			require $path;
			return new \GuzzleHttp\Psr7\Response(
				200,
				[],
				ob_get_clean()
			);
		}

		return new \GuzzleHttp\Psr7\Response(
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
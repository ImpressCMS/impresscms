<?php

namespace ImpressCMS\Core\Controllers;

use icms;
use ImpressCMS\Core\Data\Feeds\Simplerss;
use ImpressCMS\Core\Response\ViewResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sunrise\Http\Router\Annotation\Route;

/**
 * Defines base admin actions
 *
 * @package ImpressCMS\Core\Controllers
 */
class AdminController
{

	/**
	 * Returns default admin page
	 *
	 * @Route(
	 *     name="admin",
	 *     path="/admin.php",
	 *     methods={"GET"}
	 * )
	 *
	 * @param RequestInterface $request
	 *
	 * @return ResponseInterface
	 */
	public function getDefaultPage(ServerRequestInterface $request)
	{
		$params = $request->getQueryParams();

		define('ICMS_IN_ADMIN', 1);

		icms::$logger->stopTime('Module init');
		icms::$logger->startTime('ImpressCMS CP Output Init');

		if (isset($params['op']) && (int) $params['op'] === 1) {
			return $this->showRSSFeedPage($request);
		}

		$response = new ViewResponse([
			'isAdminSide' => true
		]);

		icms::$preload->triggerEvent('adminHeader');

		icms::$logger->stopTime();
		icms::$logger->stopTime('Module display');
		icms::$logger->stopTime('XOOPS output init');

		return $response;
	}

	/**
	 * Shows ImpressCMS RSS feed page
	 *
	 * @Route(
	 *     name="impresscms_rss_feed_page",
	 *     path="/admin/rss",
	 *     methods={"GET"}
	 * )
	 *
	 * @param ServerRequestInterface $request
	 *
	 * @return ResponseInterface
	 */
	public function showRSSFeedPage(ServerRequestInterface $request): ResponseInterface
	{
		global $icmsConfigPersona;

		$feed = new Simplerss($icmsConfigPersona['rss_local'], 3600);
		$feed->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
		$feed->init();
		$feed->handle_content_type();

		$response = new ViewResponse(
			[
				'isAdminSide' => true,
				'template_main' => 'db:admin/system_adm_rss.html',
			]
		);

		if ($feed->error) {
			/**
			 * @var ResponseFactoryInterface $responseFactory
			 */
			$responseFactory = icms::getInstance()->get('response_factory');
			return $responseFactory->createResponse(500, $feed->error);
		}

		$items = [];
		foreach ($feed->get_items() as $item) {
			$items[] = [
				'link' => $item->get_permalink(),
				'title' => $item->get_title(),
				'description' => $item->get_description(),
				'date' => $item->get_date(),
				'guid' => $item->get_id(),
			];
		}

		$response->assign('admin_rss_feed_link', $feed->get_link());
		$response->assign('admin_rss_feed_title', $feed->get_title());
		$response->assign('admin_rss_feed_dsc', $feed->get_description());
		$response->assign('admin_rss_feeditems', $items);

		return $response;
	}

}
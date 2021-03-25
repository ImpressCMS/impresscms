<?php

namespace ImpressCMS\Core\Controllers;

use Aura\Session\Session;
use GuzzleHttp\Psr7\Response;
use icms;
use ImpressCMS\Core\Response\RedirectResponse;
use ImpressCMS\Core\Response\ViewResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function icms_loadLanguageFile;

/**
 * Controller for doing some basic stuff
 *
 * @package ImpressCMS\Core\Controllers
 */
class DefaultController
{

	/**
	 * Gets index page
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function getIndex(ServerRequestInterface $request): ResponseInterface
	{
		global $icmsConfig;

		$member_handler = icms::handler('icms_member');
		$group = $member_handler->getUserBestGroup(
			(!empty(icms::$user) && is_object(icms::$user)) ? icms::$user->uid : 0
		);

		// added failover to default startpage for the registered users group -- JULIAN EGELSTAFF Apr 3 2017
		$groups = (!empty(icms::$user) && is_object(icms::$user)) ? icms::$user->getGroups() : [ICMS_GROUP_ANONYMOUS];
		if ((($icmsConfig['startpage'][$group] === '') || ($icmsConfig['startpage'][$group] === '--'))
			&& in_array(ICMS_GROUP_USERS, $groups, true)
			&& !in_array($icmsConfig['startpage'][ICMS_GROUP_USERS], ['', '--'], true)
		) {
			$icmsConfig['startpage'] = $icmsConfig['startpage'][ICMS_GROUP_USERS];
		} else {
			$icmsConfig['startpage'] = $icmsConfig['startpage'][$group];
		}

		if (isset($icmsConfig['startpage']) && $icmsConfig['startpage'] != '' && $icmsConfig['startpage'] != '--') {
			$arr = explode('-', $icmsConfig['startpage']);
			if (count($arr) > 1) {
				$page_handler = icms::handler('icms_data_page');
				$page = $page_handler->get($arr[1]);
				if (is_object($page)) {
					return new RedirectResponse(
						$page->getURL()
					);
				}
				$icmsConfig['startpage'] = '--';
				return $this->getDefaultEmptyPage($request);
			}
			return new RedirectResponse(
				ICMS_MODULES_URL . '/' . $icmsConfig['startpage'] . '/'
			);
		}
		return $this->getDefaultEmptyPage($request);
	}

	/**
	 * Gets default empty page
	 *
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 */
	public function getDefaultEmptyPage(ServerRequestInterface $request): ResponseInterface
	{
		global $xoopsOption;
		$xoopsOption['show_cblock'] = 1;

		$response = new ViewResponse();
		$response->assign('icms_contents', ob_get_clean());

		return $response;
	}

	/**
	 * Gets error page
	 *
	 * @param ServerRequestInterface $request
	 *
	 * @return ResponseInterface
	 */
	public function getError(ServerRequestInterface $request): ResponseInterface
	{
		global $icmsConfig, $xoopsOption;

		$xoopsOption['pagetype'] = 'error';

		$params = $request->getQueryParams();

		$errorNo = isset($params['e']) ? (int)$params['e'] : 500;

		$response = new ViewResponse([
			'template_main' => 'system_error.html'
		]);

		icms_loadLanguageFile('core', 'error');

		$siteName = $icmsConfig['sitename'];
		$lang_error_no = sprintf(_ERR_NO, $errorNo);

		$response->assign('lang_found_contact', sprintf(_ERR_CONTACT, $icmsConfig['adminmail']));
		$response->assign('lang_search', _ERR_SEARCH);
		$response->assign('lang_advanced_search', _ERR_ADVANCED_SEARCH);
		$response->assign('lang_start_again', _ERR_START_AGAIN);
		$response->assign('lang_search_our_site', _ERR_SEARCH_OUR_SITE);

		$msg = $params['msg'] ? constant('_ERR_MSG_' . strtoupper($params['msg'])) : sprintf(constant('_ERR_' . $errorNo . '_DESC'), $siteName);


		$response->assign('lang_error_no', $lang_error_no);
		$response->assign('lang_error_desc', $msg);
		$response->assign('lang_error_title', $lang_error_no . ' ' . constant('_ERR_' . $errorNo . '_TITLE'));
		$response->assign('icms_pagetitle', $lang_error_no . ' ' . constant('_ERR_' . $errorNo . '_TITLE'));

		return $response;
	}

	/**
	 * Pings from user interface to automatically extend session
	 *
	 * @param ServerRequestInterface $request
	 * @return ResponseInterface
	 */
	public function getPing(ServerRequestInterface $request): ResponseInterface {
		/**
		 * @var Session $session
		 */
		$session = $request->getAttribute('session');
		if ($session->isStarted()) {
			$session->regenerateId();
		}
		return new Response();
	}

}
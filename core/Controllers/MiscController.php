<?php

namespace ImpressCMS\Core\Controllers;

use GuzzleHttp\Psr7\Response;
use icms;
use ImpressCMS\Core\DataFilter;
use ImpressCMS\Core\Messaging\MessageSender;
use ImpressCMS\Core\Models\AvatarHandler;
use ImpressCMS\Core\Models\OnlineHandler;
use ImpressCMS\Core\Models\User;
use ImpressCMS\Core\Response\ViewResponse;
use ImpressCMS\Core\Security\RequestSecurity;
use ImpressCMS\Core\View\Form\Elements\ButtonElement;
use ImpressCMS\Core\View\Form\Elements\Captcha\ImageRenderer;
use ImpressCMS\Core\View\Form\Elements\HiddenElement;
use ImpressCMS\Core\View\Form\Elements\HiddenTokenElement;
use ImpressCMS\Core\View\Form\Elements\LabelElement;
use ImpressCMS\Core\View\Form\Elements\TextElement;
use ImpressCMS\Core\View\Form\Elements\TrayElement;
use ImpressCMS\Core\View\Form\ThemeForm;
use ImpressCMS\Core\View\PageNav;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sunrise\Http\Router\Annotation\Route;
use Sunrise\Http\Router\Router;
use Symfony\Contracts\Translation\TranslatorInterface;
use const FILTER_VALIDATE_EMAIL;

/**
 * Does few usefull actions
 *
 * @package ImpressCMS\Core\Controllers
 */
class MiscController
{

	/**
	 * Gets default misc page
	 *
	 * @Route(
	 *     name="default_misc_page",
	 *     path="/misc.php",
	 *     methods={"GET", "POST"}
	 * )
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function getDefaultPage(ServerRequestInterface $request): ResponseInterface
	{
		$params = $request->getQueryParams();

		switch ($params['action'] ?? null) {
			case 'update-captcha':
				return $this->updateCaptcha($request);
			case 'showpopups':
				switch ($params['type'] ?? null) {
					case 'smilies':
					case 'smiles':
						return $this->showSmilesPopup($request);
					case 'friend':
						return $this->showRecommendToFriendPopup($request);
					case 'avatars':
						return $this->showAvatarsPopup($request);
					case 'online':
						return $this->showUsersOnlinePopup($request);
				}
		}

		/**
		 * @var ResponseFactoryInterface $responseFactory
		 */
		$responseFactory = icms::getInstance()->get('response_factory');

		return $responseFactory->createResponse(404);
	}

	/**
	 * Updates captcha to be entered
	 *
	 * @Route(
	 *     name="update_captcha",
	 *     path="/update-captcha",
	 *     methods={"GET"}
	 * )
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function updateCaptcha(ServerRequestInterface $request)
	{
		$image_handler = new ImageRenderer();
		$image_handler->clearAttempts();

		ob_start();
		$image_handler->loadImage();
		$contents = ob_get_clean();
		ob_end_clean();

		return new Response(200, headers_list(), $contents);
	}

	/**
	 * Shows smiles list for popup
	 *
	 * @Route(
	 *     name="show_smiles_popup",
	 *     path="/smiles/popup",
	 *     methods={"GET"}
	 * )
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function showSmilesPopup(ServerRequestInterface $request): ResponseInterface
	{
		$params = $request->getQueryParams();

		$target = $params['target'] ?? '';
		if (!$target || !preg_match('/^[0-9a-z_]*$/i', $target)) {
			/**
			 * @var ResponseFactoryInterface $responseFactory
			 */
			$responseFactory = icms::getInstance()->get('response_factory');
			return $responseFactory->createResponse(400, "Target not correct or specified");
		}

		$response = new ViewResponse([
			'template_canvas' => 'db:system_blank.html',
			'template_main' => 'db:system_smiles.html',
		]);
		$response->assign(
			'smiles',
			DataFilter::getSmileys(true)
		);
		$response->assign('target', $target);
		return $response;
	}

	/**
	 * Shows friends list for popup
	 *
	 * @Route(
	 *     name="recommend_to_friends_popup",
	 *     path="/recommend-to-friend/popup",
	 *     methods={"GET", "POST"}
	 * )
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function showRecommendToFriendPopup(ServerRequestInterface $request): ResponseInterface
	{
		$params = $request->getQueryParams() + $request->getParsedBody();

		/**
		 * @var RequestSecurity $security
		 */
		$security = icms::getInstance()->get('security');

		$op = $params['op'] ?? 'showform';

		/**
		 * @var TranslatorInterface $translator
		 */
		$translator = icms::getInstance()->get('translator');

		/**
		 * @var Router $router
		 */
		$router = icms::getInstance()->get('router');

		$response = new ViewResponse([
			'template_canvas' => 'db:system_blank.html',
			'template_main' => 'db:system_send_to_friend.html',
		]);

		$validationErrors = [];
		if ($op === 'sendsite') {
			if (empty($params['yname']) || empty($params['ymail']) || empty($params['fname']) || empty($params['fmail'])) {
				$validationErrors[] = $translator->trans('_MSC_NEEDINFO', [], 'misc');
			} elseif (!filter_var($params['ymail'], FILTER_VALIDATE_EMAIL)) {
				$validationErrors[] = $translator->trans('_MSC_INVALIDEMAIL1', [], 'misc');
			} elseif (!filter_var($params['fmail'], FILTER_VALIDATE_EMAIL)) {
				$validationErrors[] = $translator->trans('_MSC_INVALIDEMAIL2', [], 'misc');
			}
		}

		if (!empty($validationErrors) || $op !== 'sendsite' || !$security->check()) {
			$form = new ThemeForm(
				$translator->trans('_MSC_RECOMMENDSITE', [], 'misc'),
				'recommend',
				$router->generateUri('recommend_to_friends_popup'),
				'post'
			);
			$form->setExtra('onsubmit="return checkForm();"');
			$form->addElement(
				new HiddenElement('op', 'sendsite')
			);
			$form->addElement(
				new HiddenElement('action', 'showpopups')
			);
			$form->addElement(
				new HiddenElement('type', 'friend')
			);
			$form->addElement(
				new HiddenElement('type', 'friend')
			);

			$yourNameTranslated = $translator->trans('_MSC_YOURNAMEC', [], 'misc');
			$yourEmailTranslated = $translator->trans('_MSC_FRIENDNAMEC', [], 'misc');
			if (icms::$user instanceof User) {
				$yourNameField = new TrayElement($yourNameTranslated);
				$yourNameField->addElement(
					new LabelElement(icms::$user->uname)
				);
				$yourNameField->addElement(
					new HiddenElement('yname', icms::$user->uname)
				);
				$yourEmailField = new TrayElement($yourEmailTranslated);
				$yourEmailField->addElement(
					new LabelElement(icms::$user->email)
				);
				$yourEmailField->addElement(
					new HiddenElement('ymail', icms::$user->email)
				);
			} else {
				$yourNameField = new TextElement($yourNameTranslated, 'yname', 255, 255, $params['yname'] ?? '');
				$yourEmailField = new TextElement($yourEmailTranslated, 'ymail', 255, 255, $params['ymail'] ?? '');
			}
			$form->addElement($yourNameField, true);
			$form->addElement($yourEmailField, true);
			$form->addElement(
				new TextElement(
					$translator->trans('_MSC_FRIENDNAMEC', [], 'misc'),
					'fname',
					30,
					255,
					$params['fname'] ?? ''
				),
				true
			);
			$form->addElement(
				new TextElement(
					$translator->trans('_MSC_FRIENDEMAILC', [], 'misc'),
					'fmail',
					30,
					255,
					$params['fmail'] ?? ''
				),
				true
			);
			$form->addElement(
				new HiddenTokenElement()
			);
			$submitButton = new ButtonElement(
				'&nbsp;',
				'send',
				$translator->trans('_SEND', [], 'global'),
				'submit'
			);
			$form->addElement($submitButton);

			$response->assign(
				'errors',
				array_merge(
					$security->getErrors(),
					$validationErrors
				)
			);
			$response->assign(
				'form',
				$form->render()
			);

			return $response;
		}

		global $icmsConfig;

		$mailer = new MessageSender();
		$mailer->setTemplate('tellfriend.tpl');
		$mailer->assign('SITENAME', $icmsConfig['sitename']);
		$mailer->assign('ADMINMAIL', $icmsConfig['adminmail']);
		$mailer->assign(
			'SITEURL',
			$router->generateUri('homepage')
		);
		$mailer->assign('YOUR_NAME', $params['yname']);
		$mailer->assign('FRIEND_NAME', $params['fname']);
		$mailer->setToEmails($params['fmail']);
		$mailer->setFromEmail($params['ymail']);
		$mailer->setFromName($params['yname']);
		$mailer->setSubject(
			$translator->trans('_MSC_INTSITE', ['%s' => $icmsConfig['sitename']], 'misc')
		);

		if (!$mailer->send()) {
			$response->assign(
				'errors',
				$mailer->getErrors()
			);
		} else {
			$response->assign(
				'message',
				$translator->trans('_MSC_REFERENCESENT', [], 'misc')
			);
		}

		return $response;
	}

	/**
	 * Shows avatars list for popup
	 *
	 * @Route(
	 *     name="show_avatars_popup",
	 *     path="/avatars/popup",
	 *     methods={"GET"}
	 * )
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function showAvatarsPopup(ServerRequestInterface $request): ResponseInterface
	{
		global $icmsConfigUser;

		/**
		 * @var AvatarHandler $avatar_handler
		 */
		$avatar_handler = icms::handler('icms_data_avatar');

		$response = new ViewResponse([
			'template_canvas' => 'db:system_blank.html',
			'template_main' => 'db:system_avatars.html',
		]);

		$response->assign(
			'avatars',
			$avatar_handler->getList('S')
		);
		$response->assign('avatar_width', $icmsConfigUser['avatar_width']);
		$response->assign('avatar_height', $icmsConfigUser['avatar_height']);
		return $response;
	}

	/**
	 * Shows online list for popup
	 *
	 * @Route(
	 *     name="show_online_popup",
	 *     path="/online/popup",
	 *     methods={"GET"}
	 * )
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function showUsersOnlinePopup(ServerRequestInterface $request): ResponseInterface
	{
		global $icmsConfig;

		$data = $request->getQueryParams();

		/**
		 * @var OnlineHandler $onlineHandler
		 */
		$onlineHandler = icms::handler('icms_core_Online');

		$start = isset($data['start']) ? (int) $data['start'] : 0;
		$limit = 20;

		$count = $onlineHandler->getCount();

		$response = new ViewResponse([
			'template_canvas' => 'db:system_blank.html',
			'template_main' => 'db:system_who_is_online.html',
		]);
		$response->assign(
			'items',
			$onlineHandler->getItemsForList($start, $limit)
		);
		$response->assign(
			'isAdmin',
			icms::$user ? icms::$user->isAdmin() : false
		);
		$response->assign('anonymousName', $icmsConfig['anonymous']);

		if ($count > $limit) {
			$nav = new PageNav($$count, $limit, $start, 'start', 'action=showpopups&amp;type=online');
			$response->assign('nav', $nav->renderNav());
		}

		return $response;
	}

}
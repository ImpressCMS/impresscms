<?php

namespace ImpressCMS\Core\Controllers;

use icms;
use Imponeer\Database\Criteria\CriteriaCompo;
use Imponeer\Database\Criteria\CriteriaItem;
use Imponeer\Database\Criteria\Enum\ComparisionOperator;
use ImpressCMS\Core\Facades\Member;
use ImpressCMS\Core\Messaging\MessageSender;
use ImpressCMS\Core\Models\User;
use ImpressCMS\Core\Response\RedirectResponse;
use ImpressCMS\Core\Response\ViewResponse;
use PHPMailer\PHPMailer\Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RandomLib\Factory as RandomFactory;
use SecurityLib\Strength;
use Sunrise\Http\Router\Annotation\Route;
use Sunrise\Http\Router\Router;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Controller that handles all password related functionality
 */
class PasswordController
{

	/**
	 * Gets default misc page
	 *
	 * @Route(
	 *     name="lost_password",
	 *     path="/lostpass.php",
	 *     methods={"GET", "POST"}
	 * )
	 *
	 * @param ServerRequestInterface $request Request
	 *
	 * @return ResponseInterface
	 */
	public function getLostPass(ServerRequestInterface $request): ResponseInterface {
		$params = $request->getParsedBody() + $request->getQueryParams();

		/**
		 * @var TranslatorInterface $translator
		 */
		$translator = icms::getInstance()->get('translator');

		if (empty($params['email'])) {
			return new RedirectResponse(
				'user.php',
				301,
				$translator->trans('_US_SORRYNOTFOUND', [], 'user')
			);
		}

		/**
		 * @var Member $members
		 */
		$members = icms::handler('icms_member');
		$criteria = new CriteriaCompo(
			new CriteriaItem('email', $params['email'])
		);
		$criteria->add(
			new CriteriaItem('level', '-1', ComparisionOperator::NOT_EQUAL_TO)
		);
		$criteria->setLimit(1);
		$users = $members->getUsers($criteria);
		if (empty($users)) {
			return new RedirectResponse(
				'user.php',
				301,
				$translator->trans('_US_SORRYNOTFOUND', [], 'user')
			);
		}

		global $icmsConfig;

		if (!empty($params['code']) && strpos($users[0]->pass, $params['code']) === 0 && strlen($params['code']) === 5) {
			return $this->generateLostPass(
				$users[0],
				$icmsConfig['sitename'],
				$icmsConfig['adminmail'],
				ICMS_URL . '/',
				$request->getServerParams()['REMOTE_ADDR'],
				\icms::getInstance()
			);
		}

		return $this->generatePasswordRetrieval(
			$users[0],
			$icmsConfig['sitename'],
			$icmsConfig['adminmail'],
			ICMS_URL,
			$request->getServerParams()['REMOTE_ADDR'],
			\icms::getInstance()
		);
	}

	/**
	 * Generate password retrieval response
	 *
	 * @param User $user User for whom
	 * @param string $siteName Sitename
	 * @param string $adminMail Admin name
	 * @param string $siteUrl Site URL
	 * @param string $ip Requester IP
	 * @param ContainerInterface $container Used container
	 *
	 * @return ResponseInterface
	 *
	 * @throws Exception
	 */
	protected function generatePasswordRetrieval(
		User $user,
		string $siteName,
		string $adminMail,
		string $siteUrl,
		string $ip,
		ContainerInterface $container
	) {
		/**
		 * @var TranslatorInterface $translator
		 */
		$translator = $container->get('translator');

		/**
		 * @var Router $router
		 */
		$router = $container->get('router');

		$code = substr($user->pass, 0, 5);

		$mailer = new MessageSender();
		$mailer
			->setTemplate('lostpass1.tpl')
			->assign('SITENAME', $siteName)
			->assign('ADMINMAIL', $adminMail)
			->assign('SITEURL', $siteUrl)
			->assign('IP', $ip)
			->assign('NEWPWD_LINK',
				$router->generateUri('lost_password') . '?email=' . $user->email . '&code=' . $code
			)
			->useMail()
			->setFromEmail($adminMail)
			->setFromName($siteName)
			->setToUsers($user)
			->setSubject(
				$translator->trans('_US_NEWPWDREQ', ['%s' => $siteName], 'user')
			)
		;

		if (!$mailer->send()) {
			/**
			 * @var ResponseFactoryInterface $responseFactory
			 */
			$responseFactory = $container->get('response_factory');

			return $responseFactory->createResponse(
				500,
				$mailer->getErrors()
			);
		}


		$response = new ViewResponse([
			'template_main' => 'db:system_dummy.html',
		]);
		$response->assign(
			'dummy_content',
			$translator->trans('_US_CONFMAIL', ['%s' => $user->uname], 'user')
		);

		return $response;
	}

	/**
	 * Generate lost password
	 *
	 * @param User $user User who needs password
	 * @param string $siteName Site name
	 * @param string $adminEmail Administrator email
	 * @param string $siteUrl Site URL
	 * @param string $ip IP who requested password change
	 * @param ContainerInterface $container Container for some services
	 *
	 * @return ResponseInterface
	 *
	 * @throws Exception
	 */
	protected function generateLostPass(
		User               $user,
		string             $siteName,
		string             $adminEmail,
		string             $siteUrl,
		string             $ip,
		ContainerInterface $container
	): ResponseInterface {
		$randomFactory = new RandomFactory();
		$passStrength = new Strength(Strength::MEDIUM);

		$user->pass = $randomFactory->getGenerator($passStrength)->generateString(
			8,
			'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
		);
		$user->pass_expired = true;

		/**
		 * @var TranslatorInterface $translator
		 */
		$translator = $container->get('translator');

		$mailer = new MessageSender();
		$mailer
			->useMail()
			->setFromEmail($adminEmail)
			->setFromName($siteName)
			->setSubject(
				$translator->trans('_US_NEWPWDREQ', ['%s' => $siteUrl], 'user')
			)
			->setTemplate('lostpass2.tpl')
			->assign('SITENAME', $siteName)
			->assign('ADMINMAIL', $adminEmail)
			->assign('SITEURL', $siteUrl)
			->assign('IP',  $ip)
			->assign('NEWPWD', $user->pass)
			->setToUsers($user)
		;

		if (!$user->store()) {
			/**
			 * @var ResponseFactoryInterface $responseFactory
			 */
			$responseFactory = $container->get('response_factory');

			return $responseFactory->createResponse(
				500,
				$translator->trans('_US_MAILPWDNG', [], 'user')
			);
		}

		if (!$mailer->send()) {
			/**
			 * @var ResponseFactoryInterface $responseFactory
			 */
			$responseFactory = $container->get('response_factory');

			return $responseFactory->createResponse(
				500,
				$mailer->getErrors()
			);
		}

		return new RedirectResponse(
			'user.php',
			301,
			$translator->trans('_US_PWDMAILED', ['%s' => $user->uname], 'user')
		);
	}

}
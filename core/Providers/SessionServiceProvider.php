<?php

namespace ImpressCMS\Core\Providers;

use Aura\Session\SessionFactory;
use icms;
use ImpressCMS\Core\Facades\Member;
use ImpressCMS\Core\Models\User;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Session service provider
 */
class SessionServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{

	/**
	 * @inheritdoc
	 */
	protected $provides = [
		'session',
		'user'
	];

	/**
	 * @inheritdoc
	 */
	public function register()
	{

	}

	/**
	 * @inheritDoc
	 */
	public function boot()
	{
		$this->getContainer()->add('session', $this->getSessionInstance());
	}

	/**
	 * Get session instance
	 *
	 * @return \Aura\Session\Session
	 */
	protected function getSessionInstance()
	{
		global $icmsConfig;

		$factory = new SessionFactory();
		$session = $factory->newInstance($_COOKIE);
		$session->setCookieParams([
			'secure' => substr(ICMS_URL, 0, 5) == 'https',
			'httponly' => true,
			'domain' => parse_url(ICMS_URL, PHP_URL_HOST),
			'path' => parse_url(ICMS_URL, PHP_URL_PATH),
			'lifetime' => 60 * $icmsConfig['session_expire']
		]);
		// $sslpost_name = isset($_POST[$icmsConfig['sslpost_name']]) ? $_POST[$icmsConfig['sslpost_name']] : "";
		if ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '') {
			$session->setName($icmsConfig['session_name']);
		} else {
			$session->setName('ICMSSESSION');
		}

		$userSection = $session->getSegment(User::class);
		if ($userid = $userSection->get('userid')) {
			/**
			 * @var Member $userHandler
			 */
			$userHandler = icms::handler('icms_member');
			$user = $userHandler->getUser($userid);
			if (!is_object($user)) {
				// Regenerate a new session id and destroy old session
				$session->regenerateId();
				$session->clear();
				icms::$user = null;
			} else {
				$user->setGroups($userSection->get('groups'));
				if (!$userSection->get('language')) {
					$userSection->set('language', $user->getVar('language'));
				}
				icms::$user = $user;
			}
		}

		return $session;
	}
}
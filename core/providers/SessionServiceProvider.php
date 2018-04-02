<?php

namespace ImpressCMS\Core\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use icms_core_Session;
use icms;
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
		$this->getContainer()->add('session', function () {
			global $icmsConfig;
			$db = $this->getContainer()->get('xoopsDB');
			$instance = new icms_core_Session($db);
			session_set_save_handler(
				array($instance, 'open'), array($instance, 'close'), array($instance, 'read'),
				array($instance, 'write'), array($instance, 'destroy'), array($instance, 'gc')
			);
			$sslpost_name = isset($_POST[$icmsConfig['sslpost_name']]) ? $_POST[$icmsConfig['sslpost_name']] : "";
			$instance->sessionStart($sslpost_name);

			if (!empty($_SESSION['xoopsUserId'])) {
				$user = icms::handler('icms_member')->getUser($_SESSION['xoopsUserId']);
				if (!is_object($user)) {
					// Regenerate a new session id and destroy old session
					$instance->icms_sessionRegenerateId(true);
					$_SESSION = array();
					icms::$user = null;
				} else {
					if ($icmsConfig['use_mysession'] && $icmsConfig['session_name'] != '') {
						// we need to secure cookie when using SSL
						$secure = substr(ICMS_URL, 0, 5) == 'https' ? 1 : 0;
						setcookie(
							$icmsConfig['session_name'], session_id(),
							time() + (60 * $icmsConfig['session_expire']), '/', '', $secure, 1
						);
					}
					$user->setGroups($_SESSION['xoopsUserGroups']);
					if (!isset($_SESSION['UserLanguage']) || empty($_SESSION['UserLanguage'])) {
						$_SESSION['UserLanguage'] = $user->getVar('language');
					}
					icms::$user = $user;
				}
			}
			return $instance;
		});
	}
}
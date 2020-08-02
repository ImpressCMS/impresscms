<?php

namespace ImpressCMS\Core\Middlewares;

use icms_member_user_Object;
use ImpressCMS\Core\Response\ViewResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware that shows site closed screen when site is closed and user is not in correct group
 *
 * @package ImpressCMS\Core\Middlewares
 */
class SiteClosedMiddleware implements MiddlewareInterface
{

	/**
	 * @var array
	 */
	private $allowedGroups;
	/**
	 * @var string
	 */
	private $siteClosedText;
	/**
	 * @var string
	 */
	private $siteName;
	/**
	 * @var string
	 */
	private $slogan;

	/**
	 * SiteClosedMiddleware constructor.
	 *
	 * @param array $allowedGroups
	 * @param string $siteClosedText
	 * @param string $siteName
	 * @param string $slogan
	 */
	public function __construct(array $allowedGroups, string $siteClosedText, string $siteName, string $slogan)
	{
		$this->allowedGroups = $allowedGroups;
		$this->siteClosedText = $siteClosedText;
		$this->siteName = $siteName;
		$this->slogan = $slogan;
	}

	/**
	 * @inheritDoc
	 */
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$user = $request->getAttribute('user');

		$request->withAttribute('site-closed', true);

		if ($this->isUserGroupAllowed($user) || $request->getAttribute('ignore-site-closed', false)) {
			return $handler->handle($request);
		}

		return $this->makeSiteClosedResponse(
			$request->getAttribute('theme-set'),
			$request->getAttribute('module', null),
			$request->getUri()->getPath()
		);
	}

	/**
	 * Makes response for site closed view
	 *
	 * @param string $themeSet Current theme
	 * @param \icms_module_Object|null $module Current module
	 * @param string $requestUri Current request uri
	 *
	 * @return ViewResponse
	 */
	protected function makeSiteClosedResponse(string $themeSet, ?\icms_module_Object $module, string $requestUri): ViewResponse
	{
		$response = new ViewResponse([
			'template_canvas' => 'db:system_siteclosed.html'
		]);
		$response->assign('icms_theme', $themeSet);
		$response->assign('icms_imageurl', ICMS_THEME_URL . '/' . $themeSet. '/');
		$response->assign('icms_themecss', xoops_getcss($themeSet) );
		$response->assign('lang_login', _LOGIN );
		$response->assign('lang_username', _USERNAME );
		$response->assign('lang_password', _PASSWORD );
		$response->assign('lang_siteclosemsg', $this->siteClosedText );
		$response->assign('icms_slogan',  htmlspecialchars($this->slogan,ENT_QUOTES, _CHARSET) );
		$response->assign('icms_sitename', htmlspecialchars($this->siteName,ENT_QUOTES, _CHARSET) );
		$response->assign('icms_dirname', $module ? $module->dirname : 'system' );
		$response->assign('icms_pagetitle', $module ? $module->name : htmlspecialchars($this->slogan, ENT_QUOTES, _CHARSET) );
		$response->assign('icms_requesturi', htmlspecialchars($requestUri, ENT_QUOTES, _CHARSET) );

		return $response;
	}

	/**
	 * Checks if user has allowed group
	 *
	 * @param icms_member_user_Object|null $user User
	 *
	 * @return bool
	 */
	protected function isUserGroupAllowed(?icms_member_user_Object $user): bool {
		if (!$user) {
			return false;
		}
		foreach ($user->getGroups() as $group) {
			if (ICMS_GROUP_ADMIN === (int)$group || in_array($group, $this->allowedGroups, false)) {
				return true;
			}
		}
		return false;
	}
}
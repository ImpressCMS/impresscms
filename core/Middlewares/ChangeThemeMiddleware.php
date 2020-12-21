<?php

namespace ImpressCMS\Core\Middlewares;

use icms_member_user_Object;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Changes theme middleware from request (also adds theme_set attribute to request as current theme)
 *
 * @package ImpressCMS\Core\Middlewares
 */
class ChangeThemeMiddleware implements MiddlewareInterface
{
	/**
	 * @var array
	 */
	private $themeSetAllowed;

	/**
	 * ChangeThemeMiddleware constructor.
	 *
	 * @param array $themeSetAllowed
	 */
	public function __construct(array $themeSetAllowed)
	{
		$this->themeSetAllowed = $themeSetAllowed;
	}

	/**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
		global $icmsConfig;

		/**
		 * @var Aura\Session\Session $session
		 */
		$session = $request->getAttribute('session');
		$userSegment = $session->getSegment('user');

		$post = $request->getParsedBody();

		if (!empty($post['xoops_theme_select']) && in_array($post['xoops_theme_select'], $this->themeSetAllowed, true)) {
			$icmsConfig['theme_set'] = $post['xoops_theme_select'];
			$userSegment->set('theme', $post['xoops_theme_select']);
		} elseif (!empty($post['theme_select']) && in_array($post['theme_select'], $this->themeSetAllowed, true)) {
			$icmsConfig['theme_set'] = $post['theme_select'];
			$userSegment->set('theme', $post['theme_select']);
		} elseif ($userSegment->get('theme')
			&& in_array($userSegment->get('theme'), $this->themeSetAllowed, true)
		) {
			$icmsConfig['theme_set'] = $userSegment->get('theme');
		}

        return $handler->handle(
			$request->withAttribute(
				'theme-set',
				$icmsConfig['theme_set']
			)
		);
    }
}
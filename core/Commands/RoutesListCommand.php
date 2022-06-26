<?php

namespace ImpressCMS\Core\Commands;

use Sunrise\Http\Router\Command\RouteListCommand;
use Sunrise\Http\Router\Router;

class RoutesListCommand extends RouteListCommand
{

	public function __construct()
	{
		parent::__construct();
	}

	protected function getRouter() : Router
	{
		return \icms::getInstance()->get('router');
	}

}
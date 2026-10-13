<?php
// $Id: common.php 12400 2014-01-25 18:46:03Z skenow $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System //
// Copyright (c) 2000 XOOPS.org //
// <http://www.xoops.org/> //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version. //
// //
// You may not change or alter any portion of this comment or credits //
// of supporting developers from this source code or any supporting //
// source code which is considered copyrighted (c) material of the //
// original comment or credit authors. //
// //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
// //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------ //

/**
 * All common information used in the core goes from here.
 * Be careful while editing this file!
 *
 * @copyright http://www.impresscms.org/ The ImpressCMS Project
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package core
 * @version $Id: common.php 12400 2014-01-25 18:46:03Z skenow $
 */

/**
 * make sure mainfile is included, for security and functionality
 */
defined("XOOPS_MAINFILE_INCLUDED") or die();

// The boot sequence lives in Icms\Kernel\Bootstrap (stages: constants, autoload,
// services, output, language, themeSelection, siteState, module, online). It is
// loaded explicitly because the autoloader is only set up by its second stage.
require_once ICMS_ROOT_PATH . "/libraries/Icms/Kernel/Bootstrap.php";

$icmsBootstrap = new Icms\Kernel\Bootstrap(Icms\Kernel\Bootstrap::MODE_INTERACTIVE);
foreach (Icms\Kernel\Bootstrap::STAGES as $icmsBootStage) {
	try {
		$icmsBootstrap->run($icmsBootStage);
	} catch (Icms\Kernel\SiteClosedException $icmsBootException) {
		// Rendered at global scope: shows the closed-site page and exits, unless the
		// visitor may use the closed site.
		include ICMS_INCLUDE_PATH . "/site-closed.php";
		Icms\Kernel\Bootstrap::markCompleted($icmsBootStage);
	}
}
unset($icmsBootstrap, $icmsBootStage, $icmsBootException);

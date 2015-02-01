<?php
// $Id: xoopsmultimailer.php 12329 2013-09-19 13:53:36Z skenow $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Jochen B��nagel (job@buennagel.com)                               //
// URL:  http://www.xoops.org												 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

/**
 * Functions to extend PHPMailer to email the users
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	MultiMailer
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: xoopsmultimailer.php 12329 2013-09-19 13:53:36Z skenow $
 */
if (!defined("ICMS_ROOT_PATH")) {
	die("ImpressCMS root path not defined");
}

/**
 * Mailer Class.
 *
 * @deprecated	Use icms_messaging_EmailHandler, instead
 * @todo		Remove in version 1.4
 * @author		Jochen Buennagel	<job@buennagel.com>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 * @version		$Revision: 1083 $ - changed by $Author$ on $Date: 2007-10-16 12:42:51 -0400 (mar., 16 oct. 2007) $
 */
class XoopsMultiMailer extends icms_messaging_EmailHandler {

	private $_deprecated;

	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_messaging_EmailHandler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>

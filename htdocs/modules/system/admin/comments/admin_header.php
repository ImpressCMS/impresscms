<?php
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

/**
 * Administration of comments, Admin Header file
 *
 * Checks the rights of the user for being able to admin the comments
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Comments
 * @version		SVN: $Id$
 */

include '../../../../mainfile.php';
include ICMS_ROOT_PATH.'/include/cp_functions.php';
if (is_object(icms::$user)) {
	$module_handler = icms::handler('icms_module');
	$icmsModule =& $module_handler->getByDirname('system');
	if (!in_array(XOOPS_GROUP_ADMIN, icms::$user->getGroups())) {
		$sysperm_handler = icms::handler('icms_member_groupperm');
		if (!$sysperm_handler->checkRight('system_admin', XOOPS_SYSTEM_COMMENT, icms::$user->getGroups())) {
			redirect_header(ICMS_URL . '/', 3, _NOPERM);;
			exit();
		}
	}
} else {
	redirect_header(ICMS_URL . '/', 3, _NOPERM);
	exit();
}

<?php
// $Id: xoopstree.php 12278 2013-08-31 22:12:36Z fiammy $
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
 * XOOPS Block Class File
 *
 * @since 		XOOPS
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @copyright 	The XOOPS Project <http://www.xoops.org>
 * @author 		The XOOPS Project Community <http://www.xoops.org>
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 *
 * @version		$Id: xoopsblock.php 19425 2010-06-14 23:03:14Z skenow $
 *
 * @deprecated	use icms_core_Block class, instead - the file will be autoloaded
 * @todo		Remove from this file from the core on ImpressCMS 1.4
 */

if (!defined('ICMS_ROOT_PATH')) { exit(); }
icms_core_Debug::setDeprecated( 'class icms_core_Block', 'this file will be removed in ImpressCMS 1.4 - the classes are automatically loaded when instantiated' );
require_once ICMS_ROOT_PATH . '/kernel/block.php' ;

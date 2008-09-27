<?php
// $Id: fpdf.inc.php,v 1.1.2.4 2004/11/16 19:06:02 phppp Exp $
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

define('MP_FPDF_PATH',XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/fpdf');
define('FPDF_FONTPATH',MP_FPDF_PATH.'/font/');

require MP_FPDF_PATH.'/gif.php';
require MP_FPDF_PATH.'/fpdf.php';

if(is_readable(MP_FPDF_PATH.'/language/'.$xoopsConfig['language'].'.php')){
	include_once(MP_FPDF_PATH.'/language/'.$xoopsConfig['language'].'.php');
}elseif(is_readable(MP_FPDF_PATH.'/language/english.php')){
	include_once(MP_FPDF_PATH.'/language/english.php');
}else{
	die('No Language File Readable!');
}
include MP_FPDF_PATH.'/makepdf_class.php';
?>
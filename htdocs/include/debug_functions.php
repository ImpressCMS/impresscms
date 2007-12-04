<?php
// $Id: common.php,v 1.2 2007/06/26 18:51:34 marcan Exp $
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

function xoops_debug_display($msg, $exit=false)
{
	echo "<div style='padding: 5px; color: red; font-weight: bold'>debug :: $msg</div>";
	if ($exit) {
		die();
	}
}

function xoops_debug_dump($text)
{
	if (class_exists('MyTextSanitizer')) {
		$myts = MyTextSanitizer::getInstance();
		xoops_debug_display($myts->displayTarea(var_export($text, true)));
	} else {
		$text = var_export($text, true);
		$text = preg_replace("/(\015\012)|(\015)|(\012)/","<br />",$text);
		xoops_debug_display($text);
	}
}
?>
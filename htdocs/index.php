<?php
// $Id: index.php 2 2005-11-02 18:23:29Z skalpa $
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
/**
 * Catch new users and redirect them to the start page, if any
 * @copyright &copy; 2000 xoops.org
 **/
 
/**
 * redirects to installation, if xoops is not installed yet
 **/
include "mainfile.php";

//check if start page is defined
if ( isset($xoopsConfig['startpage']) && $xoopsConfig['startpage'] != "" && $xoopsConfig['startpage'] != "--" ) {
	header('Location: '.XOOPS_URL.'/modules/'.$xoopsConfig['startpage'].'/');
	exit();
} else {
	$xoopsOption['show_cblock'] =1;
	include "header.php";
	include "footer.php";
}
?>
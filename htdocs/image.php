<?php
// $Id: image.php 12313 2013-09-15 21:14:35Z skenow $
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
 * functions for image.
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		core
 * @since		XOOPS
 * @author		skalpa <psk@psykaos.net>
 * @version		$Id: image.php 12313 2013-09-15 21:14:35Z skenow $
 */

$image_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
if (empty($image_id)) {
	header("Content-type: image/gif");
	readfile(ICMS_UPLOAD_PATH . "/blank.gif");
	exit();
}

include "mainfile.php";
icms::$logger->disableLogger();

$criteria = icms_buildCriteria(array("i.image_display" => 1, "i.image_id" => $image_id));
$images = icms::handler("icms_image")->getObjects($criteria, FALSE, TRUE);

if (count($images) == 1 && $images[0]->getVar("image_body") !== NULL) {
	header("Content-type: ".$images[0]->getVar("image_mimetype"));
	header("Cache-control: max-age=31536000");
	header("Expires: " . gmdate("D, d M Y H:i:s", time() + 31536000) . "GMT");
	header("Content-disposition: filename=" . $images[0]->getVar("image_name"));
	header("Content-Length: " . strlen($images[0]->getVar("image_body")));
	header("Last-Modified: " . gmdate("D, d M Y H:i:s", $images[0]->getVar("image_created"))  . "GMT");
	echo $images[0]->getVar("image_body");
} else {
	header("Content-type: image/gif");
	readfile(ICMS_UPLOAD_PATH . "/blank.gif");
	exit();
}
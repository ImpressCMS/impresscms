<?php
// $Id: pda.php 2 2005-11-02 18:23:29Z skalpa $
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

include "mainfile.php";
header("Content-Type: text/html");

echo "<html><head><title>". htmlspecialchars($xoopsConfig['sitename'])."</title>
      <meta name='HandheldFriendly' content='True' />
      <meta name='PalmComputingPlatform' content='True' />
      </head>
      <body>";

$sql = "SELECT storyid, title FROM ".$xoopsDB->prefix("stories")." WHERE published>'0' AND published<'".time()."' ORDER BY published DESC";

$result = $xoopsDB->query($sql,10,0);

if (!$result) {
    echo "An error occured";
} else {
    echo "<img src='images/logo.gif' alt='".htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)."' border='0' /><br />";
    echo "<h2>".htmlspecialchars($xoopsConfig['slogan'])."</h2>";
    echo "<div>";
    while (list($storyid, $title) = $xoopsDB->fetchRow($result)) {
        echo "<a href='".XOOPS_URL."/modules/news/print.php?storyid=$storyid'>".htmlspecialchars($title)."</a><br />";

    }
    echo "</div>";
}

echo "</body></html>";

?>
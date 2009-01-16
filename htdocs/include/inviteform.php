<?php
// $Id$
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
// Author: Sudhaker Raj (http://sudhaker.com/)                               //
// Project: The ImpressCMS Project (http://www.impresscms.org/)              //
// ------------------------------------------------------------------------- //

if (!defined("XOOPS_ROOT_PATH")) {
    die("ImpressCMS root path not defined");
}
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$invite_form = new XoopsThemeForm(_US_USERINVITE, "userinvite", "invite.php", "post", true);
$invite_form->addElement(new XoopsFormText(_US_EMAIL, "email", 25, 60, $myts->htmlSpecialChars($email)), true);
$invite_form->addElement(new XoopsFormHidden("op", "finish"));
$invite_form->addElement(new XoopsFormButton("", "submit", _US_SUBMIT, "submit"));

?>
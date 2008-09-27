<?php
// ------------------------------------------------------------------------- //
//                       XOOPS - Module MP Manager                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //
//                 Votre nouveau systeme de messagerie priver                //
//                                                                           //
//                               "MP"                                        //
//                                                                           //
//                       http://lexode.info/mods                             //
//                                                                           //
//                                                                           //
//---------------------------------------------------------------------------//
include("../../../mainfile.php");
include_once(XOOPS_ROOT_PATH."/class/xoopsmodule.php");
include(XOOPS_ROOT_PATH."/include/cp_functions.php");
include '../../../include/cp_header.php';
include '../include/functions.php';
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include '../class/formselectuser.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgs.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgscat.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgscont.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgsopt.php';
include_once XOOPS_ROOT_PATH.'/modules/messenger/class/priv_msgsup.php';

if ($xoopsUser) {
  $xoopsModule = XoopsModule::getByDirname("messenger");
  if (!$xoopsUser->isAdmin($xoopsModule->mid())) {
    redirect_header(XOOPS_URL."/",3,_NOPERM);
    exit();
  }
}else{
  redirect_header(XOOPS_URL."/",3,_NOPERM);
  exit();
}

?>

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

/**
* cache_manager for XOOPS installer
*
* @author Haruki Setoyama  <haruki@planewave.org>
* @version $Id: cachemanager.php 2 2005-11-02 18:23:29Z skalpa $
* @access public
**/
class cache_manager {

    var $s_files = array();
    var $f_files = array();

    function write($file, $source){
        if (false != $fp = fopen(XOOPS_CACHE_PATH.'/'.$file, 'w')) {
            fwrite($fp, $source);
            fclose($fp);
            $this->s_files[] = $file;
        }else{
            $this->f_files[] = $file;
        }
    }

    function report(){
        $content = "<table align='center'><tr><td align='left'>\n";
        foreach($this->s_files as $val){
            $content .= _OKIMG.sprintf(_INSTALL_L123, "<b>$val</b>")."<br />\n";
        }
        foreach($this->f_files as $val){
            $content .= _NGIMG.sprintf(_INSTALL_L124, "<b>$val</b>")."<br />\n";
        }
        $content .= "</td></tr></table>\n";
        return $content;
    }

}


?>
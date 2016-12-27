<?php
/**
* Cache Manager Class
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	installer
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id: cachemanager.php 10648 2010-09-17 10:58:25Z m0nty_ $
*/

/**
* cache_manager for XOOPS installer
*
* @author Haruki Setoyama  <haruki@planewave.org>
* @version $Id: cachemanager.php 10648 2010-09-17 10:58:25Z m0nty_ $
* @access public
**/

error_reporting(0); // prevents path disclosure from null arrays when accessing class directly, comment to allow.

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
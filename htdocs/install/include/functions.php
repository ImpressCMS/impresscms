<?php
/** ---------------------------------------------------------------------------
*  $Id: functions.php 3539 2008-07-08 22:28:20Z pesian_stranger $
*  ---------------------------------------------------------------------------
*
*  Project: ImpressCMS <http://www.impresscms.org/>
*
*  ImpressCMS is derived from XOOPS 2.0.17.1
*  <http://www.xoops.org/> Copyright (c) 2000-2007 XOOPS.org
*  Subsequent changes and additions: Copyright (c) 2007 ImpressCMS
*
*  ---------------------------------------------------------------------------
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  You may not change or alter any portion of this comment or credits
*  of supporting developers from this source code or any supporting
*  source code which is considered copyrighted (c) material of the
*  original comment or credit authors.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
*  ---------------------------------------------------------------------------
*/

/**
* Functions needed by the ImpressCMS installer
*
* @copyright    The ImpressCMS Project http://www.impresscms.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @author		marcan <marcan@impresscms.org>
*/

/**
* Create a folder
*
* @author	Newbb2 developpement team
* @param	string	$target    folder being created
* @return   bool    Returns true on success, false on failure
*/
function icms_install_mkdir($target) {
	// http://www.php.net/manual/en/function.mkdir.php
	// saint at corenova.com
	// bart at cdasites dot com
	if (is_dir($target) || empty ($target)) {
		return true; // best case check first
	}
	if (file_exists($target) && !is_dir($target)) {
		return false;
	}
	if (icms_install_mkdir(substr($target, 0, strrpos($target, '/')))) {
		if (!file_exists($target)) {
			$res = mkdir($target, 0777); // crawl back up & create dir tree
			icms_install_chmod($target);
			return $res;
		}
	}
	$res = is_dir($target);
	return $res;
}
/**
* Change the permission of a file or folder
*
* @author	Newbb2 developpement team
* @param	string	$target  target file or folder
* @param	int		$mode    permission
* @return   bool    Returns true on success, false on failure
*/
function icms_install_chmod($target, $mode = 0777) {
	return @ chmod($target, $mode);
}

// ----- New Password System
function icms_createSalt($slength=64)
{
	$salt= '';
	$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$microtime = function_exists('microtime') ? microtime() : time();
    	srand((double)$microtime * 1000000);
    	for($i=0; $i<=$slength; $i++)
		$salt.= substr($base, rand() % strlen($base), 1);
    	return $salt;
}

function icms_encryptPass($adminpass, $adminsalt, $mainSalt)
{
	if(!function_exists('hash'))
    	{
		$pass = md5($adminpass);
    	}
	else
	{
		$pass = hash('sha256', $adminsalt.md5($adminpass).$mainSalt);
	}
	unset($mainSalt);
	return $pass;
}
// ----- End New Password System
?>
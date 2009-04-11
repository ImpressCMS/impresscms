<?php
/**
* Functions needed by the ImpressCMS installer
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	installer
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author		marcan <marcan@impresscms.org>
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id$
*/

/**
* Create a folder
*
* @author	Newbb2 developpement team
* @param	string	$target    folder being created
* @return   bool    Returns true on success, false on failure
*/
function imcms_install_mkdir($target) {
	// http://www.php.net/manual/en/function.mkdir.php
	// saint at corenova.com
	// bart at cdasites dot com
	if (is_dir($target) || empty ($target)) {
		return true; // best case check first
	}
	if (file_exists($target) && !is_dir($target)) {
		return false;
	}
	if (imcms_install_mkdir(substr($target, 0, strrpos($target, '/')))) {
		if (!file_exists($target)) {
			$res = mkdir($target, 0777); // crawl back up & create dir tree
			imcms_install_chmod($target);
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
function imcms_install_chmod($target, $mode = 0777) {
	return @ chmod($target, $mode);
}
// ----- New Password System
function imcms_createSalt($slength=64)
{
	$salt= '';
	$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$microtime = function_exists('microtime') ? microtime() : time();
    	srand((double)$microtime * 1000000);
    	for($i=0; $i<=$slength; $i++)
		$salt.= substr($base, rand() % strlen($base), 1);
    	return $salt;
}
function imcms_encryptPass($adminpass, $adminsalt, $mainSalt)
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
/**
 * Recursively delete a directory
 *
 * @param string $dir Directory name
 * @param boolean $deleteRootToo Delete specified top-level directory as well
 */
function unlinkRecursive($dir, $deleteRootToo=true)
{
    if(!$dh = @opendir($dir))
    {
        return;
    }
    while (false !== ($obj = readdir($dh)))
    {
        if($obj == '.' || $obj == '..')
        {
            continue;
        }

        if (!@unlink($dir . '/' . $obj))
        {
            unlinkRecursive($dir.'/'.$obj, true);
        }
    }

    closedir($dh);
   
    if ($deleteRootToo)
    {
        @rmdir($dir);
    }
   
    return;
} 
?>
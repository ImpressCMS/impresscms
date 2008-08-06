<?php
/**
 * Cache handlers
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		installer
 * @since		XOOPS
 * @author		http://www.xoops.org/ The XOOPS Project
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id:$
*/

if (!defined("CAPTCHA_FUNCTIONS_CACHE")):
define("CAPTCHA_FUNCTIONS_CACHE", true);

function mod_generateCacheId_byGroup($groups = null) {
	global $xoopsUser;
	
	if (!empty($groups) && is_array($groups)) {
	} elseif (is_object( $xoopsUser )) {
		$groups = $xoopsUser->getGroups();
	}
	if (!empty($groups) && is_array($groups)) {
		sort($groups);
		$contentCacheId = substr( md5(implode(",", $groups).XOOPS_DB_PASS.XOOPS_DB_NAME), 0, strlen(XOOPS_DB_USER) * 2 );
	} else {
		$contentCacheId = XOOPS_GROUP_ANONYMOUS;
	}
	
	return $contentCacheId;
}

function mod_generateCacheId($groups = null) {
    return mod_generateCacheId_byGroup($groups);
}

function mod_createFile($data, $name = null, $dirname = null, $root_path = XOOPS_CACHE_PATH)
{
    global $xoopsModule;

    $name = ($name) ? $name : strval(time());
    $dirname = ($dirname) ? $dirname : (is_object($xoopsModule) ? $xoopsModule->getVar("dirname", "n") : "system");

	$file_name = $dirname."_".$name.".php";
	$file = $root_path."/".$file_name;
	if ( $fp = fopen( $file , "wt" ) ) {
		fwrite( $fp, "<?php\nreturn " . var_export( $data, true ) . ";\n?>" );
		fclose( $fp );
	} else {
		xoops_error( "Cannot create file: ".$file_name );
	}
    return $file_name;
	
}

function mod_createCacheFile($data, $name = null, $dirname = null)
{
	return mod_createFile($data, $name, $dirname);
}

function mod_createCacheFile_byGroup($data, $name = null, $dirname = null, $groups = null)
{
	$name .= mod_generateCacheId_byGroup();
	return mod_createCacheFile($data, $name, $dirname);
}

function &mod_loadFile($name, $dirname = null, $root_path = XOOPS_CACHE_PATH)
{
    global $xoopsModule;

    $data = null;
    
    if (empty($name)) return $data;
    $dirname = ($dirname) ? $dirname : (is_object($xoopsModule) ? $xoopsModule->getVar("dirname", "n") : "system");
	$file_name = $dirname."_".$name.".php";
	$file = $root_path."/".$file_name;

	$data = @include $file;
	return $data;
}

function &mod_loadCacheFile($name, $dirname = null)
{
	$data = mod_loadFile($name, $dirname);
	return $data;
}

function &mod_loadCacheFile_byGroup($name, $dirname = null, $groups = null)
{
	$name .= mod_generateCacheId_byGroup();
	$data = mod_loadFile($name, $dirname);
	return $data;
}

/* Shall we use the function of glob for better performance ? */

function mod_clearFile($name = "", $dirname = null, $root_path = XOOPS_CACHE_PATH)
{
    global $xoopsModule;
    
    $pattern = ($dirname) ? "{$dirname}_{$name}.*\.php" : "[^_]+_{$name}.*\.php";
	if ($handle = opendir($root_path)) {
		while (false !== ($file = readdir($handle))) {
			if (is_file($root_path.'/'.$file) && preg_match("/^{$pattern}$/", $file)) {
				@unlink($root_path.'/'.$file);
			}
		}
		closedir($handle);
	}
	return true;
}

function mod_clearCacheFile($name = "", $dirname = null)
{
	return mod_clearFile($name, $dirname);
}

function mod_clearSmartyCache($pattern = "")
{
    global $xoopsModule;
    
    if (empty($pattern)) {
	    $dirname = (is_object($xoopsModule) ? $xoopsModule->getVar("dirname", "n") : "system");
	    $pattern = "/(^{$dirname}\^.*\.html$|blk_{$dirname}_.*[^\.]*\.html$)/";
    }
	if ($handle = opendir(XOOPS_CACHE_PATH)) {
		while (false !== ($file = readdir($handle))) {
			if (is_file(XOOPS_CACHE_PATH.'/'.$file) && preg_match($pattern, $file)) {
				@unlink(XOOPS_CACHE_PATH.'/'.$file);
			}
		}
		closedir($handle);
	}
	return true;
}

endif;
?>
<?php
/**
*
*
*
* @copyright		http://lexode.info/mods/ Venom (Original_Author)
* @copyright		Author_copyrights.txt
* @copyright		http://www.impresscms.org/ The ImpressCMS Project
* @license			http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package			modules
* @since			XOOPS
* @author			Venom <webmaster@exode-fr.com>
* @author			modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version			$Id$
*/


include "../../mainfile.php";
require_once ("include/functions.php");
$fileup = !empty($_GET['fileup']) ? $_GET['fileup'] : "";
global $xoopsUser, $xoopsModuleConfig;

if (empty($fileup) ) {
	redirect_header("javascript:history.go(-1)",1, _MP_ERREURDL);
	exit;
	}
	
$uid = $xoopsUser->getVar('uid');
if (empty($uid) ) {
	redirect_header("javascript:history.go(-1)",1, _PM_USERNOEXIST);
	exit;
	}
	


	$path = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/".$fileup."";
	$fp = fopen($path , "rb");
	//Si on arrive  lire le fichier on continue
	if ($fp) { 
		$fileName = basename ($path);
		if(ereg(".zip", $fileName)) $fileType = "application/x-zip-compressed";
		elseif(ereg(".rar", $fileName)) $fileType = "application/x-rar-compressed";
		else $fileType = "application/octet-stream";
		
		$fileLength = filesize($path);

		Header("Cache-control: private");
		Header("Pragma: no-cache");
		Header("Content-Type: $fileType");
		Header("Content-Transfer-Encoding: binary");
		Header("Content-Length: $fileLength");
		Header("Accept-Ranges: bytes");
		Header('Content-Disposition: attachment; filename="'.$fileName.'"');
		Header("Connection: close");
		
		while(!feof($fp)) {
			echo fread($fp, 4096);
		}
		fclose($fp);
	}
	//Sinon erreur : 
	else {
	redirect_header("javascript:history.go(-1)",1, _MP_ERREURDL);
	}

exit();

?>

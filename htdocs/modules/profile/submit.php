<?php
/**
 * Extended User Profile
 *
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          Marcello Brandao <marcello.brandao@gmail.com>
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

/**
 * Xoops header ...
 */
include_once 'header.php';
$xoopsOption['template_main'] = 'profile_index.html';

$modname = basename( dirname( __FILE__ ) );
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}


$myts =& MyTextSanitizer::getInstance();

/**
 * Factory of pictures created  
 */
$album_factory = icms_getmodulehandler('images', $modname, 'profile' );

/**
 * Getting the title 
 */
$title = $myts->htmlSpecialChars($_POST['caption']);

/**
 * Getting parameters defined in admin side  
 */
$path_upload    = ICMS_ROOT_PATH."/uploads";
$pictwidth      = $icmsModuleConfig['resized_width'];
$pictheight     = $icmsModuleConfig['resized_height'];
$thumbwidth     = $icmsModuleConfig['thumb_width'];
$thumbheight    = $icmsModuleConfig['thumb_height'];
$maxfilebytes   = $icmsModuleConfig['maxfilesize'];
$maxfileheight  = $icmsModuleConfig['max_original_height'];
$maxfilewidth   = $icmsModuleConfig['max_original_width'];

/**
 * If we are receiving a file  
 */
if ($_POST['xoops_upload_file'][0]=='sel_photo'){
       
              /**
              * Verify Token
              */
              if (!($GLOBALS['xoopsSecurity']->check())){
                     redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
              }
              ini_set('memory_limit', '50M');
              /**
              * Try to upload picture resize it insert in database and then redirect to index
              */
              if ($album_factory->receivePicture($title,$path_upload, $thumbwidth, $thumbheight, $pictwidth, $pictheight, $maxfilebytes,$maxfilewidth,$maxfileheight)){
                     $extra_tags['X_OWNER_NAME'] = $icmsUser->getVar('uname');
                     $extra_tags['X_OWNER_UID'] = $icmsUser->getVar('uid');
                     $notification_handler =& xoops_gethandler('notification');
                     $notification_handler->triggerEvent ("picture", $icmsUser->getVar('uid'), "new_picture",$extra_tags);
                     //header("Location: ".ICMS_URL."/modules/".$modname."/index.php?uid=".$icmsUser->getVar('uid'));
                     redirect_header(ICMS_URL."/modules/".$modname."/album.php?uid=".$icmsUser->getVar('uid'),3,_MD_PROFILE_UPLOADED);
              } else {
                     redirect_header(ICMS_URL."/modules/".$modname."/album.php?uid=".$icmsUser->getVar('uid'),3,_MD_PROFILE_NOCACHACA);
              }
}

/**
 * Close page  
 */
include("footer.php");
?>
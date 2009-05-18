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
$xoopsOption['template_main'] = 'profile_index.html';
include_once 'header.php';
$modname = basename( dirname( __FILE__ ) );
if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}

/**
 * Factory of pictures created  
 */
$audio_factory = icms_getmodulehandler('audio', $modname, 'profile' );;

$myts =& MyTextSanitizer::getInstance();	
/**
 * Getting the title 
 */
$title = $myts->displayTarea($_POST['title'],0,1,1,1,1);
$author = $myts->displayTarea($_POST['author'],0,1,1,1,1); 

/**
 * Getting parameters defined in admin side  
 */
$path_upload    = ICMS_ROOT_PATH.'/uploads/'.$modname.'/mp3/';
$maxfilebytes   = $icmsModuleConfig['maxfilesize'];

/**
 * If we are receiving a file  
 */
if ($_POST['xoops_upload_file'][0]=='sel_audio') {
       
              /**
              * Verify Token
              */
              if (!($GLOBALS['xoopsSecurity']->check())){
                     redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
              }
              
              /**
              * Try to upload picture resize it insert in database and then redirect to index
              */
              if ($audio_factory->receiveAudio($title,$path_upload, $author, $maxfilebytes)){
                     //$extra_tags['X_OWNER_NAME'] = $icmsUser->getVar('uname');
//                     $extra_tags['X_OWNER_UID'] = $icmsUser->getVar('uid');
//                     $notification_handler =& xoops_gethandler('notification');
//                     $notification_handler->triggerEvent ("picture", $icmsUser->getVar('uid'), "new_picture",$extra_tags);
                     //header("Location: ".ICMS_URL."/modules/".$modname."/index.php?uid=".$icmsUser->getVar('uid'));
                     redirect_header(ICMS_URL."/modules/".$modname."/audio.php?uid=".$icmsUser->getVar('uid'),5,_MD_PROFILE_UPLOADEDAUDIO);
              } else {
                     redirect_header(ICMS_URL."/modules/".$modname."/audio.php?uid=".$icmsUser->getVar('uid'),5,_MD_PROFILE_NOCACHACA);
              }
}

/**
 * Close page  
 */
include("footer.php");
?>
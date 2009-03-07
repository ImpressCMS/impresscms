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

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/');
	exit();
}

/**
 * Factories of tribes  
 */
$modname = basename( dirname( __FILE__ ) );
$reltribeuser_factory      = icms_getmodulehandler('reltribeuser', $modname, 'profile' );
$tribes_factory = icms_getmodulehandler('tribes', $modname, 'profile' );

$marker = (!empty($_POST['marker']))?1:0;

if ($marker==1) {
  /**
   * Verify Token
   */
  if (!($GLOBALS['xoopsSecurity']->check())){
	redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
  }
  /**
   * 
   */
  $myts =& MyTextSanitizer::getInstance();
  $tribe_title = $myts->displayTarea($_POST['tribe_title'],0,1,1,1,1);
  $tribe_desc  = $myts->displayTarea($_POST['tribe_desc'],0,1,1,1,1);
  $tribe_img   = (!empty($_POST['tribe_img'])) ? $_POST['tribe_img'] : "";
  $path_upload    = ICMS_ROOT_PATH."/uploads";
  $pictwidth      = $xoopsModuleConfig['resized_width'];
  $pictheight     = $xoopsModuleConfig['resized_height'];
  $thumbwidth     = $xoopsModuleConfig['thumb_width'];
  $thumbheight    = $xoopsModuleConfig['thumb_height'];
  $maxfilebytes   = $xoopsModuleConfig['maxfilesize'];
  $maxfileheight  = $xoopsModuleConfig['max_original_height'];
  $maxfilewidth   = $xoopsModuleConfig['max_original_width'];
  if ($tribes_factory->receiveTribe($tribe_title,$tribe_desc,'',$path_upload,$maxfilebytes,$maxfilewidth,$maxfileheight)) {
    $reltribeuser = $reltribeuser_factory->create();
    $reltribeuser->setVar('rel_tribe_id',$xoopsDB->getInsertId());
    $reltribeuser->setVar('rel_user_uid',$xoopsUser->getVar('uid'));
    $reltribeuser_factory->insert($reltribeuser);
    redirect_header("tribes.php",5,_MD_PROFILE_TRIBE_CREATED);
  } else {
    $tribes_factory->renderFormSubmit($maxfilebytes,$xoopsTpl);
  }
} else {
  $tribes_factory->renderFormSubmit($maxfilebytes,$xoopsTpl);
}      

include("footer.php");
?>
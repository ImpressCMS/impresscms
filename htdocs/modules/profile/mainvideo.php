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

include_once 'header.php';
include_once ICMS_ROOT_PATH.'/class/criteria.php';

$modname = basename( dirname( __FILE__ ) );
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}


if(!($GLOBALS['xoopsSecurity']->check())) {
  redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
}

$cod_img = intval($_POST['video_id']);

/**
* Creating the factory  loading the video changing its caption
*/
$video_factory = icms_getmodulehandler('video', $modname, 'profile' );
$video = $video_factory->create(false);
$video->load($cod_img);
$video->setVar('main_video',1);

/**
* Verifying who's the owner to allow changes
*/
$uid = intval($icmsUser->getVar('uid'));
if($uid == $video->getVar('uid_owner')) {
	if($video_factory->unsetAllMainsbyID($uid))	{
		if($video_factory->insert($video)) {
		  redirect_header('video.php', 2, _MD_PROFILE_SETMAINVIDEO);
		} else {
		  redirect_header('video.php', 2, _MD_PROFILE_NOCACHACA);
		}
	} else {
	  echo "nao deu certo";
	}
}

include 'footer.php';
?>
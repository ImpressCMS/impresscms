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
if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}


$cod_img = intval($_POST['video_id']);
$marker = (!empty($_POST['marker'])) ? intval($_POST['marker']) : 0;

$uid = intval($icmsUser->getVar('uid'));

if($marker==1) {
    if(!($GLOBALS['xoopsSecurity']->check())) {
      redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
    }
	/**
	* Creating the factory  loading the picture changing its caption
	*/
	$video_factory = icms_getmodulehandler('video', $modname, 'profile' );
	$video = $video_factory->create(false);
	$video->load($cod_img);
	$video->setVar('video_desc',trim(htmlspecialchars($_POST['caption'])));
	
	/**
	* Verifying who's the owner to allow changes
	*/
	if($uid == $video->getVar('uid_owner'))	{
		if($video_factory->insert($video)) {
		  redirect_header('video.php?uid='.$uid, 2, _MD_PROFILE_DESC_EDITED);
		} else {
		  redirect_header('index.php?uid='.$uid, 2, _MD_PROFILE_NOCACHACA);
		}
	}
}
/**
* Creating the factory  and the criteria to edit the desc of the picture
* The user must be the owner
*/ 
$album_factory = icms_getmodulehandler('video', $modname, 'profile' );
$criteria_video = new Criteria('video_id',$cod_img);
$criteria_uid = new Criteria('uid_owner',$uid);
$criteria = new CriteriaCompo($criteria_video);
$criteria->add($criteria_uid);

/**
* Lets fetch the info of the pictures to be able to render the form
* The user must be the owner
*/
if($array_pict = $album_factory->getObjects($criteria)){
	$caption = $array_pict[0]->getVar('video_desc');
	$url = $array_pict[0]->getVar('youtube_code');
}

$album_factory->renderFormEdit($caption,$cod_img,$url);

include 'footer.php';
?>
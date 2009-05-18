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


if(!($GLOBALS['xoopsSecurity']->check())) {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);}

$cod_video = $_POST['cod_video'];

$confirm = isset($_POST['confirm'])? intval($_POST['confirm']):'';
if($confirm != 1){
	xoops_confirm(array('cod_video'=> $cod_video,'confirm'=>1), 'delvideo.php', _MD_PROFILE_ASKCONFIRMVIDEODELETION, _MD_PROFILE_CONFIRMVIDEODELETION);
}else{
	/**
	* Creating the factory  and the criteria to delete the picture
	* The user must be the owner
	*/
	$album_factory = icms_getmodulehandler('video', $modname, 'profile' );
	$criteria_img = new Criteria('video_id',$cod_video);
	$uid = intval($icmsUser->getVar('uid'));
	$criteria_uid = new Criteria('uid_owner',$uid);
	$criteria = new CriteriaCompo($criteria_img);
	$criteria->add($criteria_uid);
	
	/**
	* Try to delete  
	*/
	if($album_factory->deleteAll($criteria)) {redirect_header('video.php?uid='.$uid, 2, _MD_PROFILE_VIDEODELETED);}
	else {redirect_header('video.php?uid='.$uid, 2, _MD_PROFILE_NOCACHACA);}
}

include 'footer.php';
?>
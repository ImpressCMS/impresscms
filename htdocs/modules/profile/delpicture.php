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
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/');
	exit();
}

$modname = basename( dirname( __FILE__ ) );

if(!($GLOBALS['xoopsSecurity']->check())) {
  redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
}

$cod_img = $_POST['cod_img'];

if(!isset($_POST['confirm']) || $_POST['confirm']!=1){
	xoops_confirm(array('cod_img'=> $cod_img,'confirm'=>1), 'delpicture.php', _MD_PROFILE_ASKCONFIRMDELETION, _MD_PROFILE_CONFIRMDELETION);
}else{
	/**
	* Creating the factory  and the criteria to delete the picture
	* The user must be the owner
	*/
	$album_factory = icms_getmodulehandler('images', $modname, 'profile' );
	$criteria_img = new Criteria('cod_img',$cod_img);
	$uid = intval($xoopsUser->getVar('uid'));
	$criteria_uid = new Criteria('uid_owner',$uid);
	$criteria = new CriteriaCompo($criteria_img);
	$criteria->add($criteria_uid);
	
	$objects_array = $album_factory->getObjects($criteria);
	$image_name = $objects_array[0]->getVar('url');  
	$avatar_image = $xoopsUser->getVar('user_avatar');
	
	/**
	* Try to delete  
	*/
	if($album_factory->deleteAll($criteria))
	{
		if($xoopsModuleConfig['physical_delete']==1)
		{
			//unlink($xoopsModuleConfig['path_upload']."\/".$image_name);
			unlink(ICMS_ROOT_PATH.'/uploads'.'/'.$image_name);
			unlink(ICMS_ROOT_PATH.'/uploads'.'/resized_'.$image_name);
			/**
			* Delete the thumb (avatar now has another name)
			*/ 
			//if ($avatar_image!=$image_name){
			unlink(ICMS_ROOT_PATH.'/uploads'.'/thumb_'.$image_name);
			//}
		}
		redirect_header('album.php', 2, _MD_PROFILE_DELETED);
	}
	else {redirect_header('album.php', 2, _MD_PROFILE_NOCACHACA);}
}

include 'footer.php';
?>
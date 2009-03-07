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

$cod_img = $_POST['cod_img'];
$marker = (!empty($_POST['marker'])) ? intval($_POST['marker']):0;
$uid = intval($xoopsUser->getVar('uid'));

if($marker==1){
    if(!($GLOBALS['xoopsSecurity']->check())) {
      redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
    }
	/**
	* Creating the factory loading the picture changing its caption
	*/
	$picture_factory = icms_getmodulehandler('images', $modname, 'profile' );
	$picture = $picture_factory->create(false);
	$picture->load($cod_img);
	$picture->setVar('title', trim(htmlspecialchars($_POST['caption'])));
	
	/**
	* Verifying who's the owner to allow changes
	*/
	if($uid == $picture->getVar('uid_owner'))	{
		if($picture_factory->insert($picture)) {redirect_header('album.php', 2, _MD_PROFILE_DESC_EDITED);}
		else {redirect_header('album.php', 2, _MD_PROFILE_NOCACHACA);}
	}
}
/**
* Creating the factory  and the criteria to edit the desc of the picture
* The user must be the owner
*/ 
$album_factory = icms_getmodulehandler('images', $modname, 'profile' );
$criteria_img = new Criteria ('cod_img', $cod_img);
$criteria_uid = new Criteria ('uid_owner',$uid);
$criteria = new CriteriaCompo ($criteria_img);
$criteria->add($criteria_uid);

/**
* Lets fetch the info of the pictures to be able to render the form
* The user must be the owner
*/
if($array_pict = $album_factory->getObjects($criteria)){
	$caption = $array_pict[0]->getVar('title');
	$url = $array_pict[0]->getVar('url');
}
//$url = $xoopsModuleConfig['link_path_upload']."/thumb_".$url;
$url = ICMS_URL.'/uploads/thumb_'.$url;
$album_factory->renderFormEdit($caption,$cod_img,$url);

include 'footer.php';
?>
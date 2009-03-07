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

if(!($GLOBALS['xoopsSecurity']->check())) {
  redirect_header('index.php', 3, _MD_PROFILE_TOKENEXPIRED);
}

/**
* Creating the factory  loading the picture changing its caption
*/
$picture_factory = icms_getmodulehandler('images', $modname, 'profile' );
$picture = $picture_factory->create(false);
$picture->load($_POST['cod_img']);

$uid = intval($xoopsUser->getVar('uid'));

$image = ICMS_ROOT_PATH.'/uploads/'.'thumb_'.$picture->getVar('url');
$avatar = 'av'.$uid.'_'.time().'.jpg';
$imageavatar = ICMS_ROOT_PATH.'/uploads/'.$avatar;

if(!copy($image, $imageavatar)) {
  echo 'failed to copy $file...\n';
}
$xoopsUser->setVar('user_avatar',$avatar);
$userHandler = new XoopsUserHandler($xoopsDB);
/**
* Verifying who's the owner to allow changes
*/
if($uid == $picture->getVar('uid_owner')) {
	if($userHandler->insert($xoopsUser)) {
	  redirect_header('album.php', 2, _MD_PROFILE_AVATAR_EDITED);
	} else {
	  redirect_header('album.php', 2, _MD_PROFILE_NOCACHACA);
	}
}
include 'footer.php';
?>
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

if(!($GLOBALS['xoopsSecurity']->check())) {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);}

$cod_img = $_POST['cod_img'];

/**
* Creating the factory  loading the picture changing its caption
*/
$picture_factory = icms_getmodulehandler('images', $modname, 'profile' );
$picture = $picture_factory->create(false);
$picture->load($cod_img);
$picture->setVar('private', intval($_POST['private']));

/**
* Verifying who's the owner to allow changes
*/
$uid = intval($xoopsUser->getVar('uid'));
if($uid == $picture->getVar('uid_owner')){
	if($picture_factory->insert($picture))
	{
		if($_POST['private']==1) {redirect_header('album.php', 2, _MD_PROFILE_PRIVATIZED);}
		else {redirect_header('album.php', 2, _MD_PROFILE_UNPRIVATIZED);}
	}
	else {redirect_header('album.php', 2, _MD_PROFILE_NOCACHACA);}
}

include 'footer.php';
?>
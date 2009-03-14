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
$modname = basename( dirname( __FILE__ ) );
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}

include_once ICMS_ROOT_PATH.'/class/criteria.php';

if(!($GLOBALS['xoopsSecurity']->check())) {
  redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
}

/**
* Receiving info from get parameters  
*/ 
$cod_audio = $_POST['cod_audio'];
if(!isset($_POST['confirm']) || $_POST['confirm']!=1) {
	xoops_confirm(array('cod_audio'=> $cod_audio,'confirm'=>1), 'delaudio.php', _MD_PROFILE_ASKCONFIRMAUDIODELETION, _MD_PROFILE_CONFIRMAUDIODELETION);
} else {
	/**
	* Creating the factory  and the criteria to delete the picture
	* The user must be the owner
	*/  
	$audio_factory = icms_getmodulehandler('audio', $modname, 'profile' );
	$criteria_aud = new Criteria('audio_id',$cod_audio);
	$uid = intval($xoopsUser->getVar('uid'));
	$criteria_uid = new Criteria('uid_owner',$uid);
	$criteria = new CriteriaCompo($criteria_aud);
	$criteria->add($criteria_uid);
	
	$objects_array = $audio_factory->getObjects($criteria);
	$audio_name = $objects_array[0]->getVar('url');
	
	/**
	* Try to delete  
	*/
	if($audio_factory->deleteAll($criteria)) 	{
		unlink(ICMS_ROOT_PATH.'/uploads/'.$modname.'/mp3/'.$audio_name);
		redirect_header('audio.php', 2, _MD_PROFILE_AUDIODELETED);
	} else {
	  redirect_header('audio.php', 2, _MD_PROFILE_NOCACHACA);
	}
}

include 'footer.php';
?>
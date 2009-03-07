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
$xoopsOption['template_main'] = 'profile_index.html';

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/');
	exit();
}

/**
* Factory of pictures created  
*/
$modname = basename( dirname( __FILE__ ) );
$album_factory = icms_getmodulehandler('video', $modname, 'profile' );

$url = $_POST['codigo'];

if(!($GLOBALS['xoopsSecurity']->check())) {redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);}

/**
* Try to upload picture resize it insert in database and then redirect to index
*/
$newvideo = $album_factory->create(true);
$newvideo->setVar('uid_owner', intval($xoopsUser->getVar('uid')));
$newvideo->setVar('video_desc', trim(htmlspecialchars($_POST['caption'])));

if(strlen($url)==11) {$code=$url;}
else
{
	$position_of_code = strpos($url,'v=');
	$code = substr($url,$position_of_code+2,11);
}

$newvideo->setVar('youtube_code',$code);
if($album_factory->insert($newvideo))
{
	$extra_tags['X_OWNER_NAME'] = $xoopsUser->getVar('uname');
	$extra_tags['X_OWNER_UID'] = intval($xoopsUser->getVar('uid'));
	$notification_handler =& xoops_gethandler('notification');
	$notification_handler->triggerEvent('video', intval($xoopsUser->getVar('uid')), 'new_video',$extra_tags);
	redirect_header(ICMS_URL.'/modules/profile/video.php?uid='.intval($xoopsUser->getVar('uid')),2,_MD_PROFILE_VIDEOSAVED);
}
else {redirect_header(ICMS_URL.'/modules/profile/video.php?uid='.intval($xoopsUser->getVar('uid')),2,_MD_PROFILE_NOCACHACA);}

include 'footer.php';
?>
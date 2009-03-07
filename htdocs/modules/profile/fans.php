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

$profile_template = 'profile_fans.html';
include_once 'header.php';

$uid = !empty($_GET['uid'])?intval($_GET['uid']):'';

if ($uid <= 0) {
	if(is_object($xoopsUser)){
		$uid = $xoopsUser->getVar('uid');
	}else{
		header('location: '.ICMS_URL);
		exit();
	}
}

if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/profile/userinfo.php?uid='.$uid);
	exit();
}


$controler = new ProfileControlerFriends($xoopsDB,$xoopsUser);
$nbSections = $controler->getNumbersSections();

$start = (isset($_GET['start']))? intval($_GET['start']) : 0;

/**
* Friends 
*/
$criteria_friends = new criteria('friend2_uid',$controler->uidOwner);
$criteria_fans = new criteria('fan',1);
$criteria_compo_fans = new CriteriaCompo($criteria_friends);
$criteria_compo_fans->add($criteria_fans);
$nb_friends = $controler->friendships_factory->getCount($criteria_compo_fans);
$criteria_compo_fans->setLimit($xoopsModuleConfig['friendsperpage']);
$criteria_compo_fans->setStart($start);
$vetor = $controler->friendships_factory->getFans('',$criteria_compo_fans,0);
if($nb_friends ==0) {$xoopsTpl->assign('lang_nofansyet',_MD_PROFILE_NOFANSYET);}

/**
* Let's get the user name of the owner of the album
*/
$owner = new XoopsUser();
$identifier = $owner->getUnameFromId($controler->uidOwner);

/**
* Criando a barra de navegao caso tenha muitos amigos
*/
$barra_navegacao = new XoopsPageNav($nb_friends,$xoopsModuleConfig['friendsperpage'],$start,'start','uid='.intval($controler->uidOwner));
$navegacao = $barra_navegacao->renderImageNav(2);

//permissions
$xoopsTpl->assign('allow_scraps',$controler->checkPrivilegeBySection('scraps'));
$xoopsTpl->assign('allow_friends',$controler->checkPrivilegeBySection('friends'));
$xoopsTpl->assign('allow_tribes',$controler->checkPrivilegeBySection('tribes'));
$xoopsTpl->assign('allow_pictures',$controler->checkPrivilegeBySection('pictures'));
$xoopsTpl->assign('allow_videos',$controler->checkPrivilegeBySection('videos'));
$xoopsTpl->assign('allow_audios',$controler->checkPrivilegeBySection('audio'));

//Owner data
$xoopsTpl->assign('uid_owner',$controler->uidOwner);
$xoopsTpl->assign('owner_uname',$controler->nameOwner);
$xoopsTpl->assign('isOwner',$controler->isOwner);
$xoopsTpl->assign('isanonym',$controler->isAnonym);

//numbers
$xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);
$xoopsTpl->assign('nb_photos',$nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos',$nbSections['nbVideos']);
$xoopsTpl->assign('nb_scraps',$nbSections['nbScraps']);
$xoopsTpl->assign('nb_friends',$nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio',$nbSections['nbAudio']); 

//navbar
$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYFRIENDS);
$xoopsTpl->assign('section_name',_MD_PROFILE_FRIENDS);

//barra de navega��o
$xoopsTpl->assign('navegacao',$navegacao);  

//page atributes
$xoopsTpl->assign('xoops_pagetitle', sprintf(_MD_PROFILE_PAGETITLE,$xoopsModule->getVar('name'), $controler->nameOwner));

$xoopsTpl->assign('lang_fanstitle',  sprintf(_MD_PROFILE_FANSTITLE,$identifier));
//$xoopsTpl->assign('path_profile_uploads',$xoopsModuleConfig['link_path_upload']);

$xoopsTpl->assign('friends',$vetor);

$xoopsTpl->assign('lang_delete',_MD_PROFILE_DELETE );
$xoopsTpl->assign('lang_evaluate',_MD_PROFILE_FRIENDSHIPCONFIGS );

include 'footer.php';
?>
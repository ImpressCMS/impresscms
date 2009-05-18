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

$profile_template = 'profile_tribes.html';
include_once("header.php");

$uid = !empty($_GET['uid'])?intval($_GET['uid']):'';

if ($uid <= 0) {
	if(is_object($icmsUser)){
		$uid = $icmsUser->getVar('uid');
	}else{
		header('location: '.ICMS_URL);
		exit();
	}
}

$modname = basename( dirname( __FILE__ ) );
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/userinfo.php?uid='.$uid);
	exit();
}

$controler = new ProfileControlerTribes($xoopsDB,$icmsUser);
$nbSections = $controler->getNumbersSections();

$start_all = (isset($_GET['start_all']))? intval($_GET['start_all']) : 0;
$start_my = (isset($_GET['start_my']))? intval($_GET['start_my']) : 0;

/**
* All Tribes 
*/
$criteria_tribes = new criteria('tribe_id',0,'>');
$nb_tribes = $controler->tribes_factory->getCount($criteria_tribes);
$criteria_tribes->setLimit($icmsModuleConfig['tribesperpage']);
$criteria_tribes->setStart($start_all);
$tribes = $controler->tribes_factory->getTribes($criteria_tribes);

/**
* My Tribes 
*/
$mytribes = '';
$criteria_mytribes = new criteria('rel_user_uid', $controler->uidOwner);
$nb_mytribes = $controler->reltribeusers_factory->getCount($criteria_mytribes);
$criteria_mytribes->setLimit($icmsModuleConfig['tribesperpage']);
$criteria_mytribes->setStart($start_my);
$mytribes = $controler->reltribeusers_factory->getTribes('', $criteria_mytribes,0);

/**
* Criando a barra de navegao caso tenha muitos amigos
*/
$barra_navegacao = new XoopsPageNav($nb_tribes,$icmsModuleConfig['tribesperpage'],$start_all,'start_all','uid='.intval($controler->uidOwner).'&amp;start_my='.$start_my);
$barrinha = $barra_navegacao->renderImageNav(2);//alltribes

$barra_navegacao_my = new XoopsPageNav($nb_mytribes,$icmsModuleConfig['tribesperpage'],$start_my,'start_my','uid='.intval($controler->uidOwner).'&amp;start_all='.$start_all);
$barrinha_my = $barra_navegacao_my->renderImageNav(2);

$maxfilebytes = $icmsModuleConfig['maxfilesize'];

//permissions
$xoopsTpl->assign('allow_scraps',$controler->checkPrivilegeBySection('scraps'));
$xoopsTpl->assign('allow_friends',$controler->checkPrivilegeBySection('friends'));
$xoopsTpl->assign('allow_tribes',$controler->checkPrivilegeBySection('tribes'));
$xoopsTpl->assign('allow_pictures',$controler->checkPrivilegeBySection('pictures'));
$xoopsTpl->assign('allow_videos',$controler->checkPrivilegeBySection('videos'));
$xoopsTpl->assign('allow_audios',$controler->checkPrivilegeBySection('audio'));

//form
$xoopsTpl->assign('lang_youcanupload',sprintf(_MD_PROFILE_YOUCANUPLOAD,$maxfilebytes/1024));
$xoopsTpl->assign('lang_tribeimage',_MD_PROFILE_TRIBE_IMAGE);
$xoopsTpl->assign('maxfilesize',$maxfilebytes);
$xoopsTpl->assign('lang_title',_MD_PROFILE_TRIBE_TITLE);
$xoopsTpl->assign('lang_description',_MD_PROFILE_TRIBE_DESC);
$xoopsTpl->assign('lang_savetribe',_MD_PROFILE_UPLOADTRIBE);

//Owner data
$xoopsTpl->assign('uid_owner',$controler->uidOwner);
$xoopsTpl->assign('owner_uname',$controler->nameOwner);
$xoopsTpl->assign('isOwner',$controler->isOwner);
$xoopsTpl->assign('isanonym',$controler->isAnonym);

//numbers
//$xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);look at hte end for this nb
$xoopsTpl->assign('nb_photos',$nbSections['nbPhotos']);
$xoopsTpl->assign('nb_videos',$nbSections['nbVideos']);
$xoopsTpl->assign('nb_scraps',$nbSections['nbScraps']);
$xoopsTpl->assign('nb_friends',$nbSections['nbFriends']);
$xoopsTpl->assign('nb_audio',$nbSections['nbAudio']);
$xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);

//navbar
$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYTRIBES);
$xoopsTpl->assign('section_name',_MD_PROFILE_TRIBES);

//page atributes
$xoopsTpl->assign('xoops_pagetitle', sprintf(_MD_PROFILE_PAGETITLE,$icmsModule->getVar('name'), $controler->nameOwner));

//$xoopsTpl->assign('path_profile_uploads',$icmsModuleConfig['link_path_upload']);
$xoopsTpl->assign('tribes',$tribes);
$xoopsTpl->assign('mytribes',$mytribes);
$xoopsTpl->assign('lang_mytribestitle',_MD_PROFILE_MYTRIBES);
$xoopsTpl->assign('lang_tribestitle',_MD_PROFILE_ALLTRIBES.' ('.$nb_tribes.')');
$xoopsTpl->assign('lang_notribesyet',_MD_PROFILE_NOTRIBESYET);

//page nav
$xoopsTpl->assign('barra_navegacao',$barrinha);//alltribes
$xoopsTpl->assign('barra_navegacao_my',$barrinha_my);
$xoopsTpl->assign('nb_tribes',$nb_mytribes);// this is the one wich shows in the upper bar actually is about the mytribes
$xoopsTpl->assign('nb_tribes_all',$nb_tribes);//this is total number of tribes

$xoopsTpl->assign('lang_createtribe',_MD_PROFILECREATEYOURTRIBE);
$xoopsTpl->assign('lang_owner',_MD_PROFILE_TRIBEOWNER);
$xoopsTpl->assign('lang_abandontribe',_MD_PROFILE_TRIBE_ABANDON);
$xoopsTpl->assign('lang_jointribe',_MD_PROFILE_TRIBE_JOIN);
$xoopsTpl->assign('lang_searchtribe',_MD_PROFILE_TRIBE_SEARCH);
$xoopsTpl->assign('lang_tribekeyword',_MD_PROFILE_TRIBE_SEARCHKEYWORD);

include 'footer.php';
?>
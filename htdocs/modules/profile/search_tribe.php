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

$profile_template = 'profile_tribes_results.html';
include_once("header.php");

$modname = basename( dirname( __FILE__ ) );
if($moduleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}


$controler = new ProfileControlerTribes($xoopsDB,$xoopsUser);
$nbSections = $controler->getNumbersSections();

$start_all = (isset($_GET['start_all']))? intval($_GET['start_all']) : 0;
$start_my = (isset($_GET['start_my']))? intval($_GET['start_my']) : 0;

$tribe_keyword = $myts->stripSlashesGPC($myts->htmlSpecialChars($_GET['tribe_keyword']));

/**
* All Tribes 
*/
$criteria_title = new criteria('tribe_title','%'.$tribe_keyword.'%','LIKE');
$criteria_desc = new criteria('tribe_desc','%'.$tribe_keyword.'%','LIKE');
$criteria_tribes = new CriteriaCompo($criteria_title);
$criteria_tribes->add($criteria_desc,'OR');
$nb_tribes = $controler->tribes_factory->getCount($criteria_tribes);
$criteria_tribes->setLimit($xoopsModuleConfig['tribesperpage']);
$criteria_tribes->setStart($start_all);
$tribes_objects = $controler->tribes_factory->getObjects($criteria_tribes);
$i = 0;
foreach($tribes_objects as $tribe_object)
{
	$tribes[$i]['id'] = $tribe_object->getVar('tribe_id');
	$tribes[$i]['title'] = $tribe_object->getVar('tribe_title');
	$tribes[$i]['img'] = $tribe_object->getVar('tribe_img');
	$tribes[$i]['desc'] = $tribe_object->getVar('tribe_desc');
	$tribes[$i]['uid'] = $tribe_object->getVar('owner_uid');
	$i++;
}

/**
* Criando a barra de navegao caso tenha muitos amigos
*/
$barra_navegacao = new XoopsPageNav($nb_tribes,$xoopsModuleConfig['tribesperpage'],$start_all,'start_all','tribe_keyword='.$tribe_keyword.'&amp;start_my='.$start_my);
$barrinha = $barra_navegacao->renderImageNav(2);

//permissions
$xoopsTpl->assign('allow_scraps',$controler->checkPrivilegeBySection('scraps'));
$xoopsTpl->assign('allow_friends',$controler->checkPrivilegeBySection('friends'));
$xoopsTpl->assign('allow_tribes',$controler->checkPrivilegeBySection('tribes'));
$xoopsTpl->assign('allow_pictures',$controler->checkPrivilegeBySection('pictures'));
$xoopsTpl->assign('allow_videos',$controler->checkPrivilegeBySection('videos'));
$xoopsTpl->assign('allow_audios',$controler->checkPrivilegeBySection('audio'));

//form
//$xoopsTpl->assign('lang_youcanupload',sprintf(_MD_PROFILE_YOUCANUPLOAD,$maxfilebytes/1024));
$xoopsTpl->assign('lang_tribeimage',_MD_PROFILE_TRIBE_IMAGE);
//$xoopsTpl->assign('maxfilesize',$maxfilebytes);
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
$xoopsTpl->assign('nb_tribes',$nbSections['nbTribes']);
$xoopsTpl->assign('nb_audio',$nbSections['nbAudio']); 

//navbar
$xoopsTpl->assign('lang_mysection',_MD_PROFILE_MYTRIBES);
$xoopsTpl->assign('section_name',_MD_PROFILE_TRIBES);

//page atributes
$xoopsTpl->assign('xoops_pagetitle',  sprintf(_MD_PROFILE_PAGETITLE,$xoopsModule->getVar('name'), $controler->nameOwner));

//$xoopsTpl->assign('path_profile_uploads',$xoopsModuleConfig['link_path_upload']);
//$xoopsTpl->assign('tribes',$tribes);
//$xoopsTpl->assign('mytribes',$mytribes);
$xoopsTpl->assign('lang_mytribestitle',_MD_PROFILE_MYTRIBES);
$xoopsTpl->assign('lang_tribestitle',_MD_PROFILE_ALLTRIBES.' ('.$nb_tribes.')');
$xoopsTpl->assign('lang_notribesyet',_MD_PROFILE_NOTRIBESYET);

//page nav
$xoopsTpl->assign('barra_navegacao',$barrinha);
//$xoopsTpl->assign('barra_navegacao_my',$barrinha_my);
//$xoopsTpl->assign('nb_tribes',$nb_mytribes);// this is the one wich shows in the upper bar actually is about the mytribes
$xoopsTpl->assign('nb_tribes_all',$nb_tribes);//this is total number of tribes

$xoopsTpl->assign('lang_createtribe',_MD_PROFILECREATEYOURTRIBE);
$xoopsTpl->assign('lang_owner',_MD_PROFILE_TRIBEOWNER);
$xoopsTpl->assign('lang_abandontribe',_MD_PROFILE_TRIBE_ABANDON);
$xoopsTpl->assign('lang_jointribe',_MD_PROFILE_TRIBE_JOIN);
$xoopsTpl->assign('lang_searchtribe',_MD_PROFILE_TRIBE_SEARCH);
$xoopsTpl->assign('lang_tribekeyword',_MD_PROFILE_TRIBE_SEARCHKEYWORD);

include 'footer.php';
?>
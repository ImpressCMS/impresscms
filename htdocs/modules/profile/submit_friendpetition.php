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

/**
 * Xoops header ...
 */
$xoopsOption['template_main'] = 'profile_index.html';
include_once 'header.php';

$modname = basename( dirname( __FILE__ ) );
if($icmsModuleConfig['profile_social']==0){
	header('Location: '.ICMS_URL.'/modules/'.$modname.'/');
	exit();
}


/**
 * Factory of petitions created  
 */
$friendpetition_factory      = icms_getmodulehandler('friendpetition', $modname, 'profile' );

/**
 * Getting the uid of the user which user want to ask to be friend
 */
$petitioned_uid = intval($_POST['petitioned_uid']);
                     
       
              /**
              * Verify Token
              */
              if (!($GLOBALS['xoopsSecurity']->check())){
                     redirect_header($_SERVER['HTTP_REFERER'], 3, _MD_PROFILE_TOKENEXPIRED);
              }

              
              //Verify if the user has already asked for friendship or if the user he s asking to be a friend has already asked him
$criteria= new criteriaCompo (new criteria('petioned_uid',$petitioned_uid));
$criteria->add(new criteria('petitioner_uid',$icmsUser->getVar('uid')));
if ($friendpetition_factory->getCount($criteria)>0){
redirect_header(ICMS_URL."/modules/".$modname."/index.php?uid=".$_POST['petitioned_uid'],3,_MD_PROFILE_ALREADY_PETITIONED);
} else {
$criteria2= new criteriaCompo (new criteria('petitioner_uid',$petitioned_uid));
$criteria2->add(new criteria('petioned_uid',$icmsUser->getVar('uid')));
if ($friendpetition_factory->getCount($criteria2)>0){
redirect_header(ICMS_URL."/modules/".$modname."/index.php?uid=".$_POST['petitioned_uid'],3,_MD_PROFILE_ALREADY_PETITIONED);
}	
}             
              /**
              * create the petition in database
              */
              $newpetition = $friendpetition_factory->create(true);
              $newpetition->setVar('petitioner_uid',$icmsUser->getVar('uid'));
              $newpetition->setVar('petioned_uid',$_POST['petitioned_uid']);

              if ($friendpetition_factory->insert($newpetition)){
              $extra_tags['X_OWNER_NAME'] = $icmsUser->getVar('uname');
              $extra_tags['X_OWNER_UID'] = $icmsUser->getVar('uid');
              $notification_handler =& xoops_gethandler('notification');
              $notification_handler->triggerEvent ("friendship", $_POST['petitioned_uid'] , "new_friendship",$extra_tags);       
                     
                     redirect_header(ICMS_URL."/modules/".$modname."/index.php?uid=".$_POST['petitioned_uid'],3,_MD_PROFILE_PETITIONED);
              } else {
                     redirect_header(ICMS_URL."/modules/".$modname."/index.php?uid=".$icmsUser->getVar('uid'),3,_MD_PROFILE_NOCACHACA);
              }


/**
 * Close page  
 */
include("footer.php");
?>
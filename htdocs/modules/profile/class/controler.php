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
 * @author          Bruno Barthez
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

if (!defined("ICMS_ROOT_PATH")) {
    die("ICMS root path not defined");
}
// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableobject.php';
include_once 'class.Id3v1.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');
$modname = basename(  dirname(  dirname( __FILE__ ) ) );
/**
 * Module classes
 */

class ProfileControler extends XoopsObject {

	var $db;
	var $user;
	var $isOwner = false;
	var $isUser = false;
	var $isAnonym = true;
	var $isFriend = false;
	var $uidOwner;
	var $nameOwner;
	var $owner;
	var $album_factory;
	var $visitors_factory;
    var $audio_factory;
	var $videos_factory;
	var $petitions_factory;
	var $friendships_factory;
	var $reltribeusers_factory;
	var $suspensions_factory;
	var $tribes_factory;
	var $scraps_factory;
	var $configs_factory;
	var $section;
	var $privilegeLevel;
	var $isSuspended;

	
    
	function ProfileControler($db,$user)	{
		$this->db 		= $db;
		$this->user		= $user;
		$this->createFactories();
		$this->getPermissions();
		$this->checkSuspension();
	}
	
    function checkSuspension() {
	  $criteria_suspended = new Criteria("uid",$this->uidOwner);
	  if ($this->isSuspended==1) {
		$suspensions = $this->suspensions_factory->getObjects($criteria_suspended);
		$suspension = $suspensions[0];
		if (time()>$suspension->getVar('suspension_time')) {					
          $suspension = $this->suspensions_factory->create(false);
          $suspension->load($this->uidOwner);    
          $this->owner->setVar('email',$suspension->getVar('old_email',"n"));
          $this->owner->setVar('pass',    $suspension->getVar('old_pass',"n"));
          $this->owner->setVar('user_sig',$suspension->getVar('old_signature',"n"));
          $user_handler = new XoopsUserHandler($this->db);
          $user_handler->insert($this->owner,true);    
          $criteria = new Criteria("uid",$this->uidOwner);
          $this->suspensions_factory->deleteAll($criteria);
		}
	  }
    }
	
	function checkPrivilege($items_asked) {			
		global $icmsModuleConfig,$icmsUser;
   	    $criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
	    if ($this->configs_factory->getCount($criteria)==1){
		  $configs = $this->configs_factory->getObjects($criteria);
		  $config = $configs[0]->getVar($items_asked);
		  if (!$this->checkPrivilegeLevel($config))  {
  		    return false;
		  }
		  return true;
	   }
	   return true;
	}



	function checkPrivilegeLevel($privilegeNeeded=0) {			
		if ($privilegeNeeded <= $this->privilegeLevel) {
		  return true;
		} else {
		  return false;
		}		
	}

	
	function getPermissions() {
	   global $uid_owner,$icmsUser;
       /**
       * @desc Check if the user uid exists if not redirect back to where he was
	   */
       if ($uid_owner) {
         $member_handler =& xoops_gethandler('member');
         $user =& $member_handler->getUser(intval($uid_owner));
         if (!(is_object($user))) {
           redirect_header("index.php",3,_MD_PROFILE_USERDOESNTEXIST);  
         }    
       }
 

	   /**
 		* If anonym and uid not set then redirect to admins profile
 		* Else redirects to own profile
 		*/
		if (empty($this->user)) {
		  $this->isAnonym = 1;
		   $this->isUser=0;
		   if ( $uid_owner ){
				$this->uidOwner = intval($uid_owner);
		    } else {
				$this->uidOwner = 0;
				$this->isOwner=0;
			}
		} else {						
		  $this->isAnonym = 0;
		  $this->isUser = 1;
		  if ( $uid_owner ){
			$this->uidOwner= intval($uid_owner);
			$this->isOwner = ($this->user->getVar('uid')==intval($uid_owner)) ? 1:0;
		  } else {
				$this->uidOwner = $this->user->getVar('uid');
				$this->isOwner =1;
		  }
		}
		$this->owner = new XoopsUser($this->uidOwner);
		$criteria_suspended = new Criteria("uid",$this->uidOwner);			
		$this->isSuspended = ($this->suspensions_factory->getCount($criteria_suspended)>0)?1:0;			
			
		if ($this->owner->getVar('name')=="") {
			$this->nameOwner = $this->owner->getVar('uname');
		}else{
			$this->nameOwner = $this->owner->getVar('name');
		}			
		
       //isfriend?
       $criteria_friends = new criteria('friend1_uid',$this->uidOwner);

       if (!$icmsUser) {
         $controler->isFriend = 0;	
       } else {
         $criteria_isfriend = new criteriaCompo (new criteria ('friend2_uid',$this->user->getVar('uid')));
         $criteria_isfriend->add($criteria_friends);
         $this->isFriend = $this->friendships_factory->getCount($criteria_isfriend);
       }		
		
	   $this->privilegeLevel =0;
	   if ($this->isAnonym==1){
		 $this->privilegeLevel =0;
	   }
	   if ($this->isUser==1){
		 $this->privilegeLevel =1;
	   }
	   if ($this->isFriend==1){
		 $this->privilegeLevel =2;
	   }
	   if ($this->isOwner==1){
		 $this->privilegeLevel =3;
	   }
	 }
	
	 /**
	 * Get for each section the number of objects the user possess
	 * 
	 * @return array(nbTribes=>"",nbPhotos=>"",nbFriends=>"",nbVideos=>"")
	 */	
	function getNumbersSections(){

		$criteriaTribes 		= new Criteria('rel_user_uid',$this->uidOwner);
		$nbSections['nbTribes']	= $this->reltribeusers_factory->getCount($criteriaTribes);
		$criteriaUid       		= new Criteria('uid_owner',$this->uidOwner);
		$criteriaAlbum     		= new CriteriaCompo($criteriaUid);
		if ($this->isOwner==0){
			$criteriaPrivate   = new criteria('private',0);
			$criteriaAlbum->add($criteriaPrivate);
		}
		$nbSections['nbPhotos']	= $this->album_factory->getCount($criteriaAlbum);
		$criteriaFriends 		= new criteria('friend1_uid',$this->uidOwner);
		$nbSections['nbFriends']= $this->friendships_factory->getCount($criteriaFriends);
        $criteriaUidAudio          = new criteria('uid_owner',$this->uidOwner);
        $nbSections['nbAudio'] = $this->audio_factory->getCount($criteriaUidAudio);
		$criteriaUidVideo  		= new criteria('uid_owner',$this->uidOwner);
		$nbSections['nbVideos'] = $this->videos_factory->getCount($criteriaUidVideo);
		$criteriaUidScraps  		= new criteria('scrap_to',$this->uidOwner);
		$nbSections['nbScraps'] = $this->scraps_factory->getCount($criteriaUidScraps);

		return $nbSections;
	}
	
	
	/**
	 * This creates the module factories
	 * 
	 * @return void
	 */		
		function createFactories()	{
			$modname = basename(  dirname(  dirname( __FILE__ ) ) );
         $this->album_factory 			= icms_getmodulehandler('images', $modname, 'profile' );
         $this->visitors_factory 		= icms_getmodulehandler('visitors', $modname, 'profile' );
         $this->audio_factory           = icms_getmodulehandler('audio', $modname, 'profile' );
         $this->videos_factory 			= icms_getmodulehandler('video', $modname, 'profile' );
         $this->petitions_factory 		= icms_getmodulehandler('friendpetition', $modname, 'profile' );
         $this->friendships_factory 		= icms_getmodulehandler('friendship', $modname, 'profile' );
         $this->reltribeusers_factory 	= icms_getmodulehandler('reltribeuser', $modname, 'profile' );
         $this->scraps_factory 			= icms_getmodulehandler('scraps', $modname, 'profile' );
         $this->tribes_factory 	   		= icms_getmodulehandler('tribes', $modname, 'profile' );
         $this->configs_factory 	   		= icms_getmodulehandler('configs', $modname, 'profile' );
         $this->suspensions_factory 		= icms_getmodulehandler('suspensions', $modname, 'profile' );
	}

	function checkPrivilegeBySection($section){
		global $icmsModuleConfig,$icmsUser;
		$configsectionname = 'enable_'.$section;
		if (array_key_exists($configsectionname,$icmsModuleConfig)) {
	      if ($icmsModuleConfig[$configsectionname]==0) {	 
	  	    return -1;
	      }
	    }
   	    $criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
	    if ($this->configs_factory->getCount($criteria)==1){
		  $configs = $this->configs_factory->getObjects($criteria);
		  $config = $configs[0]->getVar($section);
		  if (!$this->checkPrivilegeLevel($config))  {
  		    return 0;
		  }
		  return 1;
	   }
	   return 1;
    }
}




class ProfileControlerIndex extends ProfileControler  {

	function checkPrivilege($section){
		global $icmsModuleConfig,$icmsUser;
		if (trim($section)=="") return -1;
		$configsectionname = 'enable_'.$section;		
		if (array_key_exists($configsectionname,$icmsModuleConfig)){
	       if ($icmsModuleConfig[$configsectionname]==0){
	  	      return -1;
	       }
		}
		$criteria = new Criteria('config_uid',intval($this->owner->getVar('uid')));
		if ($this->configs_factory->getCount($criteria)==1){
			$configs = $this->configs_factory->getObjects($criteria);
			$config = $configs[0]->getVar($section);
			if (!$this->checkPrivilegeLevel($config)){
				return 0;
			}
			return 1;
		}
		return 1;
	  }	
}
?>
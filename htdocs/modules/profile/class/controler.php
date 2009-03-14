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
include_once ICMS_ROOT_PATH."/class/xoopsobject.php";
include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";
include_once ICMS_ROOT_PATH."/class/criteria.php";
include_once ICMS_ROOT_PATH."/class/pagenav.php";
$modname = basename(  dirname(  dirname( __FILE__ ) ) );
/**
 * Module classes
 */
include_once ICMS_ROOT_PATH.'/modules/'.$modname.'/class/class.Id3v1.php';

class ProfileControler extends XoopsObject {

	var $db;
	var $user;
	var $isOwner;
	var $isUser;
	var $isAnonym;
	var $isFriend;
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
		$this->isOwner	= 0;
		$this->isAnonym	= 1;
		$this->isFriend	= 0;
		$this->isUser	= 0;
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
		global $xoopsModuleConfig,$xoopsUser;
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
	   global $uid_owner,$xoopsUser;
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

       if (!$xoopsUser) {
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
		global $xoopsModuleConfig,$xoopsUser;
		$configsectionname = 'enable_'.$section;
		if (array_key_exists($configsectionname,$xoopsModuleConfig)) {
	      if ($xoopsModuleConfig[$configsectionname]==0) {	 
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


class ProfileVideoControler extends ProfileControler {

	
		/**
	 * Fecth videos
	 * @param object $criteria
	 * @return array of video objects
	 */		
	function getVideos($criteria) {
		$videos = $this->videos_factory->getObjects($criteria);
		return $videos;
	}
	
	/**
	 * Assign Videos Submit Form to theme
	 * @param int $maxNbVideos the maximum number of videos a user can have
	 * @param int $presentNbr the present number of videos the user has
	 * @return void
	 */	
	function showFormSubmitVideos($maxNbVideos,$presentNb)	{
		global $xoopsTpl;
		if ($this->isUser){
			if (($this->isOwner==1) && ($maxNbVideos > $presentNb)){
				echo "&nbsp;";
				$this->videos_factory->renderFormSubmit($xoopsTpl);
			}
		}
	}
	
	
	/**
	 * Assign Video Content to Template
	 * @param int $NbVideos the number of videos this user have
	 * @param object $tpl Xoops Template
	 * @param array of objects 
	 * @return void
	 */	
	function assignVideoContent($nbVideos, $videos)	{
		if ($nbVideos==0){
			return false;
		} else {
			/**
     * Lets populate an array with the dati from the videos
     */  
			$i = 0;
			foreach ($videos as $video){
				$videos_array[$i]['url']      = $video->getVar("youtube_code","s");
				$videos_array[$i]['desc']     = $video->getVar("video_desc","s");
				$videos_array[$i]['id']  	  = $video->getVar("video_id","s");
				
				$i++;
			}
		   return $videos_array;
		}

	}
		
	
	/**
	 * Create a page navbar for videos
	 * @param int $NbVideos the number of videos this user have
	 * @param int $videosPerPage the number of videos in a page
	 * @param int $start at which position of the array we start
	 * @param int $interval how many pages between the first link and the next one
	 * @return void
	 */	
		function VideosNavBar($nbVideos,$videosPerPage,$start,$interval) {
		  $pageNav = new XoopsPageNav($nbVideos, $videosPerPage,$start,"start","uid=".$this->uidOwner);
	      $navBar = $pageNav->renderImageNav($interval);
	      return $navBar;
	   }
	
	
	function checkPrivilege(){
		global $xoopsModuleConfig;
		if ($xoopsModuleConfig['enable_videos']==0){
			redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_VIDEOSNOTENABLED);
		}
		$criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
		if ($this->configs_factory->getCount($criteria)==1){
			$configs = $this->configs_factory->getObjects($criteria);
			$config = $configs[0]->getVar('videos');
			if (!$this->checkPrivilegeLevel($config)){
				redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_NOPRIVILEGE);
			}
		}
		return true;
	}
}



class ProfileControlerScraps extends ProfileControler {

//	function renderFormNewPost($tpl){
//		
//		
//		
//		$form = new XoopsThemeForm("",'formScrapNew','submit_scrap.php','post',true);
//		$fieldScrap = new XoopsFormTextArea('','text',_MD_PROFILE_ENTERTEXTSCRAP);
//		$fieldScrap->setExtra(' onclick="cleanScrapForm(text,\''._MD_PROFILE_ENTERTEXTSCRAP.'\')"');
//		
//		
//		$fieldScrabookUid = new XoopsFormHidden ("uid", $this->uidOwner);
//		
//		$submitButton = new XoopsFormButton("","post_scrap",_MD_PROFILE_SENDSCRAP,"submit");
//	
//		$form->addElement($fieldScrabookUid);
//		$form->addElement($fieldScrap,true);
//		$form->addElement($submitButton);
//		
//		//$form->display();
//		$form->assign($tpl);
//	}

	function fecthScraps($nb_scraps, $criteria) {
		if ($scraps = $this->scraps_factory->getScraps($nb_scraps, $criteria)) {
			return $scraps;
		} else { 
		    return false;
		}
	}
	
    function checkPrivilege($privilegeType="") {
        global $xoopsModuleConfig;
		if ($xoopsModuleConfig['enable_scraps']==0){
		  redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_SCRAPSNOTENABLED);
		}
		if ($privilegeType=="sendscraps"){
		  $criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
		  if ($this->configs_factory->getCount($criteria)==1){
			$configs = $this->configs_factory->getObjects($criteria);
			$config = $configs[0]->getVar('sendscraps');
		  }
		}
		$criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
		if ($this->configs_factory->getCount($criteria)==1){
			$configs = $this->configs_factory->getObjects($criteria);
			$config = $configs[0]->getVar('scraps');
			if (!$this->checkPrivilegeLevel($config)){
				redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_NOPRIVILEGE);
			}
		}
		return true;
	}

}



class ProfileControlerPhotos extends ProfileControler {
	
	function checkPrivilege(){
        global $xoopsModuleConfig;
		if ($xoopsModuleConfig['enable_pictures']==0){
			redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_PICTURESNOTENABLED);
		}
		$criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
		if ($this->configs_factory->getCount($criteria)==1){
			$configs = $this->configs_factory->getObjects($criteria);
			$config = $configs[0]->getVar('pictures');
			if (!$this->checkPrivilegeLevel($config)){
				redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_NOPRIVILEGE);
			}
		}
		return true;
	}

}


class ProfileAudioControler extends ProfileControler {


/**
     * Fecth audios
     * @param object $criteria
     * @return array of video objects
     */        
    function getAudio($criteria)
    {

        $audios = $this->audio_factory->getObjects($criteria);
        return $audios;
    }
    
    
    /**
     * Assign Audio Content to Template
     * @param int $NbAudios the number of videos this user have
     * @param object $tpl Xoops Template
     * @param array of objects 
     * @return void
     */    
    function assignAudioContent($nbAudios, $audios)
    {
	
        if ($nbAudios==0) {
            return false;
        } else {            
          //audio info
            /**
             * Lets populate an array with the dati from the audio
            */  
            $i = 0;
            foreach ($audios as $audio){
                $audios_array[$i]['url']      = $audio->getVar("url","s");
                $audios_array[$i]['title']    = $audio->getVar("title","s");
                $audios_array[$i]['id']       = $audio->getVar("audio_id","s");
                $audios_array[$i]['author']   = $audio->getVar("author","s");

                if ( (str_replace('.', '', PHP_VERSION)) > 499 ){  
                  $audio_path = ICMS_ROOT_PATH.'/uploads/'.basename(  dirname(  dirname( __FILE__ ) ) ).'/mp3/'.$audio->getVar("url","s");
                  // echo $audio_path;
                  $mp3filemetainfo = new Id3v1($audio_path, true);
                  $mp3filemetainfoarray = array();
                  $mp3filemetainfoarray['Title'] = $mp3filemetainfo->getTitle();
                  $mp3filemetainfoarray['Artist'] = $mp3filemetainfo->getArtist();
                  $mp3filemetainfoarray['Album'] = $mp3filemetainfo->getAlbum();
                  $mp3filemetainfoarray['Year'] =  $mp3filemetainfo->getYear();
                  $audios_array[$i]['meta'] = $mp3filemetainfoarray;
                } else {
                  $audios_array[$i]['nometa'] = 1;
                }                
                $i++;
            }
        return $audios_array;
        }

    }
    

/**
     * Create a page navbar for videos
     * @param int $NbAudios the number of videos this user have
     * @param int $audiosPerPage the number of videos in a page
     * @param int $start at which position of the array we start
     * @param int $interval how many pages between the first link and the next one
     * @return void
     */    
        function AudiosNavBar($nbAudios,$audiosPerPage,$start,$interval){
        
    $pageNav = new XoopsPageNav($nbAudios, $audiosPerPage,$start,"start","uid=".$this->uidOwner);
    $navBar = $pageNav->renderImageNav($interval);
    return $navBar;
    }
    
    function checkPrivilege(){
        global $xoopsModuleConfig;
        if ($xoopsModuleConfig['enable_audio']==0){
            redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_AUDIONOTENABLED);
        }
		$criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
        if ($this->configs_factory->getCount($criteria)==1){
            $configs = $this->configs_factory->getObjects($criteria);
            $config = $configs[0]->getVar('audio');
            if (!$this->checkPrivilegeLevel($config)){
                redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_NOPRIVILEGE);
            }
        }
		return true;
    }

}

class ProfileControlerFriends extends ProfileControler {	
	function checkPrivilege() {
        global $xoopsModuleConfig;
		if ($xoopsModuleConfig['enable_friends']==0){
			redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_FRIENDSNOTENABLED);
		}
		$criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
		if ($this->configs_factory->getCount($criteria)==1){
			$configs = $this->configs_factory->getObjects($criteria);
			$config = $configs[0]->getVar('friends');
			if (!$this->checkPrivilegeLevel($config)){
			  redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_NOPRIVILEGE);
			}
		}
		return true;
	}
	
}

class ProfileControlerTribes extends ProfileControler  {
	
	function checkPrivilege(){
        global $xoopsModuleConfig;
		if ($xoopsModuleConfig['enable_tribes']==0){
			redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_TRIBESNOTENABLED);
		}
		$criteria = new Criteria('config_uid',$this->owner->getVar('uid'));
		if ($this->configs_factory->getCount($criteria)==1){
			$configs = $this->configs_factory->getObjects($criteria);
			$config = $configs[0]->getVar('tribes');
			if (!$this->checkPrivilegeLevel($config)){
				redirect_header("index.php?uid=".$this->owner->getVar('uid'),3,_MD_PROFILE_NOPRIVILEGE);
			}
		}
		return true;
	}
	
}

class ProfileControlerIndex extends ProfileControler  {

	function checkPrivilege($section){
		global $xoopsModuleConfig,$xoopsUser;
		if (trim($section)=="") return -1;
		$configsectionname = 'enable_'.$section;		
		if (array_key_exists($configsectionname,$xoopsModuleConfig)){
	       if ($xoopsModuleConfig[$configsectionname]==0){
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

class ProfileControlerConfigs extends ProfileControler {

	function checkPrivilege(){
		return true;
	}
}

?>
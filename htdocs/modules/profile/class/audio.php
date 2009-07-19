<?php

/**
* Classes responsible for managing profile audio objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @package		profile
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');

class ProfileAudio extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('audio_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('author', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('url', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('uid_owner', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('data_creation', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('data_update', XOBJ_DTYPE_TXTBOX, false);
		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false, true);
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('doxcode', false, true);
		$this->setControl('url', 'upload');

		$this->hideFieldFromForm('data_creation');
		$this->hideFieldFromForm('data_update');
		$this->hideFieldFromForm('uid_owner');




		$this->IcmsPersistableSeoObject();
	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ())) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}
}
class ProfileAudioHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		global $icmsModuleConfig;
		$this->IcmsPersistableObjectHandler($db, 'audio', 'audio_id', '', '', 'profile');
		$this->setUploaderConfig(false, array("audio/mp3" , "audio/x-mp3", "audio/mpeg"), $icmsModuleConfig['maxfilesize']);
	}

	/**
	* delete profile_audios matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->table;
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

    /**
     * Assign Audio Content to Template
     * @param int $NbAudios the number of videos this user have
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
}
?>
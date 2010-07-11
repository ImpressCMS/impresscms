<?php
/**
 * ImpressCMS Conent Persistable Class
 *
 * @since 		ImpressCMS 1.2
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @author		Rodrigo P Lima (aka TheRplima) <therplima@impresscms.org>
 * @version		$Id$
 */

defined ( 'ICMS_ROOT_PATH' ) or die ( 'ImpressCMS root path not defined' );

include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';

/**
 * Content status definitions
 */
define ( 'CONTENT_CONTENT_STATUS_PUBLISHED', 1 );
define ( 'CONTENT_CONTENT_STATUS_PENDING', 2 );
define ( 'CONTENT_CONTENT_STATUS_DRAFT', 3 );
define ( 'CONTENT_CONTENT_STATUS_PRIVATE', 4 );
define ( 'CONTENT_CONTENT_STATUS_EXPIRED', 5 );

define ( 'CONTENT_CONTENT_VISIBLE_MENUOLNY', 1 );
define ( 'CONTENT_CONTENT_VISIBLE_SUBSONLY', 2 );
define ( 'CONTENT_CONTENT_VISIBLE_MENUSUBS', 3 );
define ( 'CONTENT_CONTENT_VISIBLE_DONTSHOW', 4 );

/**
 * ImpressCMS Core Content Object Handler Class
 *
 * @copyright The ImpressCMS Project <http://www.impresscms.org>
 * @license GNU GPL v2
 *
 * @since ImpressCMS 1.2
 * @author Rodrigo P Lima (aka TheRplima) <therplima@impresscms.org>
 */
class mod_content_ContentHandler extends icms_ipf_Handler {

	/**
	 * @public array of status
	 */
	public $_content_statusArray = array ( );

	/**
	 * @public array of status
	 */
	public $_content_visibleArray = array ( );

	/**
	 * @public array of tags
	 */
	public $_content_tagsArray = array ( );

	public function __construct(& $db) {
		parent::__construct( $db, 'content', 'content_id', 'content_title', 'content_body', 'content' );

		icms_loadLanguageFile('content', 'common');
		$this->addPermission ( 'content_read', _CO_CONTENT_CONTENT_READ, _CO_CONTENT_CONTENT_READ_DSC );
	}

	/**
	 * Retreive the possible status of a content object
	 *
	 * @return array of status
	 */
	function getContent_statusArray() {
		if (! $this->_content_statusArray) {
			$this->_content_statusArray [CONTENT_CONTENT_STATUS_PUBLISHED] = _CO_CONTENT_CONTENT_STATUS_PUBLISHED;
			$this->_content_statusArray [CONTENT_CONTENT_STATUS_PENDING] = _CO_CONTENT_CONTENT_STATUS_PENDING;
			$this->_content_statusArray [CONTENT_CONTENT_STATUS_DRAFT] = _CO_CONTENT_CONTENT_STATUS_DRAFT;
			$this->_content_statusArray [CONTENT_CONTENT_STATUS_PRIVATE] = _CO_CONTENT_CONTENT_STATUS_PRIVATE;
			$this->_content_statusArray [CONTENT_CONTENT_STATUS_EXPIRED] = _CO_CONTENT_CONTENT_STATUS_EXPIRED;
		}
		return $this->_content_statusArray;
	}

	/**
	 * Retreive the possible visibility of a content object
	 *
	 * @return array of visibility
	 */
	function getContent_visibleArray() {
		if (! $this->_content_visibleArray) {
			$this->_content_visibleArray [CONTENT_CONTENT_VISIBLE_MENUOLNY] = _CO_CONTENT_CONTENT_VISIBLE_MENUOLNY;
			$this->_content_visibleArray [CONTENT_CONTENT_VISIBLE_SUBSONLY] = _CO_CONTENT_CONTENT_VISIBLE_SUBSONLY;
			$this->_content_visibleArray [CONTENT_CONTENT_VISIBLE_MENUSUBS] = _CO_CONTENT_CONTENT_VISIBLE_MENUSUBS;
			$this->_content_visibleArray [CONTENT_CONTENT_VISIBLE_DONTSHOW] = _CO_CONTENT_CONTENT_VISIBLE_DONTSHOW;
		}
		return $this->_content_visibleArray;
	}


	/**
	 * Retreive the tags of the content object
	 *
	 * @return array of tags
	 */
	function getContent_tagsArray() {
		if (! $this->_content_tagsArray) {
			$ret = array();
			$contents = $this->getObjects();
			foreach($contents as $content){
				$tags = $content->getVar('content_tags','e');
				$tag_arr = explode(",",$tags);
				foreach ($tag_arr as $tag){
					$tag = trim($tag);
					if (isset($ret[$tag])){
						$ret[$tag]++;
					}else{
						$ret[$tag] = 1;
					}
				}
			}
			foreach($ret as $k=>$v){
				if ($k != ''){
					$ret[$k] = $k.'('.$v.')';
				}else{
					unset($ret[$k]);
				}
			}
			$this->_content_tagsArray = $ret;
		}
		return $this->_content_tagsArray;
	}

	/**
	 * Create the criteria that will be used by getContents and getContentsCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of contents to return
	 * @param int $content_uid if specifid, only the content of this user will be returned
	 * @param int $cid if specifid, only the content related to this category will be returned
	 * @param int $year of contents to display
	 * @param int $month of contents to display
	 * @param int $content_id ID of a single content to retrieve
	 * @return icms_criteria_Compo $criteria
	 */
	function getContentsCriteria($start = 0, $limit = 0, $content_uid = false, $content_tags=false, $content_id = false,  $content_pid = false, $order = 'content_published_date', $sort = 'DESC') {
		global $xoopsUser;

		$criteria = new icms_criteria_Compo ( );
		if ($start) {
			$criteria->setStart ( $start );
		}
		if ($limit) {
			$criteria->setLimit ( (int) ( $limit ) );
		}
		$criteria->setSort ( $order );
		$criteria->setOrder ( $sort );

		$criteria->add ( new icms_criteria_Item ( 'content_status', CONTENT_CONTENT_STATUS_PUBLISHED ) );

		if ($content_uid) {
			$criteria->add ( new icms_criteria_Item ( 'content_uid', $content_uid ) );
		}

		if ($content_tags){
			$criteria->add ( new icms_criteria_Item ( 'content_tags', '%'.$content_tags.'%', 'LIKE' ) );
		}

		if ($content_id) {
			$crit = new icms_criteria_Compo(new icms_criteria_Item('short_url', $content_id,'LIKE'));
			$alt_content_id = str_replace('-',' ',$content_id);
			$crit->add(new icms_criteria_Item('short_url', $alt_content_id),'OR'); //Added for backward compatiblity in case short_url contains spaces instead of dashes.
			$crit->add(new icms_criteria_Item('content_id', $content_id),'OR');
			$criteria->add($crit);
		}

		if ($content_pid !== false){
			$criteria->add ( new icms_criteria_Item ( 'content_pid', $content_pid ) );
		}
		return $criteria;
	}


	/**
	 * Get single content object
	 *
	 * @param int $content_id
	 * @return object ImreportingContent object
	 */
	function getContent($content_id) {
		$ret = $this->getContents ( 0, 0, false, false, $content_id );
		return isset ( $ret [$content_id] ) ? $ret [$content_id] : false;
	}


	/**
	 * Get contents as array, ordered by content_published_date DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max contents to display
	 * @param int $content_uid if specifid, only the content of this user will be returned
	 * @param int $cid if specifid, only the content related to this category will be returned
	 * @param int $year of contents to display
	 * @param int $month of contents to display
	 * @param int $content_id ID of a single content to retrieve
	 * @return array of contents
	 */
	function getContents($start = 0, $limit = 0, $content_uid = false, $content_tags = false, $content_id = false,  $content_pid = false, $order = 'content_published_date', $sort = 'DESC') {
		$criteria = $this->getContentsCriteria ( $start, $limit, $content_uid, $content_tags, $content_id,  $content_pid, $order, $sort );
		$contents = $this->getObjects ( $criteria, true, false );
		$ret = array();
		foreach ($contents as $content){
			if ($content['accessgranted']){
				$ret[$content['content_id']] = $content;
			}
		}
		return $ret;
	}


	/**
	 * Get a list of users
	 *
	 * @return array list of users
	 */
	function getPostersArray() {
		$member_handler = xoops_getHandler ( 'member' );
		return $member_handler->getUserList ();
	}


	/**
	 * Get contents count
	 *
	 * @param int $content_uid if specifid, only the content of this user will be returned
	 * @param int $cid if specifid, only the content related to this category will be returned
	 * @return array of contents
	 */
	function getContentsCount($content_uid) {
		$criteria = $this->getContentsCriteria ( false, false, $content_uid );
		return $this->getCount ( $criteria );
	}


	/**
	 * Get Contents requested by the global search feature
	 *
	 * @param array $queryarray array containing the searched keywords
	 * @param bool $andor wether the keywords should be searched with AND or OR
	 * @param int $limit maximum results returned
	 * @param int $offset where to start in the resulting dataset
	 * @param int $userid should we return contents by specific contenter ?
	 * @return array array of contents
	 */
	function getContentsForSearch($queryarray, $andor, $limit, $offset, $userid) {
		$criteria = new icms_criteria_Compo ( );

		$criteria->setStart ( $offset );
		$criteria->setLimit ( $limit );

		if ($userid != 0) {
			$criteria->add ( new icms_criteria_Item ( 'content_uid', $userid ) );
		}
		if ($queryarray) {
			$criteriaKeywords = new icms_criteria_Compo ( );
			for($i = 0; $i < count ( $queryarray ); $i ++) {
				$criteriaKeyword = new icms_criteria_Compo ( );
				$criteriaKeyword->add ( new icms_criteria_Item ( 'content_title', '%' . $queryarray [$i] . '%', 'LIKE' ), 'OR' );
				$criteriaKeyword->add ( new icms_criteria_Item ( 'content_body', '%' . $queryarray [$i] . '%', 'LIKE' ), 'OR' );
				$criteriaKeywords->add ( $criteriaKeyword, $andor );
				unset ( $criteriaKeyword );
			}
			$criteria->add ( $criteriaKeywords );
		}
		$criteria->add ( new icms_criteria_Item ( 'content_status', CONTENT_CONTENT_STATUS_PUBLISHED ) );
		return $this->getObjects ( $criteria, true, false );
	}

	/**
	 * Check wether the current user can submit a new content or not
	 *
	 * @return bool true if he can false if not
	 */
	function userCanSubmit() {
		global $xoopsUser, $content_isAdmin;
		if (! is_object ( $xoopsUser )) {

			return false;
		}
		if ($content_isAdmin) {
			return true;
		}
		$user_groups = $xoopsUser->getGroups ();

		return count ( array_intersect ( $xoopsModuleConfig ['poster_group'], $user_groups ) ) > 0;
	}



	/**
	 * Update the counter field of the content object
	 *
	 * @param int $content_id
	 *
	 * @return VOID
	 */
	function updateCounter($id) {
		global $xoopsUser, $content_isAdmin;

		$contentObj = $this->get ( $id );
		if (! is_object ( $contentObj )) {
			return false;
		}
		if (!is_object($xoopsUser) || (!$content_isAdmin && $contentObj->getVar ( 'content_uid', 'e' ) != $xoopsUser->uid ())) {
			$contentObj->updating_counter = true;
			$contentObj->setVar ( 'counter', $contentObj->getVar ( 'counter', 'n' ) + 1 );
			$this->insert ( $contentObj, true );
		}

		return true;
	}


	/**
	 * Get contents count
	 *
	 * @param int $content_uid if specifid, only the content of this user will be returned
	 * @return array of contents
	 */
	function getContentsSubsCount($content_id = 0) {
		$criteria = $this->getContentsCriteria ();
		$criteria->add ( new icms_criteria_Item ( 'content_pid', $content_id ) );
		return $this->getCount ( $criteria );
	}

	/**
	 * Get the subpages of the page
	 *
	 * @return array of contents
	 */
	function getContentSubs($content_id = 0, $toarray=false) {
		$criteria = $this->getContentsCriteria();
		$criteria->add( new icms_criteria_Item ( 'content_pid', $content_id ) );
		$crit = new icms_criteria_Compo(new icms_criteria_Item('content_visibility', 2));
		$crit->add(new icms_criteria_Item('content_visibility', 3),'OR');
		$criteria->add($crit);
		$contents = $this->getObjects($criteria);
		if (!$toarray){
			return $contents;
		}
		$ret = array();
		foreach ( array_keys ( $contents ) as $i ) {
			if ($contents[$i]->accessGranted()){
				$ret[$i] = $contents[$i]->toArray();
				$ret[$i]['content_body'] = icms_substr(icms_cleanTags($contents[$i]->getVar('content_body','n'),array()),0,300);
				$ret[$i]['content_url'] = $contents[$i]->getItemLink();
			}
		}
		return $ret;
	}


	function getList($content_status = null) {
		$criteria = new icms_criteria_Compo ( );

		if (isset ( $content_status )) {
			$criteria->add ( new icms_criteria_Item ( 'content_status', (int) ( $content_status ) ) );
		}
		$contents = & $this->getObjects ( $criteria, true );
		foreach ( array_keys ( $contents ) as $i ) {
			$ret [$contents [$i]->getVar ( 'content_id' )] = $contents [$i]->getVar ( 'content_title' );
		}
		return $ret;
	}


	function getContentList($groups = array(), $perm = 'content_read', $status = null, $content_id = null, $showNull = true) {
		$criteria = new icms_criteria_Compo ( );
		if (is_array ( $groups ) && ! empty ( $groups )) {
			$criteriaTray = new icms_criteria_Compo ( );
			foreach ( $groups as $gid ) {
				$criteriaTray->add ( new icms_criteria_Item ( 'gperm_groupid', $gid ), 'OR' );
			}
			$criteria->add ( $criteriaTray );
			if ($perm == 'content_read' || $perm == 'content_admin') {
				$criteria->add ( new icms_criteria_Item ( 'gperm_name', $perm ) );
				$criteria->add ( new icms_criteria_Item ( 'gperm_modid', 1 ) );
			}
		}
		if (isset ( $status )) {
			$criteria->add ( new icms_criteria_Item ( 'content_status', (int) ( $status ) ) );
		}
		if (is_null ( $content_id ))
		$content_id = 0;
		$criteria->add ( new icms_criteria_Item ( 'content_pid', $content_id ) );

		$contents = & $this->getObjects ( $criteria, true );
		$ret = array ( );
		if ($showNull) {
			$ret [0] = '-----------------------';
		}
		foreach ( array_keys ( $contents ) as $i ) {
			$ret [$i] = $contents [$i]->getVar ( 'content_title' );
			$subccontents = $this->getContentList ( $groups, $perm, $status, $contents [$i]->getVar ( 'content_id' ), $showNull );
			foreach ( array_keys ( $subccontents ) as $j ) {
				$ret [$j] = '-' . $subccontents [$j];
			}
		}

		return $ret;
	}


	function makeLink($content,$onlyUrl=false) {
		$count = $this->getCount ( new icms_criteria_Item ( "short_url", $content->getVar ( "short_url" ) ) );

		if ($count > 1) {
			return $content->getVar ( 'content_id' );
		} else {
			$seo = str_replace ( " ", "-", $content->getVar ( 'short_url' ) );
			return $seo;
		}
	}

	function hasPage($user) {
		$gperm_handler = & xoops_gethandler('member_groupperm');
		$groups = is_object ( $user ) ? $user->getGroups () : XOOPS_GROUP_ANONYMOUS;
		$criteria = new icms_criteria_Compo ( new icms_criteria_Item ( 'content_status', 1 ) );
		$cont_arr = $this->getObjects ( $criteria );
		if (count ( $cont_arr ) > 0) {
			$perm = array ( );
			foreach ( $cont_arr as $cont ) {
				if ($gperm_handler->checkRight ( 'content_read', $cont->getVar ( 'content_id' ), $groups )) {
					$perm [] = $cont->getVar ( 'content_id' );
				}
			}
			if (count ( $perm ) > 0) {
				if ($xoopsModuleConfig ['default_page'] != 0) {
					if (! in_array ( $xoopsModuleConfig ['default_page'], $perm )) {
						return false;
					}
				}
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function getLastestCreated($asObj=true){
		$criteria = $this->getContentsCriteria (0,1);
		$criteria->setSort ( 'content_id' );
		$criteria->setOrder ( 'DESC' );
		$ret = $this->getObjects ( $criteria, false, $asObj );
		if ($asObj){
			return $ret[0];
		}else{
			return $ret[0]['content_id'];
		}
	}

	/**
	 * Function to create a navigation menu in content pages.
	 * This function was based on the function that do the same in mastop publish module
	 *
	 * @param integer $content_id
	 * @return string
	 */
	function getBreadcrumbForPid($content_id, $userside=false){
		$url = $_SERVER['PHP_SELF'];
		$ret = false;

		if ($content_id == false) {
			return $ret;
		}else{
			if ($content_id > 0) {
				$content = $this->get($content_id);
				if ($content->getVar('content_id','e') > 0) {
					if (!$userside){
						$ret = "<a href='".$url."?content_pid=".$content->getVar('content_id','e')."'>".$content->getVar('content_title','e')."</a>";
					}else{
						$ret = "<a href='".$url."?page=".$this->makeLink($content)."'>".$content->getVar('content_title','e')."</a>";
					}
					if ($content->getVar('content_pid','e') == 0) {
						if (!$userside){
							return "<a href='".$url."?content_pid=0'>"._MI_CONTENT_CONTENTS."</a> &gt; ".$ret;
						}else{
							return $ret;
						}
					}elseif ($content->getVar('content_pid','e') > 0){
						$ret = $this->getBreadcrumbForPid($content->getVar('content_pid','e'), $userside)." &gt; ". $ret;
					}
				}
			}else{
				return $ret;
			}
		}
		return $ret;
	}

	/**
	 * Update number of comments on a content
	 *
	 * This method is triggered by imcontent_com_update in include/functions.php which is
	 * called by ImpressCMS when updating comments
	 *
	 * @param int $content_id id of the content to update
	 * @param int $total_num total number of comments so far in this content
	 * @return VOID
	 */
	function updateComments($content_id, $total_num) {
		$contentObj = $this->get ( $content_id );
		if ($contentObj && ! $contentObj->isNew ()) {
			$contentObj->setVar ( 'content_comments', $total_num );
			$this->insert ( $contentObj, true );
		}
	}

	/**
	 * BeforeSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework before the object is inserted or updated.
	 *
	 * @param object $obj Content object
	 * @return true
	 */
	function beforeSave(& $obj) {
		if ($obj->updating_counter)
		return true;

		$obj->setVar ( 'dobr', $obj->need_do_br () );

		//Prevent that the page is defined as parent page of yourself.
		if ($obj->getVar('content_pid','e') == $obj->getVar('content_id','e')){
			$obj->setVar('content_pid', 0);
		}

		return true;
	}

	/**
	 * AfterSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework after the object is inserted or updated
	 *
	 * @param object $obj Content object
	 * @return true
	 */
	function afterSave(& $obj) {
		if ($obj->updating_counter)
		return true;

		if (! $obj->getVar ( 'content_notification_sent' ) && $obj->getVar ( 'content_status', 'e' ) == CONTENT_CONTENT_STATUS_PUBLISHED) {
			$obj->sendNotifContentPublished ();
			$obj->setVar ( 'content_notification_sent', true );
			$this->insert ( $obj );
		}

		if ($obj->getVar('content_makesymlink') == 1){
			$module_handler = xoops_gethandler('module');
			$module = $module_handler->getByDirname('content');

			$seo = $obj->handler->makelink($obj);
			$url = str_replace(ICMS_URL.'/','',$obj->handler->_moduleUrl.$obj->handler->_itemname.'.php?page='.$seo);

			$symlink_handler = icms_getModuleHandler('pages','system');
			$criteria = new icms_criteria_Compo(new icms_criteria_Item('page_url','%'.$seo,'LIKE'));
			$criteria->add(new icms_criteria_Item('page_moduleid',$module->mid()));
			$ct = $symlink_handler->getObjects($criteria);
			if (count($ct) <= 0){
				$symlink = $symlink_handler->create(true);
				$symlink->setVar('page_moduleid',$module->mid());
				$symlink->setVar('page_title',$obj->getVar('content_title'));
				$symlink->setVar('page_url',$url);
				$symlink->setVar('page_status',1);
				$symlink_handler->insert($symlink);
			}
		}
		return true;
	}

	/**
	 * AfterDelete event
	 *
	 * Event automatically triggered by IcmsPersistable Framework after the object is deleted
	 *
	 * @param object $obj Content object
	 * @return true
	 */
	function afterDelete(& $obj) {
		$seo = $obj->handler->makelink($obj);
		$url = str_replace(ICMS_URL.'/','',$obj->handler->_moduleUrl.$obj->handler->_itemname.'.php?page='.$seo);
		$module_handler = xoops_gethandler('module');
		$module = $module_handler->getByDirname('content');

		$symlink_handler = icms_getModuleHandler('pages','system');
		$criteria = new icms_criteria_Compo(new icms_criteria_Item('page_url',$url));
		$criteria->add(new icms_criteria_Item('page_moduleid',$module->mid()));
		$symlink_handler->deleteAll($criteria);

		return true;
	}
}

?>
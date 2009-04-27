<?php
// $Id$
/**
* Content Class
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	LICENSE.txt
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	modified by UnderDog <underdog@impresscms.org>
* @version	$Id$
*/

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

class XoopsContent extends XoopsObject
{
     function XoopsContent()
     {
          $this->XoopsObject();
          $this->initVar('content_id', XOBJ_DTYPE_INT, null, false);
          $this->initVar('content_catid', XOBJ_DTYPE_INT, 1, true);
          $this->initVar('content_supid', XOBJ_DTYPE_INT, 0, true);
          $this->initVar('content_uid', XOBJ_DTYPE_INT, 1, true);
          $this->initVar('content_title', XOBJ_DTYPE_TXTBOX, null, true, 255);
          $this->initVar('content_menu', XOBJ_DTYPE_TXTBOX, null, true, 100);
          $this->initVar('content_body', XOBJ_DTYPE_TXTAREA, null, true);
          $this->initVar('content_css', XOBJ_DTYPE_TXTAREA, null, false);
          $this->initVar('content_tags', XOBJ_DTYPE_TXTAREA, null, false);
          $this->initVar('content_visibility', XOBJ_DTYPE_INT, 3, false);
          $this->initVar('content_created', XOBJ_DTYPE_INT, null, false);
          $this->initVar('content_updated', XOBJ_DTYPE_INT, null, false);
          $this->initVar('content_weight', XOBJ_DTYPE_INT, 0, false);
          $this->initVar('content_reads', XOBJ_DTYPE_INT, 0, false);
          $this->initVar('content_status', XOBJ_DTYPE_INT, 1, false);
          $this->initVar('content_seo_description', XOBJ_DTYPE_TXTBOX, null, false, 255);
          $this->initVar('content_seo_keywords', XOBJ_DTYPE_TXTAREA, null, false);
     }

	function getReads(){
		return $this->getVar('content_reads');
	}

	function setReads($qtde=null){
		$t = $this->getVar('content_reads');
		if (isset($qtde)){
			$t += $qtde;
		}else{
			$t ++;
		}
		$this->setVar('content_reads',$t);
	}
}

/**
 * ICMS content handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of ICMS content class objects.
 *
 *
 * @author  TheRplima <therplima@impresscms.org>
 */

class XoopsContentHandler extends XoopsObjectHandler
{

	function &create($isNew = true)
	{
		$content = new XoopsContent();
		if ($isNew) {
			$content->setNew();
		}
		return $content;
	}

	function &get($id)
	{
		$content = false;
		$id = intval($id);
		if ($id > 0) {
			$sql = "SELECT * FROM ".$this->db->prefix('icmscontent')." WHERE content_id='".$id."'";
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$content = new XoopsContent();
				$content->assignVars($this->db->fetchArray($result));
				return $content;
			}
		}
		return $content;
	}

	function insert(&$content)
	{
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		if (strtolower(get_class($content)) != 'xoopscontent') {
			return false;
		}
		if (!$content->isDirty()) {
			return true;
		}
		if (!$content->cleanVars()) {
			return false;
		}
		foreach ($content->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if ($content->isNew()) {
			$content_id = $this->db->genId('content_content_id_seq');
			$sql = sprintf("INSERT INTO %s (content_id, content_catid, content_supid, content_uid, content_title, content_menu, content_body, content_css, content_tags, content_visibility, content_created, content_updated, content_weight, content_reads, content_status, content_seo_description, content_seo_keywords) VALUES (%u, %u, %u, %u, %s, %s, %s, %s, %s, %u, %u, %u, %u, %u, %u, %s, %s)",
			$this->db->prefix('icmscontent'),
			intval($content_id),
			intval($content_catid),
			intval($content_supid),
			intval($content_uid),
			$this->db->quoteString($content_title),
			$this->db->quoteString(str_replace(array("_","-","/","|",";")," ", $content_menu)),
			$this->db->quoteString($content_body),
			$this->db->quoteString($content_css),
			$this->db->quoteString($content_tags),
			intval($content_visibility),
			time(),
			time(),
			intval($content_weight),
			intval($content_reads),
			intval($content_status),
               $this->db->quoteString($content_seo_description),
               $this->db->quoteString($content_seo_keywords));
		} else {
			$sql = sprintf("UPDATE %s SET content_catid=%u, content_supid=%u, content_uid=%u, content_title=%s, content_menu=%s, content_body=%s, content_css=%s, content_tags=%s, content_visibility=%u, content_updated=%u, content_weight=%u, content_reads=%u, content_status=%u, content_seo_description=%s, content_seo_keywords=%s WHERE content_id=%u",
			$this->db->prefix('icmscontent'),
			intval($content_catid),
			intval($content_supid),
			intval($content_uid),
			$this->db->quoteString($content_title),
			$this->db->quoteString(str_replace(array("_","-","/","|",";")," ", $content_menu)),
			$this->db->quoteString($content_body),
			$this->db->quoteString($content_css),
			$this->db->quoteString($content_tags),
			intval($content_visibility),
			time(),
			intval($content_weight),
			intval($content_reads),
			intval($content_status),
			intval($content_id),
               $this->db->quoteString($content_seo_description),
               $this->db->quoteString($content_seo_keywords));
		}
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		if (empty($content_id)) {
			$content_id = $this->db->getInsertId();
		}
		$content->assignVar('content_id', $content_id);
		return true;
	}

	function delete(&$content)
	{
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		if (strtolower(get_class($content)) != 'xoopscontent') {
			return false;
		}

		$id = intval($content->getVar('content_id'));
		$sql = sprintf("DELETE FROM %s WHERE content_id = '%u'", $this->db->prefix('icmscontent'), $id);
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}

		return true;
	}

	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = "SELECT * FROM ".$this->db->prefix('icmscontent');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= " ".$criteria->renderWhere();
			$sql .= " ORDER BY ".($criteria->getSort()?$criteria->getSort():'content_weight')." ".($criteria->getOrder()?$criteria->getOrder():'ASC');
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$content = new XoopsContent();
			$content->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $content;
			} else {
				$ret[$myrow['content_id']] =& $content;
			}
			unset($content);
		}
		return $ret;
	}

	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('icmscontent');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result =& $this->db->query($sql)) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	function getList($content_catid=null,$content_status=null)
	{
		$criteria = new CriteriaCompo();
		if (isset($content_catid)) {
			$criteria->add(new Criteria('content_catid', $content_catid));
		}
		if (isset($content_status)) {
			$criteria->add(new Criteria('content_status', intval($content_status)));
		}
		$contents =& $this->getObjects($criteria, true);
		foreach (array_keys($contents) as $i) {
			$ret[$contents[$i]->getVar('content_id')] = $contents[$i]->getVar('content_title');
		}
		return $ret;
	}

	function getContentList($groups = array(), $perm = 'content_read', $status = null, $content_id=null,$showNull=true)
	{
		$criteria = new CriteriaCompo();
		if (is_array($groups) && !empty($groups)) {
			$criteriaTray = new CriteriaCompo();
			foreach ($groups as $gid) {
				$criteriaTray->add(new Criteria('gperm_groupid', $gid), 'OR');
			}
			$criteria->add($criteriaTray);
			if ($perm == 'content_read' || $perm == 'content_admin') {
				$criteria->add(new Criteria('gperm_name', $perm));
				$criteria->add(new Criteria('gperm_modid', 1));
			}
		}
		if (isset($status)) {
			$criteria->add(new Criteria('content_status', intval($status)));
		}
		if (is_null($content_id))$content_id = 0;
		$criteria->add(new Criteria('content_supid', $content_id));
		$contents =& $this->getObjects($criteria, true);
		$ret = array();
		if ($showNull){
			$ret[0] = '-----------------------';
		}
		foreach (array_keys($contents) as $i) {
			$ret[$i] = $contents[$i]->getVar('content_title');
			$subccontents = $this->getContentList($groups, $perm, $status, $contents[$i]->getVar('content_id'),$showNull);
			foreach (array_keys($subccontents) as $j) {
				$ret[$j] = '-'.$subccontents[$j];
			}
		}

		return $ret;
	}

	function makeLink($content)
	{
		$count = $this->getCount(new Criteria("content_menu", $content->getVar("content_menu")));

		if ($count > 1){
			return $content->getVar('content_id');
		}else{
			$seo = str_replace(" ", "-",$content->getVar('content_menu'));
			return $seo;
		}
	}

	function hasPage($user){
		$config_handler =& xoops_gethandler('config');
		$im_contentConfig =& $config_handler->getConfigsByCat(IM_CONF_CONTENT);
		$gperm_handler = & xoops_gethandler( 'groupperm' );
		$groups = is_object($user) ? $user->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$criteria = new CriteriaCompo(new Criteria('content_status', 1));
		$cont_arr = $this->getObjects($criteria);
		if (count($cont_arr) > 0){
			$perm = array();
			foreach ($cont_arr as $cont){
				if ($gperm_handler->checkRight('content_read', $cont->getVar('content_id'), $groups)){
					$perm[] = $cont->getVar('content_id');
				}
			}
			if (count($perm) > 0){
				if ($im_contentConfig['default_page'] != 0){
					if (!in_array($im_contentConfig['default_page'],$perm)){
						return false;
					}
				}
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function getTags(){
		$ret = array();

		$sql = "SELECT content_tags FROM ".$this->db->prefix('icmscontent');
		$result = $this->db->query($sql);
		if (!$result) {
			return $ret;
		}
		while (list($tags) = $this->db->fetchRow($result)) {
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
		return $ret;
	}
}
?>
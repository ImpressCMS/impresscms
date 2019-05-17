<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * icms_view_PageBuilder component class file
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @category	ICMS
 * @package     Core
 * @subpackage	Template
 *
 * @version		SVN: $Id: PageBuilder.php 12313 2013-09-15 21:14:35Z skenow $
 *
 */
/**
 * This file cannot be requested directly
 */
defined('ICMS_ROOT_PATH') or exit();

/**
 * icms_view_PageBuilder main class
 *
 * @category	ICMS
 * @package     Core
 * @subpackage  Template
 * @author      Skalpa Keo <skalpa@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @author      Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class icms_view_PageBuilder {

	/** */
	public $theme = FALSE;

	/** */
	public $blocks = array();

	/** */
	private $uagroups = array();

	/** */
	static private $modid;

	/**
	 * Initializes the page object and loads all the blocks
	 * @param $options
	 */
	public function xoInit($options = array()) {
		$this->retrieveBlocks();
		if ($this->theme) {
			$this->theme->template->assign_by_ref('xoBlocks', $this->blocks);
		}
		return TRUE;
	}

	/**
	 * Called before a specific zone is rendered
	 *
	 * @param string $zone
	 */
	public function preRender($zone = '') { /* Empty! */ }

	/**
	 * Called after a specific zone is rendered
	 *
	 * @param string $zone
	 */
	public function postRender($zone = '') { /* Empty! */ }

	/**
	 * Retrieve Blocks for the page and loads their templates
	 *
	 */
	public function retrieveBlocks() {
		global $xoops, $icmsModule, $icmsConfig;

		$groups = is_object(icms::$user) ? icms::$user->getGroups() : array(ICMS_GROUP_ANONYMOUS);
		self::getPage();
		$modid = self::$modid['module'] . '-' . self::$modid['page'];
		$isStart = self::$modid['isStart'];

		$icms_block_handler = icms::handler('icms_view_block');
		$oldzones = $icms_block_handler->getBlockPositions();

		foreach ($oldzones as $zone) {
			$this->blocks[$zone] = array();
		}
		if ($this->theme) {
			$template =& $this->theme->template;
			$backup = array($template->caching, $template->cache_lifetime);
		} else {
			$template = new icms_view_Tpl();
		}

		/** moved here from buildBlocks to reduce redundant calls */
		$gperm = icms::handler('icms_member_groupperm');
		$ugroups = @is_object(icms::$user) ? icms::$user->getGroups() : array(ICMS_GROUP_ANONYMOUS);
		$agroups = $gperm->getGroupIds('system_admin',  5); //XOOPS_SYSTEM_BLOCK constant not available?
		$this->uagroups = array_intersect($ugroups, $agroups);
		/** End of snippet */

		$block_arr = $icms_block_handler->getAllByGroupModule($groups, $modid, $isStart, XOOPS_BLOCK_VISIBLE);
		// prefetch blocks to reduce the amount of queries required in the later step of rendering
		$tplfile_handler = icms::handler('icms_view_template_file');
		$tplfile_handler->prefetchBlocks($block_arr);
		foreach ($block_arr as $block) {
			$side = $oldzones[$block->getVar('side', 'n')];
			if ($var = $this->buildBlock($block, $template)) {
				$this->blocks[$side][$var["id"]] = $var;
			}
		}
		if ($this->theme) {
			list($template->caching, $template->cache_lifetime) = $backup;
		}
	}

	/**
	 * generate the modid (combination of current module and page) and store it in a static var
	 * isStart is only needed for this class (used in function retrieveBlocks()).
	 *
	 * @global array $icmsConfig ImpressCMS configuration array
	 * @global icms_module_Object $icmsModule current module
	 * @return void
	 */
	static public function getPage() {
		global $icmsConfig, $icmsModule;

		if (is_array(self::$modid)) return self::$modid;

		// getting the start module and page configured in the admin panel
		if (is_array($icmsConfig['startpage'])) {
			$member_handler = icms::handler('icms_member');
			$group = $member_handler->getUserBestGroup((is_object(icms::$user) ? icms::$user->getVar('uid') : 0));
			$icmsConfig['startpage'] = $icmsConfig['startpage'][$group];
		}

		$startMod = ($icmsConfig['startpage'] == '--') ? 'system' : $icmsConfig['startpage'];

		// setting the full and relative url of the actual page
		$clean_request = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
		$fullurl = icms::$urls['http'] . icms::$urls['httphost'] . $clean_request;
		$url = substr(str_replace(ICMS_URL, '', $fullurl), 1);

		$icms_page_handler = icms::handler('icms_data_page');
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('page_url', $fullurl));
		if (!empty($url)) $criteria->add(new icms_db_criteria_Item('page_url', $url), 'OR');
		$pages = $icms_page_handler->getCount($criteria);

		if ($pages > 0) {
			// we have a sym-link defined for this page
			$pages = $icms_page_handler->getObjects($criteria);
			$page = $pages[0];
			$purl = filter_var($page->getVar('page_url'), FILTER_SANITIZE_URL);
			$mid = (int) $page->getVar('page_moduleid');
			$pid = $page->getVar('page_id');
			$module_handler = icms::handler('icms_module');
			$module = $module_handler->get($mid);
			$dirname = $module->getVar('dirname');
			$isStart = ($startMod == $mid.'-'.$pid);
		} else {
			// we don't have a sym-link for this page
			if (is_object($icmsModule)) {
				$mid = (int) $icmsModule->getVar('mid');
				$dirname = $icmsModule->getVar('dirname');
				$isStart = (substr($_SERVER['PHP_SELF'], -9) == 'index.php' && $startMod == $dirname);
			} else {
				$mid = 1;
				$dirname = 'system';
				$isStart = !empty($GLOBALS['xoopsOption']['show_cblock']);
			}
			$pid = 0;
		}
/* determine the visitor's start page and update the request based on that? */
		if ($isStart) {
			self::$modid = array('module' => 0, 'page' => 1, 'isStart' => $isStart);
		} else {
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('page_status', 1));
			$pages = $icms_page_handler->getObjects($criteria);
			$pid = 0;
			foreach ($pages as $page) {
				$purl = filter_var($page->getVar('page_url'), FILTER_SANITIZE_URL);
				if (substr($purl, -1) == '*') {
					$purl = substr($purl, 0, -1);
					if (substr($url, 0, strlen($purl)) == $purl || substr($fullurl, 0, strlen($purl)) == $purl) {
						$pid = $page->getVar('page_id');
						break;
					}
				} else {
					if ($purl == $url || $purl == $fullurl) {
						$pid = $page->getVar('page_id');
						break;
					}
				}
			}
			self::$modid = array('module' => $mid, 'page' => $pid, 'isStart' => $isStart);
		}

		return self::$modid;
	}

	public function generateCacheId($cache_id) {
		if ($this->theme) {
			$cache_id = $this->theme->generateCacheId($cache_id);
		}
		return $cache_id;
	}

	/**
	 * The lame type workaround will change
	 * bid is added temporarily as workaround for specific block manipulation
	 *
	 * @param object $xobject
	 * @param object $template
	 * @return array
	 */
	public function buildBlock($xobject, &$template) {
		global $icmsConfigPersona;
		$bid = $xobject->getVar('bid');
		if ($icmsConfigPersona['editre_block'] == TRUE) {
			if (icms::$user && count($this->uagroups) > 0) {
				$url = base64_encode(str_replace(ICMS_URL, '', icms::$urls['http'] . $_SERVER['HTTP_HOST'] . filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL)));
				$titlebtns = '&nbsp;<a href="#" onclick="$(\'#ed_block_' . $bid . '\').dialog(\'open\'); return false;"><img src="' . ICMS_IMAGES_SET_URL . '/actions/configure.png" title="' . _EDIT .' '. _BLOCK_ID .' '. $bid .'" alt="' . _EDIT . '"  /></a>'
					. '<button style="display: none;"><div id="ed_block_' . $bid . '">'
					. "<a href='" . ICMS_MODULES_URL . "/system/admin.php?fct=blocksadmin&amp;op=visible&amp;bid=" . $bid . "&amp;rtn=$url'> <img src='" . ICMS_IMAGES_SET_URL . "/actions/button_cancel.png' alt='" . _INVISIBLE . "'  /> " . _INVISIBLE . "</a><br />"
					. "<a href='" . ICMS_MODULES_URL . "/system/admin.php?fct=blocksadmin&amp;op=clone&amp;bid=" . $bid . "'> <img src='" . ICMS_IMAGES_SET_URL . "/actions/editcopy.png' alt='" . _CLONE . "'  /> " . _CLONE . "</a><br />"
					. "<a href='" . ICMS_MODULES_URL . "/system/admin.php?fct=blocksadmin&amp;op=mod&amp;bid=" . $bid . "'> <img src='" . ICMS_IMAGES_SET_URL . "/actions/edit.png' alt='" . _EDIT . "'  /> " . _EDIT . "</a><br />"
					. "<a href='" . ICMS_MODULES_URL . "/system/admin.php?fct=blocksadmin&amp;op=up&amp;bid=" . $bid . "&amp;rtn=$url'> <img src='" . ICMS_IMAGES_SET_URL . "/actions/up.png' alt='" . _UP . "'  /> " . _UP . "</a><br />"
					. "<a href='" . ICMS_MODULES_URL . "/system/admin.php?fct=blocksadmin&amp;op=down&amp;bid=" . $bid . "&amp;rtn=$url'> <img src='" . ICMS_IMAGES_SET_URL . "/actions/down.png' alt='" . _DOWN . "'  /> " . _DOWN . "</a>";
				if ($xobject->getVar('dirname') == '') {
					$titlebtns .= "<br /><a href='" . ICMS_MODULES_URL . "/system/admin.php?fct=blocksadmin&amp;op=del&amp;bid=" . $bid . "'> <img src='" . ICMS_IMAGES_SET_URL . "/actions/editdelete.png' alt='" . _DELETE . "'  /> " . _DELETE . "</a>";
				}
				$titlebtns .= '</div></button>';
				$titlebtns .= '<script type="text/javascript">
					$(function() {
						$(\'#ed_block_' . $bid . '\').dialog({
							bgiframe: true,
							//height: 140,
							autoOpen: false,
							modal: true
						});
					});
					</script>
				';
			} else {
				$titlebtns = '';
			}
		} else {
			$titlebtns = '';
		}

		$block = array(
		    'id' => $bid,
		    'module' => $xobject->getVar('dirname'),
		    'title' => $xobject->getVar('title') . $titlebtns,
		    'weight' => $xobject->getVar('weight'),
		    'lastmod' => $xobject->getVar('last_modified')
		);

		$bcachetime = (int) ($xobject->getVar('bcachetime'));
		//$template = new icms_view_Tpl();
		if (empty($bcachetime)) {
			$template->caching = 0;
		} else {
			$template->caching = 2;
			$template->cache_lifetime = $bcachetime;
		}
		$tplName = ($tplName = $xobject->getVar('template')) ? "db:$tplName" : "db:system_block_dummy.html";
		$cacheid = $this->generateCacheId(
			'blk_' . $xobject->getVar('dirname', 'n') . '_'
			. $bid
		);

		if (! $bcachetime || ! $template->is_cached($tplName, $cacheid)) {
			icms::$logger->addBlock($xobject->getVar('name'));
			if (! ($bresult = $xobject->buildBlock())) {
				return FALSE;
			}
			$template->assign('block', $bresult);
			$block['content'] = $template->fetch($tplName, $cacheid);
		} else {
			icms::$logger->addBlock($xobject->getVar('name'), TRUE, $bcachetime);
			$block['content'] = $template->fetch($tplName, $cacheid);
		}
		return $block;
	}
}

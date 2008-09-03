<?php
/**
 * xos_logos_PageBuilder component class file
 *
 * @copyright	The XOOPS project http://www.xoops.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package      xos_logos
 * @subpackage   xos_logos_PageBuilder
 * @version		$Id: theme_blocks.php 1029 2007-09-09 03:49:25Z phppp $
 * @author       Skalpa Keo <skalpa@xoops.org>
 * @since        2.3.0
 */
/**
 * This file cannot be requested directly
 */
if (! defined ( 'XOOPS_ROOT_PATH' ))
	exit ();

include_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
include_once XOOPS_ROOT_PATH . '/class/template.php';

/**
 * xos_logos_PageBuilder main class
 *
 * @package     xos_logos
 * @subpackage  xos_logos_PageBuilder
 * @author 		Skalpa Keo
 * @since       2.3.0
 */
class xos_logos_PageBuilder {
	
	var $theme = false;
	
	var $blocks = array ( );
	
	function xoInit($options = array()) {
		$this->retrieveBlocks ();
		if ($this->theme) {
			$this->theme->template->assign_by_ref ( 'xoBlocks', $this->blocks );
		}
		return true;
	}
	
	/**
	 * Called before a specific zone is rendered
	 */
	function preRender($zone = '') {
	}
	/**
	 * Called after a specific zone is rendered
	 */
	function postRender($zone = '') {
	}
	
	function retrieveBlocks() {
		global $xoops, $xoopsUser, $xoopsModule, $xoopsConfig;
		
        $groups = @is_object ( $xoopsUser ) ? $xoopsUser->getGroups () : array (XOOPS_GROUP_ANONYMOUS );
		
		//Getting the start module and page configured in the admin panel
		if (is_array ( $xoopsConfig ['startpage'] )) {
			$spgi = array_keys ( $xoopsConfig ['startpage'] );
			
			if (in_array ( XOOPS_GROUP_ADMIN, $groups ) && in_array ( XOOPS_GROUP_ADMIN, $spgi )) {
				$match = XOOPS_GROUP_ADMIN;
			} else {
				foreach ( $groups as $group ) {
					if (in_array ( $group, $spgi )) {
						$match = $group;
					}
				}
			}
			$xoopsConfig ['startpage'] = $xoopsConfig ['startpage'] [$match];
		}
		$startMod = ($xoopsConfig ['startpage'] == '--') ? 'system' : $xoopsConfig ['startpage']; //Getting the top page		
		
		//Setting the full and relative url of the actual page
		$fullurl = urldecode ( "http://" . $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"] );
		$url = urldecode ( substr ( str_replace ( XOOPS_URL, '', $fullurl ), 1 ) );
		
		$page_handler = & xoops_gethandler ( 'page' );
		$criteria = new CriteriaCompo ( new Criteria ( 'page_url', $fullurl ) );
		if (! empty ( $url )) {
			$criteria->add ( new Criteria ( 'page_url', $url ), 'OR' );
		}
		$pages = $page_handler->getCount ( $criteria );
		
		if ($pages > 0) { //We have a sym-link defined for this page
			$pages = $page_handler->getObjects ( $criteria );
			$page = $pages [0];
			$purl = $page->getVar ( 'page_url' );
			$mid = $page->getVar ( 'page_moduleid' );
			$pid = $page->getVar ( 'page_id' );
			$module_handler = & xoops_gethandler ( 'module' );
			$module = & $module_handler->get ( $mid );
			$dirname = $module->getVar ( 'dirname' );
			$isStart = ($startMod == $mid . '-' . $pid || $startMod == $dirname);
		} else { //Don't have a sym-link for this page
			if (@is_object ( $xoopsModule )) {
				list ( $mid, $dirname ) = array ($xoopsModule->getVar ( 'mid' ), $xoopsModule->getVar ( 'dirname' ) );
				$isStart = (substr ( $_SERVER ['PHP_SELF'], - 9 ) == 'index.php' && $startMod == $dirname);
			} else {
				list ( $mid, $dirname ) = array (1, 'system' );
				$isStart = ! @empty ( $GLOBALS ['xoopsOption'] ['show_cblock'] );
			}
			$pid = 0;
		}
		
		if ($isStart) {
			$modid = '0-1';
		} else {
			$page_handler = & xoops_gethandler ( 'page' );
			$criteria = new CriteriaCompo ( new Criteria ( 'page_status', 1 ) );
			$pages = $page_handler->getObjects ( $criteria );
			$pid = 0;
			foreach ( $pages as $page ) {
				$purl = $page->getVar ( 'page_url' );
				if (substr ( $purl, - 1 ) == '*') {
					$purl = substr ( $purl, 0, - 1 );
					if (substr ( $url, 0, strlen ( $purl ) ) == $purl || substr ( $fullurl, 0, strlen ( $purl ) ) == $purl) {
						$pid = $page->getVar ( 'page_id' );
						break;
					}
				} else {
					if ($purl == $url || $purl == $fullurl) {
						$pid = $page->getVar ( 'page_id' );
						break;
					}
				}
			}
			$modid = $mid . '-' . $pid;
		}
		
		# Adding dynamic block area/position system - TheRpLima - 2007-10-21
		/*
		$oldzones = array(
		XOOPS_SIDEBLOCK_LEFT				=> 'canvas_left',
		XOOPS_SIDEBLOCK_RIGHT				=> 'canvas_right',
		XOOPS_CENTERBLOCK_LEFT				=> 'page_topleft',
		XOOPS_CENTERBLOCK_CENTER			=> 'page_topcenter',
		XOOPS_CENTERBLOCK_RIGHT				=> 'page_topright',
		XOOPS_CENTERBLOCK_BOTTOMLEFT		=> 'page_bottomleft',
		XOOPS_CENTERBLOCK_BOTTOM			=> 'page_bottomcenter',
		XOOPS_CENTERBLOCK_BOTTOMRIGHT		=> 'page_bottomright',
		);
		*/
		$xblock = new XoopsBlock ( );
		$oldzones = $xblock->getBlockPositions ();
		#
		foreach ( $oldzones as $zone ) {
			$this->blocks [$zone] = array ( );
		}
		if ($this->theme) {
			$template = & $this->theme->template;
			$backup = array ($template->caching, $template->cache_lifetime );
		} else {
			$template = & new XoopsTpl ( );
		}
		$xoopsblock = new XoopsBlock ( );
		$block_arr = array ( );
		$block_arr = $xoopsblock->getAllByGroupModule ( $groups, $modid, $isStart, XOOPS_BLOCK_VISIBLE );
		foreach ( $block_arr as $block ) {
			$side = $oldzones [$block->getVar ( 'side' )];
			if ($var = $this->buildBlock ( $block, $template )) {
				$this->blocks [$side] [$var ["id"]] = $var;
			}
		}
		if ($this->theme) {
			list ( $template->caching, $template->cache_lifetime ) = $backup;
		}
	}
	
	function generateCacheId($cache_id) {
		if ($this->theme) {
			$cache_id = $this->theme->generateCacheId ( $cache_id );
		}
		return $cache_id;
	}
	
	function buildBlock($xobject, &$template) {
		// The lame type workaround will change
		// bid is added temporarily as workaround for specific block manipulation
		global $xoopsUser, $xoopsConfigPersona;
		if ($xoopsConfigPersona ['editre_block'] == 1) {
			if ($xoopsUser && $xoopsUser->isAdmin ()) {
				$titlebtns = ' <a href="#" onclick="changeDisplay(\'ed_block_' . $xobject->getVar ( 'bid' ) . '\'); return false;"><img src="' . XOOPS_URL . '/modules/system/images/edit_med.png" title="' . _EDIT . '" alt="' . _EDIT . '"  /></a><div id="ed_block_' . $xobject->getVar ( 'bid' ) . '" class="ed_block_box">';
				$titlebtns .= "<a href=" . XOOPS_URL . "/modules/system/admin.php?fct=blocksadmin&op=changestatus&bid=" . $xobject->getVar ( 'bid' ) . "&sts=1> <img src=" . XOOPS_URL . "/modules/system/images/off.png" . " title=" . _INVISIBLE . " alt=" . _INVISIBLE . "  /> " . _INVISIBLE . "</a><br />";
				$titlebtns .= "<a href=" . XOOPS_URL . "/modules/system/admin.php?fct=blocksadmin&op=clone&bid=" . $xobject->getVar ( 'bid' ) . "> <img src=" . XOOPS_URL . "/modules/system/images/clone_med.png" . " title=" . _CLONE . " alt=" . _CLONE . "  /> " . _CLONE . "</a><br />";
				$titlebtns .= "<a href=" . XOOPS_URL . "/modules/system/admin.php?fct=blocksadmin&op=edit&bid=" . $xobject->getVar ( 'bid' ) . "> <img src=" . XOOPS_URL . "/modules/system/images/edit_med.png" . " title=" . _EDIT . " alt=" . _EDIT . "  /> " . _EDIT . "</a>";
				if ($xobject->getVar ( 'dirname' ) == '') {
					$titlebtns .= "<br /><a href=" . XOOPS_URL . "/modules/system/admin.php?fct=blocksadmin&op=delete&bid=" . $xobject->getVar ( 'bid' ) . "> <img src=" . XOOPS_URL . "/modules/system/images/delete_med.png" . " title=" . _DELETE . " alt=" . _DELETE . "  /> " . _DELETE . "</a>";
				}
				$titlebtns .= '</div>';
			} else {
				$titlebtns = '';
			}
		} else {
			$titlebtns = '';
		}
		
		$block = array ('id' => $xobject->getVar ( 'bid' ), 'module' => $xobject->getVar ( 'dirname' ), 'title' => $xobject->getVar ( 'title' ) . $titlebtns, //'name' => strtolower( preg_replace( '/[^0-9a-zA-Z_]/', '', str_replace( ' ', '_', $xobject->getVar( 'name' ) ) ) ),
'weight' => $xobject->getVar ( 'weight' ), 'lastmod' => $xobject->getVar ( 'last_modified' ) );
		
		//global $xoopsLogger;
		

		$xoopsLogger = & XoopsLogger::instance ();
		
		$bcachetime = intval ( $xobject->getVar ( 'bcachetime' ) );
		//$template =& new XoopsTpl();
		if (empty ( $bcachetime )) {
			$template->caching = 0;
		} else {
			$template->caching = 2;
			$template->cache_lifetime = $bcachetime;
		}
		$tplName = ($tplName = $xobject->getVar ( 'template' )) ? "db:$tplName" : "db:system_block_dummy.html";
		$cacheid = $this->generateCacheId ( 'blk_' . $xobject->getVar ( 'dirname', 'n' ) . '_' . $xobject->getVar ( 'bid' )/*, $xobject->getVar( 'show_func', 'n' )*/ );
		
		if (! $bcachetime || ! $template->is_cached ( $tplName, $cacheid )) {
			$xoopsLogger->addBlock ( $xobject->getVar ( 'name' ) );
			if (! ($bresult = $xobject->buildBlock ())) {
				return false;
			}
			$template->assign ( 'block', $bresult );
			$block ['content'] = $template->fetch ( $tplName, $cacheid );
		} else {
			$xoopsLogger->addBlock ( $xobject->getVar ( 'name' ), true, $bcachetime );
			$block ['content'] = $template->fetch ( $tplName, $cacheid );
		}
		return $block;
	}

}

<?php
// $Id: backend.php 1131 2007-10-24 16:10:53Z dugris $
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

include 'mainfile.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';
include_once XOOPS_ROOT_PATH.'/modules/news/class/class.newsstory.php';
if (function_exists('mb_http_output')) {
	mb_http_output('pass');
}
header ('Content-Type:text/xml; charset=utf-8');
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600);
if (!$tpl->is_cached('db:system_rss.html')) {
	$sarray = NewsStory::getAllPublished(10, 0);
	if (is_array($sarray)) {
		$tpl->assign('channel_title', xoops_utf8_encode(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));
		$tpl->assign('channel_link', XOOPS_URL.'/');
		$tpl->assign('channel_desc', xoops_utf8_encode(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
		$tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
		$tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
		$tpl->assign('channel_editor', $xoopsConfig['adminmail']);
		$tpl->assign('channel_category', 'News');
		$tpl->assign('channel_generator', 'XOOPS');
		$tpl->assign('channel_language', _LANGCODE);
		$tpl->assign('image_url', XOOPS_URL.'/images/logo.gif');
		$dimention = getimagesize(XOOPS_ROOT_PATH.'/images/logo.gif');
		if (empty($dimention[0])) {
			$width = 88;
		} else {
			$width = ($dimention[0] > 144) ? 144 : $dimention[0];
		}
		if (empty($dimention[1])) {
			$height = 31;
		} else {
			$height = ($dimention[1] > 400) ? 400 : $dimention[1];
		}
		$tpl->assign('image_width', $width);
		$tpl->assign('image_height', $height);
		$count = $sarray;
		foreach ($sarray as $story) {
			$tpl->append('items', array('title' => xoops_utf8_encode(htmlspecialchars($story->title(), ENT_QUOTES)), 'link' => XOOPS_URL.'/modules/news/article.php?storyid='.$story->storyid(), 'guid' => XOOPS_URL.'/modules/news/article.php?storyid='.$story->storyid(), 'pubdate' => formatTimestamp($story->published(), 'rss'), 'description' => xoops_utf8_encode(htmlspecialchars($story->hometext(), ENT_QUOTES))));
		}
	}
}
$GLOBALS['xoopsLogger']->activated = false;
$tpl->display('db:system_rss.html');
?>
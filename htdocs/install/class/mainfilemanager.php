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

/**
 * Mainfile Manager Class
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	installer
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: mainfilemanager.php 12329 2013-09-19 13:53:36Z skenow $
 */

/**
 * mainfile manager for XOOPS installer
 *
 * @author Haruki Setoyama  <haruki@planewave.org>
 * @version $Id: mainfilemanager.php 12329 2013-09-19 13:53:36Z skenow $
 * @access public
 **/
class mainfile_manager {

	public $path = '../mainfile.php';
	public $distfile = './templates/mainfile.dist.php';
	public $rewrite = array();

	public $report = '';
	public $error = false;

	public function __construct() {
	}

	public function setRewrite($def, $val) {
		$this->rewrite[$def] = $val;
	}

	public function copyDistFile() {
		if (!copy($this->distfile, $this->path)) {
			$this->report .= _NGIMG . sprintf(_INSTALL_L126, "<b>" . $this->path . "</b>") . "<br />\n";
			$this->error = true;
			return false;
		}
		$this->report .= _OKIMG . sprintf(_INSTALL_L125, "<b>" . $this->path . "</b>", "<b>" . $this->distfile . "</b>") . "<br />\n";
		return true;
	}

	public function doRewrite() {
		clearstatcache();
		$content = file_get_contents($this->path);
		if ($content === false) {
			$this->error = true;
			return false;
		}

		foreach ($this->rewrite as $key => $val) {
			if ($key === 'PROTECTOR1' || $key === 'PROTECTOR2') {
				// the value is a complete statement which replaces the whole define() call
				$pattern = '/define\(([\'"])' . preg_quote($key, '/') . '\1,\s*(?:[0-9]+|([\'"])(.*?)\2)\s*\)/';
				$newContent = preg_match($pattern, $content)
					? preg_replace_callback($pattern, static function () use ($val) {
						return (string) $val;
					}, $content)
					: null;
			} else {
				$newContent = icms_install_rewrite_define($content, $key, $val);
			}

			if ($newContent === null) {
				$this->error = true;
				$this->report .= _NGIMG . sprintf(_INSTALL_L122, "<b>" . htmlspecialchars((string) $val) . "</b>") . "<br />\n";
				continue;
			}
			$content = $newContent;
			$this->report .= _OKIMG . sprintf(_INSTALL_L121, "<b>$key</b>", htmlspecialchars((string) $val)) . "<br />\n";
		}

		if (file_put_contents($this->path, $content) === false) {
			$this->error = true;
			return false;
		}

		return true;
	}

	public function report() {
		$content = "<table align='center'><tr><td align='left'>\n";
		$content .= $this->report;
		$content .= "</td></tr></table>\n";
		return $content;
	}

	public function error() {
		return $this->error;
	}
}

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
 * Handles all functions related to downloading zipfiles within ImpressCMS
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		File
 * @version		SVN: $Id: ZipDownloader.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH') or exit();
/**
 * Handles compression of files in zip format and sending to the browser for download
 *
 * @category	ICMS
 * @package		File
 * @author		xoops.org
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 */
class icms_file_ZipDownloader extends icms_file_DownloadHandler {
	/**
	 * Constructor
	 *
	 * @param	string     $ext             extension of the file
	 * @param	string    $mimyType    the mimytype (mimetype) of the file
	 */
	public function __construct($ext = '.zip', $mimyType = 'application/x-zip') {
		$this->archiver = new icms_file_ZipFileHandler();
		$this->ext      = trim($ext);
		$this->mimeType = trim($mimyType);
	}

	/**
	 * Adds file to the zip file
	 *
	 * @param	string    $filepath      path of the file to add
	 * @param	string    $newfilename    name of the newly created file
	 */
	public function addFile($filepath, $newfilename=null) {
		// Read in the file's contents
		$fp = fopen($filepath, "r");
		$data = fread($fp, filesize($filepath));
		fclose($fp);
		$filename = (isset($newfilename) && trim($newfilename) != '') ? trim($newfilename) : $filepath;
		$this->archiver->addFile($data, $filename, filemtime($filename));
	}

	/**
	 * Adds binary file to the zip file
	 *
	 * @param	string    $filepath      path of the file to add
	 * @param	string    $newfilename    name of the newly created file
	 */
	public function addBinaryFile($filepath, $newfilename=null) {
		// Read in the file's contents
		$fp = fopen($filepath, "rb");
		$data = fread($fp, filesize($filepath));
		fclose($fp);
		$filename = (isset($newfilename) && trim($newfilename) != '') ? trim($newfilename) : $filepath;
		$this->archiver->addFile($data, $filename, filemtime($filename));
	}

	/**
	 * Adds file data to the zip file
	 *
	 * @param	string    &$data        data array
	 * @param	string    $filename     filename to add the data to
	 * @param	string    $time         timestamp
	 */
	public function addFileData(&$data, $filename, $time=0) {
		$this->archiver->addFile($data, $filename, $time);
	}

	/**
	 * Adds binary file data to the zip file
	 *
	 * @param	string    &$data        data array
	 * @param	string    $filename     filename to add the data to
	 * @param	string    $time         timestamp
	 */
	public function addBinaryFileData(&$data, $filename, $time=0) {
		self::addFileData($data, $filename, $time);
	}

	/**
	 * downloads the file
	 *
	 * @param   string  $name     filename to download
	 * @param   bool    $gzip     turn on gzip compression
	 */
	public function download($name, $gzip = true) {
		parent::_header($name . $this->ext);
		echo $this->archiver->file();
	}
}

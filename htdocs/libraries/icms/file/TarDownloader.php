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
 * The Tar files downloader class
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		File
 *
 * @version		SVN: $Id: TarDownloader.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH') or exit();

/**
 * Send tar files through a http socket
 *
 * @category	ICMS
 * @package		File
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 */
class icms_file_TarDownloader extends icms_file_DownloadHandler {

	/**
	 * Constructor
	 *
	 * @param string $ext       file extension
	 * @param string $mimyType  Mimetype
	 **/
	public function __construct($ext = '.tar.gz', $mimyType = 'application/x-gzip') {
		$this->archiver = new icms_file_TarFileHandler();
		$this->ext = trim($ext);
		$this->mimeType = trim($mimyType);
	}

	/**
	 * Add a file to the archive
	 *
	 * @param   string  $filepath       Full path to the file
	 * @param   string  $newfilename    Filename (if you don't want to use the original)
	 **/
	public function addFile($filepath, $newfilename = null) {
		$this->archiver->addFile($filepath);
		if (isset($newfilename)) {
			// dirty, but no other way
			for ($i = 0; $i < $this->archiver->numFiles; $i++) {
				if ($this->archiver->files[$i]['name'] == $filepath) {
					$this->archiver->files[$i]['name'] = trim($newfilename);
					break;
				}
			}
		}
	}

	/**
	 * Add a binary file to the archive
	 *
	 * @param   string  $filepath       Full path to the file
	 * @param   string  $newfilename    Filename (if you don't want to use the original)
	 **/
	public function addBinaryFile($filepath, $newfilename = null) {
		$this->archiver->addFile($filepath, true);
		if (isset($newfilename)) {
			// dirty, but no other way
			for ($i = 0; $i < $this->archiver->numFiles; $i++) {
				if ($this->archiver->files[$i]['name'] == $filepath) {
					$this->archiver->files[$i]['name'] = trim($newfilename);
					break;
				}
			}
		}
	}

	/**
	 * Add a dummy file to the archive
	 *
	 * @param   string  $data       Data to write
	 * @param   string  $filename   Name for the file in the archive
	 * @param   integer $time
	 **/
	public function addFileData(&$data, $filename, $time=0) {
		$dummyfile = ICMS_CACHE_PATH . '/dummy_' . time() . '.html';
		$fp = fopen($dummyfile, 'w');
		fwrite($fp, $data);
		fclose($fp);
		$this->archiver->addFile($dummyfile);
		unlink($dummyfile);

		// dirty, but no other way
		for ($i = 0; $i < $this->archiver->numFiles; $i++) {
			if ($this->archiver->files[$i]['name'] == $dummyfile) {
				$this->archiver->files[$i]['name'] = $filename;
				if ($time != 0) {
					$this->archiver->files[$i]['time'] = $time;
				}
				break;
			}
		}
	}

	/**
	 * Add a binary dummy file to the archive
	 *
	 * @param   string  $data   Data to write
	 * @param   string  $filename   Name for the file in the archive
	 * @param   integer $time
	 **/
	public function addBinaryFileData(&$data, $filename, $time=0) {
		$dummyfile = ICMS_CACHE_PATH . '/dummy_' . time() . '.html';
		$fp = fopen($dummyfile, 'wb');
		fwrite($fp, $data);
		fclose($fp);
		$this->archiver->addFile($dummyfile, true);
		unlink($dummyfile);

		// dirty, but no other way
		for ($i = 0; $i < $this->archiver->numFiles; $i++) {
			if ($this->archiver->files[$i]['name'] == $dummyfile) {
				$this->archiver->files[$i]['name'] = $filename;
				if ($time != 0) {
					$this->archiver->files[$i]['time'] = $time;
				}
				break;
			}
		}
	}

	/**
	 * Send the file to the client
	 *
	 * @param   string  $name   Filename
	 * @param   boolean $gzip   Use GZ compression
	 **/
	public function download($name, $gzip = true) {
		$this->_header($name . $this->ext);
		echo $this->archiver->toTarOutput($name . $this->ext, $gzip);
	}
}

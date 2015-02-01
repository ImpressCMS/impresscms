<?php
// $Id: uploader.php 12329 2013-09-19 13:53:36Z skenow $
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
 * The uploader class of media files
 * @copyright    http://www.xoops.org/ The XOOPS Project
 * @copyright    XOOPS_copyrights.txt
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license      LICENSE.txt
 * @package      core
 * @since        XOOPS
 * @author       http://www.xoops.org The XOOPS Project
 * @version      $Id: uploader.php 12329 2013-09-19 13:53:36Z skenow $
 * @deprecated	Use icms_file_MediaUploadHandler instead
 * @todo Remove in version 1.4
*/

class IcmsMediaUploader extends icms_file_MediaUploadHandler {
	private $_deprecated;
	public function __construct($uploadDir, $allowedMimeTypes, $maxFileSize = 0, $maxWidth = null, $maxHeight = null) {
		parent::__construct($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth, $maxHeight);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_file_MediaUploadHandler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 * XoopsMediaUploader
 * @copyright    The XOOPS Project <http://www.xoops.org/>
 * @copyright    XOOPS_copyrights.txt
 * @license      LICENSE.txt
 * @since        XOOPS
 * @author       The XOOPS Project Community <http://www.xoops.org>
 * @deprecated
 */
class XoopsMediaUploader extends icms_file_MediaUploadHandler {

	private $_deprecated;
	/**
	 * @deprecated	Use icms_file_MediaUploadHandler, instead
	 * @todo		Remove in version 1.4
	 */
	function XoopsMediaUploader($uploadDir, $allowedMimeTypes, $maxFileSize = 0, $maxWidth = null, $maxHeight = null) {
		parent::__construct($uploadDir, $allowedMimeTypes, $maxFileSize, $maxWidth, $maxHeight);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_file_MediaUploadHandler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}

}

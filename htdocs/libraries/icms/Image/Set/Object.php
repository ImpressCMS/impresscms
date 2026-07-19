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
* Manage of imagesets baseclass
* Image sets - the image directory within a module - are part of templates
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	    LICENSE.txt
* @category	    ICMS
* @package		Image
* @subpackage	Set
* @since	    XOOPS
* @author	    http://www.xoops.org The XOOPS Project
* @author	    modified by UnderDog <underdog@impresscms.org>
* @version	    $Id: imageset.php 19775 2010-07-11 18:54:25Z malanciault $
*/

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * An imageset
 *
 * These sets are managed through a {@link icms_image_set_Handler} object
 *
 * @package     kernel
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_image_set_Object extends XoopsObject
{
    /**
     * Constructor
     *
     */
  	function __construct() {
  		$this->XoopsObject();
  		$this->initVar('imgset_id', XOBJ_DTYPE_INT, null, false);
  		$this->initVar('imgset_name', XOBJ_DTYPE_TXTBOX, null, true, 50);
  		$this->initVar('imgset_refid', XOBJ_DTYPE_INT, 0, false);
  	}
}

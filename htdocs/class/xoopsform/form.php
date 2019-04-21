<?php
// $Id: form.php 12329 2013-09-19 13:53:36Z skenow $
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
 * Creates a form object (Base Class)
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	XoopsForms
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: form.php 12329 2013-09-19 13:53:36Z skenow $
 */

/**
 * Abstract base class for forms
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @author  Taiwen Jiang    <phppp@users.sourceforge.net>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 *
 * @package     kernel
 * @subpackage  form
 * @deprecated	Use icms_form_Base, instead
 * @todo		Remove in version 1.4
 */
abstract class XoopsForm extends icms_form_Base {
	private $_deprecated;
	
	public function XoopsForm($title, $name, $action, $method = "post", $addtoken = false) {
		self::__construct($title, $name, $action, $method, $addtoken);
	}
	public function __construct($title, $name, $action, $method = "post", $addtoken = false) {
		parent::__construct($title, $name, $action, $method, $addtoken);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_form_elements_Button', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
?>

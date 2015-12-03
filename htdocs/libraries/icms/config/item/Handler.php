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
 * Manage configuration items
 *
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since        XOOPS
 * @author       Kazumi Ono (aka onokazo)
 * @author       http://www.xoops.org The XOOPS Project
 * @version      $Id:Handler.php 19775 2010-07-11 18:54:25Z malanciault $
 */

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**#@+
 * Config type
 */
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_MAIN instead!
 */
define('ICMS_CONF', \icms_config_Handler::CATEGORY_MAIN);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_USER instead!
 */
define('ICMS_CONF_USER', \icms_config_Handler::CATEGORY_USER);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_METAFOOTER instead!
 */
define('ICMS_CONF_METAFOOTER', \icms_config_Handler::CATEGORY_METAFOOTER);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_CENSOR instead!
 */
define('ICMS_CONF_CENSOR', \icms_config_Handler::CATEGORY_CENSOR);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_SEARCH instead!
 */
define('ICMS_CONF_SEARCH', \icms_config_Handler::CATEGORY_SEARCH);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_MAILER instead!
 */
define('ICMS_CONF_MAILER', \icms_config_Handler::CATEGORY_MAILER);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_AUTH instead!
 */
define('ICMS_CONF_AUTH', \icms_config_Handler::CATEGORY_AUTH);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_MULILANGUAGE instead!
 */
define('ICMS_CONF_MULILANGUAGE', \icms_config_Handler::CATEGORY_MULILANGUAGE);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_CONTENT instead!
 */
define('ICMS_CONF_CONTENT', \icms_config_Handler::CATEGORY_CONTENT);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_PERSONA instead!
 */
define('ICMS_CONF_PERSONA', \icms_config_Handler::CATEGORY_PERSONA);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_CAPTCHA instead!
 */
define('ICMS_CONF_CAPTCHA', \icms_config_Handler::CATEGORY_CAPTCHA);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_PLUGINS instead!
 */
define('ICMS_CONF_PLUGINS', \icms_config_Handler::CATEGORY_PLUGINS);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_AUTOTASKS instead!
 */
define('ICMS_CONF_AUTOTASKS', \icms_config_Handler::CATEGORY_AUTOTASKS);
/**
 * @deprecated 2.0 Use \icms_config_Handler::CATEGORY_PURIFIER instead!
 */
define('ICMS_CONF_PURIFIER', \icms_config_Handler::CATEGORY_PURIFIER);
/**#@-*/

/**
 * Configuration handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of configuration class objects.
 *
 * @author      Kazumi Ono <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @package	ICMS\Config\Item
 */
class icms_config_Item_Handler extends icms_ipf_Handler {

	public function __construct(&$db) {
            parent::__construct($db, 'config_item', 'conf_id', 'conf_name', 'conf_value', 'icms', 'config', 'conf_id');
        }
}

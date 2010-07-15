<?php
/**
 * Manage of original Xoops Objects
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: object.php 19419 2010-06-13 22:52:12Z skenow $
 * @deprecated	Moving to new architecture
 * @todo		Remove in version 1.4
 */

/**
 * @package kernel
 * @copyright copyright &copy; 2000 XOOPS.org
 */

/**#@+
 * Xoops object datatype
 *
 **/
define('XOBJ_DTYPE_TXTBOX', 1);
define('XOBJ_DTYPE_TXTAREA', 2);
define('XOBJ_DTYPE_INT', 3);
define('XOBJ_DTYPE_URL', 4);
define('XOBJ_DTYPE_EMAIL', 5);
define('XOBJ_DTYPE_ARRAY', 6);
define('XOBJ_DTYPE_OTHER', 7);
define('XOBJ_DTYPE_SOURCE', 8);
define('XOBJ_DTYPE_STIME', 9);
define('XOBJ_DTYPE_MTIME', 10);
define('XOBJ_DTYPE_LTIME', 11);

define('XOBJ_DTYPE_SIMPLE_ARRAY', 101);
define('XOBJ_DTYPE_CURRENCY', 200);
define('XOBJ_DTYPE_FLOAT', 201);
define('XOBJ_DTYPE_TIME_ONLY', 202);
define('XOBJ_DTYPE_URLLINK', 203);
define('XOBJ_DTYPE_FILE', 204);
define('XOBJ_DTYPE_IMAGE', 205);
define('XOBJ_DTYPE_FORM_SECTION', 210);
define('XOBJ_DTYPE_FORM_SECTION_CLOSE', 211);
/**#@-*/

//include_once "xoopspluginloader.php";

/**
 * Base class for all objects in the Xoops kernel (and beyond)
 *
 * @author Kazumi Ono (AKA onokazu)
 * @copyright copyright &copy; 2000 XOOPS.org
 * @package kernel
 * @deprecated	Use icms_core_Object, instead
 * @todo		Remove in version 1.4
 **/
class XoopsObject extends icms_core_Object
{
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_core_Object', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}

}

/**
 * XOOPS object handler class.
 * This class is an abstract class of handler classes that are responsible for providing
 * data access mechanisms to the data source of its corresponsing data objects
 * @package kernel
 * @abstract
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright &copy; 2000 The XOOPS Project
 * @deprecated	Use icms_core_ObjectHandler, instead
 * @todo		Remove in version 1.4
 */
class XoopsObjectHandler extends icms_core_ObjectHandler
{
	private $_deprecated;
	public function __construct(&$db) {
		parent::__construct($db);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_core_ObjectHandler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}

}


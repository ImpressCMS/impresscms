<?php
/**
* Version information about ImpressCMS
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		marcan <marcan@impresscms.org>
* @version		$Id: version.php 897 2008-02-15 19:53:31Z malanciault $
*/

define("ICMS_VERSION_NAME",'ImpressCMS 1.0.1 "Janus" Final');

// For backward compatibility with XOOPS
define("XOOPS_VERSION", ICMS_VERSION_NAME);

/*
 * Version Status
 * 1  = Alpha
 * 2  = Beta
 * 3  = RC
 * 10 = Final
 */

define("ICMS_VERSION_STATUS", 10);

/*
 * Build number
 * 
 * Every release has its own build number, incrementable by 1 everytime we make a release
 */
define("ICMS_VERSION_BUILD", 7);
?>
<?php
/**
* Initiating Projax library
*
* This file is responsible for initiating the Projax library
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/

class IcmsPreloadProjax extends IcmsPreloadItem
{
	function eventStartOutputInit() {
		// just including the file... more to come
		include ( ICMS_LIBRARIES_PATH."/projax/projax.php" );
	}
}
?>
<?php
/**
* ImpressCMS Autotasks features
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.1
* @author		mekdrop <mekdrop@gmail.com>
* @version		$Id: autotasks.php 2008.07.18 17:10 $
*/

class IcmsPreloadAutotasks
	extends IcmsPreloadItem {

	/**
	 * Function to be triggered at the end of the core boot process
	 */
	function eventFinishCoreBoot() {
	    $handler = &xoops_getmodulehandler('autotasks', 'system');
	    if ($handler->needExecution()) {
	    	$handler->execTasks();
	    	$handler->startIfNeeded();
	    	if ($handler->needExit()) {
	    		exit(0);
	    	}
	    }
	}

	/**
	 * Do this event when saving item
	 *
	 * @param array config array
	 */
	function eventAfterSaveSystemAdminPreferencesItems($array) {
		if (!isset($array[IM_CONF_AUTOTASKS])) return;
		$handler = xoops_getmodulehandler('autotasks', 'system');
		$handler->virtual_config = array();
		$array = &$array[IM_CONF_AUTOTASKS];
		foreach ($array as $key => $values) {
			$handler->virtual_config[$key] = $values[0];
		}
		$system = $handler->getCurrentSystemHandler(true);
		if ($system->isEnabled()) {
			$system->stop();
		}
		$handler->virtual_config = null;
		$system = $handler->getCurrentSystemHandler(true);
		if ($rez = $system->canRun()) {
		 	$rez = $system->start($handler->getRealTasksRunningTime());
		} else {
			icms_loadLanguageFile('system', 'autotasks', true);
			xoops_error(_CO_ICMS_AUTOTASKS_INIT_ERROR);
			return false;
		}		
	}


}
?>
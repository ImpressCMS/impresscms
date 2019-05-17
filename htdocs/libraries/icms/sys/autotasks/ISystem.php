<?php
/**
 * ImpressCMS AUTOTASKSs Library - icms_sys_autotasks_ISystem interface
 *
 * @category	ICMS
 * @package		Autotasks
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version		SVN: $Id:ISystem.php 19775 2010-07-11 18:54:25Z malanciault $
 */
/**
 * ImpressCMS AUTOTASKSs Library - icms_sys_autotasks_ISystem interface
 *
 * @category	ICMS
 * @package		Autotasks
 * @since		1.2 alpha 2
 * @author		MekDrop <mekdrop@gmail.com>
 */

interface icms_sys_autotasks_ISystem {

	/**
	 * check if can run
	 * @return bool
	 */
	public function canRun();

	/**
	 * Set Checking Interval (if not enabled enables automated tasks system
	 * @param  int	$interval	interval of checking for new tasks
	 * @return bool				returns true if start was succesfull
	 */
	public function start(int $interval);

	/**
	 * Stops automated tasks system
	 * @return bool returns true if was succesfull
	 */
	public function stop();

	/**
	 * checks if core is enabled
	 *
	 * @return bool
	 */
	public function isEnabled();

	/**
	 *  Checks if need set new timer when automated task object was executed
	 *
	 *  @return bool
	 */
	public function needStart();

	/**
	 * Gets current system name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Returns if handler needs to be executed
	 *
	 * @return bool
	 */
	public function needExecution();

	/**
	 * Returns if script must end when there is all tasks executed
	 *
	 * @return bool
	 */
	public function needExit();

}


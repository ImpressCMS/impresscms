<?php
/**
 * Autotask object handler
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	license.txt
 */

/**
 * Handler for the autotask objects
 *
 * @package ImpressCMS\Modules\System\Class\Autotasks
 */
class mod_system_AutotasksHandler extends icms_ipf_Handler
{

	private $_use_virtual_config = false;
	private $_virtual_config = array();

	/**
	 * Constructor
	 *
	 * @param object $db Database object
	 */
	public function __construct($db)
	{
		parent::__construct($db, 'autotasks', 'sat_id', 'sat_name', 'sat_code', 'system');
	}

	/**
	 * Enable virtual configuartion and set it
	 *
	 * @param array
	 */
	public function enableVirtualConfig(&$array)
	{
		$this->_virtual_config = $array;
		$this->_use_virtual_config = true;
	}

	/**
	 * Get virtual configuration status
	 *
	 * @return bool
	 */
	public function isVirtualConfigEnabled()
	{
		return $this->_use_virtual_config;
	}

	/**
	 * Disable virtual configuration
	 */
	public function disableVirtualConfig()
	{
		$this->_use_virtual_config = false;
	}

	/**
	 * Gets selected type current events for current user
	 *
	 * @param int $ type
	 * @return Object
	 */
	public function getTasks()
	{
		$criteria = new icms_db_criteria_Compo();
		$criteria->setSort('sat_lastruntime');
		$criteria->setOrder('ASC');
		$criteria->add(new icms_db_criteria_Item('(sat_lastruntime + sat_interval)', time(), '<=', null, "%s"));
		$criteria->add(new icms_db_criteria_Item('sat_repeat', 0, '>=', null, "'%s'"));
		$criteria->add(new icms_db_criteria_Item('sat_enabled', 1));
		$rez = $this->getObjects($criteria, false);
		return $rez;
	}

	/**
	 * Executes events
	 *
	 * @return array
	 */
	public function execTasks()
	{
		$rez = array('all' => 0, 'ok' => 0);
		if (!($tasks = $this->getTasks())) {
			return $rez;
		}
		foreach ($tasks as $task) {
			if ($task->exec()) {
				$rez['ok']++;
			}
			$rez['all']++;
		}
		return $rez;
	}

	/**
	 * Get if current autotask handler needs execution
	 *
	 * @return bool
	 */
	public function needExecution()
	{
		$handler = $this->getCurrentSystemHandler();
		if ($handler === null) {
			return false;
		}
		return $handler->needExecution();
	}

	/**
	 * Returns if all tasks was executed to do no more php lines processing
	 *
	 * @return  bool
	 */
	public function needExit() {
		$handler = $this->getCurrentSystemHandler();
		if ($handler === null) {
			return false;
		}
		return $handler->needExit();
	}

	/**
	 * Starts handler if needed
	 */
	public function startIfNeeded()
	{
		$system = $this->getCurrentSystemHandler();
		if ($system !== null && $system->needStart()) {
			if ($system->canRun()) {
				$system->start($this->getRealTasksRunningTime());
			} else {
				trigger_error("Can't start selected automated tasks handler.");
			}
		}
		unset($system);
	}

	/**
	 * Tasks are executed in some times periods but not always exatcly the same
	 * as in administration. This will get real tasks execution interval.
	 *
	 * @return int
	 */
	public function getRealTasksRunningTime()
	{
		$sql = 'SELECT MIN(sat_interval) INTV FROM ' . $this->db->prefix('system_autotasks') . ' WHERE sat_enabled = TRUE LIMIT 1';
		if (!$result = $this->db->query($sql)) {
			return 0;
		}
		$data = $this->db->fetchArray($result);
		$interval = (int)$data['INTV'];
		return ($interval == 0) ? strtotime('60 minutes') : $interval;
	}

	/**
	 * Get selected autotask system handler
	 *
	 * @param string system name
	 *
	 * @return AutomatedTasks
	 */
	public function getSelectedSystemHandler($name)
	{
		$name = trim($name);
		if (empty($name)) {
			$name = IcmsAutoTasksInternal::class;
		}
		return \icms::getInstance()->get('\\' . $name);;
	}

	/**
	 * Gets autotasks settings
	 *
	 * @return Array(ConfigObjectItems)
	 */
	public function getConfig()
	{
		if ($this->isVirtualConfigEnabled()) {
			return $this->_virtual_config;
		}
		$config_handler = icms::handler('icms_config');
		$config_atasks = $config_handler->getConfigsByCat(\icms_config_Handler::CATEGORY_AUTOTASKS);
		return $config_atasks;
	}

	/**
	 * Get AutoTasks System
	 *
	 * @param bool force update handler
	 *
	 * @return AutomatedTasks|null
	 */
	public function getCurrentSystemHandler($forceUpdate = false)
	{
		static $handler = false;
		if (defined('ICMS_MIGRATION_MODE') && ICMS_MIGRATION_MODE) {
			return null;
		}
		if ($forceUpdate || ($handler === false)) {
			$config_atasks = $this->getConfig();
			$handler = $this->getSelectedSystemHandler($config_atasks['autotasks_system']);
		}

		return $handler;
	}

	/**
	 * Gets all avaible system handlers
	 *
	 * @param bool    checkIfItIsAvaibleOnCurrentSystem
	 *
	 * @return    array
	 */
	public function getSystemHandlersList($checkIfItIsAvaibleOnCurrentSystem = true)
	{
		static $ret = null;
		if ($ret === null) {
			foreach (\icms::getInstance()->get('autotasks.system') as $handler) {
				if ($checkIfItIsAvaibleOnCurrentSystem && (!$handler->canRun())) {
					continue;
				}
				$ret[get_class($handler)] = $handler->getName();
			}
			asort($ret);
		}
		return $ret;
	}
}

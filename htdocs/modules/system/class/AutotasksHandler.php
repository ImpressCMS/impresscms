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
class mod_system_AutotasksHandler extends icms_ipf_Handler {

	private $_use_virtual_config = FALSE;
	private $_virtual_config = array();

	/**
	 * Constructor
	 * 
	 * @param object $db	Database object
	 */
	public function __construct($db) {
		parent::__construct($db, 'autotasks', 'sat_id', 'sat_name', 'sat_code', 'system');
	}

	/**
	 * Enable virtual configuartion and set it
	 *
	 * @param	array
	 */
	public function enableVirtualConfig(&$array) {
		$this->_virtual_config = $array;
		$this->_use_virtual_config = TRUE;
	}

	/**
	 * Get virtual configuration status
	 *
	 * @return bool
	 */
	public function isVirtualConfigEnabled() {
		return $this->_use_virtual_config;
	}

	/**
	 * Disable virtual configuration
	 */
	public function disableVirtualConfig() {
		$this->_use_virtual_config = FALSE;
	}

	/**
	 * Gets selected type current events for current user
	 *
	 * @param int $ type
	 * @return Object
	 */
	public function getTasks() {
		$criteria = new icms_db_criteria_Compo();
		$criteria->setSort('sat_lastruntime');
		$criteria->setOrder('ASC');
		$criteria->add( new icms_db_criteria_Item('(sat_lastruntime + sat_interval)', time(), '<=', NULL, "%s" ));
		$criteria->add( new icms_db_criteria_Item('sat_repeat', 0, '>=', NULL, "'%s'"));
		$criteria->add( new icms_db_criteria_Item('sat_enabled', 1));
		$rez = $this->getObjects($criteria, FALSE);
		return $rez;
	}

	/**
	 * Executes events
	 *
	 * @return array
	 */
	public function execTasks() {
		$rez = array('all' => 0, 'ok' => 0);
		if (!($tasks = $this->getTasks())) return $rez;
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
	 * @return TRUE
	 */
	public function needExecution() {
		return $this->getCurrentSystemHandler()->needExecution();
	}

	/**
	 * Returns if all tasks was executed to do no more php lines processing
	 *
	 * @param bool
	 */
	public function needExit() {
		return $this->getCurrentSystemHandler()->needExit();
	}

	/**
	 * Starts handler if needed
	 */
	public function startIfNeeded() {
		$system = $this->getCurrentSystemHandler();
		if ($system->needStart()) {
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
	public function getRealTasksRunningTime() {
		$sql = 'SELECT MIN(sat_interval) INTV FROM ' . $this->db->prefix('system_autotasks') . ' WHERE sat_enabled = TRUE LIMIT 1';
		if (!$result = $this->db->query($sql)) {
			return 0;
		}
		$data = $this->db->fetchArray($result);
		$interval = (int) $data['INTV'];
		return ($interval == 0) ? strtotime('60 minutes') : $interval;
	}

	/**
	 * Get selected autotask system handler
	 *
	 * @param string system name
	 *
	 * @return AutomatedTasks
	 */
	public function getSelectedSystemHandler($name) {
		if ("$name" == '') {
			$name = 'internal';
		}
		$name = trim(strtolower($name));
		require_once $this->getSystemHandlerFileName((string) $name);
		$handler_name = 'IcmsAutoTasks' . ucfirst($name);
		if (class_exists($handler_name)) {
			$handler = new $handler_name($this);
		} else {
			trigger_error('Needed autotask handler not found!');
		}
		return $handler;
	}

	/**
	 * Gets system handler filename
	 *
	 * @param	string	name
	 * @return	string
	 */
	private function getSystemHandlerFileName($name) {
		return ICMS_PLUGINS_PATH . '/autotasks/' . $name . '.php';
	}

	/**
	 * Get system handler name from filename
	 *
	 * @param string filename
	 * @return string
	 */
	private function getSystemHandlerNameFromFileName($filename) {
		return substr($filename, strlen(ICMS_PLUGINS_PATH . '/autotasks/'), -strlen('.php'));
	}

	/**
	 * Gets autotasks settings
	 *
	 * @return Array(ConfigObjectItems)
	 */
	public function getConfig() {
		if ($this->isVirtualConfigEnabled()) {
			return $this->_virtual_config;
		}
		//$old_handler_name = get_class($handler);
		$config_handler = icms::handler('icms_config');
		$config_atasks = $config_handler->getConfigsByCat(\icms_config_Handler::CATEGORY_AUTOTASKS);
		return $config_atasks;
	}

	/**
	 * Get AutoTasks System
	 *
	 * @param bool force update handler
	 *
	 * @return AutomatedTasks
	 */
	public function getCurrentSystemHandler($forceUpdate = FALSE) {
		static $handler = FALSE;
		if ($forceUpdate || ($handler === FALSE)) {
			$config_atasks = $this->getConfig();
			$handler = $this->getSelectedSystemHandler($config_atasks['autotasks_system']);
		}
		return $handler;
	}

	/**
	 * Gets all avaible system handlers
	 *
	 * @param	bool	checkIfItIsAvaibleOnCurrentSystem
	 *
	 * @return	array
	 */
	public function getSystemHandlersList($checkIfItIsAvaibleOnCurrentSystem = TRUE) {
		static $ret = NULL;
		if ($ret === NULL) {
			$files = glob($this->getSystemHandlerFileName('*'));
			$ret = FALSE;
			foreach ($files as $file) {
				$name = (string)$this->getSystemHandlerNameFromFileName((string) $file);
				$handler = $this->getSelectedSystemHandler($name);
				if (!$handler) continue;
				if ($checkIfItIsAvaibleOnCurrentSystem && (!$handler->canRun())) {
					continue;
				}
				$ret[] = $name;
			}
		}
		sort($ret);
		return $ret;
	}
}

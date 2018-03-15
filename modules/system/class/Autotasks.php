<?php
/**
 * ImpressCMS AUTOTASKS
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2 alpha 2
 * @author	MekDrop <mekdrop@gmail.com>
 */

/* this is needed because this class is loaded outside of the admin area, too */
icms_loadLanguageFile("system", "autotasks", TRUE);

/**
 * Task objects
 *
 * @package ImpressCMS\Modules\System\Class\Autotasks
 *
 * @property int    $sat_id             Task ID
 * @property int    $sat_lastruntime    Last run time
 * @property string $sat_name           Name dispalyed in admin
 * @property string $sat_code           Code to execute
 * @property int    $sat_repeat         How many times to repeat (0 - always)
 * @property int    $sat_interval       Interval in minutes
 * @property int    $sat_onfinish       Auto delete?
 * @property int    $sat_enabled        Is enabled?
 * @property string $sat_type           Type (custom or module)
 * @property int    $sat_addon_id       Module ID
 */
class mod_system_Autotasks extends icms_ipf_Object {

	public $content = FALSE;

	/**
	 * Constructor
	 *
	 * @param object $handler
	 */
	public function __construct(&$handler) {
                $this->initVar('sat_id', self::DTYPE_INTEGER, 0, false);
                $this->initVar('sat_lastruntime', self::DTYPE_INTEGER, 0, false, null, null, null, _CO_ICMS_AUTOTASKS_LASTRUNTIME);
                $this->initVar('sat_name', self::DTYPE_STRING, '', true, 255, null, null, _CO_ICMS_AUTOTASKS_NAME, _CO_ICMS_AUTOTASKS_NAME_DSC);
                $this->initVar('sat_code', self::DTYPE_STRING, '', true, null, array(self::VARCFG_SOURCE_FORMATING => 'php'), null, _CO_ICMS_AUTOTASKS_CODE, _CO_ICMS_AUTOTASKS_CODE_DSC);
                $this->initVar('sat_repeat', self::DTYPE_INTEGER, 0, true, null, null, null, _CO_ICMS_AUTOTASKS_REPEAT, _CO_ICMS_AUTOTASKS_REPEAT_DSC);
                $this->initVar('sat_interval', self::DTYPE_INTEGER, 1440, true, null, null, null, _CO_ICMS_AUTOTASKS_INTERVAL, _CO_ICMS_AUTOTASKS_INTERVAL_DSC);
                $this->initVar('sat_onfinish', self::DTYPE_INTEGER, 0, true, 2, null, null, _CO_ICMS_AUTOTASKS_ONFINISH, _CO_ICMS_AUTOTASKS_ONFINISH_DSC);
                $this->initVar('sat_enabled', self::DTYPE_INTEGER, 1, true, 1, null, null, _CO_ICMS_AUTOTASKS_ENABLED, _CO_ICMS_AUTOTASKS_ENABLED_DSC);
                $this->initVar('sat_type', self::DTYPE_STRING, ':custom', true, 100, null, null, _CO_ICMS_AUTOTASKS_TYPE);
                $this->initVar('sat_addon_id', self::DTYPE_INTEGER, 0, false);

		parent::__construct($handler);

		$this->setControl('sat_name', 'text');
		$this->setControl('sat_onfinish', 'yesno');
		$this->setControl('sat_enabled', 'yesno');

		$this->doHideFieldFromForm('sat_addon_id');
		$this->doHideFieldFromForm('sat_type');
		$this->doHideFieldFromForm('sat_lastruntime');
	}

	/**
	 * Get the last time a task was run and format it for display
	 * @return	string
	 */
	public function getLastRunTimeForDisplay() {
		if ($this->getVar('sat_lastruntime') < 1) {
			return _CO_ICMS_AUTOTASKS_NOTYETRUNNED;
		} else {
			return formatTimestamp($this->getVar('sat_lastruntime'));
		}
	}

	/**
	 * Get the recurrence for the task and format it for display
	 * @return	string
	 */
	public function getRepeatForDisplay() {
		if ($this->getVar('sat_repeat') < 1) {
			return _CO_ICMS_AUTOTASKS_FOREVER;
		} else {
			return $this->getVar('sat_repeat');
		}
	}

	/**
	 * Get the recur interval and format it for display
	 * @return	string
	 */
	public function getIntervalForDisplay() {

		$int = $this->getVar('sat_interval');
		$day = (int) ($int / 60 / 24);
		$hou = (int) (($int - $day * 24 * 60) / 60);
		$min = (int) (($int - $day * 24 * 60) - $hou * 60);

		$ret = '';
		if ($day == 1) {
			$ret .= _DAY . ' ';
		} elseif ($day > 1) {
			$ret .= sprintf(_DAYS, $day) . ' ';
		}

		if ($hou == 1) {
			$ret .= _HOUR . ' ';
		} elseif ($hou > 1) {
			$ret .= sprintf(_HOURS, $hou) . ' ';
		}

		if ($min == 1) {
			$ret .= _MINUTE;
		} elseif ($min > 1) {
			$ret .= sprintf(_MINUTES, $min);
		}

		return trim($ret);
	}

	/**
	 * Get the autotask type
	 *
	 * @param string $part
	 * @return	string
	 */
	public function getType($part = NULL) {
		$type = $this->getVar('sat_type');
		if ($type[0] == ':') {
			$type = substr($type, 1);
		}
		$type = explode('/', $type);
		if ($part === NULL) return $type;
		return $type[$part];
	}

	/**
	 * Format the type for display
	 * @return string
	 */
	public function getTypeForDisplay() {
		return constant('_CO_ICMS_AUTOTASKS_TYPE_' . strtoupper($this->getType(0)));
	}

	/**
	 * Retrieve and format the enabled status of the task
	 * @return	string
	 */
	public function getEnableForDisplay() {
		return ($this->getVar('sat_enabled')==1) ? _YES : _NO;
	}

	/**
	 * Retrieve and format for display if the task will be deleted on completion
	 * @return	string
	 */
	public function getOnFinishForDisplay() {
		return ($this->getVar('sat_onfinish')==1) ? _YES : _NO;
	}

	/**
	 * Executes code attached to event
	 *
	 * @return bool
	 */
	public function exec() {
		if (!$this->getVar('sat_enabled')) return FALSE;
		if (((int) $this->getVar('sat_lastruntime') + (int) $this->getVar('sat_interval') * 60) > time()) return FALSE;
		$code = $this->getVar('sat_code');
		ignore_user_abort(TRUE);
		if (substr($this->getVar('sat_type'), 0, 6) == 'addon/') {
			$dirname = substr($this->getVar('sat_type'), 6);
			if ($dirname == '') return FALSE;

			// only execute autotasks for active modules
			$module = icms::handler("icms_module")->getByDirname($dirname);
			if ($module->getVar("isactive") != 1) return FALSE;

			$dirname = ICMS_MODULES_PATH . '/' . $dirname;
			$dirname = $dirname . '/' . $code;
			$code = " require '" . $dirname . "';";
			$is_bug = !(@highlight_string(file_get_contents($dirname), TRUE));
		} else {
			$is_bug = !(@highlight_string('<?' . 'php '. $code . ' return TRUE; ?' . '>', TRUE));
		}
		if ($is_bug) {
			trigger_error(sprintf(_CO_ICMS_AUTOTASKS_SOURCECODE_ERROR, $code));
			return FALSE;
		}
		eval($code);
		$count = $this->getVar('sat_repeat');
		if ($count > 0) {
			if ($count == 1) {
				if ($this->getVar('sat_onfinish')) {
					// delete this task
					$this->handler->delete($this);
					return TRUE;
				} else {
					// disable this task
					$this->setVar('sat_enabled', 0);
				}
			}
			$count--;
			$this->setVar('sat_repeat', $count);
		}
		$this->setVar('sat_lastruntime', time());
		$this->handler->insert($this, TRUE);
		return TRUE;
	}

	/**
	 * Custom form generation for autotasks
	 * @see icms_ipf_Object::getForm()
	 */
	public function getForm($form_caption, $form_name, $form_action = FALSE, $submit_button_caption = _CO_ICMS_SUBMIT, $cancel_js_action = FALSE, $captcha = FALSE) {
		if ($this->getType(0)=='addon') {
			$this->doHideFieldFromForm('sat_code');
			$this->doHideFieldFromForm('sat_onfinish');
		} else {
			$this->doShowFieldOnForm('sat_code');
			$this->doShowFieldOnForm('sat_onfinish');
		}
		return parent::getForm($form_caption, $form_name, $form_action, $submit_button_caption, $cancel_js_action, $captcha);
	}

	/**
	 * Determine if the user can delete the task and display a button
	 * @return	mixed
	 */
	public function getDeleteButtonForDisplay() {
		static $controller = NULL;
		if ($this->getType(0) == 'addon') return;
		if ($controller === NULL) $controller = new icms_ipf_Controller($this->handler);
		return $controller->getDeleteItemLink($this, FALSE, TRUE, FALSE);
	}

	/**
	 * Retrieve name for display
	 * @return	string
	 */
	public function getNameForDisplay() {
		return $this->getVar('sat_name');
	}

}

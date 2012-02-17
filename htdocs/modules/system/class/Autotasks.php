<?php
/**
 * ImpressCMS AUTOTASKS
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Autotasks
 * @since		1.2 alpha 2
 * @author		MekDrop <mekdrop@gmail.com>
 * @version		SVN: $Id$
 */
defined('ICMS_ROOT_PATH') || die('ImpressCMS root path not defined');

/* this is needed because this class is loaded outside of the admin area, too */
icms_loadLanguageFile("system", "autotasks", TRUE);

/**
 * Task objects
 * 
 * @package		Administration
 * @subpackage	Autotasks
 */
class mod_system_Autotasks extends icms_ipf_Object {

	public $content = FALSE;

	/**
	 * Constructor
	 * 
	 * @param object $handler
	 */
	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('sat_id', XOBJ_DTYPE_INT, FALSE);
		$this->quickInitVar('sat_lastruntime', XOBJ_DTYPE_INT, FALSE, _CO_ICMS_AUTOTASKS_LASTRUNTIME, NULL, 0);
		$this->quickInitVar('sat_name', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_AUTOTASKS_NAME, _CO_ICMS_AUTOTASKS_NAME_DSC);
		$this->quickInitVar('sat_code', XOBJ_DTYPE_SOURCE, TRUE, _CO_ICMS_AUTOTASKS_CODE, _CO_ICMS_AUTOTASKS_CODE_DSC);
		$this->quickInitVar('sat_repeat', XOBJ_DTYPE_INT, TRUE, _CO_ICMS_AUTOTASKS_REPEAT, _CO_ICMS_AUTOTASKS_REPEAT_DSC, 0);
		$this->quickInitVar('sat_interval', XOBJ_DTYPE_INT, TRUE, _CO_ICMS_AUTOTASKS_INTERVAL, _CO_ICMS_AUTOTASKS_INTERVAL_DSC, 24 * 60);
		$this->quickInitVar('sat_onfinish', XOBJ_DTYPE_INT, TRUE, _CO_ICMS_AUTOTASKS_ONFINISH, _CO_ICMS_AUTOTASKS_ONFINISH_DSC, 0);
		$this->quickInitVar('sat_enabled', XOBJ_DTYPE_INT, TRUE, _CO_ICMS_AUTOTASKS_ENABLED, _CO_ICMS_AUTOTASKS_ENABLED_DSC, 1);
		$this->quickInitVar('sat_type', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_AUTOTASKS_TYPE, NULL, ':custom');
		$this->quickInitVar('sat_addon_id', XOBJ_DTYPE_INT, FALSE);

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
			$code = ' require "' . $dirname . '";';
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

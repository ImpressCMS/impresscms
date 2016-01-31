<?php
/**
 * Contains the basis classes for displaying a single icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * icms_ipf_view_Single base class
 *
 * Base class handling the display of a single object
 *
 * @package	ICMS\IPF\View
 * @author      marcan <marcan@smartfactory.ca>
 */
class icms_ipf_view_Single {

	protected $_object;
	protected $_userSide;
	protected $_tpl;
	protected $_rows;
	protected $_actions;
	protected $_headerAsRow = true;

	/**
	 * Constructor
	 */
	public function __construct(&$object, $userSide = false, $actions = array(), $headerAsRow = true) {
		$this->_object = $object;
		$this->_userSide = $userSide;
		$this->_actions = $actions;
		$this->_headerAsRow = $headerAsRow;
	}
        
        /**
         * Magic getter to make some variables for class read-only from outside
         * 
         * @param string $name  Variable name
         * 
         * @return mixed
         * 
         * @throws Exception
         */
        public function __get($name) {
            if (isset($this->$name)) {
                trigger_error(sprintf('Accessing variable %s from outside IPF View Single class was deprecated', $name), E_USER_DEPRECATED);
                return $this->$name;
            } else {
                throw new Exception(sprintf('%s variable for %s doesn\'t exists', $name, __CLASS__));
            }
        }        

	/**
	 *
	 * @param $rowObj
	 */
	public function addRow($rowObj) {
		$this->_rows[] = $rowObj;
	}

	/**
	 *
	 * @param $fetchOnly
	 * @param $debug
	 */
	public function render($fetchOnly = false, $debug = false) {

		$this->_tpl = new icms_view_Tpl();
		$vars = $this->_object->getVars();
		$icms_object_array = array();

		foreach ($this->_rows as $row) {
			$key = $row->getKeyName();
			if ($row->_customMethodForValue && method_exists($this->_object, $row->_customMethodForValue)) {
				$method = $row->_customMethodForValue;
				$value = $this->_object->$method();
			} else {
				$value = $this->_object->getVar($row->getKeyName());
			}
			if ($row->isHeader()) {
				$this->_tpl->assign('icms_single_view_header_caption', $this->_object->getVarInfo($key, 'form_caption'));
				$this->_tpl->assign('icms_single_view_header_value', $value);
			} else {
				$icms_object_array[$key]['value'] = $value;
				$icms_object_array[$key]['header'] = $row->isHeader();
				$icms_object_array[$key]['caption'] = $this->_object->getVarInfo($key, 'form_caption');
			}
		}
		$action_row = '';
		if (in_array('edit', $this->_actions)) {
			$action_row .= $this->_object->getEditItemLink(false, true, $this->_userSide);
		}
		if (in_array('delete', $this->_actions)) {
			$action_row .= $this->_object->getDeleteItemLink(false, true, $this->_userSide);
		}
		if ($action_row) {
			$icms_object_array['zaction']['value'] = $action_row;
			$icms_object_array['zaction']['caption'] = _CO_ICMS_ACTIONS;
		}

		$this->_tpl->assign('icms_header_as_row', $this->_headerAsRow);
		$this->_tpl->assign('icms_object_array', $icms_object_array);

		if ($fetchOnly) {
			return $this->_tpl->fetch('db:system_persistable_singleview.html');
		} else {
			$this->_tpl->display('db:system_persistable_singleview.html');
		}
	}

	/**
	 *
	 * @param unknown_type $debug
	 */
	public function fetch($debug = false) {
		return $this->render(true, $debug);
	}
}


<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use icms;
use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\IPF\AbstractDatabaseModel;
use ImpressCMS\Core\View\Form\Elements\LabelElement;
use ImpressCMS\Core\View\Form\Elements\TrayElement;

/**
 * Form control creating a page element for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class PageElement extends TrayElement {
	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		icms_loadLanguageFile('system', 'blocks', true);
		parent::__construct(_AM_VISIBLEIN, ' ', $key . '_visiblein_tray');
		$visible_label = new LabelElement('', '<select class="form-control" name="visiblein[]" id="visiblein[]" multiple="multiple" size="10">' . $this->getPageSelOptions($object->getVar('visiblein')) . '</select>');
		$this->addElement($visible_label);
	}

	/**
	 * build options string
	 *
	 * @param mixed $value module-page combination
	 * @return string html
	 */
	private function getPageSelOptions($value = null) {
		$icms_page_handler = icms::handler('icms_data_page');
		if (!is_array($value)) {
			$value = array($value);
		}
		$module_handler = icms::handler('icms_module');
		$criteria = new CriteriaCompo(new CriteriaItem('hasmain', 1));
		$criteria->add(new CriteriaItem('isactive', 1));
		$module_list = $module_handler->getObjects($criteria);
		$mods = '';
		foreach ($module_list as $module) {
			$mods .= '<optgroup label="' . $module->getVar('name') . '">';
			$criteria = new CriteriaCompo(new CriteriaItem('page_moduleid', $module->getVar('mid')));
			$criteria->add(new CriteriaItem('page_status', 1));
			$pages = $icms_page_handler->getObjects($criteria);
			$sel = '';
			if (in_array($module->getVar('mid') . '-0', $value, false)) {
				$sel = ' selected=selected';
			}
			$mods .= '<option value="' . $module->getVar('mid') . '-0"' . $sel . '>' . _AM_ALLPAGES . '</option>';
			foreach ($pages as $page) {
				$sel = '';
				if (in_array($module->getVar('mid') . '-' . $page->getVar('page_id'), $value, false)) {
					$sel = ' selected=selected';
				}
				$mods .= '<option value="' . $module->getVar('mid') . '-' . $page->getVar('page_id') . '"' . $sel . '>';
				$mods .= $page->getVar('page_title') . '</option>';
			}
			$mods .= '</optgroup>';
		}

		$module = $module_handler->get(1);
		$criteria = new CriteriaCompo(new CriteriaItem('page_moduleid', 1));
		$criteria->add(new CriteriaItem('page_status', 1));
		$pages = $icms_page_handler->getObjects($criteria);
		$cont = '';
		if (count($pages) > 0) {
			$cont = '<optgroup label="' . $module->getVar('name') . '">';
			$sel = '';
			if (in_array($module->getVar('mid') . '-0', $value, false)) {
				$sel = ' selected=selected';
			}
			$cont .= '<option value="' . $module->getVar('mid') . '-0"' . $sel . '>' . _AM_ALLPAGES . '</option>';
			foreach ($pages as $page) {
				$sel = '';
				if (in_array($module->getVar('mid') . '-' . $page->getVar('page_id'), $value, false)) {
					$sel = ' selected=selected';
				}
				$cont .= '<option value="' . $module->getVar('mid') . '-' . $page->getVar('page_id') . '"' . $sel . '>';
				$cont .= $page->getVar('page_title') . '</option>';
			}
			$cont .= '</optgroup>';
		}
		$sel = $sel1 = '';
		if (in_array('0-1', $value, false)) {
			$sel = ' selected=selected';
		}
		if (in_array('0-0', $value, false)) {
			$sel1 = ' selected=selected';
		}
		$ret = '<option value="0-1"' . $sel . '>' . _AM_TOPPAGE . '</option>';
		$ret .= '<option value="0-0"' . $sel1 . '>' . _AM_ALLPAGES . '</option>';
		$ret .= $cont . $mods;

		return $ret;
	}
}
<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

/**
 * Form control creating a page element for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class PageElement extends \ImpressCMS\Core\Form\Elements\TrayElement {
	/**
	 * Constructor
	 * @param	\ImpressCMS\Core\IPF\AbstractModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		icms_loadLanguageFile('system', 'blocks', true);
		parent::__construct(_AM_VISIBLEIN, ' ', $key . '_visiblein_tray');
		$visible_label = new \ImpressCMS\Core\Form\Elements\LabelElement('', '<select class="form-control" name="visiblein[]" id="visiblein[]" multiple="multiple" size="10">' . $this->getPageSelOptions($object->getVar('visiblein')) . '</select>');
		$this->addElement($visible_label);
	}

	/**
	 * build options string
	 *
	 * @param mixed $value module-page combination
	 * @return string html
	 */
	private function getPageSelOptions($value = null) {
		$icms_page_handler = \icms::handler('icms_data_page');
		if (!is_array($value)) {
			$value = array($value);
		}
		$module_handler = \icms::handler('icms_module');
		$criteria = new \ImpressCMS\Core\Database\Criteria\CriteriaCompo(new \ImpressCMS\Core\Database\Criteria\CriteriaItem('hasmain', 1));
		$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem('isactive', 1));
		$module_list = $module_handler->getObjects($criteria);
		$mods = '';
		foreach ($module_list as $module) {
			$mods .= '<optgroup label="' . $module->getVar('name') . '">';
			$criteria = new \ImpressCMS\Core\Database\Criteria\CriteriaCompo(new \ImpressCMS\Core\Database\Criteria\CriteriaItem('page_moduleid', $module->getVar('mid')));
			$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem('page_status', 1));
			$pages = $icms_page_handler->getObjects($criteria);
			$sel = '';
			if (in_array($module->getVar('mid') . '-0', $value)) {
				$sel = ' selected=selected';
			}
			$mods .= '<option value="' . $module->getVar('mid') . '-0"' . $sel . '>' . _AM_ALLPAGES . '</option>';
			foreach ($pages as $page) {
				$sel = '';
				if (in_array($module->getVar('mid') . '-' . $page->getVar('page_id'), $value)) {
					$sel = ' selected=selected';
				}
				$mods .= '<option value="' . $module->getVar('mid') . '-' . $page->getVar('page_id') . '"' . $sel . '>';
				$mods .= $page->getVar('page_title') . '</option>';
			}
			$mods .= '</optgroup>';
		}

		$module = $module_handler->get(1);
		$criteria = new \ImpressCMS\Core\Database\Criteria\CriteriaCompo(new \ImpressCMS\Core\Database\Criteria\CriteriaItem('page_moduleid', 1));
		$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem('page_status', 1));
		$pages = $icms_page_handler->getObjects($criteria);
		$cont = '';
		if (count($pages) > 0) {
			$cont = '<optgroup label="' . $module->getVar('name') . '">';
			$sel = '';
			if (in_array($module->getVar('mid') . '-0', $value)) {
				$sel = ' selected=selected';
			}
			$cont .= '<option value="' . $module->getVar('mid') . '-0"' . $sel . '>' . _AM_ALLPAGES . '</option>';
			foreach ($pages as $page) {
				$sel = '';
				if (in_array($module->getVar('mid') . '-' . $page->getVar('page_id'), $value)) {
					$sel = ' selected=selected';
				}
				$cont .= '<option value="' . $module->getVar('mid') . '-' . $page->getVar('page_id') . '"' . $sel . '>';
				$cont .= $page->getVar('page_title') . '</option>';
			}
			$cont .= '</optgroup>';
		}
		$sel = $sel1 = '';
		if (in_array('0-1', $value)) {
			$sel = ' selected=selected';
		}
		if (in_array('0-0', $value)) {
			$sel1 = ' selected=selected';
		}
		$ret = '<option value="0-1"' . $sel . '>' . _AM_TOPPAGE . '</option>';
		$ret .= '<option value="0-0"' . $sel1 . '>' . _AM_ALLPAGES . '</option>';
		$ret .= $cont . $mods;

		return $ret;
	}
}
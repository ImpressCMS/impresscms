<?php
/**
 * ImpressCMS Block Persistable Class for Configure
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @since 		ImpressCMS 1.2
 * @category	ICMS
 * @package 	Administration
 * @subpackage	Blocks
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @author		Rodrigo Pereira Lima (aka therplima) <therplima@impresscms.org>
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/* This may be loaded by other modules - and not just through the cpanel */
icms_loadLanguageFile('system', 'blocks', TRUE);

/**
 * System Block Configuration Object Handler Class
 *
 * @since ImpressCMS 1.2
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class mod_system_BlocksHandler extends icms_view_block_Handler {

	private $block_positions;
	private $modules_name;

	public function __construct(& $db) {
		icms_ipf_Handler::__construct($db, 'blocks', 'bid', 'title', 'content', 'system');
		$this->table = $this->db->prefix('newblocks');

		$this->addPermission('block_read', _CO_SYSTEM_BLOCKS_BLOCKRIGHTS, _CO_SYSTEM_BLOCKS_BLOCKRIGHTS_DSC);
	}

	public function getVisibleStatusArray() {
		$rtn = array();
		$rtn[1] = _VISIBLE;
		$rtn[0] = _INVISIBLE;
		return $rtn;
	}

	//	public function getVisibleInArray() {
	//		/* TODO: To be implemented... */
	//  }

	public function getBlockPositionArray() {
		$block_positions = $this->getBlockPositions(TRUE);
		$rtn = array();
		foreach ($block_positions as $k=>$v) {
			$rtn[$k] = (defined($block_positions[$k]['title']))
				? constant($block_positions[$k]['title'])
				: $block_positions[$k]['title'];
		}
		return $rtn;
	}

	public function getContentTypeArray() {
		return array('H' => _AM_HTML, 'P' => _AM_PHP, 'S' => _AM_AFWSMILE, 'T' => _AM_AFNOSMILE);
	}

	public function getBlockCacheTimeArray() {
		$rtn = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH);
		return $rtn;
	}

	public function getModulesArray($full = FALSE) {
		if (!count($this->modules_name)) {
			$icms_module_handler = icms::handler('icms_module');
			$installed_modules =& $icms_module_handler->getObjects();
			$this->modules_name[0]['name'] = _NONE;
			$this->modules_name[0]['dirname'] = '';
			foreach ($installed_modules as $module) {
				$this->modules_name[$module->getVar('mid')]['name'] = $module->getVar('name');
				$this->modules_name[$module->getVar('mid')]['dirname'] = $module->getVar('dirname');
			}
		}

		$rtn = $this->modules_name;
		if (!$full) {
			foreach ($this->modules_name as $key => $module) {
				$rtn[$key] = $module['name'];
			}
		}
		return $rtn;
	}

	public function getModuleName($mid) {
		if ($mid == 0) return '';
		$modules = $this->getModulesArray();
		$rtn = $modules[$mid];
		return $rtn;
	}

	public function getModuleDirname($mid) {
		$modules = $this->getModulesArray(TRUE);
		$rtn = $modules[$mid]['dirname'];
		return $rtn;
	}

	public function upWeight($bid) {
		$blockObj = $this->get($bid);
		$criteria = new icms_db_criteria_Compo();
		$criteria->setLimit(1);
		$criteria->setSort('weight');
		$criteria->setOrder('DESC');
		$criteria->add(new icms_db_criteria_Item('side', $blockObj->vars['side']['value']));
		$criteria->add(new icms_db_criteria_Item('weight', $blockObj->getVar('weight'), '<'));
		$sideBlocks = $this->getObjects($criteria);
		$weight = (is_array($sideBlocks) && count($sideBlocks) == 1)
			? $sideBlocks[0]->getVar('weight') - 1
			: $blockObj->getVar('weight') - 1;
		if ($weight < 0) $weight = 0;
		$blockObj->setVar('weight', $weight);
		$this->insert($blockObj, TRUE);
	}

	public function downWeight($bid) {
		$blockObj = $this->get($bid);
		$criteria = new icms_db_criteria_Compo();
		$criteria->setLimit(1);
		$criteria->setSort('weight');
		$criteria->setOrder('ASC');
		$criteria->add(new icms_db_criteria_Item('side', $blockObj->vars['side']['value']));
		$criteria->add(new icms_db_criteria_Item('weight', $blockObj->getVar('weight'), '>'));
		$sideBlocks = $this->getObjects($criteria);
		$weight = (is_array($sideBlocks) && count($sideBlocks) == 1)
			? $sideBlocks[0]->getVar('weight') + 1
			: $blockObj->getVar('weight') + 1;
		$blockObj->setVar('weight', $weight);
		$this->insert($blockObj, TRUE);
	}

	public function changeVisible($bid) {
		$blockObj = $this->get($bid);
		if ($blockObj->getVar('visible' , 'n')) {
			$blockObj->setVar('visible', 0);
		} else {
			$blockObj->setVar('visible', 1);
		}
		$this->insert($blockObj, TRUE);
	}

	/**
	 * BeforeSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework before the object is inserted or updated
	 * We also need to do the transformation in case of an insert to handle cloned blocks with options
	 *
	 * @param object $obj Systemblocks object
	 * @return TRUE
	 */
	public function beforeSave(&$obj) {
		if (empty($_POST['options'])) return TRUE;

		$options = "";
		ksort($_POST['options']);
		foreach ($_POST['options'] as $opt) {
			if ($options != "")	$options .= '|';
			$options .= $opt;
		}
		$obj->setVar('options', $options);
		return TRUE;
	}
}

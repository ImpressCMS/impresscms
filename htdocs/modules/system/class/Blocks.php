<?php
/**
 * ImpressCMS Block Persistable Class for Configure
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license	GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @since 	ImpressCMS 1.2
 * @author	Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @author	Rodrigo Pereira Lima (aka therplima) <therplima@impresscms.org>
 */

/**
 * System Block Configuration Object Class
 *
 * @package ImpressCMS\Modules\System\Class\Blocks
 * @since   ImpressCMS 1.2
 * @author  Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class mod_system_Blocks extends icms_view_block_Object {

	/**
	 * Constructor
	 *
	 * @param SystemblocksHandler $handler
	 */
	public function __construct(& $handler) {
		parent::__construct($handler);

		$this->initNonPersistableVar('visiblein', self::DTYPE_DEP_OTHER, 'visiblein', FALSE, FALSE, FALSE, TRUE);

		$this->hideFieldFromForm('last_modified');
		$this->hideFieldFromForm('func_file');
		$this->hideFieldFromForm('show_func');
		$this->hideFieldFromForm('edit_func');
		$this->hideFieldFromForm('template');
		$this->hideFieldFromForm('dirname');
		$this->hideFieldFromForm('options');
		$this->hideFieldFromForm('bid');
		$this->hideFieldFromForm('mid');
		$this->hideFieldFromForm('func_num');
		$this->hideFieldFromForm('block_type');
		$this->hideFieldFromForm('isactive');

		$this->setControl('name', 'label');
		$this->setControl('visible', 'yesno');
		$this->setControl('bcachetime', array(
			'itemHandler' => 'blocks',
			'method' => 'getBlockCacheTimeArray',
			'module' => 'system'
			));
		$this->setControl('side', array(
			'itemHandler' => 'blocks',
			'method' => 'getBlockPositionArray',
			'module' => 'system'
			));
		$this->setControl('c_type', array(
			'itemHandler' => 'blocks',
			'method' => 'getContentTypeArray',
			'module' => 'system',
			'onSelect' => 'submit'
			));

		$this->setControl('visiblein', 'page');
		$this->setControl('options', 'blockoptions');
	}

	/**
	 * Creates custom accessors for properties
	 * @see htdocs/libraries/icms/ipf/icms_ipf_Object::getVar()
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array('visible', 'mid', 'side'))) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * Custom accesser for weight property
	 */
	private function weight() {
		$rtn = $this->getVar('weight', 'n');
		return $rtn;
	}

	/**
	 * Custom accessor for visible property
	 */
	private function visible() {
		if ($this->getVar('visible', 'n') == 1) {
			$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=blocks&op=visible&bid='
				. $this->getVar('bid') . '" title="' . _VISIBLE . '" ><img src="' . ICMS_IMAGES_SET_URL
				. '/actions/button_ok.png" alt="' . _VISIBLE . '"/></a>';
		} else {
			$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=blocks&op=visible&bid='
				. $this->getVar('bid') . '" title="' . _VISIBLE . '" ><img src="' . ICMS_IMAGES_SET_URL
				. '/actions/button_cancel.png" alt="' . _VISIBLE . '"/></a>';
		}
		return $rtn;
	}
	/**
	 * Custom accessor for mid property
	 */
	private function mid() {
		$rtn = $this->handler->getModuleName($this->getVar('mid', 'n'));
		return $rtn;
	}

	/**
	 * Custom accessor for side property
	 */
	private function side() {
		$block_positions = $this->handler->getBlockPositions(TRUE);
		$rtn = (defined($block_positions[$this->getVar('side', 'n')]['title']))
			? constant($block_positions[$this->getVar('side', 'n')]['title'])
			: $block_positions[$this->getVar('side', 'n')]['title'];
		return $rtn;
	}

	// Render Methods for Action Buttons

	/**
	 * Renders a space in the actions column
	 */
	public function getBlankLink() {
		return "<img src='" . ICMS_IMAGES_URL . "/blank.gif' width='22' alt=''  title='' />";
	}

	/**
	 * Renders a graphic and link to move the block up (lower weight)
	 */
	public function getUpActionLink() {
		$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=blocks&op=up&bid='
			. $this->getVar('bid') . '" title="' . _UP . '" ><img src="' . ICMS_IMAGES_SET_URL
			. '/actions/up.png" alt="' . _UP . '"/></a>';
		return $rtn;
	}

	/**
	 * Renders a graphic and link to move the block down (increase weight)
	 */
	public function getDownActionLink() {
		$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=blocks&op=down&bid='
			. $this->getVar('bid') . '" title="' . _DOWN . '" ><img src="' . ICMS_IMAGES_SET_URL
			. '/actions/down.png" alt="' . _DOWN . '"/></a>';
		return $rtn;
	}

	/**
	 * Renders a graphic and link to clone the block
	 */
	public function getCloneActionLink() {
		$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=blocks&op=clone&bid='
			. $this->getVar('bid') . '" title="' . _CLONE . '" ><img src="' . ICMS_IMAGES_SET_URL
			. '/actions/editcopy.png" alt="' . _CLONE . '"/></a>';
		return $rtn;
	}

	/**
	 * Renders a graphic and link to edit a block
	 */
	public function getEditActionLink() {
		$rtn = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=blocks&op=mod&bid='
			. $this->getVar('bid') . '" title="' . _EDIT . '" ><img src="' . ICMS_IMAGES_SET_URL
			. '/actions/edit.png" alt="' . _EDIT . '"/></a>';
		return $rtn;
	}

	/**
	 * Overrides parent method
	 * @see htdocs/libraries/icms/ipf/icms_ipf_Object::getAdminViewItemLink()
	 */
	public function getAdminViewItemLink($onlyUrl = FALSE) {
		$rtn = $this->getVar('title');
		return $rtn;
	}

	/**
	 * getDeleteItemLink
	 *
	 * Overwrited Method
	 *
	 * @param string $onlyUrl
	 * @param boolean $withimage
	 * @param boolean $userSide
	 * @return string
	 */
	public function getDeleteItemLink($onlyUrl=FALSE, $withimage=TRUE, $userSide=FALSE) {
		$ret = ICMS_MODULES_URL . "/system/admin.php?fct=blocks&op=del&"
			. $this->handler->keyName . "=" . $this->getVar($this->handler->keyName);
		if ($onlyUrl) {
			if ($this->getVar('block_type') != 'C' && $this->getVar('block_type') != 'K') {
				return "";
			} else {
				return $ret;
			}
		} elseif ($withimage) {
			if ($this->getVar('block_type') != 'C' && $this->getVar('block_type') != 'K') {
				return "<img src='" . ICMS_IMAGES_URL . "/blank.gif' width='22' alt=''  title='' />";
			} else {
				return "<a href='" . $ret . "'><img src='" . ICMS_IMAGES_SET_URL
					. "/actions/editdelete.png' style='vertical-align: middle;' alt='"
					. _CO_ICMS_DELETE . "'  title='" . _CO_ICMS_DELETE . "' /></a>";
			}
		}

		return "<a href='" . $ret . "'>" . $this->getVar($this->handler->identifierName) . "</a>";
	}

	/**
	 * Create the form for this object
	 *
	 * @return a {@link SmartobjectForm} object for this object
	 *
	 * @see icms_ipf_ObjectForm::icms_ipf_ObjectForm()
	 */
	public function getForm($form_caption, $form_name, $form_action=FALSE, $submit_button_caption = _CO_ICMS_SUBMIT, $cancel_js_action=FALSE, $captcha=FALSE) {
		if (!$this->isNew() && $this->getVar('block_type') != 'C') {
			$this->hideFieldFromForm('content');
			$this->hideFieldFromForm('c_type');
		}

		$form = new icms_ipf_form_Base($this, $form_name, $form_caption, $form_action, NULL, $submit_button_caption, $cancel_js_action, $captcha);
		return $form;
	}

	/**
	 *
	 */
	public function getSideControl() {
		$control = new icms_form_elements_Select('', 'block_side[]', $this->getVar('side', 'e'));
		$positions = $this->handler->getBlockPositions(TRUE);
		$block_positions = array();
		foreach ($positions as $k=>$position) {
			$block_positions[$k] = defined($position['title'])
				? constant($position['title'])
				: $position['title'];
		}
		$control->addOptionArray($block_positions);

		return $control->render();
	}

	/**
	 *
	 */
	public function getWeightControl() {
		$control = new icms_form_elements_Text('', 'block_weight[]', 5, 10, $this->getVar('weight', 'e'));
		$control->setExtra('style="text-align:center;"');
		return $control->render();
	}
}

<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

/**
 * Form control creating a textbox for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.2
 * @author	MekDrop <mekdrop@gmail.com>
 */
class SourceElement extends \ImpressCMS\Core\Form\Elements\TextElementarea {
	/*
	 * Editor's class instance
	 */
	private $_editor = null;

	/**
	 * Constructor
	 * @param	\icms_ipf_Object    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		global $icmsConfig;

		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, $object->getVar($key, 'e'));

		$control = $object->getControl($key);

		$editor_handler = icms_plugins_EditorHandler::getInstance('source');
		$this->_editor = &$editor_handler->get($icmsConfig['sourceeditor_default'],
			array('name' => $key,
				'value' => $object->getVar($key, 'e'),
				'language' => isset($control['language'])?$control['language']:_LANGCODE,
				'width' => isset($control['width'])?$control['width']:'100%',
				'height' => isset($control['height'])?$control['height']:'400px',
				'syntax' => isset($control['syntax'])?$control['syntax']:'php'));
	}

	/**
	 * Renders the editor
	 * @return	string  the constructed html string for the editor
	 */
	public function render() {
		if ($this->_editor) {
			return $this->_editor->render();
		} else {
			return parent::render();
		}
	}
}
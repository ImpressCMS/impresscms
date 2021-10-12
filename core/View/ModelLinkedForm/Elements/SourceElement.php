<?php
namespace ImpressCMS\Core\View\ModelLinkedForm\Elements;

use icms;
use ImpressCMS\Core\Extensions\Editors\EditorsRegistry;
use ImpressCMS\Core\IPF\AbstractDatabaseModel;
use ImpressCMS\Core\View\Form\Elements\TextAreaElement;

/**
 * Form control creating a textbox for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.2
 * @author	MekDrop <mekdrop@gmail.com>
 */
class SourceElement extends TextAreaElement {
	/*
	 * Editor's class instance
	 */
	private $_editor = null;

	/**
	 * Constructor
	 * @param	AbstractDatabaseModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key)
	{
		global $icmsConfig;

		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, $object->getVar($key, 'e'));

		$control = $object->getControl($key);

		/**
		 * @var EditorsRegistry $editorsRegistry
		 */
		$editorsRegistry = icms::getInstance()->get('\\' . EditorsRegistry::class);

		$this->_editor = $editorsRegistry->get(
			'source',
			$icmsConfig['sourceeditor_default'],
			[
				'name' => $key,
				'value' => $object->getVar($key, 'e'),
				'language' => $control['language'] ?? _LANGCODE,
				'width' => $control['width'] ?? '100%',
				'height' => $control['height'] ?? '400px',
				'syntax' => $control['syntax'] ?? 'php'
			]
		);

		if ($this->_editor) {
			$extra = '';
			foreach ($this->_editor->getAttributes() as $attrName => $attrValue) {
				$extra .= $attrName . '="' . htmlentities($attrValue) . '"';
			}
			$this->setExtra($extra);
		}
	}

	/**
	 * Renders the editor
	 * @return	string  the constructed html string for the editor
	 */
	public function render()
	{
		$ret = parent::render();

		if ($this->_editor) {
			$ret .= $this->_editor;
		}

		return $ret;
	}
}
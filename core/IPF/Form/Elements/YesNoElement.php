<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

/**
 * Form control creating a yesno radio button for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form\Elements
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class YesNoElement extends \ImpressCMS\Core\Form\Elements\RadioynElement {
	/**
	 * Constructor
	 * @param	\ImpressCMS\Core\IPF\AbstractModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, $object->getVar($key, 'e'));
	}
}
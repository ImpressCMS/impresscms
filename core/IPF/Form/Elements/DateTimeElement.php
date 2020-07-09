<?php
namespace ImpressCMS\Core\IPF\Form\Elements;

use ImpressCMS\Core\IPF\AbstractModel;

/**
 * Form control creating a DateTime Picker element for an object derived from \ImpressCMS\Core\IPF\AbstractModel
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 * @package	ICMS\IPF\Form\Elements
 */
class DateTimeElement extends \ImpressCMS\Core\Form\Elements\DatetimeElement {
	/**
	 * Constructor
	 * @param	AbstractModel    $object   reference to targetobject
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		parent::__construct($object->getVarInfo($key, 'form_caption'), $key, 15, $object->getVar($key, 'e'));
	}
}
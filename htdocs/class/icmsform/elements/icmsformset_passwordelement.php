<?php
/**
* Form control creating 2 password textbox to allow the user to enter twice his password, for an object derived from IcmsPersistableObject
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		IcmsPersistableObject
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/

if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

class IcmsFormSet_passwordElement extends XoopsFormElementTray {

	/**
     * Size of the field.
	 * @var	int
	 * @access	private
	 */
	var $_size;

	/**
     * Maximum length of the text
	 * @var	int
	 * @access	private
	 */
	var $_maxlength;

	/**
     * Initial content of the field.
	 * @var	string
	 * @access	private
	 */
	var $_value;

	/**
	 * Constructor
	 *
	 * @param	string	$caption	Caption
	 * @param	string	$name		"name" attribute
	 * @param	int		$size		Size of the field
	 * @param	int		$maxlength	Maximum length of the text
	 * @param	int		$value		Initial value of the field.
	 * 								<b>Warning:</b> this is readable in cleartext in the page's source!
	 */
	function IcmsFormSet_passwordElement($object, $key){

	    $var = $object->vars[$key];
	    $control = $object->controls[$key];

     	$this->XoopsFormElementTray($var['form_caption'] . '<br />' . _US_TYPEPASSTWICE, ' ', $key . '_password_tray');

		$password_box1 = new XoopsFormPassword('', $key . '1', 10, 32);
		$this->addElement($password_box1);

		$password_box2 = new XoopsFormPassword('', $key . '2', 10, 32);
		$this->addElement($password_box2);
	}
}
?>
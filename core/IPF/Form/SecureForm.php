<?php
namespace ImpressCMS\Core\IPF\Form;

use ImpressCMS\Core\View\Form\Elements\HiddenTokenElement;

/**
 * Form control creating a secure form
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\Form
 * @author	marcan <marcan@impresscms.org>
 */
class SecureForm extends Form {
	/**
	 * Constructor
	 * Sets all the values / variables for the \ImpressCMS\Core\IPF\Form\Form (parent) class
	 * @param	string    &$target                  reference to targetobject (@todo, which object will be passed here?)
	 * @param	string    $form_name                the form name
	 * @param	string    $form_caption             the form caption
	 * @param	string    $form_action              the form action
	 * @param	string    $form_fields              the form fields
	 * @param	string|false    $submit_button_caption    whether to add a caption to the submit button
	 * @param	bool      $cancel_js_action         whether to invoke a javascript action when cancel button is clicked
	 * @param	bool      $captcha                  whether to add captcha
	 */
	public function __construct(&$target, $form_name, $form_caption, $form_action, $form_fields = null, $submit_button_caption = false, $cancel_js_action = false, $captcha = false) {
		parent::__construct($target, $form_name, $form_caption, $form_action, $form_fields, $submit_button_caption, $cancel_js_action, $captcha);
		$this->addElement(new HiddenTokenElement());
	}
}
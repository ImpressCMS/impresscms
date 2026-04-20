<?php
declare(strict_types=1);
/**
 * Form control creating a secure form
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		SVN: $Id: Secure.php 10851 2010-12-05 19:15:30Z phoenyx $
 */

namespace Icms\Ipf\Form;

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

class Secure extends Base {
	/**
	 * Constructor
	 * Sets all the values / variables for the Icms\Ipf\Form\Base (parent) class
	 * @param	string    &$target                  reference to targetobject (@todo, which object will be passed here?)
	 * @param	string    $form_name                the form name
	 * @param	string    $form_caption             the form caption
	 * @param	string    $form_action              the form action
	 * @param	string    $form_fields              the form fields
	 * @param	string    $submit_button_caption    whether to add a caption to the submit button
	 * @param	bool      $cancel_js_action         whether to invoke a javascript action when cancel button is clicked
	 * @param	bool      $captcha                  whether to add captcha
	 */
	public function __construct(&$target, $form_name, $form_caption, $form_action, $form_fields = null, $submit_button_caption = false, $cancel_js_action = false, $captcha = false) {
		parent::__construct($target, $form_name, $form_caption, $form_action, $form_fields, $submit_button_caption, $cancel_js_action, $captcha);
		$this->addElement(new \icms_form_elements_Hiddentoken());
	}
}

\class_alias(Secure::class, 'icms_ipf_form_Secure');
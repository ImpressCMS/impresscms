<?php
declare(strict_types=1);
/**
 * Form control creating an element to link and URL to an object derived from \icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		ipf
 * @subpackage	form
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: Urllink.php 10849 2010-12-05 18:46:02Z phoenyx $
 */

namespace Icms\Ipf\Form\Elements;

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

class Urllink extends \Icms\Form\Elements\Tray {
	/**
	 * Constructor
	 * @param	\icms_ipf_Object	$object	target object
	 * @param	string			$key	the key
	 */
	public function __construct($object, $key) {
		parent::__construct($object->vars[$key]['form_caption'], "&nbsp;");
		$urllinkObj = $object->getUrlLinkObj($key);
		$module_handler = \icms::handler("\icms_module");
		$module = $module_handler->getByDirname($object->handler->_moduleName);

		$this->addElement(new \Icms\Form\Elements\Label("", _CO_ICMS_URLLINK_URL));
		$this->addElement(new \Icms\Ipf\Form\Elements\Text($urllinkObj, "url_" . $key));
		$this->addElement(new \Icms\Form\Elements\Label("", "<br/>" . _CO_ICMS_CAPTION));
		$this->addElement(new \Icms\Ipf\Form\Elements\Text($urllinkObj, "caption_" . $key));
		$this->addElement(new \Icms\Form\Elements\Label("", "<br/>" . _CO_ICMS_DESC));
		$this->addElement(new \Icms\Ipf\Form\Elements\Text($urllinkObj, "desc_" . $key));
		$this->addElement(new \Icms\Form\Elements\Label("", "<br/>" . _CO_ICMS_URLLINK_TARGET));
		$this->addElement(new \Icms\Form\Elements\Hidden("mid_" . $key, $module->getVar("mid")));
		$targ_val = $urllinkObj->getVar("target");
		$targetRadio = new \Icms\Form\Elements\Radio("", "target_" . $key, $targ_val != "" ? $targ_val : "_blank");
		$control = $urllinkObj->getControl("target");
		$targetRadio->addOptionArray($control["options"]);
		$this->addElement($targetRadio);
	}
}


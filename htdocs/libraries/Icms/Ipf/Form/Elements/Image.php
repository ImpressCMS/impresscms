<?php
declare(strict_types=1);
/**
 * Form control creating an hidden field for an object derived from \icms_ipf_Object
 * @todo		Remove the hardcoded height attribute, line breaks, styles
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		ipf
 * @subpackage	form
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: Image.php 11573 2012-02-16 00:39:30Z skenow $
 */

namespace Icms\Ipf\Form\Elements;

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class Image extends \Icms\Form\Elements\Tray {
	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link \icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$var = $object->vars[$key];
		$control = $object->getControl($key);

		$object_imageurl = $object->getImageDir();
		parent::__construct($var['form_caption'], ' ');

		if (isset($objectArray['image'])){
			$objectArray['image'] = str_replace('{ICMS_URL}', ICMS_URL, $objectArray['image']);
		}

		if ($object->getVar($key, 'e') != '' && (substr($object->getVar($key, 'e'), 0, 4) == 'http' || substr($object->getVar($key, 'e'), 0, 10) == '{ICMS_URL}')) {
			$this->addElement(new \Icms\Form\Elements\Label('', "<img src='" . str_replace('{ICMS_URL}', ICMS_URL, $object->getVar($key, 'e')) . "' alt='' /><br/><br/>" ));
		} elseif ($object->getVar($key, 'e') != '') {
			$this->addElement(new \Icms\Form\Elements\Label('', "<a rel='lightbox' title='" . $object_imageurl . $object->getVar($key, 'e') 
				. "' href='" . $object_imageurl . $object->getVar($key, 'e') 
				. "' ><img class='acp_object_imageurl' src='" . $object_imageurl . $object->getVar($key, 'e') 
				. "' alt='" . $object_imageurl . $object->getVar($key, 'e') . "' height='150' /></a><br/><br/>" ));
		}

		$this->addElement(new \Icms\Ipf\Form\Elements\Fileupload($object, $key));

		if (!isset($control['nourl']) || !$control['nourl']) {
			$this->addElement(new \Icms\Form\Elements\Label('<div style="padding-top: 8px; font-size: 80%;">'._CO_ICMS_URL_FILE_DSC.'</div>', ''));
			$this->addElement(new \Icms\Form\Elements\Label('', '<br />' . _CO_ICMS_URL_FILE));
			$this->addElement(new \Icms\Form\Elements\Text('', 'url_'.$key, 50, 500));
		}
		if (!$object->isNew()) {
			$this->addElement(new \Icms\Form\Elements\Label('', '<br /><br />'));
			$delete_check = new \Icms\Form\Elements\Checkbox('', 'delete_'.$key);
			$delete_check->addOption(1, '<span style="color:red;">'._CO_ICMS_DELETE.'</span>');
			$this->addElement($delete_check);
		}
	}
}


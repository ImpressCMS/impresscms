<?php
/**
 * ImpressCMS Customtags
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Custom Tags
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		SVN: $Id$
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

defined('ICMS_CUSTOMTAG_TYPE_XCODES') || define('ICMS_CUSTOMTAG_TYPE_XCODES', 1);
defined('ICMS_CUSTOMTAG_TYPE_HTML') || define('ICMS_CUSTOMTAG_TYPE_HTML', 2);
defined('ICMS_CUSTOMTAG_TYPE_PHP') || define('ICMS_CUSTOMTAG_TYPE_PHP', 3);

/**
 * Custom tags
 * @package		Administration
 * @subpackage	Custom Tags
 */
class mod_system_Customtag extends icms_ipf_Object {
	public $content = FALSE;
	public $evaluated = FALSE;

	/**
	 * Constructor
	 * @param object $handler
	 */
	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('customtagid', self::DTYPE_INTEGER, TRUE);
		$this->quickInitVar('name', self::DTYPE_DEP_TXTBOX, TRUE, _CO_ICMS_CUSTOMTAG_NAME, _CO_ICMS_CUSTOMTAG_NAME_DSC);
		$this->quickInitVar('description', self::DTYPE_STRING, FALSE, _CO_ICMS_CUSTOMTAG_DESCRIPTION, _CO_ICMS_CUSTOMTAG_DESCRIPTION_DSC);
		$this->quickInitVar('customtag_content', self::DTYPE_STRING, TRUE, _CO_ICMS_CUSTOMTAG_CONTENT, _CO_ICMS_CUSTOMTAG_CONTENT_DSC);
		$this->quickInitVar('language', self::DTYPE_DEP_TXTBOX, TRUE, _CO_ICMS_CUSTOMTAG_LANGUAGE, _CO_ICMS_CUSTOMTAG_LANGUAGE_DSC);
		$this->quickInitVar('customtag_type', self::DTYPE_INTEGER, TRUE, _CO_ICMS_CUSTOMTAG_TYPE, _CO_ICMS_CUSTOMTAG_TYPE_DSC, ICMS_CUSTOMTAG_TYPE_XCODES);
		$this->initNonPersistableVar('dohtml', self::DTYPE_INTEGER, 'class', 'dohtml', '', TRUE);
		$this->initNonPersistableVar('doimage', self::DTYPE_INTEGER, 'class', 'doimage', '', TRUE);
		$this->initNonPersistableVar('doxcode', self::DTYPE_INTEGER, 'class', 'doxcode', '', TRUE);
		$this->initNonPersistableVar('dosmiley', self::DTYPE_INTEGER, 'class', 'dosmiley', '', TRUE);

		$this->setControl('customtag_content', array('name' => 'textarea', 'form_editor' => 'textarea', 'form_rows' => 25));
		$this->setControl('language', array('name' => 'language', 'all' => TRUE));
		$this->setControl('customtag_type', array('itemHandler' => 'customtag', 'method' => 'getCustomtag_types', 'module' => 'system', "onSelect" => "submit"));
	}

	/**
	 * Override accessors for properties
	 * @see htdocs/libraries/icms/ipf/icms_ipf_Object::getVar()
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array())) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * Render and output the custom tag
	 */
	public function render() {
		$myts = icms_core_Textsanitizer::getInstance();
		if (!$this->content) {
			switch ($this->getVar('customtag_type')) {
				case ICMS_CUSTOMTAG_TYPE_XCODES:
					$ret = $this->getVar('customtag_content', 'N');
					$ret = $myts->displayTarea($ret, 1, 1, 1, 1, 1);
					break;
					
				case ICMS_CUSTOMTAG_TYPE_HTML:
					$ret = $this->getVar('customtag_content', 'N');
					$ret = $myts->displayTarea($ret, 1, 1, 1, 1, 0);
					break;

				case ICMS_CUSTOMTAG_TYPE_PHP:
					$ret = $this->renderWithPhp();
					break;
					
				default:
					break;
			}
			$this->content = $ret;
		}
		return $this->content;
	}

	/**
	 * Rendering a custom tag that contains PHP
	 */
	public function renderWithPhp() {
		if (!$this->content && !$this->evaluated) {
			$ret = $this->getVar('customtag_content', 'N');

			// check for PHP if we are not on admin side
			if (!defined('XOOPS_CPFUNC_LOADED' ) && $this->getVar('customtag_type') == ICMS_CUSTOMTAG_TYPE_PHP) {
				// we have PHP code, let's evaluate
				ob_start();
				echo eval($ret);
				$ret = ob_get_contents();
				ob_end_clean();
				$this->evaluated = TRUE;
			}
			$this->content = $ret;
		}
		return $this->content;
	}


	/**
	 * Generate a bbcode for the custom tag
	 */
	public function getXoopsCode() {
		$ret = '[customtag]' . $this->getVar('name', 'n') . '[/customtag]';
		return $ret;
	}

	/**
	 * Generate link and graphic for cloning a custom tag
	 */
	public function getCloneLink() {
		$ret = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=customtag&amp;op=clone&amp;customtagid=' . $this->id() . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/editcopy.png" style="vertical-align: middle;" alt="' . _CO_ICMS_CUSTOMTAG_CLONE . '" title="' . _CO_ICMS_CUSTOMTAG_CLONE . '" /></a>';
		return $ret;
	}


	/**
	 * Determine if the string is empty
	 */
	public function emptyString($var) {
		return strlen($var) > 0;
	}

	/**
	 * Accessor for the name property
	 */
	public function getCustomtagName() {
		$ret = $this->getVar('name');
		return $ret;
	}
}

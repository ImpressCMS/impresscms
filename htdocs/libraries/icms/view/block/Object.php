<?php
/**
 * ImpressCMS Block Persistable Class
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @category	ICMS
 * @package		View
 * @subpackage	Block
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * ImpressCMS Core Block Object Class
 *
 * @category	ICMS
 * @package		View
 * @subpackage	Block
 * @since		ImpressCMS 1.2
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class icms_view_block_Object extends icms_ipf_Object {

	/**
	 * Constructor for the block object
	 * @param $handler
	 */
	public function __construct(& $handler) {

		parent::__construct($handler);

		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('mid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('func_num', XOBJ_DTYPE_INT);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('side', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('weight', XOBJ_DTYPE_INT, TRUE, FALSE, FALSE, 0);
		$this->quickInitVar('visible', XOBJ_DTYPE_INT, TRUE);
		/**
		 * @var string $block_type Holds the type of block
		 * 	S - System block
		 * 	M - block from a Module (other than system)
		 * 	C - Custom block (legacy type 'E')
		 * 	K - block cloned from another block (legacy type 'D')
		 */
		$this->quickInitVar('block_type', XOBJ_DTYPE_TXTBOX);
		/**
		 * @var	string	$c_type	The type of content in the block
		 * 	H - HTML
		 * 	P - PHP
		 * 	S - Auto Format (smilies and HTML enabled)
		 *  T - Auto Format (smilies and HTML disabled)
		 */
		$this->quickInitVar('c_type', XOBJ_DTYPE_TXTBOX, TRUE, FALSE, FALSE, "S");
		$this->quickInitVar('isactive', XOBJ_DTYPE_INT);
		$this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('func_file', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('show_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('edit_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('template', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bcachetime', XOBJ_DTYPE_INT);
		$this->quickInitVar('last_modified', XOBJ_DTYPE_INT);
		$this->quickInitVar('options', XOBJ_DTYPE_TXTBOX);
	}

	// The next Methods are for backward Compatibility

	public function getContent($format = 'S', $c_type = 'T') {
		switch ($format) {
			case 'S':
				if ($c_type == 'H') {
                    $content = $this->getVar('content', 'n');
                    $content = str_replace('{X_SITEURL}', ICMS_URL . '/', $content);
                    $content = str_replace(XOOPS_DB_SALT, '', $content);
                    $content = str_replace(ICMS_DB_SALT, '', $content);
					return $content;
				} elseif ($c_type == 'P') {
					ob_start();
					echo eval($this->getVar('content', 'n'));
					$content = ob_get_contents();
					ob_end_clean();
                    $content = str_replace('{X_SITEURL}', ICMS_URL . '/', $content);
                    $content = str_replace(XOOPS_DB_SALT, '', $content);
                    $content = str_replace(ICMS_DB_SALT, '', $content);
					return $content;
				} elseif ($c_type == 'S') {
					$myts =& icms_core_Textsanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->getVar('content', 'n'));
					return $myts->displayTarea($content, 1, 1);
				} else {
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->getVar('content', 'n'));
					return icms_core_DataFilter::checkVar($content, 'text', 'output');
				}
				break;

			case 'E':
				return $this->getVar('content', 'e');
				break;

			default:
				return $this->getVar('content', 'n');
				break;
		}
	}

	/**
	 * (HTML-) form for setting the options of the block
	 *
	 * @return string|FALSE $edit_form is HTML for the form, FALSE if no options defined for this block
	 */
	public function getOptions() {
		if ($this->getVar('block_type') != 'C') {
			$edit_func = $this->getVar('edit_func');
			if (!$edit_func) {
				return FALSE;
			}
			icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
			include_once ICMS_ROOT_PATH . '/modules/' . $this->getVar('dirname') . '/blocks/' . $this->getVar('func_file');
			$options = explode('|', $this->getVar('options'));
			$edit_form = $edit_func($options);
			if (!$edit_form) {
				return FALSE;
			}
			return $edit_form;
		} else {
			return FALSE;
		}
	}

	/**
	 * For backward compatibility
	 *
	 * @todo improve with IPF
	 * @return unknown
	 */
	public function isCustom() {
		if ($this->getVar("block_type") == "C" || $this->getVar("block_type") == "E") {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Builds the block
	 *
	 * @return array $block the block information array
	 *
	 * @todo improve with IPF
	 */
	public function buildBlock() {
		global $icmsConfig, $xoopsOption;
		$block = array();
		// M for module block, S for system block C for Custom
		if ($this->isCustom()) {
			// it is a custom block, so just return the contents
			$block['content'] = $this->getContent("S", $this->getVar("c_type"));
			if (empty($block['content'])) {
				return FALSE;
			}
		} else {
			// get block display function
			$show_func = $this->getVar('show_func');
			if (!$show_func) {
				return FALSE;
			}
			// Must get lang files before execution of the function.
			if (!file_exists(ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file'))) {
				return FALSE;
			} else {
				icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
				include_once ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file');
				$options = explode("|", $this->getVar("options"));
				if (!function_exists($show_func)) {
					return FALSE;
				} else {
					// execute the function
					$block = $show_func($options);
					if (!$block) {
						return FALSE;
					}
				}
			}
		}
		return $block;
	}

	/**
	 * Aligns the content of a block
	 * If position is 0, content in DB is positioned
	 * before the original content
	 * If position is 1, content in DB is positioned
	 * after the original content
	 *
	 * @todo remove this? It is not found anywhere else in the core
	 */
	public function buildContent($position, $content = "", $contentdb = "") {
		if ($position == 0) {
			$ret = $contentdb . $content;
		} elseif ($position == 1) {
			$ret = $content . $contentdb;
		}
		return $ret;
	}

	/**
	 * Build Block Title
	 *
	 * @param string $originaltitle
	 * @param string $newtitle
	 * @return string
	 *
	 * @todo remove this? it is not found anywhere else in the core
	 */
	public function buildTitle($originaltitle, $newtitle = "") {
		if ($newtitle != "") {
			$ret = $newtitle;
		} else {
			$ret = $originaltitle;
		}
		return $ret;
	}
}
<?php
/**
 * Class To make printer friendly texts.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	ImpressCMS
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

namespace ImpressCMS\Core\View\Renderer;

use ImpressCMS\Core\View\Template;

/**
 * Class to manage a printer friendly page
 *
 * @package	ICMS\View
 * @link       The IcmsFactory <www.smartfactory.ca>
 */
class PrinterFriendlyViewRenderer {

	public $_title;
	public $_dsc;
	public $_content;
	public $_tpl;
	public $_pageTitle = false;
	public $_width = 680;

	/**
	 * Constructor
	 *
	 * @param string $content
	 * @param false|string $title
	 * @param false|string $dsc
	 */
	public function __construct($content, $title = false, $dsc = false) {
		$this->_title = $title;
		$this->_dsc = $dsc;
		$this->_content = $content;
	}

	public function setPageTitle($text) {
		$this->_pageTitle = $text;
	}

	public function setWidth($width) {
		$this->_width = $width;
	}

	public function render() {
		/**
		 * @todo move the output to a template
		 * @todo make the output XHTML compliant
		 */

		$this->_tpl = new Template();

		$this->_tpl->assign('icms_print_pageTitle', $this->_pageTitle?:$this->_title);
		$this->_tpl->assign('icms_print_title', $this->_title);
		$this->_tpl->assign('icms_print_dsc', $this->_dsc);
		$this->_tpl->assign('icms_print_content', $this->_content);
		$this->_tpl->assign('icms_print_width', $this->_width);

		$current_url = \icms::$urls['full'];

		$this->_tpl->assign('icms_print_currenturl', $current_url);
		$this->_tpl->assign('icms_print_url', $this->url);

		$this->_tpl->display('db:system_print.html');
	}

	/**
	 * Generates a printer friendly version of a page
	 *
	 * @param	string	$content	The HTML content of the page
	 * @param	false|string	$title		The title of the page
	 * @param	false|string	$description	The description of the page
	 * @param	false|string	$pagetitle
	 * @param	int		$width		The width of the page, in pixels
	 */
	public static function generate($content, $title = false, $description = false, $pagetitle = false, $width = 680) {
		$PrintDataBuilder = new self($content, $title, $description);
		$PrintDataBuilder->setPageTitle($pagetitle);
		$PrintDataBuilder->setWidth($width);
		$PrintDataBuilder->render();
	}

}


<?php
declare(strict_types=1);

namespace Icms\Form\Elements\Select;

use Icms\Form\Elements\Select as SelectElement;

/**
 * Create a form element to select an image
 *
 * from Mastop Go2 module v1.0 for XOOPS
 *
 ### =============================================================
 ### Mastop InfoDigital - Paixão por Internet
 ### =============================================================
 ### Classe para Colocar as imagens da biblioteca em um Select
 ### =============================================================
 ### @author Developer: Fernando Santos (topet05), fernando@mastop.com.br
 ### @Copyright: Mastop InfoDigital � 2003-2007
 ### -------------------------------------------------------------
 ### www.mastop.com.br
 ### =============================================================
 *
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @author		modified by UnderDog <underdog@impresscms.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
defined('ICMS_ROOT_PATH') or die();

class Image extends SelectElement
{
	/**
	 * OptGroup
	 *
	 * @var array
	 */
	private array $_optgroups = [];

	/**
	 * OptGroup IDs
	 *
	 * @var array
	 */
	private array $_optgroupsID = [];

	/**
	 * Constructor
	 *
	 * @param string    $caption  Form field caption
	 * @param string    $name     Field name
	 * @param mixed     $value    Value for the Select element
	 * @param int|mixed $cat      Category number or array of categories
	 */
	public function __construct(string $caption, string $name, $value = null, $cat = null)
	{
		parent::__construct($caption, $name, $value);
		$this->addOptGroupArray($this->getImageList($cat));
	}

	/**
	 * Adds an optgroup
	 *
	 * @param array  $value  Options in the group
	 * @param string $name   Name of the option group
	 */
	public function addOptGroup(array $value = [], string $name = '&nbsp;'): void
	{
		$this->_optgroups[$name] = $value;
	}

	/**
	 * Adds multiple optgroups
	 *
	 * @param array $options Array with name->options
	 */
	public function addOptGroupArray(array $options): void
	{
		if (!empty($options)) {
			foreach ($options as $k => $v) {
				$this->addOptGroup($v, $k);
			}
		}
	}

	/**
	 * Gets the image list
	 *
	 * @param int|mixed $cat Category number or array of categories
	 * @return array         The imagelist array
	 */
	public function getImageList($cat = null): array
	{
		$ret = [];

		// Get current user
		if (!\xoops_user_isLoggedIn()) {
			$groups = [XOOPS_GROUP_ANONYMOUS];
		} else {
			/** @var \Icms\User\UserHandler $userHandler */
			$userHandler = \Icms::getHandler('user');
			$currentMember = $userHandler->get(\xoops_user_get_uid());
			$groups = $currentMember->getGroups();
		}

		/** @var \Icms\Image\CategoryHandler $imgcatHandler */
		$imgcatHandler = \Icms::getHandler('image_category');
		$catlist = $imgcatHandler->getList($groups, 'imgcat_read', 1);

		// Filter categories if array provided
		if (is_array($cat) && !empty($catlist)) {
			foreach ($catlist as $k => $v) {
				if (!in_array($k, $cat, true)) {
					unset($catlist[$k]);
				}
			}
		} elseif (is_int($cat)) {
			$catlist = array_key_exists($cat, $catlist) ? [$cat => $catlist[$cat]] : [];
		}

		/** @var \Icms\Image\ImageHandler $imageHandler */
		$imageHandler = \Icms::getHandler('image');

		foreach ($catlist as $cid => $imgcat) {
			$this->_optgroupsID[$imgcat] = $cid;
			$criteria = new \Icms\Db\Criteria_Compo(new \Icms\Db\Criteria_Item('imgcat_id', $cid));
			$criteria->add(new \Icms\Db\Criteria_Item('image_display', 1));
			$total = $imageHandler->getCount($criteria);

			if ($total > 0) {
				$storetype = $imgcat->getVar('imgcat_storetype');
				if ($storetype === 'db') {
					$images = $imageHandler->getObjects($criteria, false, true);
				} else {
					$images = $imageHandler->getObjects($criteria, false, false);
				}

				foreach ($images as $i) {
					if ($storetype === 'db') {
						$ret[$imgcat]["/image.php?id=" . $i->getVar('image_id')] = $i->getVar('image_nicename');
					} else {
						$categPath = $imgcatHandler->getCategFolder($imgcat);
						$categPath = str_replace(\Icms\Http\Uri::getBaseUrl(), '', $categPath);
						$path = (substr($categPath, -1) !== '/') ? $categPath . '/' : $categPath;
						$ret[$imgcat][$path . $i->getVar('image_name')] = $i->getVar('image_nicename');
					}
				}
			} else {
				$ret[$imgcat] = '';
			}
		}

		return $ret;
	}

	/**
	 * Get Optgroups
	 *
	 * @return array Array of optgroups
	 */
	public function getOptGroups(): array
	{
		return $this->_optgroups;
	}

	/**
	 * Get OptgroupIDs
	 *
	 * @return array Array of optgroup IDs
	 */
	public function getOptGroupsID(): array
	{
		return $this->_optgroupsID;
	}

	/**
	 * Renders the HTML for the select form element
	 *
	 * @return string The constructed select form element HTML
	 */
	public function render(): string
	{
		// Get current user
		if (!\xoops_user_isLoggedIn()) {
			$groups = [XOOPS_GROUP_ANONYMOUS];
		} else {
			/** @var \Icms\User\UserHandler $userHandler */
			$userHandler = \Icms::getHandler('user');
			$currentMember = $userHandler->get(\xoops_user_get_uid());
			$groups = $currentMember->getGroups();
		}

		/** @var \Icms\Image\CategoryHandler $imgcatHandler */
		$imgcatHandler = \Icms::getHandler('image_category');
		$catlist = $imgcatHandler->getList($groups, 'imgcat_write', 1);
		$catlistTotal = count($catlist);
		$optIds = $this->getOptGroupsID();

		$addImageInput = '';
		if ($catlistTotal > 0) {
			$browseUrl = \Icms\Http\Uri::getBaseUrl() . 'modules/system/admin/images/browser.php';
			$addImageInput = ' <input type="button" value="' . _ADDIMAGE
				. '" onclick="window.open(\''
				. htmlspecialchars($browseUrl, ENT_QUOTES) . '?target='
				. htmlspecialchars($this->getName(), ENT_QUOTES) . '\',\'formImage\',\'resizable=yes,scrollbars=yes,width=985,height=470,left='
				. '(screen.availWidth/2-492)+\',top='
				. '(screen.availHeight/2-235)+\'");return false;">';
		}

		$imagem = null;

		$selectHtml = '<select onchange=\'if(this.options[this.selectedIndex].value != "") {'
			. 'document.getElementById("'
			. $this->getName() . '_img").src="'
			. \Icms\Http\Uri::getBaseUrl()
			. 'this.options[this.selectedIndex].value;}else{'
			. 'document.getElementById("'
			. $this->getName() . '_img").src="'
			. \Icms\Http\Uri::getBaseUrl()
			. '/images/blank.gif";}\' size="'
			. $this->getSize()
			. '"'
			. $this->getExtra()
			. '>'
			. '<option value="">';
		$selectHtml .= _SELECT . '</option>';

		foreach ($this->getOptGroups() as $nome => $valores) {
			$selectHtml .= '<optgroup id="img_cat_'
				. $optIds[$nome] . '" label="' . htmlspecialchars($nome, ENT_QUOTES) . '">';
			if (is_array($valores)) {
				foreach ($valores as $value => $name) {
					$selectHtml .= '<option value="'
						. htmlspecialchars($value, ENT_QUOTES)
						. '"';
					if (!empty($this->getValue()) && in_array($value, $this->getValue(), true)) {
						$selectHtml .= ' selected="selected"';
						$imagem = $value;
					}
					$selectHtml .= '>' . htmlspecialchars($name, ENT_QUOTES) . '</option>';
				}
			}
			$selectHtml .= '</optgroup>';
		}

		$selectHtml .= '</select>';
		$selectHtml .= $addImageInput;
		$selectHtml .= '<br />';
		$selectHtml .= '<img id="' . htmlspecialchars($this->getName() . '_img', ENT_QUOTES)
			. '" src="'
			. (!empty($imagem) ? \Icms\Http\Uri::getBaseUrl() . $imagem : \Icms\Http\Uri::getBaseUrl() . '/images/blank.gif')
			. '">';

		return $selectHtml;
	}
}
class_alias(Image::class, 'icms_form_elements_select_Image');
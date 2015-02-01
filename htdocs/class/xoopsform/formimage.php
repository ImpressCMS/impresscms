<?PHP
/**
 * Creates a form attribute which is able to select an image
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
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	XoopsForms
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: formimage.php 12278 2013-08-31 22:12:36Z fiammy $
 */

defined('ICMS_ROOT_PATH') or die("Oooops!!");

/**
 * Image select control for a form
 * @deprecated	Use icms_form_elements_select_Image, instead
 * @todo		Remove in 1.4
 */
class MastopFormSelectImage extends icms_form_elements_select_Image {
	private $_deprecated;
	
	/**
	 * Construtor
	 *
	 * @param	string	$caption
	 * @param	string	$name
	 * @param	mixed	  $value	Value for the Select attribute
	 * @param	string	$cat    Name of the Category
	 */
	function __construct($caption, $name, $value = null, $cat = null) {
		parent::__construct($caption, $name, $value, $cat);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_form_elements_select_Image', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

<?php
/**
 * Code required for common var of dobr type
 *
 * @copyright           The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		2.0
 * @author		i.know@mekdrop.name
 * @package		ICMS\Properties\Common
 */

if (!defined('_CM_DOAUTOWRAP')) {
    icms_loadLanguageFile('core', 'comment');
}

$value = ($default === 'notdefined') ? true : $default;
$this->initVar($varname, icms_properties_Handler::DTYPE_INTEGER,$value, false, null, "", false, _CM_DOAUTOWRAP, '', false, true, $displayOnForm);
$this->setControl($varname, "yesno");
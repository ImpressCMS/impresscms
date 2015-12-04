<?php
/**
 * Code required for common var of hierarchy_path type
 *
 * @copyright           The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		2.0
 * @author		i.know@mekdrop.name
 * @package		ICMS\Properties\Common
 */

$value = $default != 'notdefined' ? $default : '';
$this->initVar($varname, icms_properties_Handler::DTYPE_ARRAY, $value, false, null, "", false, _CO_ICMS_HIERARCHY_PATH, _CO_ICMS_HIERARCHY_PATH_DSC, false, true, $displayOnForm);
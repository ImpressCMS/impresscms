<?php
/**
 * Function to use timepicker
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	1.1
 * @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version	$Id: calendarjalalijs.php 12495 2015-06-15 19:43:10Z fiammy $
 **/

defined('ICMS_ROOT_PATH') or exit();

global $icmsConfig, $icmsTheme;
icms_loadLanguageFile('core', 'calendar');
$icmsTheme->addScript(ICMS_URL . "/libraries/jalalijscalendar/jquery.ui.datepicker-cc-fa.js", array("type" => "text/javascript"));

function dateFormatTojQueryUIDatePickerFormat($dateFormat) {
    $chars = array(
        // Day
        'd' => 'dd', 'j' => 'd', 'l' => 'DD', 'D' => 'D',
        // Month
        'm' => 'mm', 'n' => 'm', 'F' => 'MM', 'M' => 'M',
        // Year
        'Y' => 'yy', 'y' => 'y',
    );
    return strtr((string)$dateFormat, $chars);
}
$dateFormatTojQueryUIDatePickerFormat = dateFormatTojQueryUIDatePickerFormat(_SHORTDATESTRING);
define('_DATEFORMATCHANGED', $dateFormatTojQueryUIDatePickerFormat);

$time = isset($jstime) ? $jstime : "null";
$src = ' $(function() {
$(".datepick").datepicker({
dateFormat: "' ._DATEFORMATCHANGED. '",
showOn: "button",
buttonImage: "' . ICMS_URL . '/images/calendar.png",
buttonImageOnly: true,
changeMonth: true,
changeYear: true,
showAnim: "slideDown"
});
$(".ui-datepicker-trigger").attr("alt", "' ._CALENDAR. '").attr("title", "' ._CALENDAR. '");
});';

$icmsTheme->addScript("", array("type" => "text/javascript"), $src);
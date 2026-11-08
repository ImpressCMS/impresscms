<?php
/**
 * jQueryUI dateppicker used for calendars
 *
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license    LICENSE.txt
 * @package    core
 * @since    ImpressCMS 1.3.8
 * @author    debianus
 * @version    $Id: calendarjs.php 12495 2015-06-15 19:43:10Z fiammy $
 */

defined('ICMS_ROOT_PATH') or exit();

global $icmsConfig;
icms_loadLanguageFile('core', 'calendar');
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

if (_LANGCODE  !== 'en' && file_exists (ICMS_ROOT_PATH.'/language/'.$icmsConfig['language']."/datepicker-" ._LANGCODE. ".js")) {
    icms_form_elements_Date::addCalendarScript(ICMS_URL . "/language/" .$icmsConfig['language']. "/datepicker-" ._LANGCODE. ".js");
}


$time = isset($jstime) ? $jstime : "null";
$src = ' $(function() {
$.datepicker.setDefaults($.datepicker.regional["' ._LANGCODE. '"]);
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

icms_form_elements_Date::addCalendarScript("", $src);
<?php
/**
 * jQueryUI dateppicker used for calendars
 *
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license    LICENSE.txt
 * @package    core
 * @since    ImpressCMS 1.3.8
 * @author    debianus
 */
global $icmsTheme;
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
	$icmsTheme->addScript(ICMS_URL . "/language/" .$icmsConfig['language']. "/datepicker-" ._LANGCODE. ".js",  array("type" => "text/javascript"));
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

$icmsTheme->addScript("", array("type" => "text/javascript"), $src);
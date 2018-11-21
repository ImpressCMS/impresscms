<?php
// $Id: calendar.php 9536 2009-11-13 18:59:32Z pesianstranger $
//%%%%%		Time Zone	%%%%
define("_CAL_SUNDAY", "Zondag");
define("_CAL_MONDAY", "Maandag");
define("_CAL_TUESDAY", "Dinsdag");
define("_CAL_WEDNESDAY", "Woensdag");
define("_CAL_THURSDAY", "Donderdag");
define("_CAL_FRIDAY", "Vrijdag");
define("_CAL_SATURDAY", "Zaterdag");
define("_CAL_JANUARY", "Januari");
define("_CAL_FEBRUARY", "Februari");
define("_CAL_MARCH", "Maart");
define("_CAL_APRIL", "April");
define("_CAL_MAY", "Mei");
define("_CAL_JUNE", "Juni");
define("_CAL_JULY", "Juli");
define("_CAL_AUGUST", "Augustus");
define("_CAL_SEPTEMBER", "September");
define("_CAL_OCTOBER", "Oktober");
define("_CAL_NOVEMBER", "November");
define("_CAL_DECEMBER", "December");
define("_CAL_TGL1STD", "Selecteer de eerste dag van de week");
define("_CAL_PREVYR", "Vorig jaar (hou voor menu)");
define("_CAL_PREVMNTH", "Vorige maand (hou voor menu)");
define("_CAL_GOTODAY", "Ga naar Vandaag");
define("_CAL_NXTMNTH", "Volgende maand (hou voor menu)");
define("_CAL_NEXTYR", "Volgend jaar (hou voor menu)");
define("_CAL_SELDATE", "Selecteer datum");
define("_CAL_DRAGMOVE", "Sleep om te bewegen");
define("_CAL_TODAY", "Vandaag");
define("_CAL_DISPM1ST", "Toon maandag eerst");
define("_CAL_DISPS1ST", "Toon zondag eerst");

############# added since 1.1.2 #############
define("_CAL_SUN", "Zon");
define("_CAL_MON", "Maa");
define("_CAL_TUE", "Din");
define("_CAL_WED", "Woe");
define("_CAL_THU", "Don");
define("_CAL_FRI", "Vrij");
define("_CAL_SAT", "Zat");
// First day of the week. "0" means display Sunday first, "1" means display Monday first, etc...
define("_CAL_FIRSTDAY", "1");
define("_CAL_JAN", "Jan");
define("_CAL_FEB", "Feb");
define("_CAL_MAR", "Maa");
define("_CAL_APR", "Apr");
define("_CAL_JUN", "Jun");
define("_CAL_JUL", "Jul");
define("_CAL_AUG", "Aug");
define("_CAL_SEP", "Sept");
define("_CAL_OCT", "Okt");
define("_CAL_NOV", "Nov");
define("_CAL_DEC", "Dec");
// Direction of the calendar, ltr for left to right and rtl for roght to left (for Persian, Arabic, etc.)
define("_CAL_DIRECTION", "lnr");
define("_CAL_AM", "am");
define("_CAL_AM_CAPS", "AM");
define("_CAL_PM", "pm");
define("_CAL_PM_CAPS", "PM");
define("_CAL_TIME", "Tijd");
define("_CAL_WK", "wk"); // shorten of week-end
// This may be local-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
define("_CAL_WEEKEND", "0,6");
define("_CAL_DSPFIRST", "Toon %s eerst");
//This constants are for the jalali calendar included in the library
define('_CAL_FARVARDIN','Farvardin');
define('_CAL_ORDIBEHESHT','Ordibehest');
define('_CAL_KHORDAD','Khordad');
define('_CAL_TIR','Tir');
define('_CAL_MORDAD','Mordad');
define('_CAL_SHAHRIVAR','Shahrivar');
define('_CAL_MEHR','Mehr');
define('_CAL_ABAN','Aban');
define('_CAL_AZAR','Azar');
define('_CAL_DEY','Day');
define('_CAL_BAHMAN','Bahman');
define('_CAL_ESFAND','Esfand');
define("_CAL_NUMS_ARRAY", "'0', '1', '2', '3', '4', '5', '6', '7', '8', '9'"); // numeric values can differ in different languages
define("_CAL_TT_DATE_FORMAT", "%a, %b %e");
############# added since 1.2 #############
define("_CAL_SUFFIX", "th");
define('_CAL_AM_LONG','voor de middag');
define('_CAL_PM_LONG','na de middag');
?>
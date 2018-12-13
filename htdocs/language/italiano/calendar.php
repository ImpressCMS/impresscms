<?php
// 08/2008 Updated and adapted for ImpressCMS by evoc - webmaster of www.impresscms.it
// Published by ImpressCMS Italian Official Support Site - www.impresscms.it
// Updated by Ianez - Xoops Italia Staff
// Original translation by Marco Ragogna (blueangel)
// Published by Xoops Italian Official Support Site - www.xoopsitalia.org
// $Id: calendar.php 2 2005-11-02 18:23:29Z skalpa $
//%%%%%		Time Zone	%%%%
define("_CAL_SUNDAY", "Domenica");
define("_CAL_MONDAY", "Luned&igrave;");
define("_CAL_TUESDAY", "Marted&igrave;");
define("_CAL_WEDNESDAY", "Mercoled&igrave;");
define("_CAL_THURSDAY", "Gioved&igrave;");
define("_CAL_FRIDAY", "Venerd&igrave;");
define("_CAL_SATURDAY", "Sabato");
define("_CAL_JANUARY", "Gennaio");
define("_CAL_FEBRUARY", "Febbraio");
define("_CAL_MARCH", "Marzo");
define("_CAL_APRIL", "Aprile");
define("_CAL_MAY", "Maggio");
define("_CAL_JUNE", "Giugno");
define("_CAL_JULY", "Luglio");
define("_CAL_AUGUST", "Agosto");
define("_CAL_SEPTEMBER", "Settembre");
define("_CAL_OCTOBER", "Ottobre");
define("_CAL_NOVEMBER", "Novembre");
define("_CAL_DECEMBER", "Dicembre");
define("_CAL_TGL1STD", "Scambia il primo giorno della settimana");
define("_CAL_PREVYR", "Anno precedente (tieni premuto per il menu)");
define("_CAL_PREVMNTH", "Mese precedente (tieni premuto per il menu)");
define("_CAL_GOTODAY", "Vai ad oggi");
define("_CAL_NXTMNTH", "Mese successivo (tieni premuto per il menu)");
define("_CAL_NEXTYR", "Anno successivo (tieni premuto per il menu)");
define("_CAL_SELDATE", "Seleziona data");
define("_CAL_DRAGMOVE", "Trascina per spostare");
define("_CAL_TODAY", "Oggi");
define("_CAL_DISPM1ST", "Mostra il Luned&igrave; prima");
define("_CAL_DISPS1ST", "Mostra la Domenica prima");

############# added since 1.1.2 #############
define("_CAL_SUN", "Dom");
define("_CAL_MON", "Lun");
define("_CAL_TUE", "Mar");
define("_CAL_WED", "Mer");
define("_CAL_THU", "Gio");
define("_CAL_FRI", "Ven");
define("_CAL_SAT", "Sab");
// First day of the week. "0" means display Sunday first, "1" means display Monday first, etc...
define("_CAL_FIRSTDAY", "1°");
define("_CAL_JAN", "Gen");
define("_CAL_FEB", "Feb");
define("_CAL_MAR", "Mar");
define("_CAL_APR", "Apr");
define("_CAL_JUN", "Giu");
define("_CAL_JUL", "Lug");
define("_CAL_AUG", "Ago");
define("_CAL_SEP", "Sett");
define("_CAL_OCT", "Ott");
define("_CAL_NOV", "Nov");
define("_CAL_DEC", "Dic");
// Direction of the calendar, ltr for left to right and rtl for roght to left (for Persian, Arabic, etc.)
define("_CAL_DIRECTION", "ltr");
define("_CAL_AM", "am");
define("_CAL_AM_CAPS", "AM");
define("_CAL_PM", "pm");
define("_CAL_PM_CAPS", "PM");
define("_CAL_TIME", "Tempo");
define("_CAL_WK", "wk"); // shorten of week-end
// This may be local-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
define("_CAL_WEEKEND", "0,6");
define("_CAL_DSPFIRST", "Mostra %s primo");
//This constants are for the jalali calendar included in the library
define('_CAL_Far','Farvardin');
define('_CAL_Ord','Ordibehest');
define('_CAL_Kho','Khordad');
define('_CAL_Tir','Tir');
define('_CAL_Mor','Mordad');
define('_CAL_Sha','Shahrivar');
define('_CAL_Meh','Mehr');
define('_CAL_Aba','Aban');
define('_CAL_Aza','Azar');
define('_CAL_Dey','Day');
define('_CAL_Bah','Bahman');
define('_CAL_Esf','Esfand');
define("_CAL_NUMS_ARRAY", "'0', '1', '2', '3', '4', '5', '6', '7', '8', '9'"); // numeric values can differ in different languages
define("_CAL_TT_DATE_FORMAT", "%a, %b %e");
############# added since 1.2 #############
define("_CAL_SUFFIX", "°");
define('_CAL_AM_LONG','ante meridiane');
define('_CAL_PM_LONG','post meridiane');

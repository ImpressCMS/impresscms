// ** I18N

// Calendar FR language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("dimanche",
 "lundi",
 "mardi",
 "mercredi",
 "jeudi",
 "vendredi",
 "samedi",
 "dimanche");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("dim",
 "lun",
 "mar",
 "mer",
 "jeu",
 "ven",
 "sam",
 "dim");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("janvier",
 "f&eacute;vrier",
 "mars",
 "avril",
 "mai",
 "juis",
 "juillet",
 "ao&ucirc;t",
 "septembre",
 "octobre",
 "novembre",
 "d&eacute;cembre");

// short month names
Calendar._SMN = new Array
("jan",
 "f&eacute;v",
 "mar",
 "avr",
 "mai",
 "jui",
 "jul",
 "ao&ucirc;t",
 "sep",
 "oct",
 "nov",
 "d&eacute;c");

 // full month names
Calendar._JMN = new Array
("janvier",
 "f&egrave;vrier",
 "mars",
 "avril",
 "mai",
 "juis",
 "juillet",
 "ao&ucirc;t",
 "septembre",
 "octobre",
 "novembre",
 "d&eacute;cembre");

// short month names
Calendar._JSMN = new Array
("jan",
 "f&eacute;v",
 "mar",
 "avr",
 "mai",
 "jui",
 "jul",
 "ao&ucirc;t",
 "sep",
 "oct",
 "nov",
 "d&eacute;c");


 
// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "À propos du calendrier";

Calendar._TT["ABOUT"] =
"S&eacute;lecteur DHTML de Date/Temps\n" +
"(c) dynarch.com 2002-2005 / Auteur: Mihai Bazon\n" + // don't translate this this ;-)
"Pour la version la plus r&eacute;cente visitez: http://www.dynarch.com/projects/calendar/\n" +
"Distribu&eacute; under GNU LGPL.  Voir http://gnu.org/licenses/lgpl.html pour plus de d&eacute;tails." +
"\n\n" +
"S&eacute;lection de date:\n" +
"- utilizes les boutons \xab, \xbb pour s&eacute;lectionner une ann&eacute;e\n" +
"- Utilisez les boutons  " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " pour s&eacute;lectionner un mois\n" +
"- Tenez votre bouton souris sur chacun de ces boutons pour des s&eacute;lections plus rapides.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Selection du temps:\n" +
"- Cliquez sur une des parties du temps pour augmenter\n" +
"- ou shift-click pour diminuer\n" +
"- ou cliquez et traînez our une s&eacute;lection plus rapide.";

Calendar._TT["PREV_YEAR"] = "Ann&eacute;e pr&eacute;c. (tenez pour le menu)";
Calendar._TT["PREV_MONTH"] = "Mois pr&eacute;c. (tenez pour le menu)";
Calendar._TT["GO_TODAY"] = "Aujourd'hui";
Calendar._TT["NEXT_MONTH"] = "Mois proch. (tenez pour le menu)";
Calendar._TT["NEXT_YEAR"] = "Ann&eacute;e proch. (tenez pour le menu)";
Calendar._TT["SEL_DATE"] = "Selection de date";
Calendar._TT["DRAG_TO_MOVE"] = "Traînez pour bouger";
Calendar._TT["PART_TODAY"] = " (aujourd'hui)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Affichez %s d\'abord";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Fermer";
Calendar._TT["TODAY"] = "Aujourd'hui";
Calendar._TT["TIME_PART"] = "(Shift-)cliquez ou traîner pour changer la valuer";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %b %e";

Calendar._TT["WK"] = "sem";
Calendar._TT["TIME"] = "Time:";

Calendar._TT["LAM"] = "am";
Calendar._TT["AM"] = "AM";
Calendar._TT["LPM"] = "pm";
Calendar._TT["PM"] = "PM";

Calendar._NUMBERS = null;

Calendar._DIR = 'ltr';

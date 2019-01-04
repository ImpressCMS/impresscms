<?php
define('_CO_ICMS_AUTOTASKS_NAME', 'Taaknaam');
define('_CO_ICMS_AUTOTASKS_NAME_DSC', 'Voeg de naam van de taak in.');
define('_CO_ICMS_AUTOTASKS_CODE', 'Bron code');
define('_CO_ICMS_AUTOTASKS_CODE_DSC', 'Hier kunt u PHP code schrijven om te worden uitgevoerd als een taak.<br /><br />mainfile.php zal al worden ingevoegd.<br />Gebruik <i>global $xoopsDB</i> om gebruik te maken het database object.');
define('_CO_ICMS_AUTOTASKS_REPEAT', 'Herhalen');
define('_CO_ICMS_AUTOTASKS_REPEAT_DSC', 'Hoevaak wilt u deze taak herhalen? Voeg \'0\' in om het een altijddurende taak te laten zijn.');
define('_CO_ICMS_AUTOTASKS_INTERVAL', 'Interval');
define('_CO_ICMS_AUTOTASKS_INTERVAL_DSC', 'Taak uitvoering interval (in minuten).<br /><br />60: eenmaal per uur<br />1440: eenmaal per dag');
define('_CO_ICMS_AUTOTASKS_ONFINISH', 'Automatisch verwijderen');
define('_CO_ICMS_AUTOTASKS_ONFINISH_DSC', 'Wilt u dat deze taak automatisch wordt verwijderd na het aantal opgegeven herhalingen? Selecteer \'Ja\' wanneer u deze taak autonatisch van de lijst wilt verwijderen of \'Nee\' om deze taak in de pauze stand te zetten.<br />Dit geldt alleen bij herhalingen groter \'0\'.');
define('_CO_ICMS_AUTOTASKS_ENABLED', 'Ingeschakeld');
define('_CO_ICMS_AUTOTASKS_ENABLED_DSC', 'Selecteer \'JA\' om deze taak in te schakelen.');
define('_CO_ICMS_AUTOTASKS_TYPE', 'Type');
define('_CO_ICMS_AUTOTASKS_LASTRUNTIME', 'Laatste uitvoeringstijd');
define('_CO_ICMS_AUTOTASKS_CREATE', 'Maak een nieuwe taak aan');
define('_CO_ICMS_AUTOTASKS_EDIT', 'Taak aanpassen');
define('_CO_ICMS_AUTOTASKS_CREATED', 'Taak toegevoegd');
define('_CO_ICMS_AUTOTASKS_MODIFIED', 'Taak aangepast');
define('_CO_ICMS_AUTOTASKS_NOTYETRUNNED', 'Nog niet uitgevoerd');
define('_CO_ICMS_AUTOTASKS_TYPE_CUSTOM', 'Gebruiker');
define('_CO_ICMS_AUTOTASKS_TYPE_ADDON', 'Systeem');
define('_CO_ICMS_AUTOTASKS_FOREVER', 'altijd');
define('_CO_ICMS_AUTOTASKS_INIT_ERROR', 'FOUT: Kan het geselecteerde auto taak sub-systeem niet initialiseren.');
define('_CO_ICMS_AUTOTASKS_SOURCECODE_ERROR', 'FOUT in Autotaak Bron Code: Kan automatische taak niet uitvoeren');
?>
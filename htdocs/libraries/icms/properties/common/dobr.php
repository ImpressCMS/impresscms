<?php

if (!defined('_CM_DOAUTOWRAP')) {
    icms_loadLanguageFile('core', 'comment');
}

$value = ($default === 'notdefined') ? true : $default;
$this->initVar($varname, icms_properties_Handler::DTYPE_INTEGER,$value, false, null, "", false, _CM_DOAUTOWRAP, '', false, true, $displayOnForm);
$this->setControl($varname, "yesno");
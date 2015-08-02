<?php

if (!defined('_CM_DOSMILEY')) {
    icms_loadLanguageFile('core', 'comment');
}

$value = $default != 'notdefined' ? $default : true;
$this->initVar($varname, icms_properties_Handler::DTYPE_INTEGER,$value, false, null, "", false, _CM_DOSMILEY, '', false, true, $displayOnForm);
$this->setControl($varname, "yesno");
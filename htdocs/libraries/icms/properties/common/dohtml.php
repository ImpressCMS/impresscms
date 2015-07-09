<?php

$value = $default != 'notdefined' ? $default : true;
$this->initVar($varname, icms_properties_Handler::DTYPE_INTEGER, $value, false, null, "", false, _CM_DOHTML, '', false, true, $displayOnForm);
$this->setControl($varname, "yesno");
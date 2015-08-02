<?php

$value = $default != 'notdefined' ? $default : 0;
$this->initVar($varname, icms_properties_Handler::DTYPE_INTEGER,$value, false, null, '', false, _CO_ICMS_WEIGHT_FORM_CAPTION, '', true, true, $displayOnForm);
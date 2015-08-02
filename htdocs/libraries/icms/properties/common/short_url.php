<?php

$value = $default != 'notdefined' ? $default : '';
$this->initVar($varname, icms_properties_Handler::DTYPE_STRING,$value, false, 255, "", false, _CO_ICMS_SHORT_URL, _CO_ICMS_SHORT_URL_DSC, false, true, $displayOnForm);
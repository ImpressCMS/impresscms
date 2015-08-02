<?php

$value = $default != 'notdefined' ? $default : '';
$this->initVar($varname, icms_properties_Handler::DTYPE_ARRAY, $value, false, null, "", false, _CO_ICMS_HIERARCHY_PATH, _CO_ICMS_HIERARCHY_PATH_DSC, false, true, $displayOnForm);
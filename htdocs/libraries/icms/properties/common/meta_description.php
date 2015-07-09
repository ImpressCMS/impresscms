<?php

$value = $default != 'notdefined' ? $default : '';
$this->initVar($varname, icms_properties_Handler::DTYPE_STRING, $value, false, null, '', false, _CO_ICMS_META_DESCRIPTION, _CO_ICMS_META_DESCRIPTION_DSC, false, true, $displayOnForm);
$this->setControl('meta_description', array(
        'name' => 'textarea',
        'form_editor'=>'textarea'
));
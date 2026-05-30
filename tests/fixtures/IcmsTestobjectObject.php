<?php

declare(strict_types=1);

require_once __DIR__ . '/IcmsObjectEnvironmentStubs.php';

if (!defined('XOBJ_DTYPE_TXTBOX')) {
	require_once ICMS_LIBRARIES_PATH . '/icms/core/Object.php';
}

if (!class_exists('icms_ipf_Object')) {
	require_once ICMS_LIBRARIES_PATH . '/icms/ipf/Object.php';
}

if (!class_exists('icms_testobject_Object', false)) {
	class icms_testobject_Object extends icms_ipf_Object
	{
	}
}

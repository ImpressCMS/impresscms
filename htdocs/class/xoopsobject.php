<?php
// $Id$
if (!defined('ICMS_ROOT_PATH')) {
	exit();
}
/**
 * this file is for backward compatibility only
 * @package kernel
 * @deprecated use kernel/object.php instead
 * @todo remove this file in 1.4
 **/
icms_deprecated( '', 'class/xoopsobject.php will be removed in ImpressCMS 1.4 - use kernel/object.php');
/**
 * Load the new object class
 **/
require_once ICMS_ROOT_PATH.'/kernel/object.php';
?>
<?php
/**
 * this file is for backward compatibility only
 *
 **/
// $Id$
if (!defined('ICMS_ROOT_PATH')) {
	exit();
}
icms_core_Debug::setDeprecated( '', 'class/xoopsmodule.php will be removed in ImpressCMS 1.4 - use kernel/module.php' );
/**
 * load the new module class
 * @todo Remove this in 1.4
 * @deprecated user kernel/module.php instead
 **/
require_once ICMS_ROOT_PATH.'/kernel/module.php';
?>
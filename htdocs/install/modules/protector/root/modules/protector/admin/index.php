<?php
require '../../../mainfile.php';
if (!defined('ICMS_TRUST_PATH')) die('set ICMS_TRUST_PATH in mainfile.php');

$mydirname = basename(dirname(__DIR__));
$mydirpath = dirname(__DIR__);
require $mydirpath . '/mytrustdirname.php'; // set $mytrustdirname

require ICMS_TRUST_PATH . '/modules/' . $mytrustdirname . '/admin.php';

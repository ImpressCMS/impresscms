<?php
require '../../mainfile.php';
if (!defined('ICMS_TRUST_PATH')) die('set ICMS_TRUST_PATH in mainfile.php');

$mydirname = basename(__DIR__);
$mydirpath = __DIR__;
$mydirurl = ICMS_URL . '/modules/' . $mydirname;

require $mydirpath . '/mytrustdirname.php'; // set $mytrustdirname

$_GET['page'] = basename(__FILE__, '.php');

require ICMS_TRUST_PATH . '/modules/' . $mytrustdirname . '/main.php';

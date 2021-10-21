<?php
if (!defined('ICMS_TRUST_PATH')) die('set ICMS_TRUST_PATH into mainfile.php');

$mydirname = basename(__DIR__);
$mydirpath = __DIR__;
require $mydirpath . '/mytrustdirname.php'; // set $mytrustdirname

require ICMS_TRUST_PATH . '/modules/' . $mytrustdirname . '/search.php';

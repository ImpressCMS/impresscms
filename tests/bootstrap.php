<?php

$_SERVER['REMOTE_ADDR'] = '0.0.0.0';
require_once dirname(__DIR__) . '/htdocs/mainfile.php';

error_reporting(E_ALL & E_STRICT & ~E_DEPRECATED);
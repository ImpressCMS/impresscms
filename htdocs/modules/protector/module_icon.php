<?php

$icmsOption['nocommon'] = true ;
require '../../mainfile.php' ;

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'set ICMS_TRUST_PATH into mainfile.php' ) ;

$mydirname = basename( dirname( __FILE__ ) ) ;
$mydirpath = dirname( __FILE__ ) ;
require $mydirpath.'/mytrustdirname.php' ; // set $mytrustdirname

require ICMS_TRUST_PATH.'/modules/'.$mytrustdirname.'/module_icon.php' ;

?>
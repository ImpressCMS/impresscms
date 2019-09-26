<?php

$constpref = '_MI_' . strtoupper( $mydirname ) ;

$adminmenu = array(
	array(
		'title' => constant( $constpref.'_ADMININDEX' ) ,
		'link' => 'admin/index.php' ,
	) ,
	array(
		'title' => constant( $constpref.'_ADVISORY' ) ,
		'link' => 'admin/index.php?page=advisory' ,
	) ,
) ;

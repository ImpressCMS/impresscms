<?php
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$view_perms = 0 ;
if( is_object( $xoopsDB ) ) {
	if( ! is_object( $xoopsUser ) ) {
		$whr_groupid = "gperm_groupid=".XOOPS_GROUP_ANONYMOUS ;
	} else {
		$groups =& $xoopsUser->getGroups() ;
		$whr_groupid = "gperm_groupid IN (" ;
		foreach( $groups as $groupid ) {
			$whr_groupid .= "$groupid," ;
		}
		$whr_groupid = substr( $whr_groupid , 0 , -1 ) . ")" ;
	}
//	$rs = $xoopsDB->query( "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." WHERE gperm_modid='$myalbum_mid' AND gperm_name='myalbum_global' AND ($whr_groupid)" ) ;
	$sq2 = "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." LEFT JOIN ".$xoopsDB->prefix("modules")." m ON gperm_modid=m.mid WHERE m.dirname='$mydirname' AND gperm_name='mp_view' AND ($whr_groupid)" ;
	$rs = $xoopsDB->query($sq2); 
	while( list( $itemid ) = $xoopsDB->fetchRow( $rs ) ) {
		$view_perms |= $itemid ;
	}
}

$groupe_perms = array() ;
if( is_object( $xoopsDB ) ) {
	if( ! is_object( $xoopsUser ) ) {
		$whr_groupid = "gperm_groupid=".XOOPS_GROUP_ANONYMOUS ;
	} else {
		$groups =& $xoopsUser->getGroups() ;		
		$whr_groupid = "gperm_groupid IN (" ;
		foreach( $groups as $groupid ) {
			$whr_groupid .= "$groupid," ;
		}
		$whr_groupid = substr( $whr_groupid , 0 , -1 ) . ")" ;
	}
//	$rs = $xoopsDB->query( "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." WHERE gperm_modid='$myalbum_mid' AND gperm_name='myalbum_global' AND ($whr_groupid)" ) ;
	$sq3 = "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." LEFT JOIN ".$xoopsDB->prefix("modules")." m ON gperm_modid=m.mid WHERE m.dirname='$mydirname' AND gperm_name='mp_groupe' AND $whr_groupid" ;
	$rs3 = $xoopsDB->query($sq3); 
	while( list( $itemid ) = $xoopsDB->fetchRow( $rs3 ) ) {
		$groupe_perms[] |= $itemid ;
	}
}
?>
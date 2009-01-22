<?php



function smarty_function_xoManagerCountRead( $params, &$smarty ) {
	global $xoopsUser;
	
	if ( !isset($xoopsUser) || !is_object($xoopsUser) ) {
		return;
	}
	$time = time();
	if ( isset( $_SESSION['xoops_manager_countread'] ) && @$_SESSION['xoops_manager_countread_expire'] > $time ) {
		$count = intval( $_SESSION['xoops_manager_countread'] );
	} else {
        $pm_handler =& xoops_gethandler( 'privmessage' );
        $criteria = new CriteriaCompo( new Criteria('read_msg', 1) );
        $criteria = new Criteria( 'to_userid', $xoopsUser->getVar('uid') );
        $count = intval( $pm_handler->getCount($criteria) );
        $_SESSION['xoops_manager_countread'] = $count;
        $_SESSION['xoops_manager_countread_expire'] = $time;
	}
	if ( !@empty( $params['assign'] ) ) {
		$smarty->assign( $params['assign'], $count );
	} else {
		echo $count;
	}
}

?>
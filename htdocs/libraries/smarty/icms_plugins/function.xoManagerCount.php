<?php



function smarty_function_xoManagerCount( $params, &$smarty ) {
	global $xoopsUser;
	
	if ( !isset($xoopsUser) || !is_object($xoopsUser) ) {
		return;
	}
	$time = time();
	if ( isset( $_SESSION['xoops_manager_count'] ) && @$_SESSION['xoops_manager_count_expire'] > $time ) {
		$count = intval( $_SESSION['xoops_manager_count'] );
	} else {
        $pm_handler =& xoops_gethandler( 'privmessage' );
        $criteria = new CriteriaCompo( new Criteria('read_msg', 0) );
        $criteria->add( new Criteria( 'to_userid', $xoopsUser->getVar('uid') ) );
        $count = intval( $pm_handler->getCount($criteria) );
        $_SESSION['xoops_manager_count'] = $count;
        $_SESSION['xoops_manager_count_expire'] = $time;
	}
	if ( !@empty( $params['assign'] ) ) {
		$smarty->assign( $params['assign'], $count );
	} else {
		echo $count;
	}
}

?>
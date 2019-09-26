<?php

eval( ' function xoops_module_uninstall_'.$mydirname.'( $module ) { return protector_onuninstall_base( $module , "'.$mydirname.'" ) ; } ' ) ;


if( ! function_exists( 'protector_onuninstall_base' ) ) {

function protector_onuninstall_base( $module , $mydirname )
{
	// transations on module uninstall

	global $ret ; // TODO :-D

	if( ! is_array( $ret ) ) $ret = array() ;

	$db = icms_db_Factory::instance() ;
	$mid = $module->getVar('mid') ;

	// TABLES (loading mysql.sql)
	$sql_file_path = dirname(__FILE__).'/sql/mysql.sql' ;
	$prefix_mod = $db->prefix() . '_' . $mydirname ;
	if( file_exists( $sql_file_path ) ) {
		$ret[] = "SQL file found at <b>".htmlspecialchars($sql_file_path)."</b>.<br  /> Deleting tables...<br />";
		$sql_lines = file( $sql_file_path ) ;
		foreach( $sql_lines as $sql_line ) {
			if( preg_match( '/^CREATE TABLE \`?([a-zA-Z0-9_-]+)\`? /i' , $sql_line , $regs ) ) {
				$sql = 'DROP TABLE '.addslashes($prefix_mod.'_'.$regs[1]);
				if (!$db->query($sql)) {
					$ret[] = '<span style="color:#ff0000;">ERROR: Could not drop table <b>'.htmlspecialchars($prefix_mod.'_'.$regs[1]).'<b>.</span><br />';
				} else {
					$ret[] = 'Table <b>'.htmlspecialchars($prefix_mod.'_'.$regs[1]).'</b> dropped.<br />';
				}
			}
		}
	}


if(defined('ICMS_PRELOAD_PATH') && file_exists(ICMS_PRELOAD_PATH.'/protector.php')){
    icms_core_Filesystem::deleteFile(ICMS_PRELOAD_PATH.'/protector.php');
}

	return true ;
}

function protector_message_append_onuninstall( &$module_obj , &$log )
{
	if( is_array( @$GLOBALS['ret'] ) ) {
		foreach( $GLOBALS['ret'] as $message ) {
			$log->add( strip_tags( $message ) ) ;
		}
	}

}

}

?>
<?php

class upgrade_2018 {

	var $usedFiles = array( );

	function isApplied() {
		return ( /*$this->check_file_patch() &&*/ $this->check_rank_config() );
	}

	function apply() {
		$this->apply_alter_tables();
		return $this->apply_rank_config();
	}

	function check_file_patch() {
		/* $path = XOOPS_ROOT_PATH . '/class/auth';
		$lines = file( "$path/auth_provisionning.php");
		foreach ( $lines as $line ) {
			if ( strpos( $line, "ldap_provisionning_upd" ) !== false ) {
				// Patch found: do not apply again
				return true;
			}
		} */
		return true;
	}


	function check_rank_config() {
		$db = $GLOBALS['xoopsDB'];
		$value = getDbValue( $db, 'config', 'conf_id',
			"`conf_name` = 'rank_width' AND `conf_catid` = " . XOOPS_CONF_USER
		);
		return (bool)($value);
	}

	function query( $sql ) {
		echo $sql . "<br />";
		$db = $GLOBALS['xoopsDB'];
		if ( ! ( $ret = $db->queryF( $sql ) ) ) {
			echo $db->error();
		}
	}

	function apply_alter_tables() {
		$db = $GLOBALS['xoopsDB'];
		$this->fields = array(
			"config" => array( "conf_desc" => "varchar(100)" ),
			);

		foreach( $this->fields as $table => $data ) {
			foreach ($data as $field => $property) {
				$sql = "ALTER TABLE " . $db->prefix($table) . " CHANGE `$field` `$field` $property";
				$this->query( $sql );
			}
		}
		return true;
	}

	function apply_rank_config() {
		$db = $GLOBALS['xoopsDB'];

		// Insert config values
		$table = $db->prefix( 'config' );
		$data = array(
	   		'rank_width'	=> "'_MD_AM_RANKW', '120', '_MD_AM_RANKWDSC', 'textbox', 'int', 21",
	   		'rank_height'	=> "'_MD_AM_RANKH', '120', '_MD_AM_RANKHDSC', 'textbox', 'int', 21",
	   		'rank_maxsize'	=> "'_MD_AM_RANKMAX', '35000', '_MD_AM_RANKMAXDSC', 'textbox', 'int', 21",
		);
		reset($data);
		foreach ( $data as $name => $values ) {
			if ( !getDbValue( $db, 'config', 'conf_id', "`conf_modid`=0 AND `conf_catid`=2 AND `conf_name`='$name'" ) ) {
				$this->query(
					"INSERT INTO `$table` (conf_modid,conf_catid,conf_name,conf_title,conf_value,conf_desc,conf_formtype,conf_valuetype,conf_order) " .
					"VALUES ( 0,2,'$name',$values)"
				);
			}
		}
		return true;
	}
}

$upg = new upgrade_2018();
return $upg;

?>

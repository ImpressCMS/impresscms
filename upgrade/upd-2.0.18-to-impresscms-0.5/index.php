<?php
class upgrade_impcms05 {
	var $usedFiles = array ();
	function isApplied() {
		return ($this->check_conf_config());
	}
	function apply() {
		$this->apply_alter_tables();
		$this->apply_conf_configcategory();
		$this->apply_conf_config();
		$this->apply_ml_config();
		$this->blocks_engine_upgrade();
		return ($this->apply_new_blocks());
	}
	function apply_conf_configcategory() {
		$db = $GLOBALS['xoopsDB'];
		return $this->query(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('','_MD_AM_MULTILANGUAGE')");
	}
	function apply_new_blocks() {
		$db = $GLOBALS['xoopsDB'];
		$this->query(" INSERT INTO " . $db->prefix("newblocks") . " VALUES ('', 1, 0, '', 'Language Selection', 'Language Selection', '', 1, 0, 0, 'S', 'H', 1, 'system', 'system_blocks.php', 'b_system_multilanguage_show', '', 'system_block_multilanguage.html', 0, " . time() . ")");
		$new_block_id = $db->getInsertId();
		$this->query(" UPDATE " . $db->prefix("newblocks") . " SET func_num = " . $new_block_id . " WHERE bid=" . $new_block_id);
		$this->query(" INSERT INTO " . $db->prefix("tplfile") . " VALUES ('', " . $new_block_id . ", 'system', 'default', 'system_block_multilanguage.html', 'Displays image links to change the site language', " . time() . ", " . time() . ", 'block');");
		$new_tplfile_id = $db->getInsertId();
		$new_tpl_source = '<div align="center">\n	<{$block.ml_tag}>\n</div>';
		$this->query(" INSERT INTO " . $db->prefix("tplsource") . " VALUES (" . $new_tplfile_id . ", '" . $new_tpl_source . "');");
		$this->query(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0);");
		$this->query(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, " . $new_block_id . ", 1, 'block_read');");
		$this->query(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 2, " . $new_block_id . ", 1, 'block_read');");
		$this->query(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		return true;
	}
	function blocks_engine_upgrade() {
		echo '<h2>Updating blocks engine </h2>';
		if (!$this->table_exists('block_positions')) {
			$xoopsDB = $GLOBALS['xoopsDB'];
			$query = "CREATE TABLE `" . $xoopsDB->prefix('block_positions') . "` (
				  id int(11) NOT NULL auto_increment,
				  pname varchar(30) default '',
				  title varchar(90) NOT NULL default '',
				  description text,
				  block_default int(1) NOT NULL default '0',
				  block_type varchar(1) NOT NULL default 'L',
				  PRIMARY KEY  (`id`)
				  ) TYPE=MyISAM;";
			$this->query($query);
			$pos = array ();
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (1,'canvas_left','_AM_SBLEFT',NULL,1,'L');";
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (2,'canvas_right','_AM_SBRIGHT',NULL,1,'L');";
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (3,'page_topleft','_AM_CBLEFT',NULL,1,'C');";
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (4,'page_topcenter','_AM_CBCENTER',NULL,1,'C');";
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (5,'page_topright','_AM_CBRIGHT',NULL,1,'C');";
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (6,'page_bottomleft','_AM_CBBOTTOMLEFT',NULL,1,'C');";
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (7,'page_bottomcenter','_AM_CBBOTTOM',NULL,1,'C');";
			$pos[] = "INSERT INTO `" . $xoopsDB->prefix('block_positions') . "` VALUES (8,'page_bottomright','_AM_CBBOTTOMRIGHT',NULL,1,'C');";
			foreach ($pos as $p) {
				$this->query($p);
			}
			$curr_block_schema = array ();
			$curr_block_schema["XOOPS_SIDEBLOCK_LEFT"] = 0;
			$curr_block_schema["XOOPS_SIDEBLOCK_RIGHT"] = 1;
			$curr_block_schema["XOOPS_SIDEBLOCK_BOTH"] = 2;
			$curr_block_schema["XOOPS_CENTERBLOCK_LEFT"] = 3;
			$curr_block_schema["XOOPS_CENTERBLOCK_RIGHT"] = 4;
			$curr_block_schema["XOOPS_CENTERBLOCK_CENTER"] = 5;
			$curr_block_schema["XOOPS_CENTERBLOCK_ALL"] = 6;
			$curr_block_schema["XOOPS_CENTERBLOCK_BOTTOMLEFT"] = 7;
			$curr_block_schema["XOOPS_CENTERBLOCK_BOTTOMRIGHT"] = 8;
			$curr_block_schema["XOOPS_CENTERBLOCK_BOTTOM"] = 9;
			$curr_block_schema_id = array ();

			foreach ($curr_block_schema as $k => $v) {
				$sql = 'SELECT bid FROM ' . $xoopsDB->prefix('newblocks') . ' WHERE side = "' . $v . '"';
				echo "<li>" . $sql . "</li>";
				$result = $xoopsDB->query($sql);
				$curr_block_schema_id[$k] = array ();
				while (list ($bid) = $xoopsDB->fetchRow($result)) {
					$curr_block_schema_id[$k][] = $bid;
				}
			}
			echo '<h2>Relating old blocks schema with the new and updating data.</h2>';
			$err = 0;
			foreach ($curr_block_schema_id as $k => $v) {
				echo '<li>Position constant: ' . $k . '</li>';
				foreach ($v as $bid) {
					echo '<li>bid = ' . $bid . ' - old side = ' . $curr_block_schema[$k] . ' - new side = ' . constant($k) . '</li>';
					$sql = 'UPDATE ' . $xoopsDB->prefix('newblocks') . ' SET side = "' . constant($k) . '" WHERE bid = ' . $bid;
					$this->query($sql);
				}
			}
		}
		return true;
	}
	/**
	 * Verify that a mysql table exists
	 *
	 * @package News
	 * @author Hervé Thouzard (www.herve-thouzard.com)
	 * @copyright (c) The Xoops Project - www.xoops.org
	*/
	function table_exists($tablename) {
		global $xoopsDB;
		$query = "SHOW TABLES LIKE '" . $xoopsDB->prefix($tablename) . "'";
		$result = $xoopsDB->queryF($query);
		return ($xoopsDB->getRowsNum($result) > 0);
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
	function check_conf_config() {
		$db = $GLOBALS['xoopsDB'];
		$value = getDbValue($db, 'config', 'conf_id', "`conf_name` = 'rank_width' AND `conf_catid` = " . XOOPS_CONF_USER);
		return (bool) ($value);
	}
	function query($sql) {
		echo "<li>" . $sql . "</li>";
		$db = $GLOBALS['xoopsDB'];
		if (!($ret = $db->queryF($sql))) {
			echo "<li style='font-weight: bold; color: red;'>" . $db->error() . "</li>";
		}
	}
	function apply_alter_tables() {
		$db = $GLOBALS['xoopsDB'];
		$this->fields = array (
			"config" => array (
				"conf_desc" => "varchar(100)"
			),

		);
		foreach ($this->fields as $table => $data) {
			foreach ($data as $field => $property) {
				$sql = "ALTER TABLE " . $db->prefix($table) . " CHANGE `$field` `$field` $property";
				$this->query($sql);
			}
		}
		return true;
	}
	function apply_ml_config() {
		$db = $GLOBALS['xoopsDB'];
		// Insert config values
		$table = $db->prefix('config');
		$data = array (
			'ml_enable' => "'_MD_AM_ML_ENABLE', '0', '_MD_AM_ML_ENABLE_DESC', 'yesno', 'int', 0",
			'ml_tags' => "'_MD_AM_ML_TAGS', 'en,fr', '_MD_AM_ML_TAGS_DESC', 'textbox', 'text', 5",
			'ml_names' => "'_MD_AM_ML_NAMES', 'english,french', '_MD_AM_ML_NAMES_DESC', 'textbox', 'text', 10",
			'ml_captions' => "'_MD_AM_ML_CAPTIONS', 'English,Francais', '_MD_AM_ML_CAPTIONS_DESC', 'textbox', 'text', 15",
			);
		reset($data);
		foreach ($data as $name => $values) {
			if (!getDbValue($db, 'config', 'conf_id', "`conf_modid`=0 AND `conf_catid`=8 AND `conf_name`='$name'")) {
				$this->query("INSERT INTO `$table` (conf_modid,conf_catid,conf_name,conf_title,conf_value,conf_desc,conf_formtype,conf_valuetype,conf_order) VALUES ( 0,8,'$name',$values)");
			}
		}
		return true;
	}
	function apply_conf_config() {
		$db = $GLOBALS['xoopsDB'];
		// Insert config values
		$table = $db->prefix('config');
		$data = array (
			'rank_width' => "'_MD_AM_RANKW', '120', '_MD_AM_RANKWDSC', 'textbox', 'int', 21",
			'rank_height' => "'_MD_AM_RANKH', '120', '_MD_AM_RANKHDSC', 'textbox', 'int', 21",
			'rank_maxsize' => "'_MD_AM_RANKMAX', '35000', '_MD_AM_RANKMAXDSC', 'textbox', 'int', 21",
			'remember_me' => "'_MD_AM_REMEMBERME', '0', '_MD_AM_REMEMBERMEDSC', 'yesno', 'int', 30",
			'priv_dpolicy' => "'_MD_AM_PRIVDPOLICY', 1, '_MD_AM_PRIVDPOLICYDSC', 'yesno', 'int', 30",
			'priv_policy' => "'_MD_AM_PRIVPOLICY', '" . addslashes(_UPGRADE_PRIVPOLICY
		) . "', '_MD_AM_PRIVPOLICYDSC', 'textarea', 'text', 32");
		reset($data);
		foreach ($data as $name => $values) {
			if (!getDbValue($db, 'config', 'conf_id', "`conf_modid`=0 AND `conf_catid`=2 AND `conf_name`='$name'")) {
				$this->query("INSERT INTO `$table` (conf_modid,conf_catid,conf_name,conf_title,conf_value,conf_desc,conf_formtype,conf_valuetype,conf_order) VALUES ( 0,2,'$name',$values)");
			}
		}
		return true;
	}
}
$upg = new upgrade_impcms05();
return $upg;
?>

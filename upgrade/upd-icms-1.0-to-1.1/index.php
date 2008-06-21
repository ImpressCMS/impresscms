<?php

class upgrade_impcms06 {
	
	var $usedFiles = array ();
    var $tasks = array('conf', 'db', 'rest_of_upgrade', 'new_blocks');
	var $updater;
	
	function __construct() {
		$this->updater = XoopsDatabaseFactory::getDatabaseUpdater();
	}

    function isApplied()
    {
        if (!isset($_SESSION[__CLASS__]) || !is_array($_SESSION[__CLASS__])) {
            $_SESSION[__CLASS__] = array();
        }
        foreach ($this->tasks as $task) {
            if (!in_array($task, $_SESSION[__CLASS__])) {
                if (!$res = $this->{"check_{$task}"}()) {
                    $_SESSION[__CLASS__][] = $task;
                }
            }
        }
        return empty($_SESSION[__CLASS__]) ? true : false;
    }
    function apply()
    {
        $tasks = $_SESSION[__CLASS__];
        foreach ($tasks as $task) {
            $res = $this->{"apply_{$task}"}();
            if (!$res) return false;
            array_shift($_SESSION[__CLASS__]);
        }
		return ($this->cleaning_write_folders());
    }
    	function cleaning_write_folders() {
		$dir = array();
		$dir['templates_c'] = XOOPS_ROOT_PATH."/templates_c/";
		$dir['cache'] = XOOPS_ROOT_PATH."/cache/";

		foreach ($dir as $d)
		{
			$dd = opendir($d);
			while($file = readdir($dd))
			{
		 		if(is_file($d.$file) && ($file != 'index.html' && $file != 'php.ini' && $file != '.htaccess'))
				{
		  			unlink($d.$file);
				}
			}
			closedir($dd);
		}
			return true;
	}
function check_new_blocks()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }
	function apply_new_blocks() {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db,'newblocks','bid',' show_func="b_social_bookmarks"') != 0){return true;}
		$this->query(" INSERT INTO " . $db->prefix("newblocks") . " VALUES ('', 1, 0, '', 'Share this page!', 'Share this page!', '', 1, 0, 0, 'S', 'H', 1, 'system', 'social_bookmarks.php', 'b_social_bookmarks', '', 'system_block_socialbookmark.html', 0, " . time() . ")");
		$new_block_id = $db->getInsertId();
		$this->query(" UPDATE " . $db->prefix("newblocks") . " SET func_num = " . $new_block_id . " WHERE bid=" . $new_block_id);
		$this->query(" INSERT INTO " . $db->prefix("tplfile") . " VALUES ('', " . $new_block_id . ", 'system', 'default', 'system_block_socialbookmark.html', 'Displays image links to bookmark pages in sharing websites', " . time() . ", " . time() . ", 'block');");
		$new_tplfile_id = $db->getInsertId();
		$new_tpl_source = '<table cellspacing="0" class="outer">\n  <tr>\n    <td class="odd">\n		<{$block.bookmark}>\n	</td>\n  </tr>\n</table>';
		$this->query(" INSERT INTO " . $db->prefix("tplsource") . " VALUES (" . $new_tplfile_id . ", '" . $new_tpl_source . "');");
		$this->query(" INSERT INTO " . $db->prefix("block_module_link") . " VALUES (" . $new_block_id . ", 0);");
		$this->query(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 1, " . $new_block_id . ", 1, 'block_read');");
		$this->query(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 2, " . $new_block_id . ", 1, 'block_read');");
		$this->query(" INSERT INTO " . $db->prefix("group_permission") . " VALUES ('', 3, " . $new_block_id . ", 1, 'block_read');");
		return true;
	}
    function check_db()
    {
        $lines = file( XOOPS_ROOT_PATH . '/mainfile.php' );
        foreach ( $lines as $line ) {
            if( preg_match( '/(define\(\s*)([\'"])(XOOPS_DB_CHARSET)\\2,\s*([\'"])([^\'"]*?)\\4\s*\);/', $line ) ) {
                return true;
	}
        }
        return false;
    }
    
    function apply_db()
    {
        return $this->update_configs('db');
    }

    function update_configs($task)
    {
        if (!$vars = $this->set_configs($task) ) {
            return false;
        }
        if ($task == "db" && !empty($vars["XOOPS_DB_COLLATION"])) {
            if ($pos = strpos($vars["XOOPS_DB_COLLATION"], "_")) {
                $vars["XOOPS_DB_CHARSET"] = substr($vars["XOOPS_DB_COLLATION"], 0, $pos);
                $this->convert_db($vars["XOOPS_DB_CHARSET"], $vars["XOOPS_DB_COLLATION"]);
            }
        }
        
        return $this->write_mainfile($vars);
    }
    
    function convert_db($charset, $collation)
    {
	    $sql = "ALTER DATABASE `" . XOOPS_DB_NAME . "` DEFAULT CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation);
	    if ( !$GLOBALS["xoopsDB"]->queryF($sql) ) {
		    return false;
	    }
	    if ( !$result = $GLOBALS["xoopsDB"]->queryF("SHOW TABLES LIKE '" . XOOPS_DB_PREFIX . "\_%'") ) {
		    return false;
	    }
	    $tables = array();
	    while (list($table) = $GLOBALS["xoopsDB"]->fetchRow($result)) {
    	    $tables[] = $table;
		    //$GLOBALS["xoopsDB"]->queryF( "ALTER TABLE `{$table}` DEFAULT CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation) );
		    //$GLOBALS["xoopsDB"]->queryF( "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET " . $GLOBALS["xoopsDB"]->quote($charset) . " COLLATE " . $GLOBALS["xoopsDB"]->quote($collation) );
	    }
	    $this->convert_table($tables, $charset, $collation);
    }
    
    // Some code not ready to use
    function convert_table($tables, $charset, $collation)
    {
    	// Initialize vars.
    	$string_querys = array();
    	$binary_querys = array();
    	$gen_index_querys = array();
    	$drop_index_querys = array();
    	$tables_querys = array();
    	$optimize_querys = array();
    	$final_querys = array();
    
    	// Begin Converter Core
    	if ( !empty($tables) ) {
    		foreach ( (array) $tables as $table ) {
    			// Analyze tables for string types columns and generate his binary and string correctness sql sentences.
    			$resource = $GLOBALS["xoopsDB"]->queryF("DESCRIBE $table");
    			while ( $result = $GLOBALS["xoopsDB"]->fetchArray($resource) ) {
    				if ( preg_match('/(char)|(text)|(enum)|(set)/', $result['Type']) ) {
    					// String Type SQL Sentence.
    					$string_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . " CHARACTER SET $charset COLLATE $collation " . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    
    					// Binary String Type SQL Sentence.
    					if ( preg_match('/(enum)|(set)/', $result['Type']) ) {
    						$binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' CHARACTER SET binary ' . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    					} else {
    						$result['Type'] = preg_replace('/char/', 'binary', $result['Type']);
    						$result['Type'] = preg_replace('/text/', 'blob', $result['Type']);
    						$binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' ' . ( ( (!empty($result['Default'])) || ($result['Default'] === '0') || ($result['Default'] === 0) ) ? "DEFAULT '". $result['Default'] ."' " : '' ) . ( 'YES' == $result['Null'] ? '' : 'NOT ' ) . 'NULL';
    					}
    				}
    			}
    
    			// Analyze table indexs for any FULLTEXT-Type of index in the table.
    			$fulltext_indexes = array();
    			$resource = $GLOBALS["xoopsDB"]->queryF("SHOW INDEX FROM `$table`");
    			while ( $result = $GLOBALS["xoopsDB"]->fetchArray($resource) ) {
    				if ( preg_match('/FULLTEXT/', $result['Index_type']) )
    					$fulltext_indexes[$result['Key_name']][$result['Column_name']] = 1;
    			}
    
    			// Generate the SQL Sentence for drop and add every FULLTEXT index we found previously.
    			if ( !empty($fulltext_indexes) ) {
    				foreach ( (array) $fulltext_indexes as $key_name => $column ) {
    					$drop_index_querys[] = "ALTER TABLE `$table` DROP INDEX `$key_name`";
    					$tmp_gen_index_query = "ALTER TABLE `$table` ADD FULLTEXT `$key_name`(";
    					$fields_names = array_keys($column);
    					for ($i = 1; $i <= count($column); $i++)
    						$tmp_gen_index_query .= $fields_names[$i - 1] . (($i == count($column)) ? '' : ', ');
    					$gen_index_querys[] = $tmp_gen_index_query . ')';
    				}
    			}

    			// Generate the SQL Sentence for change default table character set.
    			$tables_querys[] = "ALTER TABLE `$table` DEFAULT CHARACTER SET $charset COLLATE $collation";

    			// Generate the SQL Sentence for Optimize Table.
    			$optimize_querys[] = "OPTIMIZE TABLE `$table`";
    		}

    	}
    	// End Converter Core

    	// Merge all SQL Sentences that we temporary store in arrays.
    	$final_querys = array_merge( (array) $drop_index_querys, (array) $binary_querys, (array) $tables_querys, (array) $string_querys, (array) $gen_index_querys, (array) $optimize_querys );

    	foreach ($final_querys as $sql) {
        	$GLOBALS["xoopsDB"]->queryF($sql);
    	}
    	
    	// Time to return.
    	return $final_querys;
    }

    function check_conf()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

 	function apply_conf() {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db,'configcategory','confcat_id',' confcat_name="_MD_AM_CONTMANAGER"') != 0){return true;}
		$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('9','_MD_AM_CONTMANAGER')");
		if (getDbValue($db,'configcategory','confcat_id',' confcat_name="_MD_AM_PERSON"') != 0){return true;}
		$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('10','_MD_AM_PERSON')");
        
        $passwordmeter_installed = false;
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix('config') . "` WHERE `conf_name` = 'pass_level'";
        if ( $result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
            list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
            if ($count == 1) {
                $passwordmeter_installed = true;
            }
        }
        if (!$passwordmeter_installed) {
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    " (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    " VALUES " .
                    " (NULL, 0, 2, 'pass_level', '_MD_AM_PASSLEVEL', '20', '_MD_AM_PASSLEVEL_DESC', 'select', 'int', 2)";

            if (!$GLOBALS['xoopsDB']->queryF( $sql )) {
                return false;
            }
            $config_id = $GLOBALS['xoopsDB']->getInsertId();
            
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('configoption') . 
                    " (confop_id, confop_name, confop_value, conf_id)" .
                    " VALUES" .
                    " (NULL, '_MD_AM_PASSLEVEL1', '20', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL2', '40', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL3', '60', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL4', '80', {$config_id})";
                    " (NULL, '_MD_AM_PASSLEVEL5', '95', {$config_id})";
            if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
                return false;
            }
        }
        
		$pw_salt_installed = false;
		$sql = "SELECT COUNT(*) FROM `" . $GLOBALS ['xoopsDB']->prefix ( 'config' ) . "` WHERE `conf_name` = 'enc_type'";
		if ($result = $GLOBALS ['xoopsDB']->queryF ( $sql ))
		{
			list ( $count ) = $GLOBALS ['xoopsDB']->fetchRow ( $result );
			if ($count == 1)
			{
				$pw_salt_installed = true;
			}
		}
        	
		if (!$pw_salt_installed)
		{
            		$sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    	" (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    	" VALUES " .
                    	" (NULL, 0, 2, 'enc_type', '_MD_AM_ENC_TYPE', '0', '_MD_AM_ENC_TYPEDSC', 'select', 'int', 50)";

            		if (!$GLOBALS['xoopsDB']->queryF( $sql ))
			{
                		return false;
            		}
            		$config_id = $GLOBALS['xoopsDB']->getInsertId();
            
            		$sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('configoption') . 
                    	" (confop_id, confop_name, confop_value, conf_id)" .
                    	" VALUES" .
                    	" (NULL, '_MD_AM_ENC_MD5', '0', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_SHA256', '1', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_SHA384', '2', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_SHA512', '3', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_RIPEMD128', '4', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_RIPEMD160', '5', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_WHIRLPOOL', '6', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1284', '7', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1604', '8', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1924', '9', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2244', '10', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2564', '11', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1285', '12', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1605', '13', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL1925', '14', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2245', '15', {$config_id})," .
                    	" (NULL, '_MD_AM_ENC_HAVAL2565', '16', {$config_id})";
            		if (!$result = $GLOBALS['xoopsDB']->queryF($sql))
			{
                		return false;
            		}

			$sql = "ALTER TABLE ".$GLOBALS['xoopsDB']->prefix('users')." ADD 'salt' VARCHAR(255)";
			$sql = "ALTER TABLE ".$GLOBALS['xoopsDB']->prefix('users')." CHANGE pass pass VARCHAR(255)";
            		if (!$result = $GLOBALS['xoopsDB']->queryF($sql))
			{
                		return false;
            		}
        	}
        
        return $result;

	}
    function write_mainfile($vars)
    {
        if (empty($vars)) {
            return false;
        }

        $file = dirname(__FILE__) . '/mainfile.dist.php';

        $lines = file($file);
        foreach (array_keys($lines) as $ln) {
            if ( preg_match("/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", $lines[$ln], $matches ) ) {
                $val = isset( $vars[$matches[3]] ) 
                        ? strval( constant($matches[3]) ) 
                        : ( defined($matches[3]) 
                            ? strval( constant($matches[3]) ) 
                            : "0"
                          );
                $lines[$ln] = preg_replace( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", 
                    "define( '" . $matches[3] . "', " . $val . " )", 
                    $lines[$ln] );
            } elseif( preg_match( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])([^\"']*?)\\4\s*\)/", $lines[$ln], $matches ) ) {
                $val = isset( $vars[$matches[3]] )
                        ? strval( $vars[$matches[3]] )
                        : ( defined($matches[3])
                            ? strval( constant($matches[3]) ) 
                            : ""
                          );
                $lines[$ln] = preg_replace( "/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])(.*?)\\4\s*\)/",
                    "define( '" . $matches[3] . "', '" . $val . "' )", 
                    $lines[$ln] );
            }
        }
        
        $fp = fopen( XOOPS_ROOT_PATH . '/mainfile.php', 'wt' );
        if ( !$fp ) {
            echo ERR_COULD_NOT_WRITE_MAINFILE;
            echo "<pre style='border: 1px solid black; width: 80%; overflow: auto;'><div style='color: #ff0000; font-weight: bold;'><div>" . implode("</div><div>", array_map("htmlspecialchars", $lines)) . "</div></div></pre>";
            return false;
        } else {
            $newline = defined( PHP_EOL ) ? PHP_EOL : ( strpos( php_uname(), 'Windows') ? "\r\n" : "\n" );
            $content = str_replace( array("\r\n", "\n"), $newline, implode('', $lines) );

            fwrite( $fp,  $content );
            fclose( $fp );
            return true;
        }
    }


    function set_configs($task)
    {
        $ret = array();
        $configs = include dirname(__FILE__) . "/settings_{$task}.php";
        if ( !$configs || !is_array($configs) ) {
            return $ret;
        }
        if (empty($_POST['action']) || $_POST['task'] != $task) {
            return false;
        }
        
        foreach ( $configs as $key => $val ) {
            $ret['XOOPS_' . $key] = $val;
        }
        return $ret;
        
    }
       function check_rest_of_upgrade()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

    function apply_rest_of_upgrade() {
		// Now, first, let's increment the conf_order of user option starting at new_user_notify
		$table = new IcmsDatabasetable('config');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('conf_order', 3, '>'));
    	$table->addUpdateAll('conf_order', 'conf_order + 2', $criteria, true);
	    $this->updater->updateTable($table);	
	    unset($table);	
	    
	    // create extended date function's config option
	    $this->updater->insertConfig(XOOPS_CONF, 'use_ext_date', '_MD_AM_EXT_DATE', 0, '_MD_AM_EXT_DATEDSC', 'yesno', 'int', 11);
	    // create editors config option
	    $this->updater->insertConfig(XOOPS_CONF, 'editor_default', '_MD_AM_EDITOR_DEFAULT', 'default', '_MD_AM_EDITOR_DEFAULT_DESC', 'editor', 'text', 15);
	    $this->updater->insertConfig(XOOPS_CONF, 'editor_enabled_list', '_MD_AM_EDITOR_ENABLED_LIST', ".addslashes(serialize(array('default'))).", '_MD_AM_EDITOR_ENABLED_LIST_DESC', 'editor_multi', 'array', 15);
	    // create captcha options
	    $this->updater->insertConfig(XOOPS_CONF, 'use_captchaf', '_MD_AM_USECAPTCHAFORM', 1, '_MD_AM_USECAPTCHAFORMDSC', 'yesno', 'int', 36);

	    // create 4 new user config options
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'use_captcha', '_MD_AM_USECAPTCHA', 1, '_MD_AM_USECAPTCHADSC', 'yesno', 'int', 2);
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'welcome_msg', '_MD_AM_WELCOMEMSG', 0, '_MD_AM_WELCOMEMSGDSC', 'yesno', 'int', 3);
	    // get the default content of the mail
	    global $xoopsConfig;
	    $default_msg_content_file = XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/mail_template/' . 'welcome.tpl';
	    if (!file_exists($default_msg_content_file)) {
	    	$default_msg_content_file = XOOPS_ROOT_PATH . '/language/english/mail_template/' . 'welcome.tpl';
	    }
	    $fp = fopen($default_msg_content_file, 'r');
        if ($fp) {
            $default_msg_content = fread($fp, filesize($default_msg_content_file));
        }
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'welcome_msg_content', '_MD_AM_WELCOMEMSG_CONTENT', $default_msg_content, '_MD_AM_WELCOMEMSG_CONTENTDSC', 'textarea', 'text', 3);	   
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'allwshow_sig', '_MD_AM_ALLWSHOWSIG', 1, '_MD_AM_ALLWSHOWSIGDSC', 'yesno', 'int', 4);
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'allow_htsig', '_MD_AM_ALLWHTSIG', 1, '_MD_AM_ALLWHTSIGDSC', 'yesno', 'int', 4);
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'sig_max_length', '_MD_AM_SIGMAXLENGTH', '255', '_MD_AM_SIGMAXLENGTHDSC', 'textbox', 'int', 4);
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'avatar_allow_gravatar', '_MD_AM_GRAVATARALLOW', '1', '_MD_AM_GRAVATARALWDSC', 'yesno', 'int', 15);
	    $this->updater->insertConfig(XOOPS_CONF_USER, 'allow_annon_view_prof', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE', '1', '_MD_AM_ALLOW_ANONYMOUS_VIEW_PROFILE_DESC', 'yesno', 'int', 36);

	    // Adding configurations of meta tag&footer
	    $this->updater->insertConfig(XOOPS_CONF_METAFOOTER, 'google_meta', '_MD_AM_METAGOOGLE', '', '_MD_AM_METAGOOGLE_DESC', 'textbox', 'text', 9);
	    $this->updater->insertConfig(XOOPS_CONF_METAFOOTER, 'google_analytics', '_MD_AM_GOOGLE_ANA', '', '_MD_AM_GOOGLE_ANA_DESC', 'textsarea', 'text', 21);
	    $this->updater->insertConfig(XOOPS_CONF_METAFOOTER, 'footadm', '_MD_AM_FOOTADM', 'Powered by ImpressCMS &copy; 2007-' . date("Y", time()) . ' <a href=\"http://www.impresscms.org/\" rel=\"external\">The ImpressCMS Project</a>', '_MD_AM_FOOTADM_DESC', 'textarea', 'text', 22);

	    // Adding configurations of search preferences
	    $this->updater->insertConfig(XOOPS_CONF_SEARCH, 'search_user_date', '_MD_AM_SEARCH_USERDATE', '1', '_MD_AM_SEARCH_USERDATE', 'yesno', 'int', 2);
	    $this->updater->insertConfig(XOOPS_CONF_SEARCH, 'search_no_res_mod', '_MD_AM_SEARCH_NO_RES_MOD', '1', '_MD_AM_SEARCH_NO_RES_MODDSC', 'yesno', 'int', 3);
	    $this->updater->insertConfig(XOOPS_CONF_SEARCH, 'search_per_page', '_MD_AM_SEARCH_PER_PAGE', '20', '_MD_AM_SEARCH_PER_PAGEDSC', 'textbox', 'int', 4);

		// Adding new cofigurations added for multi language
	    $this->updater->insertConfig(IM_CONF_MULILANGUAGE, 'ml_autoselect_enabled', '_MD_AM_ML_AUTOSELECT_ENABLED', '0', '_MD_AM_ML_AUTOSELECT_ENABLED_DESC', 'yesno', 'int', 1);
	    
	    // Adding new function of content manager
	    $this->updater->insertConfig(IM_CONF_CONTENT, 'default_page', '_MD_AM_DEFAULT_CONTPAGE', '0', '_MD_AM_DEFAULT_CONTPAGEDSC', 'select_pages', 'int', 1);
	    $this->updater->insertConfig(IM_CONF_CONTENT, 'show_nav', '_MD_AM_CONT_SHOWNAV', '1', '_MD_AM_CONT_SHOWNAVDSC', 'yesno', 'int', 2);
	    $this->updater->insertConfig(IM_CONF_CONTENT, 'show_subs', '_MD_AM_CONT_SHOWSUBS', '1', '_MD_AM_CONT_SHOWSUBSDSC', 'yesno', 'int', 3);
	    $this->updater->insertConfig(IM_CONF_CONTENT, 'show_pinfo', '_MD_AM_CONT_SHOWPINFO', '1', '_MD_AM_CONT_SHOWPINFODSC', 'yesno', 'int', 4);
	    global $xoopsConfig;
	    $default_login_content_file = XOOPS_ROOT_PATH . '/upgrade/language/' . $xoopsConfig['language'] . '/' . 'login.tpl';
	    if (!file_exists($default_login_content_file)) {
	    	$default_login_content_file = XOOPS_ROOT_PATH . '/upgrade/language/english/' . 'login.tpl';
	    }
	    $fp = fopen($default_login_content_file, 'r');
        if ($fp) {
            $default_login_content = fread($fp, filesize($default_login_content_file));
        }
	    // Adding new function of Personalization
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'adm_left_logo', '_MD_AM_LLOGOADM', '/uploads/img482278e29e81c.png', '_MD_AM_LLOGOADM_DESC', 'select_image', 'text', 1);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'adm_left_logo_url', '_MD_AM_LLOGOADM_URL', XOOPS_URL, '_MD_AM_LLOGOADM_URL_DESC', 'textbox', 'text', 2);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'adm_left_logo_alt', '_MD_AM_LLOGOADM_ALT', 'ImpressCMS', '_MD_AM_LLOGOADM_ALT_DESC', 'textbox', 'text', 3);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'adm_right_logo', '_MD_AM_RLOGOADM', '', '_MD_AM_RLOGOADM_DESC', 'select_image', 'text', 4);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'adm_right_logo_url', '_MD_AM_RLOGOADM_URL', '', '_MD_AM_RLOGOADM_URL_DESC', 'textbox', 'text', 5);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'adm_right_logo_alt', '_MD_AM_RLOGOADM_ALT', '', '_MD_AM_RLOGOADM_ALT_DESC', 'textbox', 'text', 6);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'rss_local', '_MD_AM_RSSLOCAL', 'http://www.impresscms.org/modules/smartsection/backend.php', '_MD_AM_RSSLOCAL_DESC', 'textbox', 'text', 7);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'editre_block', '_MD_AM_EDITREMOVEBLOCK', '1', '_MD_AM_EDITREMOVEBLOCKDSC', 'yesno', 'int', 8);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'multi_login', '_MD_AM_MULTLOGINPREVENT', '0', '_MD_AM_MULTLOGINPREVENTDSC', 'yesno', 'int', 9);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'multi_login_msg', '_MD_AM_MULTLOGINMSG', $default_login_content, '_MD_AM_MULTLOGINMSG_DESC', 'textarea', 'text', 10);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'email_protect', '_MD_AM_EMAILPROTECT', '0', '_MD_AM_EMAILPROTECTDSC', 'yesno', 'int', 11);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'email_font', '_MD_AM_EMAILTTF', 'arial.ttf', '_MD_AM_EMAILTTF_DESC', 'select_font', 'text', 12);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'email_font_len', '_MD_AM_EMAILLEN', '12', '_MD_AM_EMAILLEN_DESC', 'textbox', 'int', 13);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'email_cor', '_MD_AM_EMAILCOLOR', '#000000', '_MD_AM_EMAILCOLOR_DESC', 'color', 'text', 14);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'email_shadow', '_MD_AM_EMAILSHADOW', '#cccccc', '_MD_AM_EMAILSHADOW_DESC', 'color', 'text', 15);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'shadow_x', '_MD_AM_SHADOWX', '2', '_MD_AM_SHADOWX_DESC', 'textbox', 'int', 16);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'shadow_y', '_MD_AM_SHADOWY', '2', '_MD_AM_SHADOWY_DESC', 'textbox', 'int', 17);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'shorten_url', '_MD_AM_SHORTURL', '0', '_MD_AM_SHORTURLDSC', 'yesno', 'int', 18);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'max_url_long', '_MD_AM_URLLEN', '50', '_MD_AM_URLLEN_DESC', 'textbox', 'int', 19);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'pre_chars_left', '_MD_AM_PRECHARS', '35', '_MD_AM_PRECHARS_DESC', 'textbox', 'int', 20);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'last_chars_left', '_MD_AM_LASTCHARS', '10', '_MD_AM_LASTCHARS_DESC', 'textbox', 'int', 21);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'show_impresscms_menu', '_MD_AM_SHOW_ICMSMENU', '1', '_MD_AM_SHOW_ICMSMENU_DESC', 'yesno', 'int', 22);
	    $this->updater->insertConfig(XOOPS_CONF_PERSONA, 'use_hidden', '_MD_AM_HIDDENCONTENT', '0', '_MD_AM_HIDDENCONTENTDSC', 'yesno', 'int', 23);
	    
	    $table = new IcmsDatabasetable('imagecategory');
	    $this->updater->runQuery('INSERT INTO '.$table->name().' (imgcat_id, imgcat_name, imgcat_maxsize, imgcat_maxwidth, imgcat_maxheight, imgcat_display, imgcat_weight, imgcat_type, imgcat_storetype) VALUES (NULL, "Logos", 350000, 350, 80, 1, 0, "C", "file")','Successfully created Logos imagecategory','Problems when try to create Logos imagecategory');
	    unset($table);
	    
	    $result = $this->updater->_db->query("SELECT imgcat_id FROM ".$this->updater->_db->prefix('imagecategory')." WHERE imgcat_name = 'Logos'");
	    list($categ_id) = $this->updater->_db->fetchRow($result);
	    
		$table = new IcmsDatabasetable('image');
	    $this->updater->runQuery('INSERT INTO '.$table->name().' (image_id, image_name, image_nicename, image_mimetype, image_created, image_display, image_weight, imgcat_id) VALUES (1, "img482278e29e81c.png", "ImpressCMS", "image/png", '.time().', 1, 0, '.$categ_id.')','Successfully added default ImpressCMS admin logo','Problems when try to add ImpressCMS admin logo');
	    unset($table);

	    $table = new IcmsDatabasetable('group_permission');
	    $this->updater->runQuery('INSERT INTO '.$table->name().' VALUES(0,1,'.$categ_id.',1,"imgcat_write")','','');
	    $this->updater->runQuery('INSERT INTO '.$table->name().' VALUES(0,1,'.$categ_id.',1,"imgcat_read")','','');
	    unset($table);
	    
		// Create table icmspage
		$table = new IcmsDatabasetable('icmspage');
		if (!$table->exists()) {
	    $table->setStructure("page_id mediumint(8) unsigned NOT NULL auto_increment,
		  page_moduleid mediumint(8) unsigned NOT NULL default '1',
		  page_title varchar(255) NOT NULL default '',
		  page_url varchar(255) NOT NULL default '',
		  page_status tinyint(1) unsigned NOT NULL default '1',
		  PRIMARY KEY  (page_id)");
		}
		unset($table);
		
 		$table = new IcmsDatabasetable('block_module_link');
		$table->addNewField('page_id', "smallint(5) NOT NULL default '0'");
		unset($table);
		
		// Block Visibility
		$this->updater->runQuery('UPDATE '.$table->name().' SET module_id=0, page_id=1 WHERE module_id=-1','Block Visibility Restructured Successfully', 'Failed in Restructure the Block Visibility');

		// Create table system_customtag
		$table = new IcmsDatabasetable('system_customtag');
		if (!$table->exists()) {
	    $table->setStructure("customtagid int(11) unsigned NOT NULL auto_increment,
		  name varchar(255) NOT NULL default '',
		  description text NOT NULL default '',
		  content text NOT NULL default '',
		  language varchar(100) NOT NULL default '',
		  customtag_type tinyint(1) NOT NULL default 0,
		  PRIMARY KEY (customtagid)");
		}
	    unset($table);
    	$table = new IcmsDatabasetable('modules');
    	$table->addNewField('dbversion', 'INT(11) DEFAULT 0');
		$icmsDatabaseUpdater->updateTable($table);
	    
	    // Updating the system module dbversion field. This needs to be at the very end of the upgrade script
		$icmsDatabaseUpdater->updateModuleDBVersion(1, 'system');
		
	}

}

$upg = new upgrade_impcms06();
return $upg;
?>
<?php
include_once 'mainfile.php';				// Load basic Xoops environment (required).
include_once XOOPS_ROOT_PATH.'/header.php';		// Load page header (required).

if ($_POST) {
	global $xoopsDB;
	
	$column_names = "conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order";
	
	$xoopsDB->query(sprintf("INSERT INTO %s ($column_names) VALUES(0, 5, 'enable_deep_search', '_MD_AM_DODEEPSEARCH', '1', '_MD_AM_DODEEPSEARCHDSC', 'yesno', 'int', 0)",$xoopsDB->prefix("config")));
	
	$xoopsDB->query(sprintf("INSERT INTO %s ($column_names) VALUES (0, 5, 'num_shallow_search', '_MD_AM_NUMINITSRCHRSLTS', '5', '_MD_AM_NUMINITSRCHRSLTSDSC', 'textbox', 'int', 1)",$xoopsDB->prefix("config")));
	
	$xoopsDB->query(sprintf("INSERT INTO %s ($column_names) VALUES (0, 5, 'num_module_search', '_MD_AM_NUMMDLSRCHRESULTS', '20', '_MD_AM_NUMMDLSRCHRESULTSDSC', 'textbox', 'int', 2)",$xoopsDB->prefix("config")));
	
	echo "<p>You may now customize your search experience via the admin panel.</p>";
	echo "<p>Be sure to update the 'System Admin' module before you try using the new 'deep' searching option (so that your search templates get updated).</p>";
} else {
	?>
	<p>To install the search hack, click the button below:</p>
	<form name="install_hack" action="update_search.php" method="post">
		<input type="submit" value="Upgrade" />
	<?php
}


include XOOPS_ROOT_PATH . '/footer.php';		// Load page foot (required).
?>
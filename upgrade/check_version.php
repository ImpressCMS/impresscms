<?php
	defined( 'XOOPS_ROOT_PATH' ) or die();

	$dirs = getDirList( "." );

	$results = array();
	$files = array();
	$needUpgrade = false;
	
	$_SESSION['xoops_upgrade'] = array();

	foreach ( $dirs as $dir ) {
		if ( strpos( $dir, "-to-" ) ) {
			$upgrader = include_once "$dir/index.php";
			if ( is_object( $upgrader ) ) {
				if ( !( $results[$dir] = $upgrader->isApplied() ) ) {
					$_SESSION['xoops_upgrade'][] = $dir;
					$needUpgrade = true;
					if ( !empty( $upgrader->usedFiles ) ) {
						$files = array_merge( $files, $upgrader->usedFiles );
					}
				}
			}
		}
	}

	if ( $needUpgrade && !empty( $files ) ) {
		foreach ( $files as $k => $file ) {
			if ( is_writable( "../$file" ) ) {
				unset( $files[$k] );
			}
		}
	}
?>
<h2><?php echo _CHECKING_APPLIED; ?></h2>

<table id="check_results">
<?php foreach ( $results as $upd => $res ) { ?>
	<tr>
		<td><?php echo $upd; ?></td>
		<td><img src="img/<?php echo $res?'yes':'no'; ?>.png" alt="<?php echo $res?_YES:_NO; ?>" /></td>
	</tr>
<?php } ?>
</table>
<?php
	if ( !$needUpgrade ) {
		echo '<div class="x2-note">' . _NO_NEED_UPGRADE . '<br /><br /><a id="link-next" href="'. XOOPS_URL .'/modules/system/admin.php?fct=modulesadmin&op=update&module=system">' . _SYS_NEED_UPGRADE . "</a></div>";
		return;
	} else {
       echo'<div class="x2-note"><input class="checkbox" type="checkbox" id="help_button" onclick="showHideHelp(this)" />
        '._I_AM_AWARE.'</div>
        <div class="xoform-help">';
		if ( !empty( $files ) ) {
			echo '<div class="x2-note"><p>' . _NEED_UPGRADE . "</p>" . _SET_FILES_WRITABLE . "<br /><br /><ul>";
			foreach ( $files as $file ) echo "<li>$file</li>\n";
			echo "</ul></div><br /><br />";
		} else {
						if ( !is_writable(XOOPS_ROOT_PATH."/mainfile.php" ) ) {
			echo '<div class="x2-note"><p>' . _NEED_UPGRADE . "</p>" . _SET_FILES_WRITABLE . "<br /><br /><ul>";
echo "<li>".XOOPS_ROOT_PATH."/mainfile.php</li>\n";
			echo "</ul></div>";}else{
			echo '<a id="link-next" href="index.php?action=next">' . _PROCEED_UPGRADE . '</a><br /><br />';
		}
	}
}
?></div>

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
		echo '<div class="x2-note">' . _NO_NEED_UPGRADE . "</div>";
		return;
	} else {
		if ( !empty( $files ) ) {
			echo '<div class="x2-note"><p>' . _NEED_UPGRADE . "</p>" . _SET_FILES_WRITABLE . "<br /><ul>";
			foreach ( $files as $file ) echo "<li>$file</li>\n";
			echo "</ul></div>";
		} else {
			echo '<a id="link-next" href="index.php?action=next">' . _PROCEED_UPGRADE . '</a>';
		}
	}
?>

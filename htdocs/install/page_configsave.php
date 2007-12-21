<?php
/**
* Installer mainfile creation page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        2.3.0
* @author		Haruki Setoyama  <haruki@planewave.org>
* @author 		Kazumi Ono <webmaster@myweb.ne.jp>
* @author		Skalpa Keo <skalpa@xoops.org>
* @version		$Id$
*/

require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )	exit();

	$wizard->setPage( 'configsave' );
	$pageHasForm = true;
	$pageHasHelp = false;

	$vars =& $_SESSION['settings'];
	
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$error = '';
	if ( !copy( $vars['ROOT_PATH'] . '/mainfile.dist.php', $vars['ROOT_PATH'] . '/mainfile.php' ) ) {
		$error = ERR_COPY_MAINFILE;
	} else {
		clearstatcache();

		$rewrite = array( 'GROUP_ADMIN' => 1, 'GROUP_USERS' => 2, 'GROUP_ANONYMOUS' => 3 );
		$rewrite = array_merge( $rewrite, $vars );
		if ( ! $file = fopen( $vars['ROOT_PATH'] . '/mainfile.php', "r" ) ) {
			$error = ERR_READ_MAINFILE;
        } else {
        	$content = fread( $file, filesize( $vars['ROOT_PATH'] . '/mainfile.php' ) );
        	fclose($file);

			foreach( $rewrite as $key => $val ) {
				if ( is_int($val) && preg_match("/(define\()([\"'])(XOOPS_$key)\\2,\s*([0-9]+)\s*\)/", $content ) ) {
					$content = preg_replace( "/(define\()([\"'])(XOOPS_$key)\\2,\s*([0-9]+)\s*\)/",
						"define( 'XOOPS_$key', $val )", $content );
				} elseif( preg_match( "/(define\()([\"'])(XOOPS_$key)\\2,\s*([\"'])(.*?)\\4\s*\)/", $content ) ) {
					$val = addslashes( $val );
					$content = preg_replace( "/(define\()([\"'])(XOOPS_$key)\\2,\s*([\"'])(.*?)\\4\s*\)/",
						"define( 'XOOPS_$key', '$val' )", $content );
				} else {
					//$this->error = true;
					//$this->report .= _NGIMG.sprintf( ERR_WRITING_CONSTANT, "<b>$val</b>")."<br />\n";
				}
			}
	        if ( !$file = fopen( $vars['ROOT_PATH'] . '/mainfile.php', "w" ) ) {
	        	$error = ERR_WRITE_MAINFILE;
	        } else {
		        if ( fwrite( $file, $content ) == -1 ) {
					$error = ERR_WRITE_MAINFILE;
	        	}
				fclose($file);
	        }
        }
	}
	if ( empty( $error ) ) {
		$wizard->redirectToPage( '+1' );
		exit();
	}
	$content = '<p class="errorMsg">' . $error . '</p>';
    include 'install_tpl.php';
    exit();
}

    ob_start();
?>
	<p class="x2-note"><?php echo READY_SAVE_MAINFILE; ?></p>
	<dl style="height:200px;overflow:auto;border:1px solid #000000">
	<?php foreach ( $vars as $k => $v ) {
		echo "<dt>XOOPS_$k</dt><dd>$v</dd>";
	} ?>
	</dl>

<?php
	$content = ob_get_contents();
	ob_end_clean();
    include 'install_tpl.php';
?>
<?php
/**
* Installer tables creation page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The ImpressCMS project http://www.impresscms.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        1.0
* @author		Haruki Setoyama  <haruki@planewave.org>
* @author 		Kazumi Ono <webmaster@myweb.ne.jp>
* @author		Skalpa Keo <skalpa@xoops.org>
* @version		$Id$
*/
/**
 *
 */
	defined( 'XOOPS_INSTALL' ) or die();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>
		<?php echo sprintf(XOOPS_INSTALL_WIZARD, XOOPS_VERSION); ?>
		(<?php echo ($wizard->currentPage+1) . '/' . count($wizard->pages); ?>)
	</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo _INSTALL_CHARSET ?>" />
<?php
	if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL ){
echo '<link rel="stylesheet" type="text/css" media="all" href="style_rtl.css" />';
	   } else {
echo '<link rel="stylesheet" type="text/css" media="all" href="style.css" />';
           }
?>
		<script type="text/javascript" src="../libraries/prototype/prototype.js"></script>
	<script type="text/javascript">
	function showHideHelp( butt ) {
		butt.className = ( butt.className == 'on' ) ? 'off': 'on';
		document.body.className = ( butt.className == 'on' ) ? 'show-help': '';
	}
	</script>
</head>
<body>
<div id="xo-banner"><img src="img/header-logo.png" alt="ImpressCMS" /></div>
<div id="xo-content">
	<div class="tagsoup1">
	<div class="tagsoup2">
		<div id="wizard">
		<form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'>
			<h1>
				<span id="title"><?php echo sprintf(XOOPS_INSTALL_WIZARD, XOOPS_VERSION)."&nbsp;-&nbsp;".INSTALL_STEP; ?>&nbsp;<?php echo ($wizard->currentPage+1) . INSTALL_OUTOF . count($wizard->pages); ?></span>
			</h1>
			<ul id="pageslist" class="x2-navigation">
			<?php foreach ( $wizard->pages as $k => $page ) {
				$class = '';
				if ( $k == $wizard->currentPage )	$class = ' class="current"';
				elseif ( $k > $wizard->currentPage )	$class = ' class="disabled"';
				if ( empty( $class ) ) {
					$li = '<a href="' . $wizard->pageURI($page) . '">' . $wizard->pagesNames[$k] . '</a>';
				} else {
					$li = $wizard->pagesNames[$k];
				}
				echo "<li$class>$li</li>\n";
			} ?>
			</ul>
			<div class="page" id="<?php echo $wizard->currentPageName; ?>">
				<?php if ( $pageHasHelp ) { ?>
					<button type="button" id="help_button" onclick="showHideHelp(this)"> <?php echo SHOW_HIDE_HELP; ?></button>
				<?php } ?>
				<h2><?php echo $wizard->pagesTitles[ $wizard->currentPage ]; ?></h2>
				<?php echo $content; ?>
			</div>
			<div id="buttons">
				<?php if ( $wizard->currentPage != 0  && ( $wizard->currentPage != 11 )) { ?>
				<button type="button" onclick="location.href='<?php echo $wizard->pageURI('-1'); ?>'">
					<?php echo BUTTON_PREVIOUS; ?>
				</button>
				<?php } ?>
				<?php if ( $wizard->currentPage == 11 ) { ?>
				<button type="button" onclick="location.href='../index.php'">
					<?php echo BUTTON_SHOW_SITE; ?>
				</button>
				<?php } ?>
				<?php if ( $wizard->pages[$wizard->currentPage] == $wizard->secondlastpage) { ?>
					<?php if ( @$pageHasForm) { ?>
					<button type="submit">
					<?php } else { ?>
					<button type="button" accesskey="n" onclick="location.href='<?php echo $wizard->pageURI('+1'); ?>'">
					<?php } ?>
					<?php if ( $_POST['mod'] != 1 ) { ?>
						<?php echo BUTTON_NEXT; ?>
					<?php } else { ?>
						<?php echo BUTTON_FINISH; ?>
					<?php } ?>
					</button>
				<?php } else if ( $wizard->pages[$wizard->currentPage] != $wizard->lastpage) { ?>
					<?php if ( @$pageHasForm) { ?>
					<button type="submit">
					<?php } else { ?>
					<button type="button" accesskey="n" onclick="location.href='<?php echo $wizard->pageURI('+1'); ?>'">
					<?php } ?>
					<?php echo BUTTON_NEXT; ?>
					</button>
				<?php } ?>
			</div>
		</form>
		</div>

	</div>
	</div>
	<div class="footerbar">
		<?php echo INSTALL_COPYRIGHT; ?>
	</div>
</div>
</body>
</html>
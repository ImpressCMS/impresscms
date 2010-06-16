<?php
defined( 'ICMS_ROOT_PATH' ) or die();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo _XOOPS_UPGRADE; ?></title>
<meta http-equiv="Content-Type"
	content="text/html; charset=<?php echo _UPGRADE_CHARSET ?>" />
<script type="text/javascript" src="../libraries/prototype/prototype.js"></script>
<script type="text/javascript">
	function showHideHelp( butt ) {
		butt.className = ( butt.className == 'on' ) ? 'off': 'on';
		document.body.className = ( butt.className == 'on' ) ? 'show-help': '';
	}
	</script>
<link rel="stylesheet" type="text/css" media="all"
	href="<?php echo 'style'.(( defined('_ADM_USE_RTL') && _ADM_USE_RTL )?'_rtl':'').'.css' ?>" />
</head>
<body>
<div id="xo-banner"><img src="img/logo.png" alt="ImpressCMS" /></div>
<div id="xo-content">
<h1><?php echo _XOOPS_UPGRADE; ?></h1>

<?php echo $content; ?></div>
</body>
</html>

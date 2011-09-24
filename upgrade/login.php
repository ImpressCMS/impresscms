<?php
	defined( 'ICMS_ROOT_PATH' ) or die();
?>
<h2><?php echo _USER_LOGIN; ?></h2>

<form action="<?php echo XOOPS_URL; ?>/user.php?op=login" method="post">
		<input type="hidden" name="xoops_redirect" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
		<div class="xo-formfield required">
			<label><?php echo _USERNAME; ?></label>
			<input type="text" name="uname" size="21" maxlength="25" value="" />
		</div>
		<div class="xo-formfield required">
			<label><?php echo _PASSWORD; ?></label>

			<input type="password" name="pass" size="21" maxlength="32" />
		</div>
		<div class="xo-formbuttons">
			<button type="submit"><?php echo _LOGIN; ?></button>
		</div>
</form>
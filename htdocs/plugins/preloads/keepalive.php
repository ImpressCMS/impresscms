<?php
// preload/keepalive.php

defined("ICMS_ROOT_PATH") || die("ImpressCMS root path not defined");

class IcmsPreloadKeepalive extends icms_preload_Item
{
	public function eventBeforeFooter()
	{
		global $xoTheme;
		/* ------------------------------------------------------------------
		 *  1️⃣  Make sure a user object exists and is not a guest.
		 * ------------------------------------------------------------------*/
		// The core may still be booting, so icms::$user can be null.
		// We guard against that before calling isGuest().
		if (
			!isset(icms::$user) || // no user object yet
			!is_object(icms::$user) || // defensive – just in case
			icms::$user->isGuest() // guest users are ignored
		) {
			return;
		}

		/* ------------------------------------------------------------------
		 *  2️⃣  Register the external script.
		 * ------------------------------------------------------------------*/
		// No inline JS – just enqueue the file.
		$xoTheme->addScript(ICMS_URL . "/assets/js/keepalive.js");
	}
}

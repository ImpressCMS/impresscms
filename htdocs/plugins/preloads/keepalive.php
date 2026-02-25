<?php
// preload/keepalive.php

defined("ICMS_ROOT_PATH") || die("ImpressCMS root path not defined");

class IcmsPreloadKeepalive extends icms_preload_Item
{
	public function eventBeforeFooter()
	{
		global $xoTheme;
		/* ------------------------------------------------------------------
		 *  Make sure a user object exists and is not a guest.
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
		 * Register the external script, attaching a unique id
		 * and the keep‑alive endpoint as a data attribute.
		*/
		$keepaliveUrl = ICMS_URL . "/keepalive.php";

		$xoTheme->addScript(
			"assets/js/keepalive.js",
			[
				"id" => "keepalive-script",
				"data-keepalive-url" => $keepaliveUrl,
			],
			"", // no inline content
			"module",
			0, // default weight
		);
	}
}

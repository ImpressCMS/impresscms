<?php
// plugins/preloads/keepalive.php

defined("ICMS_ROOT_PATH") || die("ImpressCMS root path not defined");

class IcmsPreloadKeepalive extends icms_preload_Item
{
	public function eventBeforeFooter(): void
	{
		global $xoTheme;

		if (!is_object(icms::$user)) {
			return;
		}

		if (icms::$user->isGuest()) {
			return;
		}

		if (!is_object($xoTheme)) {
			return;
		}

		$xoTheme->addScript(
			ICMS_URL . "/assets/js/keepalive.js",
			[
				"id" => "keepalive-script",
				"data-keepalive-url" => ICMS_URL . "/keepalive.php",
			]
		);
	}

	public function eventAdminHeader(): void
	{
		$this->eventBeforeFooter();
	}
}

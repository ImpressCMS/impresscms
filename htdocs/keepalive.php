<?php
// htdocs/keepalive.php

//defined("ICMS_ROOT_PATH") or die("Forbidden");

// --- Bootstrap ImpressCMS ---------------------------------
require_once __DIR__ . "/mainfile.php"; // loads config, DB, session, etc.

// --- Optional: make sure the user is logged in ----------------
if (icms::$user->isGuest()) {
	http_response_code(403);
	echo json_encode(["error" => "Not authenticated"]);
	exit();
}

// --- Send the keep‑alive response ----------------------------
header("Content-Type: application/json");
echo json_encode(["status" => "ok"]);
exit(); // Stop here – prevents any debug or other output

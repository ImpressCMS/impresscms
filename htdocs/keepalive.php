<?php
// htdocs/keepalive.php

// Bootstrap ImpressCMS so the session and user object are available
require_once __DIR__ . "/mainfile.php";

/* ----- 1 Ensure icms::$user is set and not null ------------- */
if (!isset(icms::$user) || !is_object(icms::$user)) {
	http_response_code(403);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Not authenticated"]);
	exit();
}

// Reject guests – they don’t need a keep‑alive
if (icms::$user->isGuest()) {
	http_response_code(403);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Not authenticated"]);
	exit();
}

/* ----- 2  Send explicit no‑cache headers ------------- */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* ----- 3  Return the stable JSON payload ------------- */
header("Content-Type: application/json");
echo json_encode(["status" => "ok"]);
exit();

<?php
// htdocs/keepalive.php

// Bootstrap ImpressCMS
require_once __DIR__ . "/mainfile.php";

/* GET requests only */
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	http_response_code(405);
	header("Allow: GET");
	header("Content-Type: application/json");
	echo json_encode(["error" => "Method not allowed"]);
	exit();
}

/* X-Requested-With header check */
if (xoops_getenv('HTTP_X_REQUESTED_WITH') !== 'XMLHttpRequest') {
	http_response_code(400);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Invalid request"]);
	exit();
}

/* Referer validation */
$_keepaliveReferer = xoops_getenv('HTTP_REFERER');
if ($_keepaliveReferer !== '' && strpos($_keepaliveReferer, ICMS_URL) !== 0) {
	http_response_code(403);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Invalid request"]);
	exit();
}
unset($_keepaliveReferer);

/* Authenticated user only */
if (!is_object(icms::$user) || icms::$user->isGuest()) {
	http_response_code(403);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Not authenticated"]);
	exit();
}

/* Session rate limit */
define('KEEPALIVE_MIN_INTERVAL', 60);
if (
	isset($_SESSION['keepalive_last']) &&
	(time() - (int) $_SESSION['keepalive_last']) < KEEPALIVE_MIN_INTERVAL
) {
	$_keepaliveRetryAfter = KEEPALIVE_MIN_INTERVAL - (time() - (int) $_SESSION['keepalive_last']);
	http_response_code(429);
	header("Content-Type: application/json");
	header("Retry-After: " . $_keepaliveRetryAfter);
	echo json_encode(["error" => "Too many requests"]);
	exit();
}
$_SESSION['keepalive_last'] = time();

/* Response headers */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
header("X-Content-Type-Options: nosniff");

/* JSON response */
header("Content-Type: application/json");
echo json_encode(["status" => "ok"]);
exit();

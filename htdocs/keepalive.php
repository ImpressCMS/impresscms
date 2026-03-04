<?php
// htdocs/keepalive.php

// Bootstrap ImpressCMS so the session and user object are available
require_once __DIR__ . "/mainfile.php";

/* ----- 1  Restrict to GET requests only ---------------------------
 * POST, PUT, DELETE, etc. have no purpose here and a simple
 * cross-origin HTML form can issue a POST without a CORS preflight.
 * ------------------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	http_response_code(405);
	header("Allow: GET");
	header("Content-Type: application/json");
	echo json_encode(["error" => "Method not allowed"]);
	exit();
}

/* ----- 2  Enforce XHR-only access ---------------------------------
 * X-Requested-With is a non-simple CORS header.  A cross-origin page
 * must pass an OPTIONS preflight to include it; since we do not
 * advertise Access-Control-Allow-Headers, the preflight is refused
 * and the actual request is never sent by the browser.  Checking the
 * header server-side makes this protection explicit (OWASP "Custom
 * Request Headers" CSRF pattern) and independent of browser CORS.
 * ------------------------------------------------------------------ */
if (xoops_getenv('HTTP_X_REQUESTED_WITH') !== 'XMLHttpRequest') {
	http_response_code(400);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Invalid request"]);
	exit();
}

/* ----- 3  Validate referer when present ---------------------------
 * We use icms::$security->checkReferer() as the reference, but
 * intentionally allow an empty referer to avoid blocking privacy-
 * oriented browsers or strict Referrer-Policy configurations.
 * We only reject when a referer IS present but comes from a
 * different origin.
 * ------------------------------------------------------------------ */
$_keepaliveReferer = xoops_getenv('HTTP_REFERER');
if ($_keepaliveReferer !== '' && strpos($_keepaliveReferer, ICMS_URL) !== 0) {
	http_response_code(403);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Invalid request"]);
	exit();
}
unset($_keepaliveReferer);

/* ----- 4  Ensure icms::$user is set and not a guest -------------- */
if (!is_object(icms::$user) || icms::$user->isGuest()) {
	http_response_code(403);
	header("Content-Type: application/json");
	echo json_encode(["error" => "Not authenticated"]);
	exit();
}

/* ----- 5  Session-based rate limiting -----------------------------
 * Require at least 60 seconds between keepalive calls from the same
 * session.  This prevents a stolen-cookie holder from artificially
 * preventing session expiry and limits DB session-table write load.
 * ------------------------------------------------------------------ */
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

/* ----- 6  Send security and no-cache headers --------------------- */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
header("X-Content-Type-Options: nosniff");

/* ----- 7  Return the stable JSON payload ------------------------- */
header("Content-Type: application/json");
echo json_encode(["status" => "ok"]);
exit();

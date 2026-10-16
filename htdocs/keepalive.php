<?php
// htdocs/keepalive.php

define('KEEPALIVE_MIN_INTERVAL', 60);

/**
 * @param int $status
 * @param array<string, string> $body
 * @param array<int, string> $headers
 */
function keepaliveRespond(int $status, array $body, array $headers = []): void
{
	http_response_code($status);

	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Pragma: no-cache");
	header("Expires: 0");
	header("X-Content-Type-Options: nosniff");
	header("Content-Type: application/json");

	foreach ($headers as $header) {
		header($header);
	}

	echo json_encode($body);
	exit();
}

/**
 * Rejected requests must not extend the session, so the session is discarded
 * without being written back (which would bump sess_updated).
 *
 * @param int $status
 * @param string $error
 * @param array<int, string> $headers
 */
function keepaliveReject(int $status, string $error, array $headers = []): void
{
	if (session_status() === PHP_SESSION_ACTIVE) {
		session_abort();
	}

	keepaliveRespond($status, ["error" => $error], $headers);
}

function keepaliveRefererIsValid(string $referer): bool
{
	if ($referer === '') {
		return true;
	}

	$refererHost = parse_url($referer, PHP_URL_HOST);
	if (!is_string($refererHost)) {
		return false;
	}

	$siteHost = parse_url(ICMS_URL, PHP_URL_HOST);
	if (!is_string($siteHost)) {
		return false;
	}

	return strcasecmp($refererHost, $siteHost) === 0;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	keepaliveReject(405, "Method not allowed", ["Allow: GET"]);
}

if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'XMLHttpRequest') {
	keepaliveReject(400, "Invalid request");
}

require_once __DIR__ . "/mainfile.php";

if (empty($icmsConfig['keepalive_enable'])) {
	keepaliveReject(403, "Keepalive disabled");
}

if (!keepaliveRefererIsValid(xoops_getenv('HTTP_REFERER'))) {
	keepaliveReject(403, "Invalid request");
}

if (!is_object(icms::$user)) {
	keepaliveReject(403, "Not authenticated");
}

if (icms::$user->isGuest()) {
	keepaliveReject(403, "Not authenticated");
}

$keepaliveElapsed = time() - (int) ($_SESSION['keepalive_last'] ?? 0);
if ($keepaliveElapsed < KEEPALIVE_MIN_INTERVAL) {
	$keepaliveRetryAfter = KEEPALIVE_MIN_INTERVAL - $keepaliveElapsed;
	keepaliveReject(429, "Too many requests", ["Retry-After: {$keepaliveRetryAfter}"]);
}

$_SESSION['keepalive_last'] = time();

keepaliveRespond(200, ["status" => "ok"]);

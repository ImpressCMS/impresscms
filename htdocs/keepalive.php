<?php
// htdocs/keepalive.php

// Bootstrap ImpressCMS
require_once __DIR__ . "/mainfile.php";

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
	keepaliveRespond(405, ["error" => "Method not allowed"], ["Allow: GET"]);
}

if (xoops_getenv('HTTP_X_REQUESTED_WITH') !== 'XMLHttpRequest') {
	keepaliveRespond(400, ["error" => "Invalid request"]);
}

if (!keepaliveRefererIsValid(xoops_getenv('HTTP_REFERER'))) {
	keepaliveRespond(403, ["error" => "Invalid request"]);
}

if (!is_object(icms::$user)) {
	keepaliveRespond(403, ["error" => "Not authenticated"]);
}

if (icms::$user->isGuest()) {
	keepaliveRespond(403, ["error" => "Not authenticated"]);
}

$keepaliveElapsed = time() - (int) ($_SESSION['keepalive_last'] ?? 0);
if ($keepaliveElapsed < KEEPALIVE_MIN_INTERVAL) {
	$keepaliveRetryAfter = KEEPALIVE_MIN_INTERVAL - $keepaliveElapsed;
	keepaliveRespond(429, ["error" => "Too many requests"], ["Retry-After: {$keepaliveRetryAfter}"]);
}

$_SESSION['keepalive_last'] = time();

keepaliveRespond(200, ["status" => "ok"]);

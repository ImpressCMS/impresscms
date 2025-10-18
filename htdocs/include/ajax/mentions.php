<?php
declare(strict_types=1);

// ImpressCMS AJAX endpoint for CKEditor 4 Mentions plugin
// Returns a JSON array of users: [{ id, name, username, avatar }]
require_once dirname(__DIR__, 2) . '/mainfile.php';

// Helpers
function jsonOut(array $data, int $status = 200): void {
	http_response_code($status);
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	exit;
}

// CSRF validation (expects XOOPS_TOKEN_REQUEST in GET/POST)
if (!icms::$security->validateToken(false, false)) {
	jsonOut(['error' => 'Invalid CSRF token'], 403);
}

// Only allow GET
if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
	jsonOut(['error' => 'Method not allowed'], 405);
}

// Read and sanitize query params
$q = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
$limit = isset($_GET['limit']) ? max(1, min(50, (int) $_GET['limit'])) : 50; // cap at 50

// Early return on empty query
if ($q === '') {
	jsonOut([]);
}

// Use IPF to fetch users
$userHandler = icms::handler('icms_member_user');
$criteria = new icms_db_criteria_Compo();
$criteria->add(new icms_db_criteria_Item('uname', '%' . $q . '%', 'LIKE'), 'OR');
$criteria->add(new icms_db_criteria_Item('level', 0, '>'));
$criteria->setSort('uname');
$criteria->setOrder('ASC');
$criteria->setLimit($limit);

try {
	$users = $userHandler->getObjects($criteria, true);
} catch (Throwable $e) {
	jsonOut(['error' => 'Database query failed'], 500);
}

// Config needed to determine avatar URL
$allowGravatar = (bool) ($icmsConfigUser['avatar_allow_gravatar'] ?? false);
$avatarSize = (int) ($icmsConfigUser['avatar_width'] ?? 80);

$results = [];
foreach ($users as $user) {
	$uid = (int) $user->getVar('uid');
	$uname = (string) $user->getVar('uname');
	$name = (string) $user->getVar('name');
	$email = strtolower(trim((string) $user->getVar('email', 'E')));
	$avatarFile = (string) $user->getVar('user_avatar');

	// Display name: "Full Name (username)" or just username if name is empty
	$displayName = ($name !== '') ? sprintf('%s (%s)', $name, $uname) : $uname;

	// Resolve avatar URL
	$avatarUrl = ICMS_UPLOAD_URL . '/' . ($avatarFile !== '' ? $avatarFile : 'blank.gif');

	// Fallback to gravatar if allowed and no custom avatar present
	if ($allowGravatar && ($avatarFile === '' || $avatarFile === 'blank.gif' || !is_file(ICMS_UPLOAD_PATH . '/' . $avatarFile))) {
		$avatarUrl = icms::$user->gravatar($email, $avatarSize);
	}

	$results[] = [
		'id' => $uid,
		'name' => $displayName,
		'username' => $uname,
		'avatar' => $avatarUrl,
	];
}

jsonOut($results);

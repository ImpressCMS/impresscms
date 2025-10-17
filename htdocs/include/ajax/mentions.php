<?php
declare(strict_types=1);

// ImpressCMS AJAX endpoint for CKEditor 4 Mentions plugin
// Returns a JSON array of users: [{ id, name, username, avatar }]

// Bootstrap ImpressCMS
require_once dirname(__DIR__, 2) . '/mainfile.php';

// Helpers
function jsonOut($data, int $status = 200): void {
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

// Early return on empty query (mentions plugin may call with empty query after marker)
if ($q === '') {
    jsonOut([]);
}

// Build search (active users only)
try {
    /** @var PDO $pdo */
    $pdo = icms::$db; // PDO connection

    $sql = 'SELECT `uid`, `uname`, `name`, `user_avatar`, `email` FROM `' . str_replace('`', '', icms::$xoopsDB->prefix('users')) . '`
            WHERE (`uname` LIKE :q OR `name` LIKE :q) AND `level` > 0
            ORDER BY `uname` ASC
            LIMIT :lim';

    $stmt = $pdo->prepare($sql);
    $like = '%' . $q . '%';
    $stmt->bindValue(':q', $like, PDO::PARAM_STR);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();
} catch (Throwable $e) {
    jsonOut(['error' => 'Database query failed'], 500);
}

// Config needed to determine avatar URL
$allowGravatar = (bool) ($GLOBALS['icmsConfigUser']['avatar_allow_gravatar'] ?? false);
$avatarSize    = (int) ($GLOBALS['icmsConfigUser']['avatar_width'] ?? 80);

$results = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $uid   = (int) $row['uid'];
    $uname = (string) $row['uname'];
    $name  = (string) ($row['name'] ?? '');

    // Display name: "Full Name (username)" or just username if name is empty
    $displayName = $name !== '' ? sprintf('%s (%s)', $name, $uname) : $uname;

    // Resolve avatar URL
    $avatarFile = (string) ($row['user_avatar'] ?? '');
    $avatarUrl  = ICMS_UPLOAD_URL . '/' . ($avatarFile !== '' ? $avatarFile : 'blank.gif');

    // Fallback to gravatar if allowed and no custom avatar present
    if ($allowGravatar) {
        $hasLocal = ($avatarFile !== '' && $avatarFile !== 'blank.gif' && is_file(ICMS_UPLOAD_PATH . '/' . $avatarFile));
        if (!$hasLocal) {
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            $hash  = md5($email);
            $avatarUrl = icms::$urls['http'] . 'www.gravatar.com/avatar/' . $hash . '?d=identicon&s=' . $avatarSize;
        }
    }

    $results[] = [
        'id'       => $uid,
        'name'     => $displayName,
        'username' => $uname,
        'avatar'   => $avatarUrl,
    ];
}

jsonOut($results);


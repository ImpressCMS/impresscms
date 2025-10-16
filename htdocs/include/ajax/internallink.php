<?php
declare(strict_types=1);

use function htmlspecialchars as h;

require_once dirname(__DIR__, 2) . '/mainfile.php';

header('Content-Type: application/json; charset=utf-8');

function jsonOut(array $payload, int $code = 200): void {
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!is_object(icms::$user)) {
    jsonOut(['error' => 'Authentication required'], 401);
}

// CSRF token validation
if (!empty($_REQUEST['token'])) {
    if (!icms::$security->validateToken($_REQUEST['token'], false)) {
        jsonOut(['error' => 'Invalid token'], 403);
    }
}

$action = isset($_GET['action']) ? (string) $_GET['action'] : '';

if ($action === 'modules') {
    $moduleHandler = icms::handler('icms_module');
    // Only installed and active modules with IPF object_items
    $modules = $moduleHandler->getObjects(null, true);
    $out = [];
    foreach ($modules as $mid => $module) {
        if (!$module->getVar('isactive')) { continue; }
        $modinfoLoaded = $moduleHandler->loadInfo($module->getVar('dirname'), false);
        if (!$modinfoLoaded) { continue; }
        $info = $module->modinfo;
        if (isset($info['object_items']) && is_array($info['object_items']) && count($info['object_items']) > 0) {
            // Permission: module_read
            $gperm = icms::handler('icms_member_groupperm');
            $groups = icms::$user->getGroups();
            if (!$gperm->checkRight('module_read', (int) $mid, $groups)) { continue; }
            $items = [];
            foreach ($info['object_items'] as $itemName) {
                $items[] = (string) $itemName;
            }
            $out[] = [
                'dirname' => (string) $module->getVar('dirname'),
                'name' => (string) $module->getVar('name'),
                'items' => $items
            ];
        }
    }
    jsonOut(['modules' => $out]);
}

if ($action === 'search') {
    $moduleDir = isset($_GET['module']) ? preg_replace('/[^a-zA-Z0-9_\-]/', '', (string) $_GET['module']) : '';
    $qRaw = isset($_GET['q']) ? (string) $_GET['q'] : '';
    $q = trim($qRaw);
    $itemsRaw = isset($_GET['items']) ? (string) $_GET['items'] : '';
    $limit = isset($_GET['limit']) ? max(1, min(20, (int) $_GET['limit'])) : 10;

    if ($moduleDir === '' || strlen($q) < 3) {
        jsonOut(['results' => []]);
    }

    // Parse items filter (comma-separated)
    $itemsFilter = [];
    if ($itemsRaw !== '') {
        $itemsFilter = array_filter(array_map('trim', explode(',', $itemsRaw)));
    }

    // Module and permission checks
    $moduleHandler = icms::handler('icms_module');
    $module = $moduleHandler->getByDirname($moduleDir, true);
    if (!$module || !$module->getVar('isactive')) {
        jsonOut(['error' => 'Module not found'], 404);
    }
    $gperm = icms::handler('icms_member_groupperm');
    $groups = icms::$user->getGroups();
    if (!$gperm->checkRight('module_read', (int) $module->getVar('mid'), $groups)) {
        jsonOut(['error' => 'Not allowed'], 403);
    }

    // Load object_items
    $loaded = $moduleHandler->loadInfo($moduleDir, false);
    if (!$loaded) {
        jsonOut(['results' => []]);
    }
    $info = $module->modinfo;
    $items = isset($info['object_items']) && is_array($info['object_items']) ? $info['object_items'] : [];
    if (!$items) {
        jsonOut(['results' => []]);
    }

    $pdo = icms::$xoopsDB instanceof icms_db_legacy_PdoDatabase ? icms::$xoopsDB->getConnection() : null;
    if (!$pdo) {
        jsonOut(['error' => 'Database not available'], 500);
    }

    $results = [];
    $like = '%' . $q . '%';

    foreach ($items as $itemName) {
        // Skip if items filter is set and this item is not in it
        if (!empty($itemsFilter) && !in_array($itemName, $itemsFilter)) {
            continue;
        }

        $handler = icms_getModuleHandler($itemName, $moduleDir, null, true);
        if (!$handler) { continue; }
        // Identify key and title fields and table
        $table = $handler->table; // already prefixed
        $idField = $handler->keyName;
        $titleField = $handler->identifierName;
        if (!$table || !$idField || !$titleField) { continue; }

        // Build URL parts
        $baseUrl = $handler->_moduleUrl . $handler->_page . '?' . $idField . '=';

        // Prepare and execute
        $sql = sprintf(
            'SELECT `%s` AS id, `%s` AS title FROM `%s` WHERE `%s` LIKE :q ORDER BY `%s` ASC LIMIT :lim',
            str_replace('`','', $idField),
            str_replace('`','', $titleField),
            str_replace('`','', $table),
            str_replace('`','', $titleField),
            str_replace('`','', $titleField)
        );
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':q', $like, \PDO::PARAM_STR);
            $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
            $stmt->execute();
        } catch (\Throwable $e) {
            continue;
        }
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if (!isset($row['id'], $row['title'])) { continue; }
            $title = (string) $row['title'];
            if ($title === '') { continue; }
            $url = $baseUrl . urlencode((string)$row['id']);
            $results[] = [
                'title' => $title,
                'url' => $url,
                'module' => (string) $moduleDir,
                'item' => (string) $itemName
            ];
            if (count($results) >= $limit) { break; }
        }
        if (count($results) >= $limit) { break; }
    }

    jsonOut(['results' => $results]);
}

jsonOut(['error' => 'Invalid action'], 400);


<?php
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/mainfile.php';

icms::$logger->disableLogger();
header('Content-Type: application/json; charset=utf-8');

function jsonOut(array $payload, int $code = 200): void {
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Run a title search on one IPF object handler and return result rows.
 *
 * @param object $handler  IPF persistable handler
 * @param \PDO   $pdo
 * @param string $q        search string (wildcards are escaped)
 * @param int    $limit
 * @return array[] rows with keys id, title
 */
function searchHandler($handler, \PDO $pdo, string $q, int $limit): array {
    $table = $handler->table;
    $idField = $handler->keyName;
    $titleField = $handler->identifierName;
    // composite keys are not linkable through a single id parameter
    if (!is_string($table) || !is_string($idField) || !is_string($titleField)
        || $table === '' || $idField === '' || $titleField === '') {
        return [];
    }
    $like = '%' . addcslashes($q, '\%_') . '%';
    $id = str_replace('`', '', $idField);
    $title = str_replace('`', '', $titleField);
    $sql = sprintf(
        'SELECT `%s` AS id, `%s` AS title FROM `%s` WHERE `%s` LIKE :q ORDER BY `%s` ASC LIMIT :lim',
        $id, $title, str_replace('`', '', $table), $title, $title
    );
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':q', $like, \PDO::PARAM_STR);
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
    } catch (\Throwable $e) {
        return [];
    }
    $rows = [];
    while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        if (!isset($row['id'], $row['title']) || (string) $row['title'] === '') { continue; }
        $rows[] = $row;
    }
    return $rows;
}

if (!is_object(icms::$user)) {
    jsonOut(['error' => 'Authentication required'], 401);
}

// CSRF token validation
$token = isset($_GET['token']) ? (string) $_GET['token'] : '';
if ($token === '' || !icms::$security->validateToken($token, false)) {
    jsonOut(['error' => 'Invalid token'], 403);
}

$action = isset($_GET['action']) ? (string) $_GET['action'] : '';

if ($action === 'modules') {
    $moduleHandler = icms::handler('icms_module');
    // Only installed and active modules with IPF object_items
    $modules = $moduleHandler->getObjects(null, true);
    $out = [];
    foreach ($modules as $mid => $module) {
        if (!$module->getVar('isactive')) { continue; }
        $dirname = (string) $module->getVar('dirname');
        if ($dirname === 'system') { continue; }

        $modinfoLoaded = $module->loadInfo((string) $module->getVar('dirname'), false);
        if (!$modinfoLoaded) { continue; }
        $info = $module->getInfo();
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

    if ($moduleDir === '' || mb_strlen($q) < 3) {
        jsonOut(['results' => []]);
    }

    // Parse items filter (comma-separated)
    $itemsFilter = [];
    if ($itemsRaw !== '') {
        $itemsFilter = array_filter(array_map('trim', explode(',', $itemsRaw)));
    }

    // Special case: search across all modules
    if ($moduleDir === '__all__') {
        $moduleHandler = icms::handler('icms_module');
        $modules = $moduleHandler->getObjects(null, true);
        $gperm = icms::handler('icms_member_groupperm');
        $groups = icms::$user->getGroups();

        $pdo = icms::$xoopsDB instanceof icms_db_legacy_PdoDatabase ? icms::$xoopsDB->getConnection() : null;
        if (!$pdo) {
            jsonOut(['error' => 'Database not available'], 500);
        }

        $results = [];

        foreach ($modules as $mid => $modObj) {
            if (!$modObj->getVar('isactive')) { continue; }
            $dirname = (string) $modObj->getVar('dirname');
            if ($dirname === 'system') { continue; }
            if (!$gperm->checkRight('module_read', (int) $mid, $groups)) { continue; }

            $loaded = $modObj->loadInfo($dirname, false);
            if (!$loaded) { continue; }
            $info = $modObj->getInfo();
            $items = isset($info['object_items']) && is_array($info['object_items']) ? $info['object_items'] : [];
            if (!$items) { continue; }

            foreach ($items as $itemName) {
                $handler = icms_getModuleHandler($itemName, $dirname, null, true);
                if (!$handler || !is_string($handler->keyName)) { continue; }
                $baseUrl = $handler->_moduleUrl . $handler->_page . '?' . $handler->keyName . '=';
                foreach (searchHandler($handler, $pdo, $q, $limit) as $row) {
                    $results[] = [
                        'title' => (string) $row['title'],
                        'url' => $baseUrl . urlencode((string) $row['id']),
                        'module' => $dirname,
                        'moduleName' => (string) $modObj->getVar('name'),
                        'item' => (string) $itemName
                    ];
                    if (count($results) >= $limit) { break 2; }
                }
            }
        }

        jsonOut(['results' => $results]);
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
    $loaded = $module->loadInfo($moduleDir, false);
    if (!$loaded) {
        jsonOut(['results' => []]);
    }
    $info = $module->getInfo();
    $items = isset($info['object_items']) && is_array($info['object_items']) ? $info['object_items'] : [];
    if (!$items) {
        jsonOut(['results' => []]);
    }

    $pdo = icms::$xoopsDB instanceof icms_db_legacy_PdoDatabase ? icms::$xoopsDB->getConnection() : null;
    if (!$pdo) {
        jsonOut(['error' => 'Database not available'], 500);
    }

    $results = [];

    foreach ($items as $itemName) {
        // Skip if items filter is set and this item is not in it
        if (!empty($itemsFilter) && !in_array($itemName, $itemsFilter)) {
            continue;
        }

        $handler = icms_getModuleHandler($itemName, $moduleDir, null, true);
        if (!$handler || !is_string($handler->keyName)) { continue; }
        $baseUrl = $handler->_moduleUrl . $handler->_page . '?' . $handler->keyName . '=';
        foreach (searchHandler($handler, $pdo, $q, $limit) as $row) {
            $results[] = [
                'title' => (string) $row['title'],
                'url' => $baseUrl . urlencode((string) $row['id']),
                'module' => $moduleDir,
                'moduleName' => (string) $module->getVar('name'),
                'item' => (string) $itemName
            ];
            if (count($results) >= $limit) { break; }
        }
        if (count($results) >= $limit) { break; }
    }

    jsonOut(['results' => $results]);
}

jsonOut(['error' => 'Invalid action'], 400);


<?php
// Reports the environment the bridge gave this script.
$root = rtrim(str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__, 2)), '/');
$relative = static fn (string $path): string => substr(rtrim(str_replace(DIRECTORY_SEPARATOR, '/', $path), '/'), strlen($root)) ?: '/';
header('Content-Type: application/json');
echo json_encode([
    'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'],
    'PHP_SELF' => $_SERVER['PHP_SELF'],
    'REQUEST_URI' => $_SERVER['REQUEST_URI'],
    'QUERY_STRING' => $_SERVER['QUERY_STRING'],
    'SCRIPT_FILENAME' => $relative($_SERVER['SCRIPT_FILENAME']),
    'DOCUMENT_ROOT' => $relative($_SERVER['DOCUMENT_ROOT']),
    'cwd' => $relative(getcwd()),
    'fct' => $_GET['fct'] ?? null,
    'post' => $_POST,
    'request' => $_REQUEST,
]);

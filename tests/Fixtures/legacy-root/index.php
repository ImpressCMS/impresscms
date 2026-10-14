<?php
// Legacy front controller of the bridge test fixture.
header('Content-Type: application/json');
echo json_encode(['front_controller' => true, 'script' => $_SERVER['SCRIPT_NAME'], 'uri' => $_SERVER['REQUEST_URI']]);

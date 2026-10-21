<?php
// A top-level variable read through `global` by a function in another file.
$pageTitle = 'set at the top level of page.php';
require __DIR__ . '/functions.php';
echo pageTitle();

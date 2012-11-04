<?php
/*
 * @file: boiler.php
 * A simple script that illustrates the future possibilities with ICMS
 * you can use this script to concatenate scripts and css requests
 * example: http:example.com/boiler.php?type=css&f=media/css/style.css,media/css/another.css
 *
 * Usage: place it in your ICMS_ROOT directory (next to mainfile.php) - build urls per content type.
 */
require('mainfile.php');

$base = ICMS_URL . '/themes/boiler/';
$type = htmlspecialchars($_GET['type']);
$f = htmlspecialchars($_GET['f']);
$files = explode(',', $f);

header("X-Content-Type-Options: nosniff");
if($type == 'script') {
  header('Content-type: application/javascript');
  foreach ($files as $file) {
    echo file_get_contents($base . $file);
  }
} else if($type == 'css') {
  header("Content-Type: text/css");
  foreach ($files as $file) {
    readfile($base . $file);
  }
} else {
  return;
}
?>
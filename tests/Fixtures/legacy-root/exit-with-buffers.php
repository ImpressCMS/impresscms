<?php
ob_start();
echo 'buffered ';
ob_start();
echo 'then exit';
exit;

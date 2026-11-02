<?php
ob_start();
echo 'outer ';
ob_start();
echo 'inner ';
ob_start();
echo 'innermost';
// none of the buffers is closed

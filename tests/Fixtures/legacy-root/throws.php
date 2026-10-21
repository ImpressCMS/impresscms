<?php
// No exception handler of its own: the bridge renders the failure.
echo 'partial output before the failure';
throw new RuntimeException('legacy failure ' . ($_GET['id'] ?? ''));

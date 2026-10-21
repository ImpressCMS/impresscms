<?php
setcookie('first', 'one', ['path' => '/', 'httponly' => true]);
setcookie('second', 'two', ['expires' => 2000000000, 'path' => '/sub', 'samesite' => 'Lax']);
echo 'two cookies';

#!/usr/bin/env php
<?php

$_SERVER['HTTP_HOST'] = null;
try {    
    require "mainfile.php";
} catch (Exception $ex) {
    echo "No mainfile exists. You must install first.";
    exit(1);
}

$controller_handler = icms::handler('icms_controller');
if ($controller_handler->type !== 'command') {
    echo "This file can be runned only from console.";
    exit(2);
}

if ($argc < 2) {
	echo PHP_EOL . 'Usage:' . PHP_EOL . "\t" . $argv[0] . ' COMMAND [PARAMS]' . PHP_EOL;
	echo PHP_EOL . 'Available commands:' . PHP_EOL;
	$modules_handler = icms::handler('icms_module');
	$printed_modules = false;
	foreach ($modules_handler::getActive() as $module) {
		$printed_module_name = false;
		foreach (['command', 'controller'] as $type) {
			$actions = $controller_handler->getAvailable($module, $type);
			if (count($actions) > 0) {
				if ($printed_module_name === false) {
					$printed_module_name = true;
					$printed_modules = true;
				}				
				foreach ($actions as $controller_name => $methods) {
					foreach ($methods as $action => $data) {
						echo "\t" . $module . '/' . $controller_name . '/' . $action;
						foreach ($data['params'] as $pname => $poptions) {
							echo ' ';
							if ($poptions['optional'] === true) {
								echo '[';
							}
							echo "--$pname VALUE";
							if ($poptions['optional'] === true) {
								echo ']';
							}
						}
						echo PHP_EOL;
					}					
				}
			}
		}
	}
	if ($printed_modules === false) {
		echo PHP_EOL . "\t No actions found ;(" . PHP_EOL . PHP_EOL;
	}
	exit(0);
} else {
	list($module, $controller_name, $action) = explode('/', $argv[1], 3);
	$successed = false;
	$args = [];
	if ($argc > 2) {
		unset($argv[0], $argv[1]);
		$arg_n = null;
		while($arg = array_shift($argv)) {
			if (substr($arg, 0, 2) === '--') {
				if ($arg_n !== null) {
					$args[$arg_n] = null;
				}
				if (($o = strpos($arg, '=')) !== false) {
					$args[substr($arg, 2, $o - 2)] = substr($arg, $o  + 1);
					$arg_n = null;
				} else {
					$arg_n = substr($arg, 2);
				}
			} else {
				$args[$arg_n] = $arg;
				$arg_n = null;
			}
		}
		if ($arg_n !== null) {
			$args[$arg_n] = null;
		}
	}
	foreach (['command', 'controller'] as $type) {		
		try {
			$controller_handler->exec(
				$module,
				$type,
				$controller_name,
				$action,
				$args
			);
			$successed = true;
			echo PHP_EOL;
		} catch (Exception $ex) {
			// Try again
		}
		if ($successed === true) {
			break;
		}
	}
	if ($successed === false) {
		echo "ERROR: Command not found." .PHP_EOL;
	    exit(3);
	}
}
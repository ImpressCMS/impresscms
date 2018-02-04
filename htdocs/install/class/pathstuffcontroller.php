<?php

class PathStuffController {
	var $xoopsUrl = '';

	var $validUrl = false;

	var $permErrors = array();

	public function __construct() {
		if (isset( $_SESSION['settings']['URL'] )) {
			$this->xoopsUrl = $_SESSION['settings']['URL'];
		} else {
			$path = $GLOBALS['wizard']->baseLocation();
			$this->xoopsUrl = substr( $path, 0, strrpos( $path, '/' ) );
		}
	}

	function execute() {
		$this->readRequest();
		$valid = $this->validate();
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$_SESSION['settings']['URL'] = $this->xoopsUrl;
			if ($valid) {
				$GLOBALS['wizard']->redirectToPage( '+1' );
			} else {
				$GLOBALS['wizard']->redirectToPage( '+0' );
			}
		}
	}

	function readRequest() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$request = $_POST;
			if (isset( $request['URL'] )) {
				if (substr( $request['URL'], -1 ) == '/') {
					$request['URL'] = substr( $request['URL'], 0, -1 );
				}
				$this->xoopsUrl = $request['URL'];
			}
		}
	}

	function validate() {
		$this->validUrl = !empty($this->xoopsUrl);
		return $this->checkPermissions() && ( $this->validUrl && empty( $this->permErrors ) );
	}

	function checkPermissions() {
		$short_path = mb_substr(ICMS_PUBLIC_PATH, mb_strlen(ICMS_ROOT_PATH) + 1);
		$paths = array(
			'.env',
			$short_path . DIRECTORY_SEPARATOR . 'uploads',
			'modules',
			'cache',
			'cache' .  DIRECTORY_SEPARATOR . 'htmlpurifier',
			'cache' .  DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . 'CSS',
			'cache' .  DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . 'HTML',
			'cache' .  DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . 'Test',
			'cache' .  DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . 'URI',
			'cache' .  DIRECTORY_SEPARATOR . 'templates',
		);
		$errors = array();
		foreach ( $paths as $path) {
			$errors[$path] = $this->makeWritable( ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . $path );
		}
		if (in_array( false, $errors )) {
			$this->permErrors = $errors;
			return false;
		}
		return true;
	}

	/**
	 * Write-enable the specified file/folder
	 * @param string $path
	 * @param string $group
	 * @param bool $recurse
	 * @return false on failure, method (u-ser,g-roup,w-orld) on success
	 */
	function makeWritable( $path, $group = false, $recurse = false) {
		if (!file_exists( $path )) {
			return false;
		}
		$perm = @is_dir( $path ) ? 6 : 7;
		if (@!is_writable($path)) {
			// First try using owner bit
			@chmod( $path, octdec( '0' . $perm . '00' ) );
			clearstatcache();
			if (!@is_writable( $path ) && $group !== false) {
				// If group has been specified, try using the group bit
				@chgrp( $path, $group );
				@chmod( $path, octdec( '0' . $perm . $perm . '0' ) );
			}
			clearstatcache();
			if (!@is_writable( $path )) {
				@chmod( $path, octdec( '0' . $perm . $perm . $perm ) );
			}
		}
		clearstatcache();
		if (@is_writable( $path )) {
			$info = stat( $path );
			//echo $path . ' : ' . sprintf( '%o', $info['mode'] ) . '....';
			if ($info['mode'] & 0002) {
				return 'w';
			} elseif ($info['mode'] & 0020) {
				return 'g';
			}
			return 'u';
		}
		return false;
	}
	/**
	 * Find the webserved Group ID
	 * @return int
	 */
	function findServerGID() {
		$name = tempnam( '/non-existent/', 'XOOPS' );
		$group = 0;
		if ($name) {
			if (touch( $name )) {
				$group = filegroup( $name );
				unlink( $name );
				return $group;
				//$info = posix_getgrgid( $group );
			}
		}
		return false;
	}
}
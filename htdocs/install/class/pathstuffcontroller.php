<?php

class PathStuffController {
	var $xoopsRootPath = '';
	var $xoopsUrl = '';

	var $validRootPath = false;
	var $validUrl = false;

	var $permErrors = array();

	public function __construct() {
		if (isset( $_SESSION['settings']['ROOT_PATH'] )) {
			$this->xoopsRootPath = $_SESSION['settings']['ROOT_PATH'];
		} else {
			$path = str_replace( "\\", "/", @realpath( '../' ) );
			if (file_exists( "$path/libraries/icms.php" )) {
				$this->xoopsRootPath = $path;
			}
		}
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
			$_SESSION['settings']['ROOT_PATH'] = $this->xoopsRootPath;
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
			/*
			 $request = xo_input_get_args( INPUT_POST, array(
				'ROOT_PATH'	=> FILTER_SANITIZE_STRING,
				'URL'		=> array(
				'filter'	=> FILTER_VALIDATE_URL,
				'flags'		=> FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED,
				),
				) );*/
			if (isset($request['ROOT_PATH'])) {
				$request['ROOT_PATH'] = str_replace( "\\", "/", $request['ROOT_PATH'] );
				if (substr( $request['ROOT_PATH'], -1 ) == '/') {
					$request['ROOT_PATH'] = substr( $request['ROOT_PATH'], 0, -1 );
				}
				$this->xoopsRootPath = $request['ROOT_PATH'];
			}
			if (isset( $request['URL'] )) {
				if (substr( $request['URL'], -1 ) == '/') {
					$request['URL'] = substr( $request['URL'], 0, -1 );
				}
				$this->xoopsUrl = $request['URL'];
			}
		}
	}

	function validate() {
		if ($this->checkRootPath()) {
			$this->checkPermissions();
		}
		$this->validUrl = !empty($this->xoopsUrl);
		return ( $this->validRootPath && $this->validUrl && empty( $this->permErrors ) );
	}

	/**
	 * Check if the specified folder is a valid "XOOPS_ROOT_PATH" value
	 * @return bool
	 */
	function checkRootPath() {
		if (@is_dir( $this->xoopsRootPath ) && @is_readable( $this->xoopsRootPath )) {
			@include_once "$this->xoopsRootPath/include/version.php";
			if (file_exists( "$this->xoopsRootPath/libraries/icms.php" ) && defined('ICMS_VERSION_NAME')) {
				return $this->validRootPath = true;
			}
		}
		return $this->validRootPath = false;
	}

	function checkPermissions() {
		$paths = array( '.env', 'uploads', 'modules', 'templates_c', 'cache' );
		$errors = array();
		foreach ( $paths as $path) {
			$errors[$path] = $this->makeWritable( "$this->xoopsRootPath/$path" );
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
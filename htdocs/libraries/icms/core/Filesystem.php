<?php
/**
 * A static class for file system functions
 *
 * Using a static class instead of a include file with global functions, along with
 * autoloading of classes, reduces the memory usage and only includes files when needed.
 *
 * @category	ICMS
 * @package     Core
 * @subpackage	Filesystem
 * @author		Steve Kenow <skenow@impresscms.org>
 * @copyright	(c) 2007-2008 The ImpressCMS Project - www.impresscms.org
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @version		SVN: $Id: Filesystem.php 11944 2012-08-22 17:33:11Z skenow $
 * @since		1.3
 */

/**
 * Perform filesystem actions
 */
class icms_core_Filesystem
{
	/* Since all the methods are static, there is no __construct necessary	 */

	/**
	 *
	 * Change the permission of a file or folder
	 * Replaces icms_chmod()
	 *
	 * @author	Newbb2	developement team
	 * @param	string	$target	target file or folder
	 * @param	int		$mode	permission
	 * @return	bool	Returns true on success, false on failure
	 */
	public static function chmod($target, $mode = 0777)
	{
		return @chmod($target, $mode);
	}

	/**
	 *
	 * Safely create a folder and any folders in between
	 * Replaces icms_mkdir()
	 *
	 * @param string	$target		path to the folder to be created
	 * @param integer	$mode		permissions to set on the folder. This is affected by umask in effect
	 * @param string	$base		root location for the folder, ICMS_ROOT_PATH or ICMS_TRUST_PATH, for example
	 * @param array		$metachars	Characters to exclude from a valid path name
	 * @return boolean True if folder is created, False if it is not
	 */
	public static function mkdir(
		$target,
		$mode = 0777,
		$base = ICMS_ROOT_PATH,
		$metachars = [],
	) {
		if (is_dir($target)) {
			return true;
		}
		if (!isset($metachars)) {
			$metachars = ["[", "?", '"', ".", "<", ">", "|", " ", ":"];
		}

		$base = preg_replace("/[\\|\/]/", DIRECTORY_SEPARATOR, $base);
		$target = preg_replace("/[\\|\/]/", DIRECTORY_SEPARATOR, $target);
		if ($base !== "") {
			$target = str_ireplace($base . DIRECTORY_SEPARATOR, "", $target);
			$target =
				$base .
				DIRECTORY_SEPARATOR .
				str_replace($metachars, "_", $target);
		} else {
			$target = str_replace($metachars, "_", $target);
		}
		if (mkdir($target, $mode, true)) {
			// create an index.html file in this directory
			if ($fh = @fopen($target . "/index.html", "w")) {
				fwrite($fh, "<script>history.go(-1);</script>");
				@fclose($fh);
			}

			if (substr(decoct(fileperms($target)), 2) != $mode) {
				chmod($target, $mode);
			}
		}
		return is_dir($target);
	}

	/**
	 *
	 * Removes the content of a folder.
	 * Replaces icms_clean_folders()
	 * @todo	Rewrite with SPL Directory Iterators
	 *
	 * @author	Steve Kenow (aka skenow) <skenow@impresscms.org>
	 * @author	modified by Vaughan <vaughan@impresscms.org>
	 * @author	modified by Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
	 * @param	string	$dir	The folder path to cleaned. Must be an array like: array('templates_c' => ICMS_COMPILE_PATH . "/");
	 * @param	bool  $remove_admin_cache	  True to remove admin cache, if required.
	 */
	public static function cleanFolders($dir, $remove_admin_cache = false)
	{
		global $icmsConfig;
		foreach ($dir as $d) {
			$dd = opendir($d);
			while ($file = readdir($dd)) {
				$files_array = $remove_admin_cache
					? $file != "index.html" &&
						$file != "php.ini" &&
						$file != ".htaccess" &&
						$file != ".svn"
					: $file != "index.html" &&
						$file != "php.ini" &&
						$file != ".htaccess" &&
						$file != ".svn" &&
						$file !=
							"adminmenu_" . $icmsConfig["language"] . ".php";
				if (is_file($d . $file) && $files_array) {
					unlink($d . $file);
				}
			}
			closedir($dd);
		}
		return true;
	}

	/**
	 *
	 * Clean up all writable folders
	 * Replaces icms_cleaning_write_folders()
	 *
	 */
	public static function cleanWriteFolders()
	{
		return self::cleanFolders([
			"templates_c" => ICMS_COMPILE_PATH . "/",
			"cache" => ICMS_CACHE_PATH . "/",
		]);
	}

	/**
	 *
	 * Copy a file, or a folder and its contents
	 * Replaces icms_copyr()
	 * @todo	Can be rewritten with SPL Directory Iterators
	 *
	 * @author	Aidan Lister <aidan@php.net>
	 * @param	string	$source	The source
	 * @param	string	$dest	The destination
	 * @return	boolean	Returns true on success, false on failure
	 */
	public static function copyRecursive($source, $dest)
	{
		// Simple copy for a file
		if (is_file($source)) {
			return copy($source, $dest);
		}

		// Make destination directory
		if (!is_dir($dest)) {
			self::mkdir($dest, 0777, "");
		}

		// Loop through the folder
		$dir = dir($source);
		$success = true;
		while (false !== ($entry = $dir->read())) {
			// Skip pointers
			if ($entry === "." || $entry === "..") {
				continue;
			}
			// Deep copy directories
			if (is_dir("$source/$entry") && $dest !== "$source/$entry") {
				if (!self::copyRecursive("$source/$entry", "$dest/$entry")) {
					$success = false;
				}
			} else {
				if (!copy("$source/$entry", "$dest/$entry")) {
					$success = false;
				}
			}
		}
		// Clean up
		$dir->close();
		return $success;
	}

	/**
	 *
	 * Deletes a file
	 * Replaces icms_deleteFile()
	 *
	 * @param string $dirname path of the file
	 * @return	The unlinked dirname
	 */
	public static function deleteFile($dirname)
	{
		// Simple delete for a file
		if (is_file($dirname)) {
			return unlink($dirname);
		}
	}

	/**
	 *
	 * Copy a file, or a folder and its contents from a website to your host
	 * Replaces icms_stream_copy()
	 *
	 * @author	Sina Asghari <stranger@impresscms.org>
	 * @author	nensa at zeec dot biz
	 * @param	string	$src	The source
	 * @param	string 	$dest	  The destination
	 * @return 	boolean	Returns stream_copy_to_stream($src, $dest) on success, false on failure
	 */
	public static function copyStream($src, $dest)
	{
		$len = false;
		if (@ini_get("allow_url_fopen")) {
			$fsrc = fopen($src, "r");
			$fdest = fopen($dest, "w+");
			$len = stream_copy_to_stream($fsrc, $fdest);
			fclose($fsrc);
			fclose($fdest);
		}
		return $len;
	}

	/**
	 *
	 * Recursively delete a directory
	 * Replaces icms_unlinkRecursive()
	 * @todo	Can be rewritten with SPL Directory Iterators
	 *
	 * @param string $dir Directory name
	 * @param bool $deleteRootToo Delete specified top-level directory as well
	 */
	public static function deleteRecursive($dir, $deleteRootToo = true)
	{
		if (!($dh = @opendir($dir))) {
			return;
		}
		while (false !== ($obj = readdir($dh))) {
			if ($obj == "." || $obj == "..") {
				continue;
			}

			if (!@unlink($dir . "/" . $obj)) {
				self::deleteRecursive($dir . "/" . $obj, true);
			}
		}

		closedir($dh);

		if ($deleteRootToo) {
			@rmdir($dir);
		}

		return;
	}

	/**
	 * Writes index file
	 * Replaces xoops_write_index_file() from cp_functions.php
	 *
	 * @param string  $path  path to the file to write
	 * @return bool
	 * @todo use language constants for error messages
	 */
	public static function writeIndexFile($path = "")
	{
		if (empty($path)) {
			return false;
		}
		$path = substr($path, -1) == "/" ? substr($path, 0, -1) : $path;
		$filename = $path . "/index.html";
		if (file_exists($filename)) {
			return true;
		}
		if (!($file = fopen($filename, "w"))) {
			echo "failed open file";
			return false;
		}
		if (fwrite($file, "<script>history.go(-1);</script>") == false) {
			echo "failed write file";
			return false;
		}
		fclose($file);
		return true;
	}

	/**
	 * Create a checksum file for your installation directory
	 * @author	Steve Kenow <skenow@impresscms.org>
	 *
	 */
	public static function generateChecksum()
	{
		$rootdir = preg_replace("#[\|/]#", DIRECTORY_SEPARATOR, ICMS_ROOT_PATH);
		$dir = new RecursiveDirectoryIterator($rootdir);
		$checkfile =
			preg_replace("#[\|/]#", DIRECTORY_SEPARATOR, ICMS_TRUST_PATH) .
			DIRECTORY_SEPARATOR .
			"checkfile.sha1";

		$file = new SplFileObject($checkfile, "w");
		$cache_dir = preg_replace(
			"#[\|/]#",
			DIRECTORY_SEPARATOR,
			ICMS_CACHE_PATH,
		);
		$templates_dir = preg_replace(
			"#[\|/]#",
			DIRECTORY_SEPARATOR,
			ICMS_COMPILE_PATH,
		);
		$newline = "";
		foreach (new RecursiveIteratorIterator($dir) as $name => $item) {
			$itemPath = $item->getPath();
			$itemFilename = $item->getBasename();
			$itemPerms = $item->getPerms();
			/* exclude cache and templates_c directories */
			if ($itemPath != $cache_dir && $itemPath != $templates_dir) {
				$fileHash = sha1_file($name);
				echo _CORE_CHECKSUM_ADDING .
					": " .
					$name .
					_CORE_CHECKSUM_CHECKSUM .
					" : <em>" .
					$fileHash .
					"</em>, " .
					_CORE_CHECKSUM_PERMISSIONS .
					" : " .
					$itemPerms .
					"<br />";
				$file->fwrite(
					$newline . $name . ";" . $fileHash . ";" . $itemPerms,
				);
			}
			$newline = "\n";
		}
		unset($file);
		unset($item);
		unset($dir);
	}

	/**
	 * Validate the current installation directory against an existing checksum file
	 * This reports any changes to your installation directory - added, removed or changed files
	 *
	 * @author	Steve Kenow <skenow@impresscms.org>
	 *
	 */
	public static function validateChecksum()
	{
		$validationFile = new SplFileObject($checkfile);
		if ($validationFile->isReadable()) {
			$currentHash = $currentPerms = [];
			$cache_dir = preg_replace(
				"#[\|/]#",
				DIRECTORY_SEPARATOR,
				ICMS_CACHE_PATH,
			);
			$templates_dir = preg_replace(
				"#[\|/]#",
				DIRECTORY_SEPARATOR,
				ICMS_COMPILE_PATH,
			);
			foreach (new RecursiveIteratorIterator($dir) as $name => $item) {
				$itemPath = $item->getPath();
				$itemFilename = $item->getBasename();
				$itemPerms = $item->getPerms();
				/* exclude cache and templates_c directories */
				if ($itemPath != $cache_dir && $itemPath != $templates_dir) {
					$fileHash = sha1_file($name);
					$currentHash[$name] = $fileHash;
					$currentPerms[$name] = $itemPerms;
				}
			}
			echo _CORE_CHECKSUM_CHECKFILE . $checkfile . "<br />";
			$validHash = $validPerms = [];
			while (!$validationFile->eof()) {
				[
					$filename,
					$checksum,
					$filePermissions,
				] = $validationFile->fgetcsv(";");
				$validHash[$filename] = $checksum;
				$validPerms[$filename] = $filePermissions;
			}
			$hashVariations = array_diff_assoc($validHash, $currentHash); // changed or removed files
			$addedFiles = array_diff_key($currentHash, $validHash);
			$missingFiles = array_diff_key($validHash, $currentHash);
			$permVariations = array_diff_assoc($validPerms, $currentPerms); // changed permissions or removed files
			echo "<br /><strong>" .
				count($hashVariations) .
				_CORE_CHECKSUM_ALTERED_REMOVED .
				"</strong><br />";
			foreach ($hashVariations as $file => $check) {
				echo $file . "<br />";
			}
			echo "<br /><strong>" .
				count($addedFiles) .
				_CORE_CHECKSUM_FILES_ADDED .
				"</strong><br />";
			foreach ($addedFiles as $file => $hash) {
				echo $file . "<br />";
			}
			echo "<br /><strong>" .
				count($missingFiles) .
				_CORE_CHECKSUM_FILES_REMOVED .
				"</strong><br />";
			foreach ($missingFiles as $file => $hash) {
				echo $file . "<br />";
			}
			echo "<br /><strong>" .
				count($permVariations) .
				_CORE_CHECKSUM_PERMISSIONS_ALTERED .
				"</strong><br />";
			foreach ($permVariations as $file => $perms) {
				echo $file . "<br />";
			}
		} else {
			echo _CORE_CHECKSUM_CHECKFILE_UNREADABLE;
		}
		unset($validationFile);
		unset($item);
		unset($dir);
	}

	/**
	 * Gets a list of all directories within a path
	 *
	 * @param	string $dirname A path to a directory
	 * @param	array	$ignore	A list of folders to ignore
	 * @param	boolean	$hideDot	Hide folders starting with a dot?
	 * @return	array An array of directory names
	 */
	public static function getDirList(
		$dirname,
		array $ignore = ["cvs", "_darcs", ".svn"],
		$hideDot = true,
	) {
		$dirList = [];
		$iterator = new DirectoryIterator($dirname);
		foreach ($iterator as $file) {
			if ($file->isDir() && !$file->isDot()) {
				$filename = $file->getFilename();
				if (!$hideDot || substr($filename, 0, 1) != ".") {
					$dirList[$filename] = $filename;
				}
			}
		}
		asort($dirList);
		return array_diff($dirList, $ignore);
	}

	/**
	 * Get a list of files in a directory
	 *
	 * This can be used for several different situations -
	 * 		To retrieve an array of images, use getFileList($dirname, $prefix, array('gif', 'jpg', 'png'))
	 * 		To retrieve an array of fonts, use getFileList($dirname, $prefix, array('ttf'))
	 * 		To retrieve an array of HTML files, use getFileList($dirname, $prefix, array('html', 'htm', 'xhtml'))
	 *
	 *
	 * @param	string	$dirname	A path to a directory
	 * @param	string	$prefix		A prefix to add to the beginning of the file names
	 * @param	array	$extension	Filter the list by these extensions
	 * @param	bool	$hideDot	Hide files starting with a dot?
	 * @return	array	$fileList	A list of files in a directory
	 */
	public static function getFileList(
		$dirname,
		$prefix = "",
		array $extension = [],
		$hideDot = false,
	) {
		if (!is_dir($dirname)) {
			return [];
		}
		$fileList = [];
		if (empty($extension)) {
			$extList = "";
		} else {
			$extList = implode("|\.", $extension);
		}
		$iterator = new DirectoryIterator($dirname);
		foreach ($iterator as $file) {
			if ($file->isFile() && !$file->isDot()) {
				$filename = $file->getFilename();
				if (!$hideDot || substr($filename, 0, 1) != ".") {
					if ($extList == "") {
						$file = $prefix . $filename;
						$fileList[$file] = $file;
					} elseif (
						preg_match("/(\." . $extList . ")$/i", $filename)
					) {
						$file = $prefix . $filename;
						$fileList[$file] = $file;
					}
				}
			}
		}
		asort($fileList);
		return $fileList;
	}

	public static function writeFile(
		$contents,
		$filename,
		$extension = "",
		$location = ICMS_TRUST_PATH,
	) {
		if ($extension == "") {
			$extension = "php";
		}
		if (DIRECTORY_SEPARATOR !== "/") {
			$location = str_replace(DIRECTORY_SEPARATOR, "/", $location);
		}
		$file = $location . "/" . $filename . "." . $extension;
		if ($fp = fopen($file, "wt")) {
			if (fwrite($fp, $contents) == false) {
				echo "failed write file";
				return false;
			}
			fclose($fp);
		}
	}

	/**
	 * return the number of files in a directory
	 *
	 * @param string $dirname    the name of the directory
	 * @param string $prefix     prefix to add to the beginning of the file names
	 * @param array  $extension  array of extensions you want the files to count
	 * @param bool   $hideDot    hide files starting with a dot
	 * @param bool   $recursive  when true, count files in all subdirectories as well
	 * @return int the number of files in the directory according to the parameters
	 */
	public static function getFileCount(
		$dirname,
		$prefix = "",
		array $extension = [],
		$hideDot = false,
		bool $recursive = false,
	): int {
		if (!$recursive) {
			return count(
				self::getFileList($dirname, $prefix, $extension, $hideDot),
			);
		}

		if (!is_dir($dirname)) {
			return 0;
		}

		$extList = empty($extension) ? "" : implode("|\.", $extension);
		$count = 0;

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				$dirname,
				RecursiveDirectoryIterator::SKIP_DOTS,
			),
			RecursiveIteratorIterator::LEAVES_ONLY,
		);

		foreach ($iterator as $file) {
			if (!$file->isFile()) {
				continue;
			}
			$filename = $file->getFilename();
			if ($hideDot && substr($filename, 0, 1) === ".") {
				continue;
			}
			if (
				$extList !== "" &&
				!preg_match("/(\." . $extList . ")$/i", $filename)
			) {
				continue;
			}
			$count++;
		}

		return $count;
	}

	/* These will not be in the final release, but are only placeholders while the refactoring
	 * is being completed
	 */
	public static function getImgList(
		$dirname,
		$prefix = "",
		$extension = ["gif", "jpg", "png"],
	) {
		return self::getFileList($dirname, $prefix, $extension);
	}

	public static function getFontList(
		$dirname,
		$prefix = "",
		$extension = ["ttf"],
	) {
		return self::getFileList($dirname, $prefix, $extension);
	}

	public static function getPhpFiles(
		$dirname,
		$prefix = "",
		$extension = ["php"],
	) {
		return self::getFileList($dirname, $prefix, $extension);
	}

	public static function getHtmlFiles(
		$dirname,
		$prefix = "",
		$extension = ["htm", "html", "xhtml"],
	) {
		return self::getFileList($dirname, $prefix, $extension);
	}
	/* The above will be removed */

	/**
	 * Move the Composer vendor directory from the web root into the trust path.
	 *
	 * This is a cross-filesystem-safe operation: it uses copy + delete rather than
	 * rename() so it works on shared hosting where the web root and trust path may
	 * reside on different filesystems or partitions.
	 *
	 * Conflict policy: if vendor already exists in the trust path with DIFFERENT
	 * contents (detected by comparing sha1 of autoload.php), the destination is
	 * removed and overwritten with the source copy.  A 'warning' key is added to
	 * the return value so the caller can surface this to the admin.
	 *
	 * Return value shape:
	 * <code>
	 * [
	 *   'status'  => 'ok'|'skipped'|'novendor'|'error_copy',
	 *   'message' => string,   // human-readable, suitable for admin UI / logs
	 *   'warning' => string,   // non-fatal warning, e.g. source could not be deleted
	 *   'src'     => string,   // resolved source path (for debugging)
	 *   'dest'    => string,   // resolved destination path (for debugging)
	 * ]
	 * </code>
	 *
	 * Status meanings:
	 *   'ok'         – copy succeeded; source removed (or removal failed → see 'warning').
	 *   'skipped'    – nothing to do: vendor absent in web root and already in trust
	 *                  path, or identical copy already in trust path.
	 *   'novendor'   – vendor found in neither location; no action taken.
	 *   'error_copy' – copy failed (permissions, disk full, etc.); partial destination
	 *                  cleaned up; manual remediation required.
	 *
	 * @param  string $rootPath   Absolute path to the web root  (ICMS_ROOT_PATH)
	 * @param  string $trustPath  Absolute path to the trust path (ICMS_TRUST_PATH)
	 * @return array{status: string, message: string, warning: string, src: string, dest: string}
	 */
	public static function moveVendorToTrust(
		string $rootPath,
		string $trustPath,
	): array {
		// Normalise: forward slashes, no trailing slash
		$src = rtrim(str_replace("\\", "/", $rootPath), "/") . "/vendor";
		$dest = rtrim(str_replace("\\", "/", $trustPath), "/") . "/vendor";

		$srcExists = is_dir($src);
		$destExists = is_dir($dest);

		$base = [
			"warning" => "",
			"src" => $src,
			"dest" => $dest,
		];

		// ── Neither location has a vendor directory ───────────────────────────
		if (!$srcExists && !$destExists) {
			return $base + [
				"status" => "novendor",
				"message" => sprintf(
					"No vendor directory found in web root (%s) or trust path (%s). " .
						"If you are upgrading from a version that pre-dates Composer support " .
						"this is expected – no action required.",
					$src,
					$dest,
				),
			];
		}

		// ── Source absent: vendor already lives only in the trust path ────────
		if (!$srcExists) {
			return $base + [
				"status" => "skipped",
				"message" => sprintf(
					"Vendor directory is already in the trust path (%s) and absent " .
						"from the web root – nothing to do.",
					$dest,
				),
			];
		}

		// ── Source exists: check whether destination holds identical content ──
		if ($destExists) {
			$destAutoload = $dest . "/autoload.php";
			$srcHash = @sha1_file($src . "/autoload.php");
			$destHash = file_exists($destAutoload)
				? @sha1_file($destAutoload)
				: false;

			if (
				$srcHash !== false &&
				$destHash !== false &&
				$srcHash === $destHash
			) {
				// Identical copy already present – just remove the web-root original.
				self::deleteRecursive($src, true);
				$warning = is_dir($src)
					? sprintf(
						"The vendor directory in the web root (%s) could not be removed. " .
							"Please delete it manually via FTP/SFTP or your hosting file manager.",
						$src,
					)
					: "";
				return $base + [
					"status" => "skipped",
					"message" => sprintf(
						"An identical vendor directory already exists in the trust path (%s). " .
							"The web-root copy has been removed.",
						$dest,
					),
					"warning" => $warning,
				];
			}

			// Different contents (or incomplete dest) – remove destination so we
			// get a clean, authoritative copy from the source.
			self::deleteRecursive($dest, true);
		}

		// ── Copy source → destination ─────────────────────────────────────────
		@set_time_limit(300);

		if (!self::copyRecursive($src, $dest)) {
			// Copy failed – clean up any partial destination.
			if (is_dir($dest)) {
				self::deleteRecursive($dest, true);
			}
			return $base + [
				"status" => "error_copy",
				"message" => sprintf(
					"Failed to copy the vendor directory from the web root (%s) to the " .
						"trust path (%s). Please check that the trust path is writable and " .
						"that there is sufficient disk space, then retry. " .
						"Alternatively, copy the vendor directory manually via FTP/SFTP.",
					$src,
					$dest,
				),
			];
		}

		// Verify the copy is complete and consistent:
		// 1. autoload.php must exist in the destination.
		// 2. Its SHA-1 must match the source (rules out a zero-byte or truncated file).
		// 3. The total file count in dest must equal the source (rules out a partial tree).
		$destAutoloadPath = $dest . "/autoload.php";
		$srcAutoloadPath = $src . "/autoload.php";

		$destAutoloadHash = file_exists($destAutoloadPath)
			? @sha1_file($destAutoloadPath)
			: false;
		$srcAutoloadHash = file_exists($srcAutoloadPath)
			? @sha1_file($srcAutoloadPath)
			: false;

		if (
			$destAutoloadHash === false ||
			$srcAutoloadHash === false ||
			$destAutoloadHash !== $srcAutoloadHash
		) {
			self::deleteRecursive($dest, true);
			return $base + [
				"status" => "error_copy",
				"message" => sprintf(
					"The vendor copy to the trust path (%s) appears incomplete or corrupt " .
						"(autoload.php is missing or does not match the source). " .
						"The partial copy has been removed. " .
						"Please retry or copy the vendor directory manually via FTP/SFTP.",
					$dest,
				),
			];
		}

		// Count files recursively so that subdirectories at any depth are included.
		$srcFileCount = self::getFileCount($src, "", [], false, true);
		$destFileCount = self::getFileCount($dest, "", [], false, true);

		if ($srcFileCount !== $destFileCount) {
			self::deleteRecursive($dest, true);
			return $base + [
				"status" => "error_copy",
				"message" => sprintf(
					"The vendor copy to the trust path (%s) appears incomplete " .
						"(source has %d file(s) but destination has %d). " .
						"The partial copy has been removed. " .
						"Please retry or copy the vendor directory manually via FTP/SFTP.",
					$dest,
					$srcFileCount,
					$destFileCount,
				),
			];
		}

		// ── Delete the original from the web root ─────────────────────────────
		self::deleteRecursive($src, true);

		$warning = "";
		if (is_dir($src)) {
			$warning = sprintf(
				"The vendor directory in the web root (%s) could not be removed automatically. " .
					"Please delete it manually via FTP/SFTP or your hosting file manager " .
					"to prevent third-party library code from being web-accessible.",
				$src,
			);
		}

		return $base + [
			"status" => "ok",
			"message" => sprintf(
				"Vendor directory successfully moved from the web root (%s) to the " .
					"trust path (%s).",
				$src,
				$dest,
			),
			"warning" => $warning,
		];
	}
}

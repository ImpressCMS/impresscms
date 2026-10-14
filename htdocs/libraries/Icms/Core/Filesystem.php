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

namespace Icms\Core;

/**
 * Perform filesystem actions
 */
class Filesystem
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

		if (!is_dir($dest)) {
			return false;
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

			$path = $dir . "/" . $obj;

			if (is_link($path)) {
				// Never descend into a symlinked directory; remove the link itself.
				@unlink($path) || @rmdir($path);
				continue;
			}

			if (is_dir($path)) {
				self::deleteRecursive($path, true);
				continue;
			}

			@unlink($path);
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
		$dir = new \RecursiveDirectoryIterator($rootdir);
		$checkfile =
			preg_replace("#[\|/]#", DIRECTORY_SEPARATOR, ICMS_TRUST_PATH) .
			DIRECTORY_SEPARATOR .
			"checkfile.sha1";

		$file = new \SplFileObject($checkfile, "w");
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
		foreach (new \RecursiveIteratorIterator($dir) as $name => $item) {
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
		$validationFile = new \SplFileObject($checkfile);
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
			foreach (new \RecursiveIteratorIterator($dir) as $name => $item) {
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
		$iterator = new \DirectoryIterator($dirname);
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
		$iterator = new \DirectoryIterator($dirname);
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

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				$dirname,
				\RecursiveDirectoryIterator::SKIP_DOTS |
					\RecursiveDirectoryIterator::FOLLOW_SYMLINKS,
			),
			\RecursiveIteratorIterator::LEAVES_ONLY,
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

	/** The staging directory in the web root that carries the host files of a release. */
	public const TRUST_DISTRIBUTION_DIR = "trust_dist";

	/** Trust path entries that belong to the site and are never replaced. */
	private const TRUST_LOCAL_ENTRIES = ["var", ".env.local"];

	/**
	 * Install the Symfony host from the staging directory into the trust path.
	 *
	 * Every top-level entry of the staging directory (host.php, composer.json,
	 * vendor/, src/, config/, …) is copied into the trust path as "<entry>.new"
	 * and verified (same files, same sizes) before anything is replaced; a failed
	 * copy therefore leaves the trust path as it was. Activation then swaps each
	 * entry in, keeps var/ and .env.local, writes .env.local when it is missing,
	 * empties var/cache, removes the trust-path autoloader stub of system
	 * dbversion 49 and a web-root vendor/, and removes the staging directory.
	 * A vendor/ without host.php next to it was moved there by dbversion 49; it
	 * is kept as vendor.pre-50.
	 *
	 * Copy + delete instead of rename(), because the web root and the trust path
	 * may be on different filesystems.
	 *
	 * Return value shape:
	 * <code>
	 * [
	 *   'status'  => 'ok'|'skipped'|'nodist'|'error_dist'|'error_copy',
	 *   'message' => string,   // human-readable, suitable for admin UI / logs
	 *   'warning' => string,   // non-fatal problem, e.g. a leftover that could not be removed
	 *   'src'     => string,   // staging directory
	 *   'dest'    => string,   // trust path
	 * ]
	 * </code>
	 *
	 * @param  string $stagingPath the staging directory (<web root>/trust_dist)
	 * @param  string $trustPath   ICMS_TRUST_PATH
	 * @param  string $rootPath    ICMS_ROOT_PATH, recorded in .env.local
	 * @param  bool   $deferActivation activate at the end of the request. Use this
	 *                when the running request may still load classes from the trust
	 *                path or the staging directory (the system module update and the
	 *                installer); the copy and its verification still happen now.
	 * @return array{status: string, message: string, warning: string, src: string, dest: string}
	 */
	public static function installTrustDistribution(
		string $stagingPath,
		string $trustPath,
		string $rootPath,
		bool $deferActivation = false,
	): array {
		$src = rtrim(str_replace("\\", "/", $stagingPath), "/");
		$dest = rtrim(str_replace("\\", "/", $trustPath), "/");
		$root = rtrim(str_replace("\\", "/", $rootPath), "/");
		$base = ["warning" => "", "src" => $src, "dest" => $dest];

		if (!is_dir($src)) {
			return $base + (is_file("$dest/host.php")
				? ["status" => "skipped", "message" => sprintf("The host is already installed in the trust path (%s).", $dest)]
				: ["status" => "nodist", "message" => sprintf("No host files to install: %s does not exist.", $src)]);
		}
		if (!is_file("$src/host.php") || !is_file("$src/vendor/autoload.php")) {
			return $base + [
				"status" => "error_dist",
				"message" => sprintf(
					"%s is incomplete (host.php or vendor/autoload.php is missing). " .
						"Upload the trust_dist directory of the ImpressCMS package again.",
					$src,
				),
			];
		}
		if (!is_dir($dest) && !@mkdir($dest, 0775, true) && !is_dir($dest)) {
			return $base + [
				"status" => "error_copy",
				"message" => sprintf("The trust path (%s) does not exist and cannot be created.", $dest),
			];
		}

		@set_time_limit(300);

		// ── Copy every entry to <entry>.new and verify it ──────────────────────
		$entries = [];
		foreach (scandir($src) ?: [] as $entry) {
			if ($entry === "." || $entry === ".." || $entry === "index.html" || in_array($entry, self::TRUST_LOCAL_ENTRIES, true)) {
				continue;
			}
			$entries[] = $entry;
		}

		$discard = static function () use ($entries, $dest): void {
			foreach ($entries as $entry) {
				self::removePath("$dest/$entry.new");
			}
		};

		foreach ($entries as $entry) {
			self::removePath("$dest/$entry.new");
			$copied = is_dir("$src/$entry")
				? self::copyRecursive("$src/$entry", "$dest/$entry.new")
				: @copy("$src/$entry", "$dest/$entry.new");
			$problem = $copied ? self::compareTrees("$src/$entry", "$dest/$entry.new") : "the copy failed";
			if ($problem !== "") {
				$discard();
				return $base + [
					"status" => "error_copy",
					"message" => sprintf(
						"Could not copy %s to the trust path (%s): %s. Nothing has been changed. " .
							"Check that the trust path is writable and has enough free space, then run the update again, " .
							"or upload the contents of %s to the trust path manually.",
						$entry,
						$dest,
						$problem,
						$src,
					),
				];
			}
		}

		// ── Activate: swap the verified copies in ──────────────────────────────
		$activate = static function () use ($entries, $src, $dest, $root): string {
			$warnings = [];
			$legacyVendor = is_dir("$dest/vendor") && !is_file("$dest/host.php");

			foreach ($entries as $entry) {
				$target = "$dest/$entry";
				$previous = $entry === "vendor" && $legacyVendor ? "$dest/vendor.pre-50" : "$target.old";
				self::removePath($previous);
				if (file_exists($target) && !@rename($target, $previous)) {
					$warnings[] = sprintf("%s could not be replaced; the new version is in %s.new.", $target, $target);
					continue;
				}
				if (!@rename("$target.new", $target)) {
					@rename($previous, $target);
					$warnings[] = sprintf("%s.new could not be renamed to %s.", $target, $target);
					continue;
				}
				if ($previous === "$target.old") {
					self::removePath($previous);
				}
			}

			$warnings[] = self::writeTrustEnvironment($dest, $root);
			self::removePath("$dest/var/cache");

			// The stub that system dbversion 49 wrote for its re-based vendor/.
			self::removePath("$dest/libraries/Autoloader.php");
			if (is_dir("$dest/libraries") && (scandir("$dest/libraries") ?: []) === [".", ".."]) {
				@rmdir("$dest/libraries");
			}

			if ($warnings === array_filter($warnings, static fn(string $w): bool => $w === "")) {
				// Only once everything is in place: the staging directory is the
				// fallback the site boots from until then.
				foreach (["$src", "$root/vendor"] as $leftover) {
					self::removePath($leftover);
					if (file_exists($leftover)) {
						$warnings[] = sprintf("%s could not be removed; delete it manually.", $leftover);
					}
				}
			}

			return implode(" ", array_filter($warnings, static fn(string $w): bool => $w !== ""));
		};

		if ($deferActivation) {
			register_shutdown_function(static function () use ($activate): void {
				$warning = $activate();
				if ($warning !== "") {
					error_log("ImpressCMS host installation: " . $warning);
				}
			});

			return $base + [
				"status" => "ok",
				"message" => sprintf(
					"The host files have been copied to the trust path (%s) and are activated at the end of this request.",
					$dest,
				),
			];
		}

		$warning = $activate();

		return $base + [
			"status" => "ok",
			"message" => sprintf("The host has been installed in the trust path (%s).", $dest),
			"warning" => $warning,
		];
	}

	/**
	 * Write the trust path's .env.local when it is missing, or add the web root
	 * to it: the site's own settings for the host (environment, secret, web root
	 * for bin/console). Returns a warning, or an empty string.
	 */
	private static function writeTrustEnvironment(string $trust, string $root): string
	{
		$file = "$trust/.env.local";
		$rootLine = "ICMS_LEGACY_ROOT='" . str_replace("'", "", $root) . "'\n";

		if (is_file($file)) {
			$current = (string) @file_get_contents($file);
			if (preg_match('/^ICMS_LEGACY_ROOT=/m', $current)) {
				return "";
			}
			$written = @file_put_contents($file, rtrim($current, "\n") . "\n" . $rootLine);
		} else {
			$written = @file_put_contents(
				$file,
				"# Settings of this site for the ImpressCMS host, written by the installer\n" .
					"# or the system module update. Kept by later updates.\n" .
					"APP_ENV=prod\n" .
					"APP_DEBUG=0\n" .
					"APP_SECRET=" . bin2hex(random_bytes(32)) . "\n" .
					$rootLine,
			);
		}

		return $written === false ? sprintf("%s could not be written.", $file) : "";
	}

	/**
	 * Compare a copied file or directory tree with its source: same relative
	 * file names (ignoring index.html files that hosting panels create) and same
	 * file sizes. Returns a description of the first difference, or "".
	 */
	private static function compareTrees(string $source, string $copy): string
	{
		if (!is_dir($source)) {
			return is_file($copy) && filesize($copy) === filesize($source) ? "" : "the copy of $source differs";
		}

		$withoutIndex = static fn(array $files): array => array_values(
			array_filter($files, static fn(string $f): bool => basename($f) !== "index.html"),
		);
		$sourceFiles = $withoutIndex(self::getRelativeFileSet($source));
		$copiedFiles = $withoutIndex(self::getRelativeFileSet($copy));

		if ($sourceFiles !== $copiedFiles) {
			$missing = array_values(array_diff($sourceFiles, $copiedFiles));
			return sprintf(
				"%d of %d files are missing in the copy%s",
				count($missing),
				count($sourceFiles),
				$missing === [] ? "" : " (for example " . $missing[0] . ")",
			);
		}
		foreach ($sourceFiles as $file) {
			if (filesize("$source/$file") !== filesize("$copy/$file")) {
				return "$file differs from the original";
			}
		}

		return "";
	}

	/** Remove a file or a directory tree, if it exists. */
	private static function removePath(string $path): void
	{
		if (is_dir($path) && !is_link($path)) {
			self::deleteRecursive($path, true);
		} elseif (file_exists($path) || is_link($path)) {
			@unlink($path);
		}
	}

	/**
	 * Return a flat array of every file's path relative to $baseDir, using the
	 * same \RecursiveIteratorIterator / FOLLOW_SYMLINKS flags as getFileCount()
	 * so that the two methods always agree on what constitutes "a file".
	 *
	 * Paths use forward slashes and have no leading slash, e.g.
	 *   "autoload.php"
	 *   "composer/autoload_classmap.php"
	 *
	 * @param  string $baseDir  Absolute path to the directory to scan.
	 * @return string[]         Sorted list of relative file paths.
	 */
	private static function getRelativeFileSet(string $baseDir): array
	{
		if (!is_dir($baseDir)) {
			return [];
		}

		$baseDir = rtrim(str_replace("\\", "/", $baseDir), "/");
		$result = [];

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator(
				$baseDir,
				\RecursiveDirectoryIterator::SKIP_DOTS |
					\RecursiveDirectoryIterator::FOLLOW_SYMLINKS,
			),
			\RecursiveIteratorIterator::LEAVES_ONLY,
		);

		foreach ($iterator as $file) {
			if (!$file->isFile()) {
				continue;
			}
			// Convert the absolute path to a forward-slash relative path.
			$abs = str_replace("\\", "/", $file->getPathname());
			$result[] = ltrim(substr($abs, strlen($baseDir)), "/");
		}

		sort($result);
		return $result;
	}
}


<?php
/**
 * Install module releases from GitHub, with backup and rollback
 *
 * Before the files of a module are replaced, the current module folder and the
 * module's database tables are saved in the trust path. When the update fails the
 * backup is restored automatically; an administrator can also roll back later.
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Module
 * @since		2.0
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Install module releases from GitHub, with backup and rollback
 */
class icms_module_Updater {

	/** Backups kept per module; older ones are removed after a successful update */
	const KEEP_BACKUPS = 3;

	/** Largest release zip that will be downloaded */
	const MAX_DOWNLOAD_SIZE = 52428800;

	/** Rows per INSERT when restoring a table */
	const RESTORE_BATCH = 200;

	/** Hosts a release may be downloaded from (GitHub redirects to the last two) */
	protected static $downloadHosts = array('github.com', 'api.github.com', 'codeload.github.com', 'objects.githubusercontent.com', 'release-assets.githubusercontent.com');

	/** Core tables a module may never back up, drop or restore (same list as the module uninstaller) */
	protected static $reservedTables = array('avatar', 'avatar_users_link', 'block_module_link', 'xoopscomments', 'config', 'configcategory', 'configoption', 'image', 'imagebody', 'imagecategory', 'imgset', 'imgset_tplset_link', 'imgsetimg', 'groups', 'groups_users_link', 'group_permission', 'online', 'priv_msgs', 'ranks', 'session', 'smiles', 'users', 'newblocks', 'modules', 'tplfile', 'tplset', 'tplsource', 'xoopsnotifications');

	/** @var icms_module_Catalog */
	protected $catalog;

	/** @var PDO */
	protected $pdo;

	/** @var string database table prefix, without the trailing underscore */
	protected $prefix;

	/** @var string */
	protected $modulesPath;

	/** @var string */
	protected $backupPath;

	/** @var array */
	protected $messages = array();

	/** @var array */
	protected $errors = array();

	/**
	 * @param	icms_module_Catalog	$catalog
	 * @param	PDO		$pdo			database connection; the site's connection when NULL
	 * @param	string	$prefix			table prefix; the site's prefix when NULL
	 * @param	string	$modulesPath	folder holding the modules
	 * @param	string	$backupPath		folder backups are kept in, preferably outside the web root
	 */
	public function __construct(icms_module_Catalog $catalog, PDO $pdo = null, $prefix = null, $modulesPath = null, $backupPath = null) {
		$this->catalog = $catalog;
		$this->pdo = $pdo ?: icms::$xoopsDB->getConnection();
		$this->prefix = $prefix !== null ? $prefix : icms::$xoopsDB->prefix();
		$this->modulesPath = rtrim($modulesPath ?: ICMS_MODULES_PATH, '/');
		$this->backupPath = rtrim($backupPath ?: ICMS_TRUST_PATH . '/module_backups', '/');
	}

	/**
	 * Install the latest GitHub release of a module
	 *
	 * The current files and tables are backed up first. For an installed module
	 * $runUpdate is called to update the database the usual way; when it fails or
	 * throws, the backup is restored.
	 *
	 * @param	string		$dirname
	 * @param	array		$tables			tables of the installed module (unprefixed), empty when not installed
	 * @param	string		$version		version of the current module files, '' when there are none
	 * @param	callable	$runUpdate		function ($dirname) returning TRUE on success; NULL when the module is not installed
	 * @param	callable	$readTables		function ($dirname) returning the tables the module files on disk declare
	 * @return	string|FALSE	the backup id, FALSE on failure
	 */
	public function update($dirname, array $tables, $version, $runUpdate = null, $readTables = null) {
		$entry = $this->catalog->getModule($dirname);
		if ($entry === null || empty($entry['latest']['download_url'])) {
			$this->errors[] = sprintf('No release of %s found on GitHub', $dirname);
			return false;
		}
		$dirname = $this->safeDirname($dirname);
		if ($dirname === false) {
			return false;
		}

		$work = $this->backupPath . '/.work-' . uniqid();
		try {
			$this->ensureBackupPath();
			$this->makeDir($work);

			$zip = $work . '/release.zip';
			$this->download($entry['latest']['download_url'], $zip);
			$source = $this->extract($zip, $work . '/extract');
			$this->messages[] = sprintf('Downloaded %s %s', $entry['full_name'], $entry['latest']['tag']);

			$backupId = $this->createBackup($dirname, $tables, $version, $entry['latest']['version'], $runUpdate !== null);
			$this->messages[] = sprintf('Backup %s created', $backupId);

			$this->replaceFolder($source, $this->modulesPath . '/' . $dirname);
			$this->messages[] = 'Module files replaced';
		} catch (Exception $e) {
			$this->errors[] = $e->getMessage();
			icms_core_Filesystem::deleteRecursive($work);
			if (isset($backupId)) {
				$this->setBackupStatus($backupId, 'failed');
			}
			return false;
		}
		icms_core_Filesystem::deleteRecursive($work);

		if ($runUpdate !== null) {
			try {
				$ok = (bool) call_user_func($runUpdate, $dirname);
			} catch (Throwable $e) {
				$ok = false;
				$this->errors[] = $e->getMessage();
			}
			if (!$ok) {
				$this->errors[] = 'The module update failed, restoring the backup';
				$currentTables = $readTables ? (array) call_user_func($readTables, $dirname) : array();
				$this->rollback($backupId, $runUpdate, $currentTables);
				$this->setBackupStatus($backupId, 'failed');
				return false;
			}
		}

		$this->setBackupStatus($backupId, 'ok');
		$this->pruneBackups($dirname);
		return $backupId;
	}

	/**
	 * Put the files and tables of a backup back in place
	 *
	 * Tables in $currentTables that are not in the backup were added by the newer
	 * version and are dropped.
	 *
	 * @param	string		$backupId
	 * @param	callable	$runUpdate		function ($dirname) that refreshes templates, blocks and config from the restored files; NULL to skip
	 * @param	array		$currentTables	tables the module files in place now declare (unprefixed)
	 * @return	bool
	 */
	public function rollback($backupId, $runUpdate = null, array $currentTables = array()) {
		$backup = $this->getBackup($backupId);
		if ($backup === null) {
			$this->errors[] = sprintf('Backup %s not found', $backupId);
			return false;
		}
		$dir = $this->backupPath . '/' . $backup['id'];
		$target = $this->modulesPath . '/' . $backup['dirname'];
		$tables = array_unique(array_merge($backup['tables'], $backup['installed'] ? $currentTables : array()));

		try {
			if ($backup['has_files']) {
				$this->replaceFolder($dir . '/files', $target, true);
			} elseif (is_dir($target)) {
				// the module was not there before the download
				$this->invalidateOpcache($target);
				icms_core_Filesystem::deleteRecursive($target);
			}
			$this->messages[] = 'Module files restored';
		} catch (Exception $e) {
			$this->errors[] = 'Rollback failed: ' . $e->getMessage();
			return false;
		}

		// templates, blocks and config come from the restored files. This runs before the
		// tables are restored, because the module's update function may change its data
		// (after a failed update in the same request, PHP still has the new version's
		// functions loaded)
		if ($backup['installed'] && $runUpdate !== null) {
			try {
				call_user_func($runUpdate, $backup['dirname']);
			} catch (Throwable $e) {
				$this->errors[] = $e->getMessage();
			}
		}

		try {
			foreach ($tables as $table) {
				$this->restoreTable($table, $dir . '/tables/' . $table . '.ndjson');
			}
			if ($tables) {
				$this->messages[] = sprintf('Tables restored: %s', implode(', ', $tables));
			}
		} catch (Exception $e) {
			$this->errors[] = 'Rollback failed: ' . $e->getMessage();
			return false;
		}

		$this->setBackupStatus($backup['id'], 'rolledback');
		return true;
	}

	/**
	 * Get all backups, newest first
	 *
	 * @param	string	$dirname	only the backups of this module
	 * @return	array	backups with id, dirname, version, to_version, created, status, installed, has_files, tables
	 */
	public function getBackups($dirname = null) {
		$ret = array();
		if (!is_dir($this->backupPath)) {
			return $ret;
		}
		foreach (scandir($this->backupPath) as $id) {
			if ($id[0] === '.') {
				continue;
			}
			$backup = $this->getBackup($id);
			if ($backup !== null && ($dirname === null || strcasecmp($backup['dirname'], $dirname) === 0)) {
				$ret[] = $backup;
			}
		}
		usort($ret, function ($a, $b) {
			return $b['created'] - $a['created'];
		});
		return $ret;
	}

	/**
	 * @param	string	$backupId
	 * @return	array|NULL
	 */
	public function getBackup($backupId) {
		if (!preg_match('/^[A-Za-z0-9_-]+$/', (string) $backupId)) {
			return null;
		}
		$file = $this->backupPath . '/' . $backupId . '/backup.json';
		if (!is_file($file)) {
			return null;
		}
		$backup = json_decode((string) file_get_contents($file), true);
		return is_array($backup) ? $backup : null;
	}

	/**
	 * @param	string	$backupId
	 * @return	bool
	 */
	public function deleteBackup($backupId) {
		if ($this->getBackup($backupId) === null) {
			return false;
		}
		icms_core_Filesystem::deleteRecursive($this->backupPath . '/' . $backupId);
		return !is_dir($this->backupPath . '/' . $backupId);
	}

	/**
	 * Remove a module from the site: its folder in the modules path and all its backups
	 *
	 * Only for modules that are not installed; the caller checks that.
	 *
	 * @param	string	$dirname
	 * @return	bool
	 */
	public function removeModule($dirname) {
		if ($this->safeDirname($dirname) === false) {
			return false;
		}
		$target = $this->modulesPath . '/' . $dirname;
		if (is_dir($target)) {
			$this->invalidateOpcache($target);
			icms_core_Filesystem::deleteRecursive($target);
			clearstatcache();
			if (is_dir($target)) {
				$this->errors[] = sprintf('Could not delete %s; check the folder permissions', $target);
				return false;
			}
			$this->messages[] = sprintf('Folder %s deleted', $target);
		}
		foreach ($this->getBackups($dirname) as $backup) {
			if ($this->deleteBackup($backup['id'])) {
				$this->messages[] = sprintf('Backup %s deleted', $backup['id']);
			} else {
				$this->errors[] = sprintf('Could not delete backup %s', $backup['id']);
			}
		}
		return empty($this->errors);
	}

	/**
	 * @param	bool	$ashtml
	 * @return	mixed
	 */
	public function getMessages($ashtml = true) {
		return $ashtml ? $this->toHtml($this->messages) : $this->messages;
	}

	/**
	 * @param	bool	$ashtml
	 * @return	mixed
	 */
	public function getErrors($ashtml = true) {
		return $ashtml ? $this->toHtml($this->errors) : $this->errors;
	}

	/**
	 * Download a release zip
	 *
	 * @param	string	$url
	 * @param	string	$file
	 * @throws	RuntimeException
	 */
	protected function download($url, $file) {
		if (!$this->isAllowedUrl($url)) {
			throw new RuntimeException(sprintf('Refusing to download from %s', $url));
		}
		$fp = fopen($file, 'wb');
		if ($fp === false) {
			throw new RuntimeException('Could not create ' . $file);
		}
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_FILE => $fp,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 5,
			CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
			CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS,
			CURLOPT_USERAGENT => 'ImpressCMS-ModuleUpdater/2.0',
			// GitHub answers 415 on the source zipball when asked for application/octet-stream
			CURLOPT_HTTPHEADER => array('Accept: */*'),
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_MAXFILESIZE => self::MAX_DOWNLOAD_SIZE,
			CURLOPT_NOPROGRESS => false,
			CURLOPT_PROGRESSFUNCTION => function ($ch, $total, $downloaded) {
				// also stops downloads without a Content-Length
				return $downloaded > self::MAX_DOWNLOAD_SIZE ? 1 : 0;
			}
		));
		$ok = curl_exec($ch);
		$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$finalUrl = (string) curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$error = curl_error($ch);
		curl_close($ch);
		fclose($fp);

		if (!$ok || $status !== 200) {
			throw new RuntimeException(sprintf('Download of %s failed: %s', $url, $error ?: 'HTTP ' . $status));
		}
		if (!$this->isAllowedUrl($finalUrl)) {
			throw new RuntimeException(sprintf('Download was redirected to %s', $finalUrl));
		}
	}

	/**
	 * @param	string	$url
	 * @return	bool
	 */
	protected function isAllowedUrl($url) {
		$parts = parse_url($url);
		return isset($parts['scheme'], $parts['host'])
			&& strtolower($parts['scheme']) === 'https'
			&& in_array(strtolower($parts['host']), self::$downloadHosts, true);
	}

	/**
	 * Extract a release zip and find the module folder in it
	 *
	 * Works for GitHub source zipballs (owner-repo-sha/...), packaged releases with
	 * modules/<dirname>/ or <dirname>/ in them, and zips with the module files at the root.
	 *
	 * @param	string	$zipFile
	 * @param	string	$target
	 * @return	string	path of the folder holding icms_version.php or xoops_version.php
	 * @throws	RuntimeException
	 */
	protected function extract($zipFile, $target) {
		if (!class_exists('ZipArchive')) {
			throw new RuntimeException('The zip extension is required to install modules from GitHub');
		}
		$zip = new ZipArchive();
		if ($zip->open($zipFile) !== true) {
			throw new RuntimeException('The downloaded release is not a valid zip file');
		}
		for ($i = 0; $i < $zip->numFiles; $i++) {
			$name = str_replace('\\', '/', $zip->getNameIndex($i));
			if ($name === '' || $name[0] === '/' || strpos($name, ':') !== false || in_array('..', explode('/', $name), true)) {
				$zip->close();
				throw new RuntimeException(sprintf('The release contains an unsafe path: %s', $name));
			}
		}
		$this->makeDir($target);
		if (!$zip->extractTo($target)) {
			$zip->close();
			throw new RuntimeException('Could not extract the release');
		}
		$zip->close();

		$root = $this->findModuleRoot($target, 3);
		if ($root === null) {
			throw new RuntimeException('No icms_version.php or xoops_version.php found in the release');
		}
		return $root;
	}

	/**
	 * Breadth first search for the shallowest folder with a module version file
	 *
	 * @param	string	$dir
	 * @param	int		$depth
	 * @return	string|NULL
	 */
	protected function findModuleRoot($dir, $depth) {
		$level = array($dir);
		for ($i = 0; $i <= $depth && $level; $i++) {
			$next = array();
			foreach ($level as $path) {
				if (is_file($path . '/icms_version.php') || is_file($path . '/xoops_version.php')) {
					return $path;
				}
				foreach (scandir($path) as $item) {
					if ($item[0] !== '.' && is_dir($path . '/' . $item) && !is_link($path . '/' . $item)) {
						$next[] = $path . '/' . $item;
					}
				}
			}
			$level = $next;
		}
		return null;
	}

	/**
	 * Save the module folder and tables
	 *
	 * @return	string	backup id
	 * @throws	RuntimeException
	 */
	protected function createBackup($dirname, array $tables, $version, $toVersion, $installed) {
		$id = $dirname . '-' . date('Ymd-His') . '-' . substr(uniqid(), -4);
		$dir = $this->backupPath . '/' . $id;
		$this->makeDir($dir . '/tables');

		$source = $this->modulesPath . '/' . $dirname;
		$hasFiles = is_dir($source);
		if ($hasFiles) {
			$this->copyFolder($source, $dir . '/files');
		}

		$tables = array_values(array_filter($tables, array($this, 'isModuleTable')));
		foreach ($tables as $table) {
			$this->dumpTable($table, $dir . '/tables/' . $table . '.ndjson');
		}

		$this->writeJson($dir . '/backup.json', array(
			'id' => $id,
			'dirname' => $dirname,
			'version' => (string) $version,
			'to_version' => (string) $toVersion,
			'created' => time(),
			'status' => 'pending',
			'installed' => (bool) $installed,
			'has_files' => $hasFiles,
			'tables' => $tables
		));
		return $id;
	}

	/**
	 * Write a table as newline delimited JSON: the CREATE TABLE statement, then one row per line
	 *
	 * Binary values that are not valid UTF-8 are stored base64 encoded as {"b64": "..."}.
	 *
	 * @throws	RuntimeException
	 */
	protected function dumpTable($table, $file) {
		$name = $this->tableName($table);
		$result = $this->pdo->query('SHOW TABLES LIKE ' . $this->pdo->quote($name));
		if ($result === false || $result->fetchColumn() === false) {
			// declared by the module but not created (yet)
			return;
		}
		$create = $this->pdo->query('SHOW CREATE TABLE `' . $name . '`')->fetch(PDO::FETCH_NUM);
		$fp = fopen($file, 'wb');
		if ($fp === false) {
			throw new RuntimeException('Could not write ' . $file);
		}
		fwrite($fp, json_encode(array('create' => $create[1])) . "\n");
		$rows = $this->pdo->query('SELECT * FROM `' . $name . '`', PDO::FETCH_ASSOC);
		foreach ($rows as $row) {
			foreach ($row as $column => $value) {
				if ($value !== null && !mb_check_encoding($value, 'UTF-8')) {
					$row[$column] = array('b64' => base64_encode($value));
				}
			}
			fwrite($fp, json_encode($row) . "\n");
		}
		fclose($fp);
	}

	/**
	 * Recreate a table from its dump; a table without a dump did not exist and is dropped
	 *
	 * @throws	RuntimeException
	 */
	protected function restoreTable($table, $file) {
		if (!$this->isModuleTable($table)) {
			return;
		}
		$name = $this->tableName($table);
		$this->pdo->exec('DROP TABLE IF EXISTS `' . $name . '`');
		if (!is_file($file)) {
			return;
		}

		$fp = fopen($file, 'rb');
		$header = json_decode((string) fgets($fp), true);
		if (empty($header['create']) || $this->pdo->exec($header['create']) === false) {
			fclose($fp);
			throw new RuntimeException(sprintf('Could not recreate table %s', $name));
		}

		$batch = array();
		while (($line = fgets($fp)) !== false) {
			$row = json_decode($line, true);
			if (!is_array($row)) {
				continue;
			}
			$batch[] = $row;
			if (count($batch) >= self::RESTORE_BATCH) {
				$this->insertRows($name, $batch);
				$batch = array();
			}
		}
		fclose($fp);
		if ($batch) {
			$this->insertRows($name, $batch);
		}
	}

	/**
	 * @throws	RuntimeException
	 */
	protected function insertRows($name, array $rows) {
		$columns = array_keys($rows[0]);
		$placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
		$sql = 'INSERT INTO `' . $name . '` (`' . implode('`,`', $columns) . '`) VALUES '
			. implode(',', array_fill(0, count($rows), $placeholders));
		$values = array();
		foreach ($rows as $row) {
			foreach ($columns as $column) {
				$value = $row[$column];
				$values[] = is_array($value) ? base64_decode($value['b64']) : $value;
			}
		}
		$statement = $this->pdo->prepare($sql);
		if ($statement === false || !$statement->execute($values)) {
			throw new RuntimeException(sprintf('Could not restore the rows of %s', $name));
		}
	}

	/**
	 * Swap a folder for another, keeping the old one until the new one is in place
	 *
	 * @param	string	$source
	 * @param	string	$target
	 * @param	bool	$keepSource	copy instead of move, so the source survives
	 * @throws	RuntimeException
	 */
	protected function replaceFolder($source, $target, $keepSource = false) {
		$parent = dirname($target);
		$suffix = uniqid();
		// staging folders start with a dot, so the modules list ignores them
		$staging = $parent . '/.' . basename($target) . '.new-' . $suffix;
		$old = $parent . '/.' . basename($target) . '.old-' . $suffix;

		$this->copyFolder($source, $staging);
		if (!$keepSource) {
			icms_core_Filesystem::deleteRecursive($source);
		}
		if (is_dir($target) && !rename($target, $old)) {
			icms_core_Filesystem::deleteRecursive($staging);
			throw new RuntimeException(sprintf('Could not move %s out of the way; check the folder permissions', $target));
		}
		if (!rename($staging, $target)) {
			if (is_dir($old)) {
				rename($old, $target);
			}
			icms_core_Filesystem::deleteRecursive($staging);
			throw new RuntimeException(sprintf('Could not put the new files in %s', $target));
		}
		if (is_dir($old)) {
			icms_core_Filesystem::deleteRecursive($old);
		}
		$this->invalidateOpcache($target);
	}

	/**
	 * Make PHP read the module files again instead of serving old compiled code
	 *
	 * opcache only checks timestamps every few seconds, so without this the update
	 * would run against the code of the version that was just replaced.
	 *
	 * @param	string	$dir
	 */
	protected function invalidateOpcache($dir) {
		if (!function_exists('opcache_invalidate') || !is_dir($dir)) {
			return;
		}
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
		foreach ($files as $file) {
			if (strtolower($file->getExtension()) === 'php') {
				@opcache_invalidate($file->getPathname(), true);
			}
		}
	}

	/**
	 * @throws	RuntimeException
	 */
	protected function copyFolder($source, $target) {
		if (!is_dir($target) && !mkdir($target, 0755, true)) {
			throw new RuntimeException('Could not create ' . $target);
		}
		$items = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS),
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ($items as $item) {
			$path = $target . '/' . substr($item->getPathname(), strlen($source) + 1);
			if ($item->isLink()) {
				continue;
			}
			if ($item->isDir()) {
				if (!is_dir($path) && !mkdir($path, 0755)) {
					throw new RuntimeException('Could not create ' . $path);
				}
			} elseif (!copy($item->getPathname(), $path)) {
				throw new RuntimeException('Could not copy ' . $item->getPathname());
			}
		}
	}

	/**
	 * Remove backups beyond KEEP_BACKUPS for a module
	 */
	protected function pruneBackups($dirname) {
		foreach (array_slice($this->getBackups($dirname), self::KEEP_BACKUPS) as $backup) {
			$this->deleteBackup($backup['id']);
		}
	}

	protected function setBackupStatus($backupId, $status) {
		$backup = $this->getBackup($backupId);
		if ($backup !== null) {
			$backup['status'] = $status;
			$this->writeJson($this->backupPath . '/' . $backupId . '/backup.json', $backup);
		}
	}

	/**
	 * @throws	RuntimeException
	 */
	protected function ensureBackupPath() {
		if (!is_dir($this->backupPath)) {
			@mkdir($this->backupPath, 0700, true);
		}
		if (!is_dir($this->backupPath) || !is_writable($this->backupPath)) {
			throw new RuntimeException(sprintf('The backup folder %s is not writable', $this->backupPath));
		}
	}

	/**
	 * @throws	RuntimeException
	 */
	protected function makeDir($dir) {
		if (!is_dir($dir) && !mkdir($dir, 0700, true)) {
			throw new RuntimeException('Could not create ' . $dir);
		}
	}

	/**
	 * @throws	RuntimeException
	 */
	protected function writeJson($file, array $data) {
		if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) === false) {
			throw new RuntimeException('Could not write ' . $file);
		}
	}

	/**
	 * @return	string|FALSE
	 */
	protected function safeDirname($dirname) {
		if (!preg_match('/^[A-Za-z0-9_-]+$/', $dirname) || strtolower($dirname) === 'system') {
			$this->errors[] = sprintf('%s cannot be installed from GitHub', $dirname);
			return false;
		}
		return $dirname;
	}

	/**
	 * @return	bool
	 */
	protected function isModuleTable($table) {
		return preg_match('/^[A-Za-z0-9_]+$/', $table)
			&& !in_array($table, self::$reservedTables, true)
			&& strncmp($table, 'system_', 7) !== 0;
	}

	protected function tableName($table) {
		return $this->prefix . '_' . $table;
	}

	protected function toHtml(array $lines) {
		$ret = '';
		foreach ($lines as $line) {
			$ret .= htmlspecialchars($line, ENT_QUOTES) . '<br />';
		}
		return $ret;
	}
}

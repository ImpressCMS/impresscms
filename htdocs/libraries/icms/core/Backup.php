<?php
/**
 * ImpressCMS Core Backup Class - Optimized Version
 *
 * Handles creating and managing backups of the ImpressCMS installation
 * Uses streaming ZIP approach to avoid timeout and memory issues
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @subpackage	Backup
 * @since		2.0
 * @author		ImpressCMS Core Team
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Core backup management class with streaming ZIP support
 *
 * Provides functionality to create, manage, and restore ImpressCMS backups
 * Uses ZipArchive for better performance and memory efficiency
 */
class icms_core_Backup {

	/**
	 * Errors array
	 * @var array
	 */
	public $errors = array();

	/**
	 * Status messages
	 * @var array
	 */
	public $messages = array();

	/**
	 * Backup directory
	 * @var string
	 */
	private $backupDir;

	/**
	 * Source directory to backup
	 * @var string
	 */
	private $sourceDir;

	/**
	 * Directories to exclude from backup
	 * @var array
	 */
	private $excludeDirs;

	/**
	 * Files to exclude from backup (patterns)
	 * @var array
	 */
	private $excludeFiles;

	/**
	 * Maximum file size to include in backup (in bytes)
	 * @var int
	 */
	private $maxFileSize;

	/**
	 * Constructor
	 *
	 * @param string $sourceDir Source directory to backup (defaults to ICMS_ROOT_PATH)
	 * @param string $backupDir Backup directory (defaults to ICMS_CACHE_PATH/backups)
	 */
	public function __construct($sourceDir = null, $backupDir = null) {
		$this->sourceDir = $sourceDir ?: ICMS_ROOT_PATH;
		$this->backupDir = $backupDir ?: ICMS_CACHE_PATH . '/backups';
		
		// Default exclusions for ImpressCMS
		$this->excludeDirs = array('cache', 'uploads', 'templates_c', 'backups');
		$this->excludeFiles = array('*.log', '*.tmp', '.DS_Store', 'Thumbs.db');
		$this->maxFileSize = 50 * 1024 * 1024; // 50MB default limit
		
		// Ensure backup directory exists
		$this->createBackupDirectory();
	}

	/**
	 * Create backup directory if it doesn't exist
	 *
	 * @return bool
	 */
	private function createBackupDirectory() {
		if (!is_dir($this->backupDir)) {
			if (!icms_core_Filesystem::mkdir($this->backupDir, 0755, '')) {
				$this->errors[] = "Failed to create backup directory: " . $this->backupDir;
				return false;
			}
		}

		if (!is_writable($this->backupDir)) {
			$this->errors[] = "Backup directory is not writable: " . $this->backupDir;
			return false;
		}

		return true;
	}

	/**
	 * Set directories to exclude from backup
	 *
	 * @param array $excludeDirs Array of directory names to exclude
	 */
	public function setExcludeDirs($excludeDirs) {
		$this->excludeDirs = $excludeDirs;
	}

	/**
	 * Add directory to exclude list
	 *
	 * @param string $dir Directory name to exclude
	 */
	public function addExcludeDir($dir) {
		if (!in_array($dir, $this->excludeDirs)) {
			$this->excludeDirs[] = $dir;
		}
	}

	/**
	 * Set file patterns to exclude from backup
	 *
	 * @param array $excludeFiles Array of file patterns to exclude
	 */
	public function setExcludeFiles($excludeFiles) {
		$this->excludeFiles = $excludeFiles;
	}

	/**
	 * Add file pattern to exclude list
	 *
	 * @param string $pattern File pattern to exclude (e.g., "*.log")
	 */
	public function addExcludeFile($pattern) {
		if (!in_array($pattern, $this->excludeFiles)) {
			$this->excludeFiles[] = $pattern;
		}
	}

	/**
	 * Set maximum file size to include in backup
	 *
	 * @param int $maxSize Maximum file size in bytes
	 */
	public function setMaxFileSize($maxSize) {
		$this->maxFileSize = $maxSize;
	}

	/**
	 * Check if a file should be excluded from backup
	 *
	 * @param string $relativePath Relative path of the file
	 * @param int $fileSize File size in bytes
	 * @return bool True if file should be excluded
	 */
	private function shouldExcludeFile($relativePath, $fileSize = 0) {
		// Check directory exclusions
		foreach ($this->excludeDirs as $excludeDir) {
			if (strpos($relativePath, $excludeDir . '/') === 0 || 
				strpos($relativePath, $excludeDir . DIRECTORY_SEPARATOR) === 0) {
				return true;
			}
		}

		// Check file pattern exclusions
		$filename = basename($relativePath);
		foreach ($this->excludeFiles as $pattern) {
			if (fnmatch($pattern, $filename)) {
				return true;
			}
		}

		// Check file size limit
		if ($this->maxFileSize > 0 && $fileSize > $this->maxFileSize) {
			$this->messages[] = "Skipping large file: " . $relativePath . " (" . $this->formatFileSize($fileSize) . ")";
			return true;
		}

		return false;
	}

	/**
	 * Format file size for display
	 *
	 * @param int $bytes File size in bytes
	 * @return string Formatted file size
	 */
	private function formatFileSize($bytes) {
		$units = array('B', 'KB', 'MB', 'GB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
		return round($bytes, 2) . ' ' . $units[$pow];
	}

	/**
	 * Create a full backup of the source directory
	 *
	 * @param string $backupName Optional backup name (auto-generated if not provided)
	 * @param bool $useGzip Use gzip compression (ignored, always uses ZIP)
	 * @return string|false Backup file path on success, false on failure
	 */
	public function createBackup($backupName = null, $useGzip = true) {
		if (!$this->createBackupDirectory()) {
			return false;
		}

		// Generate backup name if not provided
		if ($backupName === null) {
			$backupName = 'backup-' . date('Y-m-d-H-i-s');
		}

		$extension = '.zip';
		$backupPath = $this->backupDir . '/' . $backupName . $extension;

		$this->messages[] = "Creating backup: " . $backupName . $extension;
		$this->messages[] = "Source directory: " . $this->sourceDir;

		try {
			// Use streaming ZIP backup approach
			return $this->createStreamingZipBackup($backupPath);

		} catch (Exception $e) {
			$this->errors[] = "Backup creation failed: " . $e->getMessage();
			return false;
		}
	}

	/**
	 * Create backup using streaming ZIP approach with ZipArchive
	 *
	 * @param string $backupPath Path to save backup file
	 * @return string|false Backup file path on success, false on failure
	 */
	private function createStreamingZipBackup($backupPath) {
		// Check if ZipArchive is available
		if (!class_exists('ZipArchive')) {
			$this->errors[] = "ZipArchive extension is not available. Please install php-zip extension.";
			return false;
		}

		$fileCount = 0;
		$totalSize = 0;

		// Create ZIP archive using ZipArchive for better performance
		$zip = new ZipArchive();
		$result = $zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		if ($result !== TRUE) {
			$this->errors[] = "Cannot create ZIP file: " . $backupPath . " (Error code: " . $result . ")";
			return false;
		}

		// Set execution time limit for large backups
		set_time_limit(0); // Remove time limit

		// Use RecursiveDirectoryIterator similar to icms_core_Filesystem::generateChecksum()
		$rootdir = preg_replace('#[\|/]#', DIRECTORY_SEPARATOR, $this->sourceDir);
		$dir = new RecursiveDirectoryIterator($rootdir, RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator = new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::LEAVES_ONLY);

		// Get normalized paths for exclusion checking (similar to Filesystem class)
		$cache_dir = preg_replace('#[\|/]#', DIRECTORY_SEPARATOR, ICMS_CACHE_PATH);
		$templates_dir = preg_replace('#[\|/]#', DIRECTORY_SEPARATOR, ICMS_COMPILE_PATH);

		foreach ($iterator as $name => $item) {
			if ($item->isFile()) {
				$filePath = $item->getPathname();
				$relativePath = $this->getRelativePath($filePath);
				$fileSize = $item->getSize();
				$itemPath = $item->getPath();

				// Use similar exclusion logic as icms_core_Filesystem::generateChecksum()
				// Skip cache and templates_c directories automatically
				if ($itemPath == $cache_dir || $itemPath == $templates_dir) {
					continue;
				}

				// Check if file should be excluded based on our custom rules
				if ($this->shouldExcludeFile($relativePath, $fileSize)) {
					continue;
				}

				// Add file directly to ZIP archive (streaming approach)
				if ($zip->addFile($filePath, $relativePath)) {
					$fileCount++;
					$totalSize += $fileSize;

					// Progress feedback for large backups
					if ($fileCount % 50 === 0) {
						$this->messages[] = "Processed " . $fileCount . " files (" . $this->formatFileSize($totalSize) . ")";
						// Flush output to show progress
						if (ob_get_level()) {
							ob_flush();
							flush();
						}
					}
				} else {
					$this->messages[] = "Warning: Could not add file to ZIP: " . $relativePath;
				}
			}
		}

		// Close the ZIP archive
		$result = $zip->close();

		if (!$result) {
			$this->errors[] = "Failed to finalize ZIP archive";
			return false;
		}

		// Verify the backup was created successfully
		if (file_exists($backupPath)) {
			$backupSize = filesize($backupPath);
			$this->messages[] = "Backup created successfully: " . basename($backupPath);
			$this->messages[] = "Files included: " . $fileCount;
			$this->messages[] = "Original size: " . $this->formatFileSize($totalSize);
			$this->messages[] = "Backup size: " . $this->formatFileSize($backupSize);
			$this->messages[] = "Compression ratio: " . round((1 - $backupSize / max($totalSize, 1)) * 100, 1) . "%";

			return $backupPath;
		} else {
			$this->errors[] = "Backup file was not created successfully";
			return false;
		}
	}

	/**
	 * Get relative path from absolute path
	 *
	 * @param string $absolutePath Absolute file path
	 * @return string Relative path
	 */
	private function getRelativePath($absolutePath) {
		$relativePath = str_replace($this->sourceDir . DIRECTORY_SEPARATOR, '', $absolutePath);
		$relativePath = str_replace('\\', '/', $relativePath); // Normalize path separators
		return $relativePath;
	}

	/**
	 * List available backups
	 *
	 * @return array Array of backup information
	 */
	public function listBackups() {
		$backups = array();

		if (!is_dir($this->backupDir)) {
			return $backups;
		}

		$files = glob($this->backupDir . '/*.zip');

		foreach ($files as $file) {
			$backups[] = array(
				'name' => basename($file),
				'path' => $file,
				'size' => filesize($file),
				'size_formatted' => $this->formatFileSize(filesize($file)),
				'created' => filemtime($file),
				'created_formatted' => date('Y-m-d H:i:s', filemtime($file))
			);
		}

		// Sort by creation time (newest first)
		usort($backups, function($a, $b) {
			return $b['created'] - $a['created'];
		});

		return $backups;
	}

	/**
	 * Delete a backup file
	 *
	 * @param string $backupName Backup filename
	 * @return bool Success status
	 */
	public function deleteBackup($backupName) {
		$backupPath = $this->backupDir . '/' . $backupName;

		if (!file_exists($backupPath)) {
			$this->errors[] = "Backup file not found: " . $backupName;
			return false;
		}

		if (!is_writable($backupPath)) {
			$this->errors[] = "Cannot delete backup file (permission denied): " . $backupName;
			return false;
		}

		// Use icms_core_Filesystem::deleteFile for consistency
		if (icms_core_Filesystem::deleteFile($backupPath)) {
			$this->messages[] = "Backup deleted successfully: " . $backupName;
			return true;
		} else {
			$this->errors[] = "Failed to delete backup file: " . $backupName;
			return false;
		}
	}

	/**
	 * Get backup directory path
	 *
	 * @return string Backup directory path
	 */
	public function getBackupDir() {
		return $this->backupDir;
	}

	/**
	 * Get source directory path
	 *
	 * @return string Source directory path
	 */
	public function getSourceDir() {
		return $this->sourceDir;
	}

	/**
	 * Get all error messages
	 *
	 * @param bool $asHtml Return as HTML
	 * @return mixed
	 */
	public function getErrors($asHtml = true) {
		if (!$asHtml) {
			return $this->errors;
		}

		$ret = '';
		foreach ($this->errors as $error) {
			$ret .= $error . '<br />';
		}
		return $ret;
	}

	/**
	 * Get all status messages
	 *
	 * @param bool $asHtml Return as HTML
	 * @return mixed
	 */
	public function getMessages($asHtml = true) {
		if (!$asHtml) {
			return $this->messages;
		}

		$ret = '';
		foreach ($this->messages as $message) {
			$ret .= $message . '<br />';
		}
		return $ret;
	}

	/**
	 * Clear all errors and messages
	 */
	public function clearMessages() {
		$this->errors = array();
		$this->messages = array();
	}

	/**
	 * Generate a checksum file for the source directory
	 * Uses the same logic as icms_core_Filesystem::generateChecksum()
	 *
	 * @param string $checksumFile Path to save the checksum file
	 * @return bool Success status
	 */
	public function generateChecksum($checksumFile = null) {
		if ($checksumFile === null) {
			$checksumFile = $this->backupDir . '/checksum-' . date('Y-m-d-H-i-s') . '.sha1';
		}

		try {
			$rootdir = preg_replace('#[\|/]#', DIRECTORY_SEPARATOR, $this->sourceDir);
			$dir = new RecursiveDirectoryIterator($rootdir);

			$file = new SplFileObject($checksumFile, 'w');
			$cache_dir = preg_replace('#[\|/]#', DIRECTORY_SEPARATOR, ICMS_CACHE_PATH);
			$templates_dir = preg_replace('#[\|/]#', DIRECTORY_SEPARATOR, ICMS_COMPILE_PATH);
			$newline = '';

			foreach (new RecursiveIteratorIterator($dir) as $name => $item) {
				$itemPath = $item->getPath();
				$itemFilename = $item->getBasename();
				$itemPerms = $item->getPerms();

				// Exclude cache and templates_c directories (same as Filesystem class)
				if ($itemPath != $cache_dir && $itemPath != $templates_dir) {
					$relativePath = $this->getRelativePath($name);

					// Apply our custom exclusion rules
					if (!$this->shouldExcludeFile($relativePath, $item->getSize())) {
						$fileHash = sha1_file($name);
						$file->fwrite($newline . $name . ';' . $fileHash . ';' . $itemPerms);
						$newline = "\n";
					}
				}
			}

			unset($file);
			unset($item);
			unset($dir);

			$this->messages[] = "Checksum file created: " . basename($checksumFile);
			return true;

		} catch (Exception $e) {
			$this->errors[] = "Failed to generate checksum: " . $e->getMessage();
			return false;
		}
	}

	/**
	 * Check if user has permission to create backups
	 *
	 * @return bool
	 */
	public static function canCreateBackup() {
		return is_object(icms::$user) && icms::$user->isAdmin();
	}
}

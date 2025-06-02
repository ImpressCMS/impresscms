<?php
/**
 * ImpressCMS Backup Manager
 *
 * Backup management interface for ImpressCMS
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		1.4
 * @author		ImpressCMS Development Team
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../../../../mainfile.php';
include_once ICMS_ROOT_PATH . '/include/cp_functions.php';

// Security check
if (!is_object(icms::$user) || !is_object(icms::$module) || !icms::$user->isAdmin()) {
	exit("Access Denied");
}

// Handle actions
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$backup = new icms_core_Backup();
$message = '';
$error = '';

switch ($action) {
	case 'create':
		if (icms_core_Backup::canCreateBackup()) {
			$backupName = isset($_POST['backup_name']) && !empty(trim($_POST['backup_name']))
				? trim($_POST['backup_name'])
				: 'manual-backup-' . date('Y-m-d-H-i-s');

			$backupPath = $backup->createBackup($backupName, true);

			if ($backupPath) {
				$message = "Backup created successfully: " . basename($backupPath);
			} else {
				$error = "Failed to create backup: " . $backup->getErrors(true);
			}
		} else {
			$error = "Permission denied";
		}
		break;

	case 'checksum':
		if (icms_core_Backup::canCreateBackup()) {
			if ($backup->generateChecksum()) {
				$message = "Checksum file generated successfully";
			} else {
				$error = "Failed to generate checksum: " . $backup->getErrors(true);
			}
		} else {
			$error = "Permission denied";
		}
		break;

	case 'delete':
		if (icms_core_Backup::canCreateBackup() && isset($_GET['file'])) {
			$filename = basename($_GET['file']); // Security: only filename, no path

			if ($backup->deleteBackup($filename)) {
				$message = "Backup deleted successfully: " . $filename;
			} else {
				$error = "Failed to delete backup: " . $backup->getErrors(true);
			}
		} else {
			$error = "Permission denied or invalid file";
		}
		break;

	case 'restore':
		if (icms_core_Backup::canRestoreBackup() && isset($_POST['backup_file'])) {
			$filename = basename($_POST['backup_file']); // Security: only filename, no path
			$createPreBackup = isset($_POST['create_pre_backup']) ? (bool)$_POST['create_pre_backup'] : true;

			if ($backup->restoreBackup($filename, $createPreBackup)) {
				$message = "Backup restored successfully: " . $filename;
			} else {
				$error = "Failed to restore backup: " . $backup->getErrors(true);
			}
		} else {
			$error = "Permission denied or invalid backup file";
		}
		break;

	case 'info':
		if (isset($_GET['file'])) {
			$filename = basename($_GET['file']); // Security: only filename, no path
			$backupInfo = $backup->getBackupInfo($filename);
			$backupContents = $backup->listBackupContents($filename, 50); // Show first 50 files
		}
		break;
}

// Get list of backups
$backups = $backup->listBackups();

icms_cp_header();
?>

<div class="CPbigTitle" style="background-image: url(<?php echo ICMS_URL; ?>/modules/system/admin/backup/images/backup_big.png)">Backup Manager</div><br />

<?php if ($message): ?>
<div class="successMsg"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="errorMsg"><?php echo $error; ?></div>
<?php endif; ?>

<div class="head" style="padding: 2px; margin-bottom: 5px;">
	<strong>Create New Backup</strong>
</div>

<div class="even">
	<?php if (icms_core_Backup::canCreateBackup()): ?>
	<form method="post" action="">
		<input type="hidden" name="action" value="create" />
		<div style="margin-bottom: 10px;">
			<label for="backup_name">Backup Name (optional):</label><br />
			<input type="text" name="backup_name" id="backup_name" placeholder="Leave empty for auto-generated name" style="width: 300px;" />
		</div>
		<input type="submit" value="Create Backup" class="formButton" onclick="return confirm('This may take several minutes. Continue?');" />
	</form>

	<form method="post" action="" style="margin-top: 10px;">
		<input type="hidden" name="action" value="checksum" />
		<input type="submit" value="Generate Checksum File" class="formButton" onclick="return confirm('Generate a checksum file for integrity verification?');" />
	</form>
	<?php else: ?>
	<div class="errorMsg">You do not have permission to create backups.</div>
	<?php endif; ?>
</div>

<div class="head" style="padding: 2px; margin-bottom: 5px; margin-top: 20px;">
	<strong>Available Backups (<?php echo count($backups); ?>)</strong>
</div>

<div class="odd">
	<?php if (empty($backups)): ?>
		<p>No backups found.</p>
	<?php else: ?>
		<table style="width: 100%; border-collapse: collapse;">
			<thead>
				<tr style="background-color: #f0f0f0;">
					<th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Backup Name</th>
					<th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Size</th>
					<th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Created</th>
					<th style="padding: 8px; border: 1px solid #ddd; text-align: center;">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($backups as $backupInfo): ?>
				<tr>
					<td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($backupInfo['name']); ?></td>
					<td style="padding: 8px; border: 1px solid #ddd;"><?php echo $backupInfo['size_formatted']; ?></td>
					<td style="padding: 8px; border: 1px solid #ddd;"><?php echo $backupInfo['created_formatted']; ?></td>
					<td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
						<?php if (icms_core_Backup::canRestoreBackup()): ?>
						<a href="?action=info&file=<?php echo urlencode($backupInfo['name']); ?>" style="color: blue;">Info</a> |
						<a href="javascript:void(0);" onclick="showRestoreForm('<?php echo htmlspecialchars($backupInfo['name'], ENT_QUOTES); ?>');" style="color: green;">Restore</a>
						<?php endif; ?>
						<?php if (icms_core_Backup::canCreateBackup()): ?>
						| <a href="?action=delete&file=<?php echo urlencode($backupInfo['name']); ?>"
						   onclick="return confirm('Are you sure you want to delete this backup?');"
						   style="color: red;">Delete</a>
						<?php else: ?>
						<span style="color: #999;">No permission</span>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>

<div class="head" style="padding: 2px; margin-bottom: 5px; margin-top: 20px;">
	<strong>Backup Information</strong>
</div>

<div class="even">
	<p><strong>Backup Directory:</strong> <?php echo htmlspecialchars($backup->getBackupDir()); ?></p>
	<p><strong>Source Directory:</strong> <?php echo htmlspecialchars($backup->getSourceDir()); ?></p>
	<p><strong>Backup Format:</strong> ZIP (compressed)</p>
	<p><strong>Excluded Directories:</strong> cache, uploads, templates_c, backups</p>
	<p><strong>Excluded Files:</strong> *.log, *.tmp, .DS_Store, Thumbs.db</p>
	<p><strong>Maximum File Size:</strong> 50MB</p>
</div>

<!-- Restore Form (Hidden by default) -->
<div id="restoreForm" style="display: none; margin-top: 20px;">
	<div class="head" style="padding: 2px; margin-bottom: 5px;">
		<strong>Restore Backup</strong>
	</div>
	<div class="even">
		<form method="post" action="" onsubmit="return confirmRestore();">
			<input type="hidden" name="action" value="restore" />
			<input type="hidden" name="backup_file" id="restore_backup_file" value="" />

			<p><strong>Selected Backup:</strong> <span id="restore_backup_name"></span></p>

			<div style="margin: 10px 0;">
				<label>
					<input type="checkbox" name="create_pre_backup" value="1" checked="checked" />
					Create a backup before restoring (recommended)
				</label>
			</div>

			<div style="margin: 10px 0;">
				<strong style="color: red;">Warning:</strong> This will overwrite your current installation files.
				Make sure you have a recent backup before proceeding.
			</div>

			<input type="submit" value="Restore Backup" class="formButton" style="background-color: #ff6600;" />
			<input type="button" value="Cancel" class="formButton" onclick="hideRestoreForm();" />
		</form>
	</div>
</div>

<?php if (isset($backupInfo) && $backupInfo): ?>
<!-- Backup Information Display -->
<div class="head" style="padding: 2px; margin-bottom: 5px; margin-top: 20px;">
	<strong>Backup Information: <?php echo htmlspecialchars($backupInfo['name']); ?></strong>
</div>

<div class="odd">
	<p><strong>File:</strong> <?php echo htmlspecialchars($backupInfo['name']); ?></p>
	<p><strong>Size:</strong> <?php echo $backupInfo['size_formatted']; ?></p>
	<p><strong>Created:</strong> <?php echo $backupInfo['created_formatted']; ?></p>
	<p><strong>Files:</strong> <?php echo number_format($backupInfo['files']); ?></p>
	<p><strong>Valid ZIP:</strong> <?php echo $backupInfo['valid_zip'] ? 'Yes' : 'No'; ?></p>

	<?php if (isset($backupContents) && $backupContents): ?>
	<h4>Contents (showing first <?php echo $backupContents['showing']; ?> of <?php echo number_format($backupContents['total_files']); ?> files):</h4>
	<div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background-color: #f9f9f9;">
		<?php foreach ($backupContents['files'] as $file): ?>
			<?php if (!$file['is_directory']): ?>
			<div style="margin: 2px 0; font-family: monospace; font-size: 12px;">
				<?php echo htmlspecialchars($file['name']); ?>
				<span style="color: #666;">(<?php echo $file['size_formatted']; ?>)</span>
			</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if ($backupContents['truncated']): ?>
		<div style="margin-top: 10px; font-style: italic; color: #666;">
			... and <?php echo number_format($backupContents['total_files'] - $backupContents['showing']); ?> more files
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<p style="margin-top: 15px;">
		<a href="?" class="formButton">← Back to Backup List</a>
	</p>
</div>
<?php endif; ?>

<script type="text/javascript">
function showRestoreForm(backupName) {
	document.getElementById('restore_backup_file').value = backupName;
	document.getElementById('restore_backup_name').textContent = backupName;
	document.getElementById('restoreForm').style.display = 'block';
	document.getElementById('restoreForm').scrollIntoView();
}

function hideRestoreForm() {
	document.getElementById('restoreForm').style.display = 'none';
}

function confirmRestore() {
	var backupName = document.getElementById('restore_backup_name').textContent;
	var createPreBackup = document.querySelector('input[name="create_pre_backup"]').checked;

	var message = 'Are you sure you want to restore from backup "' + backupName + '"?\n\n';
	message += 'This will overwrite your current installation files.\n';

	if (createPreBackup) {
		message += '\nA backup of your current installation will be created first.';
	} else {
		message += '\nWARNING: No backup will be created before restoring!';
	}

	message += '\n\nThis operation may take several minutes to complete.';

	return confirm(message);
}
</script>

<?php
icms_cp_footer();
?>

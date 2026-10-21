<?php
/**
 * Installer page: install the host files into the trust path
 *
 * The package carries the Symfony host (host.php, composer.json, vendor/, src/,
 * config/, …) in the staging directory trust_dist/ of the web root. This page
 * copies it into the trust path chosen in the previous step, so that no
 * third-party code stays in the public web root, and removes the staging
 * directory (see icms_core_Filesystem::installTrustDistribution()).
 *
 * GET  – shows what will happen when Next is clicked.
 * POST – installs the files and continues, or stays on this page with the
 *        error and manual instructions.
 *
 * @copyright    The ImpressCMS Project https://www.impresscms.org/
 * @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package      installer
 * @since        2.0
 */

require_once "common.inc.php";

if (!defined("XOOPS_INSTALL")) {
	exit();
}

$wizard->setPage("movevendor");
$pageHasForm = true;
$pageHasHelp = false;

// ─── Guard: paths must already be set by page_pathsettings.php ────────────────
if (
	empty($_SESSION["settings"]["ROOT_PATH"]) ||
	empty($_SESSION["settings"]["TRUST_PATH"])
) {
	ob_start();
	echo '<p class="errorMsg">' . VENDOR_MOVE_SESSION_ERROR . "</p>";
	$content = ob_get_clean();
	include "install_tpl.php";
	exit();
}

$rootPath = rtrim(str_replace("\\", "/", $_SESSION["settings"]["ROOT_PATH"]), "/");
$trustPath = rtrim(str_replace("\\", "/", $_SESSION["settings"]["TRUST_PATH"]), "/");
$stagingPath = $rootPath . "/" . icms_core_Filesystem::TRUST_DISTRIBUTION_DIR;

// ─── POST: install ────────────────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	// This request loaded its classes from the staging directory: the verified
	// copy is activated, and the staging directory removed, when it ends.
	$result = icms_core_Filesystem::installTrustDistribution($stagingPath, $trustPath, $rootPath, true);

	if (in_array($result["status"], ["ok", "skipped"], true)) {
		$_SESSION["settings"]["VENDOR_MOVED"] = true;
		$wizard->redirectToPage("+1");
		exit();
	}

	ob_start();
	echo '<div class="blokz"><p class="errorMsg">' .
		nl2br(htmlspecialchars($result["message"], ENT_QUOTES)) .
		'</p><div class="errorMsg" style="margin-top:8px;">' .
		VENDOR_MOVE_MANUAL_INSTRUCTIONS .
		"</div></div>";
	$content = ob_get_clean();
	include "install_tpl.php";
	exit();
}

// ─── GET: preview ─────────────────────────────────────────────────────────────
if (is_dir($stagingPath)) {
	$message = is_file($trustPath . "/host.php") ? VENDOR_MOVE_CONFLICT : VENDOR_MOVE_READY;
	$class = "x2-note";
} elseif (is_file($trustPath . "/host.php")) {
	$message = VENDOR_ALREADY_IN_TRUST;
	$class = "x2-note";
} else {
	$message = VENDOR_MOVE_NOT_FOUND;
	$class = "errorMsg";
}

ob_start();
echo "<p>" . VENDOR_MOVE_INTRO . "</p>";
echo '<div class="blokz"><p class="' . $class . '">' . $message . "</p>";
echo "<dl><dt><strong>" . VENDOR_MOVE_SOURCE . "</strong></dt><dd><code>" .
	htmlspecialchars($stagingPath, ENT_QUOTES) .
	"</code></dd><dt><strong>" . VENDOR_MOVE_DESTINATION . "</strong></dt><dd><code>" .
	htmlspecialchars($trustPath, ENT_QUOTES) .
	"</code></dd></dl>";
if ($class === "errorMsg") {
	echo '<div class="errorMsg" style="margin-top:8px;">' . VENDOR_MOVE_MANUAL_INSTRUCTIONS . "</div>";
}
echo "</div>";
$content = ob_get_clean();
include "install_tpl.php";

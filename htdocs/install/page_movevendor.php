<?php
/**
 * Installer vendor directory move page
 *
 * Moves the Composer vendor directory from the web-accessible root path into the
 * secure trust path that was configured in the previous step.  This keeps all
 * third-party Composer libraries out of the public web root, which is strongly
 * recommended on shared hosting environments.
 *
 * Behaviour summary
 * -----------------
 * GET  – Analyses the current state (vendor present in web root? in trust path?)
 *        and shows a preview message describing what will happen when Next is clicked.
 * POST – Executes the move (copy + delete), records the result in the session, and
 *        either redirects to the next installer step or stays on this page with a
 *        clear error message and manual-remediation instructions.
 *
 * Session flags written
 * ---------------------
 * $_SESSION['settings']['VENDOR_MOVED'] = true
 *   Set on success (or when no action is needed).  Used by common.inc.php on every
 *   subsequent installer page to load the Composer autoloader from the trust path.
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

// ─── Resolve canonical paths (forward-slash, no trailing slash) ───────────────
$rootPath = rtrim(
	str_replace("\\", "/", $_SESSION["settings"]["ROOT_PATH"]),
	"/",
);
$trustPath = rtrim(
	str_replace("\\", "/", $_SESSION["settings"]["TRUST_PATH"]),
	"/",
);
$srcVendor = $rootPath . "/vendor";
$destVendor = $trustPath . "/vendor";

// ─── POST: execute the move ───────────────────────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	/*
	 * Raise the execution time limit – copying a large vendor tree can take a while.
	 * On shared hosting set_time_limit() may be a no-op but we call it anyway; if
	 * execution does time out the user can simply reload and the move will be retried
	 * (see the conflict / partial-copy handling in icms_core_Filesystem::moveVendorToTrust()).
	 */
	@set_time_limit(300);

	$result = icms_core_Filesystem::moveVendorToTrust($rootPath, $trustPath);

	if (in_array($result["status"], ["ok", "skipped", "novendor"], true)) {
		// Mark vendor as moved (or acknowledged missing) so common.inc.php
		// knows where to find the autoloader on every subsequent page load.
		$_SESSION["settings"]["VENDOR_MOVED"] = true;
		$wizard->redirectToPage("+1");
		exit();
	}

	// An unrecoverable error (e.g. copy failed, conflict) – stay on this page.
	ob_start();
	echo buildResultHtml($result);
	$content = ob_get_clean();
	include "install_tpl.php";
	exit();
}

// ─── GET: show preview ────────────────────────────────────────────────────────
$preview = previewMoveVendor($srcVendor, $destVendor);
ob_start();
echo buildPreviewHtml($preview, $srcVendor, $destVendor);
$content = ob_get_clean();
include "install_tpl.php";

// ═══════════════════════════════════════════════════════════════════════════════
// Helper functions
// ═══════════════════════════════════════════════════════════════════════════════

/**
 * Analyse the current state WITHOUT making any changes.
 *
 * Possible status values returned:
 *   'ready'    – vendor exists in web root, not yet in trust path → will move.
 *   'skipped'  – vendor already in trust path (with same autoload.php hash, or
 *                source is absent) → nothing to do.
 *   'conflict' – vendor exists in BOTH locations with different autoload.php
 *                hashes → clicking Next will overwrite the trust-path copy.
 *   'novendor' – vendor found in neither location → warn the user.
 *
 * @param  string $srcVendor   Absolute path to htdocs/vendor
 * @param  string $destVendor  Absolute path to <trustpath>/vendor
 * @return array{status: string, message: string}
 */
function previewMoveVendor(string $srcVendor, string $destVendor): array
{
	$srcExists = is_dir($srcVendor);
	$destExists = is_dir($destVendor);

	if (!$srcExists && !$destExists) {
		return ["status" => "novendor", "message" => VENDOR_MOVE_NOT_FOUND];
	}

	if (!$srcExists) {
		// Vendor is already only in the trust path – nothing to do.
		return ["status" => "skipped", "message" => VENDOR_ALREADY_IN_TRUST];
	}

	if ($destExists) {
		// Both exist – compare autoload.php to detect same vs. different contents.
		$destAutoload = $destVendor . "/autoload.php";

		if (!file_exists($destAutoload)) {
			// Destination looks like an incomplete previous copy; treat as conflict
			// so the user is warned and the copy will overwrite on POST.
			return ["status" => "conflict", "message" => VENDOR_MOVE_CONFLICT];
		}

		$srcHash = @sha1_file($srcVendor . "/autoload.php");
		$destHash = @sha1_file($destAutoload);

		if (
			$srcHash !== false &&
			$destHash !== false &&
			$srcHash === $destHash
		) {
			// Contents appear identical – the move was already done (or the user
			// placed an identical vendor copy here manually).
			return [
				"status" => "skipped",
				"message" => VENDOR_ALREADY_IN_TRUST,
			];
		}

		return ["status" => "conflict", "message" => VENDOR_MOVE_CONFLICT];
	}

	// Source exists, destination does not → standard case, ready to move.
	return ["status" => "ready", "message" => VENDOR_MOVE_READY];
}

/**
 * Build the HTML shown on the GET (preview) request.
 *
 * @param  array  $preview     Return value of previewMoveVendor()
 * @param  string $srcVendor   Source path (for display only)
 * @param  string $destVendor  Destination path (for display only)
 * @return string
 */
function buildPreviewHtml(
	array $preview,
	string $srcVendor,
	string $destVendor,
): string {
	$isError = in_array($preview["status"], ["novendor"], true);
	$msgClass = $isError ? "errorMsg" : "x2-note";

	$html = "<p>" . VENDOR_MOVE_INTRO . "</p>";
	$html .=
		'<div class="blokz"><p class="' .
		$msgClass .
		'">' .
		$preview["message"] .
		"</p>";

	// Show the actual paths so the user can verify them.
	if ($preview["status"] === "ready" || $preview["status"] === "conflict") {
		$html .= "<dl>";
		$html .= "<dt><strong>Source (web root):</strong></dt>";
		$html .=
			"<dd><code>" .
			htmlspecialchars($srcVendor, ENT_QUOTES) .
			"</code></dd>";
		$html .= "<dt><strong>Destination (trust path):</strong></dt>";
		$html .=
			"<dd><code>" .
			htmlspecialchars($destVendor, ENT_QUOTES) .
			"</code></dd>";
		$html .= "</dl>";
	}

	if ($preview["status"] === "conflict") {
		$html .=
			'<div class="errorMsg" style="margin-top:8px;">' .
			VENDOR_MOVE_MANUAL_INSTRUCTIONS .
			"</div>";
	}

	if ($preview["status"] === "novendor") {
		$html .=
			'<div class="errorMsg" style="margin-top:8px;">' .
			VENDOR_MOVE_MANUAL_INSTRUCTIONS .
			"</div>";
	}

	$html .= "</div>";
	return $html;
}

/**
 * Build the HTML shown when POST returns an error result.
 *
 * @param  array $result  Return value of icms_core_Filesystem::moveVendorToTrust() with a non-ok status
 * @return string
 */
function buildResultHtml(array $result): string
{
	// Split the message into the human-readable summary (first line) and the
	// optional multi-line debug detail that follows the first blank line.
	$rawMessage = $result["message"];
	$nlPos = strpos($rawMessage, "\n\n");

	if ($nlPos !== false) {
		$summary = substr($rawMessage, 0, $nlPos);
		$detail = substr($rawMessage, $nlPos + 2);
	} else {
		$summary = $rawMessage;
		$detail = "";
	}

	$html = '<div class="blokz">';
	$html .=
		'<p class="errorMsg">' .
		htmlspecialchars($summary, ENT_QUOTES) .
		"</p>";

	if ($detail !== "") {
		$html .=
			'<pre style="background:#f8f8f8;border:1px solid #ccc;padding:8px;' .
			'overflow:auto;font-size:0.85em;white-space:pre-wrap;word-break:break-all;">' .
			htmlspecialchars($detail, ENT_QUOTES) .
			"</pre>";
	}

	$html .=
		'<div class="errorMsg" style="margin-top:8px;">' .
		VENDOR_MOVE_MANUAL_INSTRUCTIONS .
		"</div>";
	$html .= "</div>";
	return $html;
}

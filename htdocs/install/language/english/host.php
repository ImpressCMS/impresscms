<?php
/**
 * Installer strings for the host setup page (page_movevendor.php).
 *
 * A separate file, so that languages without a translation fall back to
 * English (IcmsInstallWizard::loadLangFile()).
 *
 * @copyright    The ImpressCMS Project https://www.impresscms.org/
 * @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package      installer
 */

define("MOVE_VENDOR", "Host setup");
define("MOVE_VENDOR_TITLE", "Installing the host files into the trust path");
define(
	"VENDOR_MOVE_INTRO",
	"ImpressCMS will now install its host files (the application and the Composer libraries in <code>vendor</code>) from the package's <code>trust_dist</code> directory into your trust path. Nothing of it stays in the web-accessible root.",
);
define(
	"VENDOR_MOVE_READY",
	"The host files will be installed into your trust path. Click <em>Next</em> to proceed.",
);
define(
	"VENDOR_ALREADY_IN_TRUST",
	"The host files are already installed in the trust path. No action is required; click <em>Next</em> to continue.",
);
define(
	"VENDOR_MOVE_CONFLICT",
	"The trust path already contains host files. Clicking <em>Next</em> replaces them with the files of this package; <code>var</code> and <code>.env.local</code> are kept.",
);
define(
	"VENDOR_MOVE_NOT_FOUND",
	"<strong>Warning:</strong> The package's <code>trust_dist</code> directory was not found in the web root, and the trust path contains no host files. Upload the complete package, including <code>trust_dist</code>, then reload this page.",
);
define("VENDOR_MOVE_SOURCE", "From (web root):");
define("VENDOR_MOVE_DESTINATION", "To (trust path):");
define(
	"VENDOR_MOVE_MANUAL_INSTRUCTIONS",
	"<strong>Manual remediation steps:</strong><ol><li>Connect to your server via FTP or SFTP.</li><li>Upload the contents of the package's <code>trust_dist</code> folder into your trust path folder.</li><li>The result should be: <code>&lt;your-trust-path&gt;/host.php</code> and <code>&lt;your-trust-path&gt;/vendor/autoload.php</code></li><li>Delete <code>trust_dist</code> from the web root, return here and click <em>Next</em>.</li></ol>",
);
define(
	"VENDOR_MOVE_SESSION_ERROR",
	"Could not read the installation paths from the session. Please go back to the <em>Paths settings</em> step and verify your settings.",
);

// Path settings page: trust path under the document root
define(
	"TRUST_PATH_PUBLIC_WARNING",
	"<strong>Warning:</strong> this trust path lies inside the web root or the document root. It will hold the host files, the Composer libraries and the site's secret settings, and web servers such as nginx or Caddy (FrankenPHP) serve it regardless of <code>.htaccess</code> files. Choose a folder outside the document root, for example next to it.",
);
define("TRUST_PATH_PUBLIC_CONFIRM", "I understand the risk and have blocked web access to this folder myself.");

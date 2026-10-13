<?php
/**
 * Staged ImpressCMS bootstrap
 *
 * @copyright	The ImpressCMS Project https://www.impresscms.org/
 * @license		https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU GPL v2
 * @package		ICMS\Kernel
 */

namespace Icms\Kernel;

/**
 * The ImpressCMS boot sequence of include/common.php, split into ordered stages.
 *
 * The stages follow the order of the former include/common.php and contain its
 * logic unchanged:
 *
 *  1. constants      mainfile.php (headless mode only) and include/constants.php
 *  2. autoload       the Composer autoloader (skipped when the host defines
 *                    ICMS_HOST_AUTOLOADED) and the core function libraries
 *  3. services       icms::setup() and icms::boot(): services, session, user
 *  4. output         gzip/zlib output compression
 *  5. language       time zone, core and system language files, page type file
 *  6. themeSelection theme_set from POST or session
 *  7. siteState      the `closesite` check
 *  8. module         icms::launchModule()
 *  9. online         online-user bookkeeping and the finishCoreBoot event
 *
 * Stages are idempotent and run at most once per process; running a stage first
 * runs the stages before it.
 *
 * In interactive mode (include/common.php) a closed site raises
 * SiteClosedException, and common.php renders include/site-closed.php at global
 * scope, which lets permitted visitors through and exits for everybody else.
 *
 * In headless mode (a host application that boots ImpressCMS without rendering a
 * legacy page) nothing is sent and nothing exits:
 *  - `constants` includes mainfile.php itself, with $xoopsOption['nocommon'], and
 *    throws NotInstalledException when there is no installed mainfile.php
 *  - `output` leaves response encoding to the host
 *  - `siteState` throws SiteClosedException only when the visitor may not bypass
 *    the closed site
 */
final class Bootstrap
{
	public const MODE_INTERACTIVE = 'interactive';
	public const MODE_HEADLESS = 'headless';

	public const STAGES = [
		'constants',
		'autoload',
		'services',
		'output',
		'language',
		'themeSelection',
		'siteState',
		'module',
		'online',
	];

	/**
	 * Stages completed in this process; legacy state is process-wide.
	 *
	 * @var array<string, true>
	 */
	private static array $completed = [];

	/**
	 * @param string      $mode     MODE_INTERACTIVE or MODE_HEADLESS
	 * @param string|null $mainfile mainfile.php to include in headless mode
	 */
	public function __construct(
		private readonly string $mode = self::MODE_INTERACTIVE,
		private readonly ?string $mainfile = null,
	) {
		if ($mode !== self::MODE_INTERACTIVE && $mode !== self::MODE_HEADLESS) {
			throw new \InvalidArgumentException(sprintf('Unknown bootstrap mode "%s".', $mode));
		}
	}

	public function mode(): string
	{
		return $this->mode;
	}

	/**
	 * Run every stage.
	 */
	public function runAll(): void
	{
		$this->runTo(self::STAGES[count(self::STAGES) - 1]);
	}

	/**
	 * Run all stages up to and including $stage.
	 */
	public function runTo(string $stage): void
	{
		self::assertStage($stage);
		foreach (self::STAGES as $name) {
			$this->run($name);
			if ($name === $stage) {
				return;
			}
		}
	}

	/**
	 * Run one stage, after the stages before it. A completed stage is not run again.
	 */
	public function run(string $stage): void
	{
		self::assertStage($stage);
		if (isset(self::$completed[$stage])) {
			return;
		}
		foreach (self::STAGES as $name) {
			if ($name === $stage) {
				break;
			}
			$this->run($name);
		}

		$this->{$stage}();
		self::$completed[$stage] = true;
	}

	public static function isCompleted(string $stage): bool
	{
		self::assertStage($stage);

		return isset(self::$completed[$stage]);
	}

	/**
	 * @return list<string>
	 */
	public static function completedStages(): array
	{
		return array_values(array_filter(self::STAGES, static fn (string $s): bool => isset(self::$completed[$s])));
	}

	/**
	 * Record that the caller handled a stage itself, for example include/common.php
	 * after rendering include/site-closed.php for a permitted visitor.
	 */
	public static function markCompleted(string $stage): void
	{
		self::assertStage($stage);
		self::$completed[$stage] = true;
	}

	// -- Stages ----------------------------------------------------------------

	private function constants(): void
	{
		if (!defined('XOOPS_MAINFILE_INCLUDED')) {
			if ($this->mode !== self::MODE_HEADLESS) {
				throw new \LogicException('mainfile.php must be included before an interactive boot.');
			}
			self::includeInGlobalScope($this->installedMainfile(), ['nocommon' => true]);
			if (!defined('ICMS_ROOT_PATH') || ICMS_ROOT_PATH === '') {
				throw new NotInstalledException('mainfile.php does not define ICMS_ROOT_PATH.');
			}
		}

		// -- Include common functions and constants file
		self::includeInGlobalScope(ICMS_ROOT_PATH . "/include/constants.php");
	}

	private function autoload(): void
	{
		// A host application that already loaded a single autoloader for the whole
		// process defines ICMS_HOST_AUTOLOADED; then no second vendor tree is loaded.
		if (!defined("ICMS_HOST_AUTOLOADED")) {
			// Load Composer autoloader - prefer trust path location for security.
			// After installation, the vendor directory lives in ICMS_TRUST_PATH (outside
			// the web root).  Fall back to ICMS_ROOT_PATH for pre-install or legacy setups.
			$_icms_autoload_from_trustpath = false;
			if (file_exists(ICMS_TRUST_PATH . "/vendor/autoload.php")) {
				$_icms_autoload = ICMS_TRUST_PATH . "/vendor/autoload.php";
				$_icms_autoload_from_trustpath = true;
			} elseif (file_exists(ICMS_ROOT_PATH . "/vendor/autoload.php")) {
				$_icms_autoload = ICMS_ROOT_PATH . "/vendor/autoload.php";
			} else {
				$_icms_autoload = null;
			}

			if ($_icms_autoload === null) {
				die(
					"<h1>ImpressCMS – Composer autoloader not found</h1>" .
						"<p>The Composer autoloader (<code>vendor/autoload.php</code>) could not be located in any of the expected paths:</p>" .
						"<ul>" .
						"<li><code>" .
						htmlspecialchars(
							ICMS_TRUST_PATH . "/vendor/autoload.php",
							ENT_QUOTES,
							"UTF-8",
						) .
						"</code></li>" .
						"<li><code>" .
						htmlspecialchars(
							ICMS_ROOT_PATH . "/vendor/autoload.php",
							ENT_QUOTES,
							"UTF-8",
						) .
						"</code></li>" .
						"</ul>" .
						"<p>To resolve this, run <code>composer install</code> inside the ImpressCMS root directory, " .
						"or re-upload a complete ImpressCMS package that includes the <code>vendor/</code> directory.</p>"
				);
			}

			if ($_icms_autoload_from_trustpath) {
				$_icms_bridge_path = ICMS_TRUST_PATH . "/libraries/Autoloader.php";
				if (!is_file($_icms_bridge_path)) {
					$_icms_bridge_dir = dirname($_icms_bridge_path);
					if (is_dir($_icms_bridge_dir) || @mkdir($_icms_bridge_dir, 0775, true)) {
						$_icms_bridge_content =
							"<?php\nrequire_once " .
							var_export(ICMS_ROOT_PATH . "/libraries/Autoloader.php", true) .
							";\n";
						@file_put_contents($_icms_bridge_path, $_icms_bridge_content);
						unset($_icms_bridge_content);
					}
					unset($_icms_bridge_dir);
				}
				if (!is_file($_icms_bridge_path)) {
					die(
						"<h1>ImpressCMS - Trust path is not writable</h1>" .
							"<p>Unable to create required file: <code>" .
							htmlspecialchars($_icms_bridge_path, ENT_QUOTES, "UTF-8") .
							"</code></p>" .
							"<p>Grant write permissions to the trust path and reload this page.</p>"
					);
				}
				unset($_icms_bridge_path);
			}

			require_once $_icms_autoload;
			unset($_icms_autoload);

			// When vendor lives in the trust path the Composer-generated autoloader files
			// compute $baseDir as dirname(dirname(__DIR__)) relative to trustpath/vendor/,
			// which resolves to trustpath/ instead of the web root.  Every icms_* class
			// lookup therefore targets trustpath/libraries/ – a directory that does not
			// exist because libraries/ always stays in ICMS_ROOT_PATH.
			//
			// Register a prepended SPL autoloader (runs before Composer's now-broken one)
			// that maps all three categories of ImpressCMS-native classes to the correct
			// ICMS_ROOT_PATH/libraries location:
			//
			//   "icms"     (classmap entry)  →  libraries/icms.php
			//   "icms_*"   (PSR-0 style)     →  libraries/<underscore/separated/path>.php
			//   "Icms\*"   (PSR-4 style)     →  libraries/Icms/<Namespace/Path>.php
			if ($_icms_autoload_from_trustpath) {
				// libraries/Autoloader.php resolves the "icms" kernel class, legacy icms_* names
				// (via libraries/Icms/aliases.php) and Icms\ classes from ICMS_ROOT_PATH. Loading it
				// here guarantees it is registered even when vendor/ lives in the trust path.
				require_once ICMS_ROOT_PATH . "/libraries/Autoloader.php";
			}
			unset($_icms_autoload_from_trustpath);
		}

		self::includeInGlobalScope(ICMS_INCLUDE_PATH . "/functions.php");
		self::includeInGlobalScope(ICMS_INCLUDE_PATH . "/debug_functions.php");
		self::includeInGlobalScope(ICMS_INCLUDE_PATH . "/version.php");
	}

	private function services(): void
	{
		global $xoopsOption;

		if (!isset($xoopsOption)) {
			$xoopsOption = [];
		}

		// load core language file before the initialization of the boot sequence
		icms_loadLanguageFile("core", "theme");

		// -- Initialize kernel and launch bootstrap
		require_once ICMS_LIBRARIES_PATH . "/icms.php";
		\icms::setup();
		\icms::boot();
	}

	private function output(): void
	{
		global $icmsConfig;

		if ($this->mode === self::MODE_HEADLESS) {
			// The host owns response encoding; legacy code must not compress either.
			$icmsConfig["gzip_compression"] = 0;
			return;
		}

		// -- Easiest ML by Gijoe (no longer needed here)

		// Disable gzip compression if PHP is run under CLI mode or if multi-language is enabled
		// To be refactored
		if (
			empty($_SERVER["SERVER_NAME"]) ||
			substr(PHP_SAPI, 0, 3) === "cli" ||
			$GLOBALS["icmsConfigMultilang"]
		) {
			$icmsConfig["gzip_compression"] = 0;
		}

		if (
			(int) $icmsConfig["gzip_compression"] === 1 &&
			extension_loaded("zlib") &&
			!ini_get("zlib.output_compression")
		) {
			ini_set("zlib.output_compression", true);
			if ((int) ini_get("zlib.output_compression_level") < 0) {
				ini_set("zlib.output_compression_level", 6);
			}
			if (!zlib_get_coding_type()) {
				ini_set("zlib.output_compression", false);
				ob_start("ob_gzhandler");
			}
		}
	}

	private function language(): void
	{
		global $icmsConfig, $xoopsOption;

		/*
		 * This address the strict compliance for PHP 5.3/5.4, but the rest of our timezone handling
		 * can be improved beyond this. ~skenow
		 */
		date_default_timezone_set(
			timezone_name_from_abbr("", $icmsConfig["default_TZ"] * 3600, 0),
		);

		// -- Include site-wide lang file
		icms_loadLanguageFile('core', 'global');
		if (defined('_INTL_LOCALE') && class_exists('Locale', false)) {
			\Locale::setDefault(_INTL_LOCALE);
		}
		icms_loadLanguageFile('core', 'core');
		icms_loadLanguageFile('system', 'common');
		@define('_GLOBAL_LEFT', @_ADM_USE_RTL == 1 ? 'right' : 'left');
		@define('_GLOBAL_RIGHT', @_ADM_USE_RTL == 1 ? 'left' : 'right');

		// -- Include page-specific lang file
		if (
			isset($xoopsOption["pagetype"]) &&
			false === strpos($xoopsOption["pagetype"], ".")
		) {
			icms_loadLanguageFile("core", $xoopsOption["pagetype"]);
		}

		defined("XOOPS_USE_MULTIBYTES") or define("XOOPS_USE_MULTIBYTES", 0);
	}

	private function themeSelection(): void
	{
		global $icmsConfig;

		if (
			!empty($_POST["xoops_theme_select"]) &&
			in_array($_POST["xoops_theme_select"], $icmsConfig["theme_set_allowed"])
		) {
			$icmsConfig["theme_set"] = $_POST["xoops_theme_select"];
			$_SESSION["xoopsUserTheme"] = $_POST["xoops_theme_select"];
		} elseif (
			!empty($_POST["theme_select"]) &&
			in_array($_POST["theme_select"], $icmsConfig["theme_set_allowed"])
		) {
			$icmsConfig["theme_set"] = $_POST["theme_select"];
			$_SESSION["xoopsUserTheme"] = $_POST["theme_select"];
		} elseif (
			!empty($_SESSION["xoopsUserTheme"]) &&
			in_array($_SESSION["xoopsUserTheme"], $icmsConfig["theme_set_allowed"])
		) {
			$icmsConfig["theme_set"] = $_SESSION["xoopsUserTheme"];
		}
	}

	private function siteState(): void
	{
		global $icmsConfig;

		if ((int) $icmsConfig["closesite"] === 1) {
			if ($this->mode === self::MODE_HEADLESS && self::mayBypassClosedSite()) {
				return;
			}
			throw new SiteClosedException('The site is closed.');
		}
	}

	private function module(): void
	{
		\icms::launchModule();
	}

	private function online(): void
	{
		global $icmsConfigPersona;

		if (
			isset($icmsConfigPersona["multi_login"]) &&
			(int) $icmsConfigPersona["multi_login"] === 1
		) {
			if (is_object(\icms::$user)) {
				$online_handler = \icms::handler("icms_core_Online");
				$remoteAddr = isset($_SERVER["REMOTE_ADDR"])
					? $_SERVER["REMOTE_ADDR"]
					: "";
				if ($remoteAddr === "") {
					// Record the condition with the on-screen message system (non-rendered)
					// and also keep a stripped fallback in the PHP error log for diagnostics.
					$uid = \icms::$user->getVar("uid");
					$uname = \icms::$user->getVar("uname");
					$msg = sprintf(
						"ImpressCMS: REMOTE_ADDR missing when writing online presence (uid=%s, uname=%s)",
						$uid,
						$uname,
					);
					// Prefer the framework message API if available; do not render to output.
					if (class_exists("icms_core_Message")) {
						\icms_core_Message::error($msg, "", false);
					}
					// Always write a stripped plain-text fallback to the system error log.
					error_log(strip_tags($msg));
				}
				$online_handler->write(
					\icms::$user->getVar("uid"),
					\icms::$user->getVar("uname"),
					time(),
					0,
					$remoteAddr,
				);
			}
		}

		// -- finalize boot process
		\icms::$preload->triggerEvent("finishCoreBoot");
	}

	// -- Helpers ---------------------------------------------------------------

	/**
	 * The visitor may use a closed site: same rules as include/site-closed.php,
	 * except that a login posted to a closed site is not processed.
	 */
	private static function mayBypassClosedSite(): bool
	{
		global $icmsConfig, $xoopsOption;

		if (isset($xoopsOption['ignore_closed_site']) && $xoopsOption['ignore_closed_site']) {
			return true;
		}
		if (is_object(\icms::$user)) {
			foreach (\icms::$user->getGroups() as $group) {
				if (in_array($group, $icmsConfig['closesite_okgrp']) || ICMS_GROUP_ADMIN == $group) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * The mainfile.php of an installed site. The stub shipped before installation
	 * redirects to the installer unless the server environment names a site
	 * configuration (ICMS_MAINFILE).
	 */
	private function installedMainfile(): string
	{
		$mainfile = $this->mainfile;
		if ($mainfile === null || !is_file($mainfile)) {
			throw new NotInstalledException('No mainfile.php to boot from.');
		}
		if (!str_contains((string) file_get_contents($mainfile), 'XOOPS_MAINFILE_INCLUDED')) {
			$configured = getenv('ICMS_MAINFILE');
			if (!is_string($configured) || !is_file($configured)) {
				throw new NotInstalledException('ImpressCMS is not installed.');
			}
			$mainfile = $configured;
		}

		return $mainfile;
	}

	/**
	 * Include a file as if at global scope: existing globals are visible to it,
	 * and variables it defines become globals.
	 *
	 * @param array<string, mixed> $xoopsOption options merged into $xoopsOption
	 *                                          for the duration of the include
	 */
	private static function includeInGlobalScope(string $__icms_file, array $xoopsOption = []): void
	{
		$__icms_options = $xoopsOption;
		unset($xoopsOption);
		$__icms_superglobals = ['GLOBALS', '_GET', '_POST', '_COOKIE', '_FILES', '_SERVER', '_ENV', '_REQUEST', '_SESSION'];

		if ($__icms_options !== []) {
			$__icms_previous = $GLOBALS['xoopsOption'] ?? null;
			$GLOBALS['xoopsOption'] = $__icms_options + (array) $__icms_previous;
		}

		foreach (array_keys($GLOBALS) as $__icms_name) {
			if (!in_array($__icms_name, $__icms_superglobals, true) && !str_starts_with($__icms_name, '__icms_')) {
				${$__icms_name} = &$GLOBALS[$__icms_name];
			}
		}
		unset($__icms_name);

		require_once $__icms_file;

		foreach (get_defined_vars() as $__icms_name => $__icms_value) {
			if (!str_starts_with($__icms_name, '__icms_') && !array_key_exists($__icms_name, $GLOBALS)) {
				$GLOBALS[$__icms_name] = $__icms_value;
			}
		}

		if ($__icms_options !== []) {
			if ($__icms_previous === null) {
				unset($GLOBALS['xoopsOption']);
			} else {
				$GLOBALS['xoopsOption'] = $__icms_previous;
			}
		}
	}

	private static function assertStage(string $stage): void
	{
		if (!in_array($stage, self::STAGES, true)) {
			throw new \InvalidArgumentException(sprintf('Unknown bootstrap stage "%s".', $stage));
		}
	}
}

<?php
/**
 * Locates the trust path, the Composer autoloader and the Symfony host.
 *
 * Used before anything else is loaded: by index.php (to hand the request to
 * the host in the trust path), by Icms\Kernel\Bootstrap and icms::setup() (to
 * find vendor/autoload.php) and by the installer. Plain functions without
 * dependencies; mainfile.php is read, never executed, because including it
 * would run include/common.php (or, with $xoopsOption['nocommon'], keep a later
 * include from running it).
 *
 * @copyright	The ImpressCMS Project https://www.impresscms.org/
 * @license		https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU GPL v2
 * @package		core
 * @since		2.1
 */

if (!function_exists('icms_host_trust_path')) {
	/**
	 * The trust path recorded in the site's mainfile.php, or null when the site
	 * is not installed.
	 *
	 * Follows ICMS_MAINFILE from the environment when mainfile.php is the
	 * uninstalled stub, as the stub itself does.
	 *
	 * @param string $root the web root (ICMS_ROOT_PATH)
	 */
	function icms_host_trust_path(string $root): ?string
	{
		$read = static function (string $file): ?string {
			$code = is_file($file) ? @file_get_contents($file) : false;
			if (
				$code === false
				// at the start of a line: the mainfile template has a commented example
				|| !preg_match("/^[ \\t]*define\s*\(\s*['\"]XOOPS_TRUST_PATH['\"]\s*,\s*'((?:[^'\\\\]|\\\\.)*)'\s*\)/m", $code, $m)
				&& !preg_match("/^[ \\t]*define\s*\(\s*['\"]XOOPS_TRUST_PATH['\"]\s*,\s*\"([^\"\\\\$]*)\"\s*\)/m", $code, $m)
			) {
				return null;
			}
			$path = rtrim(str_replace(['\\\\', "\\'", '\\'], ['\\', "'", '/'], $m[1]), '/');

			return $path === '' ? null : $path;
		};

		$path = $read(rtrim($root, '/\\') . '/mainfile.php');
		if ($path === null) {
			$mainfile = getenv('ICMS_MAINFILE');
			if (is_string($mainfile) && $mainfile !== '') {
				$path = $read($mainfile);
			}
		}

		return $path;
	}

	/**
	 * The Composer autoloader to load, in order of preference: the trust path's
	 * (installed host), the staging directory's (uploaded but not yet installed)
	 * and the web root's (sites from before the host). Null when there is none.
	 *
	 * @param string      $root  the web root (ICMS_ROOT_PATH)
	 * @param string|null $trust the trust path (ICMS_TRUST_PATH), if known
	 */
	function icms_host_vendor_autoload(string $root, ?string $trust): ?string
	{
		$root = rtrim(str_replace('\\', '/', $root), '/');
		$candidates = [];
		if ($trust !== null && $trust !== '') {
			$candidates[] = rtrim(str_replace('\\', '/', $trust), '/') . '/vendor/autoload.php';
		}
		$candidates[] = $root . '/trust_dist/vendor/autoload.php';
		$candidates[] = $root . '/vendor/autoload.php';

		foreach ($candidates as $candidate) {
			if (is_file($candidate)) {
				return $candidate;
			}
		}

		return null;
	}

	/**
	 * Whether the trust path lies inside one of the given public directories
	 * (the web root, the document root): then the web server can serve it, and
	 * nginx and Caddy ignore the .htaccess files that would protect it.
	 *
	 * @param string $trust      the trust path
	 * @param string ...$publics public directories; empty values are ignored
	 */
	function icms_host_trust_path_is_public(string $trust, string ...$publics): bool
	{
		$normalize = static function (string $path): string {
			$real = realpath($path);
			$path = rtrim(str_replace('\\', '/', $real !== false ? $real : $path), '/') . '/';

			return DIRECTORY_SEPARATOR === '\\' ? strtolower($path) : $path;
		};

		if (trim($trust) === '') {
			return false;
		}
		$trust = $normalize($trust);
		foreach ($publics as $public) {
			if (trim($public) !== '' && str_starts_with($trust, $normalize($public))) {
				return true;
			}
		}

		return false;
	}

	/**
	 * The host's front controller in the trust path, or null when the site is
	 * not installed or its trust path holds no host.
	 *
	 * @param string $root the web root (ICMS_ROOT_PATH)
	 */
	function icms_host_entry(string $root): ?string
	{
		$trust = icms_host_trust_path($root);
		if ($trust === null || !is_file($trust . '/host.php') || !is_file($trust . '/vendor/autoload.php')) {
			return null;
		}

		return $trust . '/host.php';
	}
}

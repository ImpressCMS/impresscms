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
	 * The script a request routed to index.php actually asked for, for sites
	 * without a host: a web server at routing level 3 sends every PHP request
	 * to index.php, which then runs that script itself, as the server would at
	 * level 2. Null for index.php itself, paths that are not existing PHP
	 * files of the web root, and paths that are never served.
	 *
	 * @param string $root the web root (ICMS_ROOT_PATH)
	 */
	function icms_host_requested_script(string $root): ?string
	{
		$path = rawurldecode((string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH));
		$base = icms_host_base_path($root);
		if ($base !== '' && str_starts_with($path, $base . '/')) {
			$path = substr($path, strlen($base));
		}
		$relative = ltrim($path, '/');
		if ($relative === '' || str_ends_with($relative, '/')) {
			$relative .= 'index.php';
		}

		// the same paths as docs/server/ and htdocs/htaccess.txt never serve
		if (
			$relative === 'index.php'
			|| !preg_match('/\.php$/i', $relative)
			|| str_contains($relative, "\0")
			|| preg_match('#(^|/)\.|(^|/)\.\.(/|$)|^(trust_dist|templates_c|cache|tests|uploads|plugins/waiting|libraries/tcpdf/tools|install/modules/protector/trust_path)(/|$)#i', $relative)
		) {
			return null;
		}

		$realRoot = realpath($root);
		$script = realpath($root . '/' . $relative);
		if ($realRoot === false || $script === false || !is_file($script) || !str_starts_with($script, $realRoot . DIRECTORY_SEPARATOR)) {
			return null;
		}

		return $script;
	}

	/**
	 * The URL path of the web root: '' for a site at the document root, or for
	 * example '/cms'. Taken from the web root's place under DOCUMENT_ROOT, or,
	 * for a web root outside it (an Apache Alias), from SCRIPT_NAME, which is
	 * index.php for a rewritten request.
	 *
	 * @param string $root the web root (ICMS_ROOT_PATH)
	 */
	function icms_host_base_path(string $root): string
	{
		$realRoot = realpath($root);
		$documentRoot = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
		if ($realRoot !== false && $documentRoot !== false && str_starts_with($realRoot . DIRECTORY_SEPARATOR, rtrim($documentRoot, '/\\') . DIRECTORY_SEPARATOR)) {
			return rtrim(str_replace('\\', '/', substr($realRoot, strlen(rtrim($documentRoot, '/\\')))), '/');
		}

		$scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));

		return str_ends_with($scriptName, '/index.php') ? rtrim(dirname($scriptName), '/') : '';
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

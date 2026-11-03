<?php
/**
 * Stops with a readable page when the server's PHP is older than ImpressCMS
 * needs, instead of failing with an empty "500 Internal Server Error".
 *
 * The minimum version is not kept here: it comes from Composer, which writes
 * the lowest PHP version that all installed packages accept into
 * vendor/composer/platform_check.php. The page is requirements.html in the web
 * root, filled in with the running and the required version.
 *
 * Runs first in index.php, include/common.php and the installer. This file must
 * therefore parse and run on the old PHP versions it rejects: no type
 * declarations, no short array syntax, no closures, no functions newer than
 * PHP 5.3. include/host.php, which may use newer syntax, reads the trust path
 * and the autoloader location through the functions below.
 *
 * @copyright	The ImpressCMS Project https://www.impresscms.org/
 * @license		https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU GPL v2
 * @package		core
 * @since		3.0
 */

if (!function_exists('icms_requirements_check')) {
	/**
	 * The trust path recorded in the site's mainfile.php, or null when the site
	 * is not installed. The file is read, never executed.
	 *
	 * Follows ICMS_MAINFILE from the environment when mainfile.php is the
	 * uninstalled stub, as the stub itself does.
	 *
	 * @param string $root the web root (ICMS_ROOT_PATH)
	 * @return string|null
	 */
	function icms_mainfile_trust_path($root)
	{
		$path = icms_mainfile_read_trust_path(rtrim($root, '/\\') . '/mainfile.php');
		if ($path === null) {
			$mainfile = getenv('ICMS_MAINFILE');
			if (is_string($mainfile) && $mainfile !== '') {
				$path = icms_mainfile_read_trust_path($mainfile);
			}
		}

		return $path;
	}

	/**
	 * The XOOPS_TRUST_PATH defined in a mainfile, or null.
	 *
	 * @param string $file
	 * @return string|null
	 */
	function icms_mainfile_read_trust_path($file)
	{
		$code = is_file($file) ? @file_get_contents($file) : false;
		if (
			$code === false
			// at the start of a line: the mainfile template has a commented example
			|| !preg_match("/^[ \\t]*define\s*\(\s*['\"]XOOPS_TRUST_PATH['\"]\s*,\s*'((?:[^'\\\\]|\\\\.)*)'\s*\)/m", $code, $m)
			&& !preg_match("/^[ \\t]*define\s*\(\s*['\"]XOOPS_TRUST_PATH['\"]\s*,\s*\"([^\"\\\\$]*)\"\s*\)/m", $code, $m)
		) {
			return null;
		}
		$path = rtrim(str_replace(array('\\\\', "\\'", '\\'), array('\\', "'", '/'), $m[1]), '/');

		return $path === '' ? null : $path;
	}

	/**
	 * The Composer vendor directory in use, in order of preference: the trust
	 * path's (installed host), the staging directory's (uploaded but not yet
	 * installed) and the web root's (sites from before the host). Null when
	 * there is none.
	 *
	 * @param string      $root  the web root (ICMS_ROOT_PATH)
	 * @param string|null $trust the trust path (ICMS_TRUST_PATH), if known
	 * @return string|null
	 */
	function icms_vendor_directory($root, $trust)
	{
		$root = rtrim(str_replace('\\', '/', $root), '/');
		$candidates = array();
		if ($trust !== null && $trust !== '') {
			$candidates[] = rtrim(str_replace('\\', '/', $trust), '/') . '/vendor';
		}
		$candidates[] = $root . '/trust_dist/vendor';
		$candidates[] = $root . '/vendor';

		foreach ($candidates as $candidate) {
			if (is_file($candidate . '/autoload.php')) {
				return $candidate;
			}
		}

		return null;
	}

	/**
	 * The lowest PHP version the installed Composer packages accept, as
	 * PHP_VERSION_ID (80200 for 8.2.0), or null when it is unknown: no vendor
	 * directory yet, or Composer's platform check is turned off.
	 *
	 * @param string $root the web root (ICMS_ROOT_PATH)
	 * @return int|null
	 */
	function icms_php_requirement($root)
	{
		$vendor = icms_vendor_directory($root, icms_mainfile_trust_path($root));
		$check = $vendor === null ? false : @file_get_contents($vendor . '/composer/platform_check.php');
		if ($check === false || !preg_match_all('/PHP_VERSION_ID\s*>=\s*(\d+)/', $check, $m)) {
			return null;
		}

		return (int) max($m[1]);
	}

	/**
	 * The page to show instead of the site when PHP is too old, or null when
	 * this PHP version is supported.
	 *
	 * @param string $root      the web root (ICMS_ROOT_PATH)
	 * @param int    $versionId the running PHP version as PHP_VERSION_ID
	 * @param string $version   the running PHP version as PHP_VERSION
	 * @return string|null
	 */
	function icms_requirements_page($root, $versionId, $version)
	{
		$required = icms_php_requirement($root);
		if ($required === null || $versionId >= $required) {
			return null;
		}

		$requiredVersion = sprintf('%d.%d.%d', (int) ($required / 10000), (int) ($required / 100) % 100, $required % 100);
		$page = @file_get_contents(rtrim($root, '/\\') . '/requirements.html');
		if ($page === false) {
			$page = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>PHP version not supported</title></head><body>'
				. '<p>ImpressCMS needs PHP <!--icms:php-required--> or newer. This server runs PHP <!--icms:php-version-->.</p></body></html>';
		}

		return str_replace(
			array('<!--icms:php-required-->', '<!--icms:php-version-->'),
			array(htmlspecialchars($requiredVersion, ENT_QUOTES), htmlspecialchars($version, ENT_QUOTES)),
			$page
		);
	}

	/**
	 * Sends the requirements page and stops when this PHP version is too old.
	 *
	 * 503 with Retry-After: the site is fine and comes back as soon as PHP is
	 * updated, so search engines should not drop it meanwhile.
	 *
	 * @param string $root the web root (ICMS_ROOT_PATH)
	 * @return void
	 */
	function icms_requirements_check($root)
	{
		$page = icms_requirements_page($root, PHP_VERSION_ID, PHP_VERSION);
		if ($page === null) {
			return;
		}

		if (!headers_sent()) {
			header('HTTP/1.1 503 Service Unavailable', true, 503);
			header('Retry-After: 3600');
			header('Content-Type: text/html; charset=utf-8');
			header('Cache-Control: no-store');
		}
		echo $page;
		exit;
	}
}

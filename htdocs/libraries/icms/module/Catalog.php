<?php
/**
 * Catalog of ImpressCMS modules published on GitHub
 *
 * Modules are found through the GitHub topic 'impresscms-module'. The GitHub API is
 * queried without authentication, so every response is cached on disk and refreshed
 * with conditional requests (ETag) to stay well inside the anonymous rate limit.
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Module
 * @since		2.0
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Catalog of ImpressCMS modules published on GitHub
 */
class icms_module_Catalog {

	/** GitHub topic that marks a repository as an ImpressCMS module */
	const TOPIC = 'impresscms-module';

	const API_URL = 'https://api.github.com';

	/** Maximum number of search result pages (100 repositories each) */
	const MAX_PAGES = 3;

	/** Stop calling GitHub when fewer requests than this remain in the current window */
	const RATE_LIMIT_RESERVE = 5;

	/** @var string */
	protected $cacheFile;

	/** @var int seconds before the catalog is fetched again */
	protected $ttl;

	/** Seconds to wait before retrying after a failed or partial refresh */
	const RETRY_DELAY = 3600;

	/** @var array cache contents: fetched_at, retry_after, blocked_until, responses (url => etag, body), modules */
	protected $cache;

	/** @var array */
	protected $errors = array();

	/** @var array urls requested during the current refresh */
	protected $requestedUrls = array();

	/** @var bool a refresh was already attempted during this page request */
	protected $refreshed = false;

	/** @var bool the module list was fetched from GitHub during this page request */
	protected $updated = false;

	/**
	 * @param	int		$ttl		seconds before the catalog is fetched again
	 * @param	string	$cacheFile	file the catalog is cached in
	 */
	public function __construct($ttl = 43200, $cacheFile = null) {
		$this->ttl = (int) $ttl;
		$this->cacheFile = $cacheFile ?: ICMS_CACHE_PATH . '/github_module_catalog.json';
		$this->cache = $this->readCache();
	}

	/**
	 * Get all modules in the catalog, indexed by lowercase dirname
	 *
	 * Every entry holds: full_name, owner, repo, dirname, description, html_url, stars,
	 * pushed_at and latest (null when the repository has no release yet; otherwise
	 * tag, version, name, published_at, html_url, changelog, download_url).
	 *
	 * @param	bool	$refresh	ignore the cache lifetime (GitHub limits are still respected)
	 * @return	array
	 */
	public function getModules($refresh = false) {
		if (!$this->refreshed && ($refresh || $this->isExpired())) {
			$this->refresh();
		}
		return $this->cache['modules'];
	}

	/**
	 * Get the catalog entry of one module
	 *
	 * @param	string	$dirname
	 * @return	array|null
	 */
	public function getModule($dirname) {
		$modules = $this->getModules();
		$key = strtolower($dirname);
		return isset($modules[$key]) ? $modules[$key] : null;
	}

	/**
	 * Is the latest release of a module newer than the installed version?
	 *
	 * @param	string	$dirname
	 * @param	string	$installedVersion	version from the module's icms_version.php
	 * @return	bool
	 */
	public function hasUpdate($dirname, $installedVersion) {
		$module = $this->getModule($dirname);
		if ($module === null || empty($module['latest'])) {
			return false;
		}
		return self::compareVersions($module['latest']['version'], $installedVersion) > 0;
	}

	/**
	 * Compare two version strings, ignoring a 'v' prefix and surrounding text
	 *
	 * @param	string	$a
	 * @param	string	$b
	 * @return	int		-1, 0 or 1
	 */
	public static function compareVersions($a, $b) {
		return version_compare(self::normalizeVersion($a), self::normalizeVersion($b));
	}

	/**
	 * Turn a tag or version name like 'v1.2.1', 'News 1.2 RC' into '1.2.1', '1.2-RC'
	 *
	 * @param	string	$version
	 * @return	string
	 */
	public static function normalizeVersion($version) {
		if (!preg_match('/(\d+(?:\.\d+)*)(?:[\s._-]*(alpha|beta|rc|a|b)[\s._-]*(\d*))?/i', (string) $version, $match)) {
			return '0';
		}
		// '1.2.0' and '1.2' are the same release
		$ret = preg_replace('/(?:\.0)+$/', '', $match[1]);
		if (!empty($match[2])) {
			$ret .= '-' . strtoupper($match[2]) . (isset($match[3]) ? $match[3] : '');
		}
		return $ret;
	}

	/**
	 * Was the module list fetched from GitHub during this page request?
	 *
	 * @return	bool
	 */
	public function isRefreshed() {
		return $this->updated;
	}

	/**
	 * When the catalog was last fetched from GitHub
	 *
	 * @return	int		unix timestamp, 0 if never
	 */
	public function getFetchedAt() {
		return (int) $this->cache['fetched_at'];
	}

	/**
	 * Gets all the error messages
	 *
	 * @param	bool	$ashtml	return as html?
	 * @return	mixed
	 */
	public function getErrors($ashtml = true) {
		if (!$ashtml) {
			return $this->errors;
		}
		$ret = '';
		foreach ($this->errors as $error) {
			$ret .= htmlspecialchars($error, ENT_QUOTES) . '<br />';
		}
		return $ret;
	}

	/**
	 * Fetch the catalog from GitHub and store it in the cache
	 *
	 * On any failure the previous catalog is kept, so a GitHub outage or rate limit
	 * never empties the list.
	 *
	 * @return	bool
	 */
	public function refresh() {
		$this->refreshed = true;
		if ($this->cache['blocked_until'] > time()) {
			$this->errors[] = sprintf('GitHub rate limit reached, retrying after %s', date('H:i', $this->cache['blocked_until']));
			return false;
		}

		$this->requestedUrls = array();
		$repositories = array();
		for ($page = 1; $page <= self::MAX_PAGES; $page++) {
			$result = $this->request('/search/repositories?q=' . rawurlencode('topic:' . self::TOPIC) . '&sort=updated&per_page=100&page=' . $page);
			if ($result === null) {
				$this->cache['retry_after'] = time() + self::RETRY_DELAY;
				$this->writeCache();
				return false;
			}
			$repositories = array_merge($repositories, isset($result['items']) ? $result['items'] : array());
			if (!isset($result['total_count']) || count($repositories) >= $result['total_count'] || empty($result['items'])) {
				break;
			}
		}

		$modules = array();
		foreach ($repositories as $repository) {
			if (!empty($repository['archived']) || !empty($repository['disabled'])) {
				continue;
			}
			$module = $this->parseRepository($repository);
			$key = strtolower($module['dirname']);
			$previous = isset($this->cache['modules'][$key]) ? $this->cache['modules'][$key] : null;

			$release = $this->request('/repos/' . $module['full_name'] . '/releases/latest', true);
			if ($release === null) {
				// keep what we knew about this repository, but don't lose the rest of the catalog
				$module['latest'] = $previous ? $previous['latest'] : null;
			} else {
				$module['latest'] = empty($release) ? null : $this->parseRelease($release);
			}
			$modules[$key] = $module;
		}

		$this->cache['modules'] = $modules;
		$this->updated = true;
		$this->cache['fetched_at'] = time();
		// after a partial refresh, try again in an hour instead of waiting the full lifetime
		$this->cache['retry_after'] = empty($this->errors) ? 0 : time() + self::RETRY_DELAY;
		// forget the ETags of repositories that left the catalog
		$this->cache['responses'] = array_intersect_key($this->cache['responses'], $this->requestedUrls);
		$this->writeCache();
		return empty($this->errors);
	}

	/**
	 * Do a cached, conditional GET request on the GitHub API
	 *
	 * @param	string	$path
	 * @param	bool	$allowNotFound	return an empty array on 404 instead of failing
	 * @return	array|null	decoded JSON, null on failure
	 */
	protected function request($path, $allowNotFound = false) {
		$url = self::API_URL . $path;
		$this->requestedUrls[$url] = true;
		$cached = isset($this->cache['responses'][$url]) ? $this->cache['responses'][$url] : null;

		if ($this->cache['blocked_until'] > time()) {
			$this->errors[] = 'GitHub rate limit reached';
			return null;
		}

		$headers = array('Accept: application/vnd.github+json', 'X-GitHub-Api-Version: 2022-11-28');
		if ($cached && !empty($cached['etag'])) {
			$headers[] = 'If-None-Match: ' . $cached['etag'];
		}

		$response = $this->httpGet($url, $headers);
		if ($response === null) {
			$this->errors[] = 'Could not connect to GitHub';
			return null;
		}

		$this->trackRateLimit($response);

		switch ($response['status']) {
			case 304:
				return $cached['body'];

			case 200:
				$body = json_decode($response['body'], true);
				if (!is_array($body)) {
					$this->errors[] = 'Invalid response from GitHub for ' . $path;
					return null;
				}
				$this->cache['responses'][$url] = array(
					'etag' => isset($response['headers']['etag']) ? $response['headers']['etag'] : '',
					'body' => $body
				);
				return $body;

			case 404:
				if ($allowNotFound) {
					unset($this->cache['responses'][$url]);
					return array();
				}
				break;

			case 403:
			case 429:
				if ($this->cache['blocked_until'] <= time()) {
					// secondary rate limit, without a reset time
					$retry = isset($response['headers']['retry-after']) ? (int) $response['headers']['retry-after'] : 3600;
					$this->cache['blocked_until'] = time() + max(60, $retry);
				}
				$this->errors[] = 'GitHub rate limit reached';
				return null;
		}

		$this->errors[] = sprintf('GitHub API error for %s: HTTP %d', $path, $response['status']);
		return null;
	}

	/**
	 * Block further requests until the rate limit window resets when we're nearly out
	 *
	 * @param	array	$response
	 */
	protected function trackRateLimit(array $response) {
		$headers = $response['headers'];
		if (!isset($headers['x-ratelimit-remaining'], $headers['x-ratelimit-reset'])) {
			return;
		}
		$remaining = (int) $headers['x-ratelimit-remaining'];
		// the search API has its own, much smaller window: only stop when it is really exhausted
		$reserve = (isset($headers['x-ratelimit-resource']) && $headers['x-ratelimit-resource'] === 'search') ? 1 : self::RATE_LIMIT_RESERVE;
		if ($remaining < $reserve) {
			$this->cache['blocked_until'] = max($this->cache['blocked_until'], (int) $headers['x-ratelimit-reset']);
		}
	}

	/**
	 * Do the actual HTTP request
	 *
	 * @param	string	$url
	 * @param	array	$headers
	 * @return	array|null	status, headers (lowercase names) and body; null when no connection could be made
	 */
	protected function httpGet($url, array $headers) {
		if (!function_exists('curl_init')) {
			$this->errors[] = 'The curl extension is required to check for module updates';
			return null;
		}

		$responseHeaders = array();
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_USERAGENT => 'ImpressCMS-ModuleCatalog/2.0',
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_HEADERFUNCTION => function ($ch, $line) use (&$responseHeaders) {
				$parts = explode(':', $line, 2);
				if (count($parts) === 2) {
					$responseHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
				}
				return strlen($line);
			}
		));
		$body = curl_exec($ch);
		$status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($body === false || $status === 0) {
			return null;
		}
		return array('status' => $status, 'headers' => $responseHeaders, 'body' => $body);
	}

	/**
	 * @param	array	$repository	repository from the GitHub search API
	 * @return	array
	 */
	protected function parseRepository(array $repository) {
		return array(
			'full_name' => $repository['full_name'],
			'owner' => $repository['owner']['login'],
			'repo' => $repository['name'],
			'dirname' => $repository['name'],
			'description' => isset($repository['description']) ? (string) $repository['description'] : '',
			'html_url' => self::githubUrl($repository['html_url']),
			'stars' => isset($repository['stargazers_count']) ? (int) $repository['stargazers_count'] : 0,
			'pushed_at' => isset($repository['pushed_at']) ? $repository['pushed_at'] : '',
			'latest' => null
		);
	}

	/**
	 * @param	array	$release	release from the GitHub releases API
	 * @return	array
	 */
	protected function parseRelease(array $release) {
		// a zip uploaded to the release is the packaged module; the zipball is the raw source
		$download = isset($release['zipball_url']) ? $release['zipball_url'] : '';
		if (!empty($release['assets'])) {
			foreach ($release['assets'] as $asset) {
				if (strtolower(substr($asset['name'], -4)) === '.zip') {
					$download = $asset['browser_download_url'];
					break;
				}
			}
		}
		return array(
			'tag' => $release['tag_name'],
			'version' => ltrim($release['tag_name'], 'vV'),
			'name' => !empty($release['name']) ? $release['name'] : $release['tag_name'],
			'published_at' => isset($release['published_at']) ? $release['published_at'] : '',
			'html_url' => isset($release['html_url']) ? self::githubUrl($release['html_url']) : '',
			'changelog' => isset($release['body']) ? (string) $release['body'] : '',
			'download_url' => $download,
			'prerelease' => !empty($release['prerelease'])
		);
	}

	/**
	 * Only keep links that point to github.com, so a link in the admin can never run script
	 *
	 * @param	string	$url
	 * @return	string	the url, or '' when it is not a github.com url
	 */
	protected static function githubUrl($url) {
		return strncmp((string) $url, 'https://github.com/', 19) === 0 ? (string) $url : '';
	}

	/**
	 * @return	bool
	 */
	protected function isExpired() {
		if ($this->cache['retry_after'] > 0) {
			return $this->cache['retry_after'] < time();
		}
		return $this->cache['fetched_at'] + $this->ttl < time();
	}

	/**
	 * @return	array
	 */
	protected function readCache() {
		$cache = array('fetched_at' => 0, 'retry_after' => 0, 'blocked_until' => 0, 'responses' => array(), 'modules' => array());
		if (is_file($this->cacheFile)) {
			$data = json_decode((string) file_get_contents($this->cacheFile), true);
			if (is_array($data)) {
				$cache = array_merge($cache, array_intersect_key($data, $cache));
			}
		}
		return $cache;
	}

	/**
	 * Write the cache atomically, so a concurrent request never reads half a file
	 *
	 * @return	bool
	 */
	protected function writeCache() {
		$tmp = $this->cacheFile . '.' . uniqid('', true) . '.tmp';
		if (file_put_contents($tmp, json_encode($this->cache)) === false) {
			$this->errors[] = 'Could not write the module catalog cache';
			return false;
		}
		return rename($tmp, $this->cacheFile);
	}
}

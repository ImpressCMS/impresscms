<?php
/**
 * Reads an optional preset file (install/preset.php) so the installer can pre-fill
 * its pages and skip those for which the preset holds every required value.
 *
 * @package		installer
 */
class IcmsInstallPreset {

	/** Keys copied into $_SESSION['settings'] */
	const SETTINGS_KEYS = array(
		'ROOT_PATH', 'TRUST_PATH', 'URL',
		'DB_TYPE', 'DB_HOST', 'DB_USER', 'DB_PASS', 'DB_PCONNECT',
		'DB_NAME', 'DB_PREFIX', 'DB_SALT', 'DB_CHARSET', 'DB_COLLATION',
	);

	/** Keys copied into $_SESSION['siteconfig'] */
	const SITE_KEYS = array('adminname', 'adminlogin_name', 'adminmail', 'adminpass');

	/** Keys that must be present for a page to be submitted from the preset */
	const REQUIRED = array(
		'langselect' => array('LANGUAGE'),
		'pathsettings' => array('ROOT_PATH', 'TRUST_PATH', 'URL'),
		'dbconnection' => array('DB_HOST', 'DB_USER', 'DB_PASS'),
		'dbsettings' => array('DB_NAME'),
		'siteinit' => array('adminname', 'adminlogin_name', 'adminmail', 'adminpass'),
		'modulesinstall' => array('MODULES'),
	);

	/** Required keys that may legitimately be an empty string */
	const MAY_BE_EMPTY = array('DB_PASS');

	/** @var array<string, mixed> */
	private array $values = array();
	/** @var string[] */
	private array $warnings = array();
	private bool $active = false;

	public function __construct(private string $file) {
		$this->read();
	}

	public function isActive(): bool {
		return $this->active;
	}

	/** @return string[] */
	public function getWarnings(): array {
		return $this->warnings;
	}

	public function has(string $key): bool {
		if (!array_key_exists($key, $this->values)) {
			return false;
		}
		return $this->values[$key] !== '' || in_array($key, self::MAY_BE_EMPTY, true);
	}

	public function get(string $key, $default = null) {
		return $this->values[$key] ?? $default;
	}

	/**
	 * Copies the known preset values into the installer session so pages can pre-fill them.
	 */
	public function importInto(array &$session): void {
		foreach (self::SETTINGS_KEYS as $key) {
			if (array_key_exists($key, $this->values)) {
				$session['settings'][$key] = $this->values[$key];
			}
		}
		foreach (self::SITE_KEYS as $key) {
			if (array_key_exists($key, $this->values)) {
				$session['siteconfig'][$key] = $this->values[$key];
			}
		}
	}

	/**
	 * @return bool true when the preset holds everything the page needs
	 */
	public function covers(string $page): bool {
		if (!$this->active || !isset(self::REQUIRED[$page])) {
			return false;
		}
		foreach (self::REQUIRED[$page] as $key) {
			if (!$this->has($key)) {
				return false;
			}
		}
		return true;
	}

	/**
	 * The form fields a page would receive if the user had submitted it with the preset values.
	 *
	 * @return array<string, mixed>
	 */
	public function formFor(string $page): array {
		switch ($page) {
			case 'langselect':
				return array('lang' => $this->get('LANGUAGE'));
			case 'pathsettings':
				return array(
					'ROOT_PATH' => $this->get('ROOT_PATH'),
					'TRUST_PATH' => $this->get('TRUST_PATH'),
					'URL' => $this->get('URL'),
				);
			case 'dbconnection':
				$form = array(
					'DB_TYPE' => $this->get('DB_TYPE', 'pdo.mysql'),
					'DB_HOST' => $this->get('DB_HOST'),
					'DB_USER' => $this->get('DB_USER'),
					'DB_PASS' => $this->get('DB_PASS'),
				);
				if ($this->get('DB_PCONNECT')) {
					$form['DB_PCONNECT'] = '1';
				}
				return $form;
			case 'dbsettings':
				return array(
					'DB_NAME' => $this->get('DB_NAME'),
					'DB_CHARSET' => $this->get('DB_CHARSET', 'utf8mb4'),
					'DB_COLLATION' => $this->get('DB_COLLATION', ''),
					'DB_PREFIX' => $this->get('DB_PREFIX') ?? $_SESSION['settings']['DB_PREFIX'] ?? 'i' . substr(md5((string) microtime(true)), 0, 8),
					'DB_SALT' => $this->get('DB_SALT') ?? $_SESSION['settings']['DB_SALT'] ?? icms_core_Password::createSalt(),
				);
			case 'siteinit':
				return array(
					'adminname' => $this->get('adminname'),
					'adminlogin_name' => $this->get('adminlogin_name'),
					'adminmail' => $this->get('adminmail'),
					'adminpass' => $this->get('adminpass'),
					'adminpass2' => $this->get('adminpass'),
				);
			case 'modulesinstall':
				$form = array('mod' => '1');
				if ($this->get('MODULES')) {
					$form['install_mods'] = $this->get('MODULES');
				}
				return $form;
		}
		return array();
	}

	private function read(): void {
		if (!is_file($this->file)) {
			return;
		}
		try {
			$data = (static function (string $file) {
				return include $file;
			})($this->file);
		} catch (\Throwable $e) {
			$this->warnings[] = basename($this->file) . ': ' . $e->getMessage();
			return;
		}
		if (!is_array($data)) {
			$this->warnings[] = basename($this->file) . ': must return an array';
			return;
		}
		$this->active = true;

		$known = array_merge(self::SETTINGS_KEYS, self::SITE_KEYS, array('LANGUAGE', 'MODULES'));
		foreach ($data as $key => $value) {
			if (!in_array($key, $known, true)) {
				$this->warnings[] = sprintf('unknown key "%s" ignored', $key);
				continue;
			}
			if ($key === 'MODULES') {
				$this->values[$key] = $this->cleanModules($value);
				continue;
			}
			if (!is_scalar($value)) {
				$this->warnings[] = sprintf('"%s" ignored: scalar value expected', $key);
				continue;
			}
			$this->values[$key] = (string) (is_bool($value) ? (int) $value : $value);
		}
		if (isset($this->values['DB_TYPE']) && !in_array($this->values['DB_TYPE'], array('pdo.mysql', 'mysql'), true)) {
			$this->warnings[] = sprintf('DB_TYPE "%s" ignored', $this->values['DB_TYPE']);
			unset($this->values['DB_TYPE']);
		}
	}

	/** @return string[] */
	private function cleanModules($value): array {
		if (!is_array($value)) {
			$this->warnings[] = '"MODULES" ignored: array expected';
			return array();
		}
		return array_values(array_filter(array_map('strval', array_filter($value, 'is_scalar')), static function ($dir) {
			return preg_match('/^[A-Za-z0-9_-]+$/', $dir) === 1;
		}));
	}
}

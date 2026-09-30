<?php

class IcmsInstallWizard {
	public array $pages = array();
	public array $pagesNames = array();
	public array $pagesTitles = array();
	public array $titles = array();
	public int $currentPage = 0;
	public string $currentPageName;
	public string $lastpage;
	public string $secondlastpage = '';
	public string $language = 'english';
	public bool $no_php5 = false;

	/**
	 * Wizard pages: page key => [name constant, title constant]
	 */
	private const PAGES = [
		'langselect' => ['LANGUAGE_SELECTION', 'LANGUAGE_SELECTION_TITLE'],
		'start' => ['INTRODUCTION', 'INTRODUCTION_TITLE'],
		'modcheck' => ['CONFIGURATION_CHECK', 'CONFIGURATION_CHECK_TITLE'],
		'pathsettings' => ['PATHS_SETTINGS', 'PATHS_SETTINGS_TITLE'],
		'dbconnection' => ['DATABASE_CONNECTION', 'DATABASE_CONNECTION_TITLE'],
		'dbsettings' => ['DATABASE_CONFIG', 'DATABASE_CONFIG_TITLE'],
		'configsave' => ['CONFIG_SAVE', 'CONFIG_SAVE_TITLE'],
		'tablescreate' => ['TABLES_CREATION', 'TABLES_CREATION_TITLE'],
		'siteinit' => ['INITIAL_SETTINGS', 'INITIAL_SETTINGS_TITLE'],
		'tablesfill' => ['DATA_INSERTION', 'DATA_INSERTION_TITLE'],
		'modulesinstall' => ['MODULES_INSTALL', 'MODULES_INSTALL_TITLE'],
		'end' => ['WELCOME', 'WELCOME_TITLE'],
	];

	/**
	 * Page shown instead of the wizard when the PHP version is too old
	 */
	private const NO_PHP_PAGE = ['no_php5' => ['NO_PHP5', 'NO_PHP5_TITLE']];

	public function xoInit(): bool {
		if (!$this->checkAccess()) {
			return false;
		}
		if (empty($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = htmlspecialchars($_SERVER['PHP_SELF'] ?? '', ENT_QUOTES);
		}

		$this->no_php5 = PHP_VERSION_ID < 70400;

		// Load the main language file
		$this->initLanguage(!empty($_COOKIE['xo_install_lang']) ? (string) $_COOKIE['xo_install_lang'] : 'english');

		// Setup pages
		$pages = $this->no_php5 ? self::NO_PHP_PAGE : self::PAGES;
		foreach ($pages as $page => [$nameConstant, $titleConstant]) {
			$this->pages[] = $page;
			$this->pagesNames[] = constant($nameConstant);
			$this->pagesTitles[] = constant($titleConstant);
		}
		$this->lastpage = end($this->pages);

		$this->setPage(0);
		// Prevent client caching
		header("Cache-Control: no-store, no-cache, must-revalidate", false);
		header("Pragma: no-cache");
		return true;
	}

	public function checkAccess(): bool {
		if (INSTALL_USER && INSTALL_PASSWORD) {
			$user = $_SERVER['PHP_AUTH_USER'] ?? null;
			$password = $_SERVER['PHP_AUTH_PW'] ?? '';
			if ($user === null) {
				header('WWW-Authenticate: Basic realm="ImpressCMS Installer"');
			}
			if ($user === null
				|| !hash_equals(INSTALL_USER, (string) $user)
				|| !hash_equals(INSTALL_PASSWORD, (string) $password)
			) {
				header('HTTP/1.0 401 Unauthorized');
				echo 'You can not access this ImpressCMS installer.';
				return false;
			}
		}
		return true;
	}

	public function loadLangFile(string $file): void {
		if (file_exists("./language/$this->language/$file.php")) {
			include_once "./language/$this->language/$file.php";
		} else {
			include_once "./language/english/$file.php";
		}
	}

	public function initLanguage(string $language): void {
		$language = preg_replace('/[^A-Za-z]+/', '', $language);
		if (!file_exists("./language/$language/install.php")) {
			$language = 'english';
		}
		$this->language = $language;
		$this->loadLangFile('install');
	}

	/**
	 * Sets the current page
	 *
	 * @param int|string $page Page index or page name
	 * @return int|false The index of the current page or false if the page doesn't exist
	 */
	public function setPage($page) {
		// If the PHP version is too old, display the no_php5 page and stop the install
		if ($this->no_php5 && $page !== 'no_php5') {
			header('location:page_no_php5.php');
			exit();
		}

		if (is_int($page) || (is_string($page) && ctype_digit($page))) {
			$index = (int) $page;
			if (!isset($this->pages[$index])) {
				return false;
			}
		} else {
			$index = array_search($page, $this->pages, true);
			if ($index === false) {
				return false;
			}
		}
		$this->currentPageName = $this->pages[$index];
		$this->currentPage = $index;
		return $index;
	}

	public function baseLocation(): string {
		$https = $_SERVER['HTTPS'] ?? '';
		$proto = ($https !== '' && strtolower($https) !== 'off') ? 'https' : 'http';
		$host = htmlspecialchars($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost', ENT_QUOTES);
		$server_php_self = htmlspecialchars($_SERVER['PHP_SELF'] ?? '', ENT_QUOTES);
		$base = substr($server_php_self, 0, (int) strrpos($server_php_self, '/'));
		return "$proto://$host$base";
	}

	/**
	 * Gets the URI of a page
	 *
	 * @param int|string $page Page index, page name or a relative offset ('+1', '-2')
	 */
	public function pageURI($page): string {
		if (is_string($page) && $page !== '' && ($page[0] === '+' || $page[0] === '-')) {
			$index = $this->currentPage + (int) $page;
		} elseif (is_int($page) || (is_string($page) && ctype_digit($page))) {
			$index = (int) $page;
		} else {
			$index = (int) array_search($page, $this->pages, true);
		}
		$page = $this->pages[$index] ?? $this->pages[0];
		return $this->baseLocation() . "/page_$page.php";
	}

	public function redirectToPage($page, int $status = 303, string $message = 'See other'): void {
		$location = $this->pageURI($page);
		$proto = !empty($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
		header("$proto $status $message");
		header("Location: $location");
	}
}

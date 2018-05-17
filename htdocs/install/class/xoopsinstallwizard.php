<?php

class XoopsInstallWizard {

	var $pages = array();
	var $titles = array();
	var $currentPage = 0;
	var $lastpage;
	var $secondlastpage;
	var $language = 'english';
	var $no_php5 = false;
	var $safe_mode = false;

	function xoInit() {
		if (!$this->checkAccess()) {
			return false;
		}
		if (@empty($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
		}

		if (version_compare(phpversion(), '5', '<')) {
			$this->no_php5 = true;
		}

		// Load the main language file
		$this->initLanguage(!@empty($_COOKIE['xo_install_lang'])?$_COOKIE['xo_install_lang']:'english');
		// Setup pages
		if ($this->no_php5) {
			$this->pages[] = 'no_php5';
		} else {
			$this->pages[] = 'langselect';
			$this->pages[] = 'start';
			$this->pages[] = 'modcheck';
			$this->pages[] = 'pathsettings';
			$this->pages[] = 'dbconnection';
			$this->pages[] = 'dbsettings';
			$this->pages[] = 'configsave';
			$this->pages[] = 'tablescreate';
			$this->pages[] = 'siteinit';
			$this->pages[] = 'tablesfill';
			$this->pages[] = 'modulesinstall';
			$this->pages[] = 'end';
		}

		$this->lastpage = end($this->pages);

		if ($this->no_php5) {
			$this->pagesNames[] = NO_PHP5;
		} elseif ($this->safe_mode) {
			$this->pagesNames[] = SAFE_MODE;
		} else {
			$this->pagesNames[] = LANGUAGE_SELECTION;
			$this->pagesNames[] = INTRODUCTION;
			$this->pagesNames[] = CONFIGURATION_CHECK;
			$this->pagesNames[] = PATHS_SETTINGS;
			$this->pagesNames[] = DATABASE_CONNECTION;
			$this->pagesNames[] = DATABASE_CONFIG;
			$this->pagesNames[] = CONFIG_SAVE;
			$this->pagesNames[] = TABLES_CREATION;
			$this->pagesNames[] = INITIAL_SETTINGS;
			$this->pagesNames[] = DATA_INSERTION;
			$this->pagesNames[] = MODULES_INSTALL;
			$this->pagesNames[] = WELCOME;
		}

		if ($this->no_php5) {
			$this->pagesTitles[] = NO_PHP5_TITLE;
		} elseif ($this->safe_mode) {
			$this->pagesTitles[] = SAFE_MODE_TITLE;
		} else {
			$this->pagesTitles[] = LANGUAGE_SELECTION_TITLE;
			$this->pagesTitles[] = INTRODUCTION_TITLE;
			$this->pagesTitles[] = CONFIGURATION_CHECK_TITLE;
			$this->pagesTitles[] = PATHS_SETTINGS_TITLE;
			$this->pagesTitles[] = DATABASE_CONNECTION_TITLE;
			$this->pagesTitles[] = DATABASE_CONFIG_TITLE;
			$this->pagesTitles[] = CONFIG_SAVE_TITLE;
			$this->pagesTitles[] = TABLES_CREATION_TITLE;
			$this->pagesTitles[] = INITIAL_SETTINGS_TITLE;
			$this->pagesTitles[] = DATA_INSERTION_TITLE;
			$this->pagesTitles[] = MODULES_INSTALL_TITLE;
			$this->pagesTitles[] = WELCOME_TITLE;
		}

		$this->setPage(0);
		// Prevent client caching
		header("Cache-Control: no-store, no-cache, must-revalidate", false);
		header("Pragma: no-cache");
		return true;
	}

	function checkAccess() {
		if (INSTALL_USER != '' && INSTALL_PASSWORD != '') {
			if (!isset($_SERVER['PHP_AUTH_USER'])) {
				header('WWW-Authenticate: Basic realm="ImpressCMS Installer"');
				header('HTTP/1.0 401 Unauthorized');
				echo 'You can not access this ImpressCMS installer.';
				return false;
			}
			if (INSTALL_USER != '' && $_SERVER['PHP_AUTH_USER'] != INSTALL_USER) {
				header('HTTP/1.0 401 Unauthorized');
				echo 'You can not access this ImpressCMS installer.';
				return false;
			}
			if (INSTALL_PASSWD != $_SERVER['PHP_AUTH_PW']) {
				header('HTTP/1.0 401 Unauthorized');
				echo 'You can not access this ImpressCMS installer.';
				return false;
			}
		}
		return true;
	}

	function loadLangFile($file) {
		if (file_exists("./language/$this->language/$file.php")) {
			include_once "./language/$this->language/$file.php";
		} else {
			include_once "./language/english/$file.php";
		}
	}

	function initLanguage($language) {
		if (!file_exists("./language/$language/install.php")) {
			$language = 'english';
		}
		$this->language = $language;
		$this->loadLangFile('install');
	}

	function setPage($page) {
		/**
		 * If server is PHP 4, display the php4 page and stop the install
		 */
		if ($this->no_php5 && $page != 'no_php5') {
			header('location:page_no_php5.php');
			exit;
		}
		/**
		 * If server is in Safe Mode, display the safe_mode page and stop the install
		 */
		if ($this->safe_mode && $page != 'safe_mode') {
			header('location:page_safe_mode.php');
			exit;
		}

		if ((int) $page && $page >= 0 && $page < count($this->pages)) {
			$this->currentPageName = $this->pages[$page];
			$this->currentPage = $page;
		} elseif (false !== ($index = array_search($page, $this->pages))) {
			$this->currentPageName = $page;
			$this->currentPage = $index;
		} else {
			return false;
		}
		return $this->currentPage;
	}

	function baseLocation() {
		$proto = (@$_SERVER['HTTPS'] == 'on')?'https':'http';
		$host	= $_SERVER['HTTP_HOST'];
		$base	= substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'));
		return "$proto://$host$base";
	}

	function pageURI($page) {
		if (!(int) $page{0}) {
			if ($page{0} == '+') {
				$page = $this->currentPage + substr($page, 1);
			} elseif ($page{0} == '-') {
				$page = $this->currentPage - substr($page, 1);
			} else {
				$page = (int) array_search($page, $this->pages);
			}
		}
		$page = $this->pages[$page];
		return $this->baseLocation() . "/page_$page.php";
	}

	function redirectToPage($page, $status = 303, $message = 'See other') {
		$location = $this->pageURI($page);
		$proto = !@empty($_SERVER['SERVER_PROTOCOL'])?$_SERVER['SERVER_PROTOCOL']:'HTTP/1.1';
		header("$proto $status $message");
		//header( "Status: $status $message" );
		header("Location: $location");
	}

}
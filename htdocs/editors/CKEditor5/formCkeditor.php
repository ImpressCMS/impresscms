<?php
/**
 * CKEditor adapter for ICMS
 *
 */
defined("ICMS_ROOT_PATH") || die("Root path not defined");

class icmsFormCKEditor extends icms_form_elements_Textarea {
	var $rootpath = "";
	var $_language = _LANGCODE;
	var $_width = "100%";
	var $_height = "400px";

	var $ckeditor;
	var $config = array();

	/**
	 * Constructor
	 *
	 * @param	array   $configs  Editor Options
	 * @param	bool 	$checkCompatible  true - return false on failure
	 */
	public function __construct($configs, $checkCompatible = false) {
		$current_path = dirname(__FILE__);
		if (DIRECTORY_SEPARATOR != "/") {
			$current_path = str_replace(strpos($current_path, "\\\\", 2) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
		}

		$this->rootpath = substr(dirname($current_path), strlen(ICMS_ROOT_PATH));
		
		if (is_array($configs)) {
			$vars = array_keys(get_object_vars($this));
			foreach($configs as $key => $val) {
				if (in_array("_" . $key, $vars)) {
					$this->{"_" . $key} = $val;
				} else {
					$this->config[$key] = $val;
				}
			}
		}
		parent::__construct(@$this->_caption, @$this->_name, @$this->_value);
		parent::setExtra("class=ckeditor style='width: " . $this->_width . "; height: " . $this->_height . ";'");
	}

	/**
	 * get language
	 *
	 * @return	string
	 */
	protected function getLanguage() {
		$language = str_replace('-', '_', strtolower($this->_language));
		if (strtolower(_CHARSET) != "utf-8") {
			$language .= "_ansi";
		}
		return $language;
	}

	/**
	 * Gets the fonts for CKEditor
	 **/
	protected function getFonts() {
		if (empty($this->config["fonts"]) && defined("_ICMS_EDITOR_CKEDITOR_FONTS")) {
			$this->config["fonts"] = constant("_ICMS_EDITOR_CKEDITOR_FONTS");
		}

		return @$this->config["fonts"];
	}

	/**
	 * prepare HTML for output
	 * @return	string    $ret    HTML
	 */
	public function render() {

		global $xoTheme;
/*
		$toolbar = "Basic";

		if (is_object(icms::$user) ) {
	         $toolbar = "Normal";
	         if (is_object(icms::$module)) {
	         	if (icms::$user->isAdmin(icms::$module->getVar('mid'))) { $toolbar = "Full"; }
	         }
	    }
*/
		$xoTheme->addStylesheet('editors/CKEditor5/style.css');
		$xoTheme->addStylesheet('editors/CKEditor5/ckeditor5/ckeditor5.css');
		$xoTheme->addScript('', array('type' => 'importmap'), '{
			"imports": {
				"ckeditor5": "' . ICMS_URL . '/editors/CKEditor5/ckeditor5/ckeditor5.js",
				"ckeditor5/": "' . ICMS_URL . '/editors/CKEditor5/ckeditor5/"
			}
		}');
		$xoTheme->addScript("editors/CKEditor5/main.js", array('type' => 'module'), '', 'foot');
		return parent::render();
	}
}

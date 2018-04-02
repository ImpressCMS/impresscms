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
		$current_path = __DIR__;
		if (DIRECTORY_SEPARATOR != "/") {
			$current_path = str_replace(strpos($current_path, "\\\\", 2) ? "\\\\" : DIRECTORY_SEPARATOR, "/", $current_path);
		}
		$docroot = pathinfo($_SERVER['DOCUMENT_ROOT']);
		$homepath = $docroot['dirname'] . DIRECTORY_SEPARATOR . $docroot['basename'];
		$this->rootpath = str_replace($homepath, '', $current_path) . '/ckeditor/';

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

		if ($checkCompatible && ! $this->isCompatible ()) {
			return false;
		}

		parent::__construct(@$this->_caption, @$this->_name, @$this->_value);
		parent::setExtra("style='width: " . $this->_width . "; height: " . $this->_height . ";'");

		$this->initCKEditor();
	}

	/**
	 * Initializes CKEditor
	 */
	protected function initCKEditor() {
		$this->config = array(
				"elements" => $this->getName (),
				"language" => $this->getLanguage (),
				"rootpath" => $this->rootpath,
				"area_width" => $this->_width,
				"area_height" => $this->_height,
				"fonts" => $this->getFonts(),
				"contentsCss" => xoops_getcss(), // default: CKEDITOR.basePath + 'contents.css''
				"filebrowserImageBrowseUrl" => ICMS_URL . '/editors/CKeditor/ceditfinder/browse.php?site=administrator',
			);

		require_once __DIR__ . "/ckeditor/ckeditor.php";
		$this->CKEditor = new CKEditor($this->rootpath);
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
	 */
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

		$toolbar = "Basic";

		if (is_object(icms::$user) ) {
	         $toolbar = "Normal";
	         if (icms::$user->isAdmin(icms::$module->getVar('mid'))) { $toolbar = "Full"; }
	    }

		$ret = $xoTheme->addScript("/editors/CKeditor/ckeditor/ckeditor.js", array('type' => 'text/javascript'), '');
		$ret .= $xoTheme->addScript("/editors/CKeditor/ckeditor/adapters/jquery.js", array('type' => 'text/javascript'), '');
		$ret .= $xoTheme->addScript('', array('type' => 'text/javascript'),
			'var config = {filebrowserImageBrowseUrl: "' . ICMS_URL . '/editors/CKeditor/ceditfinder/browse.php?site=administrator", toolbar: "' .  $toolbar . '"}; $(function() { $("#'.@$this->_name.'_tarea").ckeditor(config); $("#'.@$this->_name.'_tarea").parents("form").submit(function() { var data = $("#'.@$this->_name.'_tarea").html(); $("#'.@$this->_name.'_tarea").html(data); }); });');
		$ret .= parent::render();

		$ret .= '<br clear="' . _GLOBAL_RIGHT . '" />';

		return $ret;
	}

	/**
	 * Check if compatible
	 *
	 * @return  bool
	 */
	protected function isCompatible() {
		return is_readable(ICMS_ROOT_PATH . $this->rootpath . "/ckeditor/ckeditor.php");
	}
}

<?php
/**
 * ImpressCMS Autoloader
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_core
 * @subpackage	icms_core_Autoloader
 * @since		1.3
 * @author		Marc-André Lanciault (aka marcan) <mal@inboxintl.com>
 * @version		$Id$
 */
class icms_Autoloader {
	/**
	 * Paths of known global repositories
	 * @var array
	 */
	static protected $globalRepositories = array();
	/**
	 * Paths of known namespace repositories
	 * @var array
	 * @internal Each entry is stored as array(strlen($ns), $path), to avoid having to call strlen repeatedly during autoload
	 */
	static protected $localRepositories = array();
	/**
	 * Imported namespaces
	 * @var array
	 */
	static protected $imported = array();
	/**
	 * Whether setup has already been called or not
	 * @var bool
	 */
	static protected $initialized = false;

	/**
	 * Initialize the autoloader, and register its autoload method
	 * @return void
	 */
	static public function setup() {
		if (!self::$initialized) {
			self::register(dirname(dirname(__FILE__)));
			spl_autoload_register(array('icms_Autoloader', 'autoload'));
			spl_autoload_register(array('icms_Autoloader', 'registerLegacy'));
			self::$initialized = true;
		}
	}

	/**
	 * Split a fully qualified class name into Namespace and Local class name
	 *
	 * Supports both PHP 5.3 proj\sub\Class and 5.2 proj_sub_Class naming convention.
	 *
	 * @param string $class
	 * @return array
	 */
	static public function split($class) {
		if (false === ($pos = strrpos($class, "\\"))) {
			$pos = strrpos($class, "_");
		}
		if ($pos) {
			$ns = substr($class, 0, $pos);
			$local = substr($class, $pos + 1);
		} else {
			$ns = "";
			$local = $class;
		}
		return array($ns, $local);
	}

	/**
	 * Register/unregister a class repository
	 *
	 * The autoload system will look for classes in all registered repositories one after the other.
	 *
	 * @param string $path Repository physical path
	 * @param string $namespace If specified, all classes of the repository belong to this namespace
	 * @return void
	 */
	static public function register($path, $namespace = "") {
		if ($namespace) {
			self::$localRepositories[ $namespace ] = array(strlen($namespace), $path);
		} else {
			self::$globalRepositories[] = $path;
		}
	}

	/**
	 * Import a namespace global elements (constants and functions)
	 *
	 * If a namespace has functions or constants, they must be put in a file called "namespace.php"
	 * that will be read by this function.
	 *
	 * @param string $namespace
	 * @param bool $required Whether to throw an exception or not if the namespace file is not found
	 * @return bool
	 */
	static public function import($namespace, $required = true) {
		if (!isset(self::$imported[ $namespace ])) {
			$nspath = self::classPath($namespace, true, DIRECTORY_SEPARATOR . "namespace.php");
			if ($nspath) {
				include_once($nspath . DIRECTORY_SEPARATOR . "namespace.php");
				return self::$imported[$namespace] = true;
			}
			self::$imported[$namespace] = false;
		}
		if (!self::$imported[$namespace] && $required) {
			throw new RuntimeException("No namespace file for namespace '$namespace'.");
		}
		return self::$imported[$namespace];
	}

	/**
	 * Locate and load the appropriate file, based on a class name
	 *
	 * @param string $class
	 * @return bool
	 */
	static public function autoload($class) {
		if ($path = self::classPath($class)) {
			list($ns, $local) = self::split($class);
			include_once "$path.php";
			return true;
		}
		return false;
	}

	/**
	 * Attempt to find a class path by scanning registered repositories
	 *
	 * @param string $class Name of the class to find
	 * @param bool $useIncludePath If to search include paths too
	 */
	static public function classPath($class, $useIncludePath = true, $ext = ".php") {
		$classPath = str_replace(array("\\", "_"), DIRECTORY_SEPARATOR, $class);
		// First, try local repositories
		if (strpos($class, "\\") || strpos($class, "_")) {
			foreach (self::$localRepositories as $name => $info) {
				list($len, $path) = $info;
				if (!strncmp($name . "\\", $class, $len+1) || !strncmp($name . "_", $class, $len+1)) {
					$localPath = substr($classPath, $len + 1);
					if (file_exists($path . DIRECTORY_SEPARATOR . $localPath . $ext)) {
						return $path . DIRECTORY_SEPARATOR . $localPath;
					}
				}
			}
		}
		// Search global repositories
		foreach(self::$globalRepositories as $path) {
			if (file_exists($path . DIRECTORY_SEPARATOR . $classPath . $ext)) {
				return $path . DIRECTORY_SEPARATOR . $classPath;
			}
		}
		// Search include path
		// On Windows include paths use "/" as directory_separator, even if added to set_include_path with anti-slashes
		// We do this to make sure the string we get compensates for that
		if ($useIncludePath) {
			foreach (explode(PATH_SEPARATOR, get_include_path()) as $path) {
				if (file_exists($path . DIRECTORY_SEPARATOR . $classPath . $ext)) {
					return (DIRECTORY_SEPARATOR != "/" ? str_replace("/", DIRECTORY_SEPARATOR, $path) : $path) . DIRECTORY_SEPARATOR . $classPath;
				}
			}
		}
		return false;
	}
	
	/**
	 *  This function maps the legacy classes that were included in common.php and xoopsformloader.php
	 *  Rather than including all the legacy files, this defines where PHP should look to use these classes
	 *  It's ugly, but so was the code we're getting rid of
	 */
	static public function registerLegacy($class) {
		$legacyClassPath = array(
		    "Database" 						=> "/class/database/database.php",
		    "IcmsDatabase" 					=> "/class/database/database.php",
		    "XoopsDatabase" 				=> "/class/database/database.php",
			"MyTextSanitizer" 				=> "/class/module.textsanitizer.php",
			"IcmsPreloadHandler"			=> "/kernel/icmspreloadhandler.php", 
			"XoopsModule" 					=> "/kernel/module.php",
			"XoopsModuleHandler"			=> "/kernel/module.php",
			"XoopsMemberHandler"			=> "/kernel/member.php",
			"IcmsPreloadHandler"			=> "/kernel/icmspreloadhandler.php",
			"IcmsPreloadItem" 				=> "/kernel/icmspreloadhandler.php",
			"IcmsKernel" 					=> "/kernel/icmskernel.php", 		
			"IcmsSecurity" 					=> "/class/xoopssecurity.php", 		
			"XoopsSecurity" 				=> "/class/xoopssecurity.php", 		
			"XoopsLogger" 					=> "/class/logger.php", 			
			"XoopsDatabaseFactory" 			=> "/class/database/databasefactory.php",
			"IcmsDatabaseFactory" 			=> "/class/database/databasefactory.php",
			"XoopsObject" 					=> "/kernel/object.php", 				
			"XoopsObjectHandler" 			=> "/kernel/object.php", 				
			"XoopsLists" 					=> "/class/xoopslists.php", 			
			"IcmsLists" 					=> "/class/xoopslists.php", 			
			"XoopsThemeForm"				=> "/class/xoopsform/themeform.php",
			"XoopsFormHidden"		 		=> "/class/xoopsform/formhidden.php",
			"XoopsFormText" 		 	 	=> "/class/xoopsform/formtext.php",
			"XoopsFormElement"				=> "/class/xoopsform/formelement.php",
			"XoopsForm"						=> "/class/xoopsform/form.php",
			"XoopsFormElementLabel"			=> "/class/xoopsform/formlabel.php",
			"XoopsFormSelect"				=> "/class/xoopsform/formselect.php",
			"XoopsFormPassword"				=> "/class/xoopsform/formpassword.php",
			"XoopsFormButton"				=> "/class/xoopsform/formbutton.php",
			"XoopsFormCheckBox"				=> "/class/xoopsform/formcheckbox.php",
			"XoopsFormFile"					=> "/class/xoopsform/formfile.php",
			"XoopsFormRadio"				=> "/class/xoopsform/formradio.php",
			"XoopsFormRadioYN"				=> "/class/xoopsform/formradioyn.php",
			"XoopsFormSelectCountry"		=> "/class/xoopsform/formselectcountry.php",
			"XoopsFormSelectTimezone"		=> "/class/xoopsform/formselecttimezone.php",
			"XoopsFormSelectLang"			=> "/class/xoopsform/formselectlang.php",
			"XoopsFormSelectGroup"			=> "/class/xoopsform/formselectgroup.php",
			"XoopsFormSelectUser"			=> "/class/xoopsform/formselectuser.php",
			"XoopsFormSelectTheme"			=> "/class/xoopsform/formselecttheme.php",
			"XoopsFormSelectMatchOption"	=> "/class/xoopsform/formselectmatchoption.php",
			"XoopsFormText"					=> "/class/xoopsform/formtext.php",
			"XoopsFormTextArea"				=> "/class/xoopsform/formtextarea.php",
			"XoopsFormDhtmlTextArea"		=> "/class/xoopsform/formdhtmltextarea.php",
			"XoopsFormElementTray"			=> "/class/xoopsform/formelementtray.php",
			"XoopsThemeForm"				=> "/class/xoopsform/themeform.php",
			"XoopsSimpleForm"				=> "/class/xoopsform/simpleform.php",
			"XoopsFormTextDateSelect"		=> "/class/xoopsform/formtextdateselect.php",
			"XoopsFormDateTime"				=> "/class/xoopsform/formdatetime.php",
			"XoopsFormHiddenToken"			=> "/class/xoopsform/formhiddentoken.php",
			"XoopsFormColorPicker"			=> "/class/xoopsform/formcolorpicker.php",
			"XoopsFormSelectEditor"			=> "/class/xoopsform/formselecteditor.php",
			"XoopsFormCaptcha"				=> "/class/xoopsform/formcaptcha.php",
			"IcmsFormCaptcha"				=> "/class/xoopsform/formcaptcha.php",
			"CriteriaCompo"                 => "/class/criteria.php",
			"Criteria"                      => "/class/criteria.php",
			"CriteriaElement"               => "/class/criteria.php",
			"IcmsPersistableObjectHandler"	=> "/kernel/icmspersistableobjecthandler.php"
		);
		if (in_array($class, array_keys($legacyClassPath))) {
			include_once ICMS_ROOT_PATH . $legacyClassPath[$class];
		}
	}
}
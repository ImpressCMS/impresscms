<?php
/**
 * ImpressCMS Composer Autoload Bridge
 *
 * This class provides backward compatibility between the legacy icms_Autoloader
 * and Composer's PSR-4 autoloader, ensuring a smooth transition without breaking
 * existing code.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @since		1.5
 * @author		ImpressCMS Modernization Team
 */

class icms_ComposerAutoloadBridge {
    
    /**
     * Whether the bridge has been initialized
     * @var bool
     */
    private static $initialized = false;
    
    /**
     * Legacy autoloader instance
     * @var icms_Autoloader
     */
    private static $legacyAutoloader = null;
    
    /**
     * Class aliases for backward compatibility
     * @var array
     */
    private static $classAliases = array();
    
    /**
     * Initialize the bridge between Composer and legacy autoloaders
     */
    public static function initialize() {
        if (self::$initialized) {
            return;
        }
        
        // Register our bridge autoloader with high priority
        spl_autoload_register(array(__CLASS__, 'autoload'), true, true);
        
        // Keep reference to legacy autoloader for fallback
        if (class_exists('icms_Autoloader', false)) {
            self::$legacyAutoloader = 'icms_Autoloader';
        }
        
        // Set up class aliases for common patterns
        self::setupClassAliases();
        
        self::$initialized = true;
    }
    
    /**
     * Bridge autoloader that handles both PSR-4 and legacy patterns
     * 
     * @param string $class The class name to load
     * @return bool True if class was loaded, false otherwise
     */
    public static function autoload($class) {
        // Handle class aliases first
        if (isset(self::$classAliases[$class])) {
            $realClass = self::$classAliases[$class];
            if (class_exists($realClass, true)) {
                class_alias($realClass, $class);
                return true;
            }
        }
        
        // Try to convert PSR-4 style to PSR-0 for backward compatibility
        if (strpos($class, '\\') !== false) {
            $psr0Class = str_replace('\\', '_', $class);
            if (class_exists($psr0Class, true)) {
                class_alias($psr0Class, $class);
                return true;
            }
        }
        
        // Try legacy autoloader as fallback
        if (self::$legacyAutoloader && method_exists(self::$legacyAutoloader, 'autoload')) {
            return call_user_func(array(self::$legacyAutoloader, 'autoload'), $class);
        }
        
        return false;
    }
    
    /**
     * Set up class aliases for common naming patterns
     */
    private static function setupClassAliases() {
        // Common aliases that might be needed
        self::$classAliases = array(
            // PSR-4 to PSR-0 mappings
            'Icms\\Core\\Security' => 'icms_core_Security',
            'Icms\\Core\\Logger' => 'icms_core_Logger',
            'Icms\\Core\\Session' => 'icms_core_Session',
            'Icms\\Auth\\Factory' => 'icms_auth_Factory',
            'Icms\\Config\\Handler' => 'icms_config_Handler',
            'Icms\\Module\\Object' => 'icms_module_Object',
            'Icms\\Module\\Handler' => 'icms_module_Handler',
            
            // Legacy XOOPS compatibility
            'XoopsObject' => 'icms_core_Object',
            'XoopsObjectHandler' => 'icms_core_ObjectHandler',
        );
    }
    
    /**
     * Register a class alias
     * 
     * @param string $alias The alias name
     * @param string $realClass The real class name
     */
    public static function registerAlias($alias, $realClass) {
        self::$classAliases[$alias] = $realClass;
    }
    
    /**
     * Register multiple class aliases
     * 
     * @param array $aliases Array of alias => realClass mappings
     */
    public static function registerAliases(array $aliases) {
        self::$classAliases = array_merge(self::$classAliases, $aliases);
    }
    
    /**
     * Check if Composer autoloader is available
     * 
     * @return bool True if Composer autoloader is loaded
     */
    public static function isComposerAvailable() {
        return class_exists('Composer\\Autoload\\ClassLoader', false);
    }
    
    /**
     * Get the legacy autoloader instance
     * 
     * @return string|null The legacy autoloader class name
     */
    public static function getLegacyAutoloader() {
        return self::$legacyAutoloader;
    }
    
    /**
     * Disable the bridge (for testing purposes)
     */
    public static function disable() {
        if (self::$initialized) {
            spl_autoload_unregister(array(__CLASS__, 'autoload'));
            self::$initialized = false;
        }
    }
}

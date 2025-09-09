<?php
/**
 * ImpressCMS Autoload Performance Monitor
 *
 * This class provides performance monitoring and optimization tools
 * for the autoloading system.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @since		1.5
 */

class icms_AutoloadPerformanceMonitor {
    
    /**
     * Performance metrics
     * @var array
     */
    private static $metrics = array(
        'class_loads' => 0,
        'load_times' => array(),
        'failed_loads' => 0,
        'cache_hits' => 0,
        'cache_misses' => 0
    );
    
    /**
     * Whether monitoring is enabled
     * @var bool
     */
    private static $enabled = false;
    
    /**
     * Start performance monitoring
     */
    public static function enable() {
        if (!self::$enabled) {
            self::$enabled = true;
            self::registerMonitoringAutoloader();
        }
    }
    
    /**
     * Stop performance monitoring
     */
    public static function disable() {
        self::$enabled = false;
    }
    
    /**
     * Register a monitoring autoloader
     */
    private static function registerMonitoringAutoloader() {
        spl_autoload_register(array(__CLASS__, 'monitoringAutoloader'), true, true);
    }
    
    /**
     * Monitoring autoloader that tracks performance
     * 
     * @param string $class The class name
     * @return bool
     */
    public static function monitoringAutoloader($class) {
        if (!self::$enabled) {
            return false;
        }
        
        $startTime = microtime(true);
        
        // Let other autoloaders handle the actual loading
        $loaded = false;
        $autoloaders = spl_autoload_functions();
        
        foreach ($autoloaders as $autoloader) {
            if ($autoloader === array(__CLASS__, 'monitoringAutoloader')) {
                continue; // Skip ourselves
            }
            
            if (is_callable($autoloader)) {
                if (call_user_func($autoloader, $class)) {
                    $loaded = true;
                    break;
                }
            }
        }
        
        $endTime = microtime(true);
        $loadTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        // Record metrics
        if ($loaded) {
            self::$metrics['class_loads']++;
            self::$metrics['load_times'][] = $loadTime;
            
            // Check if it was a cache hit (very fast load)
            if ($loadTime < 0.1) {
                self::$metrics['cache_hits']++;
            } else {
                self::$metrics['cache_misses']++;
            }
        } else {
            self::$metrics['failed_loads']++;
        }
        
        return $loaded;
    }
    
    /**
     * Get performance metrics
     * 
     * @return array
     */
    public static function getMetrics() {
        $metrics = self::$metrics;
        
        if (!empty($metrics['load_times'])) {
            $metrics['average_load_time'] = array_sum($metrics['load_times']) / count($metrics['load_times']);
            $metrics['max_load_time'] = max($metrics['load_times']);
            $metrics['min_load_time'] = min($metrics['load_times']);
        }
        
        $metrics['cache_hit_ratio'] = 0;
        if (($metrics['cache_hits'] + $metrics['cache_misses']) > 0) {
            $metrics['cache_hit_ratio'] = $metrics['cache_hits'] / ($metrics['cache_hits'] + $metrics['cache_misses']);
        }
        
        return $metrics;
    }
    
    /**
     * Reset performance metrics
     */
    public static function resetMetrics() {
        self::$metrics = array(
            'class_loads' => 0,
            'load_times' => array(),
            'failed_loads' => 0,
            'cache_hits' => 0,
            'cache_misses' => 0
        );
    }
    
    /**
     * Generate a performance report
     * 
     * @return string
     */
    public static function generateReport() {
        $metrics = self::getMetrics();
        
        $report = "ImpressCMS Autoload Performance Report\n";
        $report .= "=====================================\n\n";
        
        $report .= "Classes Loaded: " . $metrics['class_loads'] . "\n";
        $report .= "Failed Loads: " . $metrics['failed_loads'] . "\n";
        $report .= "Cache Hits: " . $metrics['cache_hits'] . "\n";
        $report .= "Cache Misses: " . $metrics['cache_misses'] . "\n";
        $report .= "Cache Hit Ratio: " . number_format($metrics['cache_hit_ratio'] * 100, 2) . "%\n\n";
        
        if (isset($metrics['average_load_time'])) {
            $report .= "Average Load Time: " . number_format($metrics['average_load_time'], 3) . "ms\n";
            $report .= "Max Load Time: " . number_format($metrics['max_load_time'], 3) . "ms\n";
            $report .= "Min Load Time: " . number_format($metrics['min_load_time'], 3) . "ms\n\n";
        }
        
        // Performance recommendations
        $report .= "Recommendations:\n";
        $report .= "----------------\n";
        
        if ($metrics['cache_hit_ratio'] < 0.8) {
            $report .= "- Consider enabling APCu for better caching performance\n";
            $report .= "- Run 'composer optimize-production' for production environments\n";
        }
        
        if (isset($metrics['average_load_time']) && $metrics['average_load_time'] > 1.0) {
            $report .= "- Average load time is high, consider optimizing autoloader\n";
            $report .= "- Check for unnecessary file system operations\n";
        }
        
        if ($metrics['failed_loads'] > 0) {
            $report .= "- " . $metrics['failed_loads'] . " failed loads detected, check class naming\n";
        }
        
        return $report;
    }
    
    /**
     * Check if autoloader optimizations are available
     * 
     * @return array
     */
    public static function checkOptimizations() {
        $optimizations = array();
        
        // Check for APCu
        $optimizations['apcu_available'] = extension_loaded('apcu') && apcu_enabled();
        
        // Check for Composer optimizations
        $optimizations['composer_optimized'] = false;
        if (file_exists(ICMS_ROOT_PATH . '/vendor/composer/autoload_classmap.php')) {
            $classmap = include ICMS_ROOT_PATH . '/vendor/composer/autoload_classmap.php';
            $optimizations['composer_optimized'] = !empty($classmap);
        }
        
        // Check for production mode
        $optimizations['production_mode'] = !defined('ICMS_DEBUG') || !ICMS_DEBUG;
        
        return $optimizations;
    }
}

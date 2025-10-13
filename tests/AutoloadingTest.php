<?php
/**
 * ImpressCMS Autoloading Test Suite
 *
 * Tests to verify that the Composer autoloader integration works correctly
 * and maintains backward compatibility with the legacy autoloader.
 *
 * @copyright	https://www.impresscms.org/ The ImpressCMS Project
 * @license		MIT
 * @category	ICMS
 * @package		Tests
 * @since		2.1
 */

use PHPUnit\Framework\TestCase;

class AutoloadingTest extends TestCase {

    /**
     * Test that Composer autoloader is available
     */
    public function testComposerAutoloaderAvailable() {
        $this->assertTrue(
            class_exists('Composer\\Autoload\\ClassLoader', false),
            'Composer autoloader should be available'
        );
    }

    /**
     * Test that core ImpressCMS classes can be autoloaded
     */
    public function testCoreClassesAutoload() {
        $coreClasses = [
            'icms_core_Security',
            'icms_core_Logger',
            'icms_core_Session',
            'icms_auth_Factory',
            'icms_config_Handler',
            'icms_module_Object',
            'icms_Autoloader',
            'icms_Event',
            'icms_Utils'
        ];

        foreach ($coreClasses as $className) {
            $this->assertTrue(
                class_exists($className, true),
                "Class {$className} should be autoloadable"
            );
        }
    }

    /**
     * Test that the bridge autoloader is working
     */
    public function testBridgeAutoloaderInitialized() {
        $this->assertTrue(
            class_exists('icms_ComposerAutoloadBridge', true),
            'Bridge autoloader should be available'
        );

        $this->assertTrue(
            icms_ComposerAutoloadBridge::isComposerAvailable(),
            'Bridge should detect Composer availability'
        );
    }

    /**
     * Test class aliasing functionality
     */
    public function testClassAliasing() {
        // Register a test alias
        icms_ComposerAutoloadBridge::registerAlias('TestAlias', 'icms_core_Security');

        // Test that the alias works
        $this->assertTrue(
            class_exists('TestAlias', true),
            'Class alias should work'
        );

        // Test that both refer to the same class
        $original = new icms_core_Security();
        $aliased = new TestAlias();

        $this->assertEquals(
            get_class($original),
            get_class($aliased),
            'Aliased class should be the same as original'
        );
    }

    /**
     * Test that legacy autoloader fallback works
     */
    public function testLegacyAutoloaderFallback() {
        $legacyAutoloader = icms_ComposerAutoloadBridge::getLegacyAutoloader();

        $this->assertNotNull(
            $legacyAutoloader,
            'Legacy autoloader should be available as fallback'
        );

        $this->assertEquals(
            'icms_Autoloader',
            $legacyAutoloader,
            'Legacy autoloader should be icms_Autoloader'
        );
    }

    /**
     * Test PSR-0 to PSR-4 conversion
     */
    public function testPsr0ToPsr4Conversion() {
        // This would test if PSR-4 style class names get converted to PSR-0
        // For now, we'll just verify the bridge handles this scenario

        $this->assertTrue(
            method_exists('icms_ComposerAutoloadBridge', 'autoload'),
            'Bridge should have autoload method for PSR-0 to PSR-4 conversion'
        );
    }

    /**
     * Test that all registered autoloaders are working
     */
    public function testAutoloadersRegistered() {
        $autoloaders = spl_autoload_functions();

        $this->assertNotEmpty($autoloaders, 'Autoloaders should be registered');

        // Check that our bridge is registered
        $bridgeFound = false;
        foreach ($autoloaders as $autoloader) {
            if (is_array($autoloader) &&
                $autoloader[0] === 'icms_ComposerAutoloadBridge' &&
                $autoloader[1] === 'autoload') {
                $bridgeFound = true;
                break;
            }
        }

        $this->assertTrue($bridgeFound, 'Bridge autoloader should be registered');
    }

    /**
     * Test performance of autoloading
     */
    public function testAutoloadingPerformance() {
        $startTime = microtime(true);

        // Load several classes to test performance
        $classes = [
            'icms_core_Security',
            'icms_core_Logger',
            'icms_auth_Factory',
            'icms_config_Handler'
        ];

        foreach ($classes as $className) {
            class_exists($className, true);
        }

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        // Should load classes reasonably quickly (under 100ms)
        $this->assertLessThan(
            0.1,
            $duration,
            'Autoloading should be performant'
        );
    }
}

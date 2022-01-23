<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class PluginsTest extends TestCase {

	/**
	 * Test if is available
	 */
	public function testAvailability() {
		foreach ([
			'icms_plugins_Handler',
			'icms_plugins_Object'
		] as $class) {
			$this->assertTrue(class_exists($class, true), $class . ' does\'t exist');
		}
	}

	/**
	 * Gets instance of class from classname
	 *
	 * @param string $class     ClassName
	 *
	 * @return object
	 */
	private function getClassInstance($class) {
		$instance = $this->getMockBuilder($class)
					->disableOriginalConstructor()
					->getMock();
		return $instance;
	}

	/**
	 * Test methods availability
	 */
	public function testMethodsAvailability() {
		foreach ([
			'icms_plugins_Object' => [
				'getItemInfo',
				'getItemList',
				'getItem',
				'getItemIdForItem'
			],
			'icms_plugins_Handler' => [
				'getPlugin',
				'getPluginsArray'
			],
		] as $class => $methods) {
			foreach ($methods as $method) {
				$this->assertTrue(method_exists($class, $method), 'Static method ' . $method . ' doesn\'t exists for class ' . $class);
			}
		}
	}

	/**
	 * Tests variables availability and types
	 */
	public function testVariables() {
		foreach ([
			'icms_plugins_Handler' => [
				'pluginPatterns' => 'assertIsBool'
			],
			'icms_plugins_Object' => [
				'_infoArray' => 'assertIsArray'
			]
		] as $class => $variables) {
			$instance = $this->getClassInstance($class);
			foreach ($variables as $variable => $func) {
				$this->$func($instance->$variable, '$' . $variable . ' is not of correct type ');
			}
		}
	}

}
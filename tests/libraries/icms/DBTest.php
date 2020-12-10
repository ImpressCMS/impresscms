<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use icms_db_criteria_Item;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class DBTest extends TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
            'icms_db_Factory' => null,
            'icms_db_criteria_Element' => null,
            'icms_db_criteria_Compo' => ['icms_db_criteria_Element'],
            'icms_db_criteria_Item' => ['icms_db_criteria_Element'],
            'icms_db_legacy_mysql_Utility' => ['icms_db_IUtility'],
            'icms_db_legacy_updater_Handler' => null,
            'icms_db_legacy_updater_Table' => null,
            'icms_db_legacy_Factory' => ['icms_db_Factory'],
        //    'icms_db_mysql_Utility' => ['icms_db_IUtility'],
            'icms_db_Connection' => [
                'PDO',
                'icms_db_IConnection'
            ]
        ] as $class => $must_be_instances_of) {
            $this->assertTrue(class_exists($class, true), $class . ' does\'t exist');
            if ($must_be_instances_of === null) {
                continue;
            }
            $instance = $this->getClassInstance($class);
            foreach ($must_be_instances_of as $must_be_instance_of) {
                $this->assertInstanceOf($must_be_instance_of, $instance, $class . ' must be instance of ' . $must_be_instance_of . ' but is not');
            }
        }
    }

	/**
	 * Gets instance of class from classname
	 *
	 * @param string $class ClassName
	 *
	 * @return object
	 * @throws ReflectionException
	 */
    private function getClassInstance($class) {
        $reflection = new ReflectionClass($class);
        if ($reflection->isAbstract()) {
            $instance = $this->getMockForAbstractClass($class);
        } else {
            $instance = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        }
        return $instance;
    }

    /**
     * Test methods availability
     */
    public function testMethodsAvailability() {
        foreach ([
            'icms_db_criteria_Compo' => [
                'add',
                'render',
                'renderWhere',
                'renderLdap'
            ],
            'icms_db_criteria_Element' => [
                'render',
                'setSort',
                'getSort',
                'setOrder',
                'getOrder',
                'setLimit',
                'getLimit',
                'setStart',
                'getStart',
                'setGroupby',
                'getGroupby'
            ],
            'icms_db_criteria_Item' => [
                'render',
                'renderLdap',
                'renderWhere'
            ],
            'icms_db_legacy_updater_Handler' => [
                'runQuery',
                'renameTable',
                'updateTable',
                'automaticUpgrade',
                'getFieldTypeFromVar',
                'getFieldDefaultFromVar',
                'uninstallObjectItem',
                'upgradeObjectItem',
                'insertConfig',
                'moduleUpgrade',
                'updateModuleDBVersion'
            ],
            'icms_db_legacy_updater_Table' => [
                'name',
                'exists',
                'getExistingFieldsArray',
                'fieldExists',
                'setStructure',
                'getStructure',
                'setData',
                'getData',
                'addData',
                'addAlteredField',
                'addNewField',
                'getAlteredFields',
                'addUpdateAll',
                'addDeleteAll',
                'getNewFields',
                'getUpdateAll',
                'getDeleteAll',
                'addDropedField',
                'getDropedFields',
                'setFlagForDrop',
                'createTable',
                'dropTable',
                'alterTable',
                'addNewFields',
                'updateAll',
                'deleteAll',
                'dropFields'
            ],
            'icms_db_Connection' => [
                'escape',
                'query'
            ]
        ] as $class => $methods) {
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($class, $method), 'Static method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }

    /**
     * Test static method availability
     */
    public function testStaticMethodsAvailability() {
        foreach ([
            'icms_db_legacy_mysql_Utility' => [
                'splitMySqlFile',
                'splitSqlFile',
                'prefixQuery',
                'checkSQL'
            ],
            'icms_db_legacy_Factory' => [
                'instance',
                'getDatabase',
                'getDatabaseUpdater'
            ],
            /*'icms_db_mysql_Utility' => [
                'prefixQuery',
                'splitSqlFile',
                'checkSQL'
            ],*/
            'icms_db_Factory' => [
                'pdoInstance',
                'instance'
            ]
        ] as $class => $methods) {
            $instance = $this->getClassInstance($class);
            foreach ($methods as $method) {
                $this->assertTrue(method_exists($instance, $method), 'Method ' . $method . ' doesn\'t exists for class ' . $class);
            }
        }
    }

    /**
     * Tests variables availability and types
     */
    public function testVariables() {
        foreach ([
            'icms_db_criteria_Compo' => [
                'criteriaElements' => 'assertIsArray',
                'conditions' => 'assertIsArray'
            ],
            'icms_db_criteria_Item' => [
                'prefix' => 'assertIsString',
                'function' => 'assertIsString',
                'column' => 'assertIsString',
                'operator' => 'assertIsString',
                'value' => 'mixed'
            ],
            'icms_db_criteria_Element' => [
                'order' => 'assertIsString',
                'sort' => 'assertIsString',
                'limit' => 'assertIsInt',
                'start' => 'assertIsInt',
                'groupby' => 'assertIsString'
            ],
            'icms_db_legacy_updater_Handler' => [
                'db' => 'assertNull'
            ],
            'icms_db_legacy_updater_Table' => [
                'force' => 'assertIsBool',
                'db' => 'assertNull'
            ],
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $func) {
                if ($func === 'mixed') {
                    $this->assertTrue(property_exists($instance, $variable), '$' . $variable . ' is not defined in instance of ' . $class);
                } else {
                    $this->$func($instance->$variable, '$' . $variable . ' is not of correct type');
                }
            }
        }
    }

    /**
     * Test how criteria is working
     */
    public function testCriteria() {
        $column = sha1(mt_rand(0, PHP_INT_MAX));
        $value = sha1(mt_rand(0, PHP_INT_MAX));
        $group_by = sha1(mt_rand(0, PHP_INT_MAX));
        $sort_by = sha1(mt_rand(0, PHP_INT_MAX));
        $item = new icms_db_criteria_Item($column, $value);
        foreach (['render', 'renderLdap', 'renderWhere'] as $method) {
            $rendered = $item->$method();
            $this->assertNotNull($rendered, 'Rendered with ' . $method . ' criteria result must be not null');
            $this->assertNotSame('', $rendered, 'Rendered with '.$method.' criteria result must be not empty');
            $this->assertIsString( $rendered, 'Rendered with '.$method.' criteria result must be string');
        }
        $item->setGroupby($group_by);
        $this->assertSame($item->groupby, $group_by, 'When set with setGroupBy function result is not modified groupby variable as should be');
        $this->assertTrue(strpos($item->getGroupby(), $group_by) > -1, 'getGroupby returns bad result' );
        $item->setSort($sort_by);
        $this->assertSame($item->sort, $sort_by, 'When set with setSort function result is not modified $sort variable as should be');
        $this->assertTrue(strpos($item->getSort(), $sort_by) > -1, 'getSort returns bad result' );
        $this->assertSame($item->order, $item->getOrder(), 'Variable and function getOrder returns not same data');

        foreach ([
            'order' => ['DESC', 'ASC'],
            'start' => [mt_rand(0, PHP_INT_MAX), mt_rand(0, PHP_INT_MAX)],
            'limit' => [mt_rand(0, PHP_INT_MAX), mt_rand(0, PHP_INT_MAX)],
        ] as $data => $values) {
            $method_set = 'set' . ucfirst($data);
            $method_get = 'get' . ucfirst($data);
            foreach ($values as $value) {
                $item->$method_set($value);
                $fvalue = $item->$method_get();
                $this->assertSame($item->$data, $fvalue, 'Variable $' . $data . ' and function '.$method_get.' returns not same data');
                $this->assertSame($value, $fvalue, 'Data for $' . $data . ' was unchangend');
            }
        }
    }

}
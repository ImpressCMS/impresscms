<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

use ImpressCMS\Core\Models\AbstractExtendedHandler;
use ImpressCMS\Core\Models\AbstractExtendedModel;
use PHPUnit\Framework\TestCase;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ViewTest extends TestCase {

    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
            'icms_view_Tree' => null,
            'icms_view_Tpl' => ['Smarty'],
            'icms_view_Printerfriendly' => null,
            'icms_view_PageNav' => null,
            'icms_view_PageBuilder' => null,
            'icms_view_Breadcrumb' => null,
            'icms_view_theme_Object' => null,
            'icms_view_theme_Factory' => null,
            'icms_view_template_set_Handler' => [AbstractExtendedHandler::class],
            'icms_view_template_set_Object' => [AbstractExtendedModel::class],
            'icms_view_template_file_Object' => [AbstractExtendedModel::class],
            'icms_view_template_file_Handler' => [AbstractExtendedHandler::class],
            'icms_view_block_Object' => [AbstractExtendedModel::class],
            'icms_view_block_Handler' => [AbstractExtendedHandler::class],
            'icms_view_block_position_Handler' => [AbstractExtendedHandler::class],
            'icms_view_block_position_Object' => [AbstractExtendedModel::class]
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
            'icms_view_block_Handler' => [
                'getBlockPositions',
                'getByModule',
                'getAllBlocks',
                'getAllByGroupModule',
                'getNonGroupedBlocks',
                'get',
                'getMultiple',
                'getCountSimilarBlocks',
            ],
            'icms_view_block_Object' => [
                'getContent',
                'getOptions',
                'isCustom',
                'buildBlock',
            ],
            'icms_view_template_file_Handler' => [
                'loadSource',
                'forceUpdate',
                'getModuleTplCount',
                'find',
                'templateExists',
                'prefetchBlocks',
                'getPrefetchedBlock'
            ],
            'icms_view_template_file_Object' => [
                'getSource',
                'getLastModified'
            ],
            'icms_view_template_set_Handler' => [
                'getByName',
                'delete'
            ],
            'icms_view_theme_Factory' => [
                'createInstance',
                'isThemeAllowed'
            ],
            'icms_view_theme_Object' => [
                'xoInit',
                'generateCacheId',
                'checkCache',
                'render',
                'addScript',
                'addStylesheet',
                'addLink',
                'addHttpMeta',
                'addMeta',
                'headContent',
                'renderMetas',
                'renderOldMetas',
                'genElementId',
                'renderAttributes',
                'resourcePath'
            ],
            'icms_view_Breadcrumb' => [
                'render'
            ],
            'icms_view_PageBuilder' => [
                'xoInit',
                'preRender',
                'postRender',
                'retrieveBlocks',
                'generateCacheId',
                'buildBlock'
            ],
            'icms_view_PageNav' => [
                'renderNav',
                'renderSelect',
                'renderImageNav'
            ],
            'icms_view_Printerfriendly' => [
                'setPageTitle',
                'setWidth',
                'render'
            ],
            'icms_view_Tpl' => [
                'fetchFromData',
                'touch'
            ],
            'icms_view_Tree' => [
                'getFirstChild',
                'getFirstChildId',
                'getAllChildId',
                'getAllParentId',
                'getPathFromId',
                'makeMySelBox',
                'getNicePathFromId',
                'getIdPathFromId',
                'getAllChild',
                'getChildTreeArray'
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
            'icms_view_PageBuilder' => [
                'getPage'
            ],
            'icms_view_Printerfriendly' => [
                'generate'
            ],
            'icms_view_Tpl' => [
                'template_touch',
                'template_clear_module_cache'
            ],
            'icms_view_theme_Factory' => [
                'getThemesList',
                'getAdminThemesList'
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
    public function notestVariables() {
        foreach ([
            'icms_view_Tree' => [
                //'table' => 'assertIsString',
                'id'    => 'assertIsString',
                'pid'   => 'assertIsString',
                'order' => 'assertIsInt',
                'title' => 'assertIsString'
            ],
            'icms_view_Tpl' => [
                'left_delimiter' => 'assertIsString',
                'right_delimiter' => 'assertIsString',
                'template_dir' => 'assertIsString',
                'cache_dir' => 'assertIsString',
                'compile_dir' => 'assertIsString',
            ],
            'icms_view_Printerfriendly' => [
                '_title' => 'assertIsString',
                '_dsc' => 'assertIsString',
                '_content' => 'assertIsString',
                '_tpl' => 'assertIsString',
                '_pageTitle' => 'assertIsBool',
                '_width' => 'assertIsInt'
            ],
            'icms_view_PageNav' => [
                'total' => 'assertIsInt',
                'perpage' => 'assertIsInt',
                'current' => 'assertIsInt',
                'url' => 'assertIsString'
            ],
            'icms_view_PageBuilder' => [
                'theme' => 'assertIsBool',
                'blocks' => 'assertIsArray',
                'uagroups' => 'assertIsArray'
            ],
            'icms_view_theme_Object' => [
                'folderName' => 'assertIsString',
                'path' => 'assertIsString',
                'url' => 'assertIsString',
                'bufferOutput' => 'assertIsBool',
                'canvasTemplate' => 'assertIsString',
                'contentTemplate' => 'assertIsString',
                'contentCacheLifetime' => 'assertIsInt',
                'contentCacheId' => 'assertNull',
                'content' => 'assertIsString',
                'plugins' => 'assertIsArray',
                'renderCount' => 'assertIsInt',
                'template' => 'assertIsBool',
                'metas' => 'assertIsArray',
                'types' => 'assertIsArray',
                'htmlHeadStrings' => 'assertIsArray',
                'templateVars' => 'assertIsArray',
                'use_extra_cache_id' => 'assertIsBool'
            ],
            'icms_view_theme_Factory' => [
                'xoBundleIdentifier' => 'assertIsString',
                'allowedThemes' => 'assertIsArray',
                'defaultTheme' => 'assertIsString',
                'allowUserSelection' => 'assertIsBool'
            ],
            'icms_view_template_file_Object' => [
                'tpl_source' => 'assertIsBool'
            ],
            'icms_view_block_Object' => [
                'visiblein' => 'assertIsArray'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $func) {
                $this->$func($instance->$variable, '$' . $variable . ' is not of type ' . $type . ' in instance of ' . $class);
            }
        }
    }

}
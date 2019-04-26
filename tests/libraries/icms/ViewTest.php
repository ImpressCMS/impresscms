<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class ViewTest extends \PHPUnit_Framework_TestCase {

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
            'icms_view_template_set_Handler' => ['icms_ipf_Handler'],
            'icms_view_template_set_Object' => ['icms_ipf_Object'],
            'icms_view_template_file_Object' => ['icms_ipf_Object'],
            'icms_view_template_file_Handler' => ['icms_ipf_Handler'],
            'icms_view_block_Object' => ['icms_ipf_Object'],
            'icms_view_block_Handler' => ['icms_ipf_Handler'],
            'icms_view_block_position_Handler' => ['icms_ipf_Handler'],
            'icms_view_block_position_Object' => ['icms_ipf_Object']
        ] as $class => $must_be_instances_of) {
            $this->assertTrue(class_exists($class, true), $class . ' does\'t exist');
            if ($must_be_instances_of === null) {
                continue;
            }
            $instance = $this->getClassInstance($class);
            foreach ($must_be_instances_of as $must_be_instance_of) {
                $this->assertTrue($instance instanceof $must_be_instance_of, $class . ' must be instance of ' . $must_be_instance_of . ' but is not');
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
                'getAllBlocksByGroup'
            ],
            'icms_view_block_Object' => [
                'getContent',
                'getOptions',
                'isCustom',
                'buildBlock',
                'buildContent',
                'buildTitle'
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
                //'table' => 'string',
                'id'    => 'string',
                'pid'   => 'string',
                'order' => 'int',
                'title' => 'string'
            ],
            'icms_view_Tpl' => [
                'left_delimiter' => 'string',
                'right_delimiter' => 'string',
                'template_dir' => 'string',
                'cache_dir' => 'string',
                'compile_dir' => 'string',
            ],
            'icms_view_Printerfriendly' => [
                '_title' => 'string',
                '_dsc' => 'string',
                '_content' => 'string',
                '_tpl' => 'string',
                '_pageTitle' => 'bool',
                '_width' => 'int'
            ],
            'icms_view_PageNav' => [
                'total' => 'int',
                'perpage' => 'int',
                'current' => 'int',
                'url' => 'string'
            ],
            'icms_view_PageBuilder' => [
                'theme' => 'bool',
                'blocks' => 'array',
                'uagroups' => 'array'
            ],
            'icms_view_theme_Object' => [
                'folderName' => 'string',
                'path' => 'string',
                'url' => 'string',
                'bufferOutput' => 'bool',
                'canvasTemplate' => 'string',
                'contentTemplate' => 'string',
                'contentCacheLifetime' => 'int',
                'contentCacheId' => 'null',
                'content' => 'string',
                'plugins' => 'array',
                'renderCount' => 'int',
                'template' => 'bool',
                'metas' => 'array',
                'types' => 'array',
                'htmlHeadStrings' => 'array',
                'templateVars' => 'array',
                'use_extra_cache_id' => 'bool'
            ],
            'icms_view_theme_Factory' => [
                'xoBundleIdentifier' => 'string',
                'allowedThemes' => 'array',
                'defaultTheme' => 'string',
                'allowUserSelection' => 'bool'
            ],
            'icms_view_template_file_Object' => [
                'tpl_source' => 'bool'
            ],
            'icms_view_block_Object' => [
                'visiblein' => 'array'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $type) {
                $this->assertInternalType($type, $instance->$variable, '$' . $variable . ' is not of type ' . $type . ' in instance of ' . $class);
            }
        }
    }

}
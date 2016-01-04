<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

class FormTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if is available
     */
    public function testAvailability() {
        foreach ([
            'icms_form_Element' => null,
            'icms_form_Base' => null,            
            'icms_form_Theme' => ['icms_form_Base'],
            'icms_form_Table' => ['icms_form_Base'],
            'icms_form_Simple' => ['icms_form_Base'],
            'icms_form_Groupperm' => ['icms_form_Base'],
            'icms_form_elements_Tray' => ['icms_form_Element'],
            'icms_form_elements_Textarea' => ['icms_form_Element'],
            'icms_form_elements_Text' => ['icms_form_Element'],
            'icms_form_elements_Select' => ['icms_form_Element'],
            'icms_form_elements_Radio' => ['icms_form_Element'],
            'icms_form_elements_Radioyn' => ['icms_form_elements_Radio'],                        
            'icms_form_elements_Password' => ['icms_form_Element'],
            'icms_form_elements_Label' => ['icms_form_Element'],
            'icms_form_elements_Hiddentoken' => ['icms_form_elements_Hidden'],
            'icms_form_elements_Hidden' => ['icms_form_Element'],
            'icms_form_elements_Groupperm' => ['icms_form_Element'],
            'icms_form_elements_File' => ['icms_form_Element'],
            'icms_form_elements_Editor' => ['icms_form_elements_Textarea'],
            'icms_form_elements_Dhtmltextarea' => ['icms_form_elements_Textarea'],
            'icms_form_elements_Datetime' => ['icms_form_elements_Tray'],
            'icms_form_elements_Date' => ['icms_form_elements_Text'],            
            'icms_form_elements_Colorpicker' => ['icms_form_elements_Text'],
            'icms_form_elements_Checkbox' => ['icms_form_Element'],            
            'icms_form_elements_Captcha' => ['icms_form_Element'],
            'icms_form_elements_Button' => ['icms_form_Element'],
            'icms_form_elements_select_Country' => ['icms_form_elements_Select'],
            'icms_form_elements_select_Editor' => ['icms_form_elements_Tray'],
            'icms_form_elements_select_Group' => ['icms_form_elements_Select'],
            'icms_form_elements_select_Image' => ['icms_form_elements_Select'],
            'icms_form_elements_select_Lang' => ['icms_form_elements_Select'],
            'icms_form_elements_select_Matchoption' => ['icms_form_elements_Select'],
            'icms_form_elements_select_Theme' => ['icms_form_elements_Select'],
            'icms_form_elements_select_Timezone' => ['icms_form_elements_Select'],
            'icms_form_elements_select_User' => ['icms_form_elements_Tray'],
            'icms_form_elements_captcha_Text' => null,
            'icms_form_elements_captcha_Object' => null,
            'icms_form_elements_captcha_ImageHandler' => null,
            'icms_form_elements_captcha_Image' => null
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
            'icms_form_elements_captcha_Image' => [
                'loadConfig',
                'render',
                'loadImage'
            ],
            'icms_form_elements_captcha_ImageHandler' => [
                'loadImage',
                'createCode',
                'setCode',
                'createImage',
                'createImageGd',
                '_getList',
                'loadFont',
                'setImageSize',
                'loadBackground',
                'createFromFile',
                'drawCode',
                'drawBorder',
                'drawCircles',
                'drawLines',
                'drawRectangles',
                'drawBars',
                'drawEllipses',
                'drawPolygons',
                'createImageBmp'
            ],
            'icms_form_elements_captcha_Object' => [
                'setConfig',
                'setMode',
                'init',
                'verify',
                'getCaption',
                'getMessage',
                'destroyGarbage',
                'render',
                'loadForm'
            ],
            'icms_form_elements_captcha_Text' => [
                'loadConfig',
                'setCode',
                'render',
                'loadText'
            ],
            'icms_form_elements_select_Image' => [
                'addOptGroup',
                'addOptGroupArray',
                'getImageList',
                'getOptGroups',
                'getOptGroupsID',
                'render'
            ],
            'icms_form_elements_Button' => [
                'getValue',
                'setValue',
                'getType',
                'render'
            ],
            'icms_form_elements_Captcha' => [
                'setConfig',
                'render'
            ],
            'icms_form_elements_Checkbox' => [
                'getValue',
                'setValue',
                'addOption',
                'addOptionArray',
                'getOptions',
                'getDelimeter',
                'render'
            ],
            'icms_form_elements_Colorpicker' => [
                'render',
                'renderValidationJS'
            ],
            'icms_form_elements_Date' => [
                'render'
            ],
            'icms_form_elements_Dhtmltextarea' => [
                'render',
                'renderValidationJS'
            ],
            'icms_form_elements_Editor' => [
                'render'
            ],
            
            'icms_form_elements_File' => [
                'getMaxFileSize',
                'render'
            ],
            'icms_form_elements_Groupperm' => [
                'setValue',
                'setOptionTree',
                'render',
                '_renderOptionTree'
            ],
            'icms_form_elements_Hidden' => [
                'getValue',
                'setValue',
                'render'
            ],
            'icms_form_elements_Label' => [
                'getValue',
                'render'
            ],
            'icms_form_elements_Password' => [
                'getSize',
                'getMaxlength',
                'getValue',
                'setValue',
                'setClassName',
                'getClassName',
                'render'
            ],
            'icms_form_elements_Radio' => [
                'getValue',
                'setValue',
                'addOption',
                'addOptionArray',
                'getOptions',
                'getDelimeter',
                'render'
            ],
            'icms_form_elements_Select' => [
                'isMultiple',
                'getSize',
                'getValue',
                'setValue',
                'addOption',
                'addOptionArray',
                'getOptions',
                'render'
            ],
            'icms_form_elements_Text' => [
                'getSize',
                'getMaxlength',
                'getValue',
                'setValue',
                'render'
            ],
            'icms_form_elements_Textarea' => [
                'getRows',
                'getCols',
                'getValue',
                'setValue',
                'render'
            ],
            'icms_form_elements_Tray' => [
                'isContainer',
                'isRequired',
                'addElement',
                'getRequired',
                'getElements',
                'getDelimeter',
                'render'
            ],
            'icms_form_Base' => [
                'getTitle',
                'getName',
                'getAction',
                'getMethod',
                'addElement',
                'getElements',
                'getElementNames',
                'getElementByName',
                'setElementValue',
                'setElementValues',
                'getElementValue',
                'getElementValues',
                'setExtra',
                'getExtra',
                'setRequired',
                'getRequired',
                'display',
                'renderValidationJS',
                'assign'
            ],
            'icms_form_Element' => [
                'isContainer',
                'setName',
                'getName',
                'setAccessKey',
                'getAccessKey',
                'getAccessString',
                'setClass',
                'getClass',
                'setCaption',
                'getCaption',
                'setDescription',
                'getDescription',
                'setHidden',
                'isHidden',
                'isRequired',
                'setRequired',
                'setExtra',
                'getExtra',
                'renderValidationJS'
            ],
            'icms_form_Groupperm' => [
                'addItem',
                'render',
                'insertBreak'
            ],
            'icms_form_Simple' => [
                'render'
            ],
            'icms_form_Table' => [
                'insertBreak',
                'render'
            ],
            'icms_form_Theme' => [
                'insertBreak',
                'render'
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
            'icms_form_elements_captcha_Image' => [
                'instance'
            ],
            'icms_form_elements_captcha_Object' => [
                'instance'
            ],
            'icms_form_elements_captcha_Text' => [
                'instance'
            ],
            'icms_form_elements_select_Country' => [
                'getCountryList'
            ],            
            'icms_form_elements_select_Timezone' => [
                'getTimeZoneList'
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
            'icms_form_elements_captcha_ImageHandler' => [
                'invalid' => 'bool'
            ],
            'icms_form_elements_captcha_Object' => [
                'active' => 'bool',
                'mode' => 'string',
                'config' => 'array',
                'message' => 'array'
            ],
            'icms_form_elements_captcha_Text' => [
                'config' => 'array',
                'code' => 'null'
            ],
            'icms_form_elements_Dhtmltextarea' => [
                'htmlEditor' => 'array'
            ],
            'icms_form_elements_Text' => [
                'autocomplete' => 'bool'
            ],
            'icms_form_Element' => [
                'customValidationCode' => 'array'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $type) {
                $this->assertInternalType($type, $instance->$variable, '$' . $variable . ' is not of type ' . $type . ' in instance of ' . $class);
            }
        }
    }    
    
}
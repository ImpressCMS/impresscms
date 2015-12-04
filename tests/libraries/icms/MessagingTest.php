<?php

namespace ImpressCMS\Tests\Libraries\ICMS;

class MessagingTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * Test if is available
     */
    public function testAvailability() {
        require_once ICMS_LIBRARIES_PATH . '/phpmailer/class.phpmailer.php';
        foreach ([
            'icms_messaging_EmailHandler' => 'PHPMailer',
            'icms_messaging_Handler' => null
        ] as $class => $must_be_instance_of) {
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
            'icms_messaging_Handler' => [
                'reset',
                'setTemplateDir',
                'setTemplate',
                'setFromEmail',
                'setFromName',
                'setFromUser',
                'setPriority',
                'setSubject',
                'setBody',
                'useMail',
                'usePM',
                'send',
                'sendPM',
                'sendMail',
                'getErrors',
                'getSuccess',
                'assign',
                'addHeaders',
                'setToEmails',
                'setToUsers',
                'setToGroups'
            ],
            'icms_messaging_EmailHandler' => [
                'AddrFormat',
                'encodeFromName',
                'encodeSubject',
                'encodeBody'
            ]
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
            'icms_messaging_EmailHandler' => [
                'From' => 'string',
                'FromName' => 'string',
                'Mailer' => 'string',
                'Sendmail' => 'string',
                'Host' => 'string',
                'SMTPSecure' => 'string',
                'SMTPAuth' => 'bool',
                'Username' => 'string',
                'Password' => 'string',
                'Port' => 'int'
            ]
        ] as $class => $variables) {
            $instance = $this->getClassInstance($class);
            foreach ($variables as $variable => $type) {
                $this->assertInternalType($type, $instance->$variable, '$' . $variable . ' is not of type ' . $type . ' in instance of ' . $class);
            }
        }
    }    
    
}
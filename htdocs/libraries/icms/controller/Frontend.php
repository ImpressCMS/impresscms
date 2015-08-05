<?php

/**
 * This is a base class for frontend controllers
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 */
abstract class icms_controller_Frontend
    extends icms_controller_Base {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->assignVars($_REQUEST);
    }
       
    /**
     * Can be this controller executed?
     * 
     * @return bool
     */
    public static function canRun() {
        return PHP_SAPI !== 'cli' && PHP_SAPI !== 'embed';
    }
    
    /**
     * Get folder for controllers of this type
     * 
     * @return string
     */
    public static function getFolder() {
        return 'controllers';
    }        
    
}

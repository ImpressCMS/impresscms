<?php

/**
 * This is a base class for controllers
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 */
abstract class icms_controller_Base 
    extends icms_properties_Handler {
    
    /**
     * Checks if controller can run
     * 
     * @return bool Returns true if can run
     */
    public static function canRun() {
        return false;
    }
    
    /**
     * Get folder for controllers of this type
     * 
     * @return string
     */
    public static function getFolder() {
        return '';
    }
    
    /**
     * Gets current instance of controller
     * 
     * @return null|icms_controller_Base
     */
    public static function getInstance() {
        return null;
    }
    
}

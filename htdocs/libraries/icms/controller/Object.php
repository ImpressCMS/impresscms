<?php

/**
 * This is a base class for controllers
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 * @package         ICMS\Controller
 * @copyright       http://www.impresscms.org/ The ImpressCMS Project 
 */
abstract class icms_controller_Object {
 
    /**
     * This constant defines how controller will parse params
     * It's possible to redefine on extended class
     */
    const PARAMS_FORMAT = '/{@param}/{@value}';
    
}

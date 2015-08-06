<?php

namespace ImpressCMS\Modules\System\Controller;

/**
 * Gives some information for end user
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 */
class info 
    extends \icms_controller_Object {
    
    public function __construct() {
        $this->initVar('msg', self::DTYPE_STRING);
    }
    
    /**
     * Returns version of ImpressCMS
     * 
     * @return \icms_response_Text
     */
    public function getVersion() {        
        return new \icms_response_Text(ICMS_VERSION);
    }
    
    /**
     * Echo same message
     * 
     * @param string $msg
     * 
     * @return \icms_response_Text
     */
    public function getEcho($msg) {
        return new \icms_response_Text($msg);
    }
    
    /**
     * Returns live server time stream
     * 
     * @return \icms_response_Events
     */
    public function getServerTime() {
        $response = new \icms_response_Events();
        
        while(true) {
            $response->sendMessage(date(DATE_ISO8601));
            sleep(1);
        }
        
        return $response;
    }
           
}

<?php

/**
* Creates response of PHP serialize type
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 */
class icms_response_Serialize
    extends icms_response_Text {    
    
    /**
     * Constructor
     * 
     * @param string|null   $msg
     * @param int|null      $http_status    If not null sets http status on sending response
     * @param array         $headers        Here You can place some additional headers 
     */
    public function __construct($msg = null, $http_status = null, $headers = array()) {        
        parent::__construct(serialize($msg), $http_status, $headers);
    }
    
}

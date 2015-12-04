<?php

/**
* Creates response of json type
 *
 * @author      Raimondas Rimkevičius <mekdrop@impresscms.org>
 * @package	ICMS\Response
 */
class icms_response_JSON
    extends icms_response_Text {
    
    /**
     * Mimetype for this response
     */    
    const CONTENT_TYPE = 'application/json';
    
    /**
     * Constructor
     * 
     * @param string|null   $msg
     * @param int|null      $http_status    If not null sets http status on sending response
     * @param array         $headers        Here You can place some additional headers 
     */
    public function __construct($msg = null, $http_status = null, $headers = array()) {        
        parent::__construct(json_encode($msg), $http_status, $headers);
    }
    
}
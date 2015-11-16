<?php

/**
* Creates response of jsonp type
 *
 * @author      Raimondas Rimkevičius <mekdrop@impresscms.org>
 * @package	ICMS\Response
 */
class icms_response_JSONP
    extends icms_response_Text {
    
    /**
     * Mimetype for this response
     */    
    const CONTENT_TYPE = 'application/javascript';
    
    /**
     * Constructor
     * 
     * @param string        $function       JSONP function
     * @param string|null   $msg            Content
     * @param int|null      $http_status    If not null sets http status on sending response
     * @param array         $headers        Here You can place some additional headers 
     */
    public function __construct($function, $msg = null, $http_status = null, $headers = array()) {        
        parent::__construct($function . '(' . json_encode($msg) . ')', $http_status, $headers);
    }
    
}
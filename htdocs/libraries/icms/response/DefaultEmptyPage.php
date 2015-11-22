<?php

/**
* Creates response of PHP serialize type
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 */
class icms_response_DefaultEmptyPage
    extends icms_response_HTML {    
    
    /**
     * Constructor
     */
    public function __construct() {    
        global $xoopsOption;        
        $xoopsOption['show_cblock'] = 1;
        parent::__construct($xoopsOption);
    }
    
    /**
     * Render data
     */
    public function render() {
        $this->assign('icms_contents', ob_get_clean());
        parent::render();
    }
    
}

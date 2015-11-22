<?php

/**
* Creates response of PHP serialize type
 *
 * @author      Raimondas Rimkevičius <mekdrop@impresscms.org>
 * @package	ICMS\Response
 */
class icms_response_DefaultEmptyPage
    extends icms_response_HTML {    
    
    /**
     * Constructor
     */
    public function __construct() {    
        global $xoopsOption;
        ob_start();
        $xoopsOption['show_cblock'] = 1;
	/** Included to start page rendering */
	include ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . "header.php";
	/** Included to complete page rendering */
	include ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . "footer.php";
        $msg = ob_get_clean();
        parent::__construct($msg);
    }
    
}

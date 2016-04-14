<?php

/**
* Creates response of stamndart error type
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 */
class icms_response_Error
    extends icms_response_HTML {
    
    /**
     * Error number
     *
     * @var int 
     */
    public $errorNo = 0;
    
    /**
     * Constructor
     * 
     * @global  object  $icmsModule     Current loaded module
     * @param   array   $config         Configuration
     * @param   int     $http_status    HTTP Status code
     * @param   array   $headers        Headers array
     */
    public function __construct($config = array(), $http_status = null, $headers = array()) {        
        $config['template_main'] = 'system_error.html';
        
        parent::__construct($config, $http_status, $headers);
        
        icms_loadLanguageFile('core', 'error');
        
        global $icmsConfig;
        $this->assign('lang_found_contact', sprintf(_ERR_CONTACT, $icmsConfig['adminmail']));
        $this->assign('lang_search', _ERR_SEARCH);
        $this->assign('lang_advanced_search', _ERR_ADVANCED_SEARCH);
        $this->assign('lang_start_again', _ERR_START_AGAIN);
        $this->assign('lang_search_our_site', _ERR_SEARCH_OUR_SITE);
    }
    
    /**
     * Renders
     * 
     * @global array $icmsConfig        Site configuration array
     */
    public function render() {
        global $icmsConfig;                
        
        $siteName = $icmsConfig['sitename'];
        $lang_error_no = sprintf(_ERR_NO, $this->errorNo);
        
        $this->assign('lang_error_no', $lang_error_no);
        $this->assign('lang_error_desc', sprintf(constant('_ERR_'.$this->errorNo.'_DESC'), $siteName));
        $this->assign('lang_error_title', $lang_error_no.' '.constant('_ERR_'.$this->errorNo.'_TITLE'));
        $this->assign('icms_pagetitle', $lang_error_no.' '.constant('_ERR_'.$this->errorNo.'_TITLE'));        
        
        parent::render();
    }
    
}

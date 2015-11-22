<?php

/**
 * Creates response of HTML type
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 * 
 * @method null assign(string $name, mixed $value)   Assigns var to template
 */
class icms_response_HTML extends icms_response_Text {

    /**
     * Mimetype for this response
     */
    const CONTENT_TYPE = 'text/html';
    
    /**
     * Instance of current theme
     *
     * @var \icms_view_theme_Object 
     */
    private $theme = null;
    
    /**
     * Gets theme factory for existing configuration
     * 
     * @todo        Move this function as instance to \icms_view_theme_Factory
     * 
     * @global      array                    $icmsConfig    ICMS Configuration array
     * @staticvar   \icms_view_theme_Factory $themeFactory  Current instance of theme factory
     * @return      \icms_view_theme_Factory                Returns preconfigured icms_view_theme_Factory object
     */
    private static function getThemeFactory() {
        static $themeFactory = null;
        
        if ($themeFactory === null) {        
            global $icmsConfig;
            $themeFactory = new \icms_view_theme_Factory();
            $themeFactory->allowedThemes = $icmsConfig['theme_set_allowed'];
            $themeFactory->defaultTheme = $icmsConfig['theme_set'];
        }
       
        
        return $themeFactory;
    }   

    /**
     * Constructor
     * 
     * @global  object  $icmsModule     Current loaded module
     * @param   array   $config         Configuration
     * @param   int     $http_status    HTTP Status code
     * @param   array   $headers        Headers array
     */
    public function __construct($config = array(), $http_status = null, $headers = array()) {
                
        $this->setThemeFromConfig($config);        
        $this->setGoogleMeta();                
       
        icms::$preload->triggerEvent('startOutputInit');                

        $this->setDefaultMetas();                        
        $this->addSanitizerPlugins();       

        if (!empty($_SESSION['redirect_message'])) {
            $this->addRedirectMessageScripts();
            unset($_SESSION['redirect_message']);
        }        

        if (isset($this->theme->plugins['icms_view_PageBuilder']) && is_object($this->theme->plugins['icms_view_PageBuilder'])) {
            $this->theme->template->assign_by_ref('xoBlocks', $this->theme->plugins['icms_view_PageBuilder']->blocks);
        }
        $this->updateCacheTime();
        
        global $icmsConfig;
        $this->theme->template->assign('icmsLang', $icmsConfig['language']);
        
        $this->includeNotificationsSelection();

        parent::__construct(null, $http_status, $headers);
    }
    
    /**
     * Magic function to call work directly with template
     * 
     * @param string $name          Function name to call
     * @param array  $arguments     Array with arguments
     * 
     * @return mixed
     */
    public function __call($name, $arguments) {
        return call_user_func_array([$this->theme->template, $name], $arguments);
    }
    
    /**
     * Renders response
     */
    public function render() {
        /* check if the module is cached and retrieve it, otherwise, render the page */
        if (!$this->theme->checkCache()) {
            $this->theme->render();
        }
    }

    /**
     * 
     * @global object $icmsModule
     */
    private function includeNotificationsSelection() {
        global $icmsModule;
        // RMV-NOTIFY
	if (is_object($icmsModule) && $icmsModule->hasnotification == 1 && is_object(\icms::$user)) {
            /** Require the notifications area */
            $xoTheme = &$this->theme;
            require_once ICMS_INCLUDE_PATH . '/notification_select.php';
	}
    }
    
    /**
     * Update cache time for module
     * 
     * @global object   $icmsModule    Current module
     * @global array    $icmsConfig    Configuration array
     */
    private function updateCacheTime() {
        global $icmsModule, $icmsConfig;
        if (!empty($icmsModule) && isset($icmsConfig['module_cache']) && isset($icmsConfig['module_cache'][$icmsModule->mid])) {
            $this->theme->contentCacheLifetime = $icmsConfig['module_cache'][$icmsModule->mid];
        }
    }
    
    /**
     * Set default metas for theme instance
     */
    private function setDefaultMetas() {
        $jgrowl_css = ICMS_LIBRARIES_URL . '/jquery/jgrowl'
                . (( defined('_ADM_USE_RTL') && _ADM_USE_RTL ) ? '_rtl' : '') . '.css';
        
        $this->theme->metas = [
            'head' => [
                'stylesheet' => [
                    ICMS_LIBRARIES_URL . '/jquery/ui/css/ui-smoothness/ui.css' => [
                        'value' => [
                            'type' => 'text/css',
                            'src' => ICMS_LIBRARIES_URL . '/jquery/ui/css/ui-smoothness/ui.css',
                            'media' => 'screen'
                        ],
                        'weight' => 0
                    ],
                    ICMS_LIBRARIES_URL . '/bootstrap/bootstrap.min.css' => [
                        'value' => [
                            'type' => 'text/css',
                            'src' => ICMS_LIBRARIES_URL . '/bootstrap/bootstrap.min.css',
                            'media' => 'screen'
                        ],
                        'weight' => 0
                    ],
                    $jgrowl_css => [
                        'value' => [
                            'src' => $jgrowl_css,
                            'type' => 'text/css',
                            'media' => 'screen'
                        ],
                        'weight' => 0
                    ],
                    ICMS_LIBRARIES_URL . '/jquery/colorbox/colorbox.css' => [
                        'value' => [
                            'src' => ICMS_LIBRARIES_URL . '/jquery/colorbox/colorbox.css',
                            'type' => 'text/css',
                            'media' => 'screen'                            
                        ],
                        'weight' => 0
                    ]
                ]
            ],
            'foot' => [
                'script' => [
                    [
                        ICMS_URL . '/include/xoops.js' => [
                            'value' => [
                                'type' => 'text/javascript',
                                'src' => ICMS_URL . '/include/xoops.js'
                            ],
                            'weight' => 0
                        ],
                        ICMS_URL . '/include/linkexternal.js' => [
                            'value' => [
                                'src' => ICMS_URL . '/include/linkexternal.js',
                                'type' => 'text/javascript'
                            ],
                            'weight' => 0
                        ],
                        ICMS_LIBRARIES_URL . '/jquery/jquery.js' => [
                            'value' => [
                                'type' => 'text/javascript',
                                'src' => ICMS_LIBRARIES_URL . '/jquery/jquery.js'
                            ],
                            'weight' => 0                            
                        ],
                        ICMS_LIBRARIES_URL . '/jquery/ui/ui.min.js' => [
                            'value' => [
                                'type' => 'text/javascript',
                                'src' => ICMS_LIBRARIES_URL . '/jquery/ui/ui.min.js'
                            ],
                            'weight' => 0                            
                        ],
                        ICMS_LIBRARIES_URL . '/bootstrap/bootstrap.min.js' => [
                            'value' => [
                                'type' => 'text/javascript',
                                'src' => ICMS_LIBRARIES_URL . '/bootstrap/bootstrap.min.js'
                            ],
                            'weight' => 0                            
                        ],
                        ICMS_LIBRARIES_URL . '/jquery/colorbox/jquery.colorbox-min.js' => [
                            'value' => [
                                'type' => 'text/javascript',
                                'src' => ICMS_LIBRARIES_URL . '/jquery/colorbox/jquery.colorbox-min.js'
                            ],
                            'weight' => 0                            
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Sets theme from config
     * 
     * @param array $config     Current configuration
     */
    private function setThemeFromConfig(array &$config) {
        if (isset($config['template_main']) && is_string($config['template_main'])) {
            if (FALSE === strpos($config['template_main'], ':')) {
                $config['template_main'] = 'db:' . $config['template_main'];
            }
        } else {
            $config['template_main'] = null;
        }        
        
        $this->theme = self::getThemeFactory()->createInstance(
            [
                'contentTemplate' => $config['template_main']
            ]
        );        
    }    

    /**
     * Sets google meta
     * 
     * @global array $icmsConfigMetaFooter          Footer meta configuration array
     */
    private function setGoogleMeta() {
        global $icmsConfigMetaFooter;
        if (isset($icmsConfigMetaFooter['google_meta']) && $icmsConfigMetaFooter['google_meta'] != '') {
            $this->theme->addMeta('meta', 'verify-v1', $icmsConfigMetaFooter['google_meta']);
            $this->theme->addMeta('meta', 'google-site-verification', $icmsConfigMetaFooter['google_meta']);
        }        
    }
    
    /**
     * Adds scripts for redirect message
     */
    private function addRedirectMessageScripts() {
        $this->theme->addScript(ICMS_LIBRARIES_URL . '/jquery/jgrowl.js', array('type' => 'text/javascript'));
        $this->theme->addScript('', array('type' => 'text/javascript'), '
	if (!window.console || !console.firebug) {
		var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd",
					"time", "timeEnd", "count", "trace", "profile", "profileEnd"];
		window.console = {};

		for (var i = 0; i < names.length; ++i) window.console[names[i]] = function() {};
	}

	(function($) {
		$(document).ready(function() {
			$.jGrowl("' . $_SESSION['redirect_message'] . '", {  life:5000 , position: "center", speed: "slow" });
		});
	})(jQuery);
	');        
    }

    /**
     * Adds all enabled santitizer plugins to the theme
     * 
     * @global array $icmsConfigPlugins         Plugins configuration
     */
    private function addSanitizerPlugins() {
        global $icmsConfigPlugins;
        $style_info = '';
        if (!empty($icmsConfigPlugins['sanitizer_plugins'])) {
            foreach (array_filter($icmsConfigPlugins['sanitizer_plugins']) as $key) {
                if (file_exists(ICMS_PLUGINS_PATH . '/textsanitizer/' . $key . '/' . $key . '.css')) {
                    $this->theme->addStylesheet(ICMS_PLUGINS_URL . '/textsanitizer/' . $key . '/' . $key . '.css', array('media' => 'screen'));
                } else {
                    $extension = include_once ICMS_PLUGINS_PATH . '/textsanitizer/' . $key . '/' . $key . '.php';
                    $func = 'style_' . $key;
                    if (function_exists($func)) {
                        $style_info = $func();
                        if (!empty($style_info)) {
                            if (!file_exists(ICMS_ROOT_PATH . '/' . $style_info)) {
                                $this->theme->addStylesheet('', array('media' => 'screen'), $style_info);
                            } else {
                                $this->theme->addStylesheet($style_info, array('media' => 'screen'));
                            }
                        }
                    }
                }
            }
        }
    }

}

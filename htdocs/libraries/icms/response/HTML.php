<?php

/**
 * Creates response of HTML type
 *
 * @author      Raimondas Rimkevičius <mekdrop@impresscms.org>
 * @package	ICMS\Response
 *
 * @method assign(string $name, mixed $value)   Assigns var to template
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

        \icms::$preload->triggerEvent('startOutputInit');

        $this->setDefaultMetas();
        $this->addSanitizerPlugins();

        if (isset($config['isAdminSide']) && $config['isAdminSide'] === true) {
			$this->addAdminMetas();
			$this->loadAdminMenu();
			$this->setAdminDefaultVars();
			global $icmsAdminTpl;
			$GLOBALS['icmsAdminTpl'] = $icmsAdminTpl = &$this->theme->template;
        } else {
            global $icmsTpl;
            $GLOBALS['icmsTpl'] = $icmsTpl = &$this->theme->template;
        }
        global $icmsTheme;
        $GLOBALS['icmsTheme'] = $icmsTheme = &$this->theme;

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
        $this->theme->template->assign('xoops_url', ICMS_URL);
        $this->theme->template->assign('icms_sitename', $icmsConfig['sitename']);

        $this->includeNotificationsSelection();

        parent::__construct(null, $http_status, $headers);
    }

	/**
	 * Sets default variables for admin
	 */
    private function setAdminDefaultVars() {
		global $icmsConfigPersona;
		$this->theme->template->assign('adm_left_logo', $icmsConfigPersona['adm_left_logo']);
		$this->theme->template->assign('adm_left_logo_url', $icmsConfigPersona['adm_left_logo_url']);
		$this->theme->template->assign('adm_left_logo_alt', $icmsConfigPersona['adm_left_logo_alt']);
		$this->theme->template->assign('adm_right_logo', $icmsConfigPersona['adm_right_logo']);
		$this->theme->template->assign('adm_right_logo_url', $icmsConfigPersona['adm_right_logo_url']);
		$this->theme->template->assign('adm_right_logo_alt', $icmsConfigPersona['adm_right_logo_alt']);
	}

    /**
     * Loading admin dropdown menus
     */
    private function loadAdminMenu() {
        global $icmsConfig;

        $adminCacheFile = ICMS_CACHE_PATH . '/adminmenu_' . $icmsConfig ['language'] . '.php';
        if (!file_exists($adminCacheFile)) {
            xoops_module_write_admin_menu(impresscms_get_adminmenu());
        }

        $admin_menu = include($adminCacheFile);

        $moduleperm_handler = icms::handler('icms_member_groupperm');
        $module_handler = icms::handler('icms_module');
        $groups = \icms::$user->getGroups();
        foreach ($admin_menu as $k => $navitem) {
            //Getting array of allowed modules to use in admin home
            if ($navitem ['id'] == 'modules') {
                $perm_itens = array();
                foreach ($navitem ['menu'] as $item) {
                    $module = $module_handler->getByDirname($item['dir']);
                    if (
                            ($item['dir'] != 'system') &&
                            $moduleperm_handler->checkRight('module_admin', $module->mid, $groups)
                    ) {
                        $perm_itens[] = $item;
                    }
                }
                $navitem['menu'] = $mods = $perm_itens;
            }
            //end
            if ($navitem['id'] == 'opsystem') {
                $all_ok = false;
                if (!in_array(ICMS_GROUP_ADMIN, $groups)) {
                    $sysperm_handler = icms::handler('icms_member_groupperm');
                    $ok_syscats = & $sysperm_handler->getItemIds('system_admin', $groups);
                } else {
                    $all_ok = true;
                    $ok_syscats = [];
                }
                $perm_itens = array();

                /**
                 * Allow easely change the order of system dropdown menu.
                 * $adminmenuorder = 1; Alphabetically order;
                 * $adminmenuorder = 0; Indice key order;
                 * To change the order when using Indice key order just change the order of the array in the file modules/system/menu.php and after update the system module
                 *
                 * @todo: Create a preference option to set this value and improve the way to change the order.
                 */
                $adminmenuorder = 1;
                $adminsubmenuorder = 1;
                $adminsubsubmenuorder = 1;
                if ($adminmenuorder == 1) {
                    foreach ($navitem ['menu'] as $k => $sortarray) {
                        $column[] = $sortarray['title'];
                        if (isset($sortarray['subs']) && count($sortarray['subs']) > 0 && $adminsubmenuorder) {
                            asort($navitem['menu'][$k]['subs']);
                        }
                        if (isset($sortarray['subs']) && count($sortarray['subs']) > 0) {
                            foreach ($sortarray['subs'] as $k2 => $sortarray2) {
                                if (isset($sortarray2['subs']) && count($sortarray2['subs']) > 0 && $adminsubsubmenuorder) {
                                    asort($navitem['menu'][$k]['subs'][$k2]['subs']); //Sorting submenus of preferences
                                }
                            }
                        }
                    }
                    //sort arrays after loop
                    array_multisort($column, SORT_ASC, $navitem['menu']);
                }
                foreach ($navitem['menu'] as $item) {
                    foreach ($item['subs'] as $key => $subitem) {
                        if ($all_ok == false && !in_array($subitem['id'], $ok_syscats)) {
                            // remove the subitem
                            unset($item['subs'][$key]);
                        }
                    }
                    // only add the item (first layer: groups) if it has subitems
                    if (empty($item['subs']) === false) {
                        $perm_itens[] = $item;
                    }
                }
                //Getting array of allowed system prefs
                $navitem['menu'] = $sysprefs = $perm_itens;
            }
            // $icmsAdminTpl
            $this->theme->template->append('navitems', $navitem);
        }

        // $icmsAdminTpl
        $this->theme->template->assign('systemadm', empty($sysprefs) ? 0 : 1 );
        $this->theme->template->assign('modulesadm', empty($mods) ? 0 : 1 );

        /**
         * Loading options of the current module.
         */
        if (\icms::$module !== null) {
            if (\icms::$module->getVar('dirname') == 'system') {
                if (isset($sysprefs) && count($sysprefs) > 0) {
                    // remove the grouping for the system module preferences (first layer)
                    $sysprefs_tmp = array();
                    foreach ($sysprefs as $pref) {
                        $sysprefs_tmp = array_merge($sysprefs_tmp, $pref['subs']);
                    }
                    $sysprefs = $sysprefs_tmp;
                    unset($sysprefs_tmp);
                    $reversed_sysprefs = [];
                    for ($i = count($sysprefs) - 1; $i >= 0; $i = $i - 1) {
                        if (isset($sysprefs [$i])) {
                            $reversed_sysprefs[] = $sysprefs[$i];
                        }
                    }
                    foreach ($reversed_sysprefs as $k) {
                        $this->theme->template->append(
                                'mod_options', array(
                            'title' => $k ['title'], 'link' => $k ['link'],
                            'icon' => (isset($k['icon']) && $k['icon'] != '' ? $k['icon'] : '')
                                )
                        );
                    }
                }
            } else {
                foreach ($mods as $mod) {
                    if ($mod['dir'] == \icms::$module->getVar('dirname')) {
                        $m = $mod; //Getting info of the current module
                        break;
                    }
                }
                if (isset($m['subs']) && empty($m['subs']) === false) {
                    for ($i = count($m['subs']) - 1; $i >= 0; $i--) {
                        if (isset($m['subs'][$i])) {
                            $reversed_module_admin_menu[] = $m['subs'][$i];
                        }
                    }
                    foreach ($reversed_module_admin_menu as $k) {
                        $this->theme->template->append(
                                'mod_options', [
                                    'title' => $k['title'],
                                    'link' => $k ['link'],
                                    'icon' => (isset($k['icon']) && $k['icon'] != '' ? $k['icon'] : '')
                                ]
                        );
                    }
                }
            }
            $this->theme->template->assign('modpath', ICMS_URL . '/modules/' . \icms::$module->getVar('dirname'));
            $this->theme->template->assign('modname', \icms::$module->getVar('name'));
            $this->theme->template->assign('modid', \icms::$module->getVar('mid'));
            $this->theme->template->assign('moddir', \icms::$module->getVar('dirname'));
            $this->theme->template->assign('lang_prefs', _PREFERENCES);
        }
    }

    /**
     * Ads admin metas
     */
    private function addAdminMetas() {
        $this->theme->addScript('', array('type' => 'text/javascript'), 'startList = function() {
						if (document.all&&document.getElementById) {
							navRoot = document.getElementById("nav");
							for (i=0; i<navRoot.childNodes.length; i++) {
								node = navRoot.childNodes[i];
								if (node.nodeName=="LI") {
									node.onmouseover=function() {
										this.className+=" over";
									}
									node.onmouseout=function() {
										this.className=this.className.replace(" over", "");
									}
								}
							}
						}
					}
					window.onload=startList;');
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
        if (($icmsModule instanceof \icms_module_Object) && ($icmsModule->hasnotification == 1) && is_object(\icms::$user)) {
            /** Require the notifications area */
            global $xoTheme, $xoopsTpl;
            $xoopsTpl = &$this;
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

        $this->theme->metas['head']['stylesheet'] = [
            ICMS_LIBRARIES_URL . '/bootstrap/bootstrap.min.css' => [
                'value' => [
                    'type' => 'text/css',
                    'href' => ICMS_LIBRARIES_URL . '/bootstrap/bootstrap.min.css',
                    'media' => 'screen'
                ],
                'weight' => 0
            ],
            ICMS_LIBRARIES_URL . '/jquery/ui/css/ui-smoothness/ui.css' => [
                'value' => [
                    'type' => 'text/css',
                    'href' => ICMS_LIBRARIES_URL . '/jquery/ui/css/ui-smoothness/ui.css',
                    'media' => 'screen'
                ],
                'weight' => 0
            ],
            $jgrowl_css => [
                'value' => [
                    'type' => 'text/css',
                    'href' => $jgrowl_css,
                    'media' => 'screen'
                ],
                'weight' => 0
            ],
            ICMS_LIBRARIES_URL . '/jquery/colorbox/colorbox.css' => [
                'value' => [
                    'type' => 'text/css',
                    'href' => ICMS_LIBRARIES_URL . '/jquery/colorbox/colorbox.css',
                    'media' => 'screen'
                ],
                'weight' => 0
            ]
        ];

        $this->theme->metas['module']['script'] = [
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

        $tplConfig = [
            'contentTemplate' => $config['template_main']
        ];

        if (isset($config['template_folder'])) {
            $tplConfig['folderName'] = $config['template_folder'];
        }

        if (isset($config['template_canvas'])) {
            $tplConfig['canvasTemplate'] = $config['template_canvas'];
        }

        if (isset($config['isAdminSide']) && $config['isAdminSide'] === true) {
            global $icmsConfig;

            $tplConfig['plugins'] = [ 'icms_view_PageBuilder' ];

            if (!isset($tplConfig['canvasTemplate'])) {
                $tplConfig['canvasTemplate'] = 'theme' . ((file_exists(ICMS_THEME_PATH . '/' . $icmsConfig['theme_admin_set'] . '/theme_admin.html') ||
                                                file_exists(ICMS_MODULES_PATH . '/system/themes/' . $icmsConfig['theme_admin_set'] . '/theme_admin.html')) ? '_admin' : '') . '.html';
            }

            if (!isset($tplConfig['folderName'])) {
                $tplConfig['folderName'] = $icmsConfig['theme_admin_set'];
            }
        }
        $this->theme = \icms_view_theme_Factory::getInstance()->createInstance($tplConfig);
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
        if (!empty($icmsConfigPlugins['sanitizer_plugins'])) {
            foreach (array_filter($icmsConfigPlugins['sanitizer_plugins']) as $key) {
                if (file_exists(ICMS_PLUGINS_PATH . '/textsanitizer/' . $key . '/' . $key . '.css')) {
                    $this->theme->addStylesheet(ICMS_PLUGINS_URL . '/textsanitizer/' . $key . '/' . $key . '.css', array('media' => 'screen'));
                } else {
                    include_once ICMS_PLUGINS_PATH . '/textsanitizer/' . $key . '/' . $key . '.php';
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

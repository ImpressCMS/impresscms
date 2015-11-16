<?php

/**
 * This is a handler for controllers
 *
 * @author          Raimondas Rimkevičius <mekdrop@impresscms.org>
 * @package         ICMS/Controller
 * @copyright       http://www.impresscms.org/ The ImpressCMS Project 
 * 
 * @property-read   string  $type   Type of current controller instance.
 *                                      Possible values:
 *                                          embed       - if all scripts are running in PHP Embeded mode
 *                                          cli         - if all scripts are running from command line
 *                                          controller  - any other mode
 */
class icms_controller_Handler {
    
    /**
     * Current controller type
     *
     * @var string
     */
    private $type = '';
    
    /**
     * Constructor
     */
    public function __construct() {
        switch (PHP_SAPI) {
            case 'embed':
                $this->type = 'embed';
            break;
            case 'cli':
                $this->type = 'command';
            break;
            default:
                $this->type = 'controller';
            break;
        }
    }
    
    /**
     * Magic getter
     * 
     * @param string $name
     * 
     * @return mixed
     */
    public function __get($name) {
        return $this->$name;
    }
    
    /**
     * Creates url for action
     * 
     * @param string $module
     * @param string $controller_name
     * @param string $action
     * @param array $params
     * 
     * @return string
     */
    public function makeURL($module, $controller_name, $action, array $params = []) {
        $controller = $this->get($module, $this->type, $controller_name);         
        
        $reflector = new ReflectionClass($controller);
        $format = $reflector->getConstant('PARAMS_FORMAT');        
        if (strpos($format, '{@') !== false) {
            $args = '';
            foreach ($params as $key => $value) {
                $args .= str_replace(
                    [
                        '{@param}',
                        '{@value}'
                    ],
                    [
                        $key,
                        $value
                    ],
                    $format
                );
            }
        } else {            
            $replace_with = array_values($params);
            $replace_what = array_map(function ($item) {
                return '{' . $item . '}';
            }, array_keys($params));
            $args = str_replace($replace_what, $replace_with, $format);
        }       
        
        return ICMS_URL . '/' . $module . '/' . $controller_name . '/' . $action . $args;
    }
        
    /**
     * Gets controller
     * 
     * @param string $module
     * @param string $type
     * @param string $controller_name
     * 
     * @return icms_controller_base|null
     */
    public function get($module, $type, $controller_name) {        
        include_once $this->getControllersPath($module, $type) . DIRECTORY_SEPARATOR . $controller_name . '.php'; 
        $class = '\\ImpressCMS\\Modules\\' . $module . '\\' . ucfirst($type) . '\\' . $controller_name;
        return class_exists($class)?new $class():null;
    }
    
    /**
     * Parses params string to array
     * 
     * @param string $module
     * @param string $controller_name
     * @param string $string
     * 
     * @return string
     */
    public function parseParamsStringToArray($module, $controller_name, $string) {
        $controller = $this->get($module, $this->type, $controller_name);
        $reflector = new ReflectionClass($controller);
        
        $vars = [];
        $regex = '/' . str_replace('/', '\/', preg_replace_callback('/{([@a-zA-Z0-9]+)}/', function ($matches) use (&$vars) {
            $vars[] = $matches[1];
            return '(.+)';
        }, $reflector->getConstant('PARAMS_FORMAT'))) . '/';
        
        if (preg_match_all($regex, $string, $matches, PREG_SET_ORDER) > 0) {
            $name = null;
            $ret = [];
            foreach($matches as $match) {                
                foreach ($vars as $o => $var) {
                    if ($var === '@param') {
                        $name = $match[$o + 1];
                    } elseif ($var === '@value') {
                        if ($name === null) {
                            $ret[] = $match[$o + 1];
                        } else {
                            $ret[$name] = $match[$o + 1];
                        }
                    } else {
                        $ret[$var] = $match[$o + 1];
                    }
                }
            }
            return $ret;
        } else {
            return [];
        }
    }
    
    /**
     * Gets controller
     * 
     * @param string $module
     * @param string $type
     * @param string $controller_name
     * @param string $action
     * @param array  $params
     * 
     * @return icms_response_Text
     */    
    public function exec($module, $type, $controller_name, $action, array $params) {        
        $controller = $this->get($module, $type, $controller_name);
        $reflector = new ReflectionClass($controller);
        if (!$reflector->hasMethod($action)) {
            throw new Exception($action . ' is not defined');            
        }
        $method = $reflector->getMethod($action);
        if (empty($params)) {
            $controller->$action();
        } else {            
            if (is_int(key($params))) {
                $args = &$params;
            } else {
                $args = [];                
                foreach ($method->getParameters() as $param) {
                    $args[] = $params[$param->getName()];
                }
            }
            call_user_func_array([$controller, $action], $args);
        }            
    }
    
    /**
     * Gets path for module controllers
     * 
     * @param string $module
     * @param string $type
     * 
     * @return string
     */
    public function getControllersPath($module, $type) {
        static $paths = [];
        if (!isset($paths[$module]) || !isset($paths[$module][$type])) {
            $paths[$module][$type] = ICMS_MODULES_PATH . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $type;
        }
        return $paths[$module][$type];
    }
    
    /**
     * Gets controllers of type list for module
     * 
     * @param string $module
     * @param string $type
     * 
     * @return array
     */
    public function getAvailable($module, $type) {
        $pwd = getcwd();
        $path = $this->getControllersPath($module, $type);
        if (!is_dir($path)) {
            return [];
        }
        chdir($path);
        $ret = [];
        $prefix = '\\ImpressCMS\\Modules\\' . $module . '\\' . ucfirst($type) . '\\';
        foreach (glob('*.php') as $file) {
            include_once $file;
            $class = $prefix . substr($file, 0, -4);
            try {
                $reflection = new ReflectionClass($class);
            } catch (\Exception $ex) {
                continue;
            }
            if (!$reflection->isSubclassOf('\icms_controller_Object')) {
                continue;
            }
            $class = $reflection->getShortName();
            $ret[$class] = [];
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $params = [];
                foreach ($method->getParameters() as $param) {
                    if ($param->isPassedByReference() || $param->isCallable() || $param->isVariadic()) {
                        continue;
                    }
                    $params[$param->getName()] = [
                        'optional' => $param->isOptional(),
                        'default' => $param->isDefaultValueAvailable()?$param->getDefaultValue():null
                    ];
                }                          
                $ret[$class][$method->getShortName()] = [
                    'params' => $params,
                    'description' => $method->getDocComment() // TODO: Add actual parsing
                ];
            }                
            if (count($ret[$class]) === 0) {
                unset($ret[$class]);
                continue;
            }
            ksort($ret[$class]);
        }
        ksort($ret);
        chdir($pwd);
        return $ret;
    }
    
}

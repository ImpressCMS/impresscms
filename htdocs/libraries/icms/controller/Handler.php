<?php

/**
 * This is a handler for controllers
 *
 * @author Raimondas Rimkevičius <mekdrop@impresscms.org>
 */
class icms_controller_Handler {        
    
    /**
     * Gets controller
     * 
     * @param string $module
     * @param string $type
     * @param string $controller_name
     * 
     * @return icms_controller_base
     */
    public function get($module, $type, $controller_name) {        
        require_once $this->getPath($module, $type) . DIRECTORY_SEPARATOR . $controller_name . '.php';
        return new $controller_name();
    }
    
    /**
     * Gets path for module controllers
     * 
     * @param string $module
     * @param string $type
     * 
     * @return string
     */
    public function getPath($module, $type) {
        $base_class = 'icms_controller_' . $type;
        return ICMS_MODULES_PATH . DIRECTORY_SEPARATOR . $base_class::getFolder();
    }
    
    /**
     * Gets controllers of type list for module
     * 
     * @param string $module
     * @param string $type
     * 
     * @return array
     */
    public function getList($module, $type) {
        $pwd = getcwd();
        chdir($this->getPath($module, $type));
        $ret = [];
        $base_class = 'icms_controller_' . $type;
        foreach (glob('*.php') as $file) {
            $class = substr($file, 0, -4);
            $reflection = new ReflectionClass($class);
            if (!$reflection->isSubclassOf($base_class)) {
                continue;
            }
            $ret[$class] = [];
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $params = [];
                foreach ($method->getParameters() as $param) {
                    if ($param->isPassedByReference() || $param->isCallable()) {
                        continue;
                    }
                    $params[$param->getName()] = [
                        'optional' => $param->isOptional(),
                        'variadic' => $param->isVariadic(),
                        'default' => $param->getDefaultValue()
                    ];
                }                
                $ret[$class][$method->getName()] = $params;
            }    
            if (count($ret[$class]) > 0) {
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

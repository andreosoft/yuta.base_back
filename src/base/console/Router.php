<?php

namespace base\console;

class Router {

    public function routeselect($module, $controller, $action, $params = []) {
        if (!empty($controller) && !empty($action)) {
            $c_name = '\modules\\' . $module . '\\console_controllers\\' . ucwords($controller, "-");
            if (class_exists($c_name)) {
                $c = new $c_name();
                $a_name = 'action_' . $action;
                if (method_exists($c, $a_name)) {
                    $c->preAction($action);
                    $reflectionMethod = new \ReflectionMethod($c_name, $a_name);
                    return $reflectionMethod->invokeArgs($c,$params);
                }
            }
        }
        exit();
    }

}

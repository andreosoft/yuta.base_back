<?php

namespace base\core;

class Router {

    public function parse() {
        return false;
    }

    public function routeselect() {
        foreach (\A::$app->config['routing'] as $r_name) {
            if (class_exists($r_name)) {
                $c = new $r_name();
                $r = $c->parse();
                if ($r !== false) {
                    break;
                }
            }
        }

        $module = $r[0];
        $controller = $r[1];
        $action = $r[2];
        $params = $r[3];
        if (!empty($controller) && !empty($action)) {
            $c_name = '\modules\\' . $module . '\\controllers\\' . ucwords($controller, "-");
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
        header("HTTP/1.0 404 Not Found");
        exit();
    }

}

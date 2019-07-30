<?php

namespace base\core;

class Exception extends \Exception {

    public function __construct($type) {
        \A::$app->layout = 'blank';
        \A::$app->params['title'] = '404';
        header("HTTP/1.0 404 Not Found");
        echo \A::$app->getView()->render(SOURCE . '/views/base/404', []);
        exit();
    }

}

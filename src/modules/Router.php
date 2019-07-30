<?php

namespace modules;


class Router {

    function parse() {
        
        $uri = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
        $arr = explode('/', $uri);
        if (count($arr) == 2) {
            return [$arr[0], $arr[1], strtolower($_SERVER['REQUEST_METHOD']), []];
        } else if (count($arr) == 3) {
            return [$arr[0], $arr[1], $arr[2], []];   
        } else if (count($arr) == 4) {
            return [$arr[0], $arr[1], $arr[2], [ $arr[3] ]];   
        }       
        return false;
    }

}

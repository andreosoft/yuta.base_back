<?php

spl_autoload_register('autoload');

function autoload($class_name) {
    include_once SOURCE . '/' . str_replace('\\', '/', $class_name) . '.php';
}

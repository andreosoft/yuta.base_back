<?php
namespace base\console;

use base\core\Db;

class App {


    public $config;
    public $_isajax = true;

    public $_db;
    public $_db_mssql;


    public function __construct() {
    }
    
    function start($config, $module, $controller, $action, $params = []) {
        \A::$app = $this;
        $this->config = $config;
        $router = new Router();
        $content = $router->routeselect($module, $controller, $action, $params);
        return $content;      
    }   
    
    public function db() {
        if ($this->_db == null) {
            $this->_db = Db::init_mysql();
        }
        return $this->_db;
    }

}

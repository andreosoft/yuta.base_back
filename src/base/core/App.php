<?php
namespace base\core;

class App {

    public $_view;
    public $layout = 'main';

    public $config;
    public $params = [];
    public $_user;
    public $_isajax = true;

    public $_db;


    public function __construct() {
    }
    
    function start($config) {
        session_start();
        \A::$app = $this;
        $this->config = $config;
        $router = new Router();
        $content = $router->routeselect();
        if ($this->_isajax) {
            echo $content;
        } else {
            echo $this->getView()->render(VIEWS.'/layout/'.$this->layout, ['content' => $content]);
        }
        
    }
    
    public function getView() {
        if ($this->_view == null) {
            $this->_view = new View();
        }
        return $this->_view;
    }
    
    public function user() {
        if ($this->_user == null) {
            $this->_user = User::getUser();
        }
        return $this->_user;
    }     
    
    public function db() {
        if ($this->_db == null) {
            $this->_db = Db::init_mysql();
        }
        return $this->_db;
    }

}

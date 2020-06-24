<?php

namespace modules\crm\controllers;

class Select extends \base\core\Controller {

    public function preAction($action) {
        $this->corsHeaders();
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }       
    }
    
    public function action_options() {
        $this->corsHeaders();
    }
    
    public function action_users() {
        $q = "SELECT id AS value, name AS text FROM users WHERE status = 2";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }
}

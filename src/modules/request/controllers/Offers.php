<?php

namespace modules\request\controllers;

class Offers extends \base\core\Controller {
    
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
    
    public function action_get() {
        $q = "SELECT av100_offers.* FROM av100_offers LEFT JOIN crm_offers ON av100_offers.id = crm_offers.offer_id WHERE crm_offers.id IS NULL ORDER BY id DESC LIMIT 100";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }
}


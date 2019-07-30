<?php

namespace controllers;

use models\Tiket as Model;

class Tiket extends \base\Controller {

    public function preAction($action) {
        $this->corsHeaders();
        if (\A::$app->user()->role < 1) {
            http_response_code(403);
            die('Forbidden');
        }
    }

    public function action_create() {
        $data = json_decode(file_get_contents('php://input'), true);
        $m = new Model();
        if (is_array($data) && $m->load($data) && $m->validate() && $m->save()) {
            return json_encode(['status' => 'ok']);
        }
        return json_encode(['status' => 'error']);
    }
}

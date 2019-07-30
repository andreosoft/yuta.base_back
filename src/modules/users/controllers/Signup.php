<?php

namespace modules\users\controllers;

use modules\users\models\UserLogin;
use modules\users\models\UserRegistaration;

class Signup extends \base\core\Controller {

    public function preAction($action) {
        $this->corsHeaders();
    }
    
    public function __construct() {
        parent::__construct();
    }

    public function action_get_token() {
        $data = json_decode(file_get_contents('php://input'), true);
        $model = new UserLogin();
        if (is_array($data) && $model->load($data) && $model->validate() && $model->new_token()) {
            return json_encode(['status' => 'ok', 'token' => $model->token, 'profile' => $model->fields_one]);
        }
        return json_encode(['status' => 'error', 'error' => $model->_errors]);
    }

        
    public function action_set_profile() {
        $data = json_decode(file_get_contents('php://input'), true);
        $model = UserLogin::findOne(['id' => \A::$app->user()->id]);
        $model->login = null;
        if (is_object($model) && is_array($data) && $model->load($data) && $model->validate() && $model->save()) {
            return json_encode(['status' => 'ok', 'data' => $model->fields_one]);
        }
        return json_encode(['status' => 'error', 'error' => $model->_errors]);
    }
}

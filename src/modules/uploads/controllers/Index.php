<?php

namespace modules\uploads\controllers;

class Index extends \base\rest\RestController {

    public $baseModel = '\modules\uploads\models\Uploads';

    public function preAction($action) {
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }      
        parent::preAction($action); 
    }
}

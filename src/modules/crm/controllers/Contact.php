<?php

namespace modules\crm\controllers;

class Contact extends \base\rest\RestController {

    public $baseModel = '\modules\crm\models\Contact';

    public function preAction($action) {
        parent::preAction($action);
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }       
    }
}

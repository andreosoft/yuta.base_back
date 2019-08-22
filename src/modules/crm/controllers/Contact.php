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

    public function getFilters($fs) {
        $f_ar = [];
        if (is_object($fs)) {
            foreach ($fs as $key => $val) {
                if ($val === "")
                    continue;
                switch ($key) {
                    case 'id':
                        $f_ar[] = "`$key` = '$val'";
                        break;
                    case 'name':
                        $f_ar[] = "`$key` LIKE '%$val%'";
                        break;
                    case 'address':
                        $f_ar[] = "`$key` LIKE '%$val%'";
                        break;

                }
            }
        }
        return $f_ar;
    }
}

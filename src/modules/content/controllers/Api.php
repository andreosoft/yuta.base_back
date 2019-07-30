<?php

namespace modules\content\controllers;

class Api extends \base\rest\RestParentController {

    public $baseModel = '\modules\content\models\Content';

    public function preAction($action) {
        $this->corsHeaders();
        if ($action == 'get') {
            if (\A::$app->user()->role < 1) {
                header('HTTP/1.0 403 Forbidden');
                echo 'You are forbidden!';
                exit();
            }
        } else {
            if (\A::$app->user()->role < 100) {
                header('HTTP/1.0 403 Forbidden');
                echo 'You are forbidden!';
                exit();
            }
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
                    case 'status':
                        $f_ar[] = "`$key` = '$val'";
                        break;
                    case 'parent_id':
                        $f_ar[] = "`$key` = '$val'";
                        break;
                }
            }
        }
        return $f_ar;
    }

}

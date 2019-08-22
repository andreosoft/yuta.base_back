<?php

namespace modules\users\controllers;

class Index extends \base\rest\RestController
{

    public $baseModel = 'modules\users\models\User';

    public function preAction($action)
    {
        $this->corsHeaders();
        if (\A::$app->user()->role < 100) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }
    }

    public function getFilters($fs)
    {
        $f_ar = [];
        if (is_object($fs)) {
            foreach ($fs as $key => $val) {
                if ($val === "")
                    continue;
                switch ($key) {
                    case 'login':
                        $f_ar[] = "`$key` LIKE '%$val%'";
                        break;
                    case 'id':
                        $f_ar[] = "`$key` = '$val'";
                        break;
                    case 'name':
                        $f_ar[] = "`$key` LIKE '%$val%'";
                        break;
                    case 'status':
                        $f_ar[] = "`$key` = '$val'";
                        break;
                    case 'role':
                        $f_ar[] = "`$key` = '$val'";
                        break;
                }
            }
        }
        return $f_ar;
    }
}

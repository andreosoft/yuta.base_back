<?php

namespace modules\crm\controllers;

class Building extends \base\rest\RestController {

    public $baseModel = '\modules\crm\models\Building';

    public function preAction($action) {
        parent::preAction($action);
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }       
    }

    public function get_all() {
        $parent = filter_input(INPUT_GET, 'parent_id');
        $sort = json_decode(filter_input(INPUT_GET, 'sort'));
        if (is_object($sort)) {
            $sort = "{$sort->key} {$sort->order}";
        } else {
            $sort = $this->sortDefault;
        }
        $table_name = (new $this->baseModel)->table_name();
        $els = (new $this->baseModel)->findManyByQuery("SELECT $table_name.* FROM $table_name WHERE `object_id` = '$parent' ORDER BY $sort");
        $res = [];
        foreach ($els as $el) {
            $res[] = $el->fields_many;
        }
        return json_encode(['status' => 'ok', 'data' => $res]);
    }
}

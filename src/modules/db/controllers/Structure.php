<?php

namespace modules\db\controllers;

class Structure extends \base\rest\RestController {

    public $baseModel = '\modules\db\models\Structure';

    public function preAction($action) {
        parent::preAction($action);
        if ($action == 'data') {
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
    
    public function action_order() {
        $data = json_decode(file_get_contents('php://input'), true);
        $model = new $this->baseModel();
        if (is_array($data['order'])) {
            foreach ($data['order'] as $el) {
                $model->load($el);
                $model->save();
            }
            return json_encode(['status' => 'ok']);
        }
        return json_encode(['status' => 'error']);
        
    }

    public function action_data() {
        $q = "SELECT db_structure.* FROM db_structure ORDER BY table_name ASC, sort ASC ";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        $res = [];
        $table_name = "";
        foreach ($data as $el) {
            if ($el['table_name'] != $table_name) {
                $table_name = $el['table_name'];
            }
            $el['data'] = json_decode($el['data']);
            $res[$table_name][] = $el;
        }
        return json_encode(['status' => 'ok', 'data' => $res]);
    }

    public function get_all() {
        $name = filter_input(INPUT_GET, 'name');
        $table_name = (new $this->baseModel)->table_name();
        if (strlen($name) > 3) {
            $q = "SELECT $table_name.* FROM $table_name WHERE table_name = '$name' ORDER BY sort ASC";
            $els = (new $this->baseModel)->findManyByQuery($q);
            $res = [];
            foreach ($els as $el) {
                $res[] = $el->fields_many;
            }
            return json_encode(['status' => 'ok', 'data' => $res]);
        }
        return json_encode(['status' => 'error', 'massage' => 'no table name']);
    }

}

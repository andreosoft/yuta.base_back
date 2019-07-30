<?php

namespace base\rest;

class RestController extends \base\core\Controller {

    public $baseModel = '';
    public $sortDefault = 'id ASC';
    
    public function preAction($action) {
        $this->corsHeaders();
    }
    
    public function getFilters($fs) {
        return '';
    }

    public function action_get() {
        $id = json_decode(filter_input(INPUT_GET, 'id'));
        if ($id > 0) {
            return $this->get_one($id);
        } else {
            return $this->get_all();
        }
    }

    public function action_post() {
        $model = new $this->baseModel();
        return $this->update($model);
    }

    public function action_put() {
        $id = json_decode(filter_input(INPUT_GET, 'id'));
        $model = (new $this->baseModel)->findOne(['id' => $id]);
        return $this->update($model);
    }

    public function action_delete() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['ids'])) {
            foreach ($data['ids'] as $id) {
                $model = (new $this->baseModel)->findOne(['id' => $id]);
                $model->delete();
            }
            return json_encode(['status' => 'ok', 'data' => []]);
        }
    }

    public function action_options() {
        $this->corsHeaders();
    }

    public function update($model) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (is_object($model) && is_array($data) && $model->load($data) && $model->validate() && $model->save()) {
            return json_encode(['status' => 'ok', 'data' => $model->fields_one]);
        }
        return json_encode(['status' => 'error', 'error' => $model->_errors]);
    }

    public function get_one($id) {
        $model = (new $this->baseModel)->findOne(['id' => $id]);
        if (is_object($model)) {
            return json_encode(['status' => 'ok', 'data' => $model->fields_one]);
        }
        return json_encode(['status' => 'error', 'data' => '']);
    }

    public function get_all() {
        $sort = json_decode(filter_input(INPUT_GET, 'sort'));
        if (is_object($sort)) {
            $sort = "{$sort->key} {$sort->order}";
        } else {
            $sort = $this->sortDefault;
        }
        $fs = json_decode(filter_input(INPUT_GET, 'filters'));
        $f_str = '';
        $f_arr = $this->getFilters($fs);
        if (count($f_arr) > 0) {
            $f_str = " WHERE ".implode(' AND ', $f_arr);
        }
        $table_name = (new $this->baseModel)->table_name();
        $els = (new $this->baseModel)->findManyByQuery("SELECT $table_name.* FROM $table_name $f_str ORDER BY $sort");
        $res = [];
        foreach ($els as $el) {
            $res[] = $el->fields_many;
        }
        return json_encode(['status' => 'ok', 'data' => $res]);
    }

}

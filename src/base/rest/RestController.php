<?php

namespace base\rest;

class RestController extends \base\core\Controller {

    public $baseModel = '';
    public $sortDefault = 'id ASC';
    public $pager = [
        'page' => 0,
        'limit' => 150
    ];

    public function preAction($action) {
        $this->corsHeaders();
    }
    
    public function getFilters($fs) {
        return null;
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
        $pgr = json_decode(filter_input(INPUT_GET, 'pager'));
        $fs = json_decode(filter_input(INPUT_GET, 'filters'));
        if (is_object($sort) && isset($sort->key)  && isset($sort->order) && $sort->key != '' && $sort->order != '') {
            $sort = "{$sort->key} {$sort->order}";
        } else {
            $sort = $this->sortDefault;
        }

        $pager = $this->pager;
        if (is_object($pgr)) {
            $pager['limit'] = $pgr->limit;
            $pager['page'] = $pgr->page;
        }
        $pager['offset'] = $pager['page'] * $pager['limit'];
        
        $f_str = '';
        $f_arr = $this->getFilters($fs);
        if (is_array($f_arr) && count($f_arr) > 0) {
            $f_str = " WHERE ".implode(' AND ', $f_arr);
        }
        $table_name = (new $this->baseModel)->table_name();
        $q_body = "FROM $table_name $f_str";
        $q = "SELECT COUNT(*) $q_body";
        $pager['count'] = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        $q = "SELECT $table_name.* $q_body ORDER BY $sort LIMIT {$pager['limit']} OFFSET {$pager['offset']}";
        $els = (new $this->baseModel)->findManyByQuery($q);
        $res = [];
        foreach ($els as $el) {
            $res[] = $el->fields_many;
        }
        return json_encode(['status' => 'ok', 'data' => $res, 'pager' => $pager]);
    }

}

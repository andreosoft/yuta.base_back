<?php

namespace modules\crm\controllers;

class Calls extends \base\rest\RestController {

    public $baseModel = '\modules\crm\models\Calls';

    public $sortDefault = 'id DESC';
    
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
        return $f_ar;
    }
    
    public function action_put_xml() {
        $data = $_POST;
        if (isset($data['cdr'])) {
            $model = new $this->baseModel;
            $xml = simplexml_load_string($data['cdr']);
            $json = json_encode($xml);
            $d = json_decode($json,TRUE);
            $variables = $d['variables'];
            $model->status = $variables['DIALSTATUS'];
            $model->direction = $d['callflow']['caller_profile']['context'] == 'public' ? 1 : 0;
            $model->phone = $d['callflow']['caller_profile']['context'] == 'public' ? $d['callflow']['caller_profile']['caller_id_number'] : $d['callflow']['caller_profile']['callee_id_number'];
            $model->uuid = $variables['uuid'];
            $model->calldatetime = date('Y-m-d H:i:s', (int)$d['callflow']['times']['created_time'] / 1000000);
            $model->info = $json;
            $model->save();
        }
        return;
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
            $f_str = " WHERE ".implode(' AND ', $f_arr)."";
        }
        $table_name = (new $this->baseModel)->table_name();
        $q_body = "FROM $table_name "
                . "LEFT JOIN crm_contacts ON crm_contacts.phone LIKE concat('%',crm_calls.phone,'%') "
                . "LEFT JOIN users ON users.id = crm_contacts.user_id ";
//                . " WHERE crm_contacts.id > 0";
        $q = "SELECT COUNT(*) $q_body";
        $pager['count'] = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        $q = "SELECT $table_name.*, "
                . "crm_contacts.id AS crm_contacts_id, "
                . "crm_contacts.name AS crm_contacts_name, "
                . "users.id AS users_id, users.name AS users_name "
                . "$q_body ORDER BY $sort LIMIT {$pager['limit']} OFFSET {$pager['offset']}";
                
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
//        $els = (new $this->baseModel)->findManyByQuery($q);
//        $res = [];
//        foreach ($els as $el) {
//            $res[] = $el->fields_many;
//        }
        return json_encode(['status' => 'ok', 'data' => $data, 'pager' => $pager]);
    }
}

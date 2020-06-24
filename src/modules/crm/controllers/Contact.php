<?php

namespace modules\crm\controllers;

use modules\crm\models\Deal;
use modules\crm\models\ContactComments;
use modules\crm\models\Contact as ContactModel;

class Contact extends \base\rest\RestController {

    public $sortDefault = 'id DESC';
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
        $table_name = (new $this->baseModel)->table_name();
        $f_ar = [];
        if (is_object($fs)) {
            foreach ($fs as $key => $val) {
                if ($val == "")
                    continue;
                $f = (new $this->baseModel)->get_fields_info();
                if (isset($f[$key])) {
                    $type = $f[$key]['type'];
                    if ($type == 'integer' || $type == 'select' || $type == 'select_api' || $type == 'status' || $type == 'ref') {
                        $f_ar[] = "`$table_name`.`$key` = '$val'";
                    } else if ($type == 'string' || $type == 'text') {
                        $f_ar[] = "`$table_name`.`$key` LIKE '%$val%'";
                    }
                }
            }
        }
        return $f_ar;
    }
    
    public function get_one($id) {
        $q = "SELECT 
        crm_contacts.*, 
        users.name AS manager 
        FROM crm_contacts
        LEFT JOIN `users` ON users.id = crm_contacts.manager_id 
        WHERE crm_contacts.id = '$id' 
        LIMIT 1";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        if (is_array($data) && count($data) == 1) {
            return json_encode(['status' => 'ok', 'data' => $data[0]]);
        }
        return json_encode(['status' => 'error', 'data' => '']);
    }
    
    public function get_all() {
        $sort = json_decode(filter_input(INPUT_GET, 'sort'));
        $pgr = json_decode(filter_input(INPUT_GET, 'pager'));
        $fs = json_decode(filter_input(INPUT_GET, 'filters'));
        if (is_object($sort) && isset($sort->key) && isset($sort->order) && $sort->key != '' && $sort->order != '') {
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
            $f_str = " WHERE " . implode(' AND ', $f_arr);
        }
        $table_name = (new $this->baseModel)->table_name();
        $q_body = "FROM $table_name 
        LEFT JOIN `users` ON users.id = $table_name.manager_id $f_str";
        $q = "SELECT COUNT(*) $q_body";
        $pager['count'] = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        $q = "SELECT 
        $table_name.* , 
        users.name AS manager 
        $q_body ORDER BY $sort LIMIT {$pager['limit']} OFFSET {$pager['offset']}";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data, 'pager' => $pager]);
    }

    public function action_get_deals() {
        $contact_id = json_decode(filter_input(INPUT_GET, 'contact_id'));
        $els = Deal::findMany(['contact_id' => $contact_id]);
        $res = [];
        foreach ($els as $el) {
            $res[] = $el->fields_many;
        }
        return json_encode(['status' => 'ok', 'data' => $res]);
    }

    public function action_get_comments() {
        $contact_id = json_decode(filter_input(INPUT_GET, 'contact_id'));
        $q = "SELECT crm_contacts_comments.*, users.name AS user FROM crm_contacts_comments LEFT JOIN users ON users.id = crm_contacts_comments.user_id WHERE crm_contacts_comments.contact_id = '$contact_id' ORDER BY createdon DESC";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data]);
    }

    public function action_add_comments() {
        $contact_id = json_decode(filter_input(INPUT_GET, 'contact_id'));
        $data = json_decode(file_get_contents('php://input'), true);
        $model = new ContactComments();
        if (is_object($model) && is_array($data) && $model->load($data)) {
            $model->contact_id = $contact_id;
            if ($model->validate() && $model->save()) {
                return json_encode(['status' => 'ok', 'data' => $model->fields_one]);
            }
        }
        return json_encode(['status' => 'error', 'error' => $model->_errors]);
    }

    public function action_get_calls() {
        $contact_id = json_decode(filter_input(INPUT_GET, 'contact_id'));
        $contact = ContactModel::findOne(['id' => $contact_id]);
        
        if (is_object($contact) && $contact->phone != '') {
            $phone = substr(trim($contact->phone),-9);
            $q = "SELECT status, calldatetime, uuid FROM crm_calls WHERE crm_calls.phone LIKE '%{$phone}%' ORDER BY createdon DESC";
//                    print_r($q); die();
            $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
            return json_encode(['status' => 'ok', 'data' => $data]);
        }
        return json_encode(['status' => 'ok', 'data' => []]);
    }

}

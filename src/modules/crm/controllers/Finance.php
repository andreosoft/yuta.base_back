<?php

namespace modules\crm\controllers;


class Finance extends \base\rest\RestController {

    public $baseModel = '\modules\crm\models\Finance';

    public function preAction($action) {
        parent::preAction($action);
        if (\A::$app->user()->role < 1) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You are forbidden!';
            exit();
        }
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
        LEFT JOIN `crm_contacts` ON crm_contacts.id = $table_name.contact_id
        LEFT JOIN `crm_deals` ON crm_deals.id = $table_name.deal_id
        LEFT JOIN `users` ON users.id = $table_name.manager_id $f_str";
        $q = "SELECT COUNT(*) $q_body";
        $pager['count'] = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC)[0]['COUNT(*)'];
        $q = "SELECT 
        $table_name.* , 
        crm_contacts.name AS contact,
        crm_deals.name AS deal,
        users.name AS manager 
        $q_body ORDER BY $sort LIMIT {$pager['limit']} OFFSET {$pager['offset']}";
        $data = \A::$app->db()->query($q)->fetchAll(\PDO::FETCH_ASSOC);
        return json_encode(['status' => 'ok', 'data' => $data, 'pager' => $pager]);
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
                if ($key == 'contact') {
                    $f_ar[] = "crm_contacts.name LIKE '%$val%'";
                }
            }
        }
        return $f_ar;
    }
}
